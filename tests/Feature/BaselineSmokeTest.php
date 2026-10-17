<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Attendance;
use App\Models\Church;
use App\Models\Donation;
use App\Exports\UsersExport;
use App\Imports\AttendanceImport;
use App\Imports\UsersImport;
use App\Console\Commands\GenerateSitemap;
use App\Notifications\SendDeviceNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Mail\Mailer;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Tests\TestCase;

/**
 * Smoke checks for the disposable, seeded baseline database.
 *
 * Set DB_DATABASE and BASELINE_SMOKE_DATABASE to the same
 * churchcms_baseline_* schema before running this suite. These checks must
 * never run against the local churchcms schema or production data. The two
 * tests that exercise writes explicitly roll them back.
 */
class BaselineSmokeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $expectedDatabase = env('BASELINE_SMOKE_DATABASE', 'churchcms_baseline_20261015');

        $this->assertMatchesRegularExpression('/^churchcms_baseline_[a-zA-Z0-9_]+$/', $expectedDatabase);
        $this->assertSame($expectedDatabase, config('database.connections.mysql.database'));
        $this->assertNotSame('churchcms', $expectedDatabase);
    }

    public function test_public_homepage_renders(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_guest_registration_form_renders(): void
    {
        $this->get('/guest/register')->assertOk();
    }

    public function test_invalid_guest_registration_is_rejected_without_creating_an_account(): void
    {
        $before = User::count();

        $this->from('/guest/register')
            ->post('/guest/register', [])
            ->assertRedirect('/guest/register')
            ->assertSessionHasErrors(['firstname', 'gender', 'date_of_birth', 'mobile_no', 'email', 'password']);

        $this->assertSame($before, User::count());
    }

    public function test_guest_registration_creates_a_guest_profile_in_a_rolled_back_transaction(): void
    {
        $email = 'baseline-' . Str::uuid() . '@example.test';
        $mobile = (string) random_int(2000000000, 9999999999);
        $church = DB::table('church')->first();

        DB::beginTransaction();

        try {
            $this->post('/guest/register', [
                'firstname' => 'Baseline',
                'lastname' => 'Guest',
                'gender' => 'female',
                'date_of_birth' => '2000-05-10',
                'mobile_no' => $mobile,
                'email' => $email,
                'password' => 'Baseline-test-Password-2026',
                'password_confirmation' => 'Baseline-test-Password-2026',
            ])->assertRedirect(route('web.prayer'));

            $user = User::where('email', $email)->firstOrFail();
            $this->assertSame(5, (int) $user->usergroup_id);
            $this->assertSame((int) $church->id, (int) $user->church_id);
            $this->assertSame('guest', $user->userprofile->membership_type);
        } finally {
            DB::rollBack();
        }
    }

    public function test_admin_member_list_requires_authentication(): void
    {
        $this->get('/admin/members')->assertRedirect();
    }

    public function test_admin_member_page_renders_for_an_authorized_admin(): void
    {
        $admin = User::where('email', 'baseline-admin@example.test')->firstOrFail();

        $this->actingAs($admin)->get('/admin/members')->assertOk();
    }

    public function test_member_role_cannot_open_admin_member_management(): void
    {
        $member = User::where('usergroup_id', 5)->firstOrFail();

        $this->actingAs($member)->get('/admin/members')->assertForbidden();
    }

    public function test_member_role_can_open_member_home(): void
    {
        $member = User::where('usergroup_id', 5)->firstOrFail();

        $this->actingAs($member)->get('/member/home')->assertOk();
    }

    public function test_device_logout_api_requires_authentication(): void
    {
        $this->postJson('/api/logout/devices')->assertUnauthorized();
    }

    public function test_push_notification_payload_is_constructed_without_sending(): void
    {
        $payload = (new SendDeviceNotification([
            'id' => 17,
            'type' => 'Event reminder',
            'message' => 'A sample reminder',
        ], 'synthetic-device-token'))->toFcmHttpV1(new User());

        $this->assertSame('synthetic-device-token', $payload['token']);
        $this->assertSame('Event reminder', $payload['notification']['title']);
        $this->assertSame('17', $payload['data']['id']);
        $this->assertArrayHasKey('apns', $payload);
        $this->assertArrayHasKey('android', $payload);
    }

    public function test_church_mailing_list_mailer_uses_symfony_smtp_without_sending(): void
    {
        $mailer = app()->makeWith('emails.maillist', [
            'smtp_host' => 'smtp.example.test',
            'smtp_port' => 587,
            'smtp_username' => 'synthetic-user',
            'smtp_password' => 'synthetic-password',
            'smtp_encryption' => 'tls',
            'from_email' => 'church@example.test',
            'from_name' => 'Synthetic Church',
        ]);

        $this->assertInstanceOf(Mailer::class, $mailer);
        $this->assertInstanceOf(EsmtpTransport::class, $mailer->getSymfonyTransport());
    }

    public function test_sitemap_contains_only_known_public_routes_and_is_valid_xml(): void
    {
        $xml = (new GenerateSitemap())->renderXml();
        $document = new \DOMDocument();

        $this->assertTrue($document->loadXML($xml));
        $this->assertSame('urlset', $document->documentElement->localName);
        $this->assertSame(9, $document->getElementsByTagName('url')->length);
        $this->assertSame(9, $document->getElementsByTagName('loc')->length);
        $this->assertSame(route('web.home'), $document->getElementsByTagName('loc')->item(0)->textContent);
    }

    public function test_member_export_has_expected_headings_and_stays_in_the_admins_church(): void
    {
        $admin = User::where('email', 'baseline-admin@example.test')->firstOrFail();
        $this->actingAs($admin);

        $export = new UsersExport();
        $members = $export->collection();

        $this->assertSame([
            'name', 'firstname', 'lastname', 'gender', 'date_of_birth', 'profession',
            'address', 'city', 'state', 'country', 'pincode', 'mobile_no', 'email',
        ], $export->headings());
        $this->assertGreaterThan(0, $members->count());
        $this->assertTrue($members->every(static fn (User $member) =>
            (int) $member->church_id === (int) $admin->church_id && (int) $member->usergroup_id === 5
        ));
    }

    public function test_member_export_and_admin_donation_list_exclude_another_church(): void
    {
        $admin = User::where('email', 'baseline-admin@example.test')->firstOrFail();
        $slug = 'baseline-isolation-' . Str::lower(Str::random(10));

        DB::beginTransaction();

        try {
            $otherChurch = Church::create([
                'name' => 'Isolated Baseline Church',
                'address' => 'Synthetic only',
                'pincode' => '00000',
                'slug' => $slug,
                'status' => 1,
            ]);
            $otherMember = User::create([
                'church_id' => $otherChurch->id,
                'usergroup_id' => 5,
                'name' => $slug,
                'email' => $slug . '@example.test',
                'password' => bcrypt('synthetic-password'),
                'mobile_no' => (string) random_int(2000000000, 9999999999),
            ]);
            $otherDonation = Donation::create([
                'church_id' => $otherChurch->id,
                'user_id' => $otherMember->id,
                'amount' => '999999.00',
                'currency' => 'KES',
                'category' => 'Synthetic isolation check',
                'method' => 'cash',
                'status' => 'completed',
                'uuid' => Str::uuid()->toString(),
            ]);

            $this->actingAs($admin);
            $exportedIds = (new UsersExport())->collection()->pluck('id')->all();
            $this->assertNotContains($otherMember->id, $exportedIds);

            $this->getJson('/admin/donation/list')
                ->assertOk()
                ->assertJsonMissing(['id' => $otherDonation->id]);
        } finally {
            DB::rollBack();
        }
    }

    public function test_attendance_import_marks_a_matching_sample_attendee_present_inside_a_rolled_back_transaction(): void
    {
        $attendance = Attendance::firstOrFail();
        $member = User::findOrFail($attendance->user_id);
        $admin = User::where('email', 'baseline-admin@example.test')->firstOrFail();

        DB::beginTransaction();

        try {
            $attendance->is_present = 0;
            $attendance->save();

            $this->actingAs($admin);
            (new AttendanceImport($attendance->entity_id))->collection(new Collection([
                ['user_mobile' => $member->mobile_no],
            ]));

            $this->assertDatabaseHas('attendances', [
                'id' => $attendance->id,
                'is_present' => 1,
            ]);
        } finally {
            DB::rollBack();
        }
    }

    public function test_member_import_creates_valid_member_and_reports_skipped_rows_inside_a_rolled_back_transaction(): void
    {
        $admin = User::where('email', 'baseline-admin@example.test')->firstOrFail();
        $existing = User::where('church_id', $admin->church_id)->whereNotNull('mobile_no')->firstOrFail();
        $mobile = (string) random_int(2000000000, 9999999999);
        $import = new UsersImport();

        DB::beginTransaction();

        try {
            $this->actingAs($admin);
            $import->collection(new Collection([
                ['firstname' => 'Imported', 'lastname' => 'Baseline', 'gender' => 'female', 'mobile_no' => $mobile, 'membership_type' => 'member'],
                ['firstname' => 'Duplicate', 'mobile_no' => $existing->mobile_no],
                ['firstname' => '', 'mobile_no' => ''],
            ]));

            $this->assertSame(1, $import->insertedCount());
            $this->assertSame(2, $import->skippedCount());
            $member = User::where('church_id', $admin->church_id)->where('mobile_no', $mobile)->firstOrFail();
            $this->assertSame(5, (int) $member->usergroup_id);
            $this->assertSame('Imported', $member->userprofile->firstname);
            $this->assertSame('member', $member->userprofile->membership_type);
        } finally {
            DB::rollBack();
        }
    }

    public function test_baseline_contains_expected_representative_domain_data(): void
    {
        $this->assertDatabaseHas('users', ['email' => 'baseline-admin@example.test']);
        $this->assertDatabaseCount('event_attendance_sessions', 1);
        $this->assertDatabaseCount('event_attendees', 1);
        $this->assertDatabaseCount('donations', 1);
        $this->assertDatabaseCount('migrations', 96);
    }

    public function test_payment_verification_requires_provider_amount_and_currency_to_match(): void
    {
        $paystack = new \App\Services\Payment\PaystackService();
        $paystackResult = ['status' => true, 'data' => ['status' => 'success', 'amount' => 12500, 'currency' => 'KES']];
        $this->assertTrue($paystack->matchesPayment($paystackResult, 125, 'KES'));
        $this->assertFalse($paystack->matchesPayment($paystackResult, 1, 'KES'));
        $this->assertFalse($paystack->matchesPayment($paystackResult, 125, 'USD'));

        $flutterwave = new \App\Services\Payment\FlutterwaveService();
        $flutterwaveResult = ['status' => 'success', 'data' => ['status' => 'successful', 'amount' => 125, 'currency' => 'KES']];
        $this->assertTrue($flutterwave->matchesPayment($flutterwaveResult, 125, 'KES'));
        $this->assertFalse($flutterwave->matchesPayment($flutterwaveResult, 124, 'KES'));

        $stripe = new \App\Services\Payment\StripeService();
        $stripeResult = ['status' => 'succeeded', 'amount_received' => 12500, 'currency' => 'kes'];
        $this->assertTrue($stripe->matchesPayment($stripeResult, 125, 'KES'));
        $this->assertFalse($stripe->matchesPayment($stripeResult, 125, 'USD'));
    }

    public function test_installer_refuses_to_rerun_without_writing_sensitive_payload_to_a_log(): void
    {
        $churchCount = Church::count();
        $secret = 'synthetic-installer-secret-' . Str::random(16);
        $payload = json_encode([
            'church_name' => 'Must Not Be Created',
            'church_email' => 'church@example.test',
            'admin_email' => 'installer@example.test',
            'admin_password' => $secret,
        ], JSON_THROW_ON_ERROR);

        $this->artisan('church:install-data', ['data' => $payload])->assertExitCode(1);

        $this->assertSame($churchCount, Church::count());
        $this->assertFileExists(storage_path('installed'));
        $logPath = storage_path('logs/installer.log');
        if (is_file($logPath)) {
            $this->assertStringNotContainsString($secret, file_get_contents($logPath));
        }
    }

    public function test_verified_donation_reference_is_idempotent_and_rejects_amount_reuse(): void
    {
        $admin = User::where('email', 'baseline-admin@example.test')->firstOrFail();
        $reference = 'baseline-payment-' . Str::uuid();
        $attributes = [
            'church_id' => $admin->church_id,
            'user_id' => $admin->id,
            'amount' => '25.00',
            'currency' => 'KES',
            'category' => 'offering',
            'method' => 'paystack',
            'gateway_ref' => $reference,
            'status' => 'completed',
            'uuid' => Str::uuid()->toString(),
            'donated_at' => now(),
        ];

        DB::beginTransaction();

        try {
            $recorder = app(\App\Services\Payment\VerifiedDonationRecorder::class);
            $first = $recorder->record($attributes);
            $retry = $recorder->record($attributes);

            $this->assertSame($first->id, $retry->id);
            $this->assertSame(1, Donation::where('gateway_ref', $reference)->count());
            $this->expectException(\RuntimeException::class);
            $recorder->record(array_merge($attributes, ['amount' => '26.00']));
        } finally {
            DB::rollBack();
        }
    }
}

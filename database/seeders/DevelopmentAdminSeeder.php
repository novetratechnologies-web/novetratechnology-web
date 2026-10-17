<?php

namespace Database\Seeders;

use App\Models\Church;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use RuntimeException;

/**
 * Creates one explicitly configured local development church administrator.
 *
 * This seeder is intentionally not called by DatabaseSeeder. Configure its
 * DEV_ADMIN_* values in the local environment, then run it explicitly.
 */
class DevelopmentAdminSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('DevelopmentAdminSeeder may only run in local or testing environments.');
        }

        $name = trim((string) env('DEV_ADMIN_NAME'));
        $email = strtolower(trim((string) env('DEV_ADMIN_EMAIL')));
        $mobile = trim((string) env('DEV_ADMIN_MOBILE'));
        $mobileCountryCode = trim((string) (env('DEV_ADMIN_MOBILE_COUNTRY_CODE') ?: '+254'));
        $password = (string) env('DEV_ADMIN_PASSWORD');
        $churchId = filter_var(env('DEV_ADMIN_CHURCH_ID'), FILTER_VALIDATE_INT);

        if ($name === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL) || $mobile === '' || strlen($password) < 16 || ! $churchId) {
            throw new InvalidArgumentException('Set DEV_ADMIN_NAME, DEV_ADMIN_EMAIL, DEV_ADMIN_MOBILE, DEV_ADMIN_PASSWORD (16+ characters), and DEV_ADMIN_CHURCH_ID before seeding.');
        }

        $church = Church::query()->where('status', 1)->find($churchId);
        if (! $church) {
            throw new InvalidArgumentException("DEV_ADMIN_CHURCH_ID {$churchId} must identify an active church.");
        }

        if (! DB::table('user_group')->where('id', 3)->where('name', 'ChurchAdmin')->exists()) {
            throw new RuntimeException('The ChurchAdmin user group is missing. Run the core database seeder first.');
        }

        $existing = User::withTrashed()->where('email', $email)->first();
        if ($existing) {
            if ((int) $existing->church_id !== (int) $church->id || (int) $existing->usergroup_id !== 3 || $existing->trashed()) {
                throw new RuntimeException('The configured development admin email already belongs to a different or deleted account; no account was changed.');
            }

            $this->command?->info("Development admin {$email} already exists; credentials and account data were left unchanged.");
            return;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'mobile_no' => $mobile,
            'mobile_country_code' => $mobileCountryCode,
            'password' => Hash::make($password),
            'church_id' => $church->id,
            'usergroup_id' => 3,
            'email_verified' => true,
            'email_verified_at' => now(),
        ]);

        $this->command?->info("Created local Church Admin account #{$user->id} for church #{$church->id} ({$church->name}).");
    }
}

<?php

namespace App\Imports;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\User;
use App\Traits\RegisterUser;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    use RegisterUser;

    private int $insertedCount = 0;
    private int $skippedCount = 0;

    public function collection(Collection $rows): void
    {
        $admin = Auth::user();
        abort_unless($admin && $admin->church_id, 403, 'A church administrator must be signed in to import members.');

        foreach ($rows as $row) {
            $mobile = trim((string) ($row['mobile_no'] ?? ''));
            $firstname = trim((string) ($row['firstname'] ?? ''));

            if ($mobile === '' || $firstname === '') {
                $this->skippedCount++;
                continue;
            }

            $alreadyExists = User::query()
                ->where('church_id', $admin->church_id)
                ->where('mobile_no', $mobile)
                ->exists();

            if ($alreadyExists) {
                $this->skippedCount++;
                continue;
            }

            $country = $this->findLocation(Country::class, $row['country'] ?? null);
            $state = $this->findLocation(State::class, $row['state'] ?? null);
            $city = $this->findLocation(City::class, $row['city'] ?? null);
            $membershipType = strtolower(trim((string) ($row['membership_type'] ?? 'member')));
            if (!in_array($membershipType, ['member', 'guest'], true)) {
                $membershipType = 'member';
            }

            $email = trim((string) ($row['email'] ?? ''));
            $refName = $this->nullableString($row['ref_name'] ?? null);
            if ($refName !== null && !User::query()->where('church_id', $admin->church_id)->where('name', $refName)->exists()) {
                $refName = null;
            }
            $name = 'member-' . Str::lower(Str::random(24));
            $data = (object) [
                'ref_name' => $refName,
                'name' => $name,
                'firstname' => $firstname,
                'lastname' => $this->nullableString($row['lastname'] ?? null),
                'birth_firstname' => $this->nullableString($row['birth_firstname'] ?? null),
                'birth_lastname' => $this->nullableString($row['birth_lastname'] ?? null),
                'gender' => $this->nullableString($row['gender'] ?? null),
                'date_of_birth' => $this->parseDate($row['date_of_birth'] ?? null),
                'profession' => $this->nullableString($row['occupation'] ?? $row['profession'] ?? null),
                'sub_occupation' => $this->nullableString($row['sub_category'] ?? $row['sub-category'] ?? null),
                'address' => $this->nullableString($row['address'] ?? null),
                'city_id' => $city?->id,
                'state_id' => $state?->id,
                'country_id' => $country?->id,
                'pincode' => $this->nullableString($row['pincode'] ?? null),
                'mobile_no' => $mobile,
                'email' => $email !== '' ? $email : null,
                'aadhar_number' => $this->nullableString($row['aadhar_number'] ?? null),
                'membership_type' => $membershipType,
                'family' => $this->nullableString($row['family'] ?? null),
                'marriage_status' => $this->nullableString($row['marriage_status'] ?? null),
                'marriage_start_date' => $this->parseDate($row['marriage_start_date'] ?? null),
                'marriage_end_date' => null,
                'relation' => $this->nullableString($row['relation'] ?? null),
                'notes' => $this->nullableString($row['notes'] ?? null),
                'status' => 'active',
                'was_baptized' => null,
                'baptism_date' => null,
                'facebook_id' => null,
                'description' => null,
            ];

            if ($this->CreateUser($data, (int) $admin->church_id, '', 5)) {
                $this->insertedCount++;
            } else {
                $this->skippedCount++;
            }
        }

        session()->put('insertedcount', $this->insertedCount);
        session()->put('skippedcount', $this->skippedCount);
    }

    public function insertedCount(): int
    {
        return $this->insertedCount;
    }

    public function skippedCount(): int
    {
        return $this->skippedCount;
    }

    private function findLocation(string $model, mixed $value): ?object
    {
        $value = $this->nullableString($value);
        return $value === null ? null : $model::query()->where('name', 'LIKE', '%' . addcslashes($value, '%_\\') . '%')->first();
    }

    private function nullableString(mixed $value): ?string
    {
        $value = trim((string) ($value ?? ''));
        return $value === '' ? null : $value;
    }

    private function parseDate(mixed $value): ?string
    {
        $value = $this->nullableString($value);
        if ($value === null) {
            return null;
        }

        try {
            return Carbon::parse($value)->toDateString();
        } catch (\Throwable) {
            return null;
        }
    }
}

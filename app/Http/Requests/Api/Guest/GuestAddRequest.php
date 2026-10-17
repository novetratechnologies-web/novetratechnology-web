<?php

namespace App\Http\Requests\Api\Guest;

use App\Models\ChurchBranch;
use App\Models\Country;
use App\Models\User;
use App\Support\CountryPhoneCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class GuestAddRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $prefix = ltrim((string) $this->input('mobile_country_code', '254'), '+');
        $this->merge(['mobile_country_code' => $prefix]);

        // Older app builds did not submit a branch. Keep them registering at IGM HQ.
        if (!$this->filled('branch_id') && $this->filled('church_id')) {
            $hqId = ChurchBranch::query()->forChurch((int) $this->input('church_id'))
                ->where('is_headquarters', true)->where('is_active', true)->value('id');
            if ($hqId) {
                $this->merge(['branch_id' => $hqId]);
            }
        }
    }

    public function rules(): array
    {
        $churchId = (int) $this->input('church_id');

        return [
            'church_id' => ['required', 'integer', Rule::exists('church', 'id')],
            'branch_id' => ['required', 'integer', Rule::exists('church_branches', 'id')->where(fn ($query) => $query->where('church_id', $churchId)->where('is_active', true))],
            'firstname' => ['required', 'string', 'max:50', 'regex:/^[\pL\pM][\pL\pM .\'-]*$/u'],
            'lastname' => ['nullable', 'string', 'max:50', 'regex:/^[\pL\pM][\pL\pM .\'-]*$/u'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date', 'before:today', 'after:1920-01-01'],
            'mobile_no' => ['required', 'string', 'max:30', 'regex:/^[0-9+().\s-]+$/'],
            'mobile_country_code' => ['required', Rule::in(Country::where('status', 1)->whereNotNull('tel_prefix')->get(['short_name', 'tel_prefix'])->map(fn ($country) => CountryPhoneCode::for($country))->unique()->all())],
            'email' => ['nullable', 'email', 'max:150', 'unique:users,email'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->has('mobile_no') || $validator->errors()->has('mobile_country_code')) {
                return;
            }

            $prefix = (string) $this->input('mobile_country_code');
            $country = Country::where('status', 1)->whereNotNull('tel_prefix')->get(['short_name', 'tel_prefix'])
                ->first(fn ($country) => CountryPhoneCode::for($country) === $prefix);
            if (!$country || !$country->short_name) {
                $validator->errors()->add('mobile_country_code', 'Choose a supported country calling code.');
                return;
            }

            try {
                $util = PhoneNumberUtil::getInstance();
                $phone = $util->parse((string) $this->input('mobile_no'), strtoupper($country->short_name));
            } catch (NumberParseException $exception) {
                $validator->errors()->add('mobile_no', 'Enter a valid phone number for the selected country.');
                return;
            }

            if (!$util->isValidNumberForRegion($phone, strtoupper($country->short_name)) || (string) $phone->getCountryCode() !== $prefix) {
                $validator->errors()->add('mobile_no', 'Enter a valid phone number for the selected country.');
                return;
            }

            $national = preg_replace('/\D+/', '', $util->format($phone, PhoneNumberFormat::NATIONAL));
            $duplicate = User::query()->where('mobile_no', $national)->where(function ($query) use ($prefix) {
                $query->where('mobile_country_code', $prefix)->orWhere('mobile_country_code', '+' . $prefix);
            })->exists();
            if ($duplicate) {
                $validator->errors()->add('mobile_no', 'This phone number already has an account. Please sign in or use another number.');
                return;
            }

            $this->merge([
                'mobile_no' => $national,
                'mobile_country_code' => $prefix,
            ]);
        });
    }

    public function messages(): array
    {
        return [
            'church_id.exists' => 'Choose a valid church.',
            'branch_id.required' => 'Choose the church branch you attend.',
            'branch_id.exists' => 'Choose an active branch belonging to this church.',
            'mobile_country_code.in' => 'Choose a supported country calling code.',
        ];
    }
}

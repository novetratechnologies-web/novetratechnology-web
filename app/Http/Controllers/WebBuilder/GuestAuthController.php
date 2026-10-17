<?php

namespace App\Http\Controllers\WebBuilder;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ChurchBranch;
use App\Models\User;
use App\Rules\ValidRecaptcha;
use App\Support\CountryPhoneCode;
use App\Traits\RegisterUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class GuestAuthController extends Controller
{
    use RegisterUser;

    public function showRegister(Request $request)
    {

        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }


        if (config('settings.guest_registration', 1) != 1) {
            return view('theme::guest_register', ['blocked' => true, 'countries' => collect(), 'branches' => collect(), 'defaultCountryCode' => '254', 'defaultBranchId' => null]);
        }

        $countries = Country::where('status', 1)
            ->whereNotNull('tel_prefix')
            ->orderByRaw("CASE WHEN tel_prefix = '254' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get(['id', 'name', 'short_name', 'tel_prefix']);
        $countries->each(fn ($country) => $country->tel_prefix = CountryPhoneCode::for($country));

        $churchId = $request->attributes->get('_church')?->id;
        $branches = ChurchBranch::query()->forChurch($churchId)->active()->orderByDesc('is_headquarters')->orderBy('name')->get();
        $defaultBranchId = $branches->firstWhere('is_headquarters', true)?->id ?? $branches->first()?->id;

        return view('theme::guest_register', [
            'blocked' => false,
            'countries' => $countries,
            'branches' => $branches,
            'defaultCountryCode' => $countries->firstWhere('short_name', 'KE')?->tel_prefix ?? '254',
            'defaultBranchId' => $defaultBranchId,
        ]);
    }

    public function register(Request $request)
    {



        if (Auth::check()) {
            return $this->redirectAuthenticatedUser();
        }

        if (config('settings.guest_registration', 1) != 1) {
            return redirect()->route('web.guest.register');
        }

        $church = $request->attributes->get('_church');
        $churchId = optional($church)->id;
        $countries = Country::where('status', 1)->whereNotNull('tel_prefix')->get(['name', 'short_name', 'tel_prefix']);
        $countries->each(fn ($country) => $country->tel_prefix = CountryPhoneCode::for($country));
        $countryPrefixes = $countries->pluck('tel_prefix')->map(fn ($code) => ltrim((string) $code, '+'))->all();
        $rules = [
            'firstname'     => ['required', 'string', 'max:50', 'regex:/^[\pL\pM][\pL\pM .\'-]*$/u'],
            'lastname'      => ['nullable', 'string', 'max:50', 'regex:/^[\pL\pM][\pL\pM .\'-]*$/u'],
            'gender'        => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today|after:1920-01-01',
            'mobile_no'     => ['required', 'string', 'max:30', 'regex:/^[0-9+().\s-]+$/'],
            'mobile_country_code' => ['required', Rule::in($countryPrefixes)],
            'email'         => 'required|email|max:150|unique:users,email',
            'password'      => 'required|string|min:8|confirmed',
            'branch_id'     => ['required', 'integer', Rule::exists('church_branches', 'id')->where(fn ($query) => $query->where('church_id', $churchId)->where('is_active', true))],
        ];

        if (config('settings.guest_register_captcha_status') == "1") {
            $rules['g-recaptcha-response'] = ['required', new ValidRecaptcha()];
        }

        $validated = $request->validate($rules, [
            'mobile_country_code.in' => 'Choose a valid country calling code.',
            'branch_id.required' => 'Choose the IGM branch you attend.',
            'branch_id.exists' => 'Choose an active branch from this church.',
        ]);

        $country = $countries->first(fn ($row) => ltrim((string) $row->tel_prefix, '+') === ltrim((string) $validated['mobile_country_code'], '+'));
        if (!$country) {
            throw ValidationException::withMessages(['mobile_country_code' => 'Choose a valid country calling code.']);
        }

        $phoneUtil = PhoneNumberUtil::getInstance();
        $region = strtoupper((string) $country->short_name);
        try {
            $phone = $phoneUtil->parse($validated['mobile_no'], $region);
        } catch (\libphonenumber\NumberParseException $exception) {
            throw ValidationException::withMessages(['mobile_no' => 'Enter a valid phone number for the selected country.']);
        }

        if (!$phoneUtil->isValidNumberForRegion($phone, $region) || (string) $phone->getCountryCode() !== ltrim((string) $country->tel_prefix, '+')) {
            throw ValidationException::withMessages(['mobile_no' => 'Enter a valid phone number for the selected country.']);
        }

        $nationalNumber = preg_replace('/\D+/', '', $phoneUtil->format($phone, PhoneNumberFormat::NATIONAL));
        $countryCode = (string) $phone->getCountryCode();
        $duplicate = User::query()
            ->where('mobile_no', $nationalNumber)
            ->where(function ($query) use ($countryCode) {
                $query->where('mobile_country_code', $countryCode)
                    ->orWhere('mobile_country_code', '+' . $countryCode);
            })
            ->exists();
        if ($duplicate) {
            throw ValidationException::withMessages(['mobile_no' => 'An account already uses this phone number. Try signing in or use another number.']);
        }

        $data = (object) [
            'name'           => trim($request->firstname . ' ' . ($request->lastname ?? '')),
            'firstname'      => $request->firstname,
            'lastname'       => $request->lastname,
            'gender'         => $request->gender,
            'date_of_birth'  => $request->date_of_birth,
            'mobile_no'      => $nationalNumber,
            'email'          => $request->email,
            'ref_name'       => null,
            'was_baptized'   => null,
            'baptism_date'   => null,
            'profession'     => null,
            'sub_occupation' => null,
            'address'        => null,
            'city_id'        => null,
            'state_id'       => null,
            'country_id'     => null,
            'pincode'        => null,
            'aadhar_number'  => null,
            'notes'          => null,
            'mobile_country_code' => $countryCode,
        ];

        $user = $this->createGuest($data, optional($church)->id ?? 0, '', 5);

        if (!$user) {
            return back()->withErrors(['error' => 'Registration failed. Please try again.'])->withInput();
        }

        $user->password = bcrypt($request->password);
        $user->branch_id = (int) $validated['branch_id'];
        $user->save();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('web.prayer'))
            ->with('success', 'Welcome! You are now registered. You can now submit prayer requests, help requests, and comments.');
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('web.home');
        }

        return view('theme::guest_login', [
            'guestLoginBlocked'  => config('settings.guest_login', 1) != 1,
            'memberLoginBlocked' => config('settings.member_web_login', 1) != 1,
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $candidate = User::with('userprofile')->where('email', $credentials['email'])->first();

        if (!$candidate || (int) $candidate->usergroup_id !== 5) {
            return back()->withErrors([
                'email' => 'Use the administration sign-in for staff accounts.',
            ])->withInput($request->only('email', 'remember'));
        }

        $membershipType = optional($candidate->userprofile)->membership_type;
        $loginAllowed = $membershipType === 'guest'
            ? config('settings.guest_login', 1) == 1
            : config('settings.member_web_login', 1) == 1;

        if (!$loginAllowed) {
            return back()->withErrors([
                'email' => 'Website sign-in is currently disabled for this account type. Please contact the church office.',
            ])->withInput($request->only('email', 'remember'));
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $status = Auth::user()->userprofile?->status ?? 'active';

            if (in_array($status, ['inactive', 'removed', 'exit'], true)) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your account is inactive. Please contact the church office.',
                ])->withInput($request->only('email', 'remember'));
            }

            $request->session()->regenerate();
            return redirect()->intended(route('member.home'));
        }

        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.home');
    }

    private function redirectAuthenticatedUser()
    {
        $user = Auth::user();

        if (in_array((int) $user->usergroup_id, [1, 2, 3, 4], true)) {
            return redirect('/admin/dashboard');
        }

        return (int) $user->usergroup_id === 5
            ? redirect()->route('member.home')
            : redirect()->route('web.home');
    }
}

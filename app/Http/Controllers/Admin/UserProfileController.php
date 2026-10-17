<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\Userprofile;
use App\Traits\LogActivity;
use App\Models\Country;
use App\Traits\Common;
use App\Models\State;
use App\Models\City;
use App\Models\User;
use Exception;
use Hash;
use Log;

/**
 * UserProfileController
 *
 * Manages user profile information and personal details.
 * Handles profile updates, avatar management, password changes, and profile-specific operations.
 * Supports geographic location data (country, state, city selection).
 *
 * @package App\Http\Controllers\Admin
 * @uses Common Trait for helper functions
 * @uses LogActivity Trait for audit logging
 */
class UserProfileController extends Controller
{
    use Common;
    use LogActivity;

    public function getavatar()
    {
        $userprofile = Userprofile::where('user_id', Auth::id())->first();

        return [
            'avatar' => $userprofile?->AvatarPath ?: asset('uploads/user/avatar/default-user.jpg'),
            'id' => $userprofile?->id,
        ];
    }

    private function getOrCreateCurrentProfile(): Userprofile
    {
        $userprofile = Userprofile::where('user_id', Auth::id())->first();
        if ($userprofile) {
            return $userprofile;
        }

        $userprofile = new Userprofile();
        $userprofile->user_id = Auth::id();
        $userprofile->church_id = Auth::user()?->church_id;
        $userprofile->gender = null;
        $userprofile->save();

        return $userprofile;
    }

    public function states($countryId)
    {
        $states = State::query()
            ->where('country_id', $countryId)
            ->where('status', '1')
            ->orderBy('name')
            ->get(['id', 'country_id', 'name'])
            ->map(fn ($state) => ['id' => $state->id, 'country_id' => $state->country_id, 'name' => $state->name])
            ->values();

        return response()->json($states);
    }

    public function cities($stateId)
    {
        $cities = City::query()
            ->where('state_id', $stateId)
            ->where('status', '1')
            ->orderBy('name')
            ->get(['id', 'state_id', 'name'])
            ->map(fn ($city) => ['id' => $city->id, 'state_id' => $city->state_id, 'name' => $city->name])
            ->values();

        return response()->json($cities);
    }

    public function changeavatar(Request $request)
    {
        return view('admin/changeavatar');
    }

    /**
     * Updates the avatar image for specified user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updatechangeavatar(Request $request)
    {
        $validated = $request->validate([
            'avatar' => ['required', 'file', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $userprofile = $this->getOrCreateCurrentProfile();

        try {
            $path = \Storage::disk('public')->putFile('uploads/avatars', $validated['avatar']);
            if (! $path) {
                throw new \RuntimeException('The avatar file was not written to public storage.');
            }

            $userprofile->avatar = $path;
            $userprofile->save();

            try {
                $this->doActivityLog(
                    $userprofile,
                    Auth::user(),
                    ['ip' => $this->getRequestIP(), 'details' => $request->userAgent()],
                    LOGNAME_CHANGE_AVATAR,
                    'Changed Avatar'
                );
            } catch (Exception $logException) {
                Log::warning('Avatar updated but its activity log could not be written.', ['user_id' => Auth::id(), 'exception' => $logException->getMessage()]);
            }

            $response = [
                'message' => __('admin_userprofile.update_avatar'),
                'avatar' => $this->getFilePath($path),
            ];
            \Session::put('successmessage', $response['message']);

            return response()->json($response);
        } catch (Exception $exception) {
            Log::error('Admin profile photo update failed.', ['user_id' => Auth::id(), 'exception' => $exception->getMessage()]);

            return response()->json([
                'message' => 'The photo could not be saved. Please try again.',
            ], 500);
        }
    }

    public function ChangePassword()
    {
        return view('/admin/changepassword');
    }

    /**
     * Updates the password of the specified user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function updateChangePassword(ChangePasswordRequest $request)
    {
        try {
            $user = User::findOrFail(Auth::id());
            $user->password = Hash::make($request->newpassword);
            $user->save();

            try {
                $this->doActivityLog(
                    $user,
                    Auth::user(),
                    ['ip' => $this->getRequestIP(), 'details' => $request->userAgent()],
                    LOGNAME_CHANGE_PASSWORD,
                    'Changed Profile Password.'
                );
            } catch (Exception $logException) {
                Log::warning('Password changed but its activity log could not be written.', ['user_id' => Auth::id(), 'exception' => $logException->getMessage()]);
            }

            $message = __('admin_userprofile.password_update');
            $request->session()->flash('successmessage', $message);

            return response()->json(['message' => $message]);
        } catch (Exception $exception) {
            Log::error('Admin password update failed.', ['user_id' => Auth::id(), 'exception' => $exception->getMessage()]);

            return response()->json(['message' => 'Your password could not be updated. Please try again.'], 500);
        }
    }

    public function edit(Request $request)
    {
        return view('/admin/editprofile');
    }

     public function create()
    {
        $array=[];

        $userprofile = $this->getOrCreateCurrentProfile();
        $country = Country::query()
            ->where('status', '1')
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])
            ->values();

        $states = $userprofile->country_id
            ? State::query()->where('country_id', $userprofile->country_id)->where('status', '1')->orderBy('name')->get(['id', 'country_id', 'name'])->map(fn ($item) => ['id' => $item->id, 'country_id' => $item->country_id, 'name' => $item->name])->values()
            : collect();
        $cities = $userprofile->state_id
            ? City::query()->where('state_id', $userprofile->state_id)->where('status', '1')->orderBy('name')->get(['id', 'state_id', 'name'])->map(fn ($item) => ['id' => $item->id, 'state_id' => $item->state_id, 'name' => $item->name])->values()
            : collect();

        if($userprofile->firstname === null)
        {
            $array['firstname']='';
        }
        else
        {
            $array['firstname']=$userprofile->firstname;
        }

        if($userprofile->lastname === null)
        {
            $array['lastname']='';
        }
        else
        {
            $array['lastname']=$userprofile->lastname;
        }

        if($userprofile->birth_firstname === null)
        {
            $array['birth_firstname']='';
        }
        else
        {
            $array['birth_firstname']=$userprofile->birth_firstname;
        }

        if($userprofile->birth_lastname === null)
        {
            $array['birth_lastname']='';
        }
        else
        {
            $array['birth_lastname']=$userprofile->birth_lastname;
        }

        if($userprofile->gender === null)
        {
            $array['gender']='';
        }
        else
        {
            $array['gender']=$userprofile->gender;
        }

        if($userprofile->date_of_birth === null)
        {
            $array['date_of_birth']='';
        }
        else
        {
            $array['date_of_birth']=date('Y-m-d',strtotime($userprofile->date_of_birth));
        }

        if($userprofile->address === null)
        {
            $array['address']='';
        }
        else
        {
            $array['address']=$userprofile->address;
        }

        $array['country_id']    =   $userprofile->country_id;
        $array['state_id']      =   $userprofile->state_id;
        $array['city_id']       =   $userprofile->city_id;
        $array['pincode']       =   $userprofile->pincode;
        $array['countrylist']   =   $country;
        $array['statelist']     =   $userprofile->country_id ? [$userprofile->country_id => $states] : [];
        $array['citylist']      =   $userprofile->state_id ? [$userprofile->state_id => $cities] : [];

        return $array;
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'firstname' => ['nullable', 'string', 'max:255'],
            'lastname' => ['nullable', 'string', 'max:255'],
            'birth_firstname' => ['nullable', 'string', 'max:255'],
            'birth_lastname' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', Rule::in(['male', 'female', 'transgender'])],
            'date_of_birth' => ['nullable', 'date', 'before_or_equal:today'],
            'address' => ['nullable', 'string', 'max:2000'],
            'country_id' => ['nullable', 'integer', 'exists:countries,id'],
            'state_id' => [
                'nullable',
                'integer',
                Rule::exists('states', 'id')->where(fn ($query) => $query->where('country_id', $request->input('country_id'))),
            ],
            'city_id' => [
                'nullable',
                'integer',
                Rule::exists('cities', 'id')->where(fn ($query) => $query->where('state_id', $request->input('state_id'))),
            ],
            'pincode' => ['nullable', 'string', 'max:30'],
        ]);

        try {
            $userprofile = $this->getOrCreateCurrentProfile();
            $userprofile->fill($validated);
            $userprofile->save();

            try {
                $this->doActivityLog(
                    $userprofile,
                    Auth::user(),
                    ['ip' => $this->getRequestIP(), 'details' => $request->userAgent()],
                    LOGNAME_EDIT_USERPROFILE,
                    'Changed Profile'
                );
            } catch (Exception $logException) {
                Log::warning('Profile updated but its activity log could not be written.', ['user_id' => Auth::id(), 'exception' => $logException->getMessage()]);
            }

            return response()->json(['message' => 'Your profile has been updated.']);
        } catch (Exception $exception) {
            Log::error('Admin profile update failed.', ['user_id' => Auth::id(), 'exception' => $exception->getMessage()]);

            return response()->json(['message' => 'Your profile could not be saved. Please try again.'], 500);
        }
    }
}

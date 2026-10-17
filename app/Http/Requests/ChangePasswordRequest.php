<?php

namespace App\Http\Requests;
 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'oldpassword' => [
                'required',
                function ($attribute, $value, $fail) {
                    $user = User::find(Auth::id());
                    if (! $user || ! $user->password || ! Hash::check($value, $user->password)) {
                        $fail(__('userprofile.oldpassword_err'));
                    }
                },
            ],
            'newpassword' => ['required', 'string', 'min:8', 'different:oldpassword'],
            'confirmpassword' => ['required', 'same:newpassword'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {      

        return [
            'oldpassword.required' =>__('userprofile.oldpassword'), 
            'oldpassword.checkpassword' => __('userprofile.oldpassword_err'),
            'newpassword.required' =>__('userprofile.newpassword'),
            'newpassword.min' => __('userprofile.newpassword_min'),
            'newpassword.different' => 'Choose a new password that differs from your current password.',
            'confirmpassword.required' =>__('userprofile.confirmpassword'),
            'confirmpassword.same' =>__('userprofile.same_confirmpassword'),              
        ];
    }
}

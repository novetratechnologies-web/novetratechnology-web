<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;

/**
 * EmailVerificationController
 *
 * Handles user email verification process and token validation.
 * Marks users as email verified and redirects to appropriate pages.
 * Manages email verification tokens and timestamps for user accounts.
 *
 * @package App\Http\Controllers\Auth
 */
class EmailVerificationController extends Controller
{
  public function emailverification($token)
  {
    $check = User::where('email_verification_code', $token)->first();
    if (!is_null($check))
    {
      $user=User::where('id',$check->id)->first();
      if ($user->email_verified === 1)
      {
        if (!is_null(Auth::id()))
        {
          \Session::put('successmessage','E-mail Verified Successfully,Login Now');
          return in_array((int) Auth::user()->usergroup_id, [1, 2, 3, 4], true)
            ? redirect('/admin/dashboard')
            : redirect('/member/home');
        }
        \Session::put('successmessage','E-mail Verified Successfully,Login Now');
        return in_array((int) $user->usergroup_id, [1, 2, 3, 4], true)
          ? redirect()->route('admin.login')
          : redirect()->route('web.guest.login');
      }
      $user->email_verified = 1;
      $user->email_verified_at = Carbon::now();

      $user->save();

      if (Auth::check()) {
        return in_array((int) Auth::user()->usergroup_id, [1, 2, 3, 4], true)
          ? redirect('/admin/dashboard')
          : redirect('/member/home');
      }

      $loginRoute = in_array((int) $user->usergroup_id, [1, 2, 3, 4], true)
        ? 'admin.login'
        : 'web.guest.login';

      return redirect()->route($loginRoute)->with('success', 'Email verified. You can now sign in.');
    }
  }
}

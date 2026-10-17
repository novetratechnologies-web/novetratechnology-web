<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommunicationPreferenceController extends Controller
{
    public function edit()
    {
        $preference = auth()->user()->communicationPreference;

        return view('account.communication-preferences', compact('preference'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'email_opt_in' => ['nullable', 'boolean'],
            'sms_opt_in' => ['nullable', 'boolean'],
            'in_app_enabled' => ['nullable', 'boolean'],
        ]);

        $user = $request->user();
        $preference = $user->communicationPreference;
        $emailOptIn = $request->boolean('email_opt_in');
        $smsOptIn = $request->boolean('sms_opt_in');

        $user->communicationPreference()->updateOrCreate([], [
            'email_opt_in' => $emailOptIn,
            'email_consent_at' => $emailOptIn
                ? ($preference?->email_opt_in ? $preference->email_consent_at : now())
                : null,
            'email_consent_source' => $emailOptIn ? 'self_service' : null,
            'sms_opt_in' => $smsOptIn,
            'sms_consent_at' => $smsOptIn
                ? ($preference?->sms_opt_in ? $preference->sms_consent_at : now())
                : null,
            'sms_consent_source' => $smsOptIn ? 'self_service' : null,
            'in_app_enabled' => $request->boolean('in_app_enabled'),
        ]);

        return redirect()->route('communication-preferences.edit')
            ->with('successmessage', 'Your communication preferences have been saved.');
    }
}

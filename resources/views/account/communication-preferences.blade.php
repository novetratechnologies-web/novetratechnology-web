@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-2xl px-4 py-8">
    <div class="rounded-lg border bg-white p-6 shadow-sm">
        <h1 class="text-2xl font-semibold text-gray-800">Communication preferences</h1>
        <p class="mt-2 text-sm text-gray-600">Choose how your church may contact you. You can change these choices at any time.</p>

        @if (session('successmessage'))
            <div class="mt-4 rounded bg-green-100 px-4 py-3 text-green-800">{{ session('successmessage') }}</div>
        @endif
        @if ($errors->any())
            <div class="mt-4 rounded bg-red-100 px-4 py-3 text-red-800">Please review your choices and try again.</div>
        @endif

        <form method="POST" action="{{ route('communication-preferences.update') }}" class="mt-6 space-y-5">
            @csrf
            @method('PUT')
            <label class="flex items-start gap-3">
                <input type="checkbox" name="in_app_enabled" value="1" @checked(old('in_app_enabled', $preference?->in_app_enabled ?? true)) class="mt-1 rounded border-gray-300">
                <span><span class="block font-medium text-gray-800">In-app notifications</span><span class="text-sm text-gray-600">Service reminders, event updates, and church messages in your account.</span></span>
            </label>
            <label class="flex items-start gap-3">
                <input type="checkbox" name="email_opt_in" value="1" @checked(old('email_opt_in', $preference?->email_opt_in ?? false)) class="mt-1 rounded border-gray-300">
                <span><span class="block font-medium text-gray-800">Email</span><span class="text-sm text-gray-600">I agree to receive church service reminders, event updates, and direct messages by email at {{ auth()->user()->email ?: 'the email address on my account' }}.</span></span>
            </label>
            <label class="flex items-start gap-3">
                <input type="checkbox" name="sms_opt_in" value="1" @checked(old('sms_opt_in', $preference?->sms_opt_in ?? false)) class="mt-1 rounded border-gray-300">
                <span><span class="block font-medium text-gray-800">SMS</span><span class="text-sm text-gray-600">I agree to receive church service reminders, event updates, and direct messages by SMS at {{ auth()->user()->mobile_no ?: 'the phone number on my account' }}. Message and carrier charges may apply.</span></span>
            </label>
            <button type="submit" class="rounded bg-blue-700 px-4 py-2 font-semibold text-white hover:bg-blue-800">Save preferences</button>
        </form>
    </div>
</div>
@endsection

@extends('layouts.admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-profile.css') }}?v=3">
@endpush

@section('content')
    @include('admin.profile._settings_header', ['activeProfileTab' => 'security'])
    <section class="admin-profile-content-card">
        <div class="admin-profile-section-heading">
            <div><span class="admin-profile-section-icon"><i class="fa-solid fa-shield-halved"></i></span><div><h2>Password &amp; security</h2><p>Update your password to protect your admin account.</p></div></div>
            <span class="admin-profile-secure"><i class="fa-solid fa-lock"></i> Secure account settings</span>
        </div>
        <change-password url="{{ url('/') }}" dusk="change-password"></change-password>
    </section>
@endsection

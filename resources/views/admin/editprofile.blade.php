@extends('layouts.admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-profile.css') }}?v=3">
@endpush

@section('content')
    @include('admin.profile._settings_header', ['activeProfileTab' => 'details'])
        <section class="admin-profile-content-card">
            <div class="admin-profile-section-heading">
                <div><span class="admin-profile-section-icon"><i class="fa-regular fa-id-card"></i></span><div><h2>Personal details</h2><p>Keep your account information accurate and up to date.</p></div></div>
                <span class="admin-profile-secure"><i class="fa-solid fa-lock"></i> Private to your account</span>
            </div>
            <edit-profile url="{{ url('/') }}"></edit-profile>
        </section>
@endsection

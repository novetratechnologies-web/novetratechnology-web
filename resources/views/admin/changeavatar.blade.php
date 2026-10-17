@extends('layouts.admin.layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-profile.css') }}?v=3">
@endpush

@section('content')
    @include('admin.profile._settings_header', ['activeProfileTab' => 'photo'])
    <section class="admin-profile-content-card">
        <div class="admin-profile-section-heading">
            <div><span class="admin-profile-section-icon"><i class="fa-regular fa-image"></i></span><div><h2>Profile photo</h2><p>Choose a clear photo so your team can recognize you.</p></div></div>
            <span class="admin-profile-secure"><i class="fa-solid fa-lock"></i> Visible to authorized staff</span>
        </div>
        <change-avatar url="{{ url('/') }}"></change-avatar>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'My IGM Account')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/member-account.css') }}?v=1">
@endpush

@section('content')
@php
    $profile = $user->userprofile;
    $church = $user->church;
    $branch = $user->branch;
    $churchName = $church?->name ?: 'International Gospel Ministries';
    $branchName = $branch?->name;
    $churchAddress = trim(strip_tags((string) ($branch?->address ?: $church?->fulladdress() ?: '')));
    $guestFields = [
        ['Email', $user->email],
        ['Mobile', $user->mobile_no],
        ['Gender', $profile?->gender ? ucfirst($profile->gender) : null],
        ['Date of birth', $profile?->date_of_birth ? date('d M Y', strtotime($profile->date_of_birth)) : null],
        ['Address', $profile?->address],
        ['City', $profile?->city?->name],
        ['Region', $profile?->state?->name],
        ['Country', $profile?->country?->name],
    ];
@endphp

<div class="member-account-page member-guest-page">
    @include('member._account_hero', ['user' => $user])

    <nav class="member-quick-actions" aria-label="Account tools">
        <a href="{{ route('member.mygrouplist') }}"><span class="member-tool-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span><span><strong>My communities</strong><small>Find your place to connect</small></span><b>›</b></a>
        <a href="{{ route('member.donate') }}"><span class="member-tool-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></span><span><strong>Give</strong><small>Support the ministry</small></span><b>›</b></a>
        <a href="{{ route('communication-preferences.edit') }}"><span class="member-tool-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="9" cy="6" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/><circle cx="15" cy="12" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/></svg></span><span><strong>Communication settings</strong><small>Choose how we contact you</small></span><b>›</b></a>
        <a href="{{ route('member.change-password') }}"><span class="member-tool-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M8 10V7a4 4 0 1 1 8 0v3m-4 4v3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span><span><strong>Password &amp; security</strong><small>Protect your account</small></span><b>›</b></a>
    </nav>

    <div class="member-dashboard-grid member-guest-dashboard-grid">
        <section class="member-panel" id="member-profile">
            <div class="member-panel-heading"><div><span class="member-panel-kicker">YOUR ACCOUNT</span><h2>Personal details</h2><p>Information connected to your guest account.</p></div><span class="member-panel-mark"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span></div>
            <dl class="member-detail-list">
                @foreach($guestFields as [$label, $value])
                    <div><dt>{{ $label }}</dt><dd>{{ $value ?: 'Not added' }}</dd></div>
                @endforeach
            </dl>
        </section>

        <section class="member-panel" id="my-church">
            <div class="member-panel-heading"><div><span class="member-panel-kicker">YOUR IGM COMMUNITY</span><h2>My church</h2><p>Your church and branch connection.</p></div><span class="member-panel-mark"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V10l7-6 7 6v11m-7-17V1m-3 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
            <div class="member-church-identity"><span class="member-church-emblem"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V10l7-6 7 6v11m-7-17V1m-3 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span><div><strong>{{ $churchName }}</strong><span>{{ $branchName ?: 'Branch not assigned' }}</span></div></div>
            <div class="member-church-facts"><div><span>Branch</span><strong>{{ $branchName ?: 'Not assigned' }}</strong></div><div><span>Address</span><strong>{{ $churchAddress ?: 'Contact your church office for location details.' }}</strong></div>@if($branch?->phone)<div><span>Branch phone</span><strong>{{ $branch->phone }}</strong></div>@endif</div>
            <a class="member-panel-action" href="{{ route('web.contact') }}">Contact IGM <span>→</span></a>
        </section>

        <section class="member-panel member-card-panel" id="membership-card">
            <div class="member-panel-heading"><div><span class="member-panel-kicker">YOUR CHURCH ID</span><h2>Account card</h2><p>Your IGM account identification.</p></div><a class="member-print-button" href="{{ url('/member/print/'.$user->name) }}" target="_blank" rel="noopener">Print card <span>↗</span></a></div>
            <div class="member-id-card-preview">@include('member.idcard.idcard')</div>
        </section>
    </div>
</div>
@endsection

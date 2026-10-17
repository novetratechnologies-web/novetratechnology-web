@extends('layouts.app')

@section('title', 'My Profile')

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
    $memberFields = [
        ['First name', $profile?->firstname],
        ['Last name', $profile?->lastname],
        ['Email', $user->email],
        ['Mobile', $user->mobile_no],
        ['Gender', $profile?->gender ? ucfirst($profile->gender) : null],
        ['Date of birth', $profile?->date_of_birth ? date('d M Y', strtotime($profile->date_of_birth)) : null],
        ['Profession', $profile?->profession ? ucwords(str_replace('_', ' ', $profile->profession)) : null],
        ['Address', $profile?->address],
        ['City', $profile?->city?->name],
        ['Region', $profile?->state?->name],
        ['Country', $profile?->country?->name],
    ];
    $membershipFields = [
        ['Membership type', $profile?->membership_type ? ucfirst($profile->membership_type) : 'Member'],
        ['Member since', $profile?->membership_start_date ? date('d M Y', strtotime($profile->membership_start_date)) : null],
        ['Membership status', $profile?->status ? ucfirst($profile->status) : 'Active'],
        ['Baptism', $profile?->was_baptized ? ucfirst($profile->was_baptized) : null],
    ];
    if ($profile?->was_baptized === 'yes') {
        $membershipFields[] = ['Baptism date', $profile->baptism_date ? date('d M Y', strtotime($profile->baptism_date)) : null];
    }
@endphp

<div class="member-account-page">
    @include('member._account_hero', ['user' => $user])

    <nav class="member-quick-actions" aria-label="Member tools">
        <a href="{{ route('member.mygrouplist') }}"><span class="member-tool-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2m7-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm13 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span><span><strong>My communities</strong><small>Groups and connections</small></span><b>›</b></a>
        <a href="{{ route('member.donate') }}"><span class="member-tool-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"/></svg></span><span><strong>Give</strong><small>Support the ministry</small></span><b>›</b></a>
        <a href="{{ route('communication-preferences.edit') }}"><span class="member-tool-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/><circle cx="9" cy="6" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/><circle cx="15" cy="12" r="2" fill="white" stroke="currentColor" stroke-width="1.7"/></svg></span><span><strong>Communication settings</strong><small>Choose how we contact you</small></span><b>›</b></a>
        <a href="{{ route('member.change-password') }}"><span class="member-tool-icon"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><rect x="4" y="10" width="16" height="11" rx="2" stroke="currentColor" stroke-width="1.7"/><path d="M8 10V7a4 4 0 1 1 8 0v3m-4 4v3" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span><span><strong>Password &amp; security</strong><small>Protect your account</small></span><b>›</b></a>
    </nav>

    <div class="member-dashboard-grid">
        <section class="member-panel member-profile-panel" id="member-profile">
            <div class="member-panel-heading"><div><span class="member-panel-kicker">YOUR ACCOUNT</span><h2>Personal details</h2><p>Information connected to your member profile.</p></div><span class="member-panel-mark"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M20 21a8 8 0 0 0-16 0m8-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg></span></div>
            <dl class="member-detail-list">
                @foreach($memberFields as [$label, $value])
                    <div><dt>{{ $label }}</dt><dd>{{ $value ?: 'Not added' }}</dd></div>
                @endforeach
            </dl>
        </section>

        <section class="member-panel member-church-panel" id="my-church">
            <div class="member-panel-heading"><div><span class="member-panel-kicker">YOUR IGM COMMUNITY</span><h2>My church</h2><p>Your church and branch connection.</p></div><span class="member-panel-mark"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V10l7-6 7 6v11m-7-17V1m-3 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
            <div class="member-church-identity"><span class="member-church-emblem"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M3 21h18M5 21V10l7-6 7 6v11m-7-17V1m-3 4h6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg></span><div><strong>{{ $churchName }}</strong><span>{{ $branchName ?: 'Branch not assigned' }}</span></div></div>
            <div class="member-church-facts">
                <div><span>Branch</span><strong>{{ $branchName ?: 'Not assigned' }}</strong></div>
                <div><span>Address</span><strong>{{ $churchAddress ?: 'Contact your church office for location details.' }}</strong></div>
                @if($branch?->phone)<div><span>Branch phone</span><strong>{{ $branch->phone }}</strong></div>@endif
            </div>
            <a class="member-panel-action" href="{{ route('web.contact') }}">Contact IGM <span>→</span></a>
        </section>

        <section class="member-panel member-membership-panel">
            <div class="member-panel-heading"><div><span class="member-panel-kicker">YOUR JOURNEY</span><h2>Membership</h2><p>Your current church membership information.</p></div><span class="member-panel-mark"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3v18M5 8h14M7 8l-4 9h8L7 8Zm10 0-4 9h8l-4-9ZM5 21h14" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg></span></div>
            <dl class="member-detail-list">
                @foreach($membershipFields as [$label, $value])
                    <div><dt>{{ $label }}</dt><dd>{{ $value ?: '—' }}</dd></div>
                @endforeach
            </dl>
            <div class="member-membership-actions"><a href="{{ route('member.home') }}#membership-card">View membership card <span>→</span></a><a href="{{ url('/member/print/'.$user->name) }}" target="_blank" rel="noopener">Print card <span>↗</span></a></div>
        </section>

        <section class="member-panel member-family-panel">
            <div class="member-panel-heading"><div><span class="member-panel-kicker">FAMILY CONNECTIONS</span><h2>Family tree</h2><p>See the family connections linked to your account.</p></div><span class="member-panel-mark"><svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 9a7 7 0 0 1 14 0M5 5.5a3.5 3.5 0 0 0-1 6.7M2 20a5 5 0 0 1 3.5-4.8m13.5-9.7a3.5 3.5 0 0 1 1 6.7M22 20a5 5 0 0 0-3.5-4.8" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg></span></div>
            <div class="member-family-tree-wrap"><member-family-tree name="{{ $user->name }}"></member-family-tree></div>
        </section>

        <section class="member-panel member-card-panel" id="membership-card">
            <div class="member-panel-heading"><div><span class="member-panel-kicker">YOUR CHURCH ID</span><h2>Membership card</h2><p>Keep your membership details close at hand.</p></div><a class="member-print-button" href="{{ url('/member/print/'.$user->name) }}" target="_blank" rel="noopener">Print card <span>↗</span></a></div>
            <div class="member-id-card-preview">@include('member.idcard.idcard')</div>
        </section>
    </div>
</div>
@endsection

@extends('layouts.admin.layout')

@section('content')
<main class="mp-page">
    <section class="mp-heading">
        <div>
            <div class="mp-eyebrow"><span class="mp-eyebrow-dot"></span> PEOPLE &amp; COMMUNITY</div>
            <h1>Members</h1>
            <p class="mp-subtitle">Keep your church community connected and up to date.</p>
        </div>
        <div class="mp-heading-actions">
            <a href="{{ url('/admin/import') }}" id="import-button" class="mp-button mp-button-light"><i class="fas fa-file-import"></i><span>Import</span></a>
            <a href="{{ url('/admin/exportUsers/?' . $query . '&usergroup_id=5') }}" id="export-button" class="mp-button mp-button-light"><i class="fas fa-file-export"></i><span>Export</span></a>
            <a href="{{ url('/admin/membershipCard/create') }}" id="membership-card-button" class="mp-button mp-button-light"><i class="fas fa-id-card"></i><span>Membership cards</span></a>
            <a href="{{ url('/admin/member/add/') }}" dusk="add-button" class="mp-button mp-button-primary"><i class="fas fa-plus"></i><span>Add member</span></a>
        </div>
    </section>

    @include('partials.message')

    <section class="mp-overview" aria-label="Member overview">
        <div class="mp-overview-copy">
            <span class="mp-overview-icon"><i class="fas fa-users"></i></span>
            <div><span class="mp-overview-label">Your church community</span><strong>{{ number_format($count) }} <small>members</small></strong></div>
        </div>
        <div class="mp-overview-note"><i class="fas fa-circle-info"></i><span>Search, filter, and reach your people from one place.</span></div>
    </section>

    <section class="mp-filter-panel">
        <div class="mp-filter-heading"><div><h2>Find a member</h2><p>Search the directory or narrow your list with filters.</p></div><span class="mp-filter-heading-icon"><i class="fas fa-sliders"></i></span></div>
        <div class="mp-filter-controls">
            <portal-target name="search"></portal-target>
            <portal-target name="memberfilter"></portal-target>
            <search-filter url="{{ url('/') }}" searchquery="{{ $query }}"></search-filter>
        </div>
    </section>

    <section class="mp-directory">
        <div class="mp-directory-heading"><div><h2>Member directory</h2><p>Browse the people who make up your church.</p></div><span class="mp-directory-count">{{ number_format($count) }} total</span></div>
        <div class="mp-alphabet" aria-label="Filter members by first letter">
            <span class="mp-alphabet-label">Browse by name</span>
            <div class="mp-letter-list">
                @foreach(range('A', 'Z') as $letter)
                    <a href="#" class="alphabet-btn" onclick="filterByLetter('{{ $letter }}'); return false;" data-letter="{{ $letter }}" aria-label="Members starting with {{ $letter }}">{{ $letter }}</a>
                @endforeach
            </div>
            <a href="{{ url('/admin/members') }}" class="clear-filter-btn"><i class="fas fa-rotate-left"></i> Clear filters</a>
        </div>

        @include('admin.member.members-table')
    </section>
</main>

<script>
    function filterByLetter(letter) {
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('alphabet', letter);
        currentUrl.searchParams.delete('page');
        window.location.href = currentUrl.toString();
    }
    document.addEventListener('DOMContentLoaded', function () {
        const activeLetter = new URLSearchParams(window.location.search).get('alphabet');
        if (activeLetter) document.querySelectorAll(`a[data-letter="${activeLetter}"]`).forEach(btn => btn.classList.add('active'));
    });
</script>

@push('styles')
<style>
    .mp-page{--mp-ink:#172b3a;--mp-muted:#758596;--mp-line:#e7edf1;--mp-brand:#087f70;--mp-brand-dark:#06685d;color:var(--mp-ink);padding:4px 0 28px}
    .mp-page *{box-sizing:border-box}.mp-heading{display:flex;justify-content:space-between;align-items:flex-end;gap:24px;margin:4px 0 24px}.mp-eyebrow{display:flex;align-items:center;gap:8px;color:#698091;font-size:10px;font-weight:800;letter-spacing:.14em}.mp-eyebrow-dot{width:7px;height:7px;border-radius:50%;background:#11a78e;box-shadow:0 0 0 4px #e2f5f0}.mp-heading h1{font-size:30px;line-height:1.15;font-weight:750;letter-spacing:-.035em;color:#142a38;margin:11px 0 6px}.mp-subtitle{margin:0;color:var(--mp-muted);font-size:13px}.mp-heading-actions{display:flex;align-items:center;gap:8px;flex-wrap:wrap}.mp-button{height:40px;padding:0 13px;border-radius:9px;display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:12px;font-weight:700;text-decoration:none!important;transition:.18s ease;white-space:nowrap}.mp-button i{font-size:12px}.mp-button-light{color:#405464!important;background:#fff;border:1px solid #dfe7eb;box-shadow:0 2px 5px #15344808}.mp-button-light:hover{background:#f7fafb;border-color:#bdcbd1;transform:translateY(-1px)}.mp-button-primary{color:#fff!important;background:linear-gradient(135deg,#078776,#087468);box-shadow:0 5px 12px #087f7029}.mp-button-primary:hover{background:var(--mp-brand-dark);transform:translateY(-1px)}
    .mp-overview{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:17px 20px;background:linear-gradient(110deg,#f0faf7,#f8fcfb 64%,#f3f8fa);border:1px solid #dcefe9;border-radius:13px;margin-bottom:17px}.mp-overview-copy{display:flex;align-items:center;gap:13px}.mp-overview-icon{display:grid;place-items:center;width:42px;height:42px;border-radius:12px;background:#dff4ed;color:#07816f;font-size:17px}.mp-overview-label{display:block;color:#6d818c;font-size:11px;font-weight:650;margin-bottom:3px}.mp-overview-copy strong{font-size:21px;letter-spacing:-.03em;color:#183b42}.mp-overview-copy strong small{font-size:12px;letter-spacing:0;font-weight:600;color:#6f8589}.mp-overview-note{display:flex;align-items:center;gap:8px;color:#607b7a;font-size:12px}.mp-overview-note i{color:#168b78}
    .mp-filter-panel,.mp-directory{background:#fff;border:1px solid var(--mp-line);border-radius:13px;box-shadow:0 3px 14px #203a4a08}.mp-filter-panel{padding:18px 20px;margin-bottom:18px}.mp-filter-heading,.mp-directory-heading{display:flex;justify-content:space-between;align-items:center;gap:16px}.mp-filter-heading h2,.mp-directory-heading h2{font-size:15px;color:#1d3441;font-weight:750;margin:0 0 4px}.mp-filter-heading p,.mp-directory-heading p{font-size:11px;color:#84929d;margin:0}.mp-filter-heading-icon{width:34px;height:34px;display:grid;place-items:center;color:#738692;background:#f3f6f7;border-radius:9px}.mp-filter-controls{margin-top:14px}.mp-filter-controls>div{max-width:100%}.mp-directory{padding:18px 18px 14px}.mp-directory-heading{padding:0 2px 15px;border-bottom:1px solid #edf1f3}.mp-directory-count{font-size:10px;font-weight:750;color:#55716e;background:#edf8f4;border:1px solid #dcefe8;border-radius:20px;padding:6px 10px;white-space:nowrap}
    .mp-alphabet{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid #edf1f3}.mp-alphabet-label{font-size:10px;font-weight:750;color:#80909a;white-space:nowrap}.mp-letter-list{display:flex;gap:4px;flex-wrap:wrap}.mp-page .alphabet-btn{display:inline-flex;align-items:center;justify-content:center;width:25px;height:25px;border:1px solid transparent;border-radius:7px;background:#f6f8f9;color:#60717d;font-size:10px;font-weight:750;text-decoration:none;transition:.15s}.mp-page .alphabet-btn:hover{background:#eaf6f2;color:#087b6c;border-color:#d3ece3}.mp-page .alphabet-btn.active{background:#087f70;color:#fff;border-color:#087f70;box-shadow:0 3px 7px #087f7026}.mp-page .clear-filter-btn{margin-left:auto;display:inline-flex;gap:6px;align-items:center;color:#73838b;font-size:10px;font-weight:700;text-decoration:none;white-space:nowrap}.mp-page .clear-filter-btn:hover{color:#ba4c4c}
    .mp-page .members-table-container{padding:14px 0 0!important;background:transparent!important;box-shadow:none!important;border-radius:0!important}.mp-page .members-table-container>div{margin-top:0!important}.mp-page #table-search{height:39px;border:1px solid #dfe7eb!important;border-radius:9px!important;background:#fff!important;box-shadow:none!important;padding-left:36px!important;font-size:12px!important;outline:none}.mp-page #table-search:focus{border-color:#50ae9d!important;box-shadow:0 0 0 3px #e3f5f0!important}.mp-page .members-table-container>div>div:first-child{position:relative}.mp-page .members-table-container>div>div:first-child:before{content:'\f002';font-family:'Font Awesome 5 Free';font-weight:900;position:absolute;left:13px;top:12px;font-size:12px;color:#9aa9b1;z-index:1}.mp-page #action-buttons-container{border:1px solid #d8ece6!important;border-radius:10px!important;background:#f1faf7!important;box-shadow:none!important;padding:12px 14px!important}.mp-page #action-buttons-container .text-blue-700{color:#17695e!important}.mp-page #action-buttons-container .bg-blue-500{background:#087f70!important}.mp-page #action-buttons-container button{border-radius:7px!important}.mp-page #action-buttons-container #select-none-checkbox{background:#e8eeee!important;color:#52646c!important}.mp-page #send-message-btn{background:#087f70!important;border-radius:8px!important;box-shadow:none!important}.mp-page #subscribe-newsletter-btn{background:#fff!important;color:#087f70!important;border:1px solid #b9ded3!important;border-radius:8px!important;box-shadow:none!important}.mp-page .overflow-x-auto{border:1px solid #e8edef;border-radius:10px;overflow-x:auto}.mp-page table{border:0!important;min-width:900px}.mp-page table thead tr{background:#f7f9fa!important}.mp-page table th{border:0!important;border-bottom:1px solid #e7edef!important;padding:12px 11px!important;color:#81909a;font-size:9px!important;text-transform:uppercase;letter-spacing:.07em;font-weight:800!important;white-space:nowrap}.mp-page table td{border:0!important;border-bottom:1px solid #eef2f3!important;padding:11px!important;color:#52636d;font-size:11px!important;vertical-align:middle}.mp-page table tbody tr:last-child td{border-bottom:0!important}.mp-page table tbody tr{transition:background .15s}.mp-page table tbody tr:hover{background:#f8fbfa!important}.mp-page table input[type=checkbox]{accent-color:#087f70;width:15px;height:15px}.mp-page table td img{border:2px solid #fff;box-shadow:0 1px 4px #233b4b20}.mp-page table td a{color:#17695e!important}.mp-page table td a:hover{color:#075d52!important}.mp-page table td .bg-green-100{background:#e8f7ef!important;color:#258153!important;border-radius:20px!important;padding:5px 9px!important}.mp-page .members-table-container .mt-4{gap:10px}.mp-page .members-table-container .mt-4>div:first-child{font-size:11px;color:#83919a}.mp-page .members-table-container .mt-4 a,.mp-page .members-table-container .mt-4 span{border:1px solid #e2e9ec!important;border-radius:7px!important;padding:7px 10px!important;font-size:10px!important;text-decoration:none}.mp-page .members-table-container .mt-4 span.bg-blue-500{background:#087f70!important;border-color:#087f70!important}.mp-page .members-table-container .my-8{text-align:center;padding:42px 15px!important;color:#84939b!important;background:#fafcfc;border:1px dashed #dce5e8;border-radius:10px}.mp-page .members-table-container .my-8 p{font-size:13px}.mp-page .modal-container{border-radius:14px!important;box-shadow:0 18px 60px #122c3d30!important}.mp-page .modal-header{border-bottom:1px solid #edf1f3;padding:14px 0}.mp-page .modal-header h2{font-weight:750;color:#1c3440}.mp-page .modal-body{padding-top:12px;padding-bottom:8px}.mp-page .tw-form-control{border-radius:8px!important;border-color:#dce5e8!important}.mp-page .btn-submit{background:#087f70!important;border-radius:8px!important;padding:9px 15px!important}
    .mp-filter-controls #search-filter,.mp-filter-controls [id="show-filter"]{width:100%;max-width:none}
    .mp-filter-controls [id="show-filter"]{z-index:20}
    .mp-filter-controls .search_user_filter{width:100%!important;max-width:none!important;left:0;right:0;border:1px solid #e2eaed!important;border-radius:10px!important;box-shadow:0 14px 36px #18344318!important;margin-top:8px;background:#fff!important}
    .mp-filter-controls .search_filter{list-style:none;margin:0;padding:0}
    .mp-filter-controls .filter_container{padding:4px 0;border-bottom:1px solid #f0f3f4}
    .mp-filter-controls .person_filter_name_title p{font-size:11px;font-weight:700;color:#637681;margin:0}
    .mp-filter-controls .filter-form-control{width:100%;min-height:37px;padding:8px 10px;border:1px solid #dce5e8;border-radius:8px;background:#fff;color:#314651;font-size:12px;outline:none;box-shadow:none}
    .mp-filter-controls .filter-form-control:focus{border-color:#50ae9d;box-shadow:0 0 0 3px #e3f5f0}
    .mp-filter-controls .btn-reset,.mp-filter-controls .btn-submit{border-radius:8px!important;padding:8px 14px!important;font-size:11px!important;font-weight:700!important;text-decoration:none}
    .mp-filter-controls .btn-reset{border-color:#e0e7ea!important;color:#536670!important;background:#f7f9fa!important}
    .mp-filter-controls .btn-submit{background:#087f70!important}
    .mp-page .mp-filter-panel{position:relative;z-index:5}
    @media(max-width:900px){.mp-heading{align-items:flex-start;flex-direction:column}.mp-heading-actions{width:100%}.mp-filter-controls{overflow:visible}.mp-overview-note{display:none}}
    @media(max-width:600px){.mp-heading h1{font-size:26px}.mp-heading-actions{display:grid;grid-template-columns:1fr 1fr}.mp-button{padding:0 9px;font-size:11px}.mp-overview{padding:13px}.mp-filter-panel,.mp-directory{padding:14px}.mp-alphabet{align-items:flex-start;flex-wrap:wrap}.mp-letter-list{order:3;width:100%}.mp-page .clear-filter-btn{margin-left:auto}.mp-page .alphabet-btn{width:27px;height:27px}.mp-directory-heading{align-items:flex-start}}
</style>
@endpush
@endsection

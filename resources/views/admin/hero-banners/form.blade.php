@extends('layouts.admin.layout')

@section('content')
@php
    $editing = $banner->exists;
    $previewImage = $banner->image_path ? $banner->ImageUrl : 'https://images.unsplash.com/photo-1507692049790-de58290a4334?auto=format&fit=crop&w=1800&q=85';
@endphp

@push('styles')
<style>
    .hero-editor{--he-ink:#14243a;--he-muted:#718096;--he-line:#e5eaf0;--he-gold:#d8ad5d;color:var(--he-ink);max-width:1500px;margin:0 auto;padding:24px 6px 48px;font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif}
    .hero-editor *{box-sizing:border-box}.he-top{display:flex;align-items:center;justify-content:space-between;gap:20px;margin:0 0 22px}.he-crumb{display:inline-flex;align-items:center;gap:8px;color:#68788d;font-size:12px;font-weight:650;text-decoration:none}.he-crumb:hover{color:#14243a}.he-overline{margin:15px 0 5px;color:#a77a2e;font-size:10px;font-weight:850;letter-spacing:.17em;text-transform:uppercase}.he-title{margin:0;color:#112b48;font-size:clamp(25px,3vw,34px);font-weight:850;letter-spacing:-.045em;line-height:1.1}.he-subtitle{max-width:640px;margin:8px 0 0;color:#718096;font-size:13px;line-height:1.6}.he-top-actions{display:flex;gap:9px}.he-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;min-height:40px;padding:0 15px;border:1px solid transparent;border-radius:9px;font-size:12px;font-weight:750;text-decoration:none;cursor:pointer;transition:.18s ease}.he-btn:hover{transform:translateY(-1px)}.he-btn-light{border-color:var(--he-line);background:#fff;color:#536275}.he-btn-dark{background:#102c49;color:#fff;box-shadow:0 5px 14px #102c4920}.he-btn-dark:hover{background:#1d4268}.he-workspace{display:grid;grid-template-columns:minmax(0,1.45fr) minmax(320px,.8fr);gap:20px;align-items:start}.he-form{min-width:0;display:grid;gap:15px}.he-card{overflow:hidden;border:1px solid var(--he-line);border-radius:15px;background:#fff;box-shadow:0 5px 20px #19355308}.he-card-head{display:flex;align-items:flex-start;gap:12px;padding:17px 19px;border-bottom:1px solid #edf0f3}.he-card-icon{flex:none;display:grid;width:34px;height:34px;place-items:center;border-radius:10px;background:#f7f1e4;color:#a57b31;font-size:13px}.he-card-head h2{margin:1px 0 3px;color:#182a40;font-size:14px;font-weight:800}.he-card-head p{margin:0;color:#8290a1;font-size:11px;line-height:1.5}.he-card-body{padding:18px 19px}.he-grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}.he-field{min-width:0}.he-field-full{grid-column:1/-1}.he-label{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:6px;color:#35465a;font-size:11px;font-weight:750}.he-optional{color:#98a3b1;font-size:10px;font-weight:550}.he-input,.he-select,.he-textarea{display:block;width:100%;min-height:40px;padding:10px 11px;border:1px solid #dce3eb;border-radius:8px;outline:none;background:#fff;color:#182a40;font:inherit;font-size:12px;transition:border-color .15s,box-shadow .15s}.he-input:focus,.he-select:focus,.he-textarea:focus{border-color:#bd9149;box-shadow:0 0 0 3px #d8ad5d24}.he-textarea{min-height:96px;resize:vertical;line-height:1.55}.he-help{margin:5px 0 0;color:#8995a4;font-size:10px;line-height:1.5}.he-error{margin:5px 0 0;color:#bd3939;font-size:10px}.he-upload{position:relative;display:flex;min-height:108px;align-items:center;gap:14px;padding:14px;border:1px dashed #c9d3de;border-radius:10px;background:#fafbfd;cursor:pointer;transition:.18s}.he-upload:hover{border-color:#bd9149;background:#fffdf8}.he-upload-icon{flex:none;display:grid;width:40px;height:40px;place-items:center;border-radius:12px;background:#fff3d6;color:#a77a2e;font-size:16px}.he-upload strong{display:block;color:#304258;font-size:11px}.he-upload span{display:block;margin-top:4px;color:#8793a2;font-size:10px;line-height:1.5}.he-upload input{position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer}.he-divider{height:1px;margin:17px 0;background:#edf0f3}.he-status{display:flex;align-items:center;gap:10px;padding:12px 13px;border:1px solid #e9edf1;border-radius:10px;background:#fafbfd}.he-status input{width:16px;height:16px;accent-color:#a47b32}.he-status strong{display:block;color:#344458;font-size:11px}.he-status span{display:block;margin-top:3px;color:#8793a2;font-size:10px}.he-inline-select{display:grid;grid-template-columns:1fr 1fr;gap:12px}.he-footnote{padding:12px 14px;border-radius:9px;background:#f7f8fa;color:#738094;font-size:10px;line-height:1.6}.he-preview-wrap{position:sticky;top:20px}.he-preview-top{display:flex;align-items:center;justify-content:space-between;margin:0 1px 10px}.he-preview-top strong{color:#304258;font-size:11px;font-weight:800}.he-preview-top span{color:#909cac;font-size:10px}.he-preview{position:relative;display:flex;min-height:280px;align-items:center;overflow:hidden;border-radius:14px;background:#122b43 center/cover no-repeat;box-shadow:0 12px 30px #112b4826;color:#fff}.he-preview:before{position:absolute;inset:0;background:linear-gradient(90deg,rgba(5,23,41,.9),rgba(6,30,53,.68) 58%,rgba(8,35,60,.17));content:""}.he-preview-copy{position:relative;z-index:1;width:100%;padding:28px 25px}.he-preview-eyebrow{margin:0 0 11px;color:#f1cb77;font-size:9px;font-weight:850;letter-spacing:.15em;text-transform:uppercase}.he-preview-title{margin:0;color:#fff;font-size:clamp(23px,2.6vw,35px);font-weight:850;letter-spacing:-.05em;line-height:1.01;overflow-wrap:anywhere}.he-preview-highlight{display:block;margin-top:5px;color:#edc875}.he-preview-description{max-width:355px;margin:11px 0 0;color:#e0e8f0;font-size:10px;line-height:1.55}.he-preview-buttons{display:flex;flex-wrap:wrap;gap:7px;margin-top:15px}.he-preview-button{display:none;min-height:30px;align-items:center;padding:0 11px;border-radius:999px;background:#edc875;color:#132b43;font-size:9px;font-weight:800}.he-preview-button.secondary{border:1px solid #ffffff8a;background:#10294370;color:white}.he-preview-note{margin-top:11px;color:#98a5b2;font-size:9px;line-height:1.5}.he-preview-image{display:none;width:100%;height:120px;margin-top:12px;border-radius:8px;object-fit:cover}.he-preview-meta{display:grid;grid-template-columns:1fr 1fr;gap:9px;margin-top:10px}.he-meta{padding:10px 11px;border:1px solid var(--he-line);border-radius:9px;background:#fff}.he-meta span{display:block;color:#8a96a5;font-size:9px}.he-meta strong{display:block;margin-top:4px;color:#304258;font-size:11px}.he-errors{margin-bottom:14px;padding:12px 15px;border:1px solid #f0caca;border-radius:10px;background:#fff6f6;color:#ad3838;font-size:11px}.he-publish{display:flex;align-items:center;justify-content:flex-end;gap:9px;padding:15px 19px;border-top:1px solid #edf0f3;background:#fcfcfd}.he-publish .he-btn{min-height:42px;padding:0 18px}.he-publish-hint{margin-right:auto;color:#8995a4;font-size:10px}
    @media(max-width:980px){.he-workspace{grid-template-columns:1fr}.he-preview-wrap{position:static;grid-row:1}.he-preview{min-height:240px}.he-preview-copy{max-width:620px}.he-preview-image{display:none}}
    @media(max-width:600px){.hero-editor{padding:15px 0 35px}.he-top{align-items:flex-start;flex-direction:column}.he-top-actions{width:100%}.he-top-actions .he-btn{flex:1}.he-card-head,.he-card-body{padding:14px}.he-grid{grid-template-columns:1fr;gap:13px}.he-field-full{grid-column:auto}.he-inline-select{grid-template-columns:1fr 1fr}.he-preview{min-height:215px}.he-preview-copy{padding:23px 19px}.he-preview-title{font-size:26px}.he-publish{flex-wrap:wrap}.he-publish-hint{width:100%;margin:0 0 3px}}
    .he-preview-top{gap:12px}.he-preview-switch{display:flex;gap:3px;padding:3px;border:1px solid var(--he-line);border-radius:8px;background:#fff}.he-preview-switch button{padding:5px 8px;border:0;border-radius:5px;background:transparent;color:#728096;font:inherit;font-size:9px;font-weight:750;cursor:pointer}.he-preview-switch button[aria-pressed="true"]{background:#102c49;color:#fff}.he-preview{transition:width .2s,min-height .2s}.he-preview.is-mobile{width:min(100%,320px);min-height:520px;margin:0 auto}.he-preview.is-mobile:before{background:linear-gradient(0deg,rgba(5,23,41,.82),rgba(6,30,53,.46) 75%,rgba(8,35,60,.12))}.he-preview.is-mobile .he-preview-copy{padding:24px 20px}.he-preview.is-mobile .he-preview-title{font-size:29px}.he-preview.is-mobile .he-preview-description{font-size:11px}
</style>
@endpush

<div class="hero-editor">
    <div class="he-top">
        <div>
            <a href="{{ route('admin.hero-banners.index') }}" class="he-crumb"><i class="fas fa-arrow-left"></i> Back to Home Hero</a>
            <p class="he-overline">Website content studio · {{ $editing ? 'Editing slide' : 'New banner' }}</p>
            <h1 class="he-title">{{ $editing ? 'Edit your hero banner' : 'Create a hero banner' }}</h1>
            <p class="he-subtitle">Build the first impression visitors see. Add the image, welcome message, and actions, then preview the banner before publishing.</p>
        </div>
        <div class="he-top-actions"><a href="{{ route('admin.hero-banners.index') }}" class="he-btn he-btn-light">Cancel</a><button type="submit" form="hero-banner-form" class="he-btn he-btn-dark"><i class="fas fa-check"></i> {{ $editing ? 'Save changes' : 'Publish banner' }}</button></div>
    </div>

    @if($errors->any())<div class="he-errors"><strong>Some details need attention.</strong> {{ $errors->first() }}</div>@endif

    <form id="hero-banner-form" method="POST" enctype="multipart/form-data" action="{{ $editing ? route('admin.hero-banners.update', $banner->id) : route('admin.hero-banners.store') }}">
        @csrf @if($editing)@method('PUT')@endif
        <div class="he-workspace">
            <div class="he-form">
                <section class="he-card">
                    <div class="he-card-head"><span class="he-card-icon"><i class="fas fa-pen-nib"></i></span><div><h2>Banner message</h2><p>Write a clear welcome and one focused message for visitors.</p></div></div>
                    <div class="he-card-body">
                        <div class="he-label" style="margin-bottom:8px">Start with a content idea <span class="he-optional">You can edit every field</span></div>
                        <div style="display:flex;flex-wrap:wrap;gap:7px;margin-bottom:16px">
                            <button type="button" class="he-btn he-btn-light" data-banner-template="welcome"><i class="fas fa-door-open" style="color:#a77a2e"></i> Sunday welcome</button>
                            <button type="button" class="he-btn he-btn-light" data-banner-template="event"><i class="fas fa-calendar-days" style="color:#a77a2e"></i> Event invitation</button>
                            <button type="button" class="he-btn he-btn-light" data-banner-template="new"><i class="fas fa-heart" style="color:#a77a2e"></i> New here</button>
                        </div>
                        <div class="he-grid">
                        <div class="he-field he-field-full"><label class="he-label" for="hero-eyebrow">Small heading <span class="he-optional">Optional · up to 100 characters</span></label><input id="hero-eyebrow" data-preview="eyebrow" class="he-input" name="eyebrow" maxlength="100" value="{{ old('eyebrow', $banner->eyebrow) }}" placeholder="WELCOME TO IGM CHURCH">@error('eyebrow')<p class="he-error">{{ $message }}</p>@enderror</div>
                        <div class="he-field"><label class="he-label" for="hero-title">Main heading <span class="he-optional">Required</span></label><input id="hero-title" data-preview="title" class="he-input" name="title" maxlength="180" required value="{{ old('title', $banner->title) }}" placeholder="A place to belong">@error('title')<p class="he-error">{{ $message }}</p>@enderror</div>
                        <div class="he-field"><label class="he-label" for="hero-highlight">Highlighted line <span class="he-optional">Optional</span></label><input id="hero-highlight" data-preview="highlight" class="he-input" name="highlight" maxlength="180" value="{{ old('highlight', $banner->highlight) }}" placeholder="A faith to grow">@error('highlight')<p class="he-error">{{ $message }}</p>@enderror</div>
                        <div class="he-field he-field-full"><label class="he-label" for="hero-description">Supporting text <span class="he-optional">Optional · short and welcoming</span></label><textarea id="hero-description" data-preview="description" class="he-textarea" name="description" maxlength="1000" placeholder="Tell visitors what makes your church a welcoming place and invite them to take the next step.">{{ old('description', $banner->description) }}</textarea><p class="he-help"><span id="hero-description-count">{{ strlen(old('description', $banner->description ?? '')) }}</span>/1000 · This appears below the main heading.</p>@error('description')<p class="he-error">{{ $message }}</p>@enderror</div>
                    </div></div>
                </section>

                <section class="he-card">
                    <div class="he-card-head"><span class="he-card-icon"><i class="fas fa-image"></i></span><div><h2>Banner image</h2><p>Choose a high-quality landscape photo with open space behind the text.</p></div></div>
                    <div class="he-card-body"><label class="he-label" for="hero-image">Background image <span class="he-optional">{{ $editing ? 'Optional · leave empty to keep the current image' : 'Required' }}</span></label><label class="he-upload" for="hero-image"><span class="he-upload-icon"><i class="fas fa-cloud-arrow-up"></i></span><span><strong id="hero-image-name">{{ $editing ? 'Choose a new image or drop it here' : 'Choose an image to upload' }}</strong><span>JPG, PNG, or WebP · up to 8 MB · landscape images work best</span></span><input id="hero-image" name="image" type="file" accept="image/jpeg,image/png,image/webp" {{ $editing ? '' : 'required' }}></label><img id="hero-image-preview" class="he-preview-image" src="{{ $previewImage }}" alt="Selected hero image">@error('image')<p class="he-error">{{ $message }}</p>@enderror<div class="he-footnote" style="margin-top:12px"><strong>Image tip:</strong> The page adds a dark overlay behind the words so they remain readable on bright photographs.</div></div>
                </section>

                <section class="he-card">
                    <div class="he-card-head"><span class="he-card-icon"><i class="fas fa-arrow-pointer"></i></span><div><h2>Visitor actions</h2><p>Add up to two buttons that take people to the next useful page.</p></div></div>
                    <div class="he-card-body"><div class="he-grid">
                        <div class="he-field"><label class="he-label" for="hero-primary-label">Primary button text <span class="he-optional">Optional</span></label><input id="hero-primary-label" data-preview="primary-label" name="primary_label" maxlength="60" value="{{ old('primary_label', $banner->primary_label) }}" class="he-input" placeholder="Plan your visit"></div>
                        <div class="he-field"><label class="he-label" for="hero-primary-url">Primary button link</label><input id="hero-primary-url" name="primary_url" maxlength="500" value="{{ old('primary_url', $banner->primary_url) }}" class="he-input" placeholder="/contact or https://example.org"><p class="he-help">Use a page path beginning with / or a full https:// link.</p>@error('primary_url')<p class="he-error">{{ $message }}</p>@enderror</div>
                        <div class="he-field"><label class="he-label" for="hero-secondary-label">Second button text <span class="he-optional">Optional</span></label><input id="hero-secondary-label" data-preview="secondary-label" name="secondary_label" maxlength="60" value="{{ old('secondary_label', $banner->secondary_label) }}" class="he-input" placeholder="Watch online"></div>
                        <div class="he-field"><label class="he-label" for="hero-secondary-url">Second button link</label><input id="hero-secondary-url" name="secondary_url" maxlength="500" value="{{ old('secondary_url', $banner->secondary_url) }}" class="he-input" placeholder="/sermons or https://example.org"><p class="he-help">Use a page path beginning with / or a full https:// link.</p>@error('secondary_url')<p class="he-error">{{ $message }}</p>@enderror</div>
                    </div></div>
                </section>

                <section class="he-card">
                    <div class="he-card-head"><span class="he-card-icon"><i class="fas fa-sliders"></i></span><div><h2>Display and schedule</h2><p>Choose how this banner appears and when it should be shown.</p></div></div>
                    <div class="he-card-body"><div class="he-grid">
                        <div class="he-field"><label class="he-label" for="hero-order">Slide position</label><input id="hero-order" type="number" min="0" max="9999" name="display_order" value="{{ old('display_order', $banner->display_order ?? 0) }}" class="he-input"><p class="he-help">Smaller numbers appear first.</p></div>
                        <div class="he-field"><label class="he-label" for="hero-transition">Animation</label><select id="hero-transition" name="transition" class="he-select"><option value="fade" @selected(old('transition', $banner->transition)==='fade')>Soft fade</option><option value="slide" @selected(old('transition', $banner->transition)==='slide')>Side slide</option></select></div>
                        <div class="he-field"><label class="he-label" for="hero-duration">Time on screen</label><select id="hero-duration" name="duration_seconds" class="he-select">@for($i=4;$i<=15;$i++)<option value="{{ $i }}" @selected((int)old('duration_seconds', $banner->duration_seconds)===$i)>{{ $i }} seconds</option>@endfor</select></div>
                        <div class="he-field"><label class="he-label" for="hero-active">Publishing status</label><label class="he-status"><input id="hero-active" type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->exists ? $banner->is_active : true))><span><strong>Slide is active</strong><span>Inactive banners stay saved but won’t appear on the site.</span></span></label></div>
                        <div class="he-field"><label class="he-label" for="hero-starts">Show from <span class="he-optional">Optional</span></label><input id="hero-starts" type="datetime-local" name="starts_at" value="{{ old('starts_at', $banner->starts_at?->format('Y-m-d\TH:i')) }}" class="he-input"></div>
                        <div class="he-field"><label class="he-label" for="hero-ends">Stop showing <span class="he-optional">Optional</span></label><input id="hero-ends" type="datetime-local" name="ends_at" value="{{ old('ends_at', $banner->ends_at?->format('Y-m-d\TH:i')) }}" class="he-input"></div>
                    </div><div class="he-footnote" style="margin-top:14px">Scheduled slides publish automatically inside the selected window. If the church is marked live, the live service takes priority over every banner.</div></div>
                    <div class="he-publish"><span class="he-publish-hint"><i class="fas fa-circle-info"></i> Changes appear on the public home page after saving.</span><a href="{{ route('admin.hero-banners.index') }}" class="he-btn he-btn-light">Cancel</a><button type="submit" class="he-btn he-btn-dark"><i class="fas fa-check"></i> {{ $editing ? 'Save changes' : 'Publish banner' }}</button></div>
                </section>
            </div>

            <aside class="he-preview-wrap">
                <div class="he-preview-top"><strong><i class="fas fa-eye" style="margin-right:6px;color:#a77a2e"></i> Live preview</strong><div class="he-preview-switch" aria-label="Preview size"><button type="button" data-preview-size="desktop" aria-pressed="true">Desktop</button><button type="button" data-preview-size="mobile" aria-pressed="false">Mobile</button></div></div>
                <div id="hero-live-preview" class="he-preview" style="background-image:url('{{ $previewImage }}')"><div class="he-preview-copy"><p class="he-preview-eyebrow" data-preview-output="eyebrow">{{ old('eyebrow', $banner->eyebrow) ?: 'WELCOME TO IGM CHURCH' }}</p><h2 class="he-preview-title"><span data-preview-output="title">{{ old('title', $banner->title) ?: 'A place to belong' }}</span><span class="he-preview-highlight" data-preview-output="highlight">{{ old('highlight', $banner->highlight) ?: 'A faith to grow' }}</span></h2><p class="he-preview-description" data-preview-output="description">{{ old('description', $banner->description) ?: 'A welcoming community where you can grow in faith, find connection, and discover a place to serve.' }}</p><div class="he-preview-buttons"><span class="he-preview-button" data-preview-output="primary-label">{{ old('primary_label', $banner->primary_label) }}</span><span class="he-preview-button secondary" data-preview-output="secondary-label">{{ old('secondary_label', $banner->secondary_label) }}</span></div></div></div>
                <div class="he-preview-meta"><div class="he-meta"><span>Transition</span><strong id="hero-preview-transition">{{ ucfirst(old('transition', $banner->transition ?: 'fade')) }}</strong></div><div class="he-meta"><span>Time per slide</span><strong id="hero-preview-duration">{{ old('duration_seconds', $banner->duration_seconds ?: 6) }} seconds</strong></div></div>
                <p class="he-preview-note"><i class="fas fa-mobile-screen-button" style="margin-right:5px"></i> Your banner adapts to mobile and desktop screens.</p>
            </aside>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(() => {
    const form = document.getElementById('hero-banner-form');
    if (!form) return;
    const preview = document.getElementById('hero-live-preview');
    const defaults = {
        eyebrow: 'WELCOME TO IGM CHURCH',
        title: 'A place to belong',
        highlight: 'A faith to grow',
        description: 'A welcoming community where you can grow in faith, find connection, and discover a place to serve.'
    };
    const templates = {
        welcome: { eyebrow: 'SUNDAY WORSHIP · YOU ARE WELCOME', title: 'A place to belong', highlight: 'A faith to grow', description: 'Join us this Sunday for worship, an encouraging message, and a warm welcome from our church family.', primary_label: 'Plan your visit', primary_url: '/contact', secondary_label: 'Watch online', secondary_url: '/sermons' },
        event: { eyebrow: 'YOU ARE INVITED', title: 'Come be part of it', highlight: 'There is a place for you', description: 'Gather with us for a joyful time of worship, connection, and community. We would love to see you there.', primary_label: 'View upcoming events', primary_url: '/events', secondary_label: 'Get in touch', secondary_url: '/contact' },
        new: { eyebrow: 'NEW TO IGM CHURCH?', title: 'We would love to meet you', highlight: 'Everyone is welcome', description: 'Whether this is your first visit or you are looking for a church home, we are glad you are here.', primary_label: 'Plan your first visit', primary_url: '/contact', secondary_label: 'About our church', secondary_url: '/pages' }
    };
    document.querySelectorAll('[data-banner-template]').forEach((button) => button.addEventListener('click', () => {
        const template = templates[button.dataset.bannerTemplate];
        Object.entries(template).forEach(([key, value]) => {
            const field = form.elements.namedItem(key);
            if (field) { field.value = value; field.dispatchEvent(new Event('input', { bubbles: true })); }
        });
    }));
    form.querySelectorAll('[data-preview]').forEach((field) => {
        const key = field.dataset.preview;
        const output = document.querySelector(`[data-preview-output="b{key}"]`);
        const update = () => {
            if (!output) return;
            output.textContent = field.value.trim() || defaults[key] || '';
            if (key === 'description') document.getElementById('hero-description-count').textContent = field.value.length;
        };
        field.addEventListener('input', update);
        update();
    });
    ['primary-label', 'secondary-label'].forEach((key) => {
        const field = form.querySelector(`[data-preview="b{key}"]`);
        const output = document.querySelector(`[data-preview-output="b{key}"]`);
        const update = () => { output.textContent = field.value.trim(); output.style.display = field.value.trim() ? 'inline-flex' : 'none'; };
        field.addEventListener('input', update);
        update();
    });
    const imageInput = document.getElementById('hero-image');
    imageInput.addEventListener('change', () => {
        const file = imageInput.files && imageInput.files[0];
        if (!file) return;
        document.getElementById('hero-image-name').textContent = file.name;
        const objectUrl = URL.createObjectURL(file);
        preview.style.backgroundImage = `url("b{objectUrl}")`;
        const imagePreview = document.getElementById('hero-image-preview');
        imagePreview.src = objectUrl;
        imagePreview.style.display = 'block';
    });
    document.querySelectorAll('[data-preview-size]').forEach((button) => button.addEventListener('click', () => {
        const mobile = button.dataset.previewSize === 'mobile';
        preview.classList.toggle('is-mobile', mobile);
        document.querySelectorAll('[data-preview-size]').forEach((choice) => choice.setAttribute('aria-pressed', String(choice === button)));
    }));
    const transition = document.getElementById('hero-transition');
    const duration = document.getElementById('hero-duration');
    transition.addEventListener('change', () => { document.getElementById('hero-preview-transition').textContent = transition.options[transition.selectedIndex].text.replace('Soft ', '').replace('Side ', ''); });
    duration.addEventListener('change', () => { document.getElementById('hero-preview-duration').textContent = `b{duration.value} seconds`; });
})();
</script>
@endpush

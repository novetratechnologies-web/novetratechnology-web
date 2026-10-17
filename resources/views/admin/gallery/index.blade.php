@extends('layouts.admin.layout')

@section('content')
<div class="gallery-admin">
    <header class="gallery-page-heading">
        <div>
            <p class="gallery-eyebrow">MEDIA LIBRARY</p>
            <h1>Photo galleries</h1>
            <p>Collect and share moments from church life.</p>
        </div>
        <a class="gallery-primary-action" href="{{ url('/admin/gallery/create') }}"><i class="fas fa-plus"></i> Create album</a>
    </header>

    @include('partials.message')

    <section class="gallery-toolbar">
        <div><strong>{{ $count }}</strong><span> {{ \Illuminate\Support\Str::plural('album', $count) }} in your library</span></div>
        <form method="GET" action="{{ url('/admin/gallery') }}" role="search">
            <label class="gallery-search"><i class="fas fa-search"></i><input type="search" name="search" value="{{ request('search') }}" placeholder="Search albums"><button type="submit">Search</button></label>
            @if(request('search'))<a class="gallery-reset" href="{{ url('/admin/gallery') }}">Clear</a>@endif
        </form>
    </section>

    @if($gallery->isEmpty())
        <section class="gallery-empty-state">
            <span class="gallery-empty-icon"><i class="far fa-images"></i></span>
            <h2>{{ request('search') ? 'No albums found' : 'Your gallery starts here' }}</h2>
            <p>{{ request('search') ? 'Try a different album name.' : 'Create an album, add photos, and give your church community a place to revisit them.' }}</p>
            @unless(request('search'))<a class="gallery-primary-action" href="{{ url('/admin/gallery/create') }}"><i class="fas fa-plus"></i> Create your first album</a>@endunless
        </section>
    @else
        <section class="gallery-album-grid" aria-label="Gallery albums">
            @foreach($gallery as $album)
                <article class="gallery-album-card">
                    <a class="gallery-album-cover" href="{{ url('/admin/gallery/' . $album->id) }}" aria-label="Open {{ $album->name }}">
                        @if($album->FullPath)
                            <img src="{{ $album->FullPath }}" alt="{{ $album->name }}" loading="lazy" onerror="this.closest('.gallery-album-cover').classList.add('is-image-missing');this.remove()">
                        @endif
                        <span class="gallery-cover-fallback"><i class="far fa-images"></i></span>
                        <span class="gallery-photo-count"><i class="far fa-image"></i> {{ $album->photos_count }} {{ \Illuminate\Support\Str::plural('photo', $album->photos_count) }}</span>
                    </a>
                    <div class="gallery-album-info">
                        <div class="gallery-album-title"><h2><a href="{{ url('/admin/gallery/' . $album->id) }}">{{ $album->name }}</a></h2><span>{{ optional($album->created_at)->format('M j, Y') }}</span></div>
                        <div class="gallery-card-actions">
                            <a href="{{ url('/admin/gallery/' . $album->id) }}" title="Add or view photos"><i class="fas fa-images"></i><span>Manage</span></a>
                            <a href="{{ url('/admin/gallery/edit/' . $album->id) }}" title="Edit album"><i class="fas fa-pen"></i><span>Edit</span></a>
                            <button type="button" class="delete-gallery" data-url="{{ url('/admin/gallery/delete/' . $album->id) }}" data-name="{{ $album->name }}" title="Delete album"><i class="far fa-trash-alt"></i><span>Delete</span></button>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    @endif
</div>
@endsection

@push('styles')
<style>
.gallery-admin{--gallery-ink:#17324a;--gallery-muted:#718195;--gallery-line:#e5eaf0;--gallery-gold:#b58b43;color:var(--gallery-ink)}
.gallery-page-heading{display:flex;align-items:center;justify-content:space-between;gap:20px;margin:3px 0 23px}.gallery-page-heading h1{margin:3px 0;color:#102d47;font-size:clamp(23px,3vw,31px);font-weight:800;letter-spacing:-.035em}.gallery-page-heading p:not(.gallery-eyebrow){margin:5px 0 0;color:var(--gallery-muted);font-size:13px}.gallery-eyebrow{margin:0;color:#a47a31;font-size:9px;font-weight:900;letter-spacing:.15em}.gallery-primary-action{display:inline-flex;align-items:center;justify-content:center;gap:9px;min-height:42px;padding:0 16px;border:0;border-radius:9px;background:#102d47;color:#fff;font-size:12px;font-weight:750;text-decoration:none;box-shadow:0 6px 16px #102d471f;transition:transform .18s,background .18s}.gallery-primary-action:hover{transform:translateY(-1px);background:#1a4264;color:white}.gallery-toolbar{display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:17px;padding:14px 17px;border:1px solid var(--gallery-line);border-radius:12px;background:white}.gallery-toolbar>div{font-size:12px;color:var(--gallery-muted)}.gallery-toolbar strong{color:var(--gallery-ink);font-size:18px}.gallery-toolbar form{display:flex;align-items:center;gap:10px}.gallery-search{display:flex;align-items:center;gap:9px;width:min(340px,55vw);height:38px;padding:0 6px 0 12px;border:1px solid #dfe5eb;border-radius:8px;color:#8493a2}.gallery-search input{width:100%;min-width:0;border:0;outline:0;color:#263e55;font-size:11px}.gallery-search button{height:27px;padding:0 11px;border:0;border-radius:6px;background:#eef2f5;color:#345169;font-size:10px;font-weight:700}.gallery-reset{color:#8a672c;font-size:11px;text-decoration:none}.gallery-album-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:18px}.gallery-album-card{overflow:hidden;border:1px solid var(--gallery-line);border-radius:13px;background:white;box-shadow:0 5px 18px #112f4708;transition:transform .2s,box-shadow .2s}.gallery-album-card:hover{transform:translateY(-3px);box-shadow:0 12px 26px #112f4715}.gallery-album-cover{position:relative;display:block;height:205px;overflow:hidden;background:#edf1f4}.gallery-album-cover img{position:relative;z-index:1;width:100%;height:100%;object-fit:cover;transition:transform .4s}.gallery-album-card:hover .gallery-album-cover img{transform:scale(1.04)}.gallery-cover-fallback{position:absolute;inset:0;display:grid;place-items:center;color:#bac6d0;font-size:35px;background:linear-gradient(135deg,#f3f5f6,#e5eaee)}.gallery-album-cover.is-image-missing img{display:none}.gallery-photo-count{position:absolute;z-index:2;right:11px;bottom:11px;padding:7px 10px;border:1px solid #ffffff80;border-radius:20px;background:#102d47dc;color:white;font-size:9px;font-weight:700;backdrop-filter:blur(6px)}.gallery-photo-count i{margin-right:4px;color:#eacb88}.gallery-album-info{padding:14px 15px 12px}.gallery-album-title{display:flex;align-items:flex-start;justify-content:space-between;gap:10px}.gallery-album-title h2{overflow:hidden;margin:0;font-size:14px;font-weight:800;text-overflow:ellipsis;white-space:nowrap}.gallery-album-title h2 a{color:#17324a;text-decoration:none}.gallery-album-title>span{flex:none;padding-top:2px;color:#91a0ae;font-size:9px}.gallery-card-actions{display:flex;gap:7px;margin-top:13px;padding-top:10px;border-top:1px solid #eff2f4}.gallery-card-actions a,.gallery-card-actions button{display:inline-flex;align-items:center;gap:6px;padding:6px 9px;border:1px solid #e6ebef;border-radius:6px;background:#fff;color:#536b80;font-size:9px;font-weight:700;text-decoration:none;cursor:pointer}.gallery-card-actions a:hover,.gallery-card-actions button:hover{border-color:#d5bc86;color:#8f6929}.gallery-card-actions button:last-child{margin-left:auto;color:#a65c5c}.gallery-empty-state{display:grid;justify-items:center;padding:65px 20px;border:1px dashed #d6dfe6;border-radius:14px;background:#fff;text-align:center}.gallery-empty-icon{display:grid;width:60px;height:60px;place-items:center;border-radius:18px;background:#f4f0e7;color:#ae8748;font-size:23px}.gallery-empty-state h2{margin:16px 0 5px;color:#17324a;font-size:18px;font-weight:800}.gallery-empty-state p{max-width:420px;margin:0 0 18px;color:var(--gallery-muted);font-size:12px;line-height:1.7}.gallery-empty-state .gallery-primary-action{margin-top:4px}
@media(max-width:1000px){.gallery-album-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.gallery-album-cover{height:190px}}
@media(max-width:600px){.gallery-page-heading{align-items:flex-start}.gallery-primary-action{min-height:38px;padding:0 11px;font-size:10px}.gallery-page-heading .gallery-primary-action{font-size:0;gap:0}.gallery-page-heading .gallery-primary-action i{font-size:12px}.gallery-toolbar{align-items:flex-start;flex-direction:column}.gallery-toolbar form,.gallery-search{width:100%}.gallery-album-grid{grid-template-columns:1fr;gap:13px}.gallery-album-cover{height:220px}.gallery-card-actions a,.gallery-card-actions button{padding:7px 8px}.gallery-album-title h2{font-size:13px}}
</style>
@endpush

@push('scripts')
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
document.querySelectorAll('.delete-gallery').forEach(function(button){button.addEventListener('click',function(){var item=this;swal({title:'Delete this album?',text:'“'+item.dataset.name+'” and its album record will be removed.',icon:'warning',buttons:['Cancel','Delete'],dangerMode:true}).then(function(confirmDelete){if(!confirmDelete)return;fetch(item.dataset.url,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'}}).then(function(response){if(!response.ok)throw new Error();return response.json().catch(function(){return {}})}).then(function(){window.location.reload()}).catch(function(){swal('Could not delete album','Please refresh the page and try again.','error')})})})});
</script>
@endpush

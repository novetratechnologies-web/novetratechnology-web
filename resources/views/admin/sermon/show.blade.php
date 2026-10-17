@extends('layouts.admin.layout')

@section('content')
<create-series sermons_id="{{ $sermon->id }}" base_url="/admin"></create-series>
<edit-series base_url="/admin"></edit-series>
<input type="hidden" id="edit_sermon_id" value="">

@php
    $sermonYoutubeThumbnail = $sermonlinks->getCollection()->first(fn ($resource) => $resource->youtube_thumbnail_url)?->youtube_thumbnail_url;
    $sermonArtwork = $sermon->CoverImagePath ?: $sermonYoutubeThumbnail;
@endphp

<div class="sermon-manage-page">
    <nav class="sermon-manage-crumb"><a href="{{ url('/admin/sermons') }}">Sermon library</a><span>/</span><span>Manage message</span></nav>
    <header class="sermon-manage-hero">
        <div class="sermon-manage-cover"><span aria-hidden="true">✦</span>@if($sermonArtwork)<img src="{{ $sermonArtwork }}" alt="">@endif</div>
        <div class="sermon-manage-copy"><p>MESSAGE WORKSPACE</p><h1>{{ $sermon->title }}</h1><span>{{ \Illuminate\Support\Str::limit(strip_tags($sermon->description ?? 'Add and manage recordings and study resources for this sermon.'), 180) }}</span><div class="sermon-manage-tags"><b>{{ $sermonlinks->total() }} {{ \Illuminate\Support\Str::plural('resource', $sermonlinks->total()) }}</b><b>Published {{ $sermon->created_at?->format('M j, Y') }}</b></div></div>
        <div class="sermon-manage-actions"><a href="{{ url('/admin/sermon/edit/' . $sermon->id) }}">Edit sermon</a><button type="button" onclick="document.querySelector('create-series button')?.click()">＋ Add resource</button></div>
    </header>

    <section class="sermon-resource-section">
        <div class="sermon-resource-heading"><div><p>VIDEO · AUDIO · STUDY NOTES</p><h2>Message resources</h2><span>Each resource can include a recording, audio version, or downloadable PDF.</span></div><button type="button" onclick="document.querySelector('create-series button')?.click()">＋ Add resource</button></div>
        @if($sermonlinks->isNotEmpty())
            <div class="sermon-resource-list">
                @foreach($sermonlinks as $index => $sermonlink)
                    <article class="sermon-resource-row">
                        <div class="sermon-resource-number">{{ str_pad((string) ($sermonlinks->firstItem() + $index), 2, '0', STR_PAD_LEFT) }}</div>
                        <div class="sermon-resource-main"><h3>{{ $sermonlink->title ?: 'Message resource' }}</h3><span>{{ $sermonlink->date ? \Carbon\Carbon::parse($sermonlink->date)->format('F j, Y') : 'Date not set' }}</span></div>
                        <div class="sermon-resource-badges">
                            @if($sermonlink->video_link)<span class="has-resource">▶ Video</span>@else<span>Video</span>@endif
                            @if($sermonlink->audio_link)<span class="has-resource">♫ Audio</span>@else<span>Audio</span>@endif
                            @if($sermonlink->pdf_link)<span class="has-resource">⇩ PDF</span>@else<span>PDF</span>@endif
                        </div>
                        <div class="sermon-resource-controls"><button type="button" onclick="editChapter({{ $sermonlink->id }})">Edit</button><button type="button" class="delete-link" data-url="{{ url('/admin/links/delete/' . $sermonlink->id) }}">Delete</button></div>
                    </article>
                @endforeach
            </div>
            @if($sermonlinks->hasPages())<div class="sermon-resource-pagination">{{ $sermonlinks->links() }}</div>@endif
        @else
            <div class="sermon-resource-empty"><span aria-hidden="true">▶</span><h3>No resources yet</h3><p>Add the sermon recording, audio, or a PDF outline. These will appear together on the public sermon page.</p><button type="button" onclick="document.querySelector('create-series button')?.click()">Add the first resource</button></div>
        @endif
    </section>
</div>
@endsection

@push('styles')
<style>
.sermon-manage-page{--sm-navy:#082846;--sm-gold:#c99b45;--sm-muted:#718194;max-width:1440px;margin:0 auto;padding:8px 3px 38px;color:#18334d}create-series>.flex.flex-row.justify-between.items-center.mb-3{display:none}.sermon-manage-crumb{display:flex;gap:9px;margin:0 0 12px;color:#8491a0;font-size:11px}.sermon-manage-crumb a{color:#8a672b;text-decoration:none;font-weight:700}.sermon-manage-hero{display:flex;align-items:center;gap:19px;padding:20px;border:1px solid #e5eaf0;border-radius:15px;background:#fff;box-shadow:0 8px 25px #08284608}.sermon-manage-cover{position:relative;width:146px;height:112px;flex:none;display:grid;place-items:center;overflow:hidden;border-radius:11px;background:radial-gradient(circle at 80% 15%,#d8ad5d4a,transparent 35%),linear-gradient(135deg,#082846,#205071);color:#e8c578;font-size:27px}.sermon-manage-cover img{position:absolute;width:100%;height:100%;object-fit:cover}.sermon-manage-copy{min-width:0;flex:1}.sermon-manage-copy>p,.sermon-resource-heading p{margin:0 0 6px;color:#a7792a;font-size:9px;font-weight:900;letter-spacing:.14em}.sermon-manage-copy h1{margin:0 0 6px;color:var(--sm-navy);font-size:clamp(20px,3vw,28px);line-height:1.2;letter-spacing:-.03em}.sermon-manage-copy>span{display:block;max-width:680px;color:var(--sm-muted);font-size:11px;line-height:1.55}.sermon-manage-tags{display:flex;gap:8px;flex-wrap:wrap;margin-top:11px}.sermon-manage-tags b{padding:5px 8px;border-radius:99px;background:#f3f5f6;color:#5a6f82;font-size:9px}.sermon-manage-actions{display:flex;flex-direction:column;gap:8px;flex:none}.sermon-manage-actions a,.sermon-manage-actions button,.sermon-resource-heading>button{display:inline-flex;align-items:center;justify-content:center;min-height:37px;padding:0 13px;border:1px solid #dfe5ea;border-radius:8px;background:#fff;color:var(--sm-navy);font-size:10px;font-weight:800;text-decoration:none;cursor:pointer}.sermon-manage-actions button,.sermon-resource-heading>button{border-color:var(--sm-navy);background:var(--sm-navy);color:#fff}.sermon-resource-section{margin-top:22px;padding:19px;border:1px solid #e5eaf0;border-radius:15px;background:#fff}.sermon-resource-heading{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:16px}.sermon-resource-heading h2{margin:0;color:var(--sm-navy);font-size:20px;letter-spacing:-.025em}.sermon-resource-heading span{display:block;margin-top:4px;color:var(--sm-muted);font-size:11px}.sermon-resource-list{display:grid;gap:9px}.sermon-resource-row{display:flex;align-items:center;gap:14px;padding:12px;border:1px solid #e9edf0;border-radius:10px;background:#fcfdfd}.sermon-resource-number{width:36px;height:36px;display:grid;place-items:center;flex:none;border-radius:9px;background:#f3efe5;color:#916b2d;font-size:11px;font-weight:900}.sermon-resource-main{min-width:130px;flex:1}.sermon-resource-main h3{margin:0 0 4px;color:#203a53;font-size:12px}.sermon-resource-main span{color:#8a97a4;font-size:10px}.sermon-resource-badges{display:flex;gap:5px;flex-wrap:wrap}.sermon-resource-badges span{padding:6px 8px;border:1px solid #e7eaed;border-radius:6px;color:#a0a9b2;font-size:9px;font-weight:700}.sermon-resource-badges span.has-resource{border-color:#dce9e0;background:#eef6f0;color:#427653}.sermon-resource-controls{display:flex;gap:6px}.sermon-resource-controls button{padding:7px 9px;border:1px solid #dfe5ea;border-radius:6px;background:#fff;color:#355b7c;font-size:9px;font-weight:800;cursor:pointer}.sermon-resource-controls button.delete-link{color:#a45252}.sermon-resource-empty{padding:42px 18px;text-align:center;border:1px dashed #dce3e8;border-radius:10px}.sermon-resource-empty>span{color:#c49a4b;font-size:24px}.sermon-resource-empty h3{margin:8px 0 5px;color:var(--sm-navy);font-size:16px}.sermon-resource-empty p{max-width:410px;margin:0 auto 13px;color:var(--sm-muted);font-size:11px;line-height:1.6}.sermon-resource-empty button{padding:9px 13px;border:0;border-radius:8px;background:var(--sm-navy);color:#fff;font-size:10px;font-weight:800;cursor:pointer}.sermon-resource-pagination{margin-top:14px}
@media(max-width:780px){.sermon-manage-hero{align-items:flex-start;flex-wrap:wrap}.sermon-manage-cover{width:105px;height:90px}.sermon-manage-copy{min-width:calc(100% - 130px)}.sermon-manage-actions{width:100%;flex-direction:row}.sermon-manage-actions>*{flex:1}.sermon-resource-row{align-items:flex-start;flex-wrap:wrap}.sermon-resource-main{flex-basis:calc(100% - 60px)}.sermon-resource-badges{padding-left:50px;flex:1}.sermon-resource-controls{margin-left:auto}}@media(max-width:520px){.sermon-manage-hero{padding:14px;gap:12px}.sermon-manage-cover{width:74px;height:74px}.sermon-manage-copy{min-width:calc(100% - 90px)}.sermon-manage-copy h1{font-size:19px}.sermon-resource-section{padding:13px}.sermon-resource-heading{align-items:flex-start}.sermon-resource-heading>button{white-space:nowrap;padding:0 9px}.sermon-resource-heading h2{font-size:18px}.sermon-resource-heading span{max-width:220px}.sermon-resource-row{gap:9px}.sermon-resource-badges{padding-left:45px}}
</style>
@endpush

@push('scripts')
<script>
function editChapter(id){$('#edit_sermon_id').val(id);$('#edit-series-modal').click();}
$(document).on('click','.delete-link',function(){var link=$(this).data('url');if(!window.swal){if(confirm('Delete this resource?'))deleteChapter(link);return;}swal({icon:'warning',text:'Delete this sermon resource?',buttons:{cancel:true,confirm:{text:'Delete',className:'btn-danger'}}}).then(function(ok){if(ok)deleteChapter(link);});});
function deleteChapter(link){$.ajax({url:link,type:'DELETE',headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')},success:function(){window.location.reload();},error:function(){alert('Could not delete this resource. Please try again.');}});}
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endpush


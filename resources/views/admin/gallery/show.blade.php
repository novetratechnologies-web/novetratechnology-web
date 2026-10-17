@extends('layouts.admin.layout')

@section('content')
<div class="gallery-manage-page">
    <a href="{{ url('/admin/gallery') }}" class="gallery-back-link"><i class="fas fa-arrow-left"></i> All albums</a>
    <header class="gallery-manage-heading">
        <div class="gallery-manage-icon"><i class="far fa-images"></i></div>
        <div class="gallery-manage-title"><p class="gallery-eyebrow">ALBUM WORKSPACE</p><h1>{{ $gallery->name }}</h1><p>Upload photographs and manage the images in this collection.</p></div>
        <a class="gallery-edit-action" href="{{ url('/admin/gallery/edit/' . $gallery->id) }}"><i class="fas fa-pen"></i> Edit album</a>
    </header>
    <section class="gallery-manage-card">
        <div class="gallery-manage-card-heading"><div><h2>Album photos</h2><p>Add multiple photos at once. JPG, PNG and WebP images are supported.</p></div><span><i class="fas fa-cloud-arrow-up"></i> Upload photos</span></div>
        <div class="gallery-upload-panel"><showgallery url="{{ url('/') }}" gallery_id="{{ $gallery->id }}" name="{{ $gallery->name }}"></showgallery></div>
    </section>
</div>
@endsection

@push('styles')
<style>
.gallery-manage-page{max-width:1180px;margin:0 auto}.gallery-back-link{display:inline-flex;align-items:center;gap:8px;margin:2px 0 17px;color:#6d7f8f;font-size:11px;font-weight:700;text-decoration:none}.gallery-back-link:hover{color:#9b742f}.gallery-manage-heading{display:flex;align-items:center;gap:15px;margin-bottom:22px}.gallery-manage-icon{display:grid;width:51px;height:51px;flex:none;place-items:center;border-radius:14px;background:#f3efe5;color:#a77d34;font-size:20px}.gallery-manage-title{min-width:0;flex:1}.gallery-eyebrow{margin:0;color:#a47a31;font-size:9px;font-weight:900;letter-spacing:.15em}.gallery-manage-title h1{margin:3px 0;color:#102d47;font-size:clamp(22px,3vw,30px);font-weight:800;letter-spacing:-.03em}.gallery-manage-title>p:last-child{margin:4px 0 0;color:#718195;font-size:12px}.gallery-edit-action{display:inline-flex;align-items:center;gap:8px;padding:10px 13px;border:1px solid #dfe6eb;border-radius:8px;background:#fff;color:#345169;font-size:10px;font-weight:750;text-decoration:none}.gallery-manage-card{overflow:hidden;border:1px solid #e4eaf0;border-radius:13px;background:#fff;box-shadow:0 8px 26px #12334a0a}.gallery-manage-card-heading{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:17px 20px;border-bottom:1px solid #edf0f3}.gallery-manage-card-heading h2{margin:0;color:#18344c;font-size:14px;font-weight:800}.gallery-manage-card-heading p{margin:4px 0 0;color:#83909c;font-size:10px}.gallery-manage-card-heading>span{display:flex;align-items:center;gap:7px;color:#9a742f;font-size:10px;font-weight:800}.gallery-upload-panel{padding:18px 20px 22px}.gallery-upload-panel .image-container{min-height:190px;margin:0 0 16px;border:1.5px dashed #cbd6df;border-radius:12px;background:#f8fafb}.gallery-upload-panel .image-center{min-height:190px}.gallery-upload-panel .drag-text{color:#31506a;font-size:12px}.gallery-upload-panel .browse-text{display:inline-block;margin-top:4px;padding:8px 14px;border-radius:7px;background:#102d47;color:#fff;font-size:10px;font-weight:700}.gallery-upload-panel .image-icon-drag{width:42px;height:42px}.gallery-upload-panel .image-list-item{overflow:hidden;border-radius:9px}.gallery-upload-panel .image-list-item img{object-fit:cover}.gallery-upload-panel .submit-btn{min-height:40px;border:0!important;border-radius:8px!important;background:#102d47!important;color:#fff!important;font-size:11px!important;font-weight:750}.gallery-upload-panel .alert{margin:0 0 12px;border-radius:8px;font-size:11px}.gallery-upload-panel .modal-mask{z-index:13000}.gallery-upload-panel .VueCarousel-slide img{max-height:65vh;object-fit:contain;background:#12202c}.gallery-upload-panel .modal-container{border-radius:14px}.gallery-upload-panel h2{font-style:normal!important;font-weight:800!important;color:#19364f!important}
@media(max-width:600px){.gallery-manage-heading{align-items:flex-start;flex-wrap:wrap}.gallery-manage-icon{width:43px;height:43px}.gallery-edit-action{margin-left:58px}.gallery-manage-card-heading{align-items:flex-start;padding:15px}.gallery-manage-card-heading>span{font-size:0}.gallery-manage-card-heading>span i{font-size:14px}.gallery-upload-panel{padding:13px}.gallery-upload-panel .image-container,.gallery-upload-panel .image-center{min-height:160px}}
</style>
@endpush

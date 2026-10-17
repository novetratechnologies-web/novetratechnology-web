<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('uploads/brand/igm-church-favicon.png') }}?v=2">
    <title>{{ config('app.name', 'IGM Church') }} :: Admin Panel</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-navigation.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/trix.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        html{scroll-behavior:smooth}body{min-height:100vh;height:auto;margin:0;gap:0;background:#f3f6f8;font-family:Arial,sans-serif}.admin-shell{display:flex;min-height:calc(100vh - 64px);align-items:stretch}.admin-content{min-width:0;flex:1;overflow-x:hidden;background:#f3f6f8;padding:20px clamp(12px,2vw,27px)}.admin-main{width:100%;max-width:1600px;margin:0 auto}@media(max-width:767px){.admin-shell{min-height:calc(100vh - 58px)}.admin-content{padding:15px 12px 26px}}
    </style>
    @stack('styles')
</head>
<body>
    <div id="app">
        @include('layouts.partials.navigation')
        <div class="admin-shell">
            @include('layouts.admin.sidebar')
            <div class="admin-content"><main class="admin-main">@yield('content')</main></div>
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="{{ asset('js/trix.js') }}"></script>
    <script src="{{ asset('js/attachments.js') }}"></script>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    @stack('scripts')
</body>
</html>

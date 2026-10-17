<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'IGM Church') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('uploads/brand/igm-church-favicon.png') }}?v=2">
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- g-captcha -->
</head>

<body>
    <div id="app">
        <main>
            <div class="login-page flex flex-col items-center justify-between">
                <div class="flex-1 flex items-center w-full">
                    @yield('content')
                </div>
                <p class="text-xs text-white text-opacity-60 py-4">
                    <span class="text-white text-opacity-80">IGM Church</span>
                </p>
            </div>
        </main>
    </div>
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>



    @stack('scripts')
    <style>
        .login-page {
            background: linear-gradient(160deg, #0f172a 0%, #1e3a5f 45%, #0e7490 100%);
            min-height: 100vh;
            padding: 40px 0;
            position: relative;
        }

        .alert-success {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #def0d8;
            color: #445441;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .alert-warning {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            background: #f8e8c1;
            border-color: #f4d899;
            color: #856e34;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        @media(max-width: 640px) {
            .login-page {
                padding: 15px;
            }
        }

    </style>
</body>

</html>

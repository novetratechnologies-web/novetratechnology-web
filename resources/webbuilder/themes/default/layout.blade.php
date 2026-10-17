<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('theme::_seo_meta')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @include('theme::_header_styles')
    @stack('styles')
</head>
<body class="bg-gray-50 text-gray-800">

    @if(request()->routeIs('web.home'))
        <div class="igm-topline">
            <strong>Worship with us</strong>&nbsp; · &nbsp;
            @if(isset($serviceSchedule) && $serviceSchedule->isNotEmpty())
                {{ $serviceSchedule->map(fn ($schedule) => \Carbon\Carbon::now()->startOfWeek(\Carbon\Carbon::SUNDAY)->addDays((int) $schedule->day_of_week)->format('D') . ' ' . \Carbon\Carbon::parse($schedule->service_time)->format('g:i A'))->implode('  |  ') }}
            @else
                Join us for worship and community this Sunday
            @endif
            @if(!empty($_church->address))&nbsp; · &nbsp;{{ $_church->address }}@endif
        </div>
    @endif

    @include('theme::_nav_bar')

    <main>
        @yield('content')
    </main>

    @include('theme::_footer')

    <script src="{{ mix('js/app.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('scripts')
    @if(isset($liveStream) && $liveStream?->playback_url)
        <script src="https://cdn.jsdelivr.net/npm/hls.js@1"></script>
        <script>
            document.querySelectorAll('[data-live-hls]').forEach((player) => {
                const source = player.dataset.streamUrl;
                const showError = () => {
                    const error = player.parentElement?.querySelector('.live-error');
                    if (error) error.hidden = false;
                };
                if (window.Hls && Hls.isSupported()) {
                    const hls = new Hls();
                    hls.loadSource(source);
                    hls.attachMedia(player);
                    hls.on(Hls.Events.ERROR, (_event, data) => { if (data.fatal) showError(); });
                } else if (player.canPlayType('application/vnd.apple.mpegurl')) {
                    player.src = source;
                    player.addEventListener('error', showError);
                } else {
                    showError();
                }
            });
        </script>
    @endif
    @if(!empty($streamAutoStatusEnabled))
        <script>
            (() => {
                const initialLiveState = @json((bool) ($liveStream?->is_live ?? false));
                const statusUrl = @json(url('/api/streaming/public-status'));
                window.setInterval(async () => {
                    try {
                        const response = await fetch(statusUrl, { headers: { Accept: 'application/json' }, cache: 'no-store' });
                        if (!response.ok) return;
                        const status = await response.json();
                        if (Boolean(status.is_live) !== initialLiveState) window.location.reload();
                    } catch (_) {}
                }, 15000);
            })();
        </script>
    @endif
</body>
</html>

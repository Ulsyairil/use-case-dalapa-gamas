<!DOCTYPE html>
<html lang="zxx" class="js">

<head>
    <base href="./">
    <meta charset="utf-8">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" >
    
    {{-- Title --}}
    <title>@yield('title') | {{ config('app.name') }}</title>

    {{-- Template & Vendor CSS --}}
    <link rel="stylesheet" href="/css/dashlite.css?ver=3.3.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="/css/libs/notfy.css">

     {{-- Vendor CSS --}}
    @yield('vendor_css')

    {{-- App CSS --}}
    <link rel="stylesheet" href="/css/app.css">

    {{-- Custom CSS --}}
    @yield('custom_css')
</head>

<body class="nk-body bg-white npc-default pg-auth">
    <div class="nk-app-root">
        {{-- Main --}}
        <div class="nk-main ">
            {{-- Wrap --}}
            <div class="nk-wrap nk-wrap-nosidebar">
                {{-- Content --}}
                <div class="nk-content ">
                    @yield('content')
                </div>
                {{-- End Content --}}
            </div>
            {{-- End Wrap --}}
        </div>
        {{-- End Main --}}
    </div>

    {{-- JavaScript --}}
    <script src="/js/bundle.js?ver=3.3.0"></script>
    <script src="/js/scripts.js?ver=3.3.0"></script>
    <script src="/js/libs/moment.js"></script>
    <script src="/js/libs/moment-timezone.js"></script>
    <script src="/js/libs/notfy.js"></script>
    <script src="/js/libs/loading-overlay.js"></script>

    {{-- Vendor JS --}}
    @yield('vendor_js')

    {{-- Set Global Variables --}}
    <script type="text/javascript">
        const locale = "{{ LaravelLocalization::getCurrentLocale() }}";
        const timezone = "{{ config('app.custom.timezone') }}";
    </script>

    {{-- App JS --}}
    @php
        use Illuminate\Support\Str;
        $hash = Str::random(32);
    @endphp
    <script src="/js/app.js?hash={{ $hash }}"></script>

    {{-- Custom JS --}}
    @yield('custom_js')
</html>
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

<body class="nk-body bg-lighter npc-default has-sidebar">
    <div class="nk-app-root">
        {{-- Main --}}
        <div class="nk-main ">
            {{-- Sidebar --}}
            @include('components.sidebar')

            {{-- Wrap --}}
            <div class="nk-wrap ">
                {{-- Main header --}}
                @include('components.header')
                {{-- End Main header --}}

                {{-- Content --}}
                <div class="nk-content ">
                    <div class="container-fluid">
                        @yield('content')
                    </div>
                </div>
                {{-- End Content --}}

                {{-- Footer --}}
                <div class="nk-footer">
                    <div class="container-fluid">
                        <div class="nk-footer-wrap">
                            <div class="nk-footer-copyright"> &copy; {{ date('Y') }} {{ config('app.name') }} </div>
                            <div class="nk-footer-links">
                                <ul class="nav nav-sm">
                                    <a id="darkModeToggle" href="#" class="link link-primary fw-normal py-2 px-3 fs-13px">
                                        <i class="fas fa-moon mr-1"></i>
                                    </a>

                                    <li class="nav-item dropup">
                                        <a class="dropdown-toggle dropdown-indicator has-indicator link link-primary fw-normal py-2 px-3 fs-13px"
                                            data-bs-toggle="dropdown" data-offset="0,10">
                                            <span>{{ LaravelLocalization::getCurrentLocale() == 'en' ? 'English' : 'Indonesia' }}</span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-end">
                                            <ul class="language-list">
                                                <li>
                                                    <a href="javascript:void(0)" class="language-item" data-value="en" onclick="changeLocale(this)">
                                                        <img src="/images/flags/english.png" alt="" class="language-flag">
                                                        <span class="language-name">English</span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" class="language-item" data-value="id" onclick="changeLocale(this)">
                                                        <img src="/images/flags/indonesia.png" alt="" class="language-flag">
                                                        <span class="language-name">Indonesia</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End Footer --}}
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
        const message = {
            'no_data_available': "{{ __('custom.no_data_available') }}",
            'first': "{{ __('custom.first') }}",
            'last': "{{ __('custom.last') }}",
            'previous': "{{ __('custom.previous') }}",
            'next': "{{ __('custom.next') }}",
            'detail' : "{{ __('custom.detail') }}",
            'total_records': "{{ __('custom.total_records') }}",
        };
    </script>

    {{-- App JS --}}
    @php
        use Illuminate\Support\Str;
        $hash = Str::random(32);
    @endphp
    <script src="/js/app.js?hash={{ $hash }}"></script>

    {{-- Custom JS --}}
    @yield('custom_js')
</body>

</html>
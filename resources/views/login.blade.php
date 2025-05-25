@extends('layout.auth')

@section('title', 'Login')

@section('content')
<div class="nk-block nk-block-middle nk-auth-body  wide-xs">
    {{-- <div class="brand-logo pb-4 text-center">
        <a href="/" class="logo-link">
            <img class="logo-light logo-img logo-img-lg" src="./images/logo.png" srcset="./images/logo2x.png 2x"
                alt="logo">
            <img class="logo-dark logo-img logo-img-lg" src="./images/logo-dark.png"
                srcset="./images/logo-dark2x.png 2x" alt="logo-dark">
        </a>
    </div> --}}

    <div class="card card-bordered shadow">
        <div class="card-inner card-inner-lg">
            <div class="nk-block-head">
                <div class="nk-block-head-content">
                    <h4 class="nk-block-title">{{ __('custom.sign_in') }}</h4>
                    {{-- <div class="nk-block-des">
                        <p>Access the Dashlite panel using your email and passcode.</p>
                    </div> --}}
                </div>
            </div>

            <form id="login_form">
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="username">{{ __('custom.email_or_username') }}</label>
                    </div>
                    <div class="form-control-wrap">
                        <input type="text" class="form-control form-control-lg" id="username"
                            placeholder="{{ __('custom.email_or_username_placeholder') }}">
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-label-group">
                        <label class="form-label" for="password">{{ __('custom.password') }}</label>
                        {{-- <a class="link link-primary link-sm" href="">Forgot Code?</a> --}}
                    </div>
                    <div class="form-control-wrap">
                        <a href="#" class="form-icon form-icon-right passcode-switch lg" data-target="password">
                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                        </a>
                        <input type="password" class="form-control form-control-lg" id="password"
                            placeholder="{{ __('custom.password_placeholder') }}">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-lg btn-primary btn-block">{{ __('custom.sign_in') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="nk-footer nk-auth-footer-full">
    <div class="container wide-lg">
        <div class="row g-3">
            <div class="col-lg-6 order-lg-last">
                <ul class="nav nav-sm justify-content-center justify-content-lg-end">
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
                                    <a href="javascript:void(0)" class="language-item" data-value="en"
                                        onclick="changeLocale(this)">
                                        <img src="/images/flags/english.png" alt="" class="language-flag">
                                        <span class="language-name">English</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="language-item" data-value="id"
                                        onclick="changeLocale(this)">
                                        <img src="/images/flags/indonesia.png" alt="" class="language-flag">
                                        <span class="language-name">Indonesia</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="nk-block-content text-center text-lg-left">
                    <p class="text-soft">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
    <script>
        $("#login_form").submit(function (e) { 
            e.preventDefault();
            login();
        });
    </script>
@endsection
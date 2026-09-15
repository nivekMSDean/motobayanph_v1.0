<!doctype html>
<html lang="{{ locale() }}">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <base href="{{ $shop_base_url }}">

    <!-- Title and Meta Description -->
    <title>@yield('title', system_setting('base.meta_title', 'MotoBayan PH | Motorcycle Parts & Riding Gear'))</title>
    <meta name="keywords" content="@yield('keywords', system_setting('base.meta_keywords'))">
    <meta name="description" content="@yield('description', system_setting('base.meta_description'))">

    <!-- Open Graph Meta Tags -->
    <meta property="og:site_name" content="{{ system_setting('base.meta_title', 'MotoBayan PH') }}">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="@yield('title', system_setting('base.meta_title', 'MotoBayan PH | Motorcycle Parts & Riding Gear'))">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:description" content="@yield('description', system_setting('base.meta_description'))">
    <meta property="og:image" content="@yield('og_image', image_origin(system_setting('base.logo')))">
    <meta property="og:image:secure_url" content="@yield('og_image', image_origin(system_setting('base.logo')))">
    <meta property="og:image:width" content="@yield('og_image_width', '300')">
    <meta property="og:image:height" content="@yield('og_image_height', '300')">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', system_setting('base.meta_title', 'MotoBayan PH | Motorcycle Parts & Riding Gear'))">
    <meta name="twitter:description" content="@yield('description', system_setting('base.meta_description'))">

    <!-- Generator Meta -->
    <meta name="generator" content="BeikeShop v{{ config('beike.version') }}({{ config('beike.build') }})">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ image_origin(system_setting('base.favicon')) }}">

    <!-- CSS and JS -->
    <link rel="stylesheet" type="text/css" href="{{ mix('/build/beike/shop/'.system_setting('base.theme').'/css/bootstrap.css') }}">
    <script src="{{ asset('vendor/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('vendor/layer/3.5.1/layer.js') }}"></script>
    <script src="{{ asset('vendor/lazysizes/lazysizes.min.js') }}" defer></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ mix('/build/beike/shop/'.system_setting('base.theme').'/js/app.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ mix('/build/beike/shop/'.system_setting('base.theme').'/css/app.css') }}">
    @if (system_setting('base.theme') === 'default')
      <link rel="stylesheet" type="text/css" href="{{ asset('/build/beike/shop/default/css/motobayan.css') }}">
    @endif

    @if (system_setting('base.head_code'))
      {!! system_setting('base.head_code') !!}
    @endif

    @hook('layout.header.code')
    @stack('header')

    @renderStyles
    @renderScripts
  </head>
<body class="@yield('body-class') {{ request('_from') }}">
  @if(trim($__env->yieldContent('bk-page-loading')))
    @include('shared.bk-page-loading')
  @endif
  @if (!request('iframe') && request('_from') != 'app')
    @hook('layout.master.header.before')
    @include('layout.header')
    @hook('layout.master.header.after')
  @endif

  @hook('layout.master.content.before')
  @yield('content')
  @hook('layout.master.content.after')

  @if (!request('iframe') && request('_from') != 'app')
    @hook('layout.master.footer.before')
    @include('layout.footer')
    @hook('layout.master.footer.after')
  @endif

  <script>
    @hook('layout.master.script.before')

    const config = {
      isLogin: !!{{ current_customer()->id ?? 'null' }},
      guestCheckout: !!{{ system_setting('base.guest_checkout', 1) }},
      loginShowPrice: !!{{ system_setting('base.show_price_after_login', 0) }},
      productImageOriginWidth: @json((int)system_setting('base.product_image_origin_width', 800)),
      productImageOriginHeight: @json((int)system_setting('base.product_image_origin_height', 800)),
    }

    // Load the ElementUI locale without document.write(), which is fragile after page load.
    @if (locale() != 'zh_cn')
      if (typeof ELEMENT !== 'undefined') {
        const localeScript = document.createElement('script');
        localeScript.src = @json(asset('vendor/element-ui/language/'.locale().'.js'));
        localeScript.async = true;
        localeScript.onload = () => {
          if (ELEMENT.lang?.[@json(locale())]) {
            ELEMENT.locale(ELEMENT.lang[@json(locale())]);
          }
        };
        document.head.appendChild(localeScript);
      }
    @endif
  </script>

  @if (strpos($_SERVER['SERVER_SOFTWARE'], 'nginx') !== false)
    <div class="nginx-alert d-none">{!! __('shop/common.nginx_alert') !!}</div>
  @endif

  @stack('add-scripts')
  @hook('layout.master.footer.code')
</body>
<!-- BeikeShop v{{ config('beike.version') }}({{ config('beike.build') }}) -->
</html>

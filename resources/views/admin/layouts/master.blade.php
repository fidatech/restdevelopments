<!DOCTYPE html>
<html lang="en" data-textdirection="ltr">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'REST')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Fonts --}}

    {{-- Vendor CSS --}}
    <link rel="stylesheet" href="{{ asset('admin/app-assets/vendors/css/vendors.min.css') }}">

    {{-- Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('admin/app-assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/app-assets/css/bootstrap-extended.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/app-assets/css/colors.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/app-assets/css/components.css') }}">

    {{-- Page CSS --}}
    <link rel="stylesheet" href="{{ asset('admin/app-assets/css/core/menu/menu-types/horizontal-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/app-assets/css/core/colors/palette-gradient.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.8/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('admin/assets/css/style.css') }}">

    @yield('vendor-style')
    @yield('page-style')
</head>

<body class="horizontal-layout horizontal-menu 2-columns  " data-open="hover" data-menu="horizontal-menu" data-color="bg-gradient-x-dark" data-col="2-columns">

    {{-- Header --}}
    @include('admin.layouts.partials.header')

    {{-- Main Menu --}}
    @include('admin.layouts.partials.horizontal-menu')

    {{-- Main Content --}}
    <div class="app-content content">
        <div class="content-wrapper">
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    @include('admin.layouts.partials.footer')

    {{-- Vendor JS --}}
    <script src="{{ asset('admin/app-assets/vendors/js/vendors.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.sticky/1.0.4/jquery.sticky.min.js"></script>
    <script src="{{ asset('admin/app-assets/js/core/app-menu.js') }}"></script>
    <script src="{{ asset('admin/app-assets/js/core/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('vendor-script')
    @yield('page-script')
</body>
</html>

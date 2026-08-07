<!DOCTYPE html>
<html lang="en" data-bs-theme="{{ Session::get('theme', 'light') }}" data-menu-color="dark"
    data-topbar-color="{{ Session::get('theme', 'light') }}">

<head>
    <meta charset="utf-8" />
    <title>{{ $setting->meta_title ?? config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ $setting->meta_title ?? config('app.name') }}" name="title" />
    <meta content="{{ $setting->meta_description ?? '' }}" name="description" />
    <meta content="TechWizi" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href={{ asset('assets/backend/images/favicon.png') }}>

    <!-- App css -->
    <link href="{{ asset('assets/backend/css/style.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/backend/css/fixes.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/backend/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/backend/libs/toastr/toastr.min.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{ asset('assets/backend/js/config.js') }}"></script>

    @stack('pageCss')

</head>

<body>

    <!-- Begin page -->
    <div class="layout-wrapper">

        <!-- ========== Left Sidebar ========== -->
        <div class="main-menu">
            <!-- Brand Logo -->
            @include('admin.layouts._app.logo')

            <!--- Menu -->

            <!-- Left Sidebar Start -->
            @include('admin.layouts._app.sidebar')
            <!-- Left Sidebar End -->

        </div>



        <!-- Start Page Content here -->
        <div class="page-content">

            <!-- ========== Topbar Start ========== -->
            <div class="navbar-custom">

                <!-- Topbar Start -->
                @include('admin.layouts._app.topbar')
                <!-- end Topbar -->
            </div>
            <!-- ========== Topbar End ========== -->

            <div class="px-3">

                <!-- Start Content-->
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="py-3 d-flex justify-content-between align-items-center">
                        @yield('breadcrumbs')
                        @yield('buttons')
                    </div>
                    <!-- end page title -->

                    {{ $slot }}

                </div> <!-- container -->

            </div> <!-- content -->

            <!-- Footer Start -->
            @include('admin.layouts._app.footer')
            <!-- end Footer -->

        </div>
        <!-- End Page content -->


    </div>
    <!-- END wrapper -->

    {{-- action::Begin --}}
    @include('admin.layouts._app.actions')
    {{-- action::End --}}

    <!-- App js -->
    <script src="{{ asset('assets/backend/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assets/backend/libs/toastr/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/backend/js/app.js') }}"></script>
    <x-toastr />

    @stack('pageScript')

</body>

</html>

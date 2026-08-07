<div class="logo-box">
    <!-- Brand Logo Light -->
    <a href="{{ route('dashboard') }}" class="logo-light">
        <img src="{{ $setting->app_logo }}" alt="logo" class="logo-lg" height="27">
        <img src="{{ $setting->app_logo }}" alt="small logo" class="logo-sm" height="24">
    </a>

    <!-- Brand Logo Dark -->
    <a href="{{ route('dashboard') }}" class="logo-dark">
        <img src="{{ $setting->app_logo }}" alt="dark logo" class="logo-lg" height="27">
        <img src="{{ $setting->app_logo }}" alt="small logo" class="logo-sm" height="24">
    </a>
</div>

<x-guest-layout>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex align-items-center min-vh-100">
                    <div class="w-100 d-block my-5 overflow-hidden">
                        <div class="row justify-content-center">
                            <div class="col-lg-6">

                                <div class="card rounded">
                                    <div class="p-5">
                                        <div class="text-center w-75 mx-auto auth-logo mb-4 bg-dark py-4 w-100">
                                            <a href="#" class="logo-dark">
                                                <span><img src="{{ $setting->app_logo }}" alt=""
                                                        height="32"></span>
                                            </a>

                                            <a href="#" class="logo-light">
                                                <span><img src="{{ $setting->app_logo }}" alt=""
                                                        height="32"></span>
                                            </a>
                                        </div>


                                        <h1 class="h5 mb-1">Welcome Back!</h1>

                                        <p class="text-muted mb-4">Enter your email address and password to access admin
                                            panel.</p>

                                        <form action="{{ route('login') }}" class="my-4" method="post">
                                            @csrf

                                            <div class="form-group mb-3">
                                                <x-input-label name="email" text="Email Address" />
                                                <x-input-field name="email" type="email" value="{{ old('email') }}"
                                                    placeholder="example@gmail.com" />
                                            </div>

                                            <div class="form-group mb-3">
                                                <x-input-label name="password" text="Password" />
                                                <x-input-field name="password" type="password"
                                                    placeholder="Enter your password" />
                                            </div>

                                            <div class="form-group d-flex mb-3">
                                                <div class="col-sm-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="checkbox-signin" name="remember" checked="">
                                                        <label class="form-check-label" for="checkbox-signin">Remember
                                                            me</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group mb-0 row">
                                                <div class="col-12">
                                                    <div class="d-grid">
                                                        <button class="btn btn-primary" type="submit"> Log In
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-guest-layout>

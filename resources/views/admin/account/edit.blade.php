<x-app-layout>
    <x-breadcrumbs :render="Breadcrumbs::render('account.update')" />


    <form action="{{ route('account.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="card" style="font-weight:bold">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">

                        <div class="mb-3">
                            <x-input-label name="avatar" text="Avatar" />
                            <div class="form-control d-flex justify-content-center align-items-center my-1"
                                style="height: 100px">
                                <img src="{{ $user->profile_image }}" alt="Avatar" height="60">
                            </div>
                            <x-input-field name="avatar" type="file" accept="image/*" />
                        </div>

                        <div class="mb-3">
                            <x-input-label name="name" text="Name" />
                            <x-input-field name="name" type="text" value="{{ old('name', $user->name) }}"
                                placeholder="Enter name" />
                        </div>

                        <div class="mb-3">
                            <x-input-label name="email" text="Email" />
                            <x-input-field name="email" type="text" value="{{ old('email', $user->email) }}"
                                placeholder="example@email.com" />
                        </div>

                        <hr>

                        <button class="btn btn-primary px-5">{{ 'Update' }}</button>

                    </div>
                    <div class="col-md-4"></div>

                </div>
            </div>
        </div>
    </form>

</x-app-layout>

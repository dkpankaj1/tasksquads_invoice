<x-app-layout>

    @if (isset($customer))
        <x-breadcrumbs :render="Breadcrumbs::render('customer.edit', $customer)" />
    @else
        <x-breadcrumbs :render="Breadcrumbs::render('customer.create')" />
    @endif

    <x-card title="{{ isset($customer) ? 'Update Customer' : 'Create Customer' }}">
        <div class="row">
            <div class="col-md-8">
                <form action="{{ isset($customer) ? route('customer.update', $customer) : route('customer.store') }}"
                    method="post">
                    @csrf
                    @isset($customer)
                        @method('PUT')
                    @endisset

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <x-input-label name="first_name" text="First Name" />
                            <x-input-field name="first_name"
                                value="{{ old('first_name', isset($customer) ? $customer->first_name : '') }}"
                                placeholder="Enter first name..." />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="last_name" text="Last Name" />
                            <x-input-field name="last_name"
                                value="{{ old('last_name', isset($customer) ? $customer->last_name : '') }}"
                                placeholder="Enter last name..." />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="email" text="Email Address" />
                            <x-input-field name="email"
                                value="{{ old('email', isset($customer) ? $customer->email : '') }}"
                                placeholder="example@email.com" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="phone" text="Phone Number" />
                            <x-input-field name="phone"
                                value="{{ old('phone', isset($customer) ? $customer->phone : '') }}"
                                placeholder="+91 9794xxx940" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="whatsapp_mobile" text="Whatsapp (Mobile)" />
                            <x-input-field name="whatsapp_mobile"
                                value="{{ old('whatsapp_mobile', isset($customer) ? $customer->whatsapp : '') }}"
                                placeholder="+91 9919xxxx55" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="address" text="Address" />
                            <x-input-field name="address"
                                value="{{ old('address', isset($customer) ? $customer->address : '') }}"
                                placeholder="Enter address.." />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="city" text="City Name" />
                            <x-input-field name="city"
                                value="{{ old('city', isset($customer) ? $customer->city : 'Kaptanganj') }}"
                                placeholder="Enter city name.." />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="state" text="State" />
                            <x-input-field name="state"
                                value="{{ old('state', isset($customer) ? $customer->state : 'Uttar Pradesh') }}"
                                placeholder="Enter state .." />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="country" text="Country" />
                            <x-input-field name="country"
                                value="{{ old('country', isset($customer) ? $customer->country : 'India') }}"
                                placeholder="+91 9919xxxx55" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="pin_code" text="Pin Code" />
                            <x-input-field name="pin_code"
                                value="{{ old('pin_code', isset($customer) ? $customer->pin_code : '') }}"
                                placeholder="Enter pinCode.." />
                        </div>

                        <div class="col-12 mb-3">
                            <x-input-label name="status" text="Status" />
                            <x-input-select name="status">
                                <option value="1"
                                    {{ old('status', isset($customer) ? $customer->active : '') == '1' ? 'selected' : '' }}>
                                    Active
                                </option>
                                <option value="0"
                                    {{ old('status', isset($customer) ? $customer->active : '') == '0' ? 'selected' : '' }}>
                                    In-Active
                                </option>
                            </x-input-select>
                        </div>

                    </div>



                    <hr>
                    <div class="d-flex gap-1">
                        <x-save-btn />
                        <x-reset-btn />
                    </div>

                </form>
            </div>
        </div>
    </x-card>
</x-app-layout>

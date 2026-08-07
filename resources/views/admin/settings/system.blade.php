<x-app-layout>
    
    <x-breadcrumbs :render="Breadcrumbs::render('systemSettings.edit')" />

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">System Settings</h4>
        </div>
        <div class="card-body">

            <form action="{{ route('systemSettings.update') }}" method="post">
                @csrf
                @method('PUT')



                <!-- Contact Information -->
                <div class="row mt-4">

                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="finance_year" text="Finance Year" />
                            <x-input-select name="finance_year">
                                @foreach ($financeYears as $year)
                                    <option value="{{ $year->id }}"
                                        {{ old('finance_year', $setting->finance_year_id ?? '') == $year->id ? 'selected' : '' }}>
                                        {{ $year->name }}
                                    </option>
                                @endforeach
                            </x-input-select>
                        </div>
                    </div>

                    <div class="w-100"></div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="currency" text="Currency" />
                            <x-input-select name="currency">
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}"
                                        {{ old('currency', $setting->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->name }} ({{ $currency->symbol }})
                                    </option>
                                @endforeach
                            </x-input-select>
                        </div>
                    </div>

                    <div class="w-100"></div>


                    <div class="col-md-4">
                        <div class="mb-3">
                            <x-input-label name="date_format" text="Date Format" />
                            <select name="date_format" class="form-control form-select">
                                <option value="">---select---</option>
                                <option value="d/m/Y"
                                    {{ old('date_format', $setting->date_format) == 'd/m/Y' ? 'selected' : '' }}>
                                    DD/MM/YYYY
                                </option>
                                <option value="m/d/Y"
                                    {{ old('date_format', $setting->date_format) == 'm/d/Y' ? 'selected' : '' }}>
                                    MM/DD/YYYY
                                </option>
                                <option value="Y-m-d"
                                    {{ old('date_format', $setting->date_format) == 'Y-m-d' ? 'selected' : '' }}>
                                    YYYY-MM-DD
                                </option>
                                <option value="d-m-Y"
                                    {{ old('date_format', $setting->date_format) == 'd-m-Y' ? 'selected' : '' }}>
                                    DD-MM-YYYY
                                </option>
                            </select>
                        </div>
                    </div>



                </div>


                <div class="mt-4">
                    <hr>
                    <x-save-btn />
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

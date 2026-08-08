<x-app-layout>

    @if (isset($currency))
        <x-breadcrumbs :render="Breadcrumbs::render('currency.edit', $currency)" />
    @else
        <x-breadcrumbs :render="Breadcrumbs::render('currency.create')" />
    @endif

    <x-card title="{{ isset($currency) ? 'Update Currency' : 'Create Currency' }}">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ isset($currency) ? route('currency.update', $currency) : route('currency.store') }}"
                    method="post">
                    @csrf
                    @isset($currency)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <x-input-label name="name" text="Name" />
                        <x-input-field name="name" value="{{ old('name', isset($currency) ? $currency->name : '') }}"
                            placeholder="Enter currency name..." />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="code" text="Code" />
                        <x-input-field name="code" value="{{ old('code', isset($currency) ? $currency->code : '') }}"
                            placeholder="e.g. USD" />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="symbol" text="Symbol" />
                        <x-input-field name="symbol"
                            value="{{ old('symbol', isset($currency) ? $currency->symbol : '') }}"
                            placeholder="e.g. $" />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="exchange_rate" text="Exchange Rate" />
                        <x-input-field name="exchange_rate" type="number" step="0.000001"
                            value="{{ old('exchange_rate', isset($currency) ? $currency->exchange_rate : '1.000000') }}"
                            placeholder="e.g. 1.000000" />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="major_unit" text="Major Unit (e.g. dollar, rupee)" />
                        <x-input-field name="major_unit"
                            value="{{ old('major_unit', isset($currency) ? $currency->major_unit : '') }}"
                            placeholder="e.g. dollar" />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="minor_unit" text="Minor Unit (e.g. cent, paisa)" />
                        <x-input-field name="minor_unit"
                            value="{{ old('minor_unit', isset($currency) ? $currency->minor_unit : '') }}"
                            placeholder="e.g. cent" />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="status" text="Status" />
                        <x-input-select name="status">
                            <option value="1"
                                {{ old('status', isset($currency) ? $currency->active : '') == '1' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0"
                                {{ old('status', isset($currency) ? $currency->active : '') == '0' ? 'selected' : '' }}>
                                In-Active
                            </option>
                        </x-input-select>
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

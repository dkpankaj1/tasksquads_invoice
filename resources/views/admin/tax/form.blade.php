<x-app-layout>

    @if (isset($tax))
        <x-breadcrumbs :render="Breadcrumbs::render('tax.edit', $tax)" />
    @else
        <x-breadcrumbs :render="Breadcrumbs::render('tax.create')" />
    @endif

    <x-card title="{{ isset($tax) ? 'Update Tax Type' : 'Create Tax Type' }}">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ isset($tax) ? route('tax.update', $tax) : route('tax.store') }}" method="post">
                    @csrf
                    @isset($tax)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <x-input-label name="name" text="Name" />
                        <x-input-field name="name" value="{{ old('name', isset($tax) ? $tax->name : '') }}"
                            placeholder="Enter Tax name..." />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="rate" text="Tax Rate" />
                        <x-input-field name="rate" value="{{ old('rate', isset($tax) ? $tax->rate : '') }}"
                            type="number" min="0" max="100" step="0.1"
                            placeholder="Enter Tax rate..." />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="treatment" text="Treatment" />
                        <x-input-field name="treatment"
                            value="{{ old('treatment', isset($treatment) ? $treatment->rate : '') }}"
                            placeholder="Enter Treatment" />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="status" text="Status" />
                        <x-input-select name="status">
                            <option value="1"
                                {{ old('status', isset($tax) ? $tax->active : '') == '1' ? 'selected' : '' }}>Active
                            </option>
                            <option value="0"
                                {{ old('status', isset($tax) ? $tax->active : '') == '0' ? 'selected' : '' }}>In-Active
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

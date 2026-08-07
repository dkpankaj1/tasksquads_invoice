<x-app-layout>
    
    @if (isset($unit))
        <x-breadcrumbs :render="Breadcrumbs::render('unit.edit', $unit)" />
    @else
        <x-breadcrumbs :render="Breadcrumbs::render('unit.create')" />
    @endif

    <x-card title="{{ isset($unit) ? 'Update Unit' : 'Create Unit' }}">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ isset($unit) ? route('unit.update', $unit) : route('unit.store') }}" method="post">
                    @csrf
                    @isset($unit)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <x-input-label name="name" text="Name" />
                        <x-input-field name="name" value="{{ old('name', isset($unit) ? $unit->name : '') }}"
                            placeholder="Enter unit name..." />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="short_name" text="Short Name" />
                        <x-input-field name="short_name"
                            value="{{ old('short_name', isset($unit) ? $unit->short_name : '') }}"
                            placeholder="Enter unit short name..." />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="status" text="Status" />
                        <x-input-select name="status">
                            <option value="1"
                                {{ old('status', isset($unit) ? $unit->active : '') == '1' ? 'selected' : '' }}>Active
                            </option>
                            <option value="0"
                                {{ old('status', isset($unit) ? $unit->active : '') == '0' ? 'selected' : '' }}>
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

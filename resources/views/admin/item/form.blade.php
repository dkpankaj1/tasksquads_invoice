<x-app-layout>
   
    @if (isset($item))
        <x-breadcrumbs :render="Breadcrumbs::render('item.edit', $item)" />
    @else
        <x-breadcrumbs :render="Breadcrumbs::render('item.create')" />
    @endif

    <x-card title="{{ isset($item) ? 'Update Item' : 'Create Item' }}">
        <div class="row ">
            <div class="col-md-8">
                <form action="{{ isset($item) ? route('item.update', $item) : route('item.store') }}" method="post">
                    @csrf
                    @isset($item)
                        @method('PUT')
                    @endisset

                    <div class="row">

                        <div class="mb-3 col-md-4">
                            <x-input-label name="hsn_code" text="HSN Code" />
                            <x-input-field name="hsn_code" value="{{ old('hsn_code', isset($item) ? $item->hsn_code : '') }}"
                                placeholder="Enter HSN code..." />
                        </div>

                        <div class="mb-3 col-md-8">
                            <x-input-label name="name" text="Item Name" />
                            <x-input-field name="name" value="{{ old('name', isset($item) ? $item->name : '') }}"
                                placeholder="Enter item name..." />
                        </div>

                        <div class="mb-3 col-md-6">
                            <x-input-label name="category" text="Item Category" />
                            <x-input-select name="category">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category', isset($item) ? $item->category_id : '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} [{{ $category->id }}]
                                    </option>
                                @endforeach
                            </x-input-select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <x-input-label name="unit" text="Item Unit" />
                            <x-input-select name="unit">
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit', isset($item) ? $item->unit_id : '') == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }} [{{ $unit->id }}]
                                    </option>
                                @endforeach
                            </x-input-select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <x-input-label name="rate" text="Rate (per unit)" />
                            <x-input-field name="rate" value="{{ old('rate', isset($item) ? $item->rate : '') }}"
                                placeholder="Enter rate" />
                        </div>

                        <div class="mb-3 col-md-6">
                            <x-input-label name="additional_cost" text="Additional Cost (per unit)" />
                            <x-input-field name="additional_cost" value="{{ old('price', isset($item) ? $item->additional_cost : '') }}"
                                placeholder="Enter additional cost" />
                        </div>

                        <div class="mb-3 col-12">
                            <x-input-label name="status" text="Status" />
                            <x-input-select name="status">
                                <option value="1"
                                    {{ old('status', isset($item) ? $item->status : '') == '1' ? 'selected' : '' }}>
                                    Active</option>
                                <option value="0"
                                    {{ old('status', isset($item) ? $item->status : '') == '0' ? 'selected' : '' }}>
                                    In-Active</option>
                            </x-input-select>
                        </div>

                        <div class="mb-3 col-12">
                            <x-input-label name="description" text="Description" />
                            <x-input-textarea name="description"
                                value="{{ old('description', isset($item) ? $item->description : '') }}"
                                placeholder="Enter description..." />
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

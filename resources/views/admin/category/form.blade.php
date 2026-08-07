<x-app-layout>

    @if (isset($category))
        <x-breadcrumbs :render="Breadcrumbs::render('category.edit', $category)" />
    @else
        <x-breadcrumbs :render="Breadcrumbs::render('category.create')" />
    @endif


    <x-card title="{{ isset($category) ? 'Update category' : 'Create category' }}">
        <div class="row">
            <div class="col-md-6">
                <form action="{{ isset($category) ? route('category.update', $category) : route('category.store') }}"
                    method="post">
                    @csrf
                    @isset($category)
                        @method('PUT')
                    @endisset

                    <div class="mb-3">
                        <x-input-label name="name" text="Name" />
                        <x-input-field name="name" value="{{ old('name', isset($category) ? $category->name : '') }}"
                            placeholder="Enter category name..." />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="short_name" text="Short Name" />
                        <x-input-field name="short_name"
                            value="{{ old('short_name', isset($category) ? $category->short_name : '') }}"
                            placeholder="Enter category short name..." />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="description" text="Description" />
                        <x-input-textarea name="description"
                            value="{{ old('description', isset($category) ? $category->description : '') }}"
                            placeholder="Enter description..." />
                    </div>

                    <div class="mb-3">
                        <x-input-label name="status" text="Status" />
                        <x-input-select name="status">
                            <option value="1"
                                {{ old('status', isset($category) ? $category->active : '') == '1' ? 'selected' : '' }}>
                                Active
                            </option>
                            <option value="0"
                                {{ old('status', isset($category) ? $category->active : '') == '0' ? 'selected' : '' }}>
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

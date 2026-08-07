<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('category.index')" />

    @section('buttons')
        <x-add-btn url="{{ route('category.create') }}" />
    @endsection

    <x-card title="Category">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>

    <x-confirm-delete />

</x-app-layout>

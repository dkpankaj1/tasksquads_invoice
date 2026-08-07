<x-app-layout>

    @section('buttons')
        <x-add-btn url="{{ route('item.create') }}" />
    @endsection

    <x-breadcrumbs :render="Breadcrumbs::render('item.index')" />

    <x-card title="Items">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>

    <x-confirm-delete />

</x-app-layout>

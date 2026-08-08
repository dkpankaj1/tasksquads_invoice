<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('currency.index')" />

    @section('buttons')
        <x-add-btn url="{{ route('currency.create') }}" />
    @endsection

    <x-card title="Currencies">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>
    <x-confirm-delete />
</x-app-layout>

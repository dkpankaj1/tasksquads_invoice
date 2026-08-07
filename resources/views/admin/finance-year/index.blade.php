<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('finance-year.index')" />
     
    <x-card title="Finance Years">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>

    <x-confirm-delete />

</x-app-layout>

<x-app-layout>

    @section('buttons')
        <x-add-btn url="{{ route('invoice.create') }}" />
    @endsection

    <x-breadcrumbs :render="Breadcrumbs::render('invoice.index')" />

    <x-card title="Invoices">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>

    <x-confirm-delete />
    
</x-app-layout>

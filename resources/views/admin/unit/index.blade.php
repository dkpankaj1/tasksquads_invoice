<x-app-layout>
    
    <x-breadcrumbs :render="Breadcrumbs::render('unit.index')" />
    
    @section('buttons')
    <x-add-btn url="{{ route('unit.create') }}" />
    @endsection

    <x-card title="Item Unit">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>
    <x-confirm-delete />
</x-app-layout>

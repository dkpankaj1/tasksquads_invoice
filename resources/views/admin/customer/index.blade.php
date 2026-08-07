<x-app-layout>

    @section('buttons')
          <x-add-btn url="{{ route('customer.create') }}" />
    @endsection

    <x-breadcrumbs :render="Breadcrumbs::render('customer.index')" />

    <x-card title="Customers">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>

    <x-confirm-delete />

</x-app-layout>

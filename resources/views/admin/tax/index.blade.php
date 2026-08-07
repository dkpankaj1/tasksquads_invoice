<x-app-layout>

    @section('buttons')
      <x-add-btn url="{{ route('tax.create') }}" />
    @endsection

    <x-breadcrumbs :render="Breadcrumbs::render('tax.index')" />
    <x-card title="Tax Type">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>
    <x-confirm-delete />
</x-app-layout>

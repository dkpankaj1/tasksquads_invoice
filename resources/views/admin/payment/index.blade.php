<x-app-layout>

    @section('buttons')
        <x-add-btn url="{{ route('payment.create') }}" />
    @endsection

    <x-breadcrumbs :render="Breadcrumbs::render('payment.index')" />

    <x-card title="Payments" subTitle="Manage invoice payments">
        <x-datatable ajaxUrl="{{ $ajaxUrl }}" :columns="$columns" />
    </x-card>
    <x-confirm-delete />

</x-app-layout>

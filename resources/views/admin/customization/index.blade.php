<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('customization.index')" />

    <x-card title="Customization">
        <div class="row">
            <div class="col-md-2">
                <div class="nav flex-column nav-pills nav-pills-tab" id="v-pills-tab" role="tablist"
                    aria-orientation="vertical">

                    @foreach ($customizations as $key => $customization)
                        <a class="nav-link mb-1 {{ $key == 0 ? 'active' : '' }}"
                            id="v-pills-{{ $customization->type }}-tab" data-bs-toggle="pill"
                            href="#v-pills-{{ $customization->type }}" role="tab"
                            aria-controls="v-pills-{{ $customization->type }}" aria-selected="true">
                            {{ text_capitalize($customization->type) }}</a>
                    @endforeach

                </div>
            </div>
            <div class="col-md-10">
                <div class="tab-content pt-0">

                    @foreach ($customizations as $key => $customization)
                        <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}"
                            id="v-pills-{{ $customization->type }}" role="tabpanel"
                            aria-labelledby="v-pills-{{ $customization->type }}-tab">

                            <div class="d-flex justify-content-end w-100">
                                <a href="{{ route('customization.edit', $customization) }}"
                                    class="btn btn-dark px-4 waves-effect waves-light">
                                    <i class="mdi mdi-pen me-1"></i> Edit
                                </a>
                            </div>

                            <hr>

                            <table class="table table-sm">
                                <tbody class="border-bottom">
                                    <tr>
                                        <th>Component</th>
                                        <th>Parameter</th>
                                    </tr>
                                </tbody>
                                <tr>
                                    <td>
                                        <h5>Series</h5>
                                        <p>To set a static prefix/postfix like 'INV' across your company. It
                                            supports character length
                                            of up to 6 chars.</p>
                                    </td>
                                    <td><x-input-field name="series" value="{{ $customization->series }}" /></td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5>Delimiter</h5>
                                        <p>Single character for specifying the boundary between 2 separate
                                            components. By default its
                                            set to -</p>
                                    </td>
                                    <td><x-input-field name="delimiter" value="{{ $customization->delimiter }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h5>Sequence</h5>
                                        <p>To set a static prefix/postfix like 'INV' across your company. It
                                            supports character length
                                            of up to 6 chars.</p>
                                    </td>
                                    <td><x-input-field name="sequence" value="{{ $customization->sequence }}" />
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <h5>Preview {{ $customization->type }} number : [
                                            {{ $customization->series }}{{ $customization->delimiter }}{{ pad_number(1, $customization->sequence) }}
                                            ]</h5>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <h5>Notes</h5>
                                        <p>The Notes field allows you to define custom text to be printed at the bottom
                                            of {{ $customization->type }} documents.</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="2">
                                        <x-input-textarea name="note" value="{{ $customization->note }}" />
                                    </td>
                                </tr>
                            </table>

                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </x-card>


</x-app-layout>

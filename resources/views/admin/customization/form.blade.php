<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('customization.edit', $customization)" />
    
    <x-card title="{{ text_capitalize($customization->type) }} Customize">
        <form action="{{ route('customization.update', $customization) }}" method="post">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
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
                            <td><x-input-field name="series" value="{{ old('series', $customization->series) }}" /></td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Delimiter</h5>
                                <p>Single character for specifying the boundary between 2 separate
                                    components. By default its
                                    set to -</p>
                            </td>
                            <td><x-input-field name="delimiter"
                                    value="{{ old('delimiter', $customization->delimiter) }}" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h5>Sequence</h5>
                                <p>To set a static prefix/postfix like 'INV' across your company. It
                                    supports character length
                                    of up to 6 chars.</p>
                            </td>
                            <td><x-input-field name="sequence" value="{{ old('sequence', $customization->sequence) }}" />
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <h5>Notes</h5>
                                <p>The Notes field allows you to define custom text to be printed at the bottom
                                    of invoices, estimates, or payment documents.</p>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2">
                                <x-input-textarea name="note" value="{{ old('note', $customization->note) }}" />
                            </td>
                        </tr>
                    </table>

                    <hr>
                    <div class="d-flex gap-1">
                        <x-save-btn />
                    </div>
                </div>
            </div>

        </form>
    </x-card>
</x-app-layout>

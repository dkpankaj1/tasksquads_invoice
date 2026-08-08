<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('customization.index')" />

    <x-card title="Document Numbering Customization">
        <div class="row">
            {{-- Sidebar tabs --}}
            <div class="col-md-3">
                <div class="nav flex-column nav-pills nav-pills-tab" id="v-pills-tab" role="tablist"
                    aria-orientation="vertical">

                    @foreach ($customizations as $key => $customization)
                        <a class="nav-link mb-1 {{ $key == 0 ? 'active' : '' }}"
                            id="v-pills-{{ $customization->type }}-tab" data-bs-toggle="pill"
                            href="#v-pills-{{ $customization->type }}" role="tab"
                            aria-controls="v-pills-{{ $customization->type }}" aria-selected="true">
                            <i class="mdi mdi-file-document-outline me-1"></i>
                            {{ text_capitalize($customization->type) }}
                        </a>
                    @endforeach

                </div>
            </div>

            {{-- Content --}}
            <div class="col-md-9">
                <div class="tab-content pt-0">

                    @foreach ($customizations as $key => $customization)
                        <div class="tab-pane fade {{ $key == 0 ? 'active show' : '' }}"
                            id="v-pills-{{ $customization->type }}" role="tabpanel"
                            aria-labelledby="v-pills-{{ $customization->type }}-tab">

                            {{-- Header with edit button --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">{{ text_capitalize($customization->type) }} Numbering Format</h5>
                                <a href="{{ route('customization.edit', $customization) }}"
                                    class="btn btn-dark px-3 waves-effect waves-light">
                                    <i class="mdi mdi-pen me-1"></i> Edit
                                </a>
                            </div>

                            <hr>

                            {{-- Numbering Components --}}
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <div class="card border">
                                        <div class="card-body text-center py-3">
                                            <small class="text-muted text-uppercase">Series</small>
                                            <h4 class="mb-0 mt-1">
                                                <code class="fs-5">{{ $customization->series }}</code>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border">
                                        <div class="card-body text-center py-3">
                                            <small class="text-muted text-uppercase">Delimiter</small>
                                            <h4 class="mb-0 mt-1">
                                                <code class="fs-5">{{ $customization->delimiter }}</code>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border">
                                        <div class="card-body text-center py-3">
                                            <small class="text-muted text-uppercase">Sequence Digits</small>
                                            <h4 class="mb-0 mt-1">
                                                <code class="fs-5">{{ $customization->sequence }}</code>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Preview --}}
                            <div class="card border bg-light mb-4">
                                <div class="card-body text-center py-3">
                                    <small class="text-muted text-uppercase d-block mb-1">Number Preview</small>
                                    <h3 class="mb-0">
                                        <code
                                            class="fs-3 text-dark">{{ $customization->series }}{{ $customization->delimiter }}{{ pad_number(1, $customization->sequence) }}</code>
                                    </h3>
                                </div>
                            </div>

                            {{-- Notes --}}
                            <div class="card border mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="mdi mdi-note-text-outline me-1"></i> Notes</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0 text-muted">
                                        {{ $customization->note ?: 'No notes configured.' }}
                                    </p>
                                </div>
                            </div>

                            {{-- Legal Note --}}
                            <div class="card border">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="mdi mdi-shield-check-outline me-1"></i> Legal Note</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0 text-muted">
                                        {{ $customization->legal_note ?: 'No legal note configured.' }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </x-card>

</x-app-layout>

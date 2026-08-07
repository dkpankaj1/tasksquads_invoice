<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('invoice.create')" />

    <form action="{{ route('invoice.store') }}" method="post">
        @csrf

        <div class="card">

            <div class="card-header">
                <h4 class="card-title">Create Invoice</h4>
                <p class="card-sub-title">Fill out the detail to create new invoice</p>
            </div>

            <div class="card-body">

                <div class="row  mb-4">
                    <div class="col-md-6 mb-3">
                        <x-input-label name="customer" text="Customer" />
                        @include('admin.invoice._include.customer-select', [
                            'options' => $customers,
                            'name' => 'customer',
                            'id' => 'invoice_customer',
                            'value' => old('customer'),
                        ])
                    </div>

                    <div class="w-100"></div>
                    <div class="col-md-3 mb-3">
                        <x-input-label name="invoice_date" text="Invoice Date" />
                        <x-input-field name="invoice_date" type="date"
                            value="{{ old('invoice_date', $todayData) }}" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <x-input-label name="due_date" text="Due Date" />
                        <x-input-field name="due_date" type="date" value="{{ old('due_date', $dueData) }}" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <x-input-label name="invoice_number" text="Invoice Number" />
                        <x-input-field name="invoice_number" value="{{ $invoiceNumber }}" />
                    </div>
                </div>

                <hr>

                {{-- Search item ::Begin --}}
                <div class="search-container" style="z-index: 10">
                    <div class="input-group mb-1">
                        <div class="input-group-text px-4 search-input-prefix">
                            <i data-lucide="scan-barcode"></i>
                        </div>
                        <input type="text" class="form-control py-3 search-input" placeholder="Scan/Search Product"
                            value="" id="searchInput">
                        <button type="button" class="btn btn-secondary" data-bs-toggle="modal"
                            data-bs-target="#createItemsModal">
                            <i class="mdi mdi-plus-circle me-1"></i>
                        </button>
                    </div>
                    <div class="search-result shadow-lg rounded" id="searchResult">

                        <ul class="searchResultContainer mb-0" id="search_item_list"></ul>

                    </div>
                </div>
                {{-- searchItem::end --}}
                <p class="my-1 text-bold"><i class="mdi mdi-check"></i> Search item by name, barcode or HSN code.</p>


            </div>

        </div>


        <div class="card">

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-sm no-wrap">

                        <thead class="bg-secondary">
                            <tr>
                                <th style="min-width: 200px; width: 35%" class="text-light">Item</th>
                                <th style="min-width: 100px; width: 10%;" class="text-light">HSN Code</th>
                                <th style="min-width: 80px; width: 10%;" class="text-light">Unit</th>
                                <th style="min-width: 100px; width: 10%;" class="text-light">Quantity</th>
                                <th style="min-width: 100px; width: 10%" class="text-light">Price</th>
                                <th style="min-width: 120px; width: 10%;" class="text-light">Additional
                                    Cost({{ $system_setting->currency->symbol }})</th>
                                <th style="min-width: 120px; width: 10%;" class="text-light">
                                    Amount({{ $system_setting->currency->symbol }})</th>
                                <th style="min-width: 60px; width: 5%;" class="text-light">Action</th>
                            </tr>
                        </thead>

                        <tbody id="cartItem">

                            @if (old('items'))
                                @foreach (old('items')['product_id'] as $key => $value)
                                    <tr>
                                        {{-- item id/name --}}
                                        <td>
                                            <input type="hidden" name="items[product_id][]"
                                                value="{{ old('items')['product_id'][$key] }}">
                                            <input type="hidden" name="items[name][]"
                                                value="{{ old('items')['name'][$key] }}">
                                            <span
                                                class="form-control form-control-sm bg-mute mb-1">{{ text_uppercase(old('items')['name'][$key]) }}</span>
                                            <textarea name="items[description][]" class="form-control form-control-sm" rows="2"
                                                placeholder="Item description..">{{ old('items')['description'][$key] }}</textarea>
                                        </td>

                                        {{-- item Hsn Code --}}
                                        <td>
                                            <input type="hidden" name="items[hsn_code][]"
                                                value="{{ old('items')['hsn_code'][$key] }}">
                                            <span
                                                class="form-control form-control-sm bg-mute ">{{ text_uppercase(old('items')['hsn_code'][$key]) }}</span>
                                        </td>

                                        {{-- item unit --}}
                                        <td>
                                            <input type="hidden" name="items[unit_id][]"
                                                value="{{ old('items')['unit_id'][$key] }}">
                                            <input type="hidden" name="items[unit][]"
                                                value="{{ old('items')['unit'][$key] }}">
                                            <span
                                                class="form-control form-control-sm bg-mute ">{{ old('items')['unit'][$key] }}
                                                [{{ old('items')['unit_id'][$key] }}]</span>
                                        </td>


                                        {{-- item quantity --}}
                                        <td>
                                            <input name="items[quantity][]" type="number" step="0.01"
                                                class="form-control form-control-sm"
                                                value="{{ old('items')['quantity'][$key] }}" />
                                        </td>

                                        {{-- item rate --}}
                                        <td>
                                            <input name="items[rate][]" type="number" step="0.01"
                                                class="form-control form-control-sm"
                                                value="{{ old('items')['rate'][$key] }}" />
                                        </td>

                                        {{-- item additional cost --}}
                                        <td>
                                            <input name="items[additional_cost][]" type="number" step="0.01"
                                                class="form-control form-control-sm"
                                                value="{{ old('items')['additional_cost'][$key] }}" />
                                        </td>

                                        {{-- item total amount --}}
                                        <td>
                                            <input name="items[amount][]" type="number" step="0.01"
                                                class="form-control form-control-sm"
                                                value="{{ old('items')['amount'][$key] }}" />
                                        </td>

                                        <td>
                                            <button class="btn btn-danger btn-sm" style=" font-size: 1rem;"
                                                onclick="removeItem(this)">
                                                <i class="mdi mdi-trash-can me-1"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>

                    </table>
                </div>

            </div>

        </div>

        <div class="row mb-3">
            <div class="col-lg-8 col-md-12 mb-3 mb-lg-0">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <x-input-label name="note" text="Invoice Note" />
                            <textarea name="note" class="form-control" rows="5" placeholder="Write invoice notes...">{{ old('note') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped border table-sm mb-0">

                                @foreach ($taxes as $tax)
                                    <tr>
                                        <td class="text-end" style="width:100%">{{ $tax->name }} : </td>
                                        <td style="min-width: 150px;">(<span
                                                class="taxes">{{ $tax->rate }}</span> % ) </td>
                                    </tr>
                                @endforeach


                                <tr>
                                    <td class="text-end">Sub Total : </td>
                                    <td><b><span id="sub_total_amt">0</span></b></td>
                                </tr>

                                <tr>
                                    <td class="text-end">Add. Cost : </td>
                                    <td><input style="min-width:7rem; max-width: 100%;" type="number" step="0.01"
                                            class="form-control form-control-sm" name="add_cost"
                                            value="{{ old('add_cost', 0) }}"></td>
                                </tr>
                                <tr>
                                    <td class="text-end">Discount :
                                    </td>
                                    <td><input style="min-width:7rem; max-width: 100%;" type="number" step="0.01"
                                            class="form-control form-control-sm" name="discount"
                                            value="{{ old('discount', 0) }}"></td>
                                </tr>
                                <tr>
                                    <td class="text-end" style="vertical-align: top; padding-top: 12px;">
                                        Discount Type :
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column g-1">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="discount_type"
                                                    value="fixed" id="flexRadioDefault1"
                                                    {{ old('discount_type', 'fixed') == 'fixed' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="flexRadioDefault1">
                                                    Fixed ({{ $system_setting->currency->symbol }})
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="discount_type"
                                                    value="percentage" id="flexRadioDefault2"
                                                    {{ old('discount_type', 'fixed') == 'percentage' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="flexRadioDefault2">
                                                    Percentage (%)
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-dark">
                                    <td class="text-end">
                                        <h4 class="text-light mb-0">Total({{ $system_setting->currency->symbol }}) :
                                        </h4>
                                    </td>
                                    <td>
                                        <h4 id="total_amt" class="text-light mb-0">0</h4>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <x-save-btn />
            </div>
        </div>

    </form>

    @include('admin.invoice._include.item-model')
    @include('admin.invoice._include.customer-model')

    @push('pageScript')
        @include('admin.script.ajax-form')
        @include('admin.script.invoice')
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
        <script>
            // Initialize Sortable.js for cart items
            document.addEventListener('DOMContentLoaded', function() {
                const cartItemElement = document.getElementById('cartItem');

                if (cartItemElement) {
                    new Sortable(cartItemElement, {
                        animation: 150,
                        handle: 'tr', // The whole row is draggable
                        ghostClass: 'sortable-ghost',
                        chosenClass: 'sortable-chosen',
                        dragClass: 'sortable-drag',
                        onEnd: function(evt) {
                            // Optional: Recalculate totals after reordering
                            if (typeof calculator !== 'undefined') {
                                calculator.calculateTotals();
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
    @push('pageCss')
        <style>
            .sortable-ghost {
                opacity: 0.4;
                background-color: #f0f0f0;
            }

            .sortable-chosen {
                background-color: #e3f2fd;
            }

            .sortable-drag {
                opacity: 1;
            }

            #cartItem tr {
                cursor: move;
            }

            #cartItem tr:hover {
                background-color: #f5f5f5;
            }
        </style>
    @endpush


</x-app-layout>

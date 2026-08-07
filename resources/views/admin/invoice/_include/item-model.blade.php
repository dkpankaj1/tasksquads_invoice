<div id="createItemsModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Item</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">

                <form action="" id="createItemForm" method="POST">
                    <div class="row">

                        <div class="mb-3 col-md-4">
                            <x-input-label name="item_hsn_code" text="HSN Code" />
                            <x-input-field name="item_hsn_code" placeholder="Enter HSN code..." />
                        </div>

                        <div class="mb-3 col-md-8">
                            <x-input-label name="item_name" text="Item Name" />
                            <x-input-field name="item_name" placeholder="Enter item name..." />
                        </div>

                        <div class="mb-3 col-md-6">
                            <x-input-label name="item_category" text="Item Category" />
                            <x-input-select name="item_category">
                                @foreach (App\Models\Category::where('active',1)->get() as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }} [{{ $category->id }}]
                                    </option>
                                @endforeach
                            </x-input-select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <x-input-label name="item_unit" text="Item Unit" />
                            <x-input-select name="item_unit">
                                @foreach (App\Models\Unit::where('active',1)->get() as $unit)
                                    <option value="{{ $unit->id }}">
                                        {{ $unit->name }} [{{ $unit->id }}]
                                    </option>
                                @endforeach
                            </x-input-select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <x-input-label name="item_rate" text="Rate (per unit)" />
                            <x-input-field name="item_rate" placeholder="Enter rate" />
                        </div>

                        <div class="mb-3 col-md-6">
                            <x-input-label name="item_additional_cost" text="Additional Cost (per unit)" />
                            <x-input-field name="item_additional_cost" placeholder="Enter additional cost" />
                        </div>

                        <div class="mb-3 col-12">
                            <x-input-label name="item_description" text="Description" />
                            <x-input-textarea name="item_description" placeholder="Enter description..." />
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" id="createItemBtn">Save</button>
            </div>
        </div>
    </div>
</div>

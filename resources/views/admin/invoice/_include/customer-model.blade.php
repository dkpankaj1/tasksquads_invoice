<div id="createCustomerModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Customer</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="" id="createCustomerForm" method="POST">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <x-input-label name="customer_first_name" text="First Name" />
                            <x-input-field name="customer_first_name" placeholder="Enter first name..." />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="customer_last_name" text="Last Name" />
                            <x-input-field name="customer_last_name" placeholder="Enter last name..." />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-label name="customer_email" text="Email Address" />
                            <x-input-field name="customer_email" placeholder="example@email.com" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-label name="customer_phone" text="Phone Number" />
                            <x-input-field name="customer_phone" placeholder="+91 9794xxx940" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-label name="customer_whatsapp_mobile" text="Whatsapp (Mobile)" />
                            <x-input-field name="customer_whatsapp_mobile" placeholder="+91 9919xxxx55" />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="customer_address" text="Address" />
                            <x-input-field name="customer_address" placeholder="Enter address.." />
                        </div>

                        <div class="col-md-6 mb-3">
                            <x-input-label name="customer_city" text="City Name" />
                            <x-input-field name="customer_city" placeholder="Enter city name.." />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-label name="customer_state" text="State" />
                            <x-input-field name="customer_state" placeholder="Enter state .." />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-label name="customer_country" text="Country" />
                            <x-input-field name="customer_country" placeholder="Enter country" />
                        </div>

                        <div class="col-md-4 mb-3">
                            <x-input-label name="customer_pin_code" text="Pin Code" />
                            <x-input-field name="customer_pin_code" placeholder="Enter pinCode.." />
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light"
                    id="createCustomerBtn">Save</button>
            </div>
        </div>
    </div>
</div>
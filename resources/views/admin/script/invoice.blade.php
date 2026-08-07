<script>
    /**
     * Calculator class for handling invoice calculations
     */
    class InvoiceCalculator {
        /**
         * Calculate all totals for the invoice
         */
        calculateTotals() {
            const itemTotal = this.calculateItemTotal();
            const totalTax = this.calculateTotalTax(itemTotal);
            const subTotal = itemTotal + totalTax;
            const total = this.calculateFinalTotal(subTotal);

            this.updateUI(subTotal, total);
        }

        /**
         * Calculate total amount for all items
         * @returns {number} Total amount of all items
         */
        calculateItemTotal() {
            let itemTotal = 0;

            $('#cartItem tr').each(function() {
                const qty = parseFloat($(this).find('input[name="items[quantity][]"]').val()) || 0;
                const rate = parseFloat($(this).find('input[name="items[rate][]"]').val()) || 0;
                const additionalCost = parseFloat($(this).find('input[name="items[additional_cost][]"]')
                    .val()) || 0;

                const amount = (qty * rate) + (qty * additionalCost);
                $(this).find('input[name="items[amount][]"]').val(amount.toFixed(2));

                itemTotal += amount;
            });

            return itemTotal;
        }

        /**
         * Calculate total tax amount
         * @param {number} itemTotal - Total amount of items
         * @returns {number} Total tax amount
         */
        calculateTotalTax(itemTotal) {
            let totalTax = 0;

            $('.taxes').each(function() {
                const taxRate = parseFloat($(this).text()) || 0;
                const taxAmount = (itemTotal * taxRate) / 100;
                totalTax += taxAmount;

                // Update tax amount in table if placeholder exists
                const taxCell = $(this).closest('tr').find('.tax-amount');
                if (taxCell.length) {
                    taxCell.text(taxAmount.toFixed(2));
                }
            });

            return totalTax;
        }

        /**
         * Calculate final total with additional costs and discounts
         * @param {number} subTotal - Subtotal amount
         * @returns {number} Final total amount
         */
        calculateFinalTotal(subTotal) {
            const invoiceAddCost = parseFloat($('input[name="add_cost"]').val()) || 0;
            const discountValue = parseFloat($('input[name="discount"]').val()) || 0;
            const discountType = $('input[name="discount_type"]:checked').val() || 'fixed';

            // Calculate discount based on type
            let discountAmount = 0;
            if (discountType === 'percentage') {
                discountAmount = (subTotal * discountValue) / 100;
            } else {
                discountAmount = discountValue;
            }

            return (subTotal + invoiceAddCost) - discountAmount;
        }

        /**
         * Update the UI with calculated totals
         * @param {number} subTotal - Subtotal amount
         * @param {number} total - Final total amount
         */
        updateUI(subTotal, total) {
            $('#sub_total_amt').text(subTotal.toFixed(2));
            $('#total_amt').text(total.toFixed(2));
        }
    }

    /**
     * Search manager class for handling product search functionality
     */
    class InvoiceSearchManager {
        constructor(calculator) {
            this.calculator = calculator;
            this.init();
        }

        /**
         * Initialize search functionality
         */
        init() {
            this.bindSearchEvents();
        }

        /**
         * Bind search related events
         */
        bindSearchEvents() {
            const searchInput = $('#searchInput');
            const debouncedSearch = this.debounce(this.searchProduct.bind(this), 300);

            searchInput.on('keyup', function() {
                const searchValue = $(this).val();
                if (searchValue === '') {
                    $('#search_item_list').html('');
                    $('#searchResult').css('display', 'none');
                } else {
                    debouncedSearch(searchValue);
                }
            });
        }

        /**
         * Debounce function to limit API calls
         * @param {Function} func - Function to debounce
         * @param {number} delay - Delay in milliseconds
         * @returns {Function} Debounced function
         */
        debounce(func, delay) {
            let timeoutId;
            return (...args) => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => func.apply(this, args), delay);
            };
        }

        /**
         * Search for products via AJAX
         * @param {string} search - Search term
         */
        searchProduct(search) {
            $.ajax({
                url: "{{ route('ajax.searchItem') }}",
                data: {
                    search
                },
                success: (data) => {
                    $('#search_item_list').html(data);
                    $('#searchResult').css('display', data.trim() === '' ? 'none' : 'block');
                },
                error: (xhr, status, error) => {
                    console.error('Search error:', error);
                }
            });
        }
    }

    /**
     * Item manager class for handling cart item operations
     */
    class InvoiceItemManager {
        constructor(calculator) {
            this.calculator = calculator;
        }

        /**
         * Add item to cart
         * @param {number} id - Item ID
         */
        addItem(id) {
            $.ajax({
                url: "{{ route('ajax.getItem') }}",
                data: {
                    id
                },
                success: (data) => {
                    $('#cartItem').append(data);
                    $('#search_item_list').html('');
                    $('#searchResult').css('display', 'none');
                    $('#searchInput').val('');
                    this.calculator.calculateTotals();
                },
                error: (xhr, status, error) => {
                    console.error('Add item error:', error);
                }
            });
        }

        /**
         * Remove item from cart
         * @param {jQuery} btn - Remove button element
         */
        removeItem(btn) {
            btn.closest('tr').remove();
            this.calculator.calculateTotals();
        }

        /**
         * Handle add item response from AJAX
         * @param {string} response - HTML response containing item row
         */
        handleAddItemResponse(response) {
            $('#cartItem').append(response);
            this.calculator.calculateTotals();
        }
    }

    /**
     * Main invoice manager class that orchestrates all invoice operations
     */
    class InvoiceManager {
        constructor() {
            this.calculator = new InvoiceCalculator();
            this.searchManager = new InvoiceSearchManager(this.calculator);
            this.itemManager = new InvoiceItemManager(this.calculator);
            this.init();
        }

        /**
         * Initialize the invoice manager
         */
        init() {
            this.bindCalculationEvents();
            this.calculator.calculateTotals();
        }

        /**
         * Bind all calculation events
         */
        bindCalculationEvents() {
            $('#cartItem').on('input',
                'input[name="items[quantity][]"], input[name="items[rate][]"], input[name="items[additional_cost][]"]',
                () => this.calculator.calculateTotals()
            );

            $('input[name="add_cost"], input[name="discount"]').on('input', () => this.calculator.calculateTotals());
            $('input[name="discount_type"]').on('change', () => this.calculator.calculateTotals());
        }

        /**
         * Add item to cart (delegate to item manager)
         * @param {number} id - Item ID
         */
        addItem(id) {
            this.itemManager.addItem(id);
        }

        /**
         * Remove item from cart (delegate to item manager)
         * @param {jQuery} btn - Remove button element
         */
        removeItem(btn) {
            this.itemManager.removeItem(btn);
        }
    }

    // Initialize invoice manager when document is ready
    let invoiceManager;

    $(document).ready(() => {
        invoiceManager = new InvoiceManager();
    });

    // Global functions for backward compatibility
    const addItem = (id) => {
        if (invoiceManager) {
            invoiceManager.addItem(id);
        }
    };

    const removeItem = (btn) => {
        if (invoiceManager) {
            invoiceManager.removeItem($(btn));
        }
    };
</script>

<script>
    /**
     * Item creation handler class
     */
    class InvoiceItemCreationHandler {
        constructor() {
            this.init();
        }

        /**
         * Initialize the item creation handler
         */
        init() {
            this.bindEvents();
        }

        /**
         * Bind events for item creation
         */
        bindEvents() {
            document.addEventListener('DOMContentLoaded', () => {
                const insertItemForm = document.getElementById('createItemForm');
                const insertItemBtn = document.getElementById('createItemBtn');

                if (insertItemForm && insertItemBtn) {
                    new AjaxFormHandler({
                        form: insertItemForm,
                        button: insertItemBtn,
                        url: "{{ route('ajax.createItem') }}",
                        method: 'POST',
                        modalToHide: '#createItemsModal',
                        onSuccess: (response) => {
                            this.handleSuccessfulItemCreation(response);
                        },
                        onError: (xhr, errorMessage) => {
                            this.handleItemCreationError(xhr, errorMessage);
                        }
                    });
                }
            });
        }

        /**
         * Handle successful item creation
         * @param {Object} response - Server response
         */
        handleSuccessfulItemCreation(response) {
            // Handle successful item insertion
            if (typeof invoiceManager !== 'undefined' && invoiceManager.itemManager) {
                invoiceManager.itemManager.handleAddItemResponse(response);
            } else {
                $('#cartItem').append(response);
                if (typeof invoiceManager !== 'undefined' && invoiceManager.calculator) {
                    invoiceManager.calculator.calculateTotals();
                }
            }

            $('#search_item_list').html('');
            $('#searchResult').css('display', 'none');
            $('#searchInput').val('');
        }

        /**
         * Handle item creation error
         * @param {Object} xhr - XMLHttpRequest object
         * @param {string} errorMessage - Error message
         */
        handleItemCreationError(xhr, errorMessage) {
            console.error('Failed to insert item:', xhr.responseText);
            alert('Failed to create item: ' + errorMessage);
        }
    }

    // Initialize item creation handler
    new InvoiceItemCreationHandler();
</script>

<script>
    /**
     * Customer creation handler class
     */
    class InvoiceCustomerCreationHandler {
        constructor() {
            this.init();
        }

        /**
         * Initialize the customer creation handler
         */
        init() {
            this.bindEvents();
        }

        /**
         * Bind events for customer creation
         */
        bindEvents() {
            document.addEventListener('DOMContentLoaded', () => {
                const createCustomerForm = document.getElementById('createCustomerForm');
                const createCustomerBtn = document.getElementById('createCustomerBtn');
                this.selectOptionsList = document.querySelector('.select-options-list');

                if (createCustomerForm && createCustomerBtn) {
                    new AjaxFormHandler({
                        form: createCustomerForm,
                        button: createCustomerBtn,
                        url: "{{ route('ajax.createCustomer') }}",
                        method: 'POST',
                        modalToHide: '#createCustomerModal',
                        onSuccess: (response) => {
                            this.handleSuccessfulCustomerCreation(response);
                        },
                        onError: (xhr, errorMessage) => {
                            this.handleCustomerCreationError(xhr, errorMessage);
                        }
                    });
                }
            });
        }

        /**
         * Handle successful customer creation
         * @param {Object} response - Server response
         */
        handleSuccessfulCustomerCreation(response) {
            const label = response.data.label;
            const id = response.data.id;

            // Update customer select dropdown
            this.updateCustomerSelect(id, label);

            // Add new option to the dropdown list
            this.addNewCustomerOption(id, label);
        }

        /**
         * Update the customer select dropdown
         * @param {string} id - Customer ID
         * @param {string} label - Customer label
         */
        updateCustomerSelect(id, label) {
            if (typeof customerSelect !== 'undefined') {
                customerSelect.selectButtonLabel.textContent = label;
                customerSelect.selectInput.value = id;
                customerSelect.selectButton.classList.remove('active');
                customerSelect.searchInput.value = '';
                customerSelect.filterItems('');
                customerSelect.updateIconAndLabel();
            }
        }

        /**
         * Add new customer option to the dropdown
         * @param {string} id - Customer ID
         * @param {string} label - Customer label
         */
        addNewCustomerOption(id, label) {
            if (this.selectOptionsList) {
                const newOptionHTML = `
                    <div class="option p-2" data-id="${id}" data-label="${label}">
                        ${label} [${id}]
                    </div>
                `;

                this.selectOptionsList.insertAdjacentHTML('beforeend', newOptionHTML);

                // Add event listener to the newly created option
                const newOption = this.selectOptionsList.lastElementChild;
                newOption.addEventListener('click', () => {
                    this.handleOptionClick(newOption);
                });
            }
        }

        /**
         * Handle option click event
         * @param {HTMLElement} option - Option element
         */
        handleOptionClick(option) {
            const optionId = option.getAttribute('data-id');
            const optionLabel = option.getAttribute('data-label');

            if (typeof customerSelect !== 'undefined') {
                customerSelect.selectButtonLabel.textContent = optionLabel;
                customerSelect.selectInput.value = optionId;
                customerSelect.selectButton.classList.remove('active');
                customerSelect.searchInput.value = '';
                customerSelect.filterItems('');
                customerSelect.updateIconAndLabel();
            }
        }

        /**
         * Handle customer creation error
         * @param {Object} xhr - XMLHttpRequest object
         * @param {string} errorMessage - Error message
         */
        handleCustomerCreationError(xhr, errorMessage) {
            console.error('Failed to insert customer:', xhr.responseText);
            alert('Failed to create customer: ' + errorMessage);
        }
    }

    // Initialize customer creation handler
    new InvoiceCustomerCreationHandler();
</script>

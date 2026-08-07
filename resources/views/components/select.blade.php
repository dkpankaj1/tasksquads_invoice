@props(['name', 'id', 'label' => 'Select Option', 'value' => '', 'options' => []])

<div class="select-container">
    <div class="select-wrapper">
        <div class="select-button form-control d-flex justify-content-between align-items-center">
            <span class="select-button-label">{{ $value ? $options[array_search($value, array_column($options, 'id'))]['label'] ?? $label : $label }}</span>
            <span class="select-button-icon">
                @if ($value)
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle clear-value" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                        <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                    </svg>
                @endif
            </span>
        </div>

        <div class="select-search border rounded shadow-lg mt-2">
            <div class="select-search-input">
                <div class="select-search-input-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </div>
                <input type="text" id="search" placeholder="Search..." class="form-control" autocomplete="off" autocorrect="off" spellcheck="false">
            </div>

            <div class="select-options-list mt-2">
                @foreach ($options as $option)
                    <div class="option p-2" data-id="{{ $option['id'] }}" data-label="{{ $option['label'] }}">
                        {{ $option['label'] }} [{{ $option['id'] }}]
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="select-input">
        <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $value }}">
        @error($name)
            <div class="invalid-feedback d-block my-1 text-danger">✖ {{ $message }}</div>
        @enderror
    </div>
</div>

@push('pageCss')
    <style>
        /* Existing styles remain unchanged */
        .select-container {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .select-wrapper {
            position: relative;
            width: 100%;
        }

        .select-button {
            cursor: pointer;
        }

        /* .select-button .select-button-icon {
            transition: linear 0.2s all
        }

        .select-button.active .select-button-icon {
            transform: rotate(180deg);
        } */

        .select-search {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            display: none;
            background-color: #fff;
            padding: 1rem;
        }

        html[data-bs-theme="dark"] .select-search {
            background-color: #2e343a;
        }

        .select-button.active+.select-search {
            display: block;
        }

        .select-search-input {
            position: relative;
        }

        .select-search .select-search-input .select-search-input-icon {
            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
        }

        .select-search .select-search-input input {
            padding-left: 2.5rem;
        }

        .select-search .select-options-list {
            max-height: 250px;
            overflow-y: auto;
        }

        .select-search .select-options-list .option {
            border-bottom: 1px solid #f4f4f4;
        }

        html[data-bs-theme="dark"] .select-search .select-options-list .option {
            border-bottom: 1px solid #808080;
        }

        .select-search .select-options-list .option:hover {
            background-color: #f4f4f4;
            cursor: pointer;
        }

        html[data-bs-theme="dark"] .select-search .select-options-list .option:hover {
            background-color: #808080;
        }

        /* Add cursor pointer for clear icon */
        .clear-value {
            cursor: pointer;
        }
    </style>
@endpush

@push('pageScript')
    <script>
        $(document).ready(() => {
            const $selectButton = $('.select-button');
            const $selectButtonLabel = $('.select-button-label');
            const $selectButtonIcon = $('.select-button-icon');
            const $selectSearch = $('.select-search');
            const $options = $('.option');
            const $searchInput = $('#search');
            const $selectInput = $('#{{ $id }}');

            // Function to update icon based on value
            function updateIconAndLabel() {
                if ($selectInput.val()) {
                    $selectButtonIcon.html(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle clear-value" viewBox="0 0 16 16">
                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                        </svg>
                    `);
                } else {
                    $selectButtonLabel.text('{{ $label }}');
                    $selectButtonIcon.html(`
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                            <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
                        </svg>
                    `);
                }
            }

            // Initialize icon and label on page load
            updateIconAndLabel();

            // Toggle dropdown
            $selectButton.on('click', (e) => {
                e.stopPropagation();
                if (!$selectButtonIcon.find('.clear-value').length) {
                    $selectButton.toggleClass('active');
                    if ($selectButton.hasClass('active')) {
                        $searchInput.focus();
                    }
                }
            });

            // Handle clear value
            $selectButtonIcon.on('click', '.clear-value', (e) => {
                e.stopPropagation();
                $selectInput.val('');
                updateIconAndLabel();
            });

            // Close dropdown when clicking outside
            $(document).on('click', (e) => {
                if (!$selectButton.is(e.target) &&
                    $selectButton.has(e.target).length === 0 &&
                    !$selectSearch.is(e.target) &&
                    $selectSearch.has(e.target).length === 0) {
                    $selectButton.removeClass('active');
                }
            });

            // Filter items based on search
            $searchInput.on('input', function() {
                const query = $(this).val().toLowerCase();
                filterItems(query);
            });

            // Handle item selection
            $options.on('click', function() {
                const $label = $(this).data('label');
                const $id = $(this).data('id');
                $selectButtonLabel.text($label);
                $selectInput.val($id);
                $selectButton.removeClass('active');
                $searchInput.val('');
                filterItems('');
                updateIconAndLabel();
            });

            // Prevent clicks inside select-search from closing the dropdown
            $selectSearch.on('click', (e) => {
                e.stopPropagation();
            });

            // Filter function
            function filterItems(query) {
                $options.each(function() {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(query));
                });
            }
        });
    </script>
@endpush
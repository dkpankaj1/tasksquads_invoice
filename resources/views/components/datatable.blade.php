@props(['ajaxUrl', 'columns', 'ajaxData' => [], 'filterSelectors' => []])

@push('pageCss')
    <!-- third party css -->
    <link href="{{ asset('assets/backend/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/backend/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <style>
        /* Target the inner loader */
        .dataTables_processing>div {
            display: flex;
            gap: 5px;
            justify-content: center;
            align-items: center;
        }

        /* Style each loader dot */
        .dataTables_processing>div>div {
            width: 12px;
            height: 12px;
            background-color: #007bff;
            border-radius: 50%;
            animation: bounce 1s infinite ease-in-out;
        }

        /* Stagger animation */
        .dataTables_processing>div>div:nth-child(2) {
            animation-delay: 0.1s;
        }

        .dataTables_processing>div>div:nth-child(3) {
            animation-delay: 0.2s;
        }

        .dataTables_processing>div>div:nth-child(4) {
            animation-delay: 0.3s;
        }

        /* Bounce animation keyframes */
        @keyframes bounce {

            0%,
            80%,
            100% {
                transform: scale(0.6);
            }

            40% {
                transform: scale(1);
            }
        }
    </style>
    <!-- third party css end -->
@endpush

<table id="datatable" class="table dt-responsive nowrap w-100 table-striped table-sm"></table>

@push('pageScript')
    <!-- third party js -->
    <script src="{{ asset('assets/backend/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/backend/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/backend/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/backend/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <!-- third party js ends -->

    <script>
        $(document).ready(function() {
            let table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ $ajaxUrl }}',
                ajax: {
                    url: "{{ $ajaxUrl }}",
                    type: "GET",
                    data: function(d) {

                        @foreach ($ajaxData as $key => $selector)
                            d.{{ $key }} = $('{{ $selector }}').val();
                        @endforeach

                    }
                },
                columns: {!! json_encode($columns) !!}
            });

            @if (!empty($filterSelectors))
                $('{{ implode(',', $filterSelectors) }}').on('keyup change', function() {
                    table.draw();
                });
            @endif
        });
    </script>
@endpush

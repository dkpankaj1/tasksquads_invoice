<x-app-layout>

    <x-breadcrumbs :render="Breadcrumbs::render('dashboard')" />

    <div class="row">

        <div class="col-md-4">

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-uppercase font-size-12 text-muted mb-3">Amount Due</h6>
                            <span class="h3 mb-0"> {!! format_money($count['due']) !!}</span>
                        </div>
                        <i data-lucide="wallet" style="font-size: 2.5rem"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">

            <div class="row">

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-uppercase font-size-12 text-muted mb-3">Customer</h6>
                                    <span class="h3 mb-0"> {{ $count['customer'] }} </span>
                                </div>
                                <i data-lucide="users" style="font-size: 2.5rem"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-uppercase font-size-12 text-muted mb-3">Invoices</h6>
                                    <span class="h3 mb-0"> {{ $count['invoice'] }} </span>
                                </div>
                                <i data-lucide="file-text" style="font-size: 2.5rem"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-uppercase font-size-12 text-muted mb-3">Items</h6>
                                    <span class="h3 mb-0"> {{ $count['item'] }} </span>
                                </div>
                                <i data-lucide="box" style="font-size: 2.5rem"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-xl-4 col-lg-5 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title">Top Products</h4>
                    <p class="card-subtitle">Based on invoiced quantity</p>
                </div>

                <div class="card-body">
                    <div id="morris-donut-example" class="morris-chart"></div>
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col-->

        <div class="col-xl-8 col-lg-7 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h4 class="card-title">Sales Analytics</h4>
                    <p class="card-subtitle">Last 12 months</p>
                </div>
                <div class="card-body">
                    <div id="morris-bar-example" class="morris-chart"></div>
                </div>
            </div>
        </div> <!-- end col-->
    </div>
    <!-- end row-->

    <div class="row">
        <div class="col-xl-6">
            <div class="card h-100 mb-3">
                <div class="card-header">
                    <h4 class="card-title">Recent Customers</h4>
                    <p class="card-subtitle">Latest customer registrations and activity</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-striped table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Location</th>
                                    <th>Create Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentCustomers as $c)
                                    <tr>
                                        <td class="table-user">
                                            <span class="me-2 avatar-sm rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white">{{ strtoupper(substr($c->first_name,0,1)) }}</span>
                                            <a href="{{ route('customer.show', $c) }}" class="text-body font-weight-semibold">{{ $c->full_name ?? ($c->first_name.' '.$c->last_name) }}</a>
                                        </td>
                                        <td>{{ $c->phone }}</td>
                                        <td>{{ $c->email }}</td>
                                        <td>{{ $c->city }}, {{ $c->country }}</td>
                                        <td>{{ format_date($c->created_at) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No customers</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->

        <div class="col-xl-6">
            <div class="card h-100 mb-3">
                <div class="card-header">
                    <h4 class="card-title">Recent Invoices</h4>
                    <p class="card-subtitle">Latest invoice transactions and payments</p>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $inv)
                                    <tr>
                                        <td><a href="{{ route('invoice.show', $inv) }}">{{ $inv->invoice_number }}</a></td>
                                        <td>{{ optional($inv->customer)->full_name }}</td>
                                        <td>{{ format_date($inv->invoice_date) }}</td>
                                        <td>{!! format_money($inv->total) !!}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No invoices</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>

                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div> <!-- end col -->
    </div>

    @push('pageScript')
        <script src="{{ asset('assets/backend/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

        <script src="{{ asset('assets/backend/libs/jquery-knob/jquery.knob.min.js') }}"></script>

        <script src="{{ asset('assets/backend/libs/morris.js/morris.min.js') }}"></script>

        <script src="{{ asset('assets/backend/libs/raphael/raphael.min.js') }}"></script>
        <script>
            (function(){
                var donutData = @json($topProducts);
                if (donutData && donutData.length) {
                    new Morris.Donut({
                        element: 'morris-donut-example',
                        data: donutData.map(function(d){ return {label: d.label, value: d.value}; })
                    });
                }

                var barData = @json($salesSeries);
                if (barData && barData.length) {
                    new Morris.Bar({
                        element: 'morris-bar-example',
                        data: barData.map(function(d){ return { y: d.month, a: d.total }; }),
                        xkey: 'y',
                        ykeys: ['a'],
                        labels: ['Sales'],
                        hideHover: 'auto',
                        resize: true
                    });
                }
            })();
        </script>
    @endpush


</x-app-layout>

<x-app-layout>

    @section('buttons')
        <div class="d-flex gap-2">
            <x-add-btn url="{{ route('customer.create') }}" />
            <a href="{{ route('customer.edit', $customer) }}" class="btn btn-warning waves-effect waves-light px-4">
                <i class="fas fa-edit"></i> Edit Customer
            </a>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary waves-effect waves-light px-4">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    @endsection

    <x-breadcrumbs :render="Breadcrumbs::render('customer.show',$customer)" />
    
    <!-- Customer Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center">
                                <div class="avatar-lg bg-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                    <i class="fas fa-user text-white fa-2x"></i>
                                </div>
                                <div>
                                    <h3 class="mb-1">{{ $customer->full_name }}</h3>
                                    <p class="text-muted mb-0">Customer ID: #{{ str_pad($customer->id, 6, '0', STR_PAD_LEFT) }}</p>
                                    <div class="mt-1">
                                        @if($customer->active)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                        <span class="badge bg-info ms-1">
                                            Member since {{ $customer->created_at->format('M Y') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <div class="mt-3 mt-md-0">
                                <h4 class="text-{{ $customer->balance >= 0 ? 'success' : 'danger' }} mb-0">
                                    {!! format_money($customer->balance) !!}
                                </h4>
                                <small class="text-muted">Current Balance</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-primary bg-gradient rounded">
                                <i class="fas fa-chart-line text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Sales</h6>
                            <h4 class="mb-0">{!! format_money($analytics['financial_summary']['total_sales']) !!}</h4>
                            <small class="text-muted">
                                {{ $analytics['financial_summary']['invoice_count'] }} invoices
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-success bg-gradient rounded">
                                <i class="fas fa-money-bill-wave text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Paid</h6>
                            <h4 class="mb-0">{!! format_money($analytics['financial_summary']['total_paid']) !!}</h4>
                            <small class="text-muted">
                                {{ $analytics['payment_statistics']['paid_invoices'] }} paid invoices
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-warning bg-gradient rounded">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Outstanding</h6>
                            <h4 class="mb-0">{!! format_money($analytics['financial_summary']['total_outstanding']) !!}</h4>
                            <small class="text-muted">
                                {{ $analytics['payment_statistics']['unpaid_invoices'] + $analytics['payment_statistics']['partial_invoices'] }} pending
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-sm bg-info bg-gradient rounded">
                                <i class="fas fa-percentage text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Payment Efficiency</h6>
                            <h4 class="mb-0">{{ $analytics['payment_efficiency'] }}%</h4>
                            <small class="text-muted">
                                @if($analytics['growth_rate'] > 0)
                                    <i class="fas fa-arrow-up text-success"></i> {{ $analytics['growth_rate'] }}%
                                @elseif($analytics['growth_rate'] < 0)
                                    <i class="fas fa-arrow-down text-danger"></i> {{ abs($analytics['growth_rate']) }}%
                                @else
                                    <i class="fas fa-minus text-muted"></i> 0%
                                @endif
                                growth
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row">
        <!-- Customer Details Sidebar -->
        <div class="col-lg-4">
            <!-- Basic Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Customer Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <td class="text-muted" style="width: 40%;">Email:</td>
                                    <td>
                                        <a href="mailto:{{ $customer->email }}" class="text-decoration-none">
                                            {{ $customer->email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone:</td>
                                    <td>
                                        <a href="tel:{{ $customer->phone }}" class="text-decoration-none">
                                            {{ $customer->phone }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">WhatsApp:</td>
                                    <td>
                                        <a href="https://wa.me/{{ $customer->whatsapp }}" target="_blank" class="text-decoration-none">
                                            {{ $customer->whatsapp }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Address:</td>
                                    <td>
                                        {{ $customer->address }}<br>
                                        {{ $customer->city }}, {{ $customer->state }}<br>
                                        {{ $customer->country }} - {{ $customer->pin_code }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Statistics -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Payment Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-success mb-1">{{ $analytics['payment_statistics']['paid_invoices'] }}</h4>
                                <small class="text-muted">Paid Invoices</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-warning mb-1">{{ $analytics['payment_statistics']['partial_invoices'] }}</h4>
                                <small class="text-muted">Partial Payments</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-danger mb-1">{{ $analytics['payment_statistics']['unpaid_invoices'] }}</h4>
                                <small class="text-muted">Unpaid Invoices</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded">
                                <h4 class="text-primary mb-1">{{ $analytics['payment_statistics']['total_invoices'] }}</h4>
                                <small class="text-muted">Total Invoices</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Metrics -->
            @if(isset($analytics['performance_metrics']) && $analytics['performance_metrics']['average_invoice_value'] > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Performance Metrics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Avg Invoice Value</span>
                            <strong>{!! format_money($analytics['performance_metrics']['average_invoice_value']) !!}</strong>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Payment Efficiency</span>
                            <strong>{{ $analytics['performance_metrics']['payment_efficiency'] }}%</strong>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar bg-success" style="width: {{ $analytics['performance_metrics']['payment_efficiency'] }}%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-muted">Avg Payment Time</span>
                            <strong>{{ $analytics['performance_metrics']['average_payment_time'] }} days</strong>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Charts and Recent Activity -->
        <div class="col-lg-8">
            <!-- Sales Trend Chart -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-area me-2"></i>Sales Trend (Last 12 Months)
                        </h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i>Export Chart</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Print</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($analytics['monthly_trends']->count() > 0)
                        <div id="salesChart" style="height: 350px;"></div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Sales Data Available</h5>
                            <p class="text-muted">This customer has no sales recorded in the last 12 months.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Invoices -->
            @if($analytics['recent_invoices']->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-invoice me-2"></i>Recent Invoices
                        </h5>
                        <a href="{{ route('invoice.index', ['customer_id' => $customer->id]) }}" class="btn btn-sm btn-outline-primary">
                            View All Invoices
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="border-0">Invoice #</th>
                                    <th class="border-0">Date</th>
                                    <th class="border-0">Amount</th>
                                    <th class="border-0">Paid</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($analytics['recent_invoices'] as $invoice)
                                <tr>
                                    <td>
                                        <strong class="text-primary">{{ $invoice->invoice_number }}</strong>
                                    </td>
                                    <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                    <td><strong>{!! format_money($invoice->total) !!}</strong></td>
                                    <td>{!! format_money($invoice->total_paid) !!}</td>
                                    <td>
                                        @switch($invoice->status)
                                            @case('paid')
                                                <span class="badge bg-success-subtle text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Paid
                                                </span>
                                                @break
                                            @case('partial')
                                                <span class="badge bg-warning-subtle text-warning">
                                                    <i class="fas fa-clock me-1"></i>Partial
                                                </span>
                                                @break
                                            @default
                                                <span class="badge bg-danger-subtle text-danger">
                                                    <i class="fas fa-exclamation-circle me-1"></i>Unpaid
                                                </span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-outline-primary btn-sm" title="View Invoice">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('invoice.edit', $invoice) }}" class="btn btn-outline-warning btn-sm" title="Edit Invoice">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-file-invoice fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Invoices Found</h5>
                    <p class="text-muted">This customer doesn't have any invoices yet.</p>
                    <a href="{{ route('invoice.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create First Invoice
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    @push('pageScript')
        @include('admin.script.apex-chart')

        @if($analytics['monthly_trends']->count() > 0)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Prepare chart data
                const monthlyData = @json($analytics['monthly_trends']);
                
                const salesData = [];
                const paidData = [];
                const labels = [];
                
                monthlyData.forEach(function(item) {
                    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 
                                      'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    const label = monthNames[item.month - 1] + ' ' + item.year;
                    
                    labels.push(label);
                    salesData.push(parseFloat(item.total_sales));
                    paidData.push(parseFloat(item.total_paid));
                });

                // Chart configuration
                const options = {
                    series: [{
                        name: 'Total Sales',
                        type: 'column',
                        data: salesData
                    }, {
                        name: 'Amount Paid',
                        type: 'line',
                        data: paidData
                    }],
                    chart: {
                        height: 350,
                        type: 'line',
                        toolbar: {
                            show: true,
                            tools: {
                                download: true,
                                selection: false,
                                zoom: false,
                                zoomin: false,
                                zoomout: false,
                                pan: false,
                                reset: false
                            }
                        },
                        background: 'transparent'
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 4,
                            columnWidth: '60%'
                        }
                    },
                    stroke: {
                        width: [0, 3],
                        curve: 'smooth'
                    },
                    dataLabels: {
                        enabled: false
                    },
                    labels: labels,
                    xaxis: {
                        type: 'category',
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: [{
                        title: {
                            text: 'Sales Amount',
                            style: {
                                fontSize: '12px'
                            }
                        },
                        labels: {
                            formatter: function (val) {
                                return new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'INR',
                                    minimumFractionDigits: 0
                                }).format(val);
                            }
                        }
                    }, {
                        opposite: true,
                        title: {
                            text: 'Paid Amount',
                            style: {
                                fontSize: '12px'
                            }
                        },
                        labels: {
                            formatter: function (val) {
                                return new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'INR',
                                    minimumFractionDigits: 0
                                }).format(val);
                            }
                        }
                    }],
                    colors: ['#3b82f6', '#10b981'],
                    fill: {
                        type: ['solid', 'gradient'],
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            opacityFrom: 0.8,
                            opacityTo: 0.1,
                        }
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'right',
                        fontSize: '13px'
                    },
                    tooltip: {
                        shared: true,
                        intersect: false,
                        theme: 'light',
                        y: {
                            formatter: function (val) {
                                return new Intl.NumberFormat('en-US', {
                                    style: 'currency',
                                    currency: 'INR'
                                }).format(val);
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f1f5f9',
                        strokeDashArray: 3
                    }
                };

                const chart = new ApexCharts(document.querySelector("#salesChart"), options);
                chart.render();
            });
        </script>
        @endif
    @endpush

    <style>
        .avatar-sm {
            height: 2.5rem;
            width: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .avatar-lg {
            height: 4rem;
            width: 4rem;
        }
        
        .bg-success-subtle {
            background-color: rgba(25, 135, 84, 0.1) !important;
        }
        
        .bg-warning-subtle {
            background-color: rgba(255, 193, 7, 0.1) !important;
        }
        
        .bg-danger-subtle {
            background-color: rgba(220, 53, 69, 0.1) !important;
        }
        
        .card {
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }
        
        .table th {
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
    </style>

</x-app-layout>

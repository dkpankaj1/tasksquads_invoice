<?php

namespace App\Http\Controllers;

use App\Datatables\CustomerDatatable;
use App\Models\Customer;
use App\Services\CustomerAnalyticsService;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CustomerDatatable $datatable)
    {
        if ($request->expectsJson()) {
            return $datatable->get();
        }

        return view('admin.customer.index', [
            'ajaxUrl' => route('customer.index'),
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                ['data' => 'fullname', 'name' => 'fullname', 'title' => 'Name'],
                ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
                ['data' => 'balance', 'name' => 'balance', 'title' => 'Balance'],
                ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created AT'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.customer.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            'whatsapp_mobile' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'vat' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data) {

            Customer::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'whatsapp' => $data['whatsapp_mobile'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => $data['country'],
                'pin_code' => $data['pin_code'],
                'vat' => $data['vat'],
                'active' => $data['status'],
            ]);

            Toastr::success(__('messages.success.created', ['item' => 'customer']));

            return redirect()->route('customer.index');
        });
    }

    /**
     * Display the specified resource.
     *
     * Shows comprehensive customer analytics including financial summary,
     * payment statistics, performance metrics, and recent activity.
     */
    public function show(Customer $customer)
    {
        return TryCatchHandler::execute(function () use ($customer) {

            $customer->load(['invoices' => function ($query) {
                $query->with('payments')->orderBy('invoice_date', 'desc');
            }]);

            // Initialize analytics service
            $analyticsService = new CustomerAnalyticsService($customer);
            $analytics = $analyticsService->getAnalyticsData();

            return view('admin.customer.show', [
                'customer' => $customer,
                'analytics' => [
                    // Financial metrics
                    'financial_summary' => $analytics['financial_summary'],
                    'payment_statistics' => $analytics['payment_statistics'],
                    'performance_metrics' => $analytics['performance_metrics'],

                    // Trend data
                    'monthly_trends' => $analytics['monthly_trends'],
                    'recent_invoices' => $analytics['recent_invoices'],

                    // Additional computed metrics
                    'quick_stats' => $analyticsService->getQuickStats(),
                    'payment_efficiency' => $analyticsService->calculatePaymentEfficiency($analytics['financial_summary']),
                    'growth_rate' => $analyticsService->calculateGrowthRate($analytics['monthly_trends']),
                ],
            ]);
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        return view('admin.customer.form', ['customer' => $customer]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:15'],
            'whatsapp_mobile' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'pin_code' => ['nullable', 'string', 'max:10'],
            'vat' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:0,1'],
        ]);

        return TryCatchHandler::execute(function () use ($data, $customer) {

            $customer->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'whatsapp' => $data['whatsapp_mobile'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => $data['country'],
                'pin_code' => $data['pin_code'],
                'vat' => $data['vat'],
                'active' => $data['status'],
            ]);

            Toastr::success(__('messages.success.updated', ['item' => 'customer']));

            return redirect()->route('customer.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();

            return response()->json([
                'message' => __('messages.success.default'),
                'status' => 'success',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => __('messages.error.default'),
                'status' => 'error',
            ]);
        }
    }
}

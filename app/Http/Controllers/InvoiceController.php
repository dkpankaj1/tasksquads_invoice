<?php

namespace App\Http\Controllers;

use App\Datatables\InvoiceDatatable;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Tax;
use App\Services\InvoiceService;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Exception;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, InvoiceDatatable $invoiceDatatable)
    {
        if ($request->expectsJson()) {
            return $invoiceDatatable->get();
        }

        return view('admin.invoice.index', [
            'ajaxUrl' => route('invoice.index'),
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                ['data' => 'invoice_number', 'name' => 'invoice_number', 'title' => 'Invoice Number'],
                ['data' => 'customer', 'name' => 'customer', 'title' => 'Customer'],
                ['data' => 'invoice_date', 'name' => 'invoice_date', 'title' => 'Invoice Date'],
                ['data' => 'due_date', 'name' => 'due_date', 'title' => 'Due Date'],
                ['data' => 'total', 'name' => 'total', 'title' => 'Total'],
                ['data' => 'total_paid', 'name' => 'total_paid', 'title' => 'Total Paid'],
                ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
                ['data' => 'created_at', 'name' => 'created_at', 'title' => 'Created AT'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
                ['data' => 'more', 'name' => 'more', 'title' => 'More', 'orderable' => false, 'searchable' => false],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::where('active', 1)->get()
            ->map(function ($data) {
                return ['id' => $data->id, 'label' => $data->full_name];
            })->toArray();

        return view('admin.invoice.create', [
            'invoiceNumber' => invoice_number(),
            'customers' => $customers,
            'todayData' => now()->format('Y-m-d'),
            'dueData' => now()->addDays(7)->format('Y-m-d'),
            'taxes' => Tax::where('active', 1)->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer' => ['required', 'exists:customers,id'],
            'invoice_date' => ['required'],
            'due_date' => ['required'],
            'invoice_number' => ['required', 'unique:invoices,invoice_number'],
            'add_cost' => ['required', 'numeric', 'min:0'],
            'discount' => ['required', 'numeric', 'min:0'],
            'discount_type' => ['required', 'in:fixed,percentage'],
            'note' => ['nullable', 'string'],

            'items' => ['required', 'array'],
            'items.product_id.*' => ['required', 'exists:items,id'],
            'items.quantity.*' => ['required', 'numeric', 'min:0'],
            'items.unit_id.*' => ['required', 'exists:units,id'],
            'items.rate.*' => ['required', 'numeric', 'min:0'],
            'items.additional_cost.*' => ['nullable', 'numeric', 'min:0'],
            'items.amount.*' => ['required', 'numeric', 'min:0'],
        ]);

        return TryCatchHandler::execute(function () use ($request) {

            $invoiceData = [
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'customer_id' => $request->customer,
                'additional_cost' => $request->add_cost,
                'discount' => $request->discount,
                'discount_type' => $request->discount_type,
                'notes' => $request->note,
            ];

            $taxIds = $request->input('taxes', []);

            (new InvoiceService)->saveInvoice($invoiceData, $request->items, $taxIds);

            Toastr::success(__('messages.success.created', ['item' => 'Invoice']));

            return redirect()->route('invoice.index');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        // Eager load relations to avoid N+1 queries
        $invoice->load(['customer', 'invoiceItems.item', 'invoiceItems.unit', 'taxes', 'payments']);

        return view('admin.invoice.show', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        // Eager load relations to avoid N+1 and provide data to the viewy
        $invoice->load(['customer', 'invoiceItems.item', 'invoiceItems.unit', 'taxes']);

        $customers = Customer::where('active', 1)->get()->map(function ($data) {
            return ['id' => $data->id, 'label' => $data->full_name];
        })->toArray();

        return view('admin.invoice.edit', [
            'invoice' => $invoice,
            'customers' => $customers,
            'todayData' => now()->format('Y-m-d'),
            'dueData' => now()->addDays(7)->format('Y-m-d'),
            'taxes' => Tax::where('active', 1)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_date' => ['required'],
            'due_date' => ['required'],
            'add_cost' => ['required', 'numeric', 'min:0'],
            'discount' => ['required', 'numeric', 'min:0'],
            'discount_type' => ['required', 'in:fixed,percentage'],
            'note' => ['nullable', 'string'],

            'items' => ['required', 'array'],
            'items.product_id.*' => ['required', 'exists:items,id'],
            'items.quantity.*' => ['required', 'numeric', 'min:0'],
            'items.unit_id.*' => ['required', 'exists:units,id'],
            'items.rate.*' => ['required', 'numeric', 'min:0'],
            'items.additional_cost.*' => ['nullable', 'numeric', 'min:0'],
            'items.amount.*' => ['required', 'numeric', 'min:0'],
        ]);

        return TryCatchHandler::execute(function () use ($request, $invoice) {
            $invoiceData = [
                'invoice_date' => $request->invoice_date,
                'due_date' => $request->due_date,
                'additional_cost' => $request->add_cost,
                'discount' => $request->discount,
                'discount_type' => $request->discount_type,
                'notes' => $request->note,
            ];

            $taxIds = $request->input('taxes', []);

            (new InvoiceService)->updateInvoice($invoice, $invoiceData, $request->items, $taxIds);

            Toastr::success(__('messages.success.updated', ['item' => 'Invoice']));

            return redirect()->route('invoice.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        try {

            $resolve = (new InvoiceService)->deleteInvoice($invoice);

            return response()->json($resolve);
        } catch (Exception $e) {
            return response()->json([
                'message' => __('messages.error.default'),
                'status' => 'error',
            ]);
        }
    }
}

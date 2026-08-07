<?php

namespace App\Http\Controllers;

use App\Datatables\PaymentDatatable;
use App\Models\Invoice;
use App\Models\Payment;
use App\Support\Toastr;
use App\Support\TryCatchHandler;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, PaymentDatatable $paymentDatatable)
    {
        if ($request->expectsJson()) {
            return $paymentDatatable->get();
        }

        return view('admin.payment.index', [
            'ajaxUrl' => route('payment.index'),
            'columns' => [
                ['data' => 'DT_RowIndex', 'name' => 'DT_RowIndex', 'title' => '#', 'searchable' => false, 'orderable' => false],
                ['data' => 'payment_number', 'name' => 'payment_number', 'title' => 'Payment Number'],
                ['data' => 'invoice_number', 'name' => 'invoice.invoice_number', 'title' => 'Invoice'],
                ['data' => 'customer', 'name' => 'invoice.customer.first_name', 'title' => 'Customer'],
                ['data' => 'amount', 'name' => 'amount', 'title' => 'Amount'],
                ['data' => 'payment_method', 'name' => 'payment_method', 'title' => 'Method'],
                ['data' => 'payment_date', 'name' => 'payment_date', 'title' => 'Date'],
                ['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false],
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $invoiceId = $request->get('invoice_id');
        $invoice = null;

        if ($invoiceId) {
            $invoice = Invoice::with(['customer', 'payments'])->findOrFail($invoiceId);
        }

        $invoices = Invoice::with('customer')
            ->whereIn('status', [Invoice::STATUS_UNPAID, Invoice::STATUS_PARTIAL])
            ->get()
            ->map(function ($inv) {
                return [
                    'id' => $inv->id,
                    'label' => $inv->invoice_number.' | '.optional($inv->customer)->full_name.' | '.format_money_plaintext($inv->remaining_amount).' due)',
                ];
            })
            ->toArray();

        return view('admin.payment.create', [
            'paymentNumber' => payment_number(),
            'invoices' => $invoices,
            'selectedInvoice' => $invoice,
            'todayDate' => now()->format('Y-m-d'),
            'paymentMethods' => Payment::getPaymentMethods(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'payment_number' => ['required', 'unique:payments,payment_number'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,credit_card,debit_card,cheque,other'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        // Validate payment amount doesn't exceed remaining balance
        $invoice = Invoice::findOrFail($request->invoice_id);
        $remainingAmount = $invoice->total - $invoice->total_paid;
        // Use bccomp for decimal comparison to avoid floating-point precision issues
        if (bccomp($request->amount, $remainingAmount, 2) > 0) {
            return back()->withErrors([
                'amount' => 'Payment amount cannot exceed remaining balance of '.format_money($remainingAmount),
            ])->withInput();
        }

        return TryCatchHandler::execute(function () use ($request) {
            DB::transaction(function () use ($request) {
                $invoice = Invoice::findOrFail($request->invoice_id);

                $payment = Payment::create([
                    'invoice_id' => $request->invoice_id,
                    'payment_number' => $request->payment_number,
                    'amount' => $request->amount,
                    'payment_date' => Carbon::parse($request->payment_date)->format('Y-m-d'),
                    'payment_method' => $request->payment_method,
                    'reference_number' => $request->reference_number,
                    'notes' => $request->notes,
                ]);

                // Update invoice payment status
                $invoice->updatePaymentStatus();
            });

            Toastr::success(__('messages.success.created', ['item' => 'Payment']));

            return redirect()->route('payment.index');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['invoice.customer', 'invoice.invoiceItems.item', 'invoice.taxes']);

        return view('admin.payment.show', [
            'payment' => $payment,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        $payment->load('invoice.customer');

        return view('admin.payment.edit', [
            'payment' => $payment,
            'paymentMethods' => Payment::getPaymentMethods(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'payment_method' => ['required', 'in:cash,bank_transfer,credit_card,debit_card,cheque,other'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        return TryCatchHandler::execute(function () use ($request, $payment) {
            DB::transaction(function () use ($request, $payment) {
                $invoice = $payment->invoice;

                // Calculate remaining amount excluding current payment
                $otherPaymentsTotal = $invoice->payments()->where('id', '!=', $payment->id)->sum('amount');
                $remainingAmount = $invoice->total - $otherPaymentsTotal;

                // Use bccomp for decimal comparison to avoid floating-point precision issues
                if (bccomp($request->amount, $remainingAmount, 2) > 0) {
                    return back()->withErrors([
                        'amount' => 'Payment amount cannot exceed remaining balance of '.format_money($remainingAmount),
                    ])->withInput();
                }

                $payment->update([
                    'amount' => $request->amount,
                    'payment_date' => Carbon::parse($request->payment_date)->format('Y-m-d'),
                    'payment_method' => $request->payment_method,
                    'reference_number' => $request->reference_number,
                    'notes' => $request->notes,
                ]);

                // Update invoice payment status
                $invoice->updatePaymentStatus();
            });

            Toastr::success(__('messages.success.updated', ['item' => 'Payment']));

            return redirect()->route('payment.index');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        try {
            DB::transaction(function () use ($payment) {
                $invoice = $payment->invoice;
                $payment->delete();

                // Update invoice payment status after deletion
                $invoice->updatePaymentStatus();
            });

            return response()->json([
                'message' => __('messages.success.deleted', ['item' => 'Payment']),
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

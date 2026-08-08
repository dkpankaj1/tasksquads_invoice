<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Tax;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    // ========== Public CRUD Operations ==========

    public function saveInvoice(array $invoiceData, array $invoiceItems, array $taxIds = []): Invoice
    {
        return DB::transaction(function () use ($invoiceData, $invoiceItems, $taxIds) {
            $formattedItems = $this->__formatInvoiceItems($invoiceItems);
            $taxes = $this->__fetchTaxes($taxIds);
            $subtotalWithTax = $this->__calculateSubtotalWithTax($formattedItems, $taxes);
            $discountAmount = $this->__calculateDiscountAmount(
                $subtotalWithTax,
                $invoiceData['discount'] ?? 0,
                $invoiceData['discount_type'] ?? 'fixed'
            );
            $total = ($subtotalWithTax + ($invoiceData['additional_cost'] ?? 0)) - $discountAmount;

            $invoice = Invoice::create([
                'invoice_number' => $invoiceData['invoice_number'],
                'invoice_date' => Carbon::parse($invoiceData['invoice_date'])->format('Y-m-d'),
                'due_date' => Carbon::parse($invoiceData['due_date'])->format('Y-m-d'),
                'customer_id' => $invoiceData['customer_id'],
                'currency_id' => $invoiceData['currency_id'] ?? null,
                'additional_cost' => $invoiceData['additional_cost'] ?? 0,
                'discount' => $invoiceData['discount'] ?? 0,
                'discount_type' => $invoiceData['discount_type'] ?? 'fixed',
                'subtotal' => $subtotalWithTax,
                'total' => $total,
                'total_paid' => 0,
                'service_period' => $invoiceData['service_period'] ?? 0,
                'notes' => $invoiceData['notes'] ?? null,
            ]);

            $invoice->invoiceItems()->createMany($formattedItems);
            $this->__createInvoiceTaxes($invoice, $taxes, $this->__calculateSubtotal($formattedItems));

            return $invoice;
        });
    }

    public function updateInvoice(Invoice $invoice, array $invoiceData, array $invoiceItems, array $taxIds = []): Invoice
    {
        return DB::transaction(function () use ($invoice, $invoiceData, $invoiceItems) {
            $formattedItems = $this->__formatInvoiceItems($invoiceItems);
            $subtotalWithTax = $this->__calculateSubtotalWithTax($formattedItems, $invoice->taxes);
            $discountAmount = $this->__calculateDiscountAmount(
                $subtotalWithTax,
                $invoiceData['discount'] ?? 0,
                $invoiceData['discount_type'] ?? 'fixed'
            );
            $total = ($subtotalWithTax + ($invoiceData['additional_cost'] ?? 0)) - $discountAmount;

            $invoice->update([
                'invoice_date' => Carbon::parse($invoiceData['invoice_date'])->format('Y-m-d'),
                'due_date' => Carbon::parse($invoiceData['due_date'])->format('Y-m-d'),
                'currency_id' => $invoiceData['currency_id'] ?? null,
                'additional_cost' => $invoiceData['additional_cost'] ?? 0,
                'discount' => $invoiceData['discount'] ?? 0,
                'discount_type' => $invoiceData['discount_type'] ?? 'fixed',
                'subtotal' => $subtotalWithTax,
                'total' => $total,
                'service_period' => $invoiceData['service_period'] ?? null,
                'notes' => $invoiceData['notes'] ?? null,
            ]);

            $invoice->invoiceItems()->delete();
            $invoice->invoiceItems()->createMany($formattedItems);
            $this->__updateInvoiceTaxAmounts($invoice, $this->__calculateSubtotal($formattedItems));

            return $invoice;
        });
    }

    public function deleteInvoice(Invoice $invoice)
    {
        $resolve = $this->__validateDeletion($invoice);

        if ($resolve['status'] !== 'error') {
            DB::transaction(function () use ($invoice) {
                $invoice->invoiceItems()->delete();
                $invoice->taxes()->delete();
                $invoice->delete();
            });
        }

        return $resolve;
    }

    // ========== Data Formatting & Validation ==========

    private function __formatInvoiceItems(array $invoiceItemsFromRequest): array
    {
        $formattedItems = [];
        $itemCount = count($invoiceItemsFromRequest['product_id'] ?? []);

        for ($i = 0; $i < $itemCount; $i++) {
            $quantity = (float) ($invoiceItemsFromRequest['quantity'][$i] ?? 0);
            $rate = (float) ($invoiceItemsFromRequest['rate'][$i] ?? 0);
            $additionalCost = (float) ($invoiceItemsFromRequest['additional_cost'][$i] ?? 0);

            $formattedItems[] = [
                'item_id' => $invoiceItemsFromRequest['product_id'][$i],
                'unit_id' => $invoiceItemsFromRequest['unit_id'][$i],
                'quantity' => $quantity,
                'rate' => $rate,
                'additional_cost' => $additionalCost,
                'description' => $invoiceItemsFromRequest['description'][$i] ?? null,
                'amount' => ($quantity * $rate) + ($quantity * $additionalCost),
            ];
        }

        return $formattedItems;
    }

    private function __sanitizeTaxIds(array $taxIdsRequest): array
    {
        return collect($taxIdsRequest)
            ->filter(fn ($id) => ! is_null($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->toArray();
    }

    // ========== Calculation Methods ==========

    private function __calculateSubtotal(array $formattedItems): float
    {
        return array_reduce($formattedItems, function ($carry, $item) {
            return $carry + (float) ($item['amount'] ?? 0);
        }, 0);
    }

    private function __calculateSubtotalWithTax(array $formattedItems, $taxes): float
    {
        $subtotal = $this->__calculateSubtotal($formattedItems);

        if (! $taxes || $taxes->isEmpty()) {
            return $subtotal;
        }

        $totalTax = $taxes->reduce(function ($carry, $tax) use ($subtotal) {
            return $carry + (($subtotal * (float) $tax->rate) / 100);
        }, 0);

        return $subtotal + $totalTax;
    }

    private function __calculateDiscountAmount(float $subtotal, float $discount, string $discountType): float
    {
        if ($discountType === 'percentage') {
            return ($subtotal * $discount) / 100;
        }

        return $discount;
    }

    private function __calculateTaxAmount(float $subtotal, float $taxRate): float
    {
        return ($subtotal * $taxRate) / 100;
    }

    private function __calculateTotalTaxAmount(float $subtotal, Collection $taxes): float
    {
        return $taxes->reduce(function ($carry, $tax) use ($subtotal) {
            return $carry + $this->__calculateTaxAmount($subtotal, (float) $tax->rate);
        }, 0);
    }

    // ========== Tax Management ==========

    private function __fetchTaxes(array $taxIds): Collection
    {
        $selectedTaxIds = $this->__sanitizeTaxIds($taxIds);

        return ! empty($selectedTaxIds)
            ? Tax::whereIn('id', $selectedTaxIds)->get()
            : Tax::where('active', 1)->get();
    }

    private function __createInvoiceTaxes(Invoice $invoice, Collection $taxes, float $subtotal): void
    {
        if ($taxes->isEmpty()) {
            return;
        }

        foreach ($taxes as $tax) {
            $invoice->taxes()->create([
                'tax_id' => $tax->id,
                'name' => $tax->name,
                'rate' => $tax->rate,
                'amount' => $this->__calculateTaxAmount($subtotal, (float) $tax->rate),
            ]);
        }
    }

    private function __updateInvoiceTaxAmounts(Invoice $invoice, float $subtotal): void
    {
        foreach ($invoice->taxes as $tax) {
            $tax->update([
                'amount' => $this->__calculateTaxAmount($subtotal, (float) $tax->rate),
            ]);
        }
    }

    // ========== Validation & Business Logic ==========

    private function __validateDeletion(Invoice $invoice)
    {
        if ($invoice->status === Invoice::STATUS_PAID) {
            return [
                'status' => 'error',
                'message' => __('invoice.error.invoice_paid'),
            ];
        }

        if ($invoice->payments()->exists()) {
            return [
                'status' => 'error',
                'message' => __('invoice.error.invoice_partially_paid'),
            ];
        }

        return [
            'status' => 'success',
            'message' => __('messages.success.default'),
        ];
    }
}

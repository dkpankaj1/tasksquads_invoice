<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\Customization;
use App\Models\Invoice;
use App\Models\Setting;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;

class InvoicePdfController extends Controller
{
    /**
     * Invoke the controller to generate and stream an invoice PDF.
     *
     * Route model binding will inject the Invoice instance.
     */
    public function __invoke(Invoice $invoice): Response
    // public function __invoke(Invoice $invoice)
    {
        // Eager load required relations
        $invoice->load(['customer', 'invoiceItems.item', 'invoiceItems.unit', 'taxes']);

        // Prepare tax summary data
        $appliedTaxes = $invoice->taxes;

        // Map through invoice items to create tax summary
        $taxSummary = $invoice->invoiceItems->map(function ($item) use ($appliedTaxes) {
            $data = [
                'hsn' => $item->item?->hsn_code ?? 'N/A',
                'taxable_value' => $item->amount,
            ];
            foreach ($appliedTaxes as $tax) {
                $data['tax']['name'][] = $tax->name;
                $data['tax']['rate'][] = $tax->rate;
                $data['tax']['amount'][] = ($item->amount * $tax->rate / 100);
            }

            return $data;
        });

        // Get application settings for PDF
        $setting = Setting::first();
        $customization = Customization::where('type', 'invoice')->first();

        // return view('pdf.invoice', [
        //     'invoice' => $invoice,
        //     'taxSummary' => $taxSummary,
        //     'setting' => $setting,
        //     'customization' => $customization,
        // ]);
        // Render HTML via a Blade view
        $html = view('pdf.invoice', [
            'invoice' => $invoice,
            'taxSummary' => $taxSummary,
            'setting' => $setting,
            'customization' => $customization,
        ])->render();

        // Configure Dompdf
        $options = new Options;
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('chroot', public_path());

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'invoice-'.$invoice->invoice_number.'.pdf';

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ]);
    }
}

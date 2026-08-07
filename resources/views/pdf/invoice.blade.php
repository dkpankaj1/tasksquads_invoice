<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tax Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #111;
            margin: 0;
            padding: 0px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        .company-header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            /* background-color: #f5f5f5; */
        }

        .border {
            border: 1px solid #000;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 9px;
        }

        .table th {
            border: 1px solid #000;
        }

        .table td {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            padding: 4px;
            font-size: 10px;
        }

        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .small {
            font-size: 8px;
        }

        .tax-summary {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tax-summary td {
            border: 1px solid #000;
            padding: 4px;
            font-size: 10px;
        }

        .footer-text {
            font-size: 8px;
            margin-top: 10px;
        }

        .logo-section {
            text-align: center;
        }

        .company-logo {
            max-height: 60px;
            max-width: 200px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <!-- Header Section -->
    <center>
        <p colspan="2" style="font-weight: bold;text-align: center; font-size: 0.8rem; padding:0;margin:0">
            {{ text_uppercase('Tax Invoice') }}
        </p>
    </center>
    <table class="header-table">
        <tr>
            <td class="company-header" colspan="2">
                <div class="logo-section">
                    @if ($setting)
                        <img src="{{ $setting->app_logo }}" alt="{{ $setting->brand_name ?? 'Company Logo' }}"
                            class="company-logo">
                    @else
                        <div
                            style="height: 60px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                            No Logo
                        </div>
                    @endif
                </div>
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                <strong>Invoice No.</strong><br>
                {{ $invoice->invoice_number }}<br>
            </td>
            <td style="width: 50%;">
                <strong>Dated</strong><br>
                {{ format_date($invoice->invoice_date) }}<br>
            </td>
        </tr>

        <tr>
            <td style="width: 50%;">
                @if ($setting)
                    <strong>{{ $setting->brand_name }}</strong><br>
                    {{ $setting->address }}, {{ $setting->city }}<br>
                    {{ $setting->state }}, {{ $setting->postal_code }}<br>
                    <strong>State Name:</strong> {{ $setting->state }}<br>
                    <strong>E-Mail:</strong> {{ $setting->contact_email }}<br>
                    <strong>Contact:</strong> {{ $setting->contact_phone }}<br>
                    <strong>GST:</strong> {{ $setting->gstin ?? '' }}
                    <strong>CIN:</strong> {{ $setting->cin ?? '' }}
                @else
                    <strong>Company Name</strong><br>
                    Address not configured<br>
                @endif
            </td>

            <td>
                <strong>Buyer (Bill to) : </strong><br>
                <strong>{{ $invoice->customer?->full_name }}</strong><br>
                {{ $invoice->customer?->address ?? '' }}<br>
                {{ $invoice->customer?->city ?? '' }}<br>
                <strong>State Name:</strong> {{ $invoice->customer->state ?? '' }}, Code:
                {{ $invoice->customer->state_code ?? '' }}<br>
                <strong>E-Mail:</strong> {{ $invoice->customer->email ?? '' }}<br>
                <strong>Contact:</strong> {{ $invoice->customer->phone ?? '' }}<br>
                {{-- <strong>CIN/UIN/IEC:</strong> {{ $invoice->customer->cin ?? '' }}<br> --}}
            </td>

        </tr>
    </table>

    <!-- Items Table -->
    <table class="table">
        <thead>
            <tr>
                <th style="width:5%">SN</th>
                <th style="width:35%">Item</th>
                <th style="width:5%">HSNs</th>
                <th style="width:10%" class="text-right">Qty</th>
                <th style="width:15%" class="text-right">Rate</th>
                <th style="width:12%" class="text-right">Add. Cost</th>
                <th style="width:18%" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody class="border">
            @foreach ($invoice->invoiceItems as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <strong>{{ $row->item?->name }}</strong><br>
                        <span class="small">{{ $row->description }}</span>
                    </td>
                    <td>{{ $row->item?->hsn_code }}</td>
                    <td class="text-right">{{ number_format((float) $row->quantity, 2) }} {{ $row->unit->short_name }}
                    </td>
                    <td class="text-right">{!! format_money((float) $row->rate) !!}</td>
                    <td class="text-right">{!! format_money((float) $row->additional_cost) !!}</td>
                    <td class="text-right">{!! format_money((float) $row->amount) !!}</td>
                </tr>
            @endforeach

            @for ($i = count($invoice->invoiceItems); $i < 6; $i++)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            @endfor
            <tr class="border">
                <td class="text-right" colspan="6">Item Total</td>
                <td class="text-right">{!! format_money((float) $invoice->invoiceItems->sum('amount')) !!}</td>
            </tr>
        </tbody>

        <tfoot>
            @foreach ($invoice->taxes as $tax)
                <tr>
                    <td class="text-left" colspan="3"></td>
                    <td class="text-right" colspan="3"><strong>{{ $tax->name }} : </strong></td>
                    <td class="text-right">(<span class="taxes">{{ $tax->rate }}</span> %)</td>
                </tr>
            @endforeach
            <tr>
                <td class="text-left" colspan="3"></td>
                <td class="text-right" colspan="3"><strong>Additional Cost</strong></td>
                <td class="text-right">{!! format_money((float) $invoice->additional_cost) !!}</td>
            </tr>
            <tr>
                <td class="text-left" colspan="3"></td>
                <td class="text-right" colspan="3"><strong>Discount
                        ({!! $invoice->discount_type == 'percentage'
                            ? $invoice->discount . '%'
                            : format_money((float) $invoice->discount) !!})</strong>
                </td>
                <td class="text-right"> - {!! format_money(
                    (float) ($invoice->discount_type == 'percentage'
                        ? ($invoice->subtotal * $invoice->discount) / 100
                        : $invoice->discount),
                ) !!}</td>
            </tr>
            <tr>
                <td class="text-left" colspan="3"></td>
                <td class="text-right" colspan="3"><strong>Total</strong></td>
                <td class="text-right"><strong>{!! format_money((float) $invoice->total) !!}</strong></td>
            </tr>
            <tr class="border">
                <td colspan="7">
                    <div>
                        <span style="font-size: 9px">Amount Chargeable (in words):</span><br>
                        <strong>{{ ucwords(number_to_words($invoice->total)) }} Only</strong>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>


    <!-- Tax Details -->

    <table class="tax-summary table">
        <thead>
            <tr>
                <th>Taxes</th>
                <th>Rate</th>
                <th class="text-right">Taxable Value</th>
                <th class="text-right">Total Tax Amount</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalTaxAmount = 0;
            @endphp
            @foreach ($invoice->taxes as $tax)
                <tr>
                    <td>{{ $tax->name }}</td>
                    <td>{{ $tax->rate }} %</td>
                    <td class="text-right">{!! format_money($invoice->invoiceItems->sum('amount')) !!}</td>
                    <td class="text-right">{!! format_money(($invoice->invoiceItems->sum('amount') * $tax->rate) / 100) !!}</td>
                </tr>
                @php
                    $totalTaxAmount += ($invoice->invoiceItems->sum('amount') * $tax->rate) / 100;
                @endphp
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td class="text-right" colspan="3"><strong>Total</strong></td>
                <td class="text-right">{!! format_money($totalTaxAmount) !!}</td>
            </tr>
            <tr>
                <td colspan="4">
                    <div>
                        <span style="font-size: 9px">Tax Amount (in words):</span><br>
                        <strong>{{ ucwords(number_to_words($totalTaxAmount)) }} Only</strong>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Footer -->
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 50%; border: 1px solid #000; padding: 8px; vertical-align: top;">
                <strong>{{ $setting->beneficiary_name }}</strong><br>
                <strong>Bank:</strong> {{ $setting->bank_name }}<br>
                <strong>Account Type:</strong> {{ $setting->account_type }}<br>
                <strong>Account Number:</strong> {{ $setting->account_number }}<br>
                <strong>IFSC Code:</strong> {{ $setting->ifsc_code }} &nbsp;|&nbsp;
                <strong>SWIFT/BIC Code:</strong> {{ $setting->swift_bic_code }}<br>
                <strong>Branch:</strong> {{ $setting->branch }}
            </td>
            <td style="width: 50%; border: 1px solid #000; padding: 8px; text-align: right; vertical-align: top;">
                for {{ $setting->brand_name ?? 'Your Company Name' }}<br><br>
                <img style="height: 50px" src="{{ $setting->stamp_image }}" alt="">
            </td>
        </tr>
    </table>

    <table class="table border" style="border-top:none">
        <tr>
            <td>
                <strong>Declaration:</strong><br>
                <span class="small">
                    {{-- Invoice specific notes can be added here --}}
                    {{ $customization->note ?? 'We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.' }}
                </span>
            </td>
        </tr>
    </table>


    <div class="footer-text text-center">
        <strong>This is a Computer Generated Invoice</strong>
    </div>
</body>

</html>

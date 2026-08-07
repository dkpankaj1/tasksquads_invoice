<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // KPI cards
        $count['due'] = Invoice::whereIn('status', [Invoice::STATUS_UNPAID, Invoice::STATUS_PARTIAL])
            ->sum(DB::raw('total - total_paid'));
        $count['customer'] = Customer::count();
        $count['invoice'] = Invoice::count();
        $count['item'] = Item::count();

        // Recent customers and invoices
        $recentCustomers = Customer::orderByDesc('id')->limit(6)->get();
        $recentInvoices = Invoice::with('customer')->orderByDesc('id')->limit(5)->get();

        // Top products by quantity from invoice items
        $topProducts = InvoiceItem::select('item_id', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(amount) as amount'))
            ->groupBy('item_id')
            ->with('item:id,name')
            ->orderByDesc(DB::raw('SUM(quantity)'))
            ->limit(5)
            ->get()
            ->map(function ($row) {
                return [
                    'label' => optional($row->item)->name ?? ('Item #'.$row->item_id),
                    'value' => (float) $row->qty,
                    'amount' => (float) $row->amount,
                ];
            });

        // Sales analytics: totals for last 12 months
        $start = now()->startOfMonth()->subMonths(11);
        $months = collect(range(0, 11))->map(fn ($i) => $start->copy()->addMonths($i));
        $invoices = Invoice::whereBetween('invoice_date', [$start, now()->endOfMonth()])->get();
        $grouped = $invoices->groupBy(fn ($inv) => optional($inv->invoice_date)->format('Y-m'));
        $salesSeries = $months->map(function ($m) use ($grouped) {
            $key = $m->format('Y-m');
            $sum = ($grouped[$key] ?? collect())->sum('total');

            return ['month' => $key, 'total' => (float) $sum];
        });

        return view('admin.pages.dashboard', [
            'count' => $count,
            'recentCustomers' => $recentCustomers,
            'recentInvoices' => $recentInvoices,
            'topProducts' => $topProducts,
            'salesSeries' => $salesSeries,
        ]);
    }
}

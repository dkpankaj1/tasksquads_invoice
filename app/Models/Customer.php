<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'whatsapp',
        'address',
        'city',
        'state',
        'country',
        'pin_code',
        'active',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'active' => 'boolean',
    ];

    /**
     * Get customer's full name
     */
    public function getFullNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    /**
     * Customer invoices relationship
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get total sales amount for this customer
     */
    public function getTotalSalesAttribute()
    {
        return $this->invoices()->sum('total') ?? 0;
    }

    /**
     * Get total paid amount for this customer
     */
    public function getTotalPaidAttribute()
    {
        return $this->invoices()->sum('total_paid') ?? 0;
    }

    /**
     * Get total outstanding amount
     */
    public function getTotalOutstandingAttribute()
    {
        return $this->total_sales - $this->total_paid;
    }

    /**
     * Get invoice count
     */
    public function getInvoiceCountAttribute()
    {
        return $this->invoices()->count();
    }

    /**
     * Get monthly sales data for the last 12 months
     */
    public function getMonthlySalesData()
    {
        // Use database-agnostic date functions
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            $monthExpression = "strftime('%m', invoice_date)";
            $yearExpression = "strftime('%Y', invoice_date)";
        } else {
            // MySQL, PostgreSQL, etc.
            $monthExpression = 'MONTH(invoice_date)';
            $yearExpression = 'YEAR(invoice_date)';
        }

        return $this->invoices()
            ->select(
                DB::raw("$monthExpression as month"),
                DB::raw("$yearExpression as year"),
                DB::raw('SUM(total) as total_sales'),
                DB::raw('SUM(total_paid) as total_paid')
            )
            ->where('invoice_date', '>=', now()->subMonths(12))
            ->groupBy(DB::raw("$yearExpression, $monthExpression"))
            ->orderBy(DB::raw("$yearExpression, $monthExpression"))
            ->get();
    }

    /**
     * Get recent invoices (last 5)
     */
    public function getRecentInvoices($limit = 5)
    {
        return $this->invoices()
            ->latest('invoice_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Get payment statistics
     */
    public function getPaymentStats()
    {
        $invoices = $this->invoices();

        return [
            'total_invoices' => $invoices->count(),
            'paid_invoices' => $invoices->where('status', Invoice::STATUS_PAID)->count(),
            'partial_invoices' => $invoices->where('status', Invoice::STATUS_PARTIAL)->count(),
            'unpaid_invoices' => $invoices->where('status', Invoice::STATUS_UNPAID)->count(),
        ];
    }
}

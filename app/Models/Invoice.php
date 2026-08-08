<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    const STATUS_UNPAID = 'unpaid';

    const STATUS_PAID = 'paid';

    const STATUS_PARTIAL = 'partial';

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'customer_id',
        'currency_id',
        'additional_cost',
        'discount',
        'discount_type',
        'subtotal',
        'total',
        'total_paid',
        'service_period',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'invoice_date' => 'datetime',
        'due_date' => 'datetime',
        'additional_cost' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'total_paid' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $systemSetting = system_setting();
            if ($systemSetting && $systemSetting->finance_year_id) {
                $invoice->finance_year_id = $systemSetting->finance_year_id;
            }
        });
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function financeYear()
    {
        return $this->belongsTo(FinanceYear::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function taxes()
    {
        return $this->hasMany(InvoiceTax::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function updatePaymentStatus()
    {
        $totalPaid = $this->payments()->sum('amount');
        $this->total_paid = $totalPaid;

        // Use bccomp for decimal comparison to avoid floating-point precision issues
        if (bccomp($totalPaid, $this->total, 2) >= 0) {
            $this->status = self::STATUS_PAID;
        } elseif (bccomp($totalPaid, 0, 2) > 0) {
            $this->status = self::STATUS_PARTIAL;
        } else {
            $this->status = self::STATUS_UNPAID;
        }

        $this->save();
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total - $this->total_paid;
    }
}

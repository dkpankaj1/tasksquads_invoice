<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_id',
        'payment_number',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public static function getPaymentMethods()
    {
        return [
            ['id' => 'cash', 'label' => 'Cash'],
            ['id' => 'bank_transfer', 'label' => 'Bank Transfer'],
            ['id' => 'credit_card', 'label' => 'Credit Card'],
            ['id' => 'debit_card', 'label' => 'Debit Card'],
            ['id' => 'cheque', 'label' => 'Cheque'],
            ['id' => 'other', 'label' => 'Other'],
        ];
    }

    public function getFormattedAmountAttribute()
    {
        return format_money($this->amount);
    }

    public function getFormattedDateAttribute()
    {
        return format_date($this->payment_date);
    }

    public function getPaymentMethodLabelAttribute()
    {
        $methods = collect(self::getPaymentMethods());
        $method = $methods->firstWhere('id', $this->payment_method);

        return $method ? $method['label'] : ucwords(str_replace('_', ' ', $this->payment_method));
    }

    public function scopeForInvoice($query, $invoiceId)
    {
        return $query->where('invoice_id', $invoiceId);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }
}

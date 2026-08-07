<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'finance_year_id',
        'currency_id',
        'date_format',
        'auto_tax',
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function financeYear()
    {
        return $this->belongsTo(FinanceYear::class);
    }
}

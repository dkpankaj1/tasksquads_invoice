<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $fillable = [
        'beneficiary_name',
        'bank_name',
        'account_type',
        'account_number',
        'ifsc_code',
        'swift_bic_code',
        'branch',
        'stamp',
    ];
}

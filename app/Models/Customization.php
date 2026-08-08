<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customization extends Model
{
    protected $fillable = [
        'type',
        'series',
        'delimiter',
        'sequence',
        'note',
        'legal_note'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'brand_name',
        'logo',
        'logo_sm',
        'favicon',

        'address',
        'city',
        'state',
        'state_code',
        'postal_code',
        'contact_email',
        'contact_phone',
        'cin',
        'gstin',

        'beneficiary_name',
        'bank_name',
        'account_type',
        'account_number',
        'ifsc_code',
        'swift_bic_code',
        'branch',

        'stamp',

        'facebook_link',
        'twitter_link',
        'instagram_link',
        'linkedin_link',
        'meta_title',
        'meta_description',
        'meta_keywords',

    ];

    public function getAppLogoAttribute()
    {
        return $this->logo == null
            ? asset('assets/backend/images/logo-light.png')
            : asset($this->logo);
    }

    public function getAppLogoSmAttribute()
    {
        return $this->logo_sm == null
            ? asset('assets/backend/images/logo-sm.png')
            : asset($this->logo_sm);
    }

    public function getAppFaviconAttribute()
    {
        return $this->favicon == null
            ? asset('assets/backend/images/favicon.png')
            : asset($this->favicon);
    }

    public function getStampImageAttribute()
    {
        return $this->stamp == null
            ? asset('assets/backend/images/stamp.png')
            : asset($this->stamp);
    }
}

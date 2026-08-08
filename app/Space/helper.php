<?php

use App\Models\Currency;
use App\Models\Customization;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SystemSetting;
use Illuminate\Support\Carbon;

/** =========== hooks :: BEGIN =========== */
function system_setting()
{
    return SystemSetting::first() ?? null;
}
/** =========== hooks :: End =========== */

/** =========== Formatter :: BEGIN =========== */
function format_date($date, $formate = null)
{
    if (! $formate) {
        $formate = SystemSetting::first()->date_format ?? 'd-m-Y';
    }

    $date = $date ?? now();

    $date = is_string($date) ? Carbon::parse($date) : $date;

    return $date->format($formate);
}
function format_money(float $amount, ?Currency $currency = null)
{
    if (! $currency) {
        $currency = SystemSetting::first()->currency;
    }
    $amount = $amount * $currency->exchange_rate;

    $formate_amount = number_format(
        $amount,
        2,
        '.',
        ','
    );

    return "<span>$currency->symbol $formate_amount</span>";
}
function format_money_plaintext($amount, $currency = null)
{
    if (! $currency) {
        $currency = SystemSetting::first()->currency->symbol;
    }

    $formate_amount = number_format(
        $amount,
        2,
        '.',
        ','
    );

    return "$currency $formate_amount";
}
function format_rate($rate)
{
    return "% $rate";
}
/** =========== Formatter :: END =========== */

/** =========== TextFormatter :: BEGIN =========== */
function text_uppercase($text)
{
    return strtoupper($text);
}

function text_lowercase($text)
{
    return strtolower($text);
}

function text_capitalize($text)
{
    return ucfirst(strtolower($text));
}

function text_capitalize_words($text)
{
    return ucwords(strtolower($text));
}

function text_snake_case($text)
{
    return str_replace(' ', '_', strtolower($text));
}

function text_kebab_case($text)
{
    return str_replace(' ', '-', strtolower($text));
}
/** =========== TextFormatter :: END =========== */

/** =========== NumberFormatter :: END =========== */
function pad_number($number, $digitCount)
{
    return str_pad($number, $digitCount, '0', STR_PAD_LEFT);
}
function number_to_words($number, ?Currency $currency = null): string
{
    if (! $currency) {
        $currency = SystemSetting::first()->currency;
    }
    $number = $number * $currency->exchange_rate;

    $number = (float) $number;

    // Handle negative numbers
    if ($number < 0) {
        return 'minus '.number_to_words(abs($number), $currency);
    }

    // Handle zero
    if ($number == 0) {
        return 'zero';
    }

    $integerPart = (int) $number;
    $decimalPart = round(($number - $integerPart) * 100);

    $majorUnit = $currency->major_unit ?? 'unit';
    $minorUnit = $currency->minor_unit ?? 'cent';

    $words = '';

    if ($integerPart > 0) {
        $words = convert_number_to_words($integerPart, $currency->code);
        $words .= ' '.pluralize_unit($majorUnit, $integerPart);
    }

    if ($decimalPart > 0) {
        if ($words !== '') {
            $words .= ' and ';
        }
        $words .= convert_number_to_words($decimalPart, $currency->code);
        $words .= ' '.pluralize_unit($minorUnit, $decimalPart);
    }

    return $words;
}

/**
 * Pluralize a unit name based on count.
 */
function pluralize_unit(string $unit, int $count): string
{
    if ($count === 1) {
        return $unit;
    }

    // Special irregular plurals
    $irregulars = [
        'paisa' => 'paise',
    ];

    return $irregulars[$unit] ?? ($unit.'s');
}

/**
 * Convert an integer to words, using Indian or International numbering based on currency code.
 */
function convert_number_to_words(int $number, string $currencyCode): string
{
    $ones = [
        '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine',
        'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen',
    ];

    $tens = [
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety',
    ];

    if ($number == 0) {
        return '';
    }

    $isIndian = strtoupper($currencyCode) === 'INR';
    $words = '';

    // Indian numbering: crore (10^7), lakh (10^5)
    if ($isIndian) {
        if ($number >= 10000000) {
            $words .= convert_number_to_words((int) ($number / 10000000), $currencyCode).' crore ';
            $number %= 10000000;
        }
        if ($number >= 100000) {
            $words .= convert_number_to_words((int) ($number / 100000), $currencyCode).' lakh ';
            $number %= 100000;
        }
    }
    // International numbering: billion (10^9), million (10^6)
    else {
        if ($number >= 1000000000) {
            $words .= convert_number_to_words((int) ($number / 1000000000), $currencyCode).' billion ';
            $number %= 1000000000;
        }
        if ($number >= 1000000) {
            $words .= convert_number_to_words((int) ($number / 1000000), $currencyCode).' million ';
            $number %= 1000000;
        }
    }

    // Shared: thousand, hundred, tens, ones
    if ($number >= 1000) {
        $words .= convert_number_to_words((int) ($number / 1000), $currencyCode).' thousand ';
        $number %= 1000;
    }
    if ($number >= 100) {
        $words .= $ones[(int) ($number / 100)].' hundred ';
        $number %= 100;
    }
    if ($number >= 20) {
        $words .= $tens[(int) ($number / 10)];
        if ($number % 10 > 0) {
            $words .= ' '.$ones[$number % 10];
        }
    } elseif ($number > 0) {
        $words .= $ones[$number];
    }

    return trim($words);
}
/** =========== NumberFormatter :: END =========== */

/** =========== customization :: END =========== */
function invoice_number()
{
    $invoiceSetting = Customization::where('type', 'invoice')->first();
    // $start = 1;
    $start = Invoice::max('id') + 1;
    do {
        $invoiceNumber = $invoiceSetting->series.$invoiceSetting->delimiter.str_pad((string) $start, $invoiceSetting->sequence, '0', STR_PAD_LEFT);
        $exists = Invoice::where('invoice_number', $invoiceNumber)->exists();
        $start = $start + 1;
    } while ($exists);

    return $invoiceNumber;
}
function payment_number()
{
    $paymentSetting = Customization::where('type', 'payment')->first();
    // $start = 1;
    $start = Payment::max('id') + 1;
    do {
        $paymentNumber = $paymentSetting->series.$paymentSetting->delimiter.str_pad((string) $start, $paymentSetting->sequence, '0', STR_PAD_LEFT);
        $exists = Payment::where('payment_number', $paymentNumber)->exists();
        $start = $start + 1;
    } while ($exists);

    return $paymentNumber;
}
/** =========== customization :: END =========== */

/** =========== SessionHelper :: BEGIN =========== */
function getDeviceInfo($userAgent)
{
    $device = 'Unknown';
    $browser = 'Unknown';
    $deviceIcon = 'monitor'; // Default Lucide icon for unknown device
    $browserIcon = 'globe'; // Default Lucide icon for unknown browser

    // Device detection
    if (stripos($userAgent, 'mobile') !== false) {
        $device = 'Mobile';
        $deviceIcon = 'smartphone'; // Lucide icon for mobile
    } elseif (stripos($userAgent, 'tablet') !== false) {
        $device = 'Tablet';
        $deviceIcon = 'tablet'; // Lucide icon for tablet
    } else {
        $device = 'Desktop';
        $deviceIcon = 'monitor'; // Lucide icon for desktop
    }

    // Browser detection
    if (stripos($userAgent, 'chrome') !== false) {
        $browser = 'Chrome';
        $browserIcon = 'chrome'; // Lucide icon for Chrome
    } elseif (stripos($userAgent, 'firefox') !== false) {
        $browser = 'Firefox';
        $browserIcon = 'globe'; // Lucide icon for Firefox (use generic browser icon if specific not available)
    } elseif (stripos($userAgent, 'safari') !== false) {
        $browser = 'Safari';
        $browserIcon = 'safari'; // Lucide icon for Safari (use generic browser icon if specific not available)
    }

    return [
        'device' => $device,
        'browser' => $browser,
        'device_icon' => $deviceIcon,
        'browser_icon' => $browserIcon,
    ];
}
/** =========== SessionHelper :: END =========== */

<?php

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
function format_money($amount, $currency = null)
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

    return "<span>$currency $formate_amount</span>";
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
function number_to_words($number): string
{
    $number = (float) $number;

    // Handle negative numbers
    if ($number < 0) {
        return 'minus '.number_to_words(abs($number));
    }

    // Handle zero
    if ($number == 0) {
        return 'zero';
    }

    // Determine currency labels based on system setting
    $currencyCode = system_setting()?->currency?->code ?? 'INR';

    if (strtoupper($currencyCode) === 'INR') {
        return number_to_words_inr($number);
    }

    return number_to_words_international($number);
}

function number_to_words_inr(float $number): string
{
    $integerPart = (int) $number;
    $decimalPart = round(($number - $integerPart) * 100);

    $words = '';

    if ($integerPart > 0) {
        $words = convert_integer_to_words_inr($integerPart);
        $words .= $integerPart == 1 ? ' rupee' : ' rupees';
    }

    if ($decimalPart > 0) {
        if ($words !== '') {
            $words .= ' and ';
        }
        $words .= convert_integer_to_words_inr($decimalPart);
        $words .= $decimalPart == 1 ? ' paisa' : ' paise';
    }

    return $words;
}

function number_to_words_international(float $number): string
{
    $integerPart = (int) $number;
    $decimalPart = round(($number - $integerPart) * 100);

    $words = '';

    if ($integerPart > 0) {
        $words = convert_integer_to_words_international($integerPart);
        $words .= $integerPart == 1 ? ' dollar' : ' dollars';
    }

    if ($decimalPart > 0) {
        if ($words !== '') {
            $words .= ' and ';
        }
        $words .= convert_integer_to_words_international($decimalPart);
        $words .= $decimalPart == 1 ? ' cent' : ' cents';
    }

    return $words;
}

function convert_integer_to_words_inr(int $number): string
{
    $ones = [
        '',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'seven',
        'eight',
        'nine',
        'ten',
        'eleven',
        'twelve',
        'thirteen',
        'fourteen',
        'fifteen',
        'sixteen',
        'seventeen',
        'eighteen',
        'nineteen',
    ];

    $tens = [
        '',
        '',
        'twenty',
        'thirty',
        'forty',
        'fifty',
        'sixty',
        'seventy',
        'eighty',
        'ninety',
    ];

    if ($number == 0) {
        return '';
    }

    $words = '';

    // Handle crores (10,000,000)
    if ($number >= 10000000) {
        $crores = (int) ($number / 10000000);
        $words .= convert_integer_to_words_inr($crores).' crore ';
        $number %= 10000000;
    }

    // Handle lakhs (100,000)
    if ($number >= 100000) {
        $lakhs = (int) ($number / 100000);
        $words .= convert_integer_to_words_inr($lakhs).' lakh ';
        $number %= 100000;
    }

    // Handle thousands (1,000)
    if ($number >= 1000) {
        $thousands = (int) ($number / 1000);
        $words .= convert_integer_to_words_inr($thousands).' thousand ';
        $number %= 1000;
    }

    // Handle hundreds (100)
    if ($number >= 100) {
        $hundreds = (int) ($number / 100);
        $words .= $ones[$hundreds].' hundred ';
        $number %= 100;
    }

    // Handle tens and ones
    if ($number >= 20) {
        $tensDigit = (int) ($number / 10);
        $onesDigit = $number % 10;
        $words .= $tens[$tensDigit];
        if ($onesDigit > 0) {
            $words .= ' '.$ones[$onesDigit];
        }
    } elseif ($number > 0) {
        $words .= $ones[$number];
    }

    return trim($words);
}

function convert_integer_to_words_international(int $number): string
{
    $ones = [
        '',
        'one',
        'two',
        'three',
        'four',
        'five',
        'six',
        'seven',
        'eight',
        'nine',
        'ten',
        'eleven',
        'twelve',
        'thirteen',
        'fourteen',
        'fifteen',
        'sixteen',
        'seventeen',
        'eighteen',
        'nineteen',
    ];

    $tens = [
        '',
        '',
        'twenty',
        'thirty',
        'forty',
        'fifty',
        'sixty',
        'seventy',
        'eighty',
        'ninety',
    ];

    if ($number == 0) {
        return '';
    }

    $words = '';

    // Handle billions (1,000,000,000)
    if ($number >= 1000000000) {
        $billions = (int) ($number / 1000000000);
        $words .= convert_integer_to_words_international($billions).' billion ';
        $number %= 1000000000;
    }

    // Handle millions (1,000,000)
    if ($number >= 1000000) {
        $millions = (int) ($number / 1000000);
        $words .= convert_integer_to_words_international($millions).' million ';
        $number %= 1000000;
    }

    // Handle thousands (1,000)
    if ($number >= 1000) {
        $thousands = (int) ($number / 1000);
        $words .= convert_integer_to_words_international($thousands).' thousand ';
        $number %= 1000;
    }

    // Handle hundreds (100)
    if ($number >= 100) {
        $hundreds = (int) ($number / 100);
        $words .= $ones[$hundreds].' hundred ';
        $number %= 100;
    }

    // Handle tens and ones
    if ($number >= 20) {
        $tensDigit = (int) ($number / 10);
        $onesDigit = $number % 10;
        $words .= $tens[$tensDigit];
        if ($onesDigit > 0) {
            $words .= ' '.$ones[$onesDigit];
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

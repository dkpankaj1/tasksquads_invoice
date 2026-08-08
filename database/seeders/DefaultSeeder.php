<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Currency;
use App\Models\Customization;
use App\Models\FinanceYear;
use App\Models\Setting;
use App\Models\SystemSetting;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DefaultSeeder - Seeds default data for the Smart Inventory application
 *
 * TABLE OF CONTENTS:
 * =================
 * 1. User Data (Admin User)
 * 2. Application Settings (Brand, Contact, SEO)
 * 3. Finance Year Configuration
 * 4. Units (Measurement Units)
 * 5. Categories (Product Categories)
 * 6. Currencies (Supported Currencies)
 * 7. Tax Configuration (CGST, SGST)
 * 8. Customization Settings (Invoice, Estimate, Payment Series)
 */
class DefaultSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedUsers();
        $this->seedSettings();
        $this->seedFinanceYears();
        $this->seedUnits();
        $this->seedCategories();
        $this->seedCurrencies();
        $this->seedTaxes();
        $this->seedCustomizations();
        $this->seedSystemSetting();
    }

    /**
     * Seed default admin user
     */
    private function seedUsers(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345'),
        ]);
    }

    /**
     * Seed application settings including brand, contact, and SEO information
     */
    private function seedSettings(): void
    {
        Setting::create([
            // Brand Settings
            'brand_name' => 'TaskSquads Services Pvt. Ltd.',
            'cin' => 'U12345UP2025PTC000000',
            'gstin' => '09ABCDE1234F1Z5',

            // Bank Details
            'beneficiary_name' => 'TASKSQUADS SERVICES PRIVATE LIMITED',
            'bank_name' => 'IDFC FIRST Bank Ltd.',
            'account_type' => 'Current Account',
            'account_number' => 'XXXXXXXX',
            'ifsc_code' => 'XXXXXXXX',
            'swift_bic_code' => 'XXXXXXXX',
            'branch' => 'XXXXXXXX',

            // Address Information
            'address' => 'Shukar Ki Bazar, Near Town Area Office, Kaptanganj',
            'city' => 'Kaptanganj',
            'state' => 'Uttar Pradesh',
            'postal_code' => '274301',
            'country' => 'India',
            'contact_email' => 'info@skjewellers.com',
            'contact_phone' => '+91-9450471185',

            // Social Media Links
            'facebook_link' => 'https://www.facebook.com/#',
            'twitter_link' => 'https://www.twitter.com/#',
            'instagram_link' => 'https://www.instagram.com/#',
            'linkedin_link' => 'https://www.linkedin.com/company/#',

            // SEO Settings
            'meta_title' => 'SmartInventoryPRO - Powerful Inventory & Stock Management Software',
            'meta_description' => 'SmartInventoryPRO simplifies inventory, purchase, sales, and stock tracking with intuitive tools for businesses of all sizes. Boost productivity and reduce errors with our modern inventory management solution.',
            'meta_keywords' => 'inventory software, stock management, inventory control, warehouse management, sales tracking, purchase management, inventory app, SmartInventoryPRO',
        ]);
    }

    /**
     * Seed default finance year
     */
    private function seedFinanceYears(): void
    {
        FinanceYear::create([
            'name' => '2026-2027',
            'start' => '2026-04-01',
            'end' => '2027-03-31',
        ]);
    }

    /**
     * Seed measurement units
     */
    private function seedUnits(): void
    {
        $units = [
            ['name' => 'Days', 'short_name' => 'Day'],
            ['name' => 'Month', 'short_name' => 'MTH'],
            ['name' => 'Year', 'short_name' => 'YER'],
        ];

        foreach ($units as $unit) {
            Unit::create($unit);
        }
    }

    /**
     * Seed product categories for jewellery business
     */
    private function seedCategories(): void
    {
        $categories = [
            ['name' => 'Digital Product', 'short_name' => 'DP'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }

    /**
     * Seed supported currencies
     */
    private function seedCurrencies(): void
    {
        $currencies = [
            [
                'name' => 'United States Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'exchange_rate' => 1,
                'major_unit' => 'dollar',
                'minor_unit' => 'cent',
                'is_base' => true,
            ],
            [
                'name' => 'Indian Rupee',
                'code' => 'INR',
                'symbol' => '₹',
                'exchange_rate' => 93.20,
                'major_unit' => 'rupee',
                'minor_unit' => 'paisa',
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }

    /**
     * Seed tax configuration for Indian GST
     */
    private function seedTaxes(): void
    {
        $taxes = [
            ['name' => 'CGST', 'rate' => 9],
            ['name' => 'SGST', 'rate' => 9],
        ];

        foreach ($taxes as $tax) {
            Tax::create($tax);
        }
    }

    /**
     * Seed customization settings for document numbering
     */
    private function seedCustomizations(): void
    {
        $customizations = [
            ['type' => 'invoice', 'series' => 'INV', 'delimiter' => '-', 'sequence' => 6],
            ['type' => 'estimate', 'series' => 'EST', 'delimiter' => '-', 'sequence' => 6],
            ['type' => 'payment', 'series' => 'PAY', 'delimiter' => '-', 'sequence' => 6],
        ];

        foreach ($customizations as $customization) {
            Customization::create($customization);
        }
    }

    /**
     * Summary of seedSystemSetting
     */
    private function seedSystemSetting(): void
    {
        SystemSetting::create(
            [
                'finance_year_id' => FinanceYear::first()->id,
                'currency_id' => Currency::first()->id,
                'date_format' => 'd-m-Y',
            ]
        );
    }
}

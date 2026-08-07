<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * ItemSeeder - Seeds jewelry items for the Smart Inventory application
 *
 * TABLE OF CONTENTS:
 * =================
 * 1. Silver Items (SLV001-SLV012)
 *    - Basic silver jewelry
 *    - Traditional silver items
 *    - Silver accessories
 * 2. Gold Items (GLD001-GLD022)
 *    - 18 Carat gold jewelry
 *    - Traditional gold items
 *    - Gold accessories and ornaments
 */
class ItemSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->seedSilverItems();
        // $this->seedGoldItems();
    }

    /**
     * Seed silver jewelry items
     */
    private function seedSilverItems(): void
    {
        $silverItems = [
            [
                'hsn_code' => 'SLV001',
                'name' => 'SILVER',
                'rate' => 80.00,
                'additional_cost' => 5.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Pure silver jewelry item',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV002',
                'name' => 'SILVER PAIR PALANI (PAAR PALANI)',
                'rate' => 150.00,
                'additional_cost' => 10.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Traditional silver pair palani jewelry',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV003',
                'name' => 'SLIVER HATH FOOL (PANJA)',
                'rate' => 200.00,
                'additional_cost' => 15.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver hand jewelry (panja)',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV004',
                'name' => 'SILVER KARADHAN (KAMARBANDH)',
                'rate' => 300.00,
                'additional_cost' => 20.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver waist chain (kamarbandh)',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV005',
                'name' => 'SILVER PAAJEB (HEAVY ANLET)',
                'rate' => 250.00,
                'additional_cost' => 18.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Heavy silver anklet (paajeb)',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV006',
                'name' => 'SILVER ANLET (PAYAL)',
                'rate' => 180.00,
                'additional_cost' => 12.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver anklet (payal)',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV007',
                'name' => 'SILVER TOE RING',
                'rate' => 50.00,
                'additional_cost' => 3.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver toe ring',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV008',
                'name' => 'SILVER CHOTI',
                'rate' => 120.00,
                'additional_cost' => 8.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver hair accessory (choti)',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV009',
                'name' => 'SILVER CHAIN',
                'rate' => 100.00,
                'additional_cost' => 7.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver chain',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV010',
                'name' => 'SILVER PENDANT',
                'rate' => 90.00,
                'additional_cost' => 6.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver pendant',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV011',
                'name' => 'SILVER KEY RING',
                'rate' => 30.00,
                'additional_cost' => 2.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver key ring',
                'status' => 1,
            ],
            [
                'hsn_code' => 'SLV012',
                'name' => 'SILVER RING',
                'rate' => 70.00,
                'additional_cost' => 5.00,
                'category_id' => 1,
                'unit_id' => 2,
                'description' => 'Silver ring',
                'status' => 1,
            ],
        ];

        foreach ($silverItems as $item) {
            Item::create($item);
        }
    }

    /**
     * Seed 18 carat gold jewelry items
     */
    private function seedGoldItems(): void
    {
        $goldItems = [
            [
                'hsn_code' => 'GLD001',
                'name' => '18 CARAT GOLD LARI (LARRY)',
                'rate' => 5500.00,
                'additional_cost' => 200.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold lari jewelry',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD002',
                'name' => '18 CARAT GOLD LARI (SUPPORT)',
                'rate' => 5200.00,
                'additional_cost' => 180.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold lari support jewelry',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD003',
                'name' => '18 CARAT GOLD KATYA',
                'rate' => 4800.00,
                'additional_cost' => 150.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold katya jewelry',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD004',
                'name' => '18 CARAT JHAALI',
                'rate' => 4500.00,
                'additional_cost' => 140.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold jhaali jewelry',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD005',
                'name' => '18 CARAT NOSE RING',
                'rate' => 3500.00,
                'additional_cost' => 100.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold nose ring',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD006',
                'name' => '18 CARAT GOLD LATKAN',
                'rate' => 4200.00,
                'additional_cost' => 130.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold latkan jewelry',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD007',
                'name' => '18 CARAT GOLD NATH',
                'rate' => 4000.00,
                'additional_cost' => 120.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold nath (nose jewelry)',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD008',
                'name' => '18 CARAT GOLD MAANG TIKA',
                'rate' => 3800.00,
                'additional_cost' => 110.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold maang tika',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD009',
                'name' => '18 CARAT GOLD BALI',
                'rate' => 3600.00,
                'additional_cost' => 105.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold bali (earrings)',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD010',
                'name' => '18 CARAT GOLD TOPS',
                'rate' => 3400.00,
                'additional_cost' => 100.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold tops (earrings)',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD011',
                'name' => '18 CARAT GOLD SUI DHAGA',
                'rate' => 3200.00,
                'additional_cost' => 95.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold sui dhaga jewelry',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD012',
                'name' => '18 CARAT GOLD KANTHI CHAIN',
                'rate' => 4500.00,
                'additional_cost' => 140.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold kanthi chain',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD013',
                'name' => '18 CARAT GOLD EARRING',
                'rate' => 3300.00,
                'additional_cost' => 98.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold earring',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD014',
                'name' => '18 CARAT GOLD BRACELET',
                'rate' => 4800.00,
                'additional_cost' => 150.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold bracelet',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD015',
                'name' => '18 CARAT GOLD BANGLES',
                'rate' => 5000.00,
                'additional_cost' => 160.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold bangles',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD016',
                'name' => '18 CARAT GOLD PENDANT',
                'rate' => 4000.00,
                'additional_cost' => 120.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold pendant',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD017',
                'name' => '18 CARAT GOLD MANGALSUTS',
                'rate' => 5800.00,
                'additional_cost' => 220.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold mangalsutra',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD018',
                'name' => '18 CARAT GOLD JHUMKA',
                'rate' => 3700.00,
                'additional_cost' => 108.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold jhumka earrings',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD019',
                'name' => '18 CARAT GOLD NECKLACES',
                'rate' => 5500.00,
                'additional_cost' => 200.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold necklaces',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD020',
                'name' => '18 CARAT GOLD CHAIN',
                'rate' => 4800.00,
                'additional_cost' => 150.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold chain',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD021',
                'name' => '18 CARAT GOLD NOSE PIN',
                'rate' => 3000.00,
                'additional_cost' => 85.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold nose pin',
                'status' => 1,
            ],
            [
                'hsn_code' => 'GLD022',
                'name' => '18 CARAT GOLD RING',
                'rate' => 3500.00,
                'additional_cost' => 100.00,
                'category_id' => 2,
                'unit_id' => 2,
                'description' => '18 carat gold ring',
                'status' => 1,
            ],
        ];

        foreach ($goldItems as $item) {
            Item::create($item);
        }
    }
}

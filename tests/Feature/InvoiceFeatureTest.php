<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Tax;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvoiceFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    // System settings and currencies are seeded via migrations; no explicit seeding needed here.

    private function seedTaxes(): array
    {
        $t1 = Tax::create(['name' => 'GST', 'rate' => 18, 'active' => 1]);
        $t2 = Tax::create(['name' => 'CESS', 'rate' => 5, 'active' => 1]);

        return [$t1, $t2];
    }

    private function seedCatalog(): array
    {
        // Use existing seeded categories/units from migrations
        $category = Category::create(['name' => 'test category', 'short_name' => 'TS', 'active' => 1]);
        $unit = Unit::create(['name' => 'Piece', 'short_name' => 'pc', 'active' => 1]);
        $item = Item::create([
            'hsn_code' => '1001',
            'name' => 'Test Item',
            'rate' => 100,
            'additional_cost' => 10,
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'status' => 1,
        ]);
        $customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'whatsapp' => '1234567890',
            'address' => '123 Test Street',
            'city' => 'Test City',
            'state' => 'TS',
            'country' => 'TestLand',
            'pin_code' => '123456',
            'active' => 1,
        ]);

        return [$unit, $item, $customer];
    }

    private function authUser(): User
    {
        return User::factory()->create();
    }

    public function test_invoice_can_be_created_and_stores_items_and_taxes(): void
    {
        [$t1, $t2] = $this->seedTaxes();
        [$unit, $item, $customer] = $this->seedCatalog();
        $user = $this->authUser();

        $payload = [
            'customer' => $customer->id,
            'invoice_date' => '2025-08-20',
            'due_date' => '2025-08-25',
            'invoice_number' => 'INV-TEST-'.Str::upper(Str::random(6)),
            'add_cost' => 5,
            'discount' => 3,
            'discount_type' => 'fixed',
            'note' => 'Test note',
            // Only apply the taxes we just seeded (23%)
            'taxes' => [$t1->id, $t2->id],
            'items' => [
                'product_id' => [$item->id],
                'name' => [$item->name],
                'description' => ['Sample'],
                'hsn_code' => [$item->hsn_code],
                'unit_id' => [$unit->id],
                'unit' => [$unit->short_name],
                'quantity' => [2],
                'rate' => [100],
                'additional_cost' => [10],
                'amount' => [220], // (2*100)+(2*10)
            ],
        ];

        $response = $this->actingAs($user)->post(route('invoice.store'), $payload);

        $response->assertRedirect(route('invoice.index'));

        $invoice = Invoice::where('invoice_number', $payload['invoice_number'])->first();
        $this->assertNotNull($invoice, 'Invoice not created');
        $this->assertEquals(1, $invoice->invoiceItems()->count(), 'Invoice items not stored');

        // Expect taxes snapshot created
        $invoice->load('taxes');
        $this->assertTrue($invoice->taxes->count() >= 1, 'Taxes snapshot not stored');

        // Validate totals (itemsSubtotal=220, taxes=23%=>50.6, subtotal=270.6, total=270.6+5-3=272.6)
        $this->assertEquals(270.60, (float) $invoice->subtotal, '', 0.01);
        $this->assertEquals(272.60, (float) $invoice->total, '', 0.01);
    }

    public function test_invoice_can_be_updated_and_recalculates_totals(): void
    {
        [$t1, $t2] = $this->seedTaxes();
        [$unit, $item, $customer] = $this->seedCatalog();
        $user = $this->authUser();

        // Create first
        $createPayload = [
            'customer' => $customer->id,
            'invoice_date' => '2025-08-20',
            'due_date' => '2025-08-25',
            'invoice_number' => 'INV-TEST-'.Str::upper(Str::random(6)),
            'add_cost' => 5,
            'discount' => 3,
            'discount_type' => 'fixed',
            'note' => 'Test note',
            'taxes' => [$t1->id, $t2->id],
            'items' => [
                'product_id' => [$item->id],
                'name' => [$item->name],
                'description' => ['Sample'],
                'hsn_code' => [$item->hsn_code],
                'unit_id' => [$unit->id],
                'unit' => [$unit->short_name],
                'quantity' => [2],
                'rate' => [100],
                'additional_cost' => [10],
                'amount' => [220],
            ],
        ];

        $this->actingAs($user)->post(route('invoice.store'), $createPayload);
        $invoice = Invoice::where('invoice_number', $createPayload['invoice_number'])->firstOrFail();

        // Update payload: change quantity and discount
        $updatePayload = [
            'invoice_date' => '2025-08-21',
            'due_date' => '2025-08-27',
            'add_cost' => 0,
            'discount' => 10,
            'discount_type' => 'fixed',
            'note' => 'Updated note',
            'items' => [
                'product_id' => [$item->id],
                'name' => [$item->name],
                'description' => ['Updated'],
                'hsn_code' => [$item->hsn_code],
                'unit_id' => [$unit->id],
                'unit' => [$unit->short_name],
                'quantity' => [1],
                'rate' => [100],
                'additional_cost' => [10],
                'amount' => [110], // (1*100)+(1*10)
            ],
        ];

        $response = $this->actingAs($user)->put(route('invoice.update', $invoice), $updatePayload);
        $response->assertRedirect(route('invoice.index'));

        $invoice->refresh();
        // ItemsSubtotal = 110; taxes (snapshot from creation) are still applied on update per controller
        $taxPercent = $invoice->taxes->sum('rate'); // 23
        $taxAmount = 110 * $taxPercent / 100; // 25.3
        $expectedSubtotal = 110 + $taxAmount; // 135.3
        $expectedTotal = $expectedSubtotal + 0 - 10; // 125.3

        $this->assertEquals(round($expectedSubtotal, 2), (float) $invoice->subtotal, '', 0.01);
        $this->assertEquals(round($expectedTotal, 2), (float) $invoice->total, '', 0.01);
    }

    public function test_invoice_calculates_percentage_discount_correctly(): void
    {
        [$t1, $t2] = $this->seedTaxes();
        [$unit, $item, $customer] = $this->seedCatalog();
        $user = $this->authUser();

        $payload = [
            'customer' => $customer->id,
            'invoice_date' => '2025-08-20',
            'due_date' => '2025-08-25',
            'invoice_number' => 'INV-TEST-PCT-'.Str::upper(Str::random(6)),
            'add_cost' => 0,
            'discount' => 10, // 10% discount
            'discount_type' => 'percentage',
            'note' => 'Test percentage discount',
            'taxes' => [$t1->id, $t2->id],
            'items' => [
                'product_id' => [$item->id],
                'name' => [$item->name],
                'description' => ['Sample'],
                'hsn_code' => [$item->hsn_code],
                'unit_id' => [$unit->id],
                'unit' => [$unit->short_name],
                'quantity' => [1],
                'rate' => [100],
                'additional_cost' => [0],
                'amount' => [100],
            ],
        ];

        $response = $this->actingAs($user)->post(route('invoice.store'), $payload);
        $response->assertRedirect(route('invoice.index'));

        $invoice = Invoice::where('invoice_number', $payload['invoice_number'])->first();
        $this->assertNotNull($invoice, 'Invoice not created');
        $this->assertEquals('percentage', $invoice->discount_type, 'Discount type not saved correctly');
        $this->assertEquals(10, (float) $invoice->discount, 'Discount percentage not saved correctly');

        // Calculate expected totals with percentage discount
        $itemsSubtotal = 100; // 1 * 100 + 1 * 0
        $taxPercent = 23; // t1 (18%) + t2 (5%)
        $taxAmount = $itemsSubtotal * $taxPercent / 100; // 23
        $subtotalWithTax = $itemsSubtotal + $taxAmount; // 123
        $percentageDiscountAmount = $subtotalWithTax * 10 / 100; // 12.3
        $expectedTotal = $subtotalWithTax - $percentageDiscountAmount; // 110.7

        $this->assertEquals(round($subtotalWithTax, 2), (float) $invoice->subtotal, '', 0.01);
        $this->assertEquals(round($expectedTotal, 2), (float) $invoice->total, '', 0.01);
    }

    public function test_invoice_show_page_displays_invoice_number(): void
    {
        [$t1, $t2] = $this->seedTaxes();
        [$unit, $item, $customer] = $this->seedCatalog();
        $user = $this->authUser();

        $payload = [
            'customer' => $customer->id,
            'invoice_date' => '2025-08-20',
            'due_date' => '2025-08-25',
            'invoice_number' => 'INV-TEST-'.Str::upper(Str::random(6)),
            'add_cost' => 0,
            'discount' => 0,
            'discount_type' => 'fixed',
            'taxes' => [$t1->id, $t2->id],
            'items' => [
                'product_id' => [$item->id],
                'name' => [$item->name],
                'description' => ['Sample'],
                'hsn_code' => [$item->hsn_code],
                'unit_id' => [$unit->id],
                'unit' => [$unit->short_name],
                'quantity' => [1],
                'rate' => [100],
                'additional_cost' => [0],
                'amount' => [100],
            ],
        ];
        $this->actingAs($user)->post(route('invoice.store'), $payload);
        $invoice = Invoice::where('invoice_number', $payload['invoice_number'])->firstOrFail();

        $res = $this->actingAs($user)->get(route('invoice.show', $invoice));
        $res->assertOk();
        $res->assertSee($invoice->invoice_number);
    }

    public function test_invoice_can_delete()
    {
        [$t1, $t2] = $this->seedTaxes();
        [$unit, $item, $customer] = $this->seedCatalog();
        $user = $this->authUser();

        $payload = [
            'customer' => $customer->id,
            'invoice_date' => '2025-08-20',
            'due_date' => '2025-08-25',
            'invoice_number' => 'INV-TEST-'.Str::upper(Str::random(6)),
            'add_cost' => 0,
            'discount' => 0,
            'discount_type' => 'fixed',
            'taxes' => [$t1->id, $t2->id],
            'items' => [
                'product_id' => [$item->id],
                'name' => [$item->name],
                'description' => ['Sample'],
                'hsn_code' => [$item->hsn_code],
                'unit_id' => [$unit->id],
                'unit' => [$unit->short_name],
                'quantity' => [1],
                'rate' => [100],
                'additional_cost' => [0],
                'amount' => [100],
            ],
        ];
        $this->actingAs($user)->post(route('invoice.store'), $payload);

        $invoice = Invoice::where('invoice_number', $payload['invoice_number'])->firstOrFail();

        $response = $this->actingAs($user)->delete(route('invoice.destroy', $invoice));

        $response->assertStatus(200);

        $response->assertJson([
            'status' => 'success',
        ]);

        $this->assertDatabaseMissing('invoices', [
            'id' => $invoice->id,
        ]);
    }

    public function test_invoice_pdf_streams_successfully(): void
    {
        [$t1, $t2] = $this->seedTaxes();
        [$unit, $item, $customer] = $this->seedCatalog();
        $user = $this->authUser();

        $payload = [
            'customer' => $customer->id,
            'invoice_date' => '2025-08-20',
            'due_date' => '2025-08-25',
            'invoice_number' => 'INV-TEST-'.Str::upper(Str::random(6)),
            'add_cost' => 0,
            'discount' => 0,
            'discount_type' => 'fixed',
            'taxes' => [$t1->id, $t2->id],
            'items' => [
                'product_id' => [$item->id],
                'name' => [$item->name],
                'description' => ['Sample'],
                'hsn_code' => [$item->hsn_code],
                'unit_id' => [$unit->id],
                'unit' => [$unit->short_name],
                'quantity' => [1],
                'rate' => [100],
                'additional_cost' => [0],
                'amount' => [100],
            ],
        ];
        $this->actingAs($user)->post(route('invoice.store'), $payload);
        $invoice = Invoice::where('invoice_number', $payload['invoice_number'])->firstOrFail();

        $res = $this->actingAs($user)->get(route('invoice.pdf', $invoice));
        $res->assertOk();
        $res->assertHeader('Content-Type', 'application/pdf');
    }
}

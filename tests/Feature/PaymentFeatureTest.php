<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PaymentFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function seedData(): array
    {
        $category = Category::create(['name' => 'Test Category', 'short_name' => 'TC', 'active' => 1]);
        $unit = Unit::create(['name' => 'Piece', 'short_name' => 'pc', 'active' => 1]);
        $item = Item::create([
            'hsn_code' => '1001',
            'name' => 'Test Item',
            'rate' => 100,
            'additional_cost' => 0,
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
            'country' => 'Testland',
            'pin_code' => '123456',
            'active' => 1,
        ]);

        return [$unit, $item, $customer];
    }

    private function createInvoice($customer, $item, $unit): Invoice
    {
        return Invoice::create([
            'invoice_number' => 'INV-TEST-'.Str::upper(Str::random(6)),
            'invoice_date' => now(),
            'due_date' => now()->addDays(7),
            'customer_id' => $customer->id,
            'additional_cost' => 0,
            'discount' => 0,
            'subtotal' => 100,
            'total' => 100,
            'total_paid' => 0,
            'status' => Invoice::STATUS_UNPAID,
        ]);
    }

    public function test_payment_can_be_created_for_invoice(): void
    {
        [$unit, $item, $customer] = $this->seedData();
        $invoice = $this->createInvoice($customer, $item, $unit);
        /** @var User $user */
        $user = User::factory()->create();

        $payload = [
            'invoice_id' => $invoice->id,
            'payment_number' => 'PAY-2025-000001',
            'amount' => 50.00,
            'payment_date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
            'reference_number' => 'REF-001',
            'notes' => 'Test payment',
        ];

        $response = $this->actingAs($user)->post(route('payment.store'), $payload);

        $response->assertRedirect(route('payment.index'));

        $payment = Payment::where('payment_number', $payload['payment_number'])->first();
        $this->assertNotNull($payment, 'Payment not created');
        $this->assertEquals(50.00, (float) $payment->amount);

        // Check invoice status updated
        $invoice->refresh();
        $this->assertEquals(50.00, (float) $invoice->total_paid);
        $this->assertEquals(Invoice::STATUS_PARTIAL, $invoice->status);
    }

    public function test_full_payment_marks_invoice_as_paid(): void
    {
        [$unit, $item, $customer] = $this->seedData();
        $invoice = $this->createInvoice($customer, $item, $unit);
        /** @var User $user */
        $user = User::factory()->create();

        $payload = [
            'invoice_id' => $invoice->id,
            'payment_number' => 'PAY-2025-000002',
            'amount' => 100.00, // Full amount
            'payment_date' => now()->format('Y-m-d'),
            'payment_method' => 'bank_transfer',
        ];

        $response = $this->actingAs($user)->post(route('payment.store'), $payload);

        $response->assertRedirect(route('payment.index'));

        // Check invoice status updated to paid
        $invoice->refresh();
        $this->assertEquals(100.00, (float) $invoice->total_paid);
        $this->assertEquals(Invoice::STATUS_PAID, $invoice->status);
    }

    public function test_payment_amount_cannot_exceed_remaining_balance(): void
    {
        [$unit, $item, $customer] = $this->seedData();
        $invoice = $this->createInvoice($customer, $item, $unit);
        /** @var User $user */
        $user = User::factory()->create();

        $payload = [
            'invoice_id' => $invoice->id,
            'payment_number' => 'PAY-2025-000003',
            'amount' => 150.00, // More than invoice total
            'payment_date' => now()->format('Y-m-d'),
            'payment_method' => 'cash',
        ];

        $response = $this->actingAs($user)->post(route('payment.store'), $payload);

        $response->assertSessionHasErrors(); // Should have validation errors
        $this->assertEquals(0, Payment::count()); // No payment should be created
    }

    public function test_payment_show_page_displays_payment_details(): void
    {
        [$unit, $item, $customer] = $this->seedData();
        $invoice = $this->createInvoice($customer, $item, $unit);
        /** @var User $user */
        $user = User::factory()->create();

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'payment_number' => 'PAY-2025-000004',
            'amount' => 50.00,
            'payment_date' => now(),
            'payment_method' => 'cash',
            'reference_number' => 'REF-004',
            'notes' => 'Test payment show',
        ]);

        $response = $this->actingAs($user)->get(route('payment.show', $payment));

        $response->assertOk();
        $response->assertSee($payment->payment_number);
        $response->assertSee('50.00');
        $response->assertSee('REF-004');
    }

    public function test_payment_can_be_updated(): void
    {
        [$unit, $item, $customer] = $this->seedData();
        $invoice = $this->createInvoice($customer, $item, $unit);
        /** @var User $user */
        $user = User::factory()->create();

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'payment_number' => 'PAY-2025-000005',
            'amount' => 30.00,
            'payment_date' => now(),
            'payment_method' => 'cash',
        ]);

        // Update the payment
        $payload = [
            'invoice_id' => $invoice->id,
            'payment_number' => 'PAY-2025-000005',
            'amount' => 40.00, // Increase amount
            'payment_date' => now()->format('Y-m-d'),
            'payment_method' => 'bank_transfer', // Change method
            'reference_number' => 'REF-UPDATE',
            'notes' => 'Updated payment',
        ];

        $response = $this->actingAs($user)->put(route('payment.update', $payment), $payload);

        $response->assertRedirect(route('payment.index'));

        $payment->refresh();
        $this->assertEquals(40.00, (float) $payment->amount);
        $this->assertEquals('bank_transfer', $payment->payment_method);
        $this->assertEquals('REF-UPDATE', $payment->reference_number);

        // Check invoice status updated
        $invoice->refresh();
        $this->assertEquals(40.00, (float) $invoice->total_paid);
        $this->assertEquals(Invoice::STATUS_PARTIAL, $invoice->status);
    }

    public function test_payment_model_attributes_and_scopes(): void
    {
        [$unit, $item, $customer] = $this->seedData();
        $invoice = $this->createInvoice($customer, $item, $unit);

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'payment_number' => 'PAY-2025-000006',
            'amount' => 75.50,
            'payment_date' => now(),
            'payment_method' => 'bank_transfer',
            'reference_number' => 'REF-006',
            'notes' => 'Test payment attributes',
        ]);

        // Test formatted attributes
        $this->assertStringContainsString('75.50', $payment->formatted_amount);
        $this->assertNotEmpty($payment->formatted_date);
        $this->assertEquals('Bank Transfer', $payment->payment_method_label);

        // Test scopes
        $paymentsForInvoice = Payment::forInvoice($invoice->id)->get();
        $this->assertCount(1, $paymentsForInvoice);

        $bankTransferPayments = Payment::byMethod('bank_transfer')->get();
        $this->assertCount(1, $bankTransferPayments);

        $todayPayments = Payment::inDateRange(now()->startOfDay(), now()->endOfDay())->get();
        $this->assertCount(1, $todayPayments);
    }
}

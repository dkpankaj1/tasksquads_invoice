<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Customer $customer;

    protected $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->customer = Customer::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '0123456789',
            'whatsapp' => '0123456789',
            'address' => '123 Main St',
            'city' => 'AnyTown',
            'state' => 'AnyState',
            'country' => 'AnyCountry',
            'pin_code' => '123456',
            'active' => 1,
        ]);
    }

    public function test_can_list_customers()
    {
        $response = $this->actingAs($this->user)->get(route('customer.index'));
        $response->assertStatus(200);

        $response->assertViewIs('admin.customer.index');

        $response->assertViewHas('ajaxUrl');
    }

    public function test_can_create_customer()
    {
        $response = $this->actingAs($this->user)->post(route('customer.store'), [
            'first_name' => 'TestFirstName',
            'last_name' => 'TestLastName',
            'email' => 'email@test.com',
            'phone' => '1234567890',
            'whatsapp_mobile' => '1234567890',
            'address' => '123 Main St',
            'city' => 'AnyTown',
            'state' => 'AnyState',
            'country' => 'AnyCountry',
            'pin_code' => '123456',
            'status' => '1',
        ]);

        $response->assertRedirect(route('customer.index'));

        $this->assertDatabaseHas('customers', [
            'email' => 'email@test.com',
            'whatsapp' => '1234567890',
        ]);
    }

    public function test_can_show_customer()
    {

        $customer = $this->customer;

        $response = $this->actingAs($this->user)->get(route('customer.show', $customer));

        $response->assertStatus(200);
        $response->assertViewIs('admin.customer.show');
        $response->assertViewHas('customer', $customer);
    }

    public function test_can_update_customer()
    {

        $customer = $this->customer;

        $response = $this->actingAs($this->user)->put(route('customer.update', $customer), [
            'first_name' => 'UpdateFirstName',
            'last_name' => 'UpdateLastName',
            'email' => 'email@update.com',
            'phone' => '1122334455',
            'whatsapp_mobile' => '1122334455',
            'address' => '123 Update St',
            'city' => 'OtherTown',
            'state' => 'OtherState',
            'country' => 'OtherCountry',
            'pin_code' => '654321',
            'status' => '1',
        ]);

        $response->assertRedirect(route('customer.index'));

        $this->assertDatabaseHas('customers', [
            'email' => 'email@update.com',
            'whatsapp' => '1122334455',
        ]);
    }

    public function test_can_delete_customer()
    {
        $customer = $this->customer;

        $response = $this->actingAs($this->user)->delete(route('customer.destroy', $customer));

        $response->assertStatus(200);

        $response->assertJson([
            'status' => 'success',
        ]);

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }
}

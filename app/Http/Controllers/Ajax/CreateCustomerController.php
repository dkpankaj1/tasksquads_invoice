<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CreateCustomerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        try {
            $data = $request->validate([
                'customer_first_name' => ['required', 'string', 'max:50'],
                'customer_last_name' => ['nullable', 'string', 'max:50'],
                'customer_email' => ['nullable', 'email', 'max:255'],
                'customer_phone' => ['nullable', 'string', 'max:15'],
                'customer_whatsapp_mobile' => ['nullable', 'string', 'max:15'],
                'customer_address' => ['nullable', 'string', 'max:255'],
                'customer_city' => ['nullable', 'string', 'max:100'],
                'customer_state' => ['nullable', 'string', 'max:100'],
                'customer_country' => ['nullable', 'string', 'max:100'],
                'customer_pin_code' => ['nullable', 'string', 'max:10'],
            ]);

            $customer = Customer::create([
                'first_name' => $data['customer_first_name'],
                'last_name' => $data['customer_last_name'],
                'email' => $data['customer_email'],
                'phone' => $data['customer_phone'],
                'whatsapp' => $data['customer_whatsapp_mobile'],
                'address' => $data['customer_address'],
                'city' => $data['customer_city'],
                'state' => $data['customer_state'],
                'country' => $data['customer_country'],
                'pin_code' => $data['customer_pin_code'],
                'active' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => ['id' => $customer->id, 'label' => $customer->full_name],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            dd($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the item',
            ], 500);
        }
    }
}

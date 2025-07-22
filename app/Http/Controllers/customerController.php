<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class customerController extends Controller
{
    //
    public function create( Request $request){

        // Create or find customer (simplified here: always create new)
        $customer = Customer::create([
            'name' => $request->customer,
            'phone' => $request->phone,
            'address' => $request->address,
            'table_number' => $request->table,
            'order_type' => $request->order,
        ]);
        // Create empty order for this customer
    //      $order = Order::create([
    //     'customer_id' => $customer->id,
    //     'table' => $request->table,
    //     'order_type' => $request->order,
    //     'note' => $request->note,
    //     'subtotal' => 0,
    //     'tax' => 0,
    //     'total' => 0,
    // ]);
        return redirect('/');
    }
}

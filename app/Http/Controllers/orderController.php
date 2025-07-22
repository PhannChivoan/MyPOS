<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class orderController extends Controller
{
   public function updateOrder(Request $request, $orderId)
    {
        $request->validate([
            'cart' => 'required',
            'customer_id' => 'required|exists:customers,id',
            'note' => 'nullable|string',
            'table' => 'nullable|string',
            'order_type' => 'nullable|string',
        ]);

        $order = Order::findOrFail($orderId);

        // Update order data
        $order->update([
            'customer_id' => $request->customer_id,
            'note' => $request->note,
            // You can calculate subtotal/tax/total here if needed
        ]);

        // Optional: Clear and re-add order items
        $order->orderItems()->delete();

        $cartItems = json_decode($request->cart, true);

        foreach ($cartItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        return response()->json(['message' => 'Order updated successfully']);
    }

    public function saveOrder(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'cart' => 'required|json',
        ]);

        $cartItems = json_decode($request->cart, true);

        // 1. Try to get latest (or active) order for the customer
        $order = Order::where('customer_id', $request->customer_id)->latest()->first();

        if (!$order) {
            //  2. Create a new order if not found
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'note' => '',
                'subtotal' => 0,
                'tax' => 0,
                'total' => 0,
            ]);
        } else {
            // Clear old items if re-using existing order
            $order->orderItems()->delete();
        }

        $subtotal = 0;

        foreach ($cartItems as $item) {
            $order->orderItems()->create([
                'product_id' => $item['product_id'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);

            $subtotal += $item['price'] * $item['quantity'];
        }

        // Optionally calculate totals and update
        $tax = $subtotal * 0.1; // example: 10% tax
        $total = $subtotal + $tax;

        $order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        return response()->json(['message' => 'Order saved successfully!', 'order_id' => $order->id]);
    }


    public function tableView()
    {
        $orders = Order::with(['customer','orderItems.product'])
                        ->whereHas('customer', function ($query) {
                            $query->where('order_type', 'dine-in');

                        })->where('status','unpaid')
                        ->get();
        $orderDs = Order::with(['customer','orderItems.product'])
                        ->whereHas('customer', function ($query) {
                            $query->where('order_type', 'delivery');
                        })
                        ->get();
                        $product = Product::all();

        return view('Layout.customers', ['order' => $orders,'delivery'=>$orderDs,'products'=>$product]);
    }

    public function status($id){
        $order = Order::findOrFail($id);
        $order->status = 'paid';
        $order->save();
        return response()->json(['success' => true]);
    }

    public function report(){
        $report = Order::with(['customer','orderItems.product'])->where('status','paid')->get();
        return view('Layout.reports',['report'=>$report]);
    }

    public function addItemToOrderBulk(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',
        ]);

        // Get latest order
        $order = Order::where('customer_id', $request->customer_id)->latest()->first();

        if (!$order) {
            return response()->json(['error' => 'No active order found for this customer.'], 404);
        }

        foreach ($request->product_id as $index => $productId) {
            $quantity = $request->quantity[$index];
            $price = $request->price[$index]; // ✅ use submitted price

            // Check for existing item
            $existingItem = $order->orderItems()->where('product_id', $productId)->first();

            if ($existingItem) {
                $existingItem->quantity += $quantity;
                $existingItem->save();
            } else {
                $order->orderItems()->create([
                    'product_id' => $productId,
                    'price' => $price,
                    'quantity' => $quantity,
                ]);
            }
        }

        // Recalculate order totals
        $this->recalculateOrder($order);

        return redirect('/customers');
    }


    // ----------------- Remove Order
    public function removeItemFromOrder(Request $request)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
        ]);

        $orderItem = \App\Models\OrderItem::find($request->order_item_id);
        $order = $orderItem->order;

        $orderItem->delete();

        // Recalculate totals
        $this->recalculateOrder($order);

        return response()->json(['message' => 'Item removed successfully.']);
    }
    // Calculate Order
    protected function recalculateOrder($order)
    {
        $subtotal = 0;

        foreach ($order->orderItems as $item) {
            $subtotal += $item->price * $item->quantity;
        }

        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;

        $order->update([
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);
    }



}

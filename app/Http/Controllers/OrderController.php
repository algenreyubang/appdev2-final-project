<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Order::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,cancelled',
            'items' => 'required|string',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);
    
        // Create the order
        $order = Order::create([
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
        ]);
    
        // Attach items to the order
        foreach ($request->items as $item) {
            $order->items()->create([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }
    
        // Optionally, recalculate total amount if needed
        // $totalAmount = $order->items->sum(fn($item) => $item->quantity * $item->price);
        // $order->update(['total_amount' => $totalAmount]);
    
        // Return the created order with its items
        return $order->load('items');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $order = Order::findOrFail($id); // Use findOrFail to get user or throw 404
        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
{
    // Validate the request
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'total_amount' => 'required|numeric',
        'status' => 'required|in:pending,completed,cancelled',
    ]);

    // Update the order
    $order->update([
        'user_id' => $request->user_id,
        'total_amount' => $request->total_amount,
        'status' => $request->status,
    ]);

    // Load the order with related items and user information
    return $order->load('items', 'user');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully',
           
            'status' => true
        ], 200);
    
    }
}

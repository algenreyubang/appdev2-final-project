<?php

namespace App\Http\Controllers;
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
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
        ]);
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_amount' => 'required|numeric',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $order->update([
            'user_id' => $request->user_id,
            'total_amount' => $request->total_amount,
            'status' => $request->status,
        ]);

        return $order->load('items');
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

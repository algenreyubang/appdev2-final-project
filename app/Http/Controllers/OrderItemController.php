<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return OrderItem::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $orderItem = OrderItem::create($request->all());

        return $orderItem;
    }

    public function show(int $id)
    {
        $orderItem = OrderItem::findOrFail($id); // Use findOrFail to get user or throw 404
        return response()->json($orderItem);
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $orderItem->update($request->all());

        return $orderItem;
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();

        return response()->json([
            'message' => 'OrderItem deleted successfully',
           
            'status' => true
        ], 200);
    }
}

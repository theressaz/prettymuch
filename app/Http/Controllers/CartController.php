<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\cart_item;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $user = Auth::user();
        $cart = $user->cart;
        if ($cart == null) {
            return back()->withErrors("");
        }

        // ddd($cart->cart_items->load('item'));
        $cart_with_item = $cart->cart_items->load('item');
        // ddd($cart_with_item);
        
        return view('store.cart', [
            'cart' => $cart_with_item,
            'total_price' => $cart->total_price
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cart $cart)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        // TODO: validate request

        // TODO: if 0 then delete item_cart
        
        $quantity = $request->get('quantity');
        $old_qty = $request->get('old_cart_qty');
        $qty_diff = $quantity - $old_qty;
        // ddd($qty_diff);

        $user = Auth::user();
        $cart = $user->cart;
        // ddd($cart);

        $total_price = $item->price * $qty_diff;
        $cart_item = cart_item::updateOrInsert(
            [
                'cart_id' => $cart->id,
                'item_id' => $item->id,
            ],
            [
                'quantity' => DB::raw('quantity + ' . $qty_diff),
                'total_price' => DB::raw('total_price + ' . $total_price),
            ]
        );
        
        $cart->total_price += $total_price;
        $cart->save();
        // ddd($cart);

        $item->stock -= $qty_diff;
        $item->save();
        // ddd($item);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cart $cart)
    {
        //
    }
}

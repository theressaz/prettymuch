<?php

namespace App\Http\Controllers;

use App\Models\cart_item;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartItemController extends Controller
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
    public function show(cart_item $cart_item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(cart_item $cart_item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cart_item $cart_item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $user_id = Auth::user()->id;
        $user_cart = Auth::user()->cart;
        $cart_item_id = $request->get('cart_item_id');
        // ddd($user_cart);
        
        $cart_item = cart_item::where('cart_id', '=', $user_cart->id)
        ->where('item_id', '=', $cart_item_id)
        ->first();

        // ddd($cart_item);
        
        // update item stock
        $item = Item::where('id', '=', $cart_item_id)
            ->update([
                'stock' => DB::raw('stock + ' . $cart_item->quantity)
            ]);

        // update user cart total price
        $user_cart->total_price -= $cart_item->total_price;
        $user_cart->save();

        // delete cart_item
        $cart_item->delete();
        return back();
    }
}

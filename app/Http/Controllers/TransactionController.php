<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Transaction;
use App\Models\Transaction_item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        $user_cart =  Auth::user()->cart;
        $user_tr = new Transaction;
        $user_tr->user_id = $user_cart->user_id;
        $user_tr->total_price = $user_cart->total_price;

        $user_tr->payment_method = $request->payment_method;
        $user_tr->status = "waiting";
        // ddd($user_tr);
        $user_tr->save();

        $cart_items = $user_cart->cart_items->load('item');
        // ddd($cart_items[0]);

        foreach ($cart_items as $cart_item) {
            Transaction_item::create([
                "transaction_id" => $user_tr->id,
                "item_id" => $cart_item->item_id,
                "quantity" => $cart_item->quantity,
                "total_price" => $cart_item->total_price,
            ]);
        }
        $user_cart->delete();
        cart::create([
            "user_id" => Auth::user()->id,
            "total_price" => 0,
        ]);
        return back();
    }

    public function admin_list_all()
    {
        $tr_list = Transaction::all()->load('user');
        return view('admin.transaction', [
            'transactions' => $tr_list
        ]);
    }

    public function user_past_tr()
    {
        $user_tr = Auth::user()->transaction;
        $user_tr->load('transaction_items');
        // ddd($user_tr);
        return view('store.transaction', [
            'transactions' => $user_tr
        ]);
    }

    public function accept(Transaction $transaction)
    {
        $transaction->status = "accepted";
        $transaction->save();
        return back();
    }
    
    public function reject(Transaction $transaction)
    {
        $transaction->status = "rejected";
        $transaction->save();
        return back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Item_rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function latest_3()
    {
        $itemsData = Item::paginate(3)->load('item_ratings');
        return view('store.home', [
            'items' => $itemsData
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $itemsData = Item::all();
        return view('admin.item', [
            'items' => $itemsData
        ]);
    }

    public function about()
    {
        return view('store.about');
    }

    /**
     * Display a listing of the resource.
     */
    public function user_item()
    {
        $itemsData = Item::all()->load('item_ratings');
        // ddd($itemsData);
        return view('store.item', [
            'items' => $itemsData
        ]);
    }

    /**
     * Display a listing of the resource that match the filter input.
     */
    public function filtered(Request $request)
    {
        $type = $request->query('type');
        // ddd($type);
        $filter = $request->query('query');
        // ddd($filter);
        $filter = "%" . $filter . "%";
        $items = Item::where($type, "like", $filter)->get();
        // ddd($items);
        $items->load('item_ratings');
        return view('store.item', [
            'items' => $items,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: request verification
        $itemDTO = $request->collect();
        $itemData = new Item;

        $itemData->name = $itemDTO->get('name');
        $itemData->description = $itemDTO->get('description');
        $itemData->stock = $itemDTO->get('stock');
        $itemData->price = $itemDTO->get('price');
        $itemData->category = $itemDTO->get('category');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->storePublicly('item_image');
            $itemData->image_location = $path;
        }

        $ok = $itemData->save();

        if (!$ok) {
            return redirect(route('admin.item'), 500);
        }

        return redirect(route('admin.item'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $user = Auth::user();
        $item->load('item_ratings');
        $ave = $item->item_ratings()->avg('rating');
        // ddd($ave);
        $cart_item_qty = $user
                    ->cart
                    ->cart_items
                    ->where("item_id", "=", $item->id)
                    ->first();
        if ($cart_item_qty == null) {
            return view('store.item-detail', [
                'item' => $item,
                'in_cart_qty' => 0,
                'ave' => $ave,
            ]);
        }
        
        return view('store.item-detail', [
            'item' => $item,
            'in_cart_qty' => $cart_item_qty->quantity,
            'ave' => $ave,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        return view('admin.item-edit', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $reqDTO = $request->all();
        foreach ($reqDTO as $key => $value) {
            if ($value != null && $key != 'image') {
                $item->update([$key => $value]);
            }
        }

        if ($request->hasFile('image')) {
            Storage::delete($item->image_location);
            $success = $item->delete();
            if (!$success) {
                Log::withContext([
                    'item' => $item,
                ]);
            }

            $path = $request->file('image')->storePublicly('item_image');
            $item->image_location = $path;
        }
        $item->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        Storage::delete($item->image_location);
        $success = $item->delete();
        if (!$success) {
            Log::withContext([
                'item' => $item,
            ]);
        }
        return redirect(route('admin.item'));
    }

    public function add_review(Request $request, Item $item)
    {
        $rating = $request->get('rating');
        if ($rating < 1 || $rating > 5) {
            return back()->withErrors("invalid rating input");
        }
        $user = Auth::user();
        Item_rating::updateOrInsert(
            [
                'user_id' => $user->id,
                'item_id' => $item->id,
            ],
            [
                'user_id' => $user->id,
                'item_id' => $item->id,
                'rating' => $rating,
                'review' => $request->get('review'),
            ]
        );
        return back();
    }
}

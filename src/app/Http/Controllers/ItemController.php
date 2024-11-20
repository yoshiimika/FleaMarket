<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::where('is_sold', false)->latest()->paginate(50);
        return view('index', compact('items'));
    }

    public function myList()
    {
    if (auth()->check()) {
        $user = auth()->user();
        $favoriteItems = \DB::table('favorites')
            ->join('items', 'favorites.item_id', '=', 'items.id')
            ->where('favorites.user_id', auth()->id())
            ->select('items.*')
            ->get();
    } else {
        $favoriteItems = collect();
    }
    return view('mylist', compact('favoriteItems'));
    }

    public function show($item_id)
    {
        $item = Item::with(['brand', 'categories', 'comments.user'])->findOrFail($item_id);
        return view('item', compact('item'));
    }

    public function search(Request $request)
    {
        $query = Item::query();
        if ($request->has('keyword')) {
            $query->where('name', 'LIKE', '%' . $request->input('keyword') . '%');
        }
        $items = $query->paginate(50);
        return view('index', compact('items'));
    }
}

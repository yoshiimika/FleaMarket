<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $items = collect();
        $favoriteItems = collect();
        if ($tab === 'mylist') {
            if (auth()->check()) {
                $user = auth()->user();
                $items = \DB::table('favorites')
                    ->join('items', 'favorites.item_id', '=', 'items.id')
                    ->where('favorites.user_id', $user->id)
                    ->select('items.*')
                    ->get();
            }
        } else {
            $items = Item::where('is_sold', false)->latest()->paginate(50);
        }
        return view('index', compact('tab', 'items'));
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

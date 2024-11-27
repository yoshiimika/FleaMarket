<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 'recommend');
        $items = collect();
        if ($page === 'mylist') {
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
        return view('index', compact('page', 'items'));
    }

    public function show($item_id)
    {
        $item = Item::with(['brand', 'categories', 'comments.user'])->findOrFail($item_id);
        return view('item', compact('item'));
    }

    public function search(Request $request)
    {
        $page = 'search';
        $query = Item::query();
        if ($request->has('keyword')) {
            $query->where('name', 'LIKE', '%' . $request->input('keyword') . '%');
        }
        $items = $query->paginate(50);
        return view('index', compact('page', 'items'));
    }
}

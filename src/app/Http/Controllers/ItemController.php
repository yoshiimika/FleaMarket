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
        $keyword = $request->query('keyword', session('keyword', ''));
        if ($request->has('keyword')) {
            session(['keyword' => $request->input('keyword')]);
        }
        $items = collect();
        if ($page === 'mylist') {
            if (auth()->check()) {
                $user = auth()->user();
                $items = $user->favoriteItems()
                    ->when($keyword, function ($query) use ($keyword) {
                        return $query->where('name', 'LIKE', '%' . $keyword . '%');
                    })
                    ->get();
            }
        } else {
            $items = Item::when(auth()->check(), function ($query) {
                    $userId = auth()->id();
                    return $query->where('user_id', '!=', $userId);
                })
                ->when($keyword, function ($query) use ($keyword) {
                    return $query->where('name', 'LIKE', '%' . $keyword . '%');
                })
                ->latest()
                ->paginate(50);
        }
        return view('index', compact('page', 'items', 'keyword'));
    }

    public function show($item_id)
    {
        $item = Item::with(['brand', 'categories', 'comments.user'])->findOrFail($item_id);
        return view('item', compact('item'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword', session('keyword', ''));
        session(['keyword' => $keyword]);
        $query = Item::query();
        if ($keyword) {
            $query->where('name', 'LIKE', '%' . $keyword . '%');
        }
        if (auth()->check()) {
            $query->where('user_id', '!=', auth()->id());
        }
        $items = $query->paginate(50);
        return redirect()->route('home', ['keyword' => $keyword]);
    }
}
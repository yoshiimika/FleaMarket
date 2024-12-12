<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->query('page', 'recommend');
        $keyword = $this->getKeyword($request);
        $items = collect();
        if ($page === 'mylist') {
            $items = $this->getFavoriteItems($keyword);
        } else {
            $items = $this->getRecommendedItems($keyword);
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
        $keyword = $this->getKeyword($request);
        return redirect()->route('home', ['keyword' => $keyword]);
    }

    private function getKeyword(Request $request)
    {
        $keyword = $request->query('keyword', session('keyword', ''));
        if ($request->has('keyword')) {
            session(['keyword' => $keyword]);
        }
        return $keyword;
    }

    private function getFavoriteItems($keyword)
    {
        if (!auth()->check()) {
            return collect();
        }

        return auth()->user()->favoriteItems()
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('name', 'LIKE', '%' . $keyword . '%');
            })
            ->get();
    }

    private function getRecommendedItems($keyword)
    {
        return Item::when(auth()->check(), function ($query) {
                return $query->where('user_id', '!=', auth()->id());
            })
            ->when($keyword, function ($query) use ($keyword) {
                return $query->where('name', 'LIKE', '%' . $keyword . '%');
            })
            ->latest()
            ->get();
    }
}
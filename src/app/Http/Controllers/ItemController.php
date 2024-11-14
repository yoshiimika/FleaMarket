<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 商品一覧表示
    public function index()
    {
        $products = Product::latest()->paginate(10); // 新着順で商品を10件ずつ表示
        return view('index', compact('products'));
    }

    // マイリスト（お気に入り一覧）
    public function myList()
    {
        $user = auth()->user();
        $favorites = $user->favorites()->with('product')->get(); // ユーザーのお気に入り商品を取得
        return view('mylist', compact('favorites'));
    }

    // 商品詳細表示
    public function show($item_id)
    {
        $product = Product::with(['images', 'comments.user'])->findOrFail($item_id); // 商品情報と画像、コメントを取得
        return view('item', compact('product'));
    }

    // 商品検索機能
    public function search(Request $request)
    {
        $query = Product::query();
        if ($request->has('keyword')) {
            $query->where('name', 'LIKE', '%' . $request->input('keyword') . '%');
        }
        $products = $query->paginate(10); // 検索結果を10件ずつ表示
        return view('index', compact('products'));
    }
}

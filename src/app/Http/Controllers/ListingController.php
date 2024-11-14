<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    // 商品出品ページ表示
    public function create()
    {
        return view('sell.sell'); // 出品ページの表示
    }

    // 商品出品処理
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'img_url' => 'nullable|image|max:2048',
            'condition' => 'required|string|in:良好,やや傷あり,状態が悪い',
        ]);
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->user_id = auth()->id(); // ログインユーザーを出品者として設定
        // 画像がアップロードされた場合、storageに保存し、パスを img_url フィールドに保存
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('product_images', 'public'); // storage/app/public/product_imagesに保存
            $product->img_url = Storage::url($path); // img_url に画像のURLを保存
        }
        $product->save();
        return redirect()->route('profile.sold')->with('success', '商品を出品しました');
    }

    // 商品編集ページ表示
    public function edit($item_id)
    {
        $product = Product::findOrFail($item_id);
        return view('sell.edit', compact('product'));
    }

    // 商品更新処理
    public function update(Request $request, $item_id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'img_url' => 'nullable|image|max:2048',
        ]);
        $product = Product::findOrFail($item_id);
        // 商品情報の更新
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        // 新しい画像がアップロードされた場合、古い画像を削除して新しい画像を保存
        if ($request->hasFile('image')) {
            if ($product->img_url) {
                // 古い画像ファイルを削除
                Storage::disk('public')->delete(str_replace('/storage/', '', $product->img_url));
            }
            $path = $request->file('image')->store('product_images', 'public');
            $product->img_url = Storage::url($path);
        }
        $product->save();
        return redirect()->route('profile.sold')->with('success', '商品情報を更新しました');
    }

    // 商品削除処理
    public function destroy($item_id)
    {
        $product = Product::findOrFail($item_id);
        // 商品に関連する画像ファイルが存在する場合、削除
        if ($product->img_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $product->img_url));
        }
        $product->delete();
        return redirect()->route('profile.sold')->with('success', '商品を削除しました');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all(['id', 'name']);
        return view('sell.sell', compact('categories', 'brands'));
    }

    public function getBrands($categoryIds)
    {
        $ids = explode(',', $categoryIds);
        $brands = Brand::whereIn('category_id', $ids)->get(['id', 'name']);
        return response()->json($brands);
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();
        $item = new Item();
        $item->user_id = auth()->id();
        $item->brand_id = $validated['brand_id'] ?? null;
        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->price = $validated['price'];
        $item->condition = $validated['condition'];
        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('item-images', $request->file('image'));
            $item->img_url = 'storage/' . $path;
        }
        $item->save();
        $item->categories()->sync($validated['category_ids']);
        return redirect()->route('home')->with('success', '商品を出品しました');
    }

    // 商品編集ページ表示
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('sell.edit', compact('item'));
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
        $item = Item::findOrFail($item_id);
        // 商品情報の更新
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        // 新しい画像がアップロードされた場合、古い画像を削除して新しい画像を保存
        if ($request->hasFile('image')) {
            if ($item->img_url) {
                // 古い画像ファイルを削除
                Storage::disk('public')->delete(str_replace('/storage/', '', $item->img_url));
            }
            $path = $request->file('image')->store('item_images', 'public');
            $item->img_url = Storage::url($path);
        }
        $item->save();
        return redirect()->route('profile.sold')->with('success', '商品情報を更新しました');
    }

    // 商品削除処理
    public function destroy($item_id)
    {
        $item = item::findOrFail($item_id);
        // 商品に関連する画像ファイルが存在する場合、削除
        if ($item->img_url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $item->img_url));
        }
        $item->delete();
        return redirect()->route('profile.sold')->with('success', '商品を削除しました');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Item;
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
}

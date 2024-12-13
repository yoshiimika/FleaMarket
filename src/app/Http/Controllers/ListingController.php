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
        $brands = Brand::whereIn('category_id', explode(',', $categoryIds))
            ->get(['id', 'name']);
        return response()->json($brands);
    }

    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();
        $item = auth()->user()->items()->create([
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'color' => $validated['color'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'condition' => $validated['condition'],
            'img_url' => $this->storeImage($request),
        ]);
        $item->categories()->sync($validated['category_ids']);
        return redirect()->route('home', ['page' => 'mylist'])
            ->with('success', '商品を出品しました');
    }

    private function storeImage($request)
    {
        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->put('item-images', $request->file('image'));
            return 'storage/' . $path;
        }
        return null;
    }
}

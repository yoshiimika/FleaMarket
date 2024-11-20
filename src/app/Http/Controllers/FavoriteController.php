<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // いいね機能（お気に入り登録/解除）
    public function toggle($item_id)
    {
        $user = auth()->user();
        $item = Item::findOrFail($item_id);
        if ($user->favorites()->where('item_id', $item_id)->exists()) {
            $user->favorites()->detach($item_id);
            return back()->with('success', 'お気に入りを解除しました');
        } else {
            $user->favorites()->attach($item_id);
            return back()->with('success', 'お気に入りに追加しました');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle($item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        if ($item->is_favorite) {
            $user->favoriteItems()->detach($item_id);
        } else {
            $user->favoriteItems()->attach($item_id);
        }
        return redirect()->back();
    }
}

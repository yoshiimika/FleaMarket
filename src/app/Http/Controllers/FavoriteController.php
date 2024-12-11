<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle($item_id)
    {
        $user = Auth::user();
        $isFavorite = $user->favoriteItems()->where('item_id', $item_id)->exists();
        if ($isFavorite) {
            $user->favoriteItems()->detach($item_id);
        } else {
            $user->favoriteItems()->attach($item_id);
        }
        return redirect()->back();
    }
}

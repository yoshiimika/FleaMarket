<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showProfile(Request $request)
    {
        $user = auth()->user();
        $page = $request->query('page', 'sell');
        if ($page === 'sell') {
            $items = Item::where('user_id', $user->id)->get();
        } elseif ($page === 'buy') {
            $items = $user->purchasedItems ?? collect();
        } else {
            $items = collect();
        }
        return view('mypage.mypage', compact('user', 'page', 'items'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        if ($request->hasFile('avatar')) {
            if ($user->img_url) {
                Storage::delete($user->img_url);
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->img_url = $avatarPath;
        }
        $user->name = $request->name;
        $user->zip = $request->zip;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();
        return redirect()->route('profile')->with('success', 'プロフィールを更新しました。');
    }
}

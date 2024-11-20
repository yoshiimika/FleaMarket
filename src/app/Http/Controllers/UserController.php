<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showProfile()
    {
        $user = auth()->user();
        return view('mypage.mypage', compact('user'));
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

    // 購入した商品一覧
    public function showPurchasedItems()
    {
        $user = auth()->user();
        $purchasedItems = $user->purchases()->with('product')->get();
        return view('mypage.buy', compact('purchasedItems'));
    }

    // 出品した商品一覧
    public function showSoldItems()
    {
        $user = auth()->user();
        $soldItems = $user->products;
        return view('mypage.sell', compact('soldItems'));
    }
}

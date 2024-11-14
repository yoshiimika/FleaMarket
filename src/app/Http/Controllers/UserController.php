<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // プロフィールページ
    public function showProfile()
    {
        $user = auth()->user();
        return view('mypage.mypage', compact('user'));
    }

    // プロフィール編集ページ
    public function editProfile()
    {
        $user = auth()->user();
        return view('mypage.profile', compact('user'));
    }

    // プロフィール更新処理
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);
        $user = auth()->user();
        $user->fill($request->only('name', 'email'));
        $user->save();
        return redirect()->route('profile')->with('success', 'プロフィールを更新しました');
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

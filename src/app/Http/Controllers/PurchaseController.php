<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    // 購入画面表示
    public function showPurchaseForm($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchase.purchase', compact('item'));
    }

    // 購入処理
    public function purchase(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $purchase = new Purchase();
        $purchase->user_id = $user->id;
        $purchase->item_id = $item->id;
        $purchase->amount = $item->price;
        $purchase->payment_method = $request->input('payment_method');
        $purchase->save();
        return redirect()->route('profile.purchased')->with('success', '購入が完了しました');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // 住所変更ページ表示
    public function editAddress($item_id)
    {
        $user = auth()->user();
        $address = $user->address ?? new Address();
        return view('purchase.address', compact('address', 'item_id'));
    }

    // 住所変更処理
    public function updateAddress(Request $request, $item_id)
    {
        $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'zip' => 'required|string|max:20',
        ]);
        $user = auth()->user();
        $address = $user->address ?? new Address();
        $address->fill($request->all());
        $address->user_id = $user->id;
        $address->save();
        return redirect()->route('purchase.show', ['item_id' => $item_id])->with('success', '住所を更新しました');
    }
}

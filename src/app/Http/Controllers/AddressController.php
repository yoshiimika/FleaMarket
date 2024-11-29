<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Address;

class AddressController extends Controller
{
    public function editAddress($item_id)
    {
        $user = auth()->user();
        $address = $user->address ?? new Address();
        return view('purchase.address', compact('address', 'item_id'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        $user = auth()->user();
        $address = $user->address ?? new Address();
        $address->fill($request->all());
        $address->user_id = $user->id;
        $address->save();
        return redirect()->route('purchase.show', compact('item_id'))->with('success', '住所を更新しました');
    }
}

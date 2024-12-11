<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    public function editShoppingAddress($item_id)
    {
        $user = auth()->user();
        $address = (object) [
            'zip' => session('shopping_zip', $user->address->zip),
            'address' => session('shopping_address', $user->address->address),
            'building' => session('shopping_building', $user->address->building),
        ];
        return view('purchase.address', compact('address', 'item_id'));
    }

    public function updateShoppingAddress(AddressRequest $request, $item_id)
    {
        session([
            'shopping_zip' => $request->input('zip'),
            'shopping_address' => $request->input('address'),
            'shopping_building' => $request->input('building'),
        ]);
        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('success', '送付先住所が更新されました');
    }
}

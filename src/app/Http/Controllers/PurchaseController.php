<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Stripe\StripeClient;

class PurchaseController extends Controller
{
    public function showPurchaseForm($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $address = [
            'zip' => session('shopping_zip', $user->address->zip),
            'address' => session('shopping_address', $user->address->address),
            'building' => session('shopping_building', $user->address->building),
        ];
        return view('purchase.purchase', compact('item', 'address'));
    }

    public function purchase(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $amount = intval($item->price);
        $paymentMethod = $request->input('payment_method');

        if (app()->environment('testing')) {
            return $this->success($item_id);
        }

        $stripe = new StripeClient(env('STRIPE_SECRET'));
        $session = $stripe->checkout->sessions->create([
            'payment_method_types' => [$paymentMethod === 'card' ? 'card' : 'konbini'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item_id]),
            'cancel_url' => route('purchase.cancel', ['item_id' => $item_id]),
        ]);
        return redirect($session->url);
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();
        $amount = intval($item->price);
        $purchase = $user->purchases()->create([
            'item_id' => $item->id,
            'amount' => $amount,
            'shopping_zip' => session('shopping_zip', $user->address->zip),
            'shopping_address' => session('shopping_address', $user->address->address),
            'shopping_building' => session('shopping_building', $user->address->building),
            'payment_method' => session('payment_method', 'card'),
        ]);
        session()->forget(['shopping_zip', 'shopping_address', 'shopping_building', 'payment_method']);
        return redirect()->route('home', ['page' => 'mylist'])
            ->with('success', '商品の購入が完了しました');
    }

    public function cancel($item_id)
    {
        return redirect()->route('home', ['page' => 'mylist'])
            ->with('error', '商品の購入を中止しました');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class PublicOrderController extends Controller
{
    //
    public function show($token)
    {
        $shop = Shop::where('token', $token)->firstOrFail();

        $products = Product::where('user_id', $shop->user_id)->get();

        return view('orders.public', compact('shop', 'products'));
    }

    public function submit(Request $request, $token)
    {
        $shop = Shop::where('token', $token)->firstOrFail();

        $order = Order::create([
            'user_id' => $shop->user_id,
            'shop_id' => $shop->id,
            'date' => now(),
            'status' => 'pending',
            'source' => 'shop',
            'public_token' => Str::random(32),
        ]);

        foreach ($request->qty as $pid => $qty) {

            if ($qty > 0) {

                $product = Product::find($pid);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $pid,
                    'ordered_qty' => $qty,
                    'rate_snapshot' => $product->current_rate
                ]);
            }
        }

        return redirect()->back()
            ->with('success', 'Order sent to vendor');
    }

    public function publicInvoice($token)
    {
        $order = Order::where('public_token', $token)
            ->with('items.product', 'shop', 'user')
            ->firstOrFail();

        return view('orders.public-invoice', compact('order'));
    }
}

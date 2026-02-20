<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Support\Str;
class OrderController extends Controller
{

    // Vendor order

    public function publicInvoice($token)
    {
        $order = Order::where('public_token',$token)
            ->with('items.product','shop','user')
            ->firstOrFail();
        $publicEnable = true;
        return view('orders.invoice',compact('order','publicEnable'));
    }

    public function index()
    {
        $orders = Order::with('shop')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }


    public function vendorCreate()
    {
        $shops = Shop::where('user_id', Auth::id())->get();
        $products = Product::where('user_id', Auth::id())->get();

        return view('orders.vendor', compact('shops', 'products'));
    }


    // Vendor order save
    public function vendorStore(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id'
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'shop_id' => $request->shop_id,
            'date' => now(),
            'status' => 'pending',
            'source' => 'vendor',
            'public_token' => Str::random(32),
        ]);

        if ($request->has('qty')) {

            foreach ($request->qty as $pid => $qty) {

                if ($qty > 0) {

                    $product = Product::where('id', $pid)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $pid,
                        'ordered_qty' => $qty,
                        'rate_snapshot' => $product->current_rate
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')
            ->with('success', 'Vendor order saved');
    }

    public function deliver(Order $order)
    {
        abort_if($order->user_id != auth()->id(), 403);

        $order->load('items.product', 'shop');

        return view('orders.deliver', compact('order'));
    }

    public function saveDelivery(Request $request, Order $order)
    {
        abort_if($order->user_id != auth()->id(), 403);

        foreach ($request->delivered as $id => $qty) {

            $item = $order->items->where('id', $id)->first();

            if ($item) {
                $item->delivered_qty = $qty;
                $item->save();
            }
        }

        $order->status = 'delivered';
        $order->save();

        return redirect()->route('dashboard')
            ->with('success', 'Delivery saved');
    }
    public function invoice(Order $order)
    {
        abort_if($order->user_id != auth()->id(), 403);

        $order->load('items.product', 'shop');
        $publicEnable = false;
        return view('orders.invoice', compact('order','publicEnable'));
    }
}

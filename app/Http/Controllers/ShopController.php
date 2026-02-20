<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = Shop::where('user_id', Auth::id())->latest()->get();
        return view('shops.index', compact('shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shops.form', [
            'shop' => new Shop()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Shop::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'mobile' => $request->mobile,
            'token' => Str::random(20)
        ]);

        return redirect()->route('shops.index')
            ->with('success', 'Shop added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        abort_if($shop->user_id != Auth::id(), 403);

        return view('shops.form', [
            'shop' => $shop
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shop $shop)
    {
        abort_if($shop->user_id != Auth::id(), 403);

        $shop->update($request->only('name', 'mobile'));

        return redirect()->route('shops.index')
            ->with('success', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        abort_if($shop->user_id != Auth::id(), 403);
        $shop->delete();

        return back()->with('success', 'Deleted');
    }
}

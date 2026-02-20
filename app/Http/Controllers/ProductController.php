<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('user_id', Auth::id())->latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          return view('products.form',[
                'product'=>new Product()
            ]);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'current_rate' => $request->current_rate,
            'unit' => $request->unit,
            'box_size' => $request->box_size
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Product added');
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
    public function edit(Product $product)
    {
        $this->authorizeProduct($product);

        return view('products.form', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorizeProduct($product);

        $product->update($request->only(
            'name',
            'current_rate',
            'unit',
            'box_size'
        ));

        return redirect()->route('products.index')
            ->with('success', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorizeProduct($product);
        $product->delete();

        return back()->with('success', 'Deleted');
    }
    private function authorizeProduct($product)
    {
        if ($product->user_id != Auth::id()) {
            abort(403);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:purchase_products|view_purchases', ['only' => ['profile', 'purchases', 'purchase']]);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('customer.profile', compact('user'));
    }

    public function purchases()
    {
        if (!Auth::user()->can('view_purchases')) {
            return redirect()->back()->with('error', 'You do not have permission to view purchases.');
        }

        $purchases = Auth::user()->purchases()->with('product')->get();
        return view('customer.purchases', compact('purchases'));
    }

    public function purchase(Request $request)
    {
        $user = Auth::user();

        if (!$user->can('purchase_products')) {
            return redirect()->back()->with('error', 'You do not have permission to purchase products.');
        }

        $product = Product::findOrFail($request->product_id);

        if ($user->credit < $product->price || $product->in_stock <= 0) {
            return redirect()->back()->with('error', 'Insufficient credit or product out of stock.');
        }

        $user->credit -= $product->price;
        $product->in_stock -= 1;
        $user->save();
        $product->save();

        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'amount' => $product->price,
        ]);

        return redirect()->route('customer.purchases')->with('success', 'Purchase completed!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                $total += $product->price * $quantity;
            }
        }

        return view('cart.index', compact('products', 'total'));
    }

    public function add(Product $product, Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]++;
        } else {
            $cart[$product->id] = 1;
        }

        session()->put('cart', $cart);
        
        return redirect()->route('cart.index')
            ->with('success', 'Product added to cart!');
    }

    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            unset($cart[$product->id]);
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Product removed from cart!');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')
            ->with('success', 'Cart cleared successfully!');
    }

    public function increase(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id])) {
            $cart[$product->id]++;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function decrease(Product $product)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$product->id]) && $cart[$product->id] > 1) {
            $cart[$product->id]--;
            session()->put('cart', $cart);
        }

        return redirect()->route('cart.index');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $products = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
                $total += $product->price * $quantity;
            }
        }

        return view('cart.checkout', compact('products', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart', []);
        
        // Validate cart exists
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Validate form inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string|max:500',
            // Add more fields as needed
        ]);

        // Process the order (you'll need to implement this)
        try {
            // 1. Create order record
            $order = auth()->user()->orders()->create([
                'total' => $this->calculateCartTotal($cart),
                'status' => 'pending',
                'shipping_address' => $validated['address'],
                // Add other order fields
            ]);
            
            // 2. Add order items
            foreach ($cart as $productId => $quantity) {
                $product = Product::find($productId);
                $order->items()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
                
                // 3. Update product stock
                $product->decrement('amount', $quantity);
            }
            
            // 4. Clear cart
            session()->forget('cart');
            
            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully!');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Checkout failed: ' . $e->getMessage());
        }
    }

    private function calculateCartTotal($cart)
    {
        $total = 0;
        foreach ($cart as $productId => $quantity) {
            $product = Product::find($productId);
            if ($product) {
                $total += $product->price * $quantity;
            }
        }
        return $total;
    }
}

<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller {

    use ValidatesRequests;

    public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

    public function list(Request $request) {
        $query = Product::select("*");

        $query->when($request->keywords,
            fn($q) => $q->where("name", "like", "%$request->keywords%"));

        $query->when($request->min_price,
            fn($q) => $q->where("price", ">=", $request->min_price));

        $query->when($request->max_price,
            fn($q) => $q->where("price", "<=", $request->max_price));

        $query->when($request->order_by,
            fn($q) => $q->orderBy($request->order_by, $request->order_direction ?? "ASC"));

        $products = $query->get();
        return view('products.list', compact('products'));
    }

    public function edit(Request $request, Product $product = null) {
        $product = $product ?? new Product();
        return view('products.edit', compact('product'));
    }

    public function save(Request $request, Product $product = null) {
        $this->validate($request, [
            'code' => ['required', 'string', 'max:32'],
            'name' => ['required', 'string', 'max:128'],
            'model' => ['required', 'string', 'max:256'],
            'description' => ['required', 'string', 'max:1024'],
            'price' => ['required', 'numeric'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $product = $product ?? new Product();
        $product->fill($request->all());
        $product->save();

        return redirect()->route('products.list');
    }

    public function delete(Request $request, Product $product) {
        if (!auth()->user()->hasPermissionTo('delete_products')) abort(401);
        $product->delete();
        return redirect()->route('products.list');
    }

    public function confirm(Product $product) {
        return view('products.buy_confirm', compact('product'));
    }

    public function buy(Request $request, Product $product) {
		$user = Auth::user();
	
		if (!$user->hasPermissionTo('buy_products')) abort(401);
	
		$quantity = (int) $request->input('quantity', 1);
		$total_price = $product->price * $quantity;
	
		if ($user->credit < $total_price) {
			return view('products.insufficient_credit', compact('product'));
		}
	
		if ($product->quantity < $quantity) {
			return redirect()->back()->withErrors("Not enough stock.");
		}
	
		// Execute Purchase
		$user->credit -= $total_price;
		$user->save();
	
		$product->quantity -= $quantity;
		$product->save();
	
		// سجل الكمية في جدول العلاقة
		$user->products()->attach($product->id, ['quantity' => $quantity]);
	
		return redirect()->route('products.list')->with('success', 'Product purchased successfully.');
	}
	
    public function myPurchases() {
        $user = auth()->user();
        $products = $user->products;
        return view('products.purchases', compact('products'));
    }
}

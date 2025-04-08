<?php
namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Purchase;

class ProductsController extends Controller {

	use ValidatesRequests;

	public function __construct()
    {
        $this->middleware('auth')->except(['list']);
    }

	public function list(Request $request) 
	{
	    try {
	        $validated = $request->validate([
	            'keywords' => 'nullable|string|max:100',
	            'min_price' => 'nullable|numeric|min:0',
	            'max_price' => 'nullable|numeric|min:0',
	            'order_by' => 'nullable|in:name,price,created_at',
	            'order_direction' => 'nullable|in:ASC,DESC'
	        ]);

	        // Build query with better performance
	        $query = Product::select([
	            'id', 'name', 'code', 'model', 
	            'price', 'description', 'photo', 
	            'amount', 'created_at'
	        ]);

	        // Apply search filters
	        $query->when($validated['keywords'] ?? null, function ($q) use ($validated) {
	            $keywords = Str::lower($validated['keywords']);
	            $q->whereRaw('LOWER(name) LIKE ?', ["%{$keywords}%"])
	              ->orWhereRaw('LOWER(code) LIKE ?', ["%{$keywords}%"]);
	        });

	        // Apply price filters
	        $query->when($validated['min_price'] ?? null, function ($q) use ($validated) {
	            return $q->where('price', '>=', $validated['min_price']);
	        });

	        $query->when($validated['max_price'] ?? null, function ($q) use ($validated) {
	            return $q->where('price', '<=', $validated['max_price']);
	        });

	        // Apply sorting
	        $orderBy = $validated['order_by'] ?? 'name';
	        $orderDirection = $validated['order_direction'] ?? 'ASC';
	        $query->orderBy($orderBy, $orderDirection);

	        // Get paginated results
	        $products = $query->paginate(12)->withQueryString();

	        return view('products.list', compact('products'));
	    } catch (\Exception $e) {
	        Log::error('Product listing failed: ' . $e->getMessage());
	        return back()->with('error', 'Failed to load products. Please try again.');
	    }
	}

	public function edit(Request $request, Product $product = null) {

		if(!auth()->user()) return redirect('/');

		$product = $product??new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, Product $product = null) {

		$this->validate($request, [
	        'code' => ['required', 'string', 'max:32'],
	        'name' => ['required', 'string', 'max:128'],
	        'model' => ['required', 'string', 'max:256'],
	        'description' => ['required', 'string', 'max:1024'],
	        'price' => ['required', 'numeric'],
	    ]);

		$product = $product??new Product();
		$product->fill($request->all());
		$product->save();

		return redirect()->route('products_list');
	}

	public function delete(Request $request, Product $product) {

		if(!auth()->user()->hasPermissionTo('delete_products')) abort(401);

		$product->delete();

		return redirect()->route('products_list');
	}

	/**
	 * Add product to cart
	 */
	public function addToCart(Request $request, Product $product)
	{
	    try {
	        // Validate product availability
	        if ($product->amount <= 0) {
	            return back()->with('error', 'Product is out of stock');
	        }

	        // Get or create cart session
	        $cart = $request->session()->get('cart', []);
	        
	        // Add/update product in cart
	        if (isset($cart[$product->id])) {
	            if ($cart[$product->id]['quantity'] >= $product->amount) {
	                return back()->with('error', 'Cannot add more than available stock');
	            }
	            $cart[$product->id]['quantity']++;
	        } else {
	            $cart[$product->id] = [
	                'name' => $product->name,
	                'price' => $product->price,
	                'quantity' => 1
	            ];
	        }

	        // Save cart back to session
	        $request->session()->put('cart', $cart);

	        return back()->with('success', 'Product added to cart');
	    } catch (\Exception $e) {
	        \Log::error('Add to cart failed: ' . $e->getMessage());
	        return back()->with('error', 'Failed to add product to cart');
	    }
	}

	/**
	 * Update product stock amount
	 */
	public function updateStock(Request $request, Product $product)
	{
	    try {
	        if (!auth()->user()->hasAnyRole(['Admin', 'Employee'])) {
	            abort(403, 'Unauthorized action.');
	        }

	        $validated = $request->validate([
	            'amount' => 'required|integer|min:0|max:999999'
	        ]);

	        DB::beginTransaction();
	        try {
	            $product->amount = $validated['amount'];
	            $product->save();

	            DB::commit();
	            return back()->with('success', 'Stock updated successfully!');
	        } catch (\Exception $e) {
	            DB::rollBack();
	            throw $e;
	        }
	    } catch (\Exception $e) {
	        Log::error('Stock update failed: ' . $e->getMessage());
	        return back()->with('error', 'Failed to update stock.');
	    }
	}

	/**
	 * Purchase product
	 */
	public function purchase(Request $request, Product $product)
	{
	    try {
	        if (!auth()->user()->can('purchase_products')) {
	            abort(403, 'Unauthorized action.');
	        }

	        if ($product->amount <= 0) {
	            return back()->with('error', 'Product is out of stock.');
	        }

	        DB::beginTransaction();
	        try {
	            // Create purchase record
	            $purchase = Purchase::create([
	                'user_id' => auth()->id(),
	                'product_id' => $product->id,
	                'price' => $product->price,
	                'quantity' => 1,
	                'purchase_date' => now()
	            ]);

	            // Reduce original product stock
	            $product->decrement('amount');

	            DB::commit();
	            return back()->with('success', 'Product purchased successfully!');
	        } catch (\Exception $e) {
	            DB::rollBack();
	            throw $e;
	        }
	    } catch (\Exception $e) {
	        Log::error('Purchase failed: ' . $e->getMessage());
	        return back()->with('error', 'Purchase failed. Please try again.');
	    }
	}
}
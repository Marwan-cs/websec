<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:add_credit|edit_credit|delete_credit|add_stock|edit_stock|delete_stock', 
            ['only' => ['products', 'addProduct', 'editProduct', 'deleteProduct', 'customers', 'addCredit', 'editCredit', 'deleteCredit']]);
    }

    public function products()
    {
        $products = Product::all();
        return view('employee.products', compact('products'));
    }

    public function addProduct(Request $request)
    {
        if (!Auth::user()->can('add_stock')) {
            return redirect()->back()->with('error', 'You do not have permission to add stock.');
        }

        try {
            $validated = $request->validate([
                'code' => 'required|string|max:64|unique:products',
                'name' => 'required|string|max:256',
                'price' => 'required|numeric|min:0',
                'model' => 'required|string|max:128',
                'in_stock' => 'required|integer|min:0',
                'description' => 'nullable|string|max:1000',
                'photo' => 'nullable|image|max:2048|mimes:jpeg,png,jpg',
            ]);

            if ($request->hasFile('photo')) {
                $validated['photo'] = $request->file('photo')->store('products', 'public');
            }

            Product::create($validated);
            return redirect()->route('employee.products')->with('success', 'Product added successfully!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function editProduct(Request $request, $id)
    {
        if (!Auth::user()->can('edit_stock')) {
            return redirect()->back()->with('error', 'You do not have permission to edit stock.');
        }

        try {
            $product = Product::findOrFail($id);
            
            $validated = $request->validate([
                'code' => 'required|string|max:64|unique:products,code,' . $id,
                'name' => 'required|string|max:256',
                'price' => 'required|numeric|min:0',
                'model' => 'required|string|max:128',
                'in_stock' => 'required|integer|min:0',
                'description' => 'nullable|string|max:1000',
            ]);

            $product->update($validated);
            return redirect()->route('employee.products')->with('success', 'Product updated successfully!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function deleteProduct($id)
    {
        if (!Auth::user()->can('delete_stock')) {
            return redirect()->back()->with('error', 'You do not have permission to delete stock.');
        }

        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('employee.products')->with('success', 'Product deleted successfully!');
    }

    public function customers()
    {
        $customers = User::role('Customer')->get();
        return view('employee.customers', compact('customers'));
    }

    public function addCredit(Request $request, $id)
    {
        if (!Auth::user()->can('add_credit')) {
            return redirect()->back()->with('error', 'You do not have permission to add credit.');
        }

        try {
            $validated = $request->validate([
                'credit' => 'required|numeric|min:0.01|max:1000000'
            ]);

            $customer = User::findOrFail($id);
            $customer->credit += $validated['credit'];
            $customer->save();

            return redirect()->route('employee.customers')->with('success', 'Credit added successfully!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function editCredit(Request $request, $id)
    {
        if (!Auth::user()->can('edit_credit')) {
            return redirect()->back()->with('error', 'You do not have permission to edit credit.');
        }

        try {
            $validated = $request->validate([
                'credit' => 'required|numeric|min:0|max:1000000'
            ]);

            $customer = User::findOrFail($id);
            $customer->credit = $validated['credit'];
            $customer->save();

            return redirect()->route('employee.customers')->with('success', 'Credit updated successfully!');
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function deleteCredit($id)
    {
        if (!Auth::user()->can('delete_credit')) {
            return redirect()->back()->with('error', 'You do not have permission to delete credit.');
        }

        $customer = User::findOrFail($id);
        $customer->credit = 0;
        $customer->save();

        return redirect()->route('employee.customers')->with('success', 'Credit reset to zero successfully!');
    }
}
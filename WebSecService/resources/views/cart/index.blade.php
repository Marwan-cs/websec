@extends('layouts.master')
@section('title', 'Shopping Cart')
@section('content')

<div class="row mt-2">
    <div class="col col-10">
        <h1>Shopping Cart</h1>
    </div>
    <div class="col col-2">
        <a href="{{ route('cart.index') }}" class="btn btn-primary form-control">Continue Shopping</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(empty($products))
    <div class="alert alert-info mt-3">
        Your cart is empty
    </div>
@else
    <form action="{{ route('cart.clear') }}" method="POST" class="mb-3">
        @csrf
        <button type="submit" class="btn btn-warning">Clear Cart</button>
    </form>
    <div class="table-responsive mt-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $item)
                <tr>
                    <td>
                        <img src="{{ asset('images/' . $item['product']->photo) }}" 
                             alt="{{ $item['product']->name }}" 
                             width="50" 
                             class="img-thumbnail">
                        {{ $item['product']->name }}
                    </td>
                    <td>${{ number_format($item['product']->price, 2) }}</td>
                    <td>
                        <div class="input-group" style="width: 120px;">
                            <form action="{{ route('cart.decrease', $item['product']->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">-</button>
                            </form>
                            <input type="text" class="form-control form-control-sm text-center" 
                                   value="{{ $item['quantity'] }}" readonly>
                            <form action="{{ route('cart.increase', $item['product']->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                            </form>
                        </div>
                    </td>
                    <td>${{ number_format($item['product']->price * $item['quantity'], 2) }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $item['product']->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong>${{ number_format($total, 2) }}</strong></td>
                    <td>
                        <a href="{{ route('checkout') }}" class="btn btn-success">Proceed to Checkout</a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endif

@endsection
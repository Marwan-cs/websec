@extends('layouts.master')
@section('title', 'Checkout')
@section('content')

<div class="row mt-2">
    <div class="col col-12">
        <h1>Checkout</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Order Summary</h3>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $item)
                        <tr>
                            <td>{{ $item['product']->name }}</td>
                            <td>${{ number_format($item['product']->price, 2) }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>${{ number_format($item['product']->price * $item['quantity'], 2) }}</td>
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
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3>Payment Details</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ auth()->user() ? auth()->user()->name : '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="{{ auth()->user() ? auth()->user()->email : '' }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address" class="form-label">Shipping Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>
                    
                    <!-- Payment method selection -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="cash" value="cash" checked>
                            <label class="form-check-label" for="cash">
                                Cash on Delivery
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="card" value="card">
                            <label class="form-check-label" for="card">
                                Credit Card
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Place Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
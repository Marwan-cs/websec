@extends('layouts.master')

@section('title', 'Confirm Purchase')

@section('content')
<div class="container mt-4">
    <h2>Confirm Purchase</h2>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <div class="card mb-3">
        <div class="card-header">{{ $product->name }}</div>
        <div class="card-body">
            <p><strong>Model:</strong> {{ $product->model }}</p>
            <p><strong>Code:</strong> {{ $product->code }}</p>
            <p><strong>Available:</strong> {{ $product->quantity }}</p>
            <p><strong>Price:</strong> {{ $product->price }} EGP</p>
            <p><strong>Description:</strong> {{ $product->description }}</p>

            <form method="POST" action="{{ route('products_buy', $product->id) }}">
                @csrf
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity to Buy</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->quantity }}" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-warning">Confirm Purchase</button>
                <a href="{{ route('products.list') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection

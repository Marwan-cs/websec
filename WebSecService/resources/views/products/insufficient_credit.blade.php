@extends('layouts.master')

@section('title', 'Insufficient Credit')

@section('content')
<div class="container mt-4">
    <div class="alert alert-danger text-center">
        <h2>Sorry!</h2>
        <p>You don't have enough credit to purchase <strong>{{ $product->name }}</strong>.</p>
        <p>Price: {{ $product->price }} EGP | Your Credit: {{ auth()->user()->credit }} EGP</p>
        <a href="{{ route('products.list') }}" class="btn btn-primary mt-3">Back to Products</a>
    </div>
</div>
@endsection

@extends('layouts.master')

@section('title', 'My Purchases')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">My Purchased Products</h1>

        @if($products->count())
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Model</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Description</th>
                        <th>Purchased At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>
                                <img src="{{ asset('images/' . $product->photo) }}" alt="{{ $product->name }}" width="80">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->model }}</td>
                            <td>{{ $product->code }}</td>
                            <td>{{ $product->price }} EGP</td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>{{ $product->price * ($product->pivot->quantity ?? 1) }} EGP</td>
                            <td>{{ $product->description }}</td>
                            <td>{{ $product->pivot->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-info">You haven't purchased any products yet.</div>
        @endif
    </div>
@endsection

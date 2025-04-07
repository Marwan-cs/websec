@extends('layouts.master')
@section('title', 'Test Page')
@section('content')
<div class="row mt-2">
    <div class="col col-10">
        <h1>Products</h1>
    </div>
    <div class="col col-2">
        @role(['admin', 'employee']) <!-- Only show for admin and employee -->
        <a href="{{ route('products_edit') }}" class="btn btn-success form-control">Add Product</a>
        @endrole
    </div>
</div>

<form>
    <div class="row">
        <div class="col col-sm-2">
            <input name="keywords" type="text" class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
        </div>
        <div class="col col-sm-2">
            <input name="min_price" type="numeric" class="form-control" placeholder="Min Price" value="{{ request()->min_price }}" />
        </div>
        <div class="col col-sm-2">
            <input name="max_price" type="numeric" class="form-control" placeholder="Max Price" value="{{ request()->max_price }}" />
        </div>
        <div class="col col-sm-2">
            <select name="order_by" class="form-select">
                <option value="" {{ request()->order_by == "" ? "selected" : "" }} disabled>Order By</option>
                <option value="name" {{ request()->order_by == "name" ? "selected" : "" }}>Name</option>
                <option value="price" {{ request()->order_by == "price" ? "selected" : "" }}>Price</option>
            </select>
        </div>
        <div class="col col-sm-2">
            <select name="order_direction" class="form-select">
                <option value="" {{ request()->order_direction == "" ? "selected" : "" }} disabled>Order Direction</option>
                <option value="ASC" {{ request()->order_direction == "ASC" ? "selected" : "" }}>ASC</option>
                <option value="DESC" {{ request()->order_direction == "DESC" ? "selected" : "" }}>DESC</option>
            </select>
        </div>
        <div class="col col-sm-1">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
        <div class="col col-sm-1">
            <button type="reset" class="btn btn-danger">Reset</button>
        </div>
    </div>
</form>

@foreach($products as $product)
    <div class="card mt-2">
        <div class="card-body">
            <div class="row">
                <div class="col col-sm-12 col-lg-4">
                    <img src="{{ asset("images/$product->photo") }}" class="img-thumbnail" alt="{{ $product->name }}" width="100%">
                </div>
                <div class="col col-sm-12 col-lg-8 mt-3">
                    <div class="row mb-2">
                        <div class="col-8">
                            <h3>{{ $product->name }}</h3>
                        </div>
                        <div class="col col-2">
                            @role(['admin', 'employee']) <!-- Only show for admin and employee -->
                            <a href="{{ route('products_edit', $product->id) }}" class="btn btn-success form-control">Edit</a>
                            @endrole
                        </div>
                        <div class="col col-2">
                            @role('admin') <!-- Only show for admin -->
                            <a href="{{ route('products_delete', $product->id) }}" class="btn btn-danger form-control">Delete</a>
                            @endrole
                        </div>
                    </div>

                    <table class="table table-striped">
                        <tr><th width="20%">Name</th><td>{{ $product->name }}</td></tr>
                        <tr><th>Model</th><td>{{ $product->model }}</td></tr>
                        <tr><th>Code</th><td>{{ $product->code }}</td></tr>
                        <tr><th>Price</th><td>{{ $product->price }}</td></tr>
                        <tr><th>Description</th><td>{{ $product->description }}</td></tr>
                        <tr><th>Amount</th><td>{{ $product->amount }}</td></tr>
                    </table>

                    <!-- Purchase Button -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-primary form-control" {{ $product->amount <= 0 ? 'disabled' : '' }}>
                            {{ $product->amount <= 0 ? 'Out of Stock' : 'Add to Cart' }}
                        </button>
                    </form>

                    <!-- Modify Stock Button (Admin and Employee Only) -->
                    @role(['admin', 'employee'])
                    <form action="{{ route('products.update_stock', $product->id) }}" method="POST" class="mt-2">
                        @csrf
                        @method('PUT')
                        <div class="input-group">
                            <input type="number" name="amount" class="form-control" placeholder="New Stock Amount" min="0" required>
                            <button type="submit" class="btn btn-warning">Update Stock</button>
                        </div>
                    </form>
                    @endrole
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection
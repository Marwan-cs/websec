@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1 class="h3 mb-0">Edit Product</h1>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('products.save', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" 
                                   id="code"
                                   name="code" 
                                   class="form-control @error('code') is-invalid @enderror"
                                   value="{{ old('code', $product->code) }}" 
                                   required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="model" class="form-label">Model</label>
                            <input type="text" 
                                   id="model"
                                   name="model" 
                                   class="form-control @error('model') is-invalid @enderror"
                                   value="{{ old('model', $product->model) }}" 
                                   required>
                            @error('model')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" 
                                   id="name"
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $product->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" 
                                   id="price"
                                   name="price" 
                                   step="0.01" 
                                   min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price', $product->price) }}" 
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" 
                                   id="photo"
                                   name="photo" 
                                   class="form-control @error('photo') is-invalid @enderror">
                            @if($product->photo)
                                <small class="form-text text-muted">Current: {{ $product->photo }}</small>
                            @endif
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea id="description"
                                    name="description" 
                                    class="form-control @error('description') is-invalid @enderror"
                                    rows="3">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="{{ route('products_list') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

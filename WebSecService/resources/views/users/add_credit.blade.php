@extends('layouts.master')
@section('title', 'Add Credit')
@section('content')
<div class="container">
    <h1>Add Credit to {{ $user->name }}</h1>
    <form action="{{ route('save_credit', $user->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="credit" class="form-label">Credit Amount</label>
            <input type="number" name="credit" id="credit" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Credit</button>
    </form>
</div>
@endsection

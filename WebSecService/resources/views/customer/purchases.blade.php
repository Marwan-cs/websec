@extends('layouts.master')
@section('title', 'My Purchases')
@section('content')
<div class="card m-4">
    <div class="card-body">
        <h1>My Purchases</h1>
        
        @if($purchases->isEmpty())
            <p class="text-muted">No purchases yet.</p>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr>
                                <td>{{ $purchase->product->name }}</td>
                                <td>${{ number_format($purchase->amount, 2) }}</td>
                                <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
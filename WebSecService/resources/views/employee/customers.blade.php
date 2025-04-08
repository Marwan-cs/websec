@extends('layouts.master')
@section('title', 'Manage Customers')
@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h1 class="h3 mb-0">Manage Customers</h1>
        </div>
        <div class="card-body">
            @if($customers->isEmpty())
                <p class="text-muted">No customers found.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Current Credit</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>${{ number_format($customer->credit, 2) }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <!-- Add Credit Form -->
                                            <form method="POST" action="{{ route('employee.customers.credit', $customer->id) }}" class="d-flex gap-2">
                                                @csrf
                                                <input type="number" 
                                                       name="credit" 
                                                       step="0.01" 
                                                       min="0.01"
                                                       class="form-control form-control-sm" 
                                                       placeholder="Amount"
                                                       required>
                                                <button type="submit" class="btn btn-success btn-sm">Add</button>
                                            </form>

                                            <!-- Edit Credit Form -->
                                            <form method="POST" action="{{ route('employee.customers.edit-credit', $customer->id) }}" class="d-flex gap-2">
                                                @csrf
                                                @method('PUT')
                                                <input type="number" 
                                                       name="credit" 
                                                       step="0.01" 
                                                       min="0"
                                                       value="{{ $customer->credit }}"
                                                       class="form-control form-control-sm"
                                                       required>
                                                <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                            </form>

                                            <!-- Delete Credit Form -->
                                            <form method="POST" action="{{ route('employee.customers.delete-credit', $customer->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to reset credit to zero?')">
                                                    Reset
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
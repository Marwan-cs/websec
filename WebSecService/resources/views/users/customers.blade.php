@extends('layouts.master')
@section('title', 'Customers')
@section('content')

<div class="container mt-4">
    <h2>Customer List</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Credit</th>

                <th>Add Credit</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->credit }}</td>

                <td>
                    <form action="{{ route('save_credit', $user->id) }}" method="POST" class="d-flex">
                        @csrf
                        <input type="number" name="credit" class="form-control" placeholder="EGP" required min="1" step="0.01">
                        <button type="submit" class="btn btn-warning ms-2">Add</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

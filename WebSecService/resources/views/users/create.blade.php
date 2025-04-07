@extends('layouts.master')
@section('title', 'Create User')
@section('content')
    @can('edit_users')
        <div class="card">
            <div class="card-header">Create User</div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="post">
                    @csrf
                    <div class="form-group mb-2">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password_confirmation" class="form-label">Confirm Password:</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <div class="form-group mb-2">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            You do not have permission to create a user.
        </div>
    @endcan
@endsection
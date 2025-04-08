@extends('layouts.master')
@section('title', 'Edit Password')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0">Change Password</h1>
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

                    <form action="{{ route('save_password', $user->id) }}" method="POST">
                        @csrf
                        
                        @if(!auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                            <div class="mb-3">
                                <label for="old_password" class="form-label">Current Password</label>
                                <input type="password" 
                                       id="old_password"
                                       name="old_password" 
                                       class="form-control @error('old_password') is-invalid @enderror"
                                       required>
                                @error('old_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" 
                                   id="password"
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Password must be at least 8 characters long and contain a mix of letters and numbers.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" 
                                   id="password_confirmation"
                                   name="password_confirmation" 
                                   class="form-control"
                                   required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Update Password
                            </button>
                            <a href="{{ route('profile', $user->id) }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.master')
@section('title', 'Edit User')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0">Edit User</h1>
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

                    <form action="{{ route('users_save', $user->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" 
                                   id="name"
                                   name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @can('admin_users')
                            <div class="mb-3">
                                <label for="roles" class="form-label d-flex justify-content-between">
                                    <span>Roles</span>
                                    <a href="#" class="text-muted small" onclick="event.preventDefault(); document.getElementById('roles').selectedIndex = -1;">
                                        <i class="bi bi-x-circle"></i> Reset
                                    </a>
                                </label>
                                <select multiple 
                                        id="roles" 
                                        name="roles[]" 
                                        class="form-select @error('roles') is-invalid @enderror" 
                                        size="5">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                                {{ $role->taken ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Hold Ctrl/Cmd to select multiple roles
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="permissions" class="form-label d-flex justify-content-between">
                                    <span>Direct Permissions</span>
                                    <a href="#" class="text-muted small" onclick="event.preventDefault(); document.getElementById('permissions').selectedIndex = -1;">
                                        <i class="bi bi-x-circle"></i> Reset
                                    </a>
                                </label>
                                <select multiple 
                                        id="permissions" 
                                        name="permissions[]" 
                                        class="form-select @error('permissions') is-invalid @enderror"
                                        size="5">
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->name }}" 
                                                {{ $permission->taken ? 'selected' : '' }}>
                                            {{ $permission->display_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('permissions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Hold Ctrl/Cmd to select multiple permissions
                                </div>
                            </div>
                        @endcan

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
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

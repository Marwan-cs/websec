@extends('layouts.master')
@section('title', 'Edit User')
@section('content')
    <div class="card">
        <div class="card-header">Edit User</div>
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="form-group mb-2">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                </div>
                <div class="form-group mb-2">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="form-group mb-2">
                    <label for="password" class="form-label">Password (leave blank to keep current password):</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group mb-2">
                    <label for="password_confirmation" class="form-label">Confirm Password:</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>
                @can('edit_users')
                <div class="col-12 mb-2">
                    <label for="model" class="form-label">Roles:</label>
                    <select multiple class="form-select" name="roles[]">
                        @foreach($roles as $role)
                            <option value='{{$role->name}}' {{$role->taken?'selected':''}}>
                                {{$role->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 mb-2">
                    <label for="model" class="form-label">Direct Permissions:</label>
                    <select multiple class="form-select" name="permissions[]">
                        @foreach($permissions as $permission)
                            <option value='{{$permission->name}}' {{$permission->taken?'selected':''}}>
                                {{$permission->display_name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endcan
                <div class="form-group mb-2">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        <div class="row m-4 col-sm-8">
            <form action="{{ route('users_save', $user->id) }}" method="post">
                {{ csrf_field() }}
                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">
                        <strong>Error!</strong> {{ $error }}
                    </div>
                @endforeach
                <div class="row mb-2">
                    <div class="col-12">
                        <label for="code" class="form-label">Name:</label>
                        <input type="text" class="form-control" placeholder="Name" name="name" required value="{{ $user->name }}">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
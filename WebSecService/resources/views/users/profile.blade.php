@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0">User Profile</h1>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th width="200">Name</th>
                                <td>{{ $user->name }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Roles</th>
                                <td>
                                    @forelse($user->roles as $role)
                                        <span class="badge bg-primary me-1">{{ $role->name }}</span>
                                    @empty
                                        <span class="text-muted">No roles assigned</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th>Permissions</th>
                                <td>
                                    @forelse($permissions as $permission)
                                        <span class="badge bg-success me-1">{{ $permission->display_name }}</span>
                                    @empty
                                        <span class="text-muted">No permissions assigned</span>
                                    @endforelse
                                </td>
                            </tr>
                            @if($user->credit !== null)
                                <tr>
                                    <th>Credit Balance</th>
                                    <td>${{ number_format($user->credit, 2) }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>

                    <div class="d-flex gap-2 mt-3">
                        @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                            <a href="{{ route('edit_password', $user->id) }}" class="btn btn-primary">
                                <i class="bi bi-key"></i> Change Password
                            </a>
                        @endif
                        
                        @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                            <a href="{{ route('users_edit', $user->id) }}" class="btn btn-success">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </a>
                        @endif

                        @if(auth()->user()->hasPermissionTo('show_users'))
                            <a href="{{ route('users') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Users
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

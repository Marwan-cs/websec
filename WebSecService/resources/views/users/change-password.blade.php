@extends('layouts.master')
@section('title', 'Change Password')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{ route('change.password.submit') }}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
      </div>
      <div class="form-group mb-2">
        <label for="password" class="form-label">New Password:</label>
        <input type="password" class="form-control" placeholder="New Password" name="password" required>
      </div>
      <div class="form-group mb-2">
        <label for="password_confirmation" class="form-label">Confirm Password:</label>
        <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
      </div>
      <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Change Password</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection
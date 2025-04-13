@extends('layouts.master')
@section('title', 'Forgot Password')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-body">
      <form action="{{ route('forgot.password.submit') }}" method="post">
      {{ csrf_field() }}
      <div class="form-group">
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
          <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
      </div>
      <div class="form-group mb-2">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="email" name="email" required>
      </div>
      <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Send Temporary Password</button>
      </div>
    </form>
    </div>
  </div>
</div>
@endsection
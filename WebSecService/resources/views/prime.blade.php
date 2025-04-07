@extends('layouts.master')

@section('title', 'Prime Numbers')

@section('content')
    @php
        function isPrime($number) {
            if ($number < 2) return false;
            for ($i = 2; $i * $i <= $number; $i++) {
                if ($number % $i == 0) return false;
            }
            return true;
        }
    @endphp

    <div class="card m-4">
        <div class="card-header text-center fw-bold">Prime Numbers</div>
        <div class="card-body text-center">
            @foreach (range(1, 100) as $i)
                @if (isPrime($i))
                    <span class="badge bg-primary m-1">{{ $i }}</span>
                @else
                    <span class="badge bg-secondary">{{$i}}</span>    
                @endif
            @endforeach
        </div>
    </div>
@endsection

@push('styles')
<style>
    .badge { font-size: 1.2rem; margin: 2px; }
</style>
@endpush

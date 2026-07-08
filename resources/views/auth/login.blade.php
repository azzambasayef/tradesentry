@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-4">
        <div class="text-center mb-4">
            <h1 class="text-neon fw-bold" style="font-size: 3rem;">TradeSentry</h1>
            <p class="text-muted">Global Supply Chain Risk Intelligence</p>
        </div>
        
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <h4 class="mb-4 text-center">Login to your account</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control bg-dark text-light border-secondary" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control bg-dark text-light border-secondary" required>
                    </div>
                    <button type="submit" class="btn btn-neon w-100 py-2">Sign In</button>
                </form>
                
                <div class="mt-4 text-center">
                    <span class="text-muted">Don't have an account?</span> 
                    <a href="{{ route('register') }}" class="text-neon text-decoration-none">Register here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

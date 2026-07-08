@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-5">
        <div class="text-center mb-4">
            <h1 class="text-neon fw-bold">TradeSentry</h1>
        </div>
        
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <h4 class="mb-4 text-center">Create a new account</h4>
                
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control bg-dark text-light border-secondary" required value="{{ old('name') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control bg-dark text-light border-secondary" required value="{{ old('email') }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control bg-dark text-light border-secondary" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control bg-dark text-light border-secondary" required>
                    </div>
                    <button type="submit" class="btn btn-neon w-100 py-2">Register</button>
                </form>
                
                <div class="mt-4 text-center">
                    <span class="text-muted">Already have an account?</span> 
                    <a href="{{ route('login') }}" class="text-neon text-decoration-none">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

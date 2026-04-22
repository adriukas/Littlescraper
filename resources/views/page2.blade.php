@extends('layouts.website') 
@section('title', 'Login - Discord Scraper')

@section('content') 
<div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card p-5 shadow-lg bg-white border-0 rounded-4" style="width: 100%; max-width: 450px;">
        
        <h2 class="text-center mb-4 fw-bold">
            <span class="text-success">Login</span> to <span class="text-warning">dashboards</span>
        </h2>
        
        <p class="text-muted text-center small mb-4">Enter your credentials</p>

        @if(session('error'))
            <div class="alert alert-danger py-2 small border-0 shadow-sm text-center">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger py-2 small border-0 shadow-sm">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.check') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Email address</label>
                <input type="email" name="email" class="form-control form-control-lg fs-6" placeholder="admin@example.com" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold small text-secondary">Password</label>
                <input type="password" name="password" class="form-control form-control-lg fs-6" placeholder="••••••••" required>
            </div>
            
            <button type="submit" class="btn btn-secondary btn-lg w-100 mt-3 fw-bold text-uppercase shadow-sm" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                Sign In
            </button>
        </form>
        
        <div class="mt-4 text-center">
            <a href="{{ route('info') }}" class="text-decoration-none small text-muted">
                <i class="bi bi-arrow-left"></i> Back to main page
            </a>
        </div>
    </div>
</div>
@endsection
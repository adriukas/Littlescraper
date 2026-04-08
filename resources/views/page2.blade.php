@extends('layouts.website') 
@section('title', 'Login - Discord Scraper')

@section('content') 


  <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
  <div class="card p-5 shadow bg-white" style="width: 100%; max-width: 400px;">
<h3 class="text-center mb-4">Login</h3>
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.check') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-secondary btn-lg w-100 mt-3">Log in</button>
        </form>
    </div>
</div>



@endsection
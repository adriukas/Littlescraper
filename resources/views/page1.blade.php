@extends('layouts.website') 

@section('content') 
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">    
    <div class="row col-lg-8 mx-auto text-center shadow-lg p-5 bg-white rounded-4 border">
        <h1 class="text-dark mb-4 fw-bold text-uppercase">
            Manage Your
            <span class="text-success">B</span> 
            <span class="text-warning">O</span> 
            <span class="text-success">T</span> 
            <span class="text-warning">s</span> 
        </h1> 
        
        <p class="text-muted mb-4">
            Track channel data, monitor purchases and organize messages easily.
        </p>

        <div class="d-grid gap-2 col-md-6 mx-auto">
            <a href="{{ route('login') }}" class="btn btn-secondary btn-lg shadow-sm fw-bold text-uppercase" style="font-size: 0.9rem;">
                Join us
            </a>
        </div>
    </div>
</div>
@endsection
@extends('layouts.website') 

@section('content') 
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">    
    <div class="row col-lg-6 mx-auto text-center shadow p-5 bg-white rounded">
        <h1 class="text-dark">Discord Scraper</h1>
        <p class="text-black mb-4">Manage your bots here</p> 
        <a href="{{ route('login') }}" class="btn btn-secondary btn-lg mx-auto w-50"> Join us </a>
    </div>
</div>
@endsection
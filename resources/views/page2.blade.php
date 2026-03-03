@extends('layouts.website') 
@section('title', 'Login - Discord Scraper')

@section('content') 


<form>
  <div class="d-flex align-items-center justify-content-center" style="min-height: 80vh;">
  <div class="card p-5 shadow bg-white" style="width: 100%; max-width: 400px;">
  <div method="POST" action="/page3">
     @csrf
    <label for="exampleInputEmail1 text-light">Email address</label>
    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
  </div>

  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" class="form-control" id="exampleInputPassword1">
  </div>
  
        <a href="/page3" class="btn btn-secondary btn-lg px-5 mt-3"> Log in</a>
</form>



@endsection
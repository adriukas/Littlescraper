@extends('layouts.website') 

@section('title', 'Dashboard - Discord Scraper')

@section('content') 
<div class="row">
    <div class="col-lg-8 mx-auto text-center shadow p-5 bg-white rounded">
        <h2 class="mb-4">Welcome, {{ session('user_email') }}</h2>

        <div class="row">
            <div class="col-md-6 border-end">
                <p class="text-dark fw-bold mb-3">Scrape purchases</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('run.scrape', ['bot' => 'VintedSeekers', 'type' => 'SEEKERS']) }}" class="btn btn-success">VintedSeekers</a>
                    <a href="{{ route('run.scrape', ['bot' => 'BartoResell', 'type' => 'BARTO']) }}" class="btn btn-success">BartoResell</a>
                </div>
            </div>

            <div class="col-md-6">
                <p class="text-dark fw-bold mb-3">Scrape messages</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('run.scrape', ['bot' => 'Parallel', 'type' => 'PARALLEL']) }}" class="btn btn-warning text-white">ParallelResellers</a>
                    <a href="{{ route('run.scrape', ['bot' => 'Astral', 'type' => 'ASTRAL']) }}" class="btn btn-warning text-white">Astral</a>
                    <a href="{{ route('run.scrape', ['bot' => 'FlipFlow', 'type' => 'FLIPFLOW']) }}" class="btn btn-warning text-white">FlipFlow</a>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <p class="text-muted mb-3">Data management</p>
        <div class="d-flex justify-content-center gap-2">
            <a href="{{ route('history.sales') }}" class="btn btn-outline-secondary">
                <i class="bi bi-graph-up"></i> Purchases history
            </a>
            <a href="{{ route('history.messages') }}" class="btn btn-outline-secondary">
                <i class="bi bi-chat-left-text"></i> Messages history
            </a>
        </div>
        
        @if(session('user_email') === env('ADMIN_EMAIL'))
            <div class="mt-4 p-3 bg-danger bg-opacity-10 rounded border border-danger">
                <small class="text-danger d-block mb-2">Admin panel</small>
                <button class="btn btn-danger btn-sm" onclick="alert('System Logs downloaded')">Download system logs</button>
            </div>
        @endif
    </div>              
</div>
@endsection
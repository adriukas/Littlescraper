@extends('layouts.website') 

@section('title', 'Login - Discord Scraper')

@section('content') 
<body class="bg-light"> <main class="d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto text-center shadow p-5 bg-white rounded">
                    <p class="text-dark mb-2">Choose a server to <strong>scrape purchases</strong>:</p>
                    
                    {{-- <a href="{{ route('run.scrape', ['bot' => 'Parallel', 'type' => 'PARALLEL']) }}" class="btn btn-success">ParallelResellers</a>--}}            
                    <a href="{{ route('run.scrape', ['bot' => 'VintedSeekers', 'type' => 'SEEKERS']) }}" class="btn btn-success">VintedSeekers</a>
                    <a href="{{ route('run.scrape', ['bot' => 'BartoResell', 'type' => 'BARTO']) }}" class="btn btn-success">BartoResell</a>

                    <p class="text-dark mb-2 mt-4">Choose a server to <strong>scrape messages</strong>:</p>

                    <a href="{{ route('run.scrape', ['bot' => 'Parallel', 'type' => 'PARALLEL']) }}" class="btn btn-warning mb-2 text-white">ParallelResellers</a>
                    <a href="{{ route('run.scrape', ['bot' => 'Astral', 'type' => 'ASTRAL']) }}" class="btn btn-warning mb-2 text-white">Astral</a>
                    <a href="{{ route('run.scrape', ['bot' => 'FlipFlow', 'type' => 'FLIPFLOW']) }}" class="btn btn-warning mb-2 text-white">FlipFlow</a>    
                    <a href="{{ route('run.scrape', ['bot' => 'Archiev', 'type' => 'ARCHIEV']) }}" class="btn btn-warning mb-2 text-white">Archiev</a>    
                    <a href="{{ route('run.scrape', ['bot' => 'DotB', 'type' => 'DOTB']) }}" class="btn btn-warning mb-2 text-white">DotB</a>    

                    
                    <p class="text-dark mb-2 mt-4">See the <strong>history of all the previous scrapes</strong>:</p>
                    <a href="{{ route('history.sales') }}" class="btn btn-secondary mb-2 text-white">Purchases scraping history</a>
                    <a href="{{ route('history.messages') }}" class="btn btn-secondary mb-2 text-white">Messages scraping history</a>

                </div>              
        </div>
    </main>
</body>

@endsection
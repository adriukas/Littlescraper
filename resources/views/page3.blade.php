@extends('layouts.website') 

@section('title', 'Login - Discord Scraper')

@section('content') 
<body class="bg-light"> <main class="d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto text-center shadow p-5 bg-white rounded">
                    <p class="text-dark mb-2">Choose a server to <strong>scrape purchases</strong>:</p>
                    
                    <a href="/run-scrape?bot=ParallelResellers&type=PARALLEL" class="btn btn-success mb-2">ParallelResellers</a>
                    <a href="/run-scrape?bot=VintedSeekers&type=SEEKERS" class="btn btn-success mb-2">VintedSeekers</a>
                    <a href="/run-scrape?bot=BartoResell&type=BARTO" class="btn btn-success mb-2">BartoResell</a>

                    <p class="text-dark mb-2 mt-4">Choose a server to <strong>scrape messages</strong>:</p>

                    <a href="/run-scrape?bot=Astral&type=ASTRAL" class="btn btn-warning mb-2 text-white">Astral</a>
                    <a href="/run-scrape?bot=FlipFlow&type=FLIPFLOW" class="btn btn-warning mb-2 text-white">FlipFlow</a>    
                
                    <p class="text-dark mb-2 mt-4">See the <strong>history of all the previous scrapes</strong>:</p>
                    <a href="/history_sales" class="btn btn-secondary mb-2 text-white">Purchases scraping history</a>
                    <a href="/history_messages" class="btn btn-secondary mb-2 text-white">Messages scraping history</a>

                </div>              
        </div>
    </main>
</body>

@endsection
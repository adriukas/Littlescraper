@extends('layouts.website') 

@section('title', 'Login - Discord Scraper')

@section('content') 
<body class="bg-light"> <main class="d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto text-center shadow p-5 bg-white rounded">
                    <p class="text-dark mb-2">Choose a server to scrape:</p>
                    
                    <a href="/page4?bot=ParallelResellers&type=PARALLEL" class="btn btn-success mb-2">ParallelResellers</a>
                    <a href="/page4?bot=VintedSeekers&type=SEEKERS" class="btn btn-success mb-2">VintedSeekers</a>
                    <a href="/page4?bot=BartoResell&type=BARTO" class="btn btn-success mb-2">BartoResell</a>

                    <p class="text-dark mb-2 mt-4">Choose server to read messages:</p>

                    <a href="/page4?bot=Astral&type=ASTRAL" class="btn btn-warning mb-2 text-white">Astral</a>
                    <a href="/page4?bot=FlipFlow&type=FLIPFLOW" class="btn btn-warning mb-2 text-white">FlipFlow</a>                </div>              
        </div>
    </main>
</body>

@endsection
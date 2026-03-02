<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraper of Vinted bots</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light"> <main class="d-flex align-items-center justify-content-center">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 mx-auto text-center shadow p-5 bg-white rounded">
                    <h1 class="fw-bold text-primary">Welcome to Bot Scraper</h1>
                    <p class="text-muted mb-4">Choose a bot to scrape:</p>
                    
                    <a href="/page4?bot=ParallelResellers&type=PARALLEL" class="btn btn-secondary mb-2">ParallelResellers</a>
                    <a href="/page4?bot=VintedSeekers&type=VINTED" class="btn btn-success mb-2">VintedSeekers</a>
                    <a href="/page4?bot=BartoResell&type=BARTO" class="btn btn-info mb-2">BartoResell</a>
                    <a href="/page4?bot=Astral&type=ASTRAL" class="btn btn-dark mb-2">Astral</a>
                    <a href="/page4?bot=FlipFlow&type=FLIPFLOW" class="btn btn-warning mb-2">FLIPFLOW</a>

                    <hr>
                    <a href="/" class="btn btn-outline-danger">Log out</a>
                </div>              
        </div>
    </main>
</body>
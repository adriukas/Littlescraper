<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Discord Scraper')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    .rounded-table-container {
        border-radius: 15px; 
        overflow: hidden; 
        border: 1px solid #dee2e6; 
    }
    </style>

</head>
<body class="bg-primary bg-opacity-10">
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow">
        <div class="container">
            <a class="navbar-brand" href="/">Discord Scraper</a>

            <div class="ms-auto d-flex align-items-center gap-2">
            
            @if(Request::routeIs('scraper') || Request::is('run-scrape*'))               
             <a href="/page3" class="btn btn-outline-light btn-sm">Back to bot list</a>
            @endif
            
            @if(!Request::is('/') && !Request::is('page2'))
                <a href="/" class="btn btn-outline-danger btn-sm">Log out</a>
            @endif
        </div>
    </nav>

    <main class="container">
        @yield('content') {{-- This is where your Bootstrap Table will appear --}}
    </main>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Discord Scraper')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: sans-serif; 
        }

        .navbar-grey {
            background-color: #6c757d !important; /* Klasikinė pilka */
            padding: 0.8rem 0;
        }

        .brand-bold {
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 1.25rem;
        }

        .text-green { color: #28a745; }
        .text-yellow { color: #ffc107; }

        .rounded-table-container { 
            border-radius: 15px; 
            overflow: hidden; 
            border: 1px solid #dee2e6; 
        }
    </style>
</head>

<body class="bg-light">
    
    <nav class="navbar navbar-expand-lg navbar-dark navbar-grey mb-4 shadow-sm">
        <div class="container">
            <a class="navbar-brand brand-bold" href="{{ route('info') }}">
                <span class="text-green">Discord</span> <span class="text-yellow">Scraper</span>
            </a>

            <div class="ms-auto d-flex align-items-center gap-2">
                @if(Request::routeIs('run.scrape') || Request::routeIs('history.*'))               
                    <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm text-uppercase">Back to bot list</a>
                @endif
                
                @if(session('is_logged_in'))
                    <a href="{{ route('logout') }}" class="btn btn-danger text-uppercase btn-sm">Log out</a>
                @endif
            </div>
        </div>
    </nav>
    
    <main class="container">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Discord Scraper')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
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

        /* Subtilus stilius klaidų pranešimams */
        .alert {
            border-radius: 10px;
        }
        input::placeholder,
     
        textarea::placeholder {
            color: #adb5bd !important; 
            opacity: 1; 
            font-weight: 400; 
            font-size: 0.9rem;
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
                    <a href="{{ route('logout') }}" class="btn btn-danger text-uppercase btn-sm shadow-sm">Log out</a>
                @endif
            </div>
        </div>
    </nav>
    
    <main class="container mt-4">
        
        {{-- 1. GLOBALUS KLAIDŲ ATVAIZDAVIMAS (Backend validatoriai) --}}
        @if ($errors->any())
            <div class="alert alert-danger shadow-sm border-0 mb-4">
                <div class="d-flex align-items-center mb-1">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <strong class="small">Please fix the following errors:</strong>
                </div>
                <ul class="mb-0 small fw-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 2. SĖKMĖS PRANEŠIMAI (Sėkmingai pridėjus, ištrynus ar redagavus) --}}
        @if(session('success'))
            <div class="alert alert-success shadow-sm border-0 mb-4 d-flex align-items-center fw-bold small">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- 3. KONKRETAUS PUSLAPIO TURINYS --}}
        @yield('content')

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
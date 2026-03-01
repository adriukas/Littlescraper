<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <a href="/page3" class="btn btn-secondary mb-3">Back to all scrapers</a>
    
    <form action="/run-scrape" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary mb-4">Click to scrape now</button>
    </form>

    @if(isset($purchases))
        <h3>Results:</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Monitor name</th>
                    <th>Time</th>
                    <th>Message</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $item)
                    <tr>
                        <td>{{ $item['username'] }}</td>
                        <td>{{ $item['timestamp'] }}</td>
                        <td>{{ $item['content'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
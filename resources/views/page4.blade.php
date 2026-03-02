<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">Scraping: {{ $botName }}</h1>
        <a href="/page3" class="btn btn-outline-secondary">Back to Bot List</a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">
            <form action="/run-scrape" method="POST">
                @csrf
                <input type="hidden" name="channel_id" value="{{ $channelId }}">
                <input type="hidden" name="bot_name" value="{{ $botName }}">
                <button type="submit" class="btn btn-primary btn-lg px-5">Click to scrape</button>
            </form>
        </div>
    </div>

    @if(isset($purchases) && count($purchases) > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Author</th>
                        <th>Type</th>
                        <th>Item / Subject</th>
                        <th>Details / Message</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $item)
                        <tr class="{{ $item['type'] == 'bot_success' ? 'table-success' : '' }}">
                            <td><strong>{{ $item['user'] }}</strong></td>
                            <td>
                                <span class="badge {{ $item['type'] == 'bot_success' ? 'bg-primary' : 'bg-info text-dark' }}">
                                    {{ $item['type'] == 'bot_success' ? 'BOT' : 'MEMBER' }}
                                </span>
                            </td>
                            <td>{{ $item['item'] }}</td>
                            <td>{{ $item['text'] }}</td>
                            <td class="text-muted small">{{ \Carbon\Carbon::parse($item['time'])->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-light text-center py-5 border">
            <i class="bi bi-cloud-download display-1 text-muted"></i>
            <p class="mt-3">No data loaded yet.</p>
        </div>
    @endif
</div>
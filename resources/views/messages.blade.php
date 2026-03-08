@extends('layouts.website') 
@section('title', 'Chat Scraper - ' . $botName)

@section('content') 
    <section class="card shadow-sm border-0 mb-4 text-center p-4">
        <form action="/run-scrape" method="POST">
            @csrf
            <input type="hidden" name="channel_id" value="{{ $channelId }}">
            <input type="hidden" name="bot_name" value="{{ $botName }}">
            <button type="submit" class="btn btn-secondary btn-lg px-5 shadow-sm">
                <i class="bi bi-chat-dots me-2"></i> Click to scrape {{ $botName }}
            </button>
        </form>
    </section>

    @if(isset($purchases) && count($purchases) > 0)
        <div class="row g-3 mb-4 text-center">
            <div class="col-md-12">
                <div class="card border-0 shadow-sm p-3">
                    <small class="text-muted d-block">Total messages today</small>
                    <span class="h3 fw-bold mb-0">{{ count($purchases) }}</span>
                </div>
            </div>
        </div>

        <div class="table-responsive rounded shadow-sm border">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Author</th>
                        <th>Message Content</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $item)
                        <tr>
                            <td class="fw-bold text-primary">{{ $item['user'] }}</td>
                            <td>{{ $item['text'] }}</td> 
                            <td class="text-muted small">{{ \Carbon\Carbon::parse($item['time'])->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="alert alert-light text-center py-5 border shadow-sm">
            <i class="bi bi-chat-left-dots display-1 text-muted"></i>
            <p class="mt-3 fs-5 text-muted">No chat history found.</p>
        </div>
    @endif
@endsection
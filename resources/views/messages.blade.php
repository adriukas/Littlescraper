@extends('layouts.website') 
@section('title', 'Chat Scraper - ' . $botName)

@section('content') 
    <section class="card shadow-sm border-0 mt-5 mb-4 text-center p-4">
        <form action="{{ route('run.scrape', ['bot' => $botName, 'type' => request('type')]) }}" method="POST">
            @csrf
            <input type="hidden" name="channel_id" value="{{ $channelId }}">
            <input type="hidden" name="bot_name" value="{{ $botName }}">
            <input type="hidden" name="type" value="{{ request()->query('type') }}">
            <button type="submit" class="btn btn-warning text-white btn-lg px-5 shadow-sm">
                <i class="bi bi-chat-dots me-2"></i> Scrape Chat: {{ $botName }}
            </button>
        </form>
    </section>

    {{-- Check if $purchases is an array --}}
    @if(is_array($purchases))
        
        @if(count($purchases) > 0)
            <div class="card border-0 shadow-sm p-3 mb-4">
                <small class="text-muted d-block text-center">Scraped messages in last 24 hours </small>
                <span class="h3 fw-bold mb-0 text-center d-block">{{ count($purchases) }}</span>
            </div>

            <div class="table-responsive rounded shadow-sm border bg-white">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Author</th>
                            <th>Message</th>
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
            <div class="alert alert-light text-center shadow-sm py-5 border">
                <i class="bi bi-chat-left-x text-warning d-block mb-3" style="font-size: 2.5rem;"></i>
                <h5 class="fw-bold">No messages found</h5>
                <p class="text-muted">There were no new messages from <strong>{{ $botName }}</strong> in the last 24 hours.</p>
            </div>
        @endif

    @endif
@endsection
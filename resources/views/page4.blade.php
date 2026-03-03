TODO:check where price is gbp where eur
@extends('layouts.website') 
@section('title', 'Login - Discord Scraper')
@section('content') 


    <div class="card shadow-sm mb-4">
        <div class="card-body text-center">
            <form action="/run-scrape" method="POST">
                @csrf
                <input type="hidden" name="channel_id" value="{{ $channelId }}">
                <input type="hidden" name="bot_name" value="{{ $botName }}">
                <button type="submit" class="btn btn-secondary btn-lg px-5 mt-3">Click to scrape {{ $botName }}</button>
            </form>
        </div>
    </div>

    @if(isset($purchases) && count($purchases) > 0)
        <div class="table-responsive rounded-table-container shadow-sm mb-5">
            <table class="table table-hover align-middle mb-0">
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


@endsection
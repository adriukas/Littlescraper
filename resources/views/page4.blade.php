@extends('layouts.website') 
@section('title', 'Scraper - ' . $botName)

// sutvarkyti sita puslapi, kad rodytu tik pardavimus, o ne zinutes (kaip messages.blade.php) 

@section('content') 

    <section class="card shadow-sm border-0 mb-4 text-center p-4">
        <form action="/run-scrape" method="POST">
            @csrf
            <input type="hidden" name="channel_id" value="{{ $channelId }}">
            <input type="hidden" name="bot_name" value="{{ $botName }}">
            <button type="submit" class="btn btn-secondary btn-lg px-5 shadow-sm">
                <i class="bi bi-cpu me-2"></i> Click to scrape {{ $botName }}
            </button>
        </form>
    </section>

    @if(isset($purchases) && count($purchases) > 0)
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center">
            <div>
                <small class="text-muted d-block">Total participation today</small>
                <span class="h3 fw-bold mb-0">{{ count($purchases) }}</span>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center border-start border-success border-4">
            <div>
                <small class="text-muted d-block">Total value today</small>
                <span class="h3 fw-bold text-success mb-0">{{ number_format($totalSum, 2) }} €</span>
            </div>
        </div>
    </div>
</div>

        <div class="table-responsive rounded shadow-sm border">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Author</th>
                        <th>Item</th>
                        <th>Price (€)</th> 
                        <th>Details</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $item)
                        <tr>
                            <td class="fw-bold">{{ $item['user'] }}</td>
                            <td>{{ $item['item'] }}</td>
                            <td class="fw-bold text-success">
                                {{ (isset($item['price']) && $item['price'] > 0) ? number_format($item['price'], 2) . ' €' : 'N/A' }}
                            </td>
                            <td>{{ $item['text'] }}</td> 
                            <td class="text-muted small">{{ \Carbon\Carbon::parse($item['time'])->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
        </div>
    @else
        <div class="alert alert-light text-center py-5 border shadow-sm">
            <i class="bi bi-cloud-download display-1 text-muted"></i>
            <p class="mt-3 fs-5 text-muted">No data loaded yet or nothing found in this channel.</p>
        </div>
    @endif

@endsection
@extends('layouts.website') 
@section('title', 'Purchases Scraper - ' . $botName)

@section('content') 
    <section class="card shadow-sm border-0 mb-4 text-center p-4">
        <form action="{{ route('run.scrape') }}" method="POST">
            @csrf
            <input type="hidden" name="channel_id" value="{{ $channelId }}">
            <input type="hidden" name="bot_name" value="{{ $botName }}">
            <input type="hidden" name="type" value="SALES"> <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm">
                <i class=" me-2"></i> Scrape latest purchases in: {{ $botName }}
            </button>
        </form>
    </section>

    @if(isset($purchases) && count($purchases) > 0)
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center">
                    <div>
                        <small class="text-muted d-block">Items found</small>
                        <span class="h3 fw-bold mb-0">{{ count($purchases) }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm p-3 d-flex flex-row align-items-center  border-4">
                    <div>
                        <small class="text-muted d-block">Total estimated value</small>
                        <span class="h3 fw-bold text-success mb-0">{{ number_format($totalSum, 2) }} €</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive rounded shadow-sm border bg-white">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Author</th>
                        <th>Item</th>
                        <th class="text-end">Price</th> 
                        <th>Details</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $item)
                        @if(!empty($item['item']) || $item['price'] >= 0)
                        <tr>
                            <td class="fw-bold text-primary">{{ $item['user'] }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $item['item'] ?? 'Item' }}</span></td>
                            <td class="fw-bold text-end {{ $item['price'] > 0 ? 'text-success' : 'text-muted' }}">
                                {{ number_format($item['price'], 2) }} €
                            </td>
                            <td class="small text-muted">{{ Str::limit($item['text'], 50) }}</td> 
                            <td class="text-muted small">{{ \Carbon\Carbon::parse($item['time'])->diffForHumans() }}</td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
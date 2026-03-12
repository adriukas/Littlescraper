@extends('layouts.website')

@section('content')
<div class="container">
    <section class="card shadow-sm border-0 mb-4  p-4">
        <small class="text-muted d-block">Purchases scraping history total value</small>
        <span class="h3 fw-bold text-success mb-0">{{ number_format($totalSum, 2) }} €</span>
    </section> <div class="table-responsive shadow-sm border rounded">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Bot</th>
                    <th>Author</th>
                    <th>Item</th>
                    <th>Price (€)</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $item)
                    <tr>
                        <td>{{ $item->bot_name }}</td>
                        <td>{{ $item->author }}</td>
                        <td>{{ $item->item_name ?? 'Success Box' }}</td>
                        <td class="text-success fw-bold">{{ number_format($item->price, 2) }} €</td>
                        <td>{{ \Carbon\Carbon::parse($item->scraped_at)->diffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $purchases->links() }}
    </div>
</div>
@endsection
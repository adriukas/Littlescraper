@extends('layouts.website')

@section('content')
<div class="container">
    <section class="card shadow-sm border-0 mb-4 p-4">
        <small class="text-muted d-block">Purchases scraping history total value</small>
        <span class="h3 fw-bold text-success mb-0">{{ number_format($totalSum, 2) }} €</span>
    </section> 

    <div class="table-responsive shadow-sm border rounded bg-white">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Bot</th>
                    <th>Author</th>
                    <th>Item</th>
                    <th>Price (€)</th>
                    <th>Time</th>
                    @if(session('user_email') === env('ADMIN_EMAIL'))
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $item)
                    <tr>
                        <td><span class="badge bg-success">{{ $item->bot->name ?? 'N/A' }}</span></td>
                        <td class="fw-bold">{{ $item->author }}</td>
                        <td>{{ $item->item_name ?? 'Success Box' }}</td>
                        <td class="text-success fw-bold">{{ number_format($item->price, 2) }} €</td>
                        <td class="text-muted small">{{ \Carbon\Carbon::parse($item->scraped_at)->diffForHumans() }}</td>
                        
                        @if(session('user_email') === env('ADMIN_EMAIL'))
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('history.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Do you really want to delete this sale?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                <button class="btn btn-sm btn-info text-white" onclick="alert('Editing functionality is available only to Admins')">Edit</button>
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ session('user_email') === env('ADMIN_EMAIL') ? 6 : 5 }}" class="text-center py-4 text-muted">
                            No sales history found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $purchases->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
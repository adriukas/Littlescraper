@extends('layouts.website')

@section('content')

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="nav nav-pills overflow-auto flex-nowrap pb-2">
            <a href="{{ route('history.sales') }}"
               class="nav-link {{ !request('bot') ? 'bg-success text-white' : 'bg-light text-dark border' }} me-2">
               All sales
            </a>
            @foreach($bots as $filterBot)
                <a href="{{ route('history.sales', ['bot' => $filterBot->name]) }}"
                   class="nav-link {{ request('bot') == $filterBot->name ? 'bg-success text-white' : 'bg-light text-dark border' }} me-2">
                   {{ $filterBot->name }}
                </a>
            @endforeach
        </div>
    </div>

    <section class="card shadow-sm border-0 mb-4 p-4 text-center">
        <small class="d-block fw-bold">Total value:</small>
        <span class="h2 fw-bold text-success mb-0">{{ number_format($totalSum, 2) }} €</span>
    </section>

    <div class="table-responsive shadow-sm border rounded bg-white">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Bot</th>
                    <th>Author</th>
                    <th>Item</th>
                    <th>Price</th>
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
                        <td class="text-muted small">{{ $item->scraped_at->diffForHumans() }}</td>

                        @if(session('user_email') === env('ADMIN_EMAIL'))
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('history.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                <button class="btn btn-sm btn-secondary text-white" data-bs-toggle="modal" data-bs-target="#editSale{{ $item->id }}">Edit</button>
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">No data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $purchases->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>

@if(session('user_email') === env('ADMIN_EMAIL'))
    @foreach($purchases as $item)
        <div class="modal fade text-dark" id="editSale{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('history.update', $item->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header bg-light">
                            <h5 class="modal-title">Edit purchase record</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-start">
                            <div class="mb-3">
                                <label class="fw-bold">Author</label>
                                <input type="text" name="author" class="form-control" value="{{ old('author', $item->author) }}">
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Price (€)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $item->price) }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endif

@endsection

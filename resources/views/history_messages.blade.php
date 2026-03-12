@extends('layouts.website')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4"> 
        <div class="nav nav-pills">
            <a href="{{ route('history.messages', ['bot' => 'ParallelResellers']) }}" 
               class="nav-link {{ request('bot') == 'ParallelResellers' ? 'bg-secondary text-white' : 'bg-light text-dark border'}} me-2">
               ParallelResellers
            </a>
            <a href="{{ route('history.messages', ['bot' => 'Astral']) }}" 
               class="nav-link {{ request('bot') == 'Astral' ? 'bg-secondary text-white' : 'bg-light text-dark border' }} me-2">
               Astral
            </a>
            <a href="{{ route('history.messages', ['bot' => 'FlipFlow']) }}" 
               class="nav-link {{ request('bot') == 'FlipFlow' ? 'bg-secondary text-white' : 'bg-light text-dark border' }} me-2">
               FlipFlow
            </a>
            <a href="{{ route('history.messages', ['bot' => 'Archiev']) }}" 
               class="nav-link {{ request('bot') == 'Archiev' ? 'bg-secondary text-white' : 'bg-light text-dark border' }} me-2">
               Archiev
            </a>
            <a href="{{ route('history.messages', ['bot' => 'DotB']) }}" 
               class="nav-link {{ request('bot') == 'DotB' ? 'bg-secondary text-white' : 'bg-light text-dark border' }} me-2">
               DotB
            </a>

        </div>
    </div>

    <div class="table-responsive shadow-sm border rounded bg-white">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Bot Name</th>
                    <th>Author</th>
                    <th>Message</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $item)
                    <tr>
                        <td><span class="badge bg-info text-dark">{{ $item->bot->name }}</span></td>
                        <td class="fw-bold">{{ $item->author }}</td>
                        <td>{{ $item->content }}</td>
                        <td class="text-muted small">{{ \Carbon\Carbon::parse($item->scraped_at)->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No messages found for this filter.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
            {{ $purchases->appends(request()->query())->links('pagination::bootstrap-4') }} 
</div>
@endsection
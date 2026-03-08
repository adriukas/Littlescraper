@extends('layouts.website')

@section('content')
<div class="container">
    <h2 class="mb-4">Chat history</h2>

    <div class="table-responsive shadow-sm border rounded">
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
                @foreach($purchases as $item)
                    <tr>
                        <td><span class="badge bg-secondary">{{ $item->bot_name }}</span></td>
                        <td class="fw-bold">{{ $item->author }}</td>
                        <td>{{ $item->content }}</td>
                        <td class="text-muted">{{ \Carbon\Carbon::parse($item->scraped_at)->diffForHumans() }}</td>
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
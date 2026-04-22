@extends('layouts.website')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger shadow-sm border-start border-danger border-4">
        <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-2"></i> Mistake:</h6>
        <ul class="mb-0 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success shadow-sm border-start border-success border-4 py-2">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
@endif
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4"> 
        <div class="nav nav-pills overflow-auto flex-nowrap pb-2">
            <a href="{{ route('history.messages') }}" 
               class="nav-link {{ !request('bot') ? 'bg-warning text-white' : 'bg-light text-dark border' }} me-2">
               All Messages
            </a>

            @foreach(\App\Models\Bot::all() as $filterBot)
                <a href="{{ route('history.messages', ['bot' => $filterBot->name]) }}" 
                   class="nav-link {{ request('bot') == $filterBot->name ? 'bg-warning text-white' : 'bg-light text-dark border'}} me-2">
                   {{ $filterBot->name }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="table-responsive shadow-sm border rounded bg-white">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Bot name</th>
                    <th>Author</th>
                    <th>Message</th>
                    <th>Time</th>
                    @if(session('user_email') === env('ADMIN_EMAIL'))
                        <th>Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($purchases as $item)
                    <tr>
                        <td><span class="badge bg-warning">{{ $item->bot->name ?? 'N/A' }}</span></td>
                      <td class="fw-bold">{{ $item->author }}</td>
                        <td>{{ $item->content }}</td>
                        <td class="text-muted small">{{ \Carbon\Carbon::parse($item->scraped_at)->diffForHumans() }}</td>
                        
                        @if(session('user_email') === env('ADMIN_EMAIL'))
                        <td>
                            <div class="d-flex gap-1">
                                <form action="{{ route('history.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this record permanently?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                                
                                <button class="btn btn-sm btn-secondary text-white" data-bs-toggle="modal" data-bs-target="#editMsg{{ $item->id }}">Edit</button>
                            </div>
                        </td>

                        <div class="modal fade" id="editMsg{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <form action="{{ route('history.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content text-dark">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit record</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Author</label>
                                                <input type="text" name="author" class="form-control" value="{{ $item->author }}">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Content</label>
                                                <textarea name="content" class="form-control" rows="3">{{ $item->content }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No records found.</td>
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
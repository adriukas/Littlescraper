@extends('layouts.website') 

@section('title', 'Dashboard - Discord Scraper')

@section('content') 
<div class="row">
    <div class="col-lg-10 mx-auto text-center shadow p-5 bg-white rounded">
        <h2 class="mb-4">Welcome, {{ session('user_email') }}</h2>

        <div class="row">
            <div class="col-md-6 border-end">
                <p class="text-dark fw-bold mb-3">Scrape purchases</p>
                <div class="d-grid gap-2">
                    @foreach($bots->where('type', 'SALES') as $bot)
                        <div class="mb-3 position-relative">
                            <form action="{{ route('run.scrape') }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="bot_name" value="{{ $bot->name }}">
                                <input type="hidden" name="type" value="SALES">
                                <input type="hidden" name="channel_id" value="{{ $bot->discord_channel_id }}">
                                <button type="submit" class="btn btn-success fw-bold py-3 shadow-sm">{{ $bot->name }}</button>
                            </form>

                            @if(session('user_email') === env('ADMIN_EMAIL'))
                                <form action="{{ route('bots.destroy', $bot->id) }}" method="POST" 
                                    onsubmit="return confirm('Are you sure you want to delete this bot?')"
                                    style="position: absolute; top: -10px; right: -10px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow" style="width: 25px; height: 25px; padding: 0;">
                                        &times;
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                    
                    @if($bots->where('type', 'SALES')->isEmpty())
                        <small class="text-muted">No purchase bots added yet.</small>
                    @endif
                </div>
            </div>

            <div class="col-md-6">
                <p class="text-dark fw-bold mb-3">Scrape messages</p>
                <div class="d-grid gap-2">
                    @foreach($bots->where('type', 'MESSAGE') as $bot)
                    <div class="mb-3 position-relative">
                        <form action="{{ route('run.scrape') }}" method="POST" class="d-grid">
                            @csrf
                            <input type="hidden" name="bot_name" value="{{ $bot->name }}">
                            <input type="hidden" name="type" value="MESSAGE">
                            <input type="hidden" name="channel_id" value="{{ $bot->discord_channel_id }}">
                            <button type="submit" class="btn btn-warning fw-bold py-3 shadow-sm text-dark">
                                <i class="bi bi-chat-dots"></i> {{ $bot->name }}
                            </button>
                        </form>

                        @if(session('user_email') === env('ADMIN_EMAIL'))
                            <form action="{{ route('bots.destroy', $bot->id) }}" method="POST" 
                                onsubmit="return confirm('Are you sure you want to delete this bot?')"
                                style="position: absolute; top: -10px; right: -10px; z-index: 10;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow" style="width: 28px; height: 28px; line-height: 1; border: 2px solid white;">
                                    &times;
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach

                    @if($bots->where('type', 'MESSAGE')->isEmpty())
                        <small class="text-muted">No message bots added yet.</small>
                    @endif
                </div>
            </div>
        </div>

        <hr class="my-5">

        <p class="text-muted mb-3 fw-bold">Data management</p>
        <div class="d-flex justify-content-center gap-3 mb-5">
            <a href="{{ route('history.sales') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-graph-up"></i> Sales history
            </a>
            <a href="{{ route('history.messages') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-chat-left-text"></i> Messages history
            </a>
        </div>
        
        @if(session('user_email') === env('ADMIN_EMAIL'))
            <div class="mt-4 p-4 bg-light rounded border shadow-sm text-start">
                <p class="text-danger fw-bold mb-3"><i class="bi bi-shield-lock"></i> Admin panel</p>
                
                <form action="{{ route('bots.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="small fw-bold">Bot name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. ALPHA" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">Discord channel ID</label>
                            <input type="text" name="discord_channel_id" class="form-control" placeholder="123456..." required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">Discord token</label>
                            <input type="password" name="token" class="form-control" placeholder="Token" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold">Bot category</label>
                            <select name="type" class="form-select" required>
                                <option value="SALES">Purchases bot</option>
                                <option value="MESSAGE">Messages bot</option>
                            </select>
                        </div>
                        <div class="col-12 text-end mt-3">
                            <button type="submit" class="btn btn-dark px-4">
                                <i class="bi bi-plus-circle"></i> Add to database
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>              
</div>
@endsection
@extends('layouts.website') 

@section('title', 'Dashboard - Discord Scraper')

@section('content') 
<div class="row">
    <div class="col-lg-10 mx-auto text-center shadow-lg p-5 bg-white rounded-4 border">
        <h2 class="mb-5 fw-bold text-dark">
            Welcome to Discord dashboards
        </h2>

        <div class="row">
            <div class="col-md-6 border-end px-4">
                <p class="text-success fw-bold mb-4 text-uppercase small letter-spacing-1">Scrape purchasing bots</p>
                <div class="d-grid gap-3">
                    @foreach($bots->where('type', 'SALES') as $bot)
                        <div class="position-relative">
                            <form action="{{ route('run.scrape') }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="bot_name" value="{{ $bot->name }}">
                                <input type="hidden" name="type" value="SALES">
                                <input type="hidden" name="channel_id" value="{{ $bot->discord_channel_id }}">
                                <button type="submit" class="btn btn-success fw-bold py-3 shadow-sm border-0">
                                    {{ $bot->name }}
                                </button>
                            </form>

                            @if(session('user_email') === env('ADMIN_EMAIL'))
                                <form action="{{ route('bots.destroy', $bot->id) }}" method="POST" 
                                    onsubmit="return confirm('Delete this bot?')"
                                    style="position: absolute; top: -10px; right: -10px; z-index: 5;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow border-2 border-white" style="width: 28px; height: 28px; padding: 0; line-height: 1;">
                                        &times;
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                    
                    @if($bots->where('type', 'SALES')->isEmpty())
                        <div class="py-4 border rounded bg-light">
                            <small class="text-muted">No purchasing bots found.</small>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-md-6 px-4">
                <p class="text-warning fw-bold mb-4 text-uppercase small letter-spacing-1">Scrape message bots</p>
                <div class="d-grid gap-3">
                    @foreach($bots->where('type', 'MESSAGE') as $bot)
                    <div class="position-relative">
                        <form action="{{ route('run.scrape') }}" method="POST" class="d-grid">
                            @csrf
                            <input type="hidden" name="bot_name" value="{{ $bot->name }}">
                            <input type="hidden" name="type" value="MESSAGE">
                            <input type="hidden" name="channel_id" value="{{ $bot->discord_channel_id }}">
                            <button type="submit" class="btn btn-warning fw-bold py-3 shadow-sm text-dark border-0">
                                {{ $bot->name }}
                            </button>
                        </form>

                        @if(session('user_email') === env('ADMIN_EMAIL'))
                            <form action="{{ route('bots.destroy', $bot->id) }}" method="POST" 
                                onsubmit="return confirm('Delete this bot?')"
                                style="position: absolute; top: -10px; right: -10px; z-index: 5;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow border-2 border-white" style="width: 28px; height: 28px; padding: 0; line-height: 1;">
                                    &times;
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach

                    @if($bots->where('type', 'MESSAGE')->isEmpty())
                        <div class="py-4 border rounded bg-light">
                            <small class="text-muted">No message bots found.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <hr class="my-5 opacity-25">

        <p class="text-secondary mb-4 fw-bold text-uppercase small">Data management</p>
        <div class="d-flex justify-content-center gap-3 mb-5">
            <a href="{{ route('history.sales') }}" class="btn btn-outline-success px-4 fw-bold">
                Purchasing history
            </a>
            <a href="{{ route('history.messages') }}" class="btn btn-outline-warning px-4 fw-bold text-yellow">
                Messages history
            </a>
        </div>
        
        @if(session('user_email') === env('ADMIN_EMAIL'))
            <div class="mt-4 p-4 bg-light rounded-3 border shadow-sm text-start">
                <p class="text-secondary fw-bold mb-4 text-uppercase small border-bottom pb-2">
                    Admin panel / Add new bot
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <ul class="mb-0 small fw-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success border-0 shadow-sm mb-4 fw-bold small text-center">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('bots.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary">Bot name</label>
                            <input type="text" name="name" class="form-control border-0 shadow-sm" placeholder="Alpha" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary">Channel ID</label>
                            <input type="text" name="discord_channel_id" class="form-control border-0 shadow-sm" placeholder="12345..." required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary">Token</label>
                            <input type="password" name="token" class="form-control border-0 shadow-sm" placeholder="Secret" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary">Category</label>
                            <select name="type" class="form-select border-0 shadow-sm" required>
                                <option value="SALES">Purchasing bot</option>
                                <option value="MESSAGE">Messages bot</option>
                            </select>
                        </div>
                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-secondary px-5 fw-bold text-uppercase shadow" style="font-size: 0.8rem;">
                                Add to database
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>              
</div>
@endsection
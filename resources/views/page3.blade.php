@extends('layouts.website') 

@section('title', 'Dashboard - Discord Scraper')

@section('content') 
<div class="row">
    <div class="col-lg-10 mx-auto text-center shadow-lg p-5 bg-white rounded-4 border">

        <div class="row">
            <div class="col-md-6 border-end px-4">
                <p class="text-success fw-bold mb-4 text-uppercase letter-spacing-1">Scrape purchasing bots</p>
                <div class="d-grid gap-3">
                    @foreach($bots->where('type', 'SALES') as $bot)
                        <div class="position-relative mb-4">
                            <form action="{{ route('run.scrape') }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="bot_name" value="{{ $bot->name }}">
                                <input type="hidden" name="type" value="SALES">
                                <input type="hidden" name="channel_id" value="{{ $bot->discord_channel_id }}">
                                <button type="submit" class="btn btn-success fw-bold py-3 shadow-sm border-0">
                                    {{ $bot->name }} 
                                    <small class="d-block opacity-75 fw-normal" style="font-size: 0.65rem;">ID: {{ $bot->discord_channel_id }}</small>
                                </button>
                            </form>

                            @if(Auth::user()->isAdmin())
                                <div style="position: absolute; top: -12px; right: -5px; z-index: 10; display: flex; gap: 5px;">
                                    <button type="button" class="btn btn-secondary btn-sm rounded-circle shadow border-2 border-white" style="width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;" data-bs-toggle="collapse" data-bs-target="#editForm{{ $bot->id }}">
                                        <i class="bi bi-pencil-fill" style="font-size: 0.75rem; color: white;"></i>
                                    </button>

                                    <form action="{{ route('bots.destroy', $bot->id) }}" method="POST" onsubmit="return confirm('Delete this bot?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow border-2 border-white" style="width: 28px; height: 28px; padding: 0;">
                                            &times;
                                        </button>
                                    </form>
                                </div>

                                <div class="collapse mt-2 text-start" id="editForm{{ $bot->id }}">
                                    <div class="card card-body bg-light border-0 shadow-sm p-3">
                                        <form action="{{ route('bots.update', $bot->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="small fw-bold text-uppercase">Name</label>
                                                    {{-- Naudojame old, jei yra klaida, kitu atveju rodomas dabartinis vardas --}}
                                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $bot->name) }}" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="small fw-bold text-uppercase">Channel ID</label>
                                                    <input type="text" name="discord_channel_id" class="form-control form-control-sm" value="{{ old('discord_channel_id', $bot->discord_channel_id) }}" required>
                                                </div>
                                                <div class="col-12 mt-1">
                                                    <label class="small fw-bold text-uppercase">Token</label>
                                                    <input type="password" name="token" class="form-control form-control-sm" value="{{ old('token', $bot->token) }}" required>
                                                </div>
                                                <div class="col-12 mt-2 text-center">
                                                    <button type="submit" class="btn btn-dark btn-sm w-100 text-uppercase">Save changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-md-6 px-4">
                <p class="text-warning fw-bold mb-4 text-uppercase letter-spacing-1">Scrape message bots</p>
                <div class="d-grid gap-3">
                    @foreach($bots->where('type', 'MESSAGE') as $bot)
                        <div class="position-relative mb-4">
                            <form action="{{ route('run.scrape') }}" method="POST" class="d-grid">
                                @csrf
                                <input type="hidden" name="bot_name" value="{{ $bot->name }}">
                                <input type="hidden" name="type" value="MESSAGE">
                                <input type="hidden" name="channel_id" value="{{ $bot->discord_channel_id }}">
                                <button type="submit" class="btn btn-warning fw-bold py-3 shadow-sm text-dark border-0">
                                    {{ $bot->name }}
                                    <small class="d-block opacity-75 fw-normal" style="font-size: 0.65rem;">ID: {{ $bot->discord_channel_id }}</small>
                                </button>
                            </form>

                            @if(Auth::user()->isAdmin())
                                <div style="position: absolute; top: -12px; right: -5px; z-index: 10; display: flex; gap: 5px;">
                                    <button type="button" class="btn btn-secondary btn-sm rounded-circle shadow border-2 border-white" style="width: 28px; height: 28px; padding: 0; display: flex; align-items: center; justify-content: center;" data-bs-toggle="collapse" data-bs-target="#editForm{{ $bot->id }}">
                                        <i class="bi bi-pencil-fill" style="font-size: 0.75rem; color: white;"></i>
                                    </button>

                                    <form action="{{ route('bots.destroy', $bot->id) }}" method="POST" onsubmit="return confirm('Delete this bot?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow border-2 border-white" style="width: 28px; height: 28px; padding: 0;">
                                            &times;
                                        </button>
                                    </form>
                                </div>

                                <div class="collapse mt-2 text-start" id="editForm{{ $bot->id }}">
                                    <div class="card card-body bg-light border-0 shadow-sm p-3">
                                        <form action="{{ route('bots.update', $bot->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="small fw-bold text-uppercase">Name</label>
                                                    <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $bot->name) }}" required>
                                                </div>
                                                <div class="col-6">
                                                    <label class="small fw-bold text-uppercase">Channel ID</label>
                                                    <input type="text" name="discord_channel_id" class="form-control form-control-sm" value="{{ old('discord_channel_id', $bot->discord_channel_id) }}" required>
                                                </div>
                                                <div class="col-12 mt-1">
                                                    <label class="small fw-bold text-uppercase">Token</label>
                                                    <input type="password" name="token" class="form-control form-control-sm" value="{{ old('token', $bot->token) }}" required>
                                                </div>
                                                <div class="col-12 mt-2 text-center">
                                                    <button type="submit" class="btn btn-dark btn-sm w-100 text-uppercase">Save changes</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <hr class="my-5 opacity-25">

        <p class="text-secondary mb-4 fw-bold text-uppercase">Data management</p>
        <div class="d-flex justify-content-center gap-3 mb-5">
            <a href="{{ route('history.sales') }}" class="btn btn-outline-success px-4 small text-uppercase fw-bold">Purchasing history</a>
            <a href="{{ route('history.messages') }}" class="btn btn-outline-warning px-4 small fw-bold text-uppercase text-dark">Messages history</a>
        </div>
        
        @if(Auth::user()->isAdmin())
            <div class="mt-4 p-4 bg-light rounded-3 border shadow-sm text-start">
                <p class="text-secondary fw-bold mb-4 text-uppercase border-bottom pb-2">
                    Admin panel / Add new bot
                </p>


                                <form action="{{ route('bots.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary text-uppercase">Bot name</label>
                            <input type="text" name="name" class="form-control border-0 shadow-sm" 
                                value="{{ old('name') }}" placeholder="Alpha" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary text-uppercase">Channel ID</label>
                            {{-- PRIDĖTA: old('discord_channel_id') --}}
                            <input type="text" name="discord_channel_id" class="form-control border-0 shadow-sm" 
                                value="{{ old('discord_channel_id') }}" placeholder="12345..." required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary text-uppercase">Token</label>
                            {{-- Tokenams old() paprastai nenaudojamas saugumo sumetimais, bet jei reikalavimas griežtas: --}}
                            <input type="password" name="token" class="form-control border-0 shadow-sm" placeholder="Secret" required>
                        </div>
                        <div class="col-md-3">
                            <label class="small fw-bold text-secondary text-uppercase">Category</label>
                            <select name="type" class="form-select border-0 shadow-sm" required>
                                {{-- PRIDĖTA: loginis patikrinimas, kuris pasirinkimas buvo pažymėtas --}}
                                <option value="SALES" {{ old('type') == 'SALES' ? 'selected' : '' }}>Purchasing bot</option>
                                <option value="MESSAGE" {{ old('type') == 'MESSAGE' ? 'selected' : '' }}>Messages bot</option>
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
@extends('layouts.app')

@section('content')

<h2 class="mb-4">{{ $election->title }}</h2>

<div class="mb-4">
    <a href="{{ route('urna', $election->id) }}" class="btn btn-success">
        🗳️ Ir para votação
    </a>
</div>

<h4>Chapas</h4>

<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>Número</th>
            <th>Líder</th>
            <th>Vice</th>
            <th>Fotos</th>
        </tr>
    </thead>
    <tbody>
        @forelse($election->tickets as $ticket)
        <tr>
            <td><strong>{{ $ticket->number }}</strong></td>
            <td>{{ $ticket->leader_name }}</td>
            <td>{{ $ticket->vice_name ?? '-' }}</td>

            <td>
                @if($ticket->leader_photo)
                    <img src="{{ asset('storage/'.$ticket->leader_photo) }}" width="40" class="me-1">
                @endif

                @if($ticket->vice_photo)
                    <img src="{{ asset('storage/'.$ticket->vice_photo) }}" width="40">
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="4" class="text-center text-muted">
                Nenhuma chapa cadastrada
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<hr>

<h4>Adicionar Chapa</h4>

<form method="POST" 
      action="{{ url('/election/'.$election->id.'/tickets') }}" 
      enctype="multipart/form-data"
      class="card p-4 shadow">
    @csrf

    <div class="row g-2">

        <div class="col-md-2">
            <input 
                type="text" 
                name="number" 
                class="form-control" 
                placeholder="Número"
                required
            >
        </div>

        <div class="col-md-4">
            <input 
                type="text" 
                name="leader_name" 
                class="form-control" 
                placeholder="Nome do Líder"
                required
            >
        </div>

        <div class="col-md-4">
            <input 
                type="text" 
                name="vice_name" 
                class="form-control" 
                placeholder="Nome do Vice"
            >
        </div>

        <div class="col-md-4 mt-2">
            <label class="form-label">Foto do Líder</label>
            <input 
                type="file" 
                name="leader_photo" 
                class="form-control"
            >
        </div>

        <div class="col-md-4 mt-2">
            <label class="form-label">Foto do Vice</label>
            <input 
                type="file" 
                name="vice_photo" 
                class="form-control"
            >
        </div>

    </div>

    <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">
            ➕ Adicionar
        </button>

        <a href="{{ route('elections.index') }}" class="btn btn-secondary">
            ← Voltar
        </a>
    </div>
</form>

@endsection
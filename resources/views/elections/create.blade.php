@extends('layouts.app')

@section('content')

<h2 class="mb-4">Criar Eleição</h2>

<form method="POST" action="{{ route('elections.store') }}" class="card p-4 shadow">
    @csrf

    <div class="mb-3">
        <label class="form-label">Título da eleição</label>
        <input 
            type="text" 
            name="title" 
            class="form-control @error('title') is-invalid @enderror"
            value="{{ old('title') }}"
            required
        >

        @error('title')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-flex gap-2">
        <button class="btn btn-success">
            💾 Salvar
        </button>

        <a href="{{ route('elections.index') }}" class="btn btn-secondary">
            ← Voltar
        </a>
    </div>
</form>

@endsection
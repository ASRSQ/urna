@extends('layouts.app')

@section('content')

<h2 class="mb-4">Eleições</h2>

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<table class="table table-bordered table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        @foreach($elections as $election)
        <tr>
            <td>{{ $election->id }}</td>
            <td>{{ $election->title }}</td>
            <td>

                <!-- VER -->
                <a href="{{ route('elections.show', $election->id) }}" 
                   class="btn btn-primary btn-sm">
                   Ver
                </a>

                <!-- EXCLUIR -->
                <form action="{{ route('elections.destroy', $election->id) }}" 
                      method="POST" 
                      style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                        Excluir
                    </button>
                </form>

                <!-- VOTAR -->
                <a href="{{ url('/urna/'.$election->id) }}" 
                   class="btn btn-success btn-sm">
                   Votar
                </a>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
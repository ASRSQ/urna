{{-- resources/views/votes/monitor.blade.php --}}

@extends('adminlte::page')

@section('title', 'Monitor de Votação')

@section('content_header')
    <h1>{{ $election->title }}</h1>
@stop

@section('content')

<div class="row">

    {{-- Último voto --}}
    <div class="col-md-12">
        <div class="card card-success">

            <div class="card-header">
                <h3 class="card-title">
                    Último voto registrado
                </h3>
            </div>

            <div class="card-body">

                @if($lastVote)

                    <div class="row">

                        <div class="col-md-4">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>
                                        {{ $lastVote->voter->registration ?? '---' }}
                                    </h3>

                                    <p>Matrícula</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h4>
                                        {{ $lastVote->voter->name ?? '---' }}
                                    </h4>

                                    <p>Nome</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>
                                        {{ $lastVote->created_at->format('H:i:s') }}
                                    </h3>

                                    <p>Horário</p>
                                </div>
                            </div>
                        </div>

                    </div>

                @else

                    <div class="alert alert-warning">
                        Nenhum voto registrado.
                    </div>

                @endif

            </div>
        </div>
    </div>

    {{-- Lista --}}
    <div class="col-md-12">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    Últimos votos
                </h3>
            </div>

            <div class="card-body table-responsive p-0">

                <table class="table table-hover text-nowrap">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Matrícula</th>
                            <th>Nome</th>
                            <th>Horário</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($votes as $vote)

                            <tr>

                                <td>
                                    {{ $vote->id }}
                                </td>

                                <td>
                                    {{ $vote->voter->registration ?? '---' }}
                                </td>

                                <td>
                                    {{ $vote->voter->name ?? '---' }}
                                </td>

                                <td>
                                    {{ $vote->created_at->format('d/m/Y H:i:s') }}
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="4" class="text-center">
                                    Nenhum voto encontrado.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>
        </div>

    </div>

</div>

@stop

@section('js')

<script>

    // Atualiza automaticamente a cada 5 segundos
    setTimeout(() => {
        location.reload();
    }, 5000);

</script>

@stop
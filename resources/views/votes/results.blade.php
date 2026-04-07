@extends('layouts.app')

@section('content')
<style>
@media print {

    /* 🧱 página deitada */
    @page {
        size: A4 landscape;
        margin: 10mm;
    }

    /* ❌ esconder coisas */
    .no-print {
        display: none !important;
    }

    nav {
        display: none !important;
    }

    form {
        display: none !important;
    }

    /* 🎨 manter cores (ESSENCIAL) */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* 🧼 limpar visual */
    body {
        background: white !important;
        font-size: 12px;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }

    .table {
        font-size: 11px;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    /* 🧱 layout dos filtros */
    .col-md-6 {
        width: 50% !important;
        float: left;
    }

}
</style>
<h2 class="mb-4">📊 Resultados da Eleição</h2>
<div class="mb-3 text-end no-print">
    <button onclick="window.print()" class="btn btn-dark">
        🖨️ Imprimir
    </button>
</div>

<!-- 🔎 FILTRO -->
<form method="GET" class="card p-3 mb-4 shadow-sm no-print">
    <div class="row g-2">

        <div class="col-md-3">
            <label>Início</label>
            <input type="datetime-local" name="start" class="form-control" value="{{ request('start') }}">
        </div>

        <div class="col-md-3">
            <label>Fim</label>
            <input type="datetime-local" name="end" class="form-control" value="{{ request('end') }}">
        </div>

        <div class="col-md-3">
            <label>Rótulo</label>
            <input type="text" name="label" class="form-control" placeholder="Ex: 1º Informática" required>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button class="btn btn-primary w-100">
                ➕ Criar Filtro
            </button>
        </div>

    </div>
</form>

<!-- 📊 RESUMO GERAL -->
<div class="row text-center mb-4">

    <div class="col-md-4">
        <div class="card bg-success text-white shadow">
            <div class="card-body">
                <h5>✅ Válidos</h5>
                <h2>{{ $data['validos'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-warning text-dark shadow">
            <div class="card-body">
                <h5>⚪ Brancos</h5>
                <h2>{{ $data['brancos'] }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-danger text-white shadow">
            <div class="card-body">
                <h5>❌ Nulos</h5>
                <h2>{{ $data['nulos'] }}</h2>
            </div>
        </div>
    </div>

</div>

<!-- 🏆 CHAPAS (GERAL) -->
<div class="card shadow mb-5">
    <div class="card-header bg-dark text-white">
        🏆 Ranking Geral
    </div>

    <div class="card-body p-0">
        <table class="table table-striped table-bordered mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Número</th>
                    <th>Líder</th>
                    <th>Vice</th>
                    <th>Votos</th>
                </tr>
            </thead>
            <tbody>

                @foreach($data['chapas'] as $index => $chapa)
                <tr class="{{ $index == 0 ? 'table-success' : '' }}">
                    <td>
                        @if($index == 0) 🥇
                        @elseif($index == 1) 🥈
                        @elseif($index == 2) 🥉
                        @else {{ $index+1 }}
                        @endif
                    </td>
                    <td><strong>{{ $chapa->ticket->number }}</strong></td>
                    <td>{{ $chapa->ticket->leader_name }}</td>
                    <td>{{ $chapa->ticket->vice_name ?? '-' }}</td>
                    <td><strong>{{ $chapa->total }}</strong></td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>

<!-- 🔥 SUB-RESULTADOS -->
@if(isset($subResultados) && count($subResultados))
<hr>

<h4 class="mb-3">📊 Resultados por Turma / Período</h4>

<div class="row">

@foreach($subResultados as $res)
<div class="col-md-6 mb-4">

    <div class="card shadow border-start border-primary border-4 h-100">
        <div class="card-body">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5 class="mb-1">📌 {{ $res['label'] }}</h5>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($res['start'])->format('H:i') }}
                        →
                        {{ \Carbon\Carbon::parse($res['end'])->format('H:i') }}
                    </small>
                </div>

                <!-- 🗑️ EXCLUIR -->
                <form method="POST" action="{{ route('filters.destroy', $res['id']) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">✖</button>
                </form>
            </div>

            <hr>

            <!-- RESUMO -->
            <div class="row text-center mb-3">

                <div class="col">
                    <div class="bg-success text-white rounded p-2">
                        ✅ {{ $res['validos'] }}
                    </div>
                </div>

                <div class="col">
                    <div class="bg-warning text-dark rounded p-2">
                        ⚪ {{ $res['brancos'] }}
                    </div>
                </div>

                <div class="col">
                    <div class="bg-danger text-white rounded p-2">
                        ❌ {{ $res['nulos'] }}
                    </div>
                </div>

            </div>

            <!-- 🏆 RANKING -->
            <table class="table table-sm table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Chapa</th>
                        <th>Votos</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($res['chapas'] as $index => $chapa)
                    <tr class="{{ $index == 0 ? 'table-success' : '' }}">
                        <td>
                            @if($index == 0) 🥇
                            @elseif($index == 1) 🥈
                            @elseif($index == 2) 🥉
                            @else {{ $index+1 }}
                            @endif
                        </td>
                        <td>{{ $chapa->ticket->leader_name }}</td>
                        <td><strong>{{ $chapa->total }}</strong></td>
                    </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
    </div>

</div>
@endforeach

</div>
@endif

@endsection
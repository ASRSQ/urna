<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Urna Web</title>

    <!-- CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4 no-print">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand" href="{{ route('elections.index') }}">
            🗳️ Urna Web
        </a>

        <!-- BOTÃO CRIAR -->
        <a href="{{ route('elections.create') }}" class="btn btn-success">
            + Nova Eleição
        </a>

    </div>
</nav>

<div class="container">
    @yield('content')
</div>

</body>
</html>
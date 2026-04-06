<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Urna - {{ $election->title }}</title>

  <!-- CSRF -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSS -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/tela.css') }}">

  <!-- ID DA ELEIÇÃO -->

<script>
  const BASE_URL = "{{ url('/') }}";
  const ELECTION_ID = {{ $election->id }};
</script>
  <!-- JS -->
  <script src="{{ asset('js/util.js') }}" defer></script>
  <script src="{{ asset('js/script.js') }}" defer></script>
</head>

<body>

  <h1 class="text-center mt-3">
    🗳️ Urna Eletrônica - {{ $election->title }}
  </h1>

  <div class="urna-area">
    <div class="urna">

      <!-- TELA -->
      <div class="tela">
        <div class="principal">

          <!-- ESQUERDA -->
          <div class="esquerda">
            <div class="rotulo r1">
              <span>Seu voto para</span>
            </div>

            <div class="rotulo r2">
              <span id="cargo">CHAPA</span>
            </div>

            <div class="rotulo r3"></div>

            <div class="rotulo r4">
              <div class="mensagem"></div>

              <p class="nome-candidato">
                Líder: <span id="nome">---</span>
              </p>

              <p class="nome-vice">
                Vice: <span id="vice">---</span>
              </p>
            </div>
          </div>

          <!-- DIREITA -->
          <div class="direita">
            <div class="candidato">
              <div class="imagem">
                <img id="foto" src="{{ asset('img/placeholder.png') }}" alt="Líder">
              </div>
              <div class="cargo">
                <p>Líder</p>
              </div>
            </div>

            <div class="candidato menor">
              <div class="imagem">
                <img id="fotoVice" src="{{ asset('img/placeholder.png') }}" alt="Vice">
              </div>
              <div class="cargo">
                <p>Vice</p>
              </div>
            </div>
          </div>

        </div>

        <!-- RODAPÉ -->
        <div class="rodape">
          <p>
            Aperte a tecla<br>
            CONFIRMA para CONFIRMAR este voto<br>
            CORRIGE para REINICIAR este voto.
          </p>
        </div>
      </div>

      <!-- LATERAL -->
      <div class="lateral">
        <div class="logoarea">
          <img src="{{ asset('img/brasao.png') }}" alt="Brasão">
          <h2>Sistema de Votação</h2>
        </div>

        <!-- TECLADO -->
        <div class="teclado">

          <div class="teclado--linha">
            <div class="teclado--botao" onclick="clicou('1')">1</div>
            <div class="teclado--botao" onclick="clicou('2')">2</div>
            <div class="teclado--botao" onclick="clicou('3')">3</div>
          </div>

          <div class="teclado--linha">
            <div class="teclado--botao" onclick="clicou('4')">4</div>
            <div class="teclado--botao" onclick="clicou('5')">5</div>
            <div class="teclado--botao" onclick="clicou('6')">6</div>
          </div>

          <div class="teclado--linha">
            <div class="teclado--botao" onclick="clicou('7')">7</div>
            <div class="teclado--botao" onclick="clicou('8')">8</div>
            <div class="teclado--botao" onclick="clicou('9')">9</div>
          </div>

          <div class="teclado--linha">
            <div class="teclado--botao" onclick="clicou('0')">0</div>
          </div>

          <div class="teclado--linha">
            <div class="teclado--botao especial branco" onclick="branco()">Branco</div>
            <div class="teclado--botao especial laranja" onclick="corrige()">Corrige</div>
            <div class="teclado--botao especial verde" onclick="confirma()">Confirma</div>
          </div>

        </div>
      </div>

    </div>
  </div>

</body>

</html>
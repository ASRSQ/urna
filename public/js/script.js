// ==============================
// SELETORES
// ==============================
const rCargo = document.querySelector('#cargo')
const numeros = document.querySelector('.r3')
const rMensagem = document.querySelector('.mensagem')
const rNome = document.querySelector('#nome')
const rVice = document.querySelector('#vice')

const imgCandidato = document.querySelector('#foto')
const imgVice = document.querySelector('#fotoVice')


// ==============================
// VARIÁVEIS
// ==============================
let etapas = []
let etapaAtual = 0
let numeroDigitado = ''
let votoEmBranco = false

// ==============================
// INICIAR (QUANDO CARREGAR)
// ==============================
window.addEventListener('DOMContentLoaded', () => {

  fetch(`${window.location.origin}${window.location.pathname.split('/urna')[0]}/urna-gremio/public/api/election/${ELECTION_ID}`)
    .then(res => res.json())
    .then(data => {
      console.log('API:', data)

      if (!data || data.length === 0) {
        alert('Nenhuma chapa cadastrada!')
        return
      }

      etapas = data
      comecarEtapa()
    })

})

// ==============================
// INICIAR ETAPA
// ==============================
function comecarEtapa() {

  let etapa = etapas[etapaAtual]

  numeroDigitado = ''
  votoEmBranco = false

  numeros.innerHTML = ''
  rMensagem.innerHTML = ''
  rNome.innerHTML = '---'
  rVice.innerHTML = '---'

  imgCandidato.src = '/img/placeholder.png'
  imgVice.src = '/img/placeholder.png'

  let total = etapa.numeros || 2

  for (let i = 0; i < total; i++) {
    numeros.innerHTML += `<div class="numero ${i === 0 ? 'pisca' : ''}"></div>`
  }

  rCargo.innerHTML = etapa.titulo || 'CHAPA'
}

// ==============================
// DIGITAR
// ==============================
function clicou(valor) {

  let el = document.querySelector('.numero.pisca')

  if (el && !votoEmBranco) {

    el.innerHTML = valor
    numeroDigitado += valor
    el.classList.remove('pisca')

    if (el.nextElementSibling) {
      el.nextElementSibling.classList.add('pisca')
    } else {
      atualizarInterface()
    }
  }
}

// ==============================
// ATUALIZAR INTERFACE
// ==============================
function atualizarInterface() {

  let etapa = etapas[etapaAtual]
  let candidato = etapa.candidatos[numeroDigitado]

  if (candidato) {

    rNome.innerHTML = candidato.nome

    // FOTO LÍDER
    imgCandidato.src = candidato.foto
      ? `${BASE_URL}/storage/${candidato.foto}`
      : `${BASE_URL}/img/placeholder.png`

    // VICE
    if (candidato.vice) {
      rVice.innerHTML = candidato.vice.nome

      imgVice.src = candidato.vice.foto
        ? `${BASE_URL}/storage/${candidato.vice.foto}`
        : `${BASE_URL}/img/placeholder.png`
    } else {
      rVice.innerHTML = '-'
      imgVice.src = `${BASE_URL}/img/placeholder.png`
    }

  } else {
    rMensagem.innerHTML = 'VOTO NULO'

    rNome.innerHTML = '---'
    rVice.innerHTML = '---'
    imgCandidato.src = `${BASE_URL}/img/placeholder.png`
    imgVice.src = `${BASE_URL}/img/placeholder.png`
  }
}
// ==============================
// BRANCO
// ==============================
function branco() {
  if (numeroDigitado === '') {
    votoEmBranco = true
    numeros.innerHTML = ''
    rMensagem.innerHTML = 'VOTO EM BRANCO'
    rNome.innerHTML = ''
    rVice.innerHTML = ''
  }
}

// ==============================
// CORRIGE
// ==============================
function corrige() {
  comecarEtapa()
}

// ==============================
// CONFIRMA
// ==============================
function confirma() {

  let etapa = etapas[etapaAtual]

  // ⚪ VOTO EM BRANCO (BOTÃO)
  if (votoEmBranco) {
    enviarVoto(null, true)
    return
  }

  if (numeroDigitado.length === etapa.numeros) {

    let candidato = etapa.candidatos[numeroDigitado]

    // ✅ VOTO VÁLIDO
    if (candidato) {
      enviarVoto(candidato.id, false)
    } 
    // ❌ VOTO NULO (digitou número inválido)
    else {
      enviarVoto(null, false)
    }

  } else {
    alert('Voto inválido')
  }
}
function enviarVoto(ticketId, branco) {

  let voterId = prompt("Digite sua matrícula (7 dígitos):")

  if (!voterId || !/^[0-9]{7}$/.test(voterId)) {
    alert("Código inválido! Use 7 dígitos.")
    return
  }

  fetch(`${BASE_URL}/vote`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content')
    },
    body: JSON.stringify({
      election_id: ELECTION_ID,
      ticket_id: ticketId,
      blank: branco,
      voter_id: voterId
    })
  })
  .then(res => res.json())
  .then(data => {

    if (data.error) {
      alert(data.error)
      return
    }

    document.querySelector('.tela').innerHTML = '<h1>FIM</h1>'
  })
}
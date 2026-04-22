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
// SONS
// ==============================
const somTecla = new Audio(`${BASE_URL}/audio/se1.mp3`)
const somErro = new Audio(`${BASE_URL}/audio/se2.mp3`)
const somConfirma = new Audio(`${BASE_URL}/audio/se3.mp3`)
// ==============================
// VARIÁVEIS
// ==============================
let etapas = []
let etapaAtual = 0
let numeroDigitado = ''
let votoEmBranco = false
let voterId = null

// ==============================
// INICIAR (QUANDO CARREGAR)
window.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modalVoter')
  const input = document.getElementById('inputVoter')

  modal.style.display = 'flex'

  if (input) {
    // 👇 garante foco mesmo com renderização
    setTimeout(() => {
      input.focus()
    }, 100)

    input.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault()
        e.stopPropagation() // 👈 ESSENCIAL
        confirmarVoter()
      }
    })
  }

  fetch(`${BASE_URL}/api/election/${ELECTION_ID}`)
    .then(res => res.json())
    .then(data => {
      if (!data || data.length === 0) {
        alert('Nenhuma chapa cadastrada!')
        return
      }

      etapas = data
      comecarEtapa()
    })
})
document.addEventListener('keydown', (e) => {

  // 👇 se está digitando no input, ignora
  if (e.target.id === 'inputVoter') return

  const modal = document.getElementById('modalVoter')

  // 🚫 se ainda está no login, não deixa votar
  if (modal && modal.style.display !== 'none') return

  // 🔢 números
  if (/^[0-9]$/.test(e.key)) {
    clicou(e.key)
  }

  // ⌫ corrigir
  if (e.key === 'Backspace') {
    e.preventDefault()
    corrige()
  }

  // ⏎ confirmar
  if (e.key === 'Enter') {
    confirma()
  }

})


function confirmarVoter() {
  let input = document.getElementById('inputVoter').value

  if (!/^[0-9]{7}$/.test(input)) {
    alert('Digite exatamente 7 dígitos')
    return
  }

  voterId = input
  document.getElementById('modalVoter').style.display = 'none'
}
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

  imgCandidato.src = '/img/placeholder.jpg'
  imgVice.src = '/img/placeholder.jpg'

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

  somTecla.currentTime = 0
  somTecla.play()

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
      : `${BASE_URL}/img/placeholder.jpg`

    // VICE
    if (candidato.vice) {
      rVice.innerHTML = candidato.vice.nome

      imgVice.src = candidato.vice.foto
        ? `${BASE_URL}/storage/${candidato.vice.foto}`
        : `${BASE_URL}/img/placeholder.jpg`
    } else {
      rVice.innerHTML = '-'
      imgVice.src = `${BASE_URL}/img/placeholder.jpg`
    }

  } else {
    rMensagem.innerHTML = 'VOTO NULO'

    rNome.innerHTML = '---'
    rVice.innerHTML = '---'
    imgCandidato.src = `${BASE_URL}/img/placeholder.jpg`
    imgVice.src = `${BASE_URL}/img/placeholder.jpg`
  }
}
// ==============================
// BRANCO
// ==============================
function branco() {
  if (numeroDigitado === '') {

    somTecla.currentTime = 0
    somTecla.play()

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

  somErro.currentTime = 0
  somErro.play()

  comecarEtapa()
}

// ==============================
// CONFIRMA
// ==============================
function confirma() {

  let etapa = etapas[etapaAtual]

  // ⚠️ nada digitado e não é branco
  if (!votoEmBranco && numeroDigitado.length === 0) {
    somErro.currentTime = 0
    somErro.play()

    mostrarAviso()
    return
  }

  // ⚪ branco
  if (votoEmBranco) {
    somConfirma.currentTime = 0
    somConfirma.play()

    enviarVoto(null, true)
    return
  }

  // ✅ número completo
  if (numeroDigitado.length === etapa.numeros) {

    let candidato = etapa.candidatos[numeroDigitado]

    somConfirma.currentTime = 0
    somConfirma.play()

    if (candidato) {
      enviarVoto(candidato.id, false)
    } else {
      enviarVoto(null, false) // nulo
    }

  } else {

    somErro.currentTime = 0
    somErro.play()

    alert('Voto inválido')
  }
}
function enviarVoto(ticketId, branco) {

  if (!voterId) {
    alert("Você precisa se identificar antes de votar.")
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

    document.querySelector('.tela').innerHTML = '<h1 class="fim">FIM</h1>'
  })


}

function mostrarAviso() {
  document.getElementById('modalAviso').style.display = 'flex'
}

function fecharAviso() {
  document.getElementById('modalAviso').style.display = 'none'
}
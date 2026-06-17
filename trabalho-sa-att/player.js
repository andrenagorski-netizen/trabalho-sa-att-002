let player;
let musicaAtual = -1;
let aleatorioAtivo = false;
let repetirAtivo = false;
let progressoIntervalo = null;
let volumeAntesDeMutar = 100;
let arrastandoProgresso = false;
let arrastandoVolume = false;

const musicas = window.wavifyMusicas || [];

document.addEventListener('DOMContentLoaded', configurarArrasteBarras);

function onYouTubeIframeAPIReady() {
    player = new YT.Player('youtube-player', {
        width: '1',
        height: '1',
        videoId: '',
        playerVars: {
            autoplay: 0,
            controls: 0,
            rel: 0
        },
        events: {
            onReady: () => {
                player.setVolume(100);
                atualizarVolumeVisual(100);
                atualizarIconePlayPause();
                iniciarAtualizacaoProgresso();
            },
            onStateChange: aoMudarEstadoPlayer
        }
    });
}

function tocarMusica(videoId, titulo, artista, thumbnail, indice = null, musicaId = null) {
    if (!player) return;

    if (indice !== null) {
        musicaAtual = Number(indice);
    } else {
        musicaAtual = musicas.findIndex((musica) => musica.youtube_id === videoId);
    }

    player.loadVideoById(videoId);
    player.playVideo();

    const tituloPlayer = document.getElementById('player-titulo');
    const artistaPlayer = document.getElementById('player-artista');
    const capaPlayer = document.getElementById('player-capa');

    if (tituloPlayer) tituloPlayer.innerText = titulo;
    if (artistaPlayer) artistaPlayer.innerText = artista;
    if (capaPlayer) capaPlayer.src = thumbnail;

    atualizarHeroMusica(titulo, artista, thumbnail);

    atualizarIconePlayPause();
    atualizarProgressoVisual(0, 0);
    registrarReproducao(musicaId);
}

function atualizarHeroMusica(titulo, artista, thumbnail) {
    const tituloHero = document.getElementById('hero-titulo');
    const artistaHero = document.getElementById('hero-artista');
    const capaHero = document.getElementById('hero-capa-fundo');

    if (tituloHero) tituloHero.innerText = titulo;
    if (artistaHero) artistaHero.innerText = artista;
    if (capaHero) capaHero.src = thumbnail;
}

function tocarMusicaPorIndice(indice) {
    if (!musicas.length) return;

    const total = musicas.length;
    const indiceNormalizado = ((indice % total) + total) % total;
    const musica = musicas[indiceNormalizado];

    tocarMusica(
        musica.youtube_id,
        musica.titulo,
        musica.artista,
        musica.thumbnail,
        indiceNormalizado,
        musica.id
    );
}

function registrarReproducao(musicaId) {
    if (!musicaId) return;

    const dados = new FormData();
    dados.append('musica_id', musicaId);

    fetch('php/registrar_reproducao.php', {
        method: 'POST',
        body: dados,
        credentials: 'same-origin'
    }).catch(() => {});
}

function ouvirTudo() {
    tocarMusicaPorIndice(0);
}

function tocarAleatorio() {
    if (!musicas.length) return;
    const indice = Math.floor(Math.random() * musicas.length);
    tocarMusicaPorIndice(indice);
}

function proximaMusica() {
    if (!musicas.length) return;

    if (aleatorioAtivo) {
        tocarAleatorio();
        return;
    }

    tocarMusicaPorIndice(musicaAtual + 1);
}

function musicaAnterior() {
    if (!player || !musicas.length) return;

    if (player.getCurrentTime && player.getCurrentTime() > 3) {
        player.seekTo(0, true);
        return;
    }

    tocarMusicaPorIndice(musicaAtual - 1);
}

function playPauseMusica() {
    if (!player) return;

    if (musicaAtual === -1 && musicas.length) {
        ouvirTudo();
        return;
    }

    const estado = player.getPlayerState();

    if (estado === YT.PlayerState.PLAYING) {
        player.pauseVideo();
    } else {
        player.playVideo();
    }

    setTimeout(atualizarIconePlayPause, 100);
}

function alternarAleatorio() {
    aleatorioAtivo = !aleatorioAtivo;
    alternarClasseAtiva('shuffle-button', aleatorioAtivo);
}

function alternarRepetir() {
    repetirAtivo = !repetirAtivo;
    alternarClasseAtiva('repeat-button', repetirAtivo);
}

function alternarClasseAtiva(id, ativo) {
    const botao = document.getElementById(id);
    if (!botao) return;
    botao.classList.toggle('text-primary', ativo);
    botao.classList.toggle('text-on-surface-variant', !ativo);
}

function aoMudarEstadoPlayer(event) {
    atualizarIconePlayPause();

    if (event.data === YT.PlayerState.PLAYING) {
        iniciarAtualizacaoProgresso();
    }

    if (event.data === YT.PlayerState.ENDED) {
        if (repetirAtivo) {
            player.seekTo(0, true);
            player.playVideo();
        } else {
            proximaMusica();
        }
    }
}

function atualizarIconePlayPause() {
    if (!player) return;

    const tocando = player.getPlayerState() === YT.PlayerState.PLAYING;
    const icones = [
        document.getElementById('icone-play-pause'),
        document.getElementById('hero-icone-play-pause')
    ].filter(Boolean);

    icones.forEach((icone) => {
        icone.innerText = tocando ? 'pause' : 'play_arrow';

        if (icone.id === 'hero-icone-play-pause') {
            icone.classList.toggle('ml-2', !tocando);
            icone.classList.toggle('ml-0', tocando);
        }

        if (icone.parentElement) {
            icone.parentElement.classList.toggle('animate-pulse-slow', tocando);
            icone.parentElement.setAttribute('aria-label', tocando ? 'Pausar musica' : 'Tocar musica');
        }
    });

    const statusHero = document.getElementById('hero-status');
    if (statusHero) {
        if (!musicas.length) {
            statusHero.innerText = 'Sem musica';
        } else {
            statusHero.innerText = tocando ? 'Tocando agora' : (musicaAtual === -1 ? 'Pronta para tocar' : 'Pausado');
        }
    }

    const waveformHero = document.getElementById('hero-waveform');
    if (waveformHero) {
        waveformHero.classList.toggle('opacity-80', tocando);
        waveformHero.classList.toggle('opacity-40', !tocando);
    }
}

function iniciarAtualizacaoProgresso() {
    if (progressoIntervalo) return;

    progressoIntervalo = setInterval(() => {
        if (!player || !player.getDuration) return;

        const duracao = player.getDuration() || 0;
        const atual = player.getCurrentTime() || 0;
        atualizarProgressoVisual(atual, duracao);
    }, 500);
}

function atualizarProgressoVisual(atual, duracao) {
    const tempoAtual = document.getElementById('tempo-atual');
    const tempoDuracao = document.getElementById('tempo-duracao');
    const progresso = document.getElementById('progresso-musica');
    const controle = document.getElementById('controle-progresso');

    if (tempoAtual) tempoAtual.innerText = formatarTempo(atual);
    if (tempoDuracao) tempoDuracao.innerText = formatarTempo(duracao);

    if (progresso) {
        const porcentagem = duracao > 0 ? (atual / duracao) * 100 : 0;
        const porcentagemLimitada = Math.min(Math.max(porcentagem, 0), 100);
        progresso.style.width = `${porcentagemLimitada}%`;
        if (controle) controle.style.left = `${porcentagemLimitada}%`;
    }
}

function alterarProgresso(event) {
    if (!player || !player.getDuration) return;

    const barra = document.getElementById('barra-progresso');
    const duracao = player.getDuration();
    if (!barra || !duracao) return;

    const porcentagem = calcularPorcentagemPonteiro(event, barra);
    player.seekTo(duracao * porcentagem, true);
}

function alterarVolume(event) {
    if (!player) return;

    const barra = document.getElementById('barra-volume');
    if (!barra) return;

    const porcentagem = calcularPorcentagemPonteiro(event, barra);
    const volume = Math.round(porcentagem * 100);

    player.setVolume(volume);
    if (volume > 0) {
        player.unMute();
        volumeAntesDeMutar = volume;
    }
    atualizarVolumeVisual(volume);
}

function configurarArrasteBarras() {
    const barraProgresso = document.getElementById('barra-progresso');
    const barraVolume = document.getElementById('barra-volume');

    if (barraProgresso) {
        barraProgresso.style.touchAction = 'none';
        barraProgresso.addEventListener('pointerdown', iniciarArrasteProgresso);
    }

    if (barraVolume) {
        barraVolume.style.touchAction = 'none';
        barraVolume.addEventListener('pointerdown', iniciarArrasteVolume);
    }

    document.addEventListener('pointermove', moverArraste);
    document.addEventListener('pointerup', finalizarArraste);
    document.addEventListener('pointercancel', finalizarArraste);
}

function iniciarArrasteProgresso(event) {
    arrastandoProgresso = true;
    document.body.classList.add('select-none');
    atualizarProgressoPeloPonteiro(event);
}

function iniciarArrasteVolume(event) {
    arrastandoVolume = true;
    document.body.classList.add('select-none');
    atualizarVolumePeloPonteiro(event);
}

function moverArraste(event) {
    if (arrastandoProgresso) {
        atualizarProgressoPeloPonteiro(event);
    }

    if (arrastandoVolume) {
        atualizarVolumePeloPonteiro(event);
    }
}

function finalizarArraste() {
    arrastandoProgresso = false;
    arrastandoVolume = false;
    document.body.classList.remove('select-none');
}

function atualizarProgressoPeloPonteiro(event) {
    if (!player || !player.getDuration) return;

    const barra = document.getElementById('barra-progresso');
    const duracao = player.getDuration();
    if (!barra || !duracao) return;

    event.preventDefault();

    const porcentagem = calcularPorcentagemPonteiro(event, barra);
    const novoTempo = duracao * porcentagem;

    atualizarProgressoVisual(novoTempo, duracao);
    player.seekTo(novoTempo, true);
}

function atualizarVolumePeloPonteiro(event) {
    if (!player) return;

    const barra = document.getElementById('barra-volume');
    if (!barra) return;

    event.preventDefault();

    const porcentagem = calcularPorcentagemPonteiro(event, barra);
    const volume = Math.round(porcentagem * 100);

    player.setVolume(volume);

    if (volume > 0) {
        player.unMute();
        volumeAntesDeMutar = volume;
    }

    atualizarVolumeVisual(volume);
}

function calcularPorcentagemPonteiro(event, barra) {
    const rect = barra.getBoundingClientRect();
    const posicao = (event.clientX - rect.left) / rect.width;
    return Math.max(0, Math.min(posicao, 1));
}

function alternarMudo() {
    if (!player) return;

    if (player.isMuted() || player.getVolume() === 0) {
        const volume = volumeAntesDeMutar || 100;
        player.unMute();
        player.setVolume(volume);
        atualizarVolumeVisual(volume);
    } else {
        volumeAntesDeMutar = player.getVolume();
        player.mute();
        atualizarVolumeVisual(0);
    }
}

function atualizarVolumeVisual(volume) {
    const nivel = document.getElementById('nivel-volume');
    const controle = document.getElementById('controle-volume');
    const icone = document.getElementById('icone-volume');
    const volumeLimitado = Math.min(Math.max(volume, 0), 100);

    if (nivel) nivel.style.width = `${volumeLimitado}%`;
    if (controle) controle.style.left = `${volumeLimitado}%`;
    if (!icone) return;

    if (volumeLimitado === 0) {
        icone.innerText = 'volume_off';
    } else if (volumeLimitado < 50) {
        icone.innerText = 'volume_down';
    } else {
        icone.innerText = 'volume_up';
    }
}

function formatarTempo(segundos) {
    const valor = Math.floor(segundos || 0);
    const minutos = Math.floor(valor / 60);
    const resto = String(valor % 60).padStart(2, '0');
    return `${minutos}:${resto}`;
}

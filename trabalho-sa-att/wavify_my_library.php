<?php

session_start();

require_once 'php/conexao.php';

$usuario_id = $_SESSION['usuario_id'];

$sql = "
SELECT *
FROM musicas
WHERE usuario_id = '$usuario_id'
ORDER BY id DESC
";

$resultado = mysqli_query($conexao, $sql);

$musicas = [];

while ($musica = mysqli_fetch_assoc($resultado)) {
    $musicas[] = $musica;
}

$capa_padrao = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAE8qW-12I3R5AbdBkyMrbYsfIWcnx5Hfw7KtsNTJPyMHUVYOT7iFXhM2dv-zHwAK_Skvb-MKyyDDkSYTrF7Cs-qwLmp2_Xe-RShetIuoBXsmXdFXlzOC-aqEMkwddXuICfYFY-_gYJcYek11EVxyz8Wp9q04wfaJa6jHxjspngBcOoZWnSlW12xjgXM5VwJ6v0mv7UqZwFbpgoTUbogyiccDWk-UaPs-kncYghGeyMUvFDowiUoxAKdFMOuHEYT0GMaVSe3kjDCZM';
$musica_inicial = count($musicas) > 0 ? $musicas[0] : null;

?>



<!DOCTYPE html>

<html class="dark" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WAVIFY - Minha Biblioteca</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&amp;family=DM+Sans:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                            "surface-variant": "#35343a",
                            "on-secondary-fixed": "#2a1700",
                            "on-primary-fixed-variant": "#5a00c6",
                            "on-tertiary-fixed": "#0f1c2c",
                            "surface-container-lowest": "#0e0e13",
                            "secondary-fixed-dim": "#ffb95f",
                            "on-secondary-container": "#5b3800",
                            "error": "#ffb4ab",
                            "surface-dim": "#131318",
                            "secondary": "#ffb95f",
                            "inverse-surface": "#e4e1e9",
                            "surface-bright": "#39383e",
                            "inverse-on-surface": "#303036",
                            "on-secondary-fixed-variant": "#653e00",
                            "on-error": "#690005",
                            "primary-fixed": "#eaddff",
                            "surface-container-low": "#1b1b20",
                            "primary": "#d2bbff",
                            "surface-container-highest": "#35343a",
                            "error-container": "#93000a",
                            "primary-container": "#7c3aed",
                            "on-surface": "#e4e1e9",
                            "primary-fixed-dim": "#d2bbff",
                            "tertiary-container": "#5a687a",
                            "inverse-primary": "#732ee4",
                            "tertiary-fixed": "#d6e4f9",
                            "surface": "#131318",
                            "on-primary-container": "#ede0ff",
                            "surface-container-high": "#2a292f",
                            "outline": "#958da1",
                            "on-surface-variant": "#ccc3d8",
                            "secondary-fixed": "#ffddb8",
                            "on-background": "#e4e1e9",
                            "tertiary": "#bac8dc",
                            "on-primary-fixed": "#25005a",
                            "on-tertiary-container": "#d9e7fc",
                            "surface-tint": "#d2bbff",
                            "outline-variant": "#4a4455",
                            "tertiary-fixed-dim": "#bac8dc",
                            "on-tertiary-fixed-variant": "#3a4859",
                            "on-primary": "#3f008e",
                            "on-tertiary": "#243141",
                            "secondary-container": "#ee9800",
                            "on-error-container": "#ffdad6",
                            "background": "#131318",
                            "on-secondary": "#472a00",
                            "surface-container": "#1f1f25"
                    },
                    "borderRadius": {
                            "DEFAULT": "0.25rem",
                            "lg": "0.5rem",
                            "xl": "0.75rem",
                            "full": "9999px"
                    },
                    "spacing": {
                            "gutter": "24px",
                            "player_height": "96px",
                            "margin_desktop": "40px",
                            "base_unit": "8px",
                            "sidebar_width": "260px",
                            "margin_mobile": "16px"
                    },
                    "fontFamily": {
                            "headline-sm": ["Syne"],
                            "body-md": ["DM Sans"],
                            "label-lg": ["DM Sans"],
                            "label-md": ["DM Sans"],
                            "headline-lg-mobile": ["Syne"],
                            "headline-lg": ["Syne"],
                            "body-sm": ["DM Sans"],
                            "body-lg": ["DM Sans"],
                            "headline-xl": ["Syne"],
                            "headline-md": ["Syne"]
                    },
                    "fontSize": {
                            "headline-sm": ["24px", {"lineHeight": "1.3", "fontWeight": "600"}],
                            "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                            "label-lg": ["14px", {"lineHeight": "1.2", "letterSpacing": "0.1em", "fontWeight": "700"}],
                            "label-md": ["12px", {"lineHeight": "1.2", "fontWeight": "500"}],
                            "headline-lg-mobile": ["36px", {"lineHeight": "1.2", "fontWeight": "700"}],
                            "headline-lg": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                            "body-sm": ["14px", {"lineHeight": "1.5", "fontWeight": "400"}],
                            "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}],
                            "headline-xl": ["64px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "800"}],
                            "headline-md": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}]
                    },
                    "animation": {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
            }
            }
        }
    </script>
<style>
        .mesh-gradient {
            background-color: #0A0A0F;
            background-image: 
                radial-gradient(at 0% 0%, rgba(124, 58, 237, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(13, 27, 42, 0.5) 0px, transparent 50%);
        }
    </style>
</head>
<body class="bg-background text-on-background mesh-gradient min-h-screen selection:bg-primary-container selection:text-on-primary-container">
<!-- TopNavBar -->
<nav class="hidden md:flex fixed top-0 right-0 w-[calc(100%-260px)] h-20 bg-background/40 backdrop-blur-md items-center justify-between px-10 z-40 transition-all">
<div class="flex items-center gap-4 flex-1">
<div class="relative w-96 group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
<input class="w-full bg-surface-variant/30 text-on-surface placeholder:text-on-surface-variant rounded-[24px] pl-10 pr-4 py-2 border border-white/5 focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container focus:shadow-[0_0_20px_rgba(124,58,237,0.2)] transition-all font-body-md text-body-md" placeholder="Search..." type="text"/>
</div>
</div>
<div class="flex items-center gap-6">
<button class="text-on-surface-variant hover:text-primary transition-colors focus:outline-none">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">notifications</span>
</button>
        <a href="wavify_upload.html" class="bg-surface-variant/50 hover:bg-surface-variant border border-white/10 px-6 py-2 rounded-full font-label-lg text-label-lg text-primary transition-all shadow-[0_0_15px_rgba(124,58,237,0.1)] hover:shadow-[0_0_20px_rgba(124,58,237,0.3)]">
            Upload
        </a>
        <a href="wavify_profile.html" class="w-10 h-10 rounded-full bg-surface-container overflow-hidden border border-white/10 hover:border-primary transition-colors cursor-pointer">
            <img id="header-profile-img" alt="User Profile Avatar" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDXRrx6d78_JeqnP86C-1PpQGgvkZuYeWjL5zLkuOPIIl08ZYxQhEBCUZOeo5mudVUsht1Sjv_3gymFFY5KdEXv6YPJ4PquDHqYwztAMRa85LgYmUn188PUtQ9pC23ZQiOQkFstIyNQvgn99zAfgicmp0N9djG6D3x0Or9hW0cnAk5EPysbGitXE1OYHQgyqhM2ib8b81wgYqQM05u__6-J6cIBNyo3CJYDa0ycp-ryol2spkwtezbwZQGEiSumVpTveKIhFHv_H2U"/>
        </a>
</div>
</nav>
<!-- SideNavBar -->
<aside class="hidden md:flex fixed left-0 top-0 h-full w-[260px] bg-surface/80 backdrop-blur-xl border-r border-white/10 shadow-2xl shadow-primary/20 flex-col p-6 gap-8 z-50">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-gradient-to-br from-primary-container to-secondary flex items-center justify-center shadow-[0_0_15px_rgba(124,58,237,0.3)]">
<span class="material-symbols-outlined text-white" style="font-variation-settings: 'FILL' 1;">graphic_eq</span>
</div>
<div>
<h1 class="font-headline-md text-headline-md font-bold text-primary tracking-tighter">WAVIFY</h1>
<p class="font-label-md text-label-md text-on-surface-variant">Áudio Premium</p>
</div>
</div>
<nav class="flex flex-col gap-2 flex-1">
<a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:bg-white/5 hover:text-on-surface transition-all duration-300 font-label-lg text-label-lg group" href="wavify_home.php">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">home</span>
                Início
            </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:bg-white/5 hover:text-on-surface transition-all duration-300 font-label-lg text-label-lg group" href="wavify_discover.html">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">explore</span>
                Descobrir
            </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-full bg-gradient-to-r from-primary to-secondary text-on-primary-fixed border-l-2 border-primary shadow-[0_0_15px_rgba(124,58,237,0.5)] transition-transform scale-95 font-label-lg text-label-lg" href="wavify_my_library.php">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">library_music</span>
                Minha Biblioteca
            </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:bg-white/5 hover:text-on-surface transition-all duration-300 font-label-lg text-label-lg group" href="#">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">playlist_play</span>
                Playlists
            </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:bg-white/5 hover:text-on-surface transition-all duration-300 font-label-lg text-label-lg group" href="wavify_upload.html">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">cloud_upload</span>
                Upload
            </a>
</nav>
<div class="mt-auto flex flex-col gap-2 pt-6 border-t border-white/5">
<button class="w-full py-3 rounded-full bg-gradient-to-r from-primary-container to-inverse-primary text-white font-label-lg text-label-lg shadow-[0_0_20px_rgba(124,58,237,0.4)] hover:shadow-[0_0_30px_rgba(124,58,237,0.6)] hover:scale-[1.02] transition-all">
                Enviar Faixa
            </button>
<a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:bg-white/5 hover:text-on-surface transition-all duration-300 font-label-lg text-label-lg mt-4 group" href="wavify_profile.html">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">settings</span>
                Configurações
            </a>
<a class="flex items-center gap-4 px-4 py-3 rounded-full text-on-surface-variant hover:bg-white/5 hover:text-error transition-all duration-300 font-label-lg text-label-lg group" href="index.php">
<span class="material-symbols-outlined group-hover:scale-110 transition-transform">logout</span>
                Sair
            </a>
</div>
</aside>
<!-- Main Content Canvas -->
<main class="md:ml-[260px] pt-24 md:pt-32 pb-[120px] px-margin_mobile md:px-margin_desktop min-h-screen">
<!-- Library Header -->
<header class="mb-10">
<h1 class="font-headline-xl text-headline-xl text-on-surface mb-6">Minha Biblioteca</h1>
<!-- Tabs -->
<div class="flex gap-8 border-b border-white/10">
<button class="pb-4 font-label-lg text-label-lg text-primary border-b-2 border-primary shadow-[0_10px_20px_-10px_rgba(124,58,237,0.5)] tracking-widest">
                    CURTIDAS
                </button>
<button class="pb-4 font-label-lg text-label-lg text-on-surface-variant hover:text-on-surface transition-colors tracking-widest">
                    PLAYLISTS
                </button>
<button class="pb-4 font-label-lg text-label-lg text-on-surface-variant hover:text-on-surface transition-colors tracking-widest">
                    ÁLBUNS
                </button>
<button class="pb-4 font-label-lg text-label-lg text-on-surface-variant hover:text-on-surface transition-colors tracking-widest">
                    ARTISTAS
                </button>
</div>
</header>



<!-- Adicionar músicas -->
<section class="mb-10">
    <div class="flex items-center gap-3 mb-6">
        <span class="material-symbols-outlined text-primary text-[28px]" style="font-variation-settings: 'FILL' 1;">add_circle</span>
        <h2 class="font-headline-md text-headline-md text-on-surface">Adicionar Música</h2>
    </div>
    <div class="bg-surface-container-low/50 backdrop-blur-md rounded-2xl border border-white/5 p-8 relative overflow-hidden">
        <!-- Decorative glow -->
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/10 rounded-full blur-3xl pointer-events-none"></div>
        <p class="font-body-md text-body-md text-on-surface-variant mb-6">Cole o link de um vídeo do YouTube para adicionar à sua biblioteca.</p>
        <form action="adicionar_musica.php" method="POST" class="flex flex-col gap-4 relative z-10">

    <div class="relative group">
        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors text-[20px]">
            link
        </span>

        <input
            type="text"
            name="youtube_link"
            id="youtube_link"
            placeholder="https://www.youtube.com/watch?v=..."
            class="w-full bg-surface-container/80 border border-white/10 focus:border-primary/60 focus:ring-2 focus:ring-primary/30 rounded-full py-3 pl-12 pr-5 text-on-surface placeholder:text-on-surface-variant/40 font-body-md text-body-md outline-none transition-all duration-300 shadow-inner"
        />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <input
            type="text"
            name="titulo"
            placeholder="Nome da música"
            class="w-full bg-surface-container/80 border border-white/10 focus:border-primary/60 focus:ring-2 focus:ring-primary/30 rounded-full py-3 px-5 text-on-surface placeholder:text-on-surface-variant/40 font-body-md text-body-md outline-none transition-all duration-300 shadow-inner"
        />

        <input
            type="text"
            name="artista"
            placeholder="Nome do artista"
            class="w-full bg-surface-container/80 border border-white/10 focus:border-primary/60 focus:ring-2 focus:ring-primary/30 rounded-full py-3 px-5 text-on-surface placeholder:text-on-surface-variant/40 font-body-md text-body-md outline-none transition-all duration-300 shadow-inner"
        />

    </div>

    <button
        type="submit"
        class="flex items-center justify-center gap-2 px-8 py-3 rounded-full bg-gradient-to-r from-primary-container to-inverse-primary text-white font-label-lg text-label-lg shadow-[0_0_20px_rgba(124,58,237,0.4)] hover:shadow-[0_0_30px_rgba(124,58,237,0.7)] hover:scale-[1.03] active:scale-[0.98] transition-all duration-300"
    >
        <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">
            add
        </span>

        Adicionar Música
    </button>

</form>
    </div>
</section>  



<!-- Actions -->
<div class="flex gap-4 mb-8">
<button onclick="ouvirTudo()" class="flex items-center gap-2 px-8 py-3 rounded-full bg-gradient-to-r from-primary-container to-inverse-primary text-white font-label-lg text-label-lg shadow-[0_0_20px_rgba(124,58,237,0.4)] hover:shadow-[0_0_30px_rgba(124,58,237,0.6)] hover:scale-[1.02] transition-all">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                Ouvir Tudo
            </button>
<button onclick="tocarAleatorio()" class="flex items-center gap-2 px-8 py-3 rounded-full bg-transparent border border-primary/50 text-primary font-label-lg text-label-lg hover:bg-primary/10 hover:shadow-[0_0_15px_rgba(124,58,237,0.2)] transition-all">
<span class="material-symbols-outlined">shuffle</span>
                Aleatório
            </button>
</div>



<!-- Track List -->
<div class="bg-surface-container-low/50 backdrop-blur-md rounded-xl border border-white/5 overflow-hidden">
<!-- Table Header -->
<div class="grid grid-cols-[auto_1fr_1fr_1fr_auto_auto] gap-4 px-6 py-4 border-b border-white/5 text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
<div class="w-8 text-center">#</div>
<div>Título</div>
<div class="hidden md:block">Artista</div>
<div class="hidden lg:block">Álbum</div>
<div class="hidden xl:block">Adicionado em</div>
<div class="w-16 text-right">
<span class="material-symbols-outlined text-[16px]">schedule</span>
</div>
</div>


<!-- List -->
<div class="flex flex-col">

<?php foreach($musicas as $indice => $musica): ?>

<div
class="group grid grid-cols-[auto_1fr_1fr_1fr_auto_auto] gap-4 px-6 py-3 items-center hover:bg-white/5 transition-colors cursor-pointer border-b border-white/5 last:border-0 relative overflow-hidden"

onclick="tocarMusicaPorIndice(<?= $indice ?>)"
>

    <div class="w-8 text-center">
        <?= $musica['id'] ?>
    </div>

    <div class="flex items-center gap-4">

        <div class="w-12 h-12 rounded overflow-hidden">
            <img
                src="<?= htmlspecialchars($musica['thumbnail']) ?>"
                class="w-full h-full object-cover">
        </div>

        <div class="flex flex-col">

            <span class="font-body-md text-on-surface">
                <?= htmlspecialchars($musica['titulo']) ?>
            </span>

            <span class="font-body-sm text-on-surface-variant">
                <?= htmlspecialchars($musica['artista']) ?>
            </span>

        </div>

    </div>

    <div class="hidden md:block">
        <?= htmlspecialchars($musica['artista']) ?>
    </div>

    <div class="hidden lg:block">
        YouTube
    </div>

    <div class="hidden xl:block">
        <?= $musica['data_adicao'] ?>
    </div>

    <div class="w-16 text-right">
        ▶
    </div>

</div>

<?php endforeach; ?>

</div>



<!-- BottomNavBar (Player Bar) -->

<footer class="fixed bottom-0 left-0 w-full h-[96px] z-50 bg-surface-container/90 backdrop-blur-2xl border-t border-white/10 shadow-[0_-10px_40px_rgba(0,0,0,0.5)] flex items-center justify-between px-4 md:px-8">
<!-- Song Info -->
<div class="flex items-center gap-4 w-1/3 min-w-[200px]">
<div class="contents">

<img 
id="player-capa"
alt="Capa da musica atual"
class="w-14 h-14 rounded-md object-cover hidden sm:block"
data-alt="Currently playing track cover art."
src="<?= htmlspecialchars($musica_inicial ? $musica_inicial['thumbnail'] : $capa_padrao) ?>"/>


<div class="hidden">
<span class="material-symbols-outlined text-white">keyboard_arrow_up</span>
</div>
</div>
<div class="flex flex-col min-w-0">

<a 
id="player-titulo"
class="font-label-lg text-label-lg text-on-surface hover:underline truncate" 
href="#"
>
<?= htmlspecialchars($musica_inicial ? $musica_inicial['titulo'] : 'Nenhuma musica') ?>
</a>


<a 
id="player-artista"
class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors truncate" 
href="#"
>
<?= htmlspecialchars($musica_inicial ? $musica_inicial['artista'] : 'Toque algo na biblioteca') ?>
</a>
</div>

<button class="text-on-surface-variant hover:text-primary ml-2 hidden lg:block">
<span class="material-symbols-outlined text-lg">favorite_border</span>
</button>
</div>
<!-- Controls -->
<div class="flex flex-col items-center justify-center w-1/3 max-w-md">
<div class="flex items-center gap-6 mb-2">
<button id="shuffle-button" onclick="alternarAleatorio()" class="text-on-surface-variant hover:text-on-surface transition-colors hidden sm:block">
<span class="material-symbols-outlined">shuffle</span>
</button>
<button onclick="musicaAnterior()" class="text-on-surface-variant hover:text-on-surface transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">skip_previous</span>
</button>

<!-- Botão principal -->
<button
class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-on-primary-fixed shadow-[0_0_20px_rgba(124,58,237,0.6)] scale-110 hover:scale-110 transition-transform duration-200"
onclick="playPauseMusica()"
>

<span
id="icone-play-pause"

class="material-symbols-outlined text-2xl"
style="font-variation-settings: 'FILL' 1;"

>
play_arrow
</span>

</button>


<button onclick="proximaMusica()" class="text-on-surface-variant hover:text-on-surface transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">skip_next</span>
</button>
<button id="repeat-button" onclick="alternarRepetir()" class="text-on-surface-variant hover:text-on-surface transition-colors hidden sm:block">
<span class="material-symbols-outlined">repeat</span>
</button>
</div>
<div class="flex items-center w-full gap-3 font-body-sm text-body-sm text-on-surface-variant text-[11px]">
<span id="tempo-atual">0:00</span>
<div id="barra-progresso" onclick="alterarProgresso(event)" class="flex-1 h-1.5 rounded-full bg-white/10 cursor-pointer group relative">
<div id="progresso-musica" class="absolute top-0 left-0 h-full w-0 rounded-full bg-primary group-hover:bg-primary-container transition-colors"></div>
<div id="controle-progresso" class="absolute left-0 top-1/2 -translate-x-1/2 -translate-y-1/2 w-3.5 h-3.5 bg-white rounded-full border-2 border-primary shadow-[0_0_10px_rgba(124,58,237,0.8)] transition-transform group-hover:scale-110"></div>
</div>
<span id="tempo-duracao">0:00</span>
</div>
</div>
<!-- Utility -->
<div class="flex items-center justify-end gap-4 w-1/3 min-w-[150px]">
<button class="text-on-surface-variant hover:text-on-surface hover:scale-105 transition-all">
<span class="material-symbols-outlined text-[20px]">mic</span>
</button>
<button class="text-on-surface-variant hover:text-on-surface hover:scale-105 transition-all">
<span class="material-symbols-outlined text-[20px]">queue_music</span>
</button>
<div class="flex items-center gap-2 w-24 group">
<button onclick="alternarMudo()" class="text-on-surface-variant hover:text-on-surface transition-colors">
<span id="icone-volume" class="material-symbols-outlined text-[20px]">volume_up</span>
</button>
<div id="barra-volume" onclick="alterarVolume(event)" class="flex-1 h-1.5 rounded-full bg-white/10 cursor-pointer relative">
<div id="nivel-volume" class="h-full w-full rounded-full bg-on-surface-variant group-hover:bg-primary transition-colors"></div>
<div id="controle-volume" class="absolute left-full top-1/2 -translate-x-1/2 -translate-y-1/2 w-3 h-3 bg-white rounded-full border-2 border-on-surface-variant shadow-[0_0_8px_rgba(255,255,255,0.35)] transition-transform group-hover:scale-110"></div>
</div>
</div>
</div>
</footer>
    
<div
    id="youtube-player"
    style="
        position: fixed;
        left: -9999px;
        top: -9999px;
        width: 1px;
        height: 1px;
        overflow: hidden;
    ">
</div>

<script>
window.wavifyMusicas = <?= json_encode(array_map(function ($musica) {
    return [
        'id' => $musica['id'],
        'youtube_id' => $musica['youtube_id'],
        'titulo' => $musica['titulo'],
        'artista' => $musica['artista'],
        'thumbnail' => $musica['thumbnail'],
    ];
}, $musicas), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
</script>

<script src="player.js"></script>

<script src="https://www.youtube.com/iframe_api"></script>

<script src="main.js"></script>



</body>
</html>

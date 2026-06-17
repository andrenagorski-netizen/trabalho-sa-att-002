<?php

session_start();
if(!isset($_SESSION['usuario_id'])){
    header("Location: ../wavify_login.php");
    exit;
}



require_once "php/conexao.php";

$usuario_id = $_SESSION['usuario_id'];

mysqli_query($conexao, "
CREATE TABLE IF NOT EXISTS reproducoes_recentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    musica_id INT NOT NULL,
    tocada_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY usuario_musica (usuario_id, musica_id)
)
");

$sql = "SELECT * FROM musicas WHERE usuario_id = '$usuario_id'";

$resultado = mysqli_query($conexao, $sql);

$musicas = [];

while($musica = mysqli_fetch_assoc($resultado)){
    $musicas[] = $musica;
}

$sql_recentes = "
SELECT m.*
FROM reproducoes_recentes r
INNER JOIN musicas m ON m.id = r.musica_id
WHERE r.usuario_id = '$usuario_id'
AND m.usuario_id = '$usuario_id'
ORDER BY r.tocada_em DESC
LIMIT 10
";

$resultado_recentes = mysqli_query($conexao, $sql_recentes);
$musicas_recentes = [];

while($musica_recente = mysqli_fetch_assoc($resultado_recentes)){
    $musicas_recentes[] = $musica_recente;
}

$capa_padrao = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAE8qW-12I3R5AbdBkyMrbYsfIWcnx5Hfw7KtsNTJPyMHUVYOT7iFXhM2dv-zHwAK_Skvb-MKyyDDkSYTrF7Cs-qwLmp2_Xe-RShetIuoBXsmXdFXlzOC-aqEMkwddXuICfYFY-_gYJcYek11EVxyz8Wp9q04wfaJa6jHxjspngBcOoZWnSlW12xjgXM5VwJ6v0mv7UqZwFbpgoTUbogyiccDWk-UaPs-kncYghGeyMUvFDowiUoxAKdFMOuHEYT0GMaVSe3kjDCZM';
$musica_destaque = count($musicas_recentes) > 0 ? $musicas_recentes[0] : null;

?>

<!DOCTYPE html>

<html class="dark" lang="pt-br"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>WAVIFY - Home</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;500;700&amp;family=Syne:wght@600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
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
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "gutter": "24px",
                        "player_height": "96px",
                        "margin_desktop": "40px",
                        "base_unit": "8px",
                        "sidebar_width": "260px",
                        "margin_mobile": "16px"
                    },
                    fontFamily: {
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
                    fontSize: {
                        "headline-sm": ["24px", { lineHeight: "1.3", fontWeight: "600" }],
                        "body-md": ["16px", { lineHeight: "1.6", fontWeight: "400" }],
                        "label-lg": ["14px", { lineHeight: "1.2", letterSpacing: "0.1em", fontWeight: "700" }],
                        "label-md": ["12px", { lineHeight: "1.2", fontWeight: "500" }],
                        "headline-lg-mobile": ["36px", { lineHeight: "1.2", fontWeight: "700" }],
                        "headline-lg": ["48px", { lineHeight: "1.2", letterSpacing: "-0.01em", fontWeight: "700" }],
                        "body-sm": ["14px", { lineHeight: "1.5", fontWeight: "400" }],
                        "body-lg": ["18px", { lineHeight: "1.6", fontWeight: "400" }],
                        "headline-xl": ["64px", { lineHeight: "1.1", letterSpacing: "-0.02em", fontWeight: "800" }],
                        "headline-md": ["32px", { lineHeight: "1.2", fontWeight: "700" }]
                    },
                    animation: {
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'mesh-gradient': 'mesh 15s ease infinite',
                    },
                    keyframes: {
                        mesh: {
                            '0%, 100%': { 'background-position': '0% 50%' },
                            '50%': { 'background-position': '100% 50%' },
                        }
                    }
                }
            }
        }
    </script>
<style>
        .mesh-bg {
            background: linear-gradient(-45deg, #0A0A0F, #131318, #0D1B2A, #1a1025);
            background-size: 400% 400%;
            animation: mesh 15s ease infinite;
        }
        @keyframes mesh {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen mesh-bg selection:bg-primary-container selection:text-on-primary-container">
<!-- TopNavBar (Web Only) -->
<nav class="hidden md:flex bg-background/40 backdrop-blur-md fixed top-0 right-0 w-[calc(100%-260px)] h-20 items-center justify-between px-10 z-40 transition-all duration-300">
<div class="flex-1 max-w-xl">
<div class="relative group focus-within:ring-2 focus-within:ring-primary focus-within:shadow-[0_0_20px_rgba(124,58,237,0.4)] rounded-full transition-all duration-300 bg-white/5">
<span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">search</span>
<input class="w-full bg-transparent border-none py-3 pl-12 pr-4 rounded-full text-on-surface placeholder:text-on-surface-variant/50 focus:ring-0 font-body-sm" placeholder="Buscar faixas, artistas, álbuns..." type="text"/>
</div>
</div>
<div class="flex items-center gap-6 ml-8">
<button class="text-on-surface-variant hover:text-primary transition-colors relative group">
<span class="material-symbols-outlined">notifications</span>
<span class="absolute top-0 right-0 w-2 h-2 bg-primary rounded-full shadow-[0_0_8px_rgba(124,58,237,0.8)]"></span>
</button>
        <a href="wavify_upload.html" class="bg-gradient-to-r from-primary to-secondary text-on-primary-fixed font-label-md text-label-md px-6 py-2 rounded-full hover:scale-105 transition-transform duration-300 shadow-[0_0_15px_rgba(124,58,237,0.3)] hover:shadow-[0_0_20px_rgba(124,58,237,0.6)]">
            Upload
        </a>
        <a href="wavify_profile.html" class="w-10 h-10 rounded-full overflow-hidden border border-white/10 hover:border-primary transition-colors cursor-pointer shrink-0">
            <img id="header-profile-img" alt="User Profile Avatar" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBCocV5HrLtEgw93YC8-9gBUa8oK_RlDwmftAqIwtQK0pzKWgmoL37qgVhJ9h11mvCgngvgpVZtYCbol8X7NqGq1xPE8v5ucNSkpbDqkTlWY8bQzra2w8DZFMSyZD4_qrkoxdNEAwUQIHE_grh9XnUYyrEiIG0r2L-LdAcbnjZHJverIAzuDwe3DNcgdVh6mHjcV0vsa-C1somLph2WLk2KRHrt_j9jy-8G9uyZqeKEANaFZmLAEQ_tLIVtLthqExlde2GgsRaJUdM"/>
        </a>
</div>
</nav>
<!-- SideNavBar (Web Only) -->
<aside class="hidden md:flex flex-col bg-surface/80 backdrop-blur-xl fixed left-0 top-0 h-full w-[260px] border-r border-white/10 shadow-2xl shadow-primary/20 p-6 gap-8 z-50">
<div class="flex items-center gap-4">
<div class="w-10 h-10 bg-primary-container rounded-xl flex items-center justify-center shadow-[0_0_15px_rgba(124,58,237,0.4)]">
<span class="material-symbols-outlined text-on-primary-container" style="font-variation-settings: 'FILL' 1;">graphic_eq</span>
</div>
<div>
<h1 class="font-headline-md text-headline-md font-bold text-primary tracking-tighter">WAVIFY</h1>
<p class="font-label-md text-label-md text-on-surface-variant opacity-70 uppercase tracking-widest text-[10px]">Áudio Premium</p>
</div>
</div>
<div class="flex-1 flex flex-col gap-2 mt-4">
<a class="flex items-center gap-4 px-4 py-3 bg-gradient-to-r from-primary to-secondary text-on-primary-fixed rounded-full border-l-2 border-primary shadow-[0_0_15px_rgba(124,58,237,0.5)] scale-95 transition-transform font-label-lg text-label-lg group cursor-pointer" href="wavify_home.php">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">home</span>
<span>Home</span>
</a>
<a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-white/5 rounded-full transition-all duration-300 font-label-lg text-label-lg cursor-pointer" href="wavify_discover.html">
<span class="material-symbols-outlined">explore</span>
<span>Descobrir</span>
</a>
<a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-white/5 rounded-full transition-all duration-300 font-label-lg text-label-lg cursor-pointer" href="wavify_my_library.php">
<span class="material-symbols-outlined">library_music</span>
<span>Minha Biblioteca</span>
</a>
<a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-white/5 rounded-full transition-all duration-300 font-label-lg text-label-lg cursor-pointer" href="#">
<span class="material-symbols-outlined">playlist_play</span>
<span>Playlists</span>
</a>
<a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-white/5 rounded-full transition-all duration-300 font-label-lg text-label-lg cursor-pointer" href="wavify_upload.html">
<span class="material-symbols-outlined">cloud_upload</span>
<span>Upload</span>
</a>
</div>
<div class="mt-auto flex flex-col gap-2 border-t border-white/5 pt-6">
<a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-on-surface hover:bg-white/5 rounded-full transition-all duration-300 font-label-lg text-label-lg cursor-pointer" href="wavify_profile.html">
<span class="material-symbols-outlined">settings</span>
<span>Configurações</span>
</a>
<a class="flex items-center gap-4 px-4 py-3 text-on-surface-variant hover:text-error hover:bg-error/10 rounded-full transition-all duration-300 font-label-lg text-label-lg cursor-pointer" href="index.php">
<span class="material-symbols-outlined">logout</span>
<span>Sair</span>
</a>
</div>
</aside>
<!-- Main Content Area -->
<main class="md:ml-[260px] pb-[120px] md:pt-24 min-h-screen px-4 md:px-10">
<!-- Mobile Header (Visible only on small screens) -->
<header class="md:hidden flex items-center justify-between py-6">
<h1 class="font-headline-lg-mobile text-headline-lg-mobile font-bold text-primary tracking-tighter">WAVIFY</h1>
        <a href="wavify_profile.html" class="w-10 h-10 rounded-full overflow-hidden border border-white/10 shrink-0">
            <img id="mobile-header-profile-img" alt="User Profile Avatar" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBCocV5HrLtEgw93YC8-9gBUa8oK_RlDwmftAqIwtQK0pzKWgmoL37qgVhJ9h11mvCgngvgpVZtYCbol8X7NqGq1xPE8v5ucNSkpbDqkTlWY8bQzra2w8DZFMSyZD4_qrkoxdNEAwUQIHE_grh9XnUYyrEiIG0r2L-LdAcbnjZHJverIAzuDwe3DNcgdVh6mHjcV0vsa-C1somLph2WLk2KRHrt_j9jy-8G9uyZqeKEANaFZmLAEQ_tLIVtLthqExlde2GgsRaJUdM"/>
        </a>
</header>
<!-- Featured Hero Section -->
<section class="mb-12 pt-4 md:pt-0">
<div class="relative w-full h-[400px] md:h-[500px] rounded-2xl overflow-hidden group">
<img id="hero-capa-fundo" alt="Capa da musica em destaque" class="absolute inset-0 w-full h-full object-cover scale-105 opacity-75 group-hover:scale-110 transition-transform duration-700" src="<?= htmlspecialchars($musica_destaque ? $musica_destaque['thumbnail'] : $capa_padrao) ?>"/>
<div class="absolute inset-0 bg-gradient-to-t from-background via-background/45 to-background/10"></div>
<div class="absolute inset-0 bg-black/25"></div>
<div class="absolute inset-0 p-8 md:p-12 flex flex-col justify-end">
<div class="flex items-end justify-between gap-6">
<div class="flex-1 max-w-3xl">
<span id="hero-status" class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md rounded-full font-label-md text-label-md text-primary mb-4 border border-white/10 uppercase tracking-widest"><?= $musica_destaque ? 'Pronta para tocar' : 'Sem musica' ?></span>
<h2 id="hero-titulo" class="font-headline-xl text-headline-xl text-on-surface mb-2 tracking-tight group-hover:text-primary transition-colors duration-500 truncate"><?= htmlspecialchars($musica_destaque ? $musica_destaque['titulo'] : 'Nenhuma musica') ?></h2>
<p class="font-headline-sm text-headline-sm text-on-surface-variant flex items-center gap-2 min-w-0">
<span id="hero-artista" class="truncate"><?= htmlspecialchars($musica_destaque ? $musica_destaque['artista'] : 'Toque algo na biblioteca') ?></span>
                                <span class="material-symbols-outlined text-primary text-xl" style="font-variation-settings: 'FILL' 1;">verified</span>
</p>
</div>
<button onclick="playPauseMusica()" class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-br from-primary to-primary-container rounded-full flex items-center justify-center shrink-0 hover:scale-110 transition-transform duration-300 shadow-[0_0_30px_rgba(124,58,237,0.5)] group-hover:shadow-[0_0_50px_rgba(124,58,237,0.8)] relative" aria-label="Pausar ou tocar musica em destaque">
<span class="absolute inset-0 rounded-full border border-white/30 animate-pulse-slow"></span>
<span id="hero-icone-play-pause" class="material-symbols-outlined text-4xl text-on-primary-fixed ml-2" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
</button>
</div>
<!-- Decorative Waveform Simulation -->
<div id="hero-waveform" class="mt-8 flex items-end gap-1 h-12 opacity-40 w-full max-w-md transition-opacity">
<div class="w-1 bg-primary rounded-t-sm h-[30%] animate-[pulse_1s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[60%] animate-[pulse_1.2s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[40%] animate-[pulse_0.8s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[90%] animate-[pulse_1.5s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[50%] animate-[pulse_0.9s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[70%] animate-[pulse_1.1s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[20%] animate-[pulse_0.7s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[80%] animate-[pulse_1.3s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[45%] animate-[pulse_1s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[30%] animate-[pulse_1.2s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[100%] animate-[pulse_1.4s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[60%] animate-[pulse_0.8s_ease-in-out_infinite_alternate]"></div>
<div class="w-1 bg-primary rounded-t-sm h-[40%] animate-[pulse_1.1s_ease-in-out_infinite_alternate]"></div>
</div>
</div>
</div>
</section>
<!-- Recently Played -->
<section class="mb-16">
<div class="flex items-center justify-between mb-6">
<h3 class="font-headline-md text-headline-md text-on-surface">Tocadas Recentemente</h3>
<a class="font-label-md text-label-md text-primary hover:text-primary-fixed transition-colors" href="wavify_my_library.php">Ver Tudo</a>
</div>
<div class="flex overflow-x-auto gap-6 pb-6 hide-scrollbar -mx-4 px-4 md:mx-0 md:px-0">
<?php if(count($musicas_recentes) > 0): ?>
<?php foreach($musicas_recentes as $indice_recente => $musica_recente): ?>
<div class="w-40 md:w-48 shrink-0 group cursor-pointer" onclick="tocarMusicaPorIndice(<?= $indice_recente ?>)">
<div class="relative w-full aspect-square rounded-xl overflow-hidden mb-4 border border-white/5 group-hover:border-primary/50 transition-colors duration-300">
<img alt="Capa de <?= htmlspecialchars($musica_recente['titulo']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="<?= htmlspecialchars($musica_recente['thumbnail']) ?>"/>
<div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
<span class="w-12 h-12 bg-primary rounded-full flex items-center justify-center shadow-[0_0_20px_rgba(124,58,237,0.6)] translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
<span class="material-symbols-outlined text-on-primary-fixed" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
</span>
</div>
</div>
<h4 class="font-label-lg text-label-lg text-on-surface truncate"><?= htmlspecialchars($musica_recente['titulo']) ?></h4>
<p class="font-body-sm text-body-sm text-on-surface-variant truncate mt-1"><?= htmlspecialchars($musica_recente['artista']) ?></p>
</div>
<?php endforeach; ?>
<?php else: ?>
<div class="w-full rounded-xl border border-white/5 bg-surface-container-low/50 px-6 py-8 text-on-surface-variant">
<p class="font-body-md text-body-md">Nenhuma música reproduzida ainda.</p>
</div>
<?php endif; ?>
</div>
</section>


</main>
<!-- BottomNavBar (Player Bar) -->
<footer class="fixed bottom-0 left-0 w-full h-[96px] z-50 bg-surface-container/90 backdrop-blur-2xl border-t border-white/10 shadow-[0_-10px_40px_rgba(0,0,0,0.5)] flex items-center justify-between px-4 md:px-8">
<!-- Track Info -->
<div class="flex items-center gap-4 w-1/3 min-w-[200px]">
<img id="player-capa" alt="Capa da música atual" class="w-14 h-14 rounded-md object-cover hidden sm:block" src="<?= count($musicas_recentes) > 0 ? htmlspecialchars($musicas_recentes[0]['thumbnail']) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuAE8qW-12I3R5AbdBkyMrbYsfIWcnx5Hfw7KtsNTJPyMHUVYOT7iFXhM2dv-zHwAK_Skvb-MKyyDDkSYTrF7Cs-qwLmp2_Xe-RShetIuoBXsmXdFXlzOC-aqEMkwddXuICfYFY-_gYJcYek11EVxyz8Wp9q04wfaJa6jHxjspngBcOoZWnSlW12xjgXM5VwJ6v0mv7UqZwFbpgoTUbogyiccDWk-UaPs-kncYghGeyMUvFDowiUoxAKdFMOuHEYT0GMaVSe3kjDCZM' ?>"/>
<div class="flex flex-col">
<a id="player-titulo" class="font-label-lg text-label-lg text-on-surface hover:underline truncate" href="#"><?= count($musicas_recentes) > 0 ? htmlspecialchars($musicas_recentes[0]['titulo']) : 'Nenhuma música' ?></a>
<a id="player-artista" class="font-body-sm text-body-sm text-on-surface-variant hover:text-on-surface transition-colors truncate" href="#"><?= count($musicas_recentes) > 0 ? htmlspecialchars($musicas_recentes[0]['artista']) : 'Toque algo na biblioteca' ?></a>
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
<!-- Play Button Active State -->
<button onclick="playPauseMusica()" class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-on-primary-fixed shadow-[0_0_20px_rgba(124,58,237,0.6)] scale-110 hover:scale-110 transition-transform duration-200">
<span id="icone-play-pause" class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
</button>
<button onclick="proximaMusica()" class="text-on-surface-variant hover:text-on-surface transition-colors">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">skip_next</span>
</button>
<button id="repeat-button" onclick="alternarRepetir()" class="text-on-surface-variant hover:text-on-surface transition-colors hidden sm:block">
<span class="material-symbols-outlined">repeat</span>
</button>
</div>
<!-- Progress Bar -->
<div class="w-full flex items-center gap-2 hidden md:flex">
<span id="tempo-atual" class="font-label-md text-label-md text-on-surface-variant/70 text-[10px]">0:00</span>
<div id="barra-progresso" onclick="alterarProgresso(event)" class="h-1 bg-surface-bright rounded-full flex-1 relative cursor-pointer group">
<div id="progresso-musica" class="absolute top-0 left-0 h-full bg-gradient-to-r from-primary to-secondary rounded-full w-0"></div>
<div id="controle-progresso" class="absolute top-1/2 -translate-x-1/2 -translate-y-1/2 left-0 w-3.5 h-3.5 bg-white rounded-full border-2 border-primary shadow-[0_0_10px_rgba(124,58,237,0.8)] transition-transform group-hover:scale-110"></div>
</div>
<span id="tempo-duracao" class="font-label-md text-label-md text-on-surface-variant/70 text-[10px]">0:00</span>
</div>
</div>
<!-- Utility -->
<div class="flex items-center justify-end gap-4 w-1/3 min-w-[150px]">


<div class="flex items-center gap-2 hidden sm:flex">
<button onclick="alternarMudo()" class="text-on-surface-variant hover:text-on-surface transition-colors">
<span id="icone-volume" class="material-symbols-outlined text-[20px]">volume_up</span>
</button>
<div id="barra-volume" onclick="alterarVolume(event)" class="w-20 h-1 bg-surface-bright rounded-full relative cursor-pointer group">
<div id="nivel-volume" class="absolute top-0 left-0 h-full bg-on-surface rounded-full w-full group-hover:bg-primary transition-colors"></div>
<div id="controle-volume" class="absolute top-1/2 -translate-x-1/2 -translate-y-1/2 left-full w-3 h-3 bg-white rounded-full border-2 border-on-surface-variant shadow-[0_0_8px_rgba(255,255,255,0.35)] transition-transform group-hover:scale-110"></div>
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
<!-- Bottom Nav Bar Mobile Alternative (Visible only on small screens) -->
<div class="md:hidden fixed bottom-[96px] left-0 w-full bg-surface-container/95 backdrop-blur-xl border-t border-white/10 flex justify-around items-center h-16 z-40">
<a class="flex flex-col items-center gap-1 text-primary" href="wavify_home.php">
<span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">home</span>
<span class="font-label-md text-[10px]">Início</span>
</a>
<a class="flex flex-col items-center gap-1 text-on-surface-variant hover:text-on-surface" href="wavify_discover.html">
<span class="material-symbols-outlined text-2xl">explore</span>
<span class="font-label-md text-[10px]">Descobrir</span>
</a>
<a class="flex flex-col items-center gap-1 text-on-surface-variant hover:text-on-surface" href="wavify_my_library.php">
<span class="material-symbols-outlined text-2xl">library_music</span>
<span class="font-label-md text-[10px]">Biblioteca</span>
</a>
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
    }, $musicas_recentes), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
    </script>
    <script src="player.js"></script>
    <script src="https://www.youtube.com/iframe_api"></script>
    <script src="main.js"></script>
</body></html>

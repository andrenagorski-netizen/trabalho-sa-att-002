<?php

session_start();

require_once "php/conexao.php";

$link = $_POST['youtube_link'];

$titulo = $_POST['titulo'];
$artista = $_POST['artista'];


$usuario_id = $_SESSION['usuario_id'];

parse_str(
    parse_url($link, PHP_URL_QUERY),
    $params
);

$youtube_id = $params['v'];

$thumbnail =
"https://i.ytimg.com/vi/$youtube_id/hqdefault.jpg";


$sql = "
INSERT INTO musicas
(
    usuario_id,
    youtube_id,
    titulo,
    artista,
    thumbnail
)
VALUES
(
    '$usuario_id',
    '$youtube_id',
    '$titulo',
    '$artista',
    '$thumbnail'
)
";

mysqli_query($conexao, $sql);



header("Location: /trabalho-sa-att/wavify_home.php");
exit;
<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors' , 1);

include("conexao.php");

if(isset($_POST['usuario'])){

    //echo "Entrou no cadastro<br>";

    $usuario = $_POST['usuario'];
    $email = $_POST['email'];
    $confirma_senha = $_POST['confirma_senha'] ?? null;

    if ($confirma_senha !== null && $_POST['senha'] !== $confirma_senha) {
        echo "As senhas nao coincidem";
        exit;
    }

    //echo "Usuario: $usuario <br>";
    //echo "Usuario: $email <br>";

    $senha = password_hash(
        $_POST['senha'],
        PASSWORD_DEFAULT
    );

    $sql = "INSERT INTO usuarios
    (usuario, email, senha)

    VALUES

    ('$usuario', '$email', '$senha')";


    if (mysqli_query($conexao, $sql)){
        $_SESSION['usuario_id'] = mysqli_insert_id($conexao);
        $_SESSION['usuario_nome'] = $usuario;

        header("Location: ../wavify_home.php");
        exit;
    }
    else{
        echo "erro: " . mysqli_error($conexao);
    }
}
?>

<form method="POST">

    <input type="text"
    name="usuario"
    placeholder="Usuário">

    <input type="email"
    name="email"
    placeholder="Email">

    <input type="password"
    name="senha"
    placeholder="Senha">

    <button type="submit">
        Criar conta
    </button>

</form>

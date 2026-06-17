<?php
//echo "<pre>";
//print_r ($_POST);
//echo "</pre>";
//exit;

session_start();

include("conexao.php");

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios
WHERE email = '$email'";

$resultado = mysqli_query($conexao, $sql);

$usuario = mysqli_fetch_assoc($resultado);

if($usuario){
   
    if(password_verify(
        $senha,
        $usuario['senha']
      
        
    )){
      
        $_SESSION['usuario_id'] = $usuario['id'];

        $_SESSION['usuario_nome'] = $usuario['usuario'];

        header("Location: ../wavify_home.php");
        exit;

    }else{

        echo "Senha incorreta";

    }

}else{

    echo "Usuário não encontrado";

}
?>
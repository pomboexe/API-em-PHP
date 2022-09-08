<?php
require('db/conexao.php');
    //VERIFICA SE O USUARIO JA ESTÁ CADASTRADO
if(isset($_POST['email'])&& isset($_POST['senha'])&& !empty($_POST['email']) && !empty($_POST['senha'])){
    $email = limparPost($_POST['email']);
    $senha = limparPost($_POST['senha']);
    $senha_cript = sha1($senha);

    $sql= $pdo->prepare("SELECT * FROM usuarios WHERE email=? AND senha=? LIMIT 1");
    $sql->execute(array($email,$senha_cript));
    $usuario = $sql->fetch(PDO::FETCH_ASSOC);
    if($usuario){
        $token = sha1(uniqid().date('d-m-Y-H-i-s'));
        
        $sql = $pdo->prepare("UPDATE usuarios SET token=? WHERE email=? AND senha=?");
            if($sql->execute(array($token,$email,$senha_cript))){
                $_SESSION['TOKEN'] = $token;
                header('Location: home.php');
            }
    }else{
        $erro_login = "Usuario ou senha incorreta";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/estilo.css" rel="stylesheet">
    <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
    <title>Login</title>
</head>
<body>
    <form method="post">
        <h1>Login</h1>
        <?php if(isset($erro_login)){ ?>
            <div class="erro-geral animate__animated animate__headShake">
            <?php  echo $erro_login; ?>
            </div>
        <?php } ?>
       <?php if (isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
                <div class="sucesso animate__animated animate__rubberBand">
                Cadastrado com sucesso!
               </div>               
       <?php }?>
         

        <div class="input-group">
            <img class="input-icon" src="img/person-fill.svg">
            <input name="email" type="email" placeholder="Digite seu email" required>
        </div>
        
        <div class="input-group">
            <img class="input-icon" src="img/key-fill.svg">
            <input type="password" name="senha" placeholder="Digite sua senha" required>
        </div>
        
        <button class="btn-blue" type="submit">Fazer Login</button>
        <a href="cadastrar.php">Ainda não tenho cadastro</a>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>    
    
    <?php if (isset($_GET['result']) && ($_GET['result']=="ok")){ ?>
    <script>
    setTimeout(() => {
           $('.sucesso').hide();            
     }, 3000);
    </script>
   <?php }?>

</body>
</html>
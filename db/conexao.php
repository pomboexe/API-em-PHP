<?php
//CONEXÃO COM O BANCO
session_start();

    $servidor ="localhost";
    $usuario = "root";
    $senha = "";
    $banco = "restaurant_sys";



try{
   $pdo = new PDO("mysql:host=$servidor;dbname=$banco",$usuario,$senha); 
   $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
   //echo "Banco conectado com sucesso!"; 
}catch(PDOException $erro){
    echo "Falha ao se conectar com o banco! ";
}
//EVITAR SQL INJECTION E ESCRITA DE SCRIPTS NOS FORMS
function limparPost($dados){
    $dados = trim($dados);
    $dados = stripslashes($dados);
    $dados = htmlspecialchars($dados);
    return $dados;
}
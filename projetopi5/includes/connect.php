<?php
// Configura��es do banco de dados


$host = "localhost";
$usuario = "root";
$senha =  "";
$bancoDeDados = "bd";



// Conex�o com o banco de dados
$conexao = new mysqli($host, $usuario, $senha, $bancoDeDados);

// Verifica a conex�o
if ($conexao->connect_error) {
    die("Erro na conex�o com o banco de dados: " . $conexao->connect_error);
}
<?php

$nome = readline("digite o nome do servico: ");
$valor = readline("digite o valor do servico: ");

$pdo = null;

try {
    $pdo = new PDO("mysql:dbname=cefet;host=127.0.0.1;charset=utf8", "root", "root");
} catch (PDOException $e) {
    die('Erro ao conectar: ' . $e->getMessage());
}

$pdo->exec( "INSERT INTO servico (descricao, valor) VALUES ('{$nome}', {$valor})" );
$id = $pdo->lastInsertId();

echo "Serviço cadastrado com sucesso!", $id, PHP_EOL;

?>
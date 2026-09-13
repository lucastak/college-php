<?php

$pdo = null;
try {
    $pdo = new PDO( 'mysql:dbname=cefet;host=localhost;charset=utf8', 'root', 'root' );
} catch ( PDOException $e ) {
    die( 'Erro ao conectar com o banco de dados: ' . $e->getMessage() );
}

$descricao = readline( 'Descrição: ' );
$valor = readline( 'Valor (R$): ' );

// Parâmetros anônimos
$stmt = $pdo->prepare( "INSERT INTO servico (descricao, valor) VALUES (?, ?)" );
$stmt->execute( [ $descricao, $valor ] );

$id = $pdo->lastInsertId(); // Obtém o id gerado
echo 'Cadastrado com o id ', $id, PHP_EOL;
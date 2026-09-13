<?php

$pdo = null;

try {
    $pdo = new PDO("mysql:dbname=cefet;host=127.0.0.1;charset=utf8", "root", "root");
} catch (PDOException $e) {
    die('Erro ao conectar: ' . $e->getMessage());
}

$descricao = readline("digite a descrição desejada: ");
$stmt = $pdo->prepare("SELECT id, descricao, valor FROM servico WHERE descricao LIKE ? ORDER BY valor DESC");
$stmt->execute([$descricao]);

foreach ($stmt as $servico) {
    echo( "{$servico['id']} - {$servico['descricao']} ({$servico['valor']})" . PHP_EOL );
}
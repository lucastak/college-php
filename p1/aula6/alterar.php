<?php

$pdo = null;

try {
    $pdo = new PDO("mysql:dbname=cefet;host=127.0.0.1;charset=utf8", "root", "root");
} catch (PDOException $e) {
    die('Erro ao conectar: ' . $e->getMessage());
}

$id = readline("digite o id do servico a ser alterado: ");
$stmt = $pdo->prepare("SELECT * FROM servico WHERE id = ?");
$stmt->execute([$id]);

if ($servico = $stmt->fetch()) {
    echo "Digite os novos dados para o servico " . $servico['descricao'] . ": " . PHP_EOL;
    $descricao = readline("descricao: ");
    $valor = readline("valor: ");
    $stmt = $pdo->prepare("UPDATE servico SET descricao = ? , valor = ? WHERE id = ?");
    $stmt->execute([$descricao, $valor, $id]);
    echo "Servico " . $servico['descricao'] . " alterado com sucesso!";
} else {
    echo "Servico nao encontrado!";
}
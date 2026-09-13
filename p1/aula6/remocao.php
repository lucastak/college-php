<?php

$pdo = null;

try {
    $pdo = new PDO("mysql:dbname=cefet;host=127.0.0.1;charset=utf8", "root", "root");
} catch (PDOException $e) {
    die('Erro ao conectar: ' . $e->getMessage());
}

$id = readline("digite o id do servico a ser removido: ");
$stmt = $pdo->prepare("DELETE FROM servico WHERE id = ?");
$stmt->execute([$id]);

if ($stmt->rowCount() > 0) {
    echo "Servico removido com sucesso!";
} else {
    echo "Servico nao encontrado!";
}
<?php

$pdo = null;

try {
    $pdo = new PDO("mysql:dbname=cefet;host=127.0.0.1;charset=utf8", "root", "root");
} catch (PDOException $e) {
    die('Erro ao conectar: ' . $e->getMessage());
}

$stmt = $pdo-> query('SELECT id, descricao, valor FROM servico');

foreach ($stmt as $servico) {
    echo( "{$servico['id']} - {$servico['descricao']} ({$servico['valor']})" . PHP_EOL );
}

$stmt = $pdo->query("SELECT SUM(valor) as total, AVG(valor) as media, MIN(valor) as menor, MAX(valor) as maior FROM servico");
$info = $stmt->fetch();
echo "Total: {$info['total']}, Média: {$info['media']}", PHP_EOL;
echo "Menor: {$info['menor']}, Maior: {$info['maior']}", PHP_EOL;

?>
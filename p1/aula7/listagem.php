<?php

$pdo = null;
try {
    $pdo = new PDO( 'mysql:dbname=cefet;host=localhost;charset=utf8', 'root', 'root' );
} catch ( PDOException $e ) {
    die( 'Erro ao conectar com o banco de dados: ' . $e->getMessage() );
}

// PDOStatement
$stmt = $pdo->query( 'SELECT id, descricao, valor FROM servico ORDER BY valor DESC' );
foreach ( $stmt as $r ) {
    echo $r['id'], ' ', $r['descricao'], ' R$ ', $r['valor'], PHP_EOL;
}

$sql = <<<SQL
    SELECT SUM(valor) as total, AVG(valor) as media, MIN(valor) AS min, MAX(valor) as max
    FROM servico
SQL;

$stmt = $pdo->query( $sql );
$r = $stmt->fetch(); // Obtém a primeira linha e coloca o cursor na próxima, se houver
echo 'Total R$ ', $r['total'], ' Média R$ ', $r['media'], PHP_EOL;
echo 'Menor valor: R$ ', $r['min'], ' Maior valor: R$ ', $r['max'], PHP_EOL;

// EXERCÍCIO: Solicite do usuário a descrição desejada e filtre todos os
// serviços que contenham o texto informado.

echo str_repeat( '-', 50 ), PHP_EOL;
$texto = readline( 'Pesquisa: ' );
$stmt = $pdo->prepare('SELECT * FROM servico WHERE descricao LIKE ? ORDER BY descricao' );
$stmt->execute( [ '%' . $texto . '%' ] ); // "%$texto%"
foreach ( $stmt as $r ) {
    echo $r['id'], ' ', $r['descricao'], ' R$ ', $r['valor'], PHP_EOL;
}

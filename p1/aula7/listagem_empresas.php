<?php

$pdo = null;
try {
    $pdo = new PDO( 'mysql:dbname=cefet;host=localhost;charset=utf8', 'root', 'root' );
} catch ( PDOException $e ) {
    die( 'Erro ao conectar com o banco de dados: ' . $e->getMessage() );
}

$sql = "SELECT e.id, e.nome, COUNT(se.servico_id) AS total_servicos
        FROM empresa e
        LEFT JOIN servico_empresa se ON e.id = se.empresa_id
        GROUP BY e.id, e.nome
        ORDER BY e.id ASC";

$stmt = $pdo->query( $sql );

foreach ( $stmt as $r ) {
    echo $r['id'], ' ', $r['nome'], ' - ', $r['total_servicos'], ' serviço(s)', PHP_EOL;
}

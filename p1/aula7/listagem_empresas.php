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

// $stmtEmpresas = $pdo->query( 'SELECT id, nome FROM empresa ORDER BY id ASC' );
// $stmtServicos = $pdo->prepare( 'SELECT servico_id FROM servico_empresa WHERE empresa_id = ?' );

// foreach ( $stmtEmpresas as $empresa ) {
//     $stmtServicos->execute( [ $empresa['id'] ] );

//     $totalServicos = 0;
//     foreach ( $stmtServicos as $servico ) {
//         $totalServicos++;
//     }

//     echo $empresa['id'], ' ', $empresa['nome'], ' - ', $totalServicos, ' serviço(s)', PHP_EOL;
// }

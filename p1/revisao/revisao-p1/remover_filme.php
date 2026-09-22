<?php

require_once 'RepositorioFilmeEmBDR.php';

// Conexão PDO
try {
    $pdo = new PDO( 'mysql:host=localhost;dbname=locadora;charset=utf8', 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ] );
} catch ( PDOException $e ) {
    die( 'Erro ao conectar ao banco de dados: ' . $e->getMessage() . PHP_EOL );
}

$idStr = trim( readline( 'Informe o ID do filme a ser removido: ' ) );

if ( !is_numeric( $idStr ) || (int)$idStr <= 0 ) {
    die( 'Erro: ID informado inválido.' . PHP_EOL );
}

$id = (int)$idStr;
$repo = new RepositorioFilmeEmBDR( $pdo );

try {
    $removido = $repo->remover( $id );
    if ( $removido ) {
        echo "Filme e suas associações de atores foram removidos com sucesso!" . PHP_EOL;
    } else {
        echo "Nenhum filme encontrado com o ID {$id}." . PHP_EOL;
    }
} catch ( RepositorioException $e ) {
    echo "Erro ao remover filme: " . $e->getMessage() . PHP_EOL;
}

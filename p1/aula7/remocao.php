<?php

$pdo = null;
try {
    $pdo = new PDO( 'mysql:dbname=cefet;host=localhost;charset=utf8', 'root', 'root' );
} catch ( PDOException $e ) {
    die( 'Erro ao conectar com o banco de dados: ' . $e->getMessage() );
}

$id = readline( 'Id a ser removido: ' );

$stmt = $pdo->prepare( 'DELETE FROM servico WHERE id = ?' );
$stmt->execute( [ $id ] );
echo $stmt->rowCount() > 0 ? 'Removido' : 'Não encontrado.', PHP_EOL;
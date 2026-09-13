<?php

$pdo = null;
try {
    $pdo = new PDO( 'mysql:dbname=cefet;host=localhost;charset=utf8', 'root', 'root' );
} catch ( PDOException $e ) {
    die( 'Erro ao conectar com o banco de dados: ' . $e->getMessage() );
}

$id = readline( 'Id a ser alterado: ' );

$stmt = $pdo->prepare( 'SELECT * FROM servico WHERE id = ?' );
$stmt->execute( [ $id ] );
if ( $stmt->rowCount() < 1 ) {
    die( 'Não encontrado.' );
}
$r = $stmt->fetch();
echo 'Serviço ', $r['id'], ' ', $r['descricao'], ' R$ ', $r['valor'], PHP_EOL;

echo 'Alteração', PHP_EOL;
$descricao = readline( 'Descrição: ' );
$valor = readline( 'Valor (R$): ' );

$stmt = $pdo->prepare( 'UPDATE servico SET descricao = :d, valor = :v WHERE id = :id' );
$stmt->execute( [ 'id' => $id, 'd' => $descricao, 'v' => $valor ] );
echo 'Atualizado com sucesso.';
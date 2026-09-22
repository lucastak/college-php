<?php

$pdo = null;
try {
    $pdo = new PDO( 'mysql:dbname=cefet;host=localhost;charset=utf8', 'root', 'root' );
} catch ( PDOException $e ) {
    die( 'Erro ao conectar com o banco de dados: ' . $e->getMessage() );
}

$nome = readline( 'Nome da empresa: ' );

// Listar serviços disponíveis
echo "--- Serviços Disponíveis ---", PHP_EOL;
$stmt = $pdo->query( 'SELECT id, descricao, valor FROM servico' );
foreach ( $stmt as $s ) {
    echo $s['id'], ' - ', $s['descricao'], ' (R$ ', $s['valor'], ')', PHP_EOL;
}

$entrada = readline( 'Informe os IDs dos serviços (ex: 1,2) ou deixe vazio: ' );

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare( 'INSERT INTO empresa (nome) VALUES (?)' );
    $stmt->execute( [ $nome ] );
    $empresaId = $pdo->lastInsertId();

    if ( trim( $entrada ) != '' ) {
        $ids = explode( ',', $entrada );
        $stmtRelacao = $pdo->prepare( 'INSERT INTO servico_empresa (empresa_id, servico_id) VALUES (?, ?)' );
        foreach ( $ids as $idServico ) {
            $idServico = trim( $idServico );
            if ( $idServico != '' ) {
                $stmtRelacao->execute( [ $empresaId, $idServico ] );
            }
        }
    }

    $pdo->commit();
    echo 'Empresa cadastrada com o ID ', $empresaId, PHP_EOL;
} catch ( PDOException $e ) {
    $pdo->rollBack();
    echo 'Erro ao cadastrar empresa: ', $e->getMessage(), PHP_EOL;
}

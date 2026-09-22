<?php

/**
 * Questão 2: Relatório de Categorias e Filmes
 * 
 * Abaixo estão duas formas de resolver:
 * - ABORDAGEM 1: Duas queries simples + laço PHP (estilo feito em sala na Aula 7).
 *   Não precisa lembrar de JOIN, nem GROUP BY, nem IFNULL. Muito fácil de escrever no papel!
 * - ABORDAGEM 2: Query única com LEFT JOIN e GROUP BY (para quem preferir resolver no SQL).
 */

try {
    $pdo = new PDO( 'mysql:host=localhost;dbname=locadora;charset=utf8', 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ] );

    // =========================================================================
    // ABORDAGEM 1: DUAS QUERIES SIMPLES (ESTILO AULA 7 - IDEAL PARA PROVA NO PAPEL)
    // =========================================================================
    echo "--- ABORDAGEM 1: COM DUAS QUERIES E LAÇO PHP ---", PHP_EOL;

    // 1. Busca todas as categorias
    $stmtCategorias = $pdo->query( 'SELECT id, nome FROM categoria ORDER BY nome ASC' );

    // 2. Prepara a busca dos filmes de cada categoria
    $stmtFilmes = $pdo->prepare( 'SELECT preco_diaria FROM filme WHERE categoria_id = ?' );

    // 3. Percorre cada categoria e faz as contas no próprio PHP
    foreach ( $stmtCategorias as $cat ) {
        $stmtFilmes->execute( [ $cat['id'] ] );

        $totalFilmes = 0;
        $somaPrecos  = 0.0;

        foreach ( $stmtFilmes as $filme ) {
            $totalFilmes++;
            $somaPrecos += (float)$filme['preco_diaria'];
        }

        $mediaPreco = $totalFilmes > 0 ? ( $somaPrecos / $totalFilmes ) : 0.00;

        echo "Categoria: {$cat['nome']} | Total: {$totalFilmes} filme(s) | Preço Médio: R$ " . 
             number_format( $mediaPreco, 2, ',', '.' ) . PHP_EOL;
    }


    // =========================================================================
    // ABORDAGEM 2: QUERY ÚNICA COM LEFT JOIN + GROUP BY
    // =========================================================================
    echo PHP_EOL, "--- ABORDAGEM 2: QUERY ÚNICA COM JOIN ---", PHP_EOL;

    $sql = "SELECT c.nome, 
                   COUNT(f.id) AS total_filmes, 
                   IFNULL(AVG(f.preco_diaria), 0) AS media_preco
            FROM categoria c
            LEFT JOIN filme f ON c.id = f.categoria_id
            GROUP BY c.id, c.nome
            ORDER BY c.nome ASC";

    $stmt = $pdo->query( $sql );

    foreach ( $stmt as $linha ) {
        $mediaFormatada = number_format( (float)$linha['media_preco'], 2, ',', '.' );
        echo "Categoria: {$linha['nome']} | Total: {$linha['total_filmes']} filme(s) | Preço Médio: R$ {$mediaFormatada}", PHP_EOL;
    }

} catch ( PDOException $e ) {
    echo "Erro no banco de dados: " . $e->getMessage() . PHP_EOL;
}


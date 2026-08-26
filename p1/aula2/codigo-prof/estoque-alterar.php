<?php
require_once 'estoque-util.php';

function alterar( &$produtos ) {
    echo criarTitulo( 'ALTERAÇÃO' );
    $codigo = readline( 'Código a alterar: ');
    $indiceEncontrado = indiceProdutoComCodigo( $produtos, $codigo );

    if ( $indiceEncontrado != INDICE_NAO_ENCONTRADO ) {
        $produto = lerProduto();
        $produtos[ $indiceEncontrado ] = $produto;
        echo "Alterado com sucesso\n";
    } else {
        echo "Não encontrado\n";
    }
}
<?php
require_once 'estoque-util.php';

function remover( &$produtos ) {
    echo criarTitulo( 'REMOÇÃO' );
    $codigo = readline( 'Código a remover: ');
    $indiceEncontrado = indiceProdutoComCodigo( $produtos, $codigo );

    if ( $indiceEncontrado != INDICE_NAO_ENCONTRADO ) {
        unset( $produtos[ $indiceEncontrado ] );
        echo "Removido com sucesso.\n";
    } else {
        echo "Não encontrado.\n";
    }
}
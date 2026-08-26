<?php
const INDICE_NAO_ENCONTRADO = -1;

function criarTitulo( $titulo ) {
    return "$titulo\n" . str_repeat( '-', 50 ) . "\n";
}

function indiceProdutoComCodigo( $produtos, $codigo ) {
    $indiceEncontrado = INDICE_NAO_ENCONTRADO;
    foreach ( $produtos as $indice => $p ) {
        if ( $codigo == $p['codigo'] ) {
            $indiceEncontrado = $indice;
            break;
        }
    }
    return $indiceEncontrado;
}


function lerProduto() {
    $codigo = readline( 'Código: ' );
    $descricao = readline( 'Descrição: ' );
    $estoque = (int) readline( 'Estoque: ' );
    $preco = (float) readline( 'Preço (R$): ' );
    return [
        'codigo' => $codigo,
        'descricao' => $descricao,
        'estoque' => $estoque,
        'preco' => $preco
    ];
}

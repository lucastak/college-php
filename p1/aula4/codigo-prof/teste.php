<?php
require_once 'Produto.php';

// $produtos = [
//     new Produto( 1, 'Tablet',             500, 1_700.00 ),
//     new Produto( 2, 'Celular',          1_000, 3_500.00 ),
//     new Produto( 3, 'Fone de Ouvido',     250,   175.00 ),
//     new Produto( 4, 'Carregador',         100,   100.00 ),
// ];

/**
 * Salva produtos
 * @param Produto[] $produtos Produtos a serem salvos
 * @return void
 */
function salvarProdutos( $produtos ) {
    $novo = [];
    foreach ( $produtos as $p ) {
        $novo []= $p->toArray();
    }
    file_put_contents( 'produtos.json', json_encode( $novo ) );
}

// salvarProdutos( $produtos );

/**
 * Retorna um array de Produtos
 * @return Produto[]
 */
function carregarProdutos() {
    $matriz = json_decode( file_get_contents( 'produtos.json' ), true );
    $produtos = [];
    foreach ( $matriz as $linha ) {
        $p = Produto::criar( $linha );
        $produtos []= $p;
    }
    return $produtos;
}

// var_dump( carregarProdutos() );

// EXERCÍCIO 1:
// CRIE UMA FUNÇÃO calcularInventario que receba um array de produtos
// e calcule o inventário total dos produtos (soma dos inventários)

function calcularInventario( $produtos ) {
    $inventario = 0;
    foreach ( $produtos as $p ) {
        $inventario += $p->inventario();
    }
    return $inventario;
}
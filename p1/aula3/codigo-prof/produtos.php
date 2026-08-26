<?php
// Exercício: calcular o Inventário do estoque
// Inventário: multiplicação do estoque pelo preço, em todos os produtos (soma)

$conteudo = file_get_contents( 'produtos.csv' );

$linhas = explode( "\n", $conteudo );

array_shift( $linhas );
array_pop( $linhas );

$inventario = 0;

foreach ( $linhas as $l ) {

    $partes = explode( ';', $l );

    $preco = trim( $partes[ 3 ], ' R$' );
    $preco = str_replace( ',', '.', $preco );

    $estoque = $partes[ 2 ];

    $inventario += $estoque * $preco;
}

echo "Inventário: R$ $inventario\n";
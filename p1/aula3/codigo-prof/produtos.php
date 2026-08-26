<?php
$conteudo = file_get_contents( 'produtos.csv' );
$linhas = explode( "\n", $conteudo );
array_shift( $linhas ); // Remove a 1a linha - títulos
array_pop( $linhas ); // Remove a última linha (vazia)
// var_dump( $linhas );

// Exercício: calcular o Inventário do estoque
// Inventário: multiplicação do estoque pelo preço, em todos os produtos (soma)

foreach ( $linhas as $l ) {
    $partes = explode( ';', $l );
    $preco = trim( $partes[ 3 ], ' R$' );
    $preco = str_replace( ',', '.', $preco );
    $estoque = $partes[ 2 ];
}
<?php
require_once 'estoque-util.php';

function cadastrar( &$produtos ) {
    echo criarTitulo( 'CADASTRO' );
    $produto = lerProduto();
    array_push( $produtos, $produto ); // $produtos []= $produto;
}

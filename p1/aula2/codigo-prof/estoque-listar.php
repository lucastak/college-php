<?php
require_once 'estoque-util.php';
function listar( $produtos ) {
    echo criarTitulo( 'LISTAGEM DE PRODUTOS' );
    foreach ( $produtos as $p ) {
        echo $p['codigo'], ' ', $p['descricao'], ' ', $p['estoque'], ' R$ ', $p['preco'], "\n";
    }
}
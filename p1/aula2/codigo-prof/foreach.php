<?php
$a = [ 10, 20, 30, 40, 50 ];

// Com índice (chave)
foreach ( $a as $i => $valor ) {
    echo 'Índice: ', $i, ' valor: ', $valor, "\n";
}

// Sem índice
foreach ( $a as $valor ) {
    echo 'Valor: ', $valor, "\n";
}
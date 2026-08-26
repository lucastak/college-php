<?php

function incrementar( &$x, $valor = 1 ) {
    $x += $valor;
}

$y = 10;
incrementar( $y ); // 11
echo $y, "\n";
incrementar( $y, 5 ); // 16
echo $y, "\n";
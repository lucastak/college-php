<?php

// Crie uma função mediaArray que receba um array
// de números e retorne a média dos seus elementos.

function mediaArray( $numeros ) {
    $soma = 0;
    $contagem = count( $numeros );
    if ( $contagem == 0 ) { // Evita divisão por zero
        return 0;
    }
    for ( $i = 0; $i < $contagem; $i++ ) {
        $soma += $numeros[ $i ];
    }
    return $soma / $contagem;
}

$idades = [ 20, 60, 5, 40, 30 ];

echo mediaArray( $idades ), "\n";
echo mediaArray( [] ), "\n";
<?php
require_once 'maior-de-dois.php';

$idades = [ 20, 60, 5, 40, 30 ];

echo count( $idades ), ' idades', "\n";

// Crie uma função para determinar o maior de um array
// recebido por argumento. Dica: use maiorDeDois.

function maiorDeArray( $numeros ) {
    $contagem = count( $numeros );
    if ( $contagem < 1 ) {
        return false;
    }
    $maior = $numeros[ 0 ];
    for ( $i = 1; $i < $contagem; $i++ ) {
        $maior = maiorDeDois( $maior, $numeros[ $i ] );
    }
    return $maior;
}

echo maiorDeArray( $idades );

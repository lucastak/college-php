<?php
require_once 'maior-de-dois.php';

// Crie uma função "maiorDeTres" que utilize a função
// "maiorDeDois" para determinar o maior de três
// números recebidos por argumento.

function maiorDeTres( $n1, $n2, $n3 ) {
    $maior = maiorDeDois( $n1, $n2 );
    return maiorDeDois( $maior, $n3 );
}

echo maiorDeTres( 3, 4, 5 );
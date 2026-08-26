<?php
// Crie uma função que receba dois números e retorne o maior deles

function maiorDeDois( $numero1, $numero2 ) {
    if ( $numero1 > $numero2 ) {
        return $numero1;
    }
    return $numero2;
}

// echo maiorDeDois( 10, 9 ), "\n";
// echo maiorDeDois( 9, 10 ), "\n";
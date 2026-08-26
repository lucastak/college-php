<?php
// Faça uma função que retorne true se o número informado for ímpar
function impar( $numero ) {
    return $numero % 2 != 0;
    // if ( $numero % 2 != 0 ) {
    //     return true;
    // } else {
    //      return false;
    // }
}

echo 'Ímpar: ', impar( 4 ) ? 'Sim' : 'Não', "\n";
echo 'Ímpar: ', impar( 5 ) ? 'Sim' : 'Não', "\n";
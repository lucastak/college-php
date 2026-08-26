<?php
// Crie um script que leia um texto do usuário e informe se é um palíndromo.
// Um palíndromo é uma palavra ou frase que se lida da esquerda para a direita ou
// da direita para a esquerda contém os mesmos caracteres. Exemplos:
// ovo, arara, radar, mirim, "o lobo ama o bolo"
//
// DICA: use explode() + implode() ou str_replace( 'busca', 'substituição', 'texto' )

$frase = readline( 'Frase: ' );
// $palavras = explode( ' ', $frase );
// $unida = implode( '', $palavras ); // oloboamaobolo
$unida = str_replace( ' ', '', $frase );

$tamanho = mb_strlen( $unida );
$invertida = '';
for ( $i = $tamanho - 1; $i >= 0; $i-- ) {
    $invertida .= $unida[ $i ];
}

if ( $unida == $invertida ) {
    echo "É um palíndromo\n";
} else {
    echo "Não é um palíndromo\n";
}


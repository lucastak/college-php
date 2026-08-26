<?php
// Solicite ao usuário uma frase e informe a palavra com maior comprimento
// e o seu comprimento.
$frase = readline( 'Frase: ' );
$palavras = explode( ' ', $frase );
$maiorTamanho = 0;
$indiceMaiorPalavra = -1;
foreach ( $palavras as $indice => $p ) {
    if ( mb_strlen( $p ) > $maiorTamanho ) {
        $maiorTamanho = mb_strlen( $p );
        $indiceMaiorPalavra = $indice;
    }
}
if ( $indiceMaiorPalavra >= 0 ) {
    echo 'A maior palavra é ', $palavras[ $indiceMaiorPalavra ],
        ' e tem tamanho ', $maiorTamanho, "\n";
} else {
    echo 'Não foi possível computar a maior palavra.';
}
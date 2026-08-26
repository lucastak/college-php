<?php

$meses = [
    1 => 'Janeiro', 2 => 'Fevereiro',   3 => 'Março',
    4 => 'Abril',   5 => 'Maio',        6 => 'Junho',
    7 => 'Julho',   8 => 'Agosto',      9 => 'Setembro',
    10 => 'Outubro',11 => 'Novembro',   12 => 'Dezembro',
];

// Exercício: Leia uma data do usuário, no formato dia/mês/ano
// (ex. 01/02/1970) e então imprima a data no formato do
// exemplo a seguir: 01 de Fevereiro de 1970

$data = readline( 'Data: ' );
$partes = explode( '/', $data );
$dia = $partes[ 0 ];
$mes = $partes[ 1 ];
$ano = $partes[ 2 ];

// foreach ( $meses as $numeroMes => $mesExtenso ) {
//     if ( $numeroMes == $mes ) {
//         echo "$dia de $mesExtenso de $ano";
//     }
// }

$mesExtenso = $meses[ (int) $mes ];
echo "$dia de $mesExtenso de $ano";
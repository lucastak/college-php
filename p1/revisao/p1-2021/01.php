<?php
// 01.php
// Questão 1 - 2021/1

function contabilizar(array $strings) {
    $ocorrencias = [];
    
    foreach ($strings as $str) {
        if (isset($ocorrencias[$str])) {
            $ocorrencias[$str]++;
        } else {
            $ocorrencias[$str] = 1;
        }
    }
    
    return $ocorrencias;
}

// Exemplo de entrada (array de strings)
$entrada = [ 'maçã', 'uva', 'maçã', 'banana', 'goiaba', 'uva', 'maçã', 'banana' ];
$saida = contabilizar( $entrada );
var_dump( $saida );

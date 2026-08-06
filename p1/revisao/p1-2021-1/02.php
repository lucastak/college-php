<?php
// 02.php
// Questão 2 - 2021/1

function itensComecandoCom($prefixo, array $strings) {
    $resultado = [];
    
    foreach ($strings as $str) {
        if (mb_stripos($str, $prefixo) === 0) {
            $resultado[] = $str;
        }
    }
    
    sort($resultado);
    return $resultado;
}

// Exemplo de entrada (array de strings)
$entrada = [ 'Pedro', 'pedra', 'cinto', 'lápis', 'Camila', 'dado' ];
$saida = itensComecandoCom( 'c', $entrada );
print_r( $saida );

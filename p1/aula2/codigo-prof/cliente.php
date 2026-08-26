<?php

$cliente = [
    'nome' => 'Mauro Gomes',
    'nascimento' => '01/02/1970',
    'telefone' => '22 99887766'
];

echo "Dados do cliente:\n";
// Exercício: imprima os dados do cliente em um foreach

foreach ( $cliente as $campo => $dado ) {
    echo $campo, ': ', $dado, "\n";
}
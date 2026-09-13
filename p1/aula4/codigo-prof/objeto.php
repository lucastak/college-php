<?php
// Objeto dinâmico
$obj = new stdClass();
$obj->nome = 'João';
$obj->idade = 20;
echo $obj->nome, ' ', $obj->idade, ' ', PHP_EOL;
// Conversível para array
$arr = (array) $obj;
echo $arr['nome'], ' ', $arr['idade'], PHP_EOL;
// E vice-versa
$obj2 = (object) $arr;
var_dump( $obj2 );
<?php
// Crie um script que leia uma frase do usuário e informe
// quantas palavras há nessa frase. Não é necessário ignorar
// pontuação (considere também como palavra).

$frase = readline( 'Frase: ' );
// O CEFET possui o Bacharelado em Sistemas de Informação
$partes = explode( ' ', $frase );
echo count( $partes ), ' palavras.';
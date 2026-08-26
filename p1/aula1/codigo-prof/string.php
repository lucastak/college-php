<?php
$x = 10;
$s1 = '\t O valor de x é $x\n';     // Apóstrofo
$s2 = "\t O valor de x é $x\n";     // Aspas
$s3 = "\t O valor de x é {$x}\n";   // Aspas

echo $s1;
echo $s2;
echo $s3;

// Heredoc - permite declarar uma string livremente
$codigo = '123';
$sql = <<<SQL
    SELECT * FROM produto p
    LEFT JOIN setor s ON s.id = p.setor_id
    WHERE p.codigo = '{$codigo}'
SQL;

echo $sql, "\n";

$html = <<<HTML
    <div>{$codigo}</div>
HTML;

echo $html, "\n";
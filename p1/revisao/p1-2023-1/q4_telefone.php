<?php
// q4_telefone.php
// Questão 4 - 2023/1

function formatarTelefone($telefone) {
    $tamanho = mb_strlen($telefone);
    
    if (($tamanho === 10 || $tamanho === 11) && is_numeric($telefone)) {
        if ($tamanho === 10) {
            $ddd = mb_substr($telefone, 0, 2);
            $parte1 = mb_substr($telefone, 2, 4);
            $parte2 = mb_substr($telefone, 6, 4);
            return "({$ddd}) {$parte1}-{$parte2}";
        } else {
            $ddd = mb_substr($telefone, 0, 2);
            $parte1 = mb_substr($telefone, 2, 5);
            $parte2 = mb_substr($telefone, 7, 4);
            return "({$ddd}) {$parte1}-{$parte2}";
        }
    }
    
    return "";
}

// Exemplos de teste
echo formatarTelefone("2225271727") . "\n";
echo formatarTelefone("22988776655") . "\n";
echo formatarTelefone("123456789") . "\n"; // Retorna vazio

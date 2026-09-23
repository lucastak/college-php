<?php

function stringParaCamelCase(string $texto): string {
    // Substitui traços e sublinhados por espaços
    $textoEspacado = str_replace(['_', '-'], ' ', $texto);
    
    // Divide em palavras
    $palavras = explode(' ', $textoEspacado);
    
    $resultado = '';
    $primeira = true;

    foreach ($palavras as $palavra) {
        $palavra = trim($palavra);
        if ($palavra === '') {
            continue;
        }

        $palavra = mb_strtolower($palavra);

        if ($primeira) {
            $resultado .= $palavra;
            $primeira = false;
        } else {
            $primeiraLetra = mb_strtoupper(mb_substr($palavra, 0, 1));
            $resto = mb_substr($palavra, 1);
            $resultado .= $primeiraLetra . $resto;
        }
    }

    return $resultado;
}

function paraCamelCase(array $itens): array {
    $novoArray = [];
    foreach ($itens as $item) {
        $novoArray[] = stringParaCamelCase($item);
    }
    return $novoArray;
}

// Teste
$entrada = [ "exemplo de TEXTO", "OUTRO_exemplo_de_texto", "mais-UM-exemplo-de-texto" ];
$saida = paraCamelCase($entrada);
print_r($saida);

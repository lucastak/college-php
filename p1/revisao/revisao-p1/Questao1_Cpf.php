<?php

/**
 * Questão 1: Formatação de CPF
 * 
 * Regra:
 * - Aceita string
 * - Extrai apenas os dígitos numéricos
 * - Se tiver exatamente 11 dígitos, retorna formatado: 000.000.000-00
 * - Caso contrário, retorna "" (string vazia)
 */

// =============================================================================
// OPÇÃO 1 (SEM REGEX - RECOMENDADA PARA PROVA NO PAPEL)
// Percorre caractere por caractere com mb_substr e filtra com is_numeric().
// É simples de lembrar, não depende de sintaxe de expressão regular e usa
// apenas funções básicas vistas em aula (mb_strlen, mb_substr, is_numeric).
// =============================================================================
function formatarCpf( string $cpf ): string {
    // 1. Filtra apenas os dígitos numéricos sem usar regex
    $numeros = '';
    $tamanho = mb_strlen( $cpf );
    for ( $i = 0; $i < $tamanho; $i++ ) {
        $char = mb_substr( $cpf, $i, 1 );
        if ( is_numeric( $char ) ) {
            $numeros .= $char;
        }
    }

    // 2. Valida se possui exatamente 11 dígitos
    if ( mb_strlen( $numeros ) !== 11 ) {
        return '';
    }

    // 3. Monta a formatação: 000.000.000-00
    $p1 = mb_substr( $numeros, 0, 3 );
    $p2 = mb_substr( $numeros, 3, 3 );
    $p3 = mb_substr( $numeros, 6, 3 );
    $p4 = mb_substr( $numeros, 9, 2 );

    return "{$p1}.{$p2}.{$p3}-{$p4}";
}


// =============================================================================
// OPÇÃO 2 (SEM REGEX - USANDO STR_REPLACE)
// Se o CPF tiver apenas pontuações comuns (ponto, traço, barra, espaço),
// podemos remover esses caracteres com str_replace:
// =============================================================================
function formatarCpfComStrReplace( string $cpf ): string {
    // Remove pontuações conhecidas
    $numeros = str_replace( [ '.', '-', ' ', '/' ], '', $cpf );

    // Garante que o que sobrou são apenas números e tem tamanho 11
    if ( mb_strlen( $numeros ) !== 11 || !is_numeric( $numeros ) ) {
        return '';
    }

    // 17823518768 -> 178.235.187-68
    return mb_substr( $numeros, 0, 3 ) . '.' .
           mb_substr( $numeros, 3, 3 ) . '.' .
           mb_substr( $numeros, 6, 3 ) . '-' .
           mb_substr( $numeros, 9, 2 );
}


// =============================================================================
// OPÇÃO 3 (COM REGEX - PREG_REPLACE)
// Explicação do Regex:
// - '/\D/' significa "qualquer caractere que NÃO seja dígito" (D maiúsculo = não-dígito).
// - Equivalente a '/[^0-9]/' (qualquer coisa fora do intervalo 0 a 9).
// Substitui tudo o que não for dígito por string vazia ('').
// =============================================================================
function formatarCpfComRegex( string $cpf ): string {
    // Remove qualquer caractere que não seja número
    $numeros = preg_replace( '/\D/', '', $cpf );

    if ( mb_strlen( $numeros ) !== 11 ) {
        return '';
    }

    $p1 = mb_substr( $numeros, 0, 3 );
    $p2 = mb_substr( $numeros, 3, 3 );
    $p3 = mb_substr( $numeros, 6, 3 );
    $p4 = mb_substr( $numeros, 9, 2 );

    return "{$p1}.{$p2}.{$p3}-{$p4}";
}


// --- Teste de Execução ---
if ( php_sapi_name() === 'cli' && basename( __FILE__ ) === basename( $argv[0] ?? '' ) ) {
    echo "--- TESTES SEM REGEX (Opção 1 - Laço) ---", PHP_EOL;
    echo "Teste 1 ('123.456.789-01'): " . formatarCpf( '123.456.789-01' ) . PHP_EOL;
    echo "Teste 2 ('12345678901'):     " . formatarCpf( '12345678901' ) . PHP_EOL;
    echo "Teste 3 ('123.456.78-01'):   " . var_export( formatarCpf( '123.456.78-01' ), true ) . PHP_EOL;
    echo "Teste 4 ('abc12345678901x'): " . formatarCpf( 'abc12345678901x' ) . PHP_EOL;

    echo PHP_EOL, "--- TESTES COM REGEX (Opção 3) ---", PHP_EOL;
    echo "Teste 1 ('123.456.789-01'): " . formatarCpfComRegex( '123.456.789-01' ) . PHP_EOL;
    echo "Teste 2 ('12345678901'):     " . formatarCpfComRegex( '12345678901' ) . PHP_EOL;
}


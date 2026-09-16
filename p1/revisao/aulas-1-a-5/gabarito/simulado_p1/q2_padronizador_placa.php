<?php

/**
 * SIMULADO P1 - Questão 2 (INÉDITO): Validador e Padronizador de Placa Veicular
 */

function padronizarPlaca(string $placa): string {
    // Remove espaços e hífens eventuais
    $limpa = str_replace([' ', '-'], '', trim($placa));
    $limpa = mb_strtoupper($limpa, 'UTF-8');

    if (mb_strlen($limpa, 'UTF-8') !== 7) {
        return '';
    }

    // Padrão Tradicional: 3 letras e 4 números (ex: ABC1234 -> ABC-1234)
    if (preg_match('/^[A-Z]{3}[0-9]{4}$/', $limpa) === 1) {
        $letras = mb_substr($limpa, 0, 3, 'UTF-8');
        $numeros = mb_substr($limpa, 3, 4, 'UTF-8');
        return "{$letras}-{$numeros}";
    }

    // Padrão Mercosul: 3 letras, 1 número, 1 letra e 2 números (ex: ABC1D23 -> ABC1D23)
    if (preg_match('/^[A-Z]{3}[0-9]{1}[A-Z]{1}[0-9]{2}$/', $limpa) === 1) {
        return $limpa;
    }

    return '';
}

// Demonstração se executado diretamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    echo "=== SIMULADO P1: Questão 2 (Padronizador de Placas) ===\n";

    $exemplos = [
        "abc1234",
        "ABC-1234",
        "bra2e19",
        "123abcd",
        "ABC12345",
        "KLR9A88"
    ];

    foreach ($exemplos as $ex) {
        $padronizada = padronizarPlaca($ex);
        echo "Entrada: '{$ex}' -> Saída: '" . ($padronizada !== '' ? $padronizada : "[INVÁLIDA]") . "'\n";
    }
}

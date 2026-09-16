<?php

/**
 * GABARITO - MÓDULO 1: AULA 1 (Fundamentos, Funções e Lógica Básica) - INÉDITO
 */

/**
 * Exercício 1.1: Aplica bônus de experiência por referência (&) com parâmetros default.
 */
function aplicarBuffExp(int &$experiencia, float $multiplicador = 1.25, int $bonusFixo = 50): void {
    if ($experiencia < 0 || $multiplicador < 1.0 || $bonusFixo < 0) {
        return;
    }
    $experiencia = (int) (($experiencia * $multiplicador) + $bonusFixo);
}

/**
 * Exercício 1.2: Monitor Térmico sem funções prontas da linguagem.
 */
function detectarPicoTermico(array $leituras): ?float {
    $total = count($leituras);
    if ($total === 0) {
        return null;
    }

    $maior = $leituras[0];
    for ($i = 1; $i < $total; $i++) {
        if ($leituras[$i] > $maior) {
            $maior = $leituras[$i];
        }
    }
    return (float) $maior;
}

function detectarMenorTemperatura(array $leituras): ?float {
    $total = count($leituras);
    if ($total === 0) {
        return null;
    }

    $menor = $leituras[0];
    for ($i = 1; $i < $total; $i++) {
        if ($leituras[$i] < $menor) {
            $menor = $leituras[$i];
        }
    }
    return (float) $menor;
}

function calcularMediaTermica(array $leituras): float {
    $total = count($leituras);
    if ($total === 0) {
        return 0.0;
    }

    $soma = 0.0;
    for ($i = 0; $i < $total; $i++) {
        $soma += $leituras[$i];
    }
    return $soma / $total;
}

function contarSuperaquecimentos(array $leituras, float $limiar = 75.0): int {
    $contagem = 0;
    $total = count($leituras);
    for ($i = 0; $i < $total; $i++) {
        if ($leituras[$i] > $limiar) {
            $contagem++;
        }
    }
    return $contagem;
}

/**
 * Exercício 1.3: Extrato de Cartão Pré-pago formatado com sintaxe Heredoc e ternário.
 */
function gerarExtratoCartao(string $titular, float $saldoAnterior, float $recarga, float $gasto): string {
    $saldoAtual = $saldoAnterior + $recarga - $gasto;
    $status = ($saldoAtual >= 0) ? 'Regular' : 'Bloqueado / Negativo';

    $sAnteriorFormatado = number_format($saldoAnterior, 2, '.', '');
    $recargaFormatada = number_format($recarga, 2, '.', '');
    $gastoFormatado = number_format($gasto, 2, '.', '');
    $sAtualFormatado = number_format($saldoAtual, 2, '.', '');

    return <<<COMPROVANTE
========================================
EXTRATO CARTAO PRE-PAGO
========================================
Titular: {$titular}
Saldo Anterior: R$ {$sAnteriorFormatado}
Recarga: R$ {$recargaFormatada}
Gasto: R$ {$gastoFormatado}
Saldo Atual: R$ {$sAtualFormatado}
Status: {$status}
========================================
COMPROVANTE;
}

// Demonstração se executado diretamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    echo "=== Testando Aula 1 (Inédito) ===\n";

    $xp = 1000;
    aplicarBuffExp($xp);
    echo "XP após buff padrão: {$xp}\n"; // 1300
    aplicarBuffExp($xp, 1.10, 100);
    echo "XP após buff customizado: {$xp}\n"; // 1530

    $temperaturas = [62.5, 78.0, 55.4, 82.1, 71.0];
    echo "Pico Térmico: " . detectarPicoTermico($temperaturas) . "°C\n";
    echo "Menor Temperatura: " . detectarMenorTemperatura($temperaturas) . "°C\n";
    echo "Média Térmica: " . number_format(calcularMediaTermica($temperaturas), 1) . "°C\n";
    echo "Superaquecimentos (>75°C): " . contarSuperaquecimentos($temperaturas) . "\n\n";

    echo gerarExtratoCartao("Lucas Santos", 50.00, 100.00, 120.00) . "\n";
}

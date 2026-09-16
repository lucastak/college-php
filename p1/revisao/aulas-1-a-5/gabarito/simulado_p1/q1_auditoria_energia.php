<?php

/**
 * SIMULADO P1 - Questão 1 (INÉDITO): Auditoria de Consumo Elétrico em CSV
 */

function auditarConsumoEnergia(string $caminhoCsv, float $limiteKwh): array {
    if (!file_exists($caminhoCsv)) {
        return [
            'unidades_acima_limite' => [],
            'consumo_total_kwh' => 0.0,
            'arrecadacao_total' => 0.0
        ];
    }

    $conteudo = file_get_contents($caminhoCsv);
    if ($conteudo === false) {
        return [
            'unidades_acima_limite' => [],
            'consumo_total_kwh' => 0.0,
            'arrecadacao_total' => 0.0
        ];
    }

    $conteudo = str_replace("\r\n", "\n", $conteudo);
    $linhas = explode("\n", trim($conteudo));

    if (count($linhas) === 0) {
        return [
            'unidades_acima_limite' => [],
            'consumo_total_kwh' => 0.0,
            'arrecadacao_total' => 0.0
        ];
    }

    // Remove cabeçalho
    array_shift($linhas);

    $unidadesAcima = [];
    $consumoTotal = 0.0;
    $arrecadacaoTotal = 0.0;

    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '') {
            continue;
        }

        $colunas = explode(';', $linha);
        if (count($colunas) < 4) {
            continue;
        }

        $uc = trim($colunas[0]);
        $bandeira = mb_strtolower(trim($colunas[1]), 'UTF-8');
        $consumo = (float) trim($colunas[2]);
        $tarifa = (float) trim($colunas[3]);

        $consumoTotal += $consumo;

        $valorUC = $consumo * $tarifa;
        if ($bandeira === 'vermelha') {
            $valorUC += ($valorUC * 0.10); // 10% de sobretaxa
        }

        $arrecadacaoTotal += $valorUC;

        if ($consumo > $limiteKwh) {
            $unidadesAcima[] = $uc;
        }
    }

    return [
        'unidades_acima_limite' => $unidadesAcima,
        'consumo_total_kwh' => $consumoTotal,
        'arrecadacao_total' => $arrecadacaoTotal
    ];
}

// Demonstração se executado diretamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    echo "=== SIMULADO P1: Questão 1 (Auditoria de Energia) ===\n";
    $csv = dirname(__DIR__, 2) . '/dados/energia.csv';
    $resultado = auditarConsumoEnergia($csv, 400.0);

    echo "Unidades que excederam 400 kWh:\n";
    foreach ($resultado['unidades_acima_limite'] as $uc) {
        echo "- {$uc}\n";
    }

    echo "\nConsumo Total: {$resultado['consumo_total_kwh']} kWh\n";
    echo "Arrecadação Total Prevista: R$ " . number_format($resultado['arrecadacao_total'], 2, ',', '.') . "\n";
}

<?php

/**
 * GABARITO - MÓDULO 3: AULA 3 (Strings Multibyte, Hashes e Manipulação de CSV) - INÉDITO
 */

/**
 * Exercício 3.1a: Gera slugs amigáveis para URL tratando acentuação e limitando tamanho.
 */
function gerarSlugWeb(string $titulo): string {
    $mapa = [
        'á' => 'a', 'à' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a',
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'í' => 'i', 'ì' => 'i', 'î' => 'i', 'ï' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o',
        'ú' => 'u', 'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c', 'ñ' => 'n',
        'Á' => 'a', 'À' => 'a', 'Â' => 'a', 'Ã' => 'a', 'Ä' => 'a',
        'É' => 'e', 'È' => 'e', 'Ê' => 'e', 'Ë' => 'e',
        'Í' => 'i', 'Ì' => 'i', 'Î' => 'i', 'Ï' => 'i',
        'Ó' => 'o', 'Ò' => 'o', 'Ô' => 'o', 'Õ' => 'o', 'Ö' => 'o',
        'Ú' => 'u', 'Ù' => 'u', 'Û' => 'u', 'Ü' => 'u',
        'Ç' => 'c', 'Ñ' => 'n'
    ];

    $limpo = strtr($titulo, $mapa);
    $limpo = mb_strtolower(trim($limpo), 'UTF-8');
    $limpo = str_replace(' ', '-', $limpo);

    // Remove qualquer caractere que não seja letra, número ou hífen
    $resultado = '';
    $tam = mb_strlen($limpo, 'UTF-8');
    for ($i = 0; $i < $tam; $i++) {
        $char = mb_substr($limpo, $i, 1, 'UTF-8');
        if (($char >= 'a' && $char <= 'z') || ($char >= '0' && $char <= '9') || $char === '-') {
            $resultado .= $char;
        }
    }

    // Reduz múltiplos hífens consecutivos para um único hífen
    while (strpos($resultado, '--') !== false) {
        $resultado = str_replace('--', '-', $resultado);
    }
    $resultado = trim($resultado, '-');

    // Limita a no máximo 30 caracteres
    return mb_substr($resultado, 0, 30, 'UTF-8');
}

/**
 * Exercício 3.1b: Conta caracteres acentuados utilizando laço e mb_substr.
 */
function contarCaracteresAcentuados(string $texto): int {
    $acentuadas = [
        'á', 'à', 'â', 'ã', 'ä', 'é', 'è', 'ê', 'ë', 'í', 'ì', 'î', 'ï',
        'ó', 'ò', 'ô', 'õ', 'ö', 'ú', 'ù', 'û', 'ü', 'ç', 'ñ',
        'Á', 'À', 'Â', 'Ã', 'Ä', 'É', 'È', 'Ê', 'Ë', 'Í', 'Ì', 'Î', 'Ï',
        'Ó', 'Ò', 'Ô', 'Õ', 'Ö', 'Ú', 'Ù', 'Û', 'Ü', 'Ç', 'Ñ'
    ];

    $total = 0;
    $tam = mb_strlen($texto, 'UTF-8');
    for ($i = 0; $i < $tam; $i++) {
        $char = mb_substr($texto, $i, 1, 'UTF-8');
        if (in_array($char, $acentuadas, true)) {
            $total++;
        }
    }
    return $total;
}

/**
 * Exercício 3.2: Assinatura de requisições de API com SHA-256.
 */
function gerarAssinaturaRequisicao(string $metodo, string $url, string $payload, string $segredo): string {
    $dados = mb_strtoupper(trim($metodo), 'UTF-8') . '|' . trim($url) . '|' . trim($payload) . '|' . $segredo;
    return hash('sha256', $dados);
}

function validarAssinaturaRequisicao(string $assinaturaRecebida, string $metodo, string $url, string $payload, string $segredo): bool {
    $esperada = gerarAssinaturaRequisicao($metodo, $url, $payload, $segredo);
    return $assinaturaRecebida === $esperada;
}

/**
 * Exercício 3.3: Auditor de faturas corporativas em arquivo CSV.
 */
function processarFaturasCsv(string $caminhoCsv): array {
    if (!file_exists($caminhoCsv)) {
        return [
            'total_faturas' => 0,
            'valor_total_pago' => 0.0,
            'valor_total_pendente' => 0.0,
            'maior_fatura_pendente' => ''
        ];
    }

    $conteudo = file_get_contents($caminhoCsv);
    if ($conteudo === false) {
        return [
            'total_faturas' => 0,
            'valor_total_pago' => 0.0,
            'valor_total_pendente' => 0.0,
            'maior_fatura_pendente' => ''
        ];
    }

    $conteudo = str_replace("\r\n", "\n", $conteudo);
    $linhas = explode("\n", trim($conteudo));

    if (count($linhas) === 0) {
        return [
            'total_faturas' => 0,
            'valor_total_pago' => 0.0,
            'valor_total_pendente' => 0.0,
            'maior_fatura_pendente' => ''
        ];
    }

    // Remove cabeçalho
    array_shift($linhas);

    $totalFaturas = 0;
    $totalPago = 0.0;
    $totalPendente = 0.0;
    $maiorValorPendente = -1.0;
    $clienteMaiorPendente = '';

    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '') {
            continue;
        }

        $colunas = explode(';', $linha);
        if (count($colunas) < 5) {
            continue;
        }

        $cliente = trim($colunas[1]);
        $status = mb_strtolower(trim($colunas[4]), 'UTF-8');

        // Limpeza de moeda: remove espaços e 'R$', remove pontos de milhar e troca vírgula por ponto
        $valorStr = trim($colunas[3], " R$\t\n\r");
        $valorStr = str_replace('.', '', $valorStr);
        $valorStr = str_replace(',', '.', $valorStr);
        $valor = (float) $valorStr;

        $totalFaturas++;

        if ($status === 'pago') {
            $totalPago += $valor;
        } elseif ($status === 'pendente') {
            $totalPendente += $valor;
            if ($valor > $maiorValorPendente) {
                $maiorValorPendente = $valor;
                $clienteMaiorPendente = $cliente;
            }
        }
    }

    return [
        'total_faturas' => $totalFaturas,
        'valor_total_pago' => $totalPago,
        'valor_total_pendente' => $totalPendente,
        'maior_fatura_pendente' => $clienteMaiorPendente
    ];
}

// Demonstração se executado diretamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    echo "=== Testando Aula 3 (Inédito) ===\n";

    $titulo = "Aprenda Programação Web com Acentos e Emojis!";
    echo "Slug: " . gerarSlugWeb($titulo) . "\n";
    echo "Acentos em 'Programação é ótimo': " . contarCaracteresAcentuados("Programação é ótimo") . "\n";

    $segredo = "chave_api_privada_xyz";
    $sig = gerarAssinaturaRequisicao("POST", "/api/pagamento", '{"valor":150}', $segredo);
    echo "Assinatura: {$sig}\n";
    echo "Assinatura válida: " . (validarAssinaturaRequisicao($sig, "POST", "/api/pagamento", '{"valor":150}', $segredo) ? 'SIM' : 'NÃO') . "\n";

    $csvFaturas = dirname(__DIR__) . '/dados/faturas.csv';
    $resultadoFaturas = processarFaturasCsv($csvFaturas);
    echo "Total Faturas: {$resultadoFaturas['total_faturas']}\n";
    echo "Total Pago: R$ " . number_format($resultadoFaturas['valor_total_pago'], 2, ',', '.') . "\n";
    echo "Total Pendente: R$ " . number_format($resultadoFaturas['valor_total_pendente'], 2, ',', '.') . "\n";
    echo "Maior Devedor Pendente: {$resultadoFaturas['maior_fatura_pendente']}\n";
}

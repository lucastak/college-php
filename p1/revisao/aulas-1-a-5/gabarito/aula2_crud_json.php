<?php

/**
 * GABARITO - MÓDULO 2: AULA 2 (Arrays Associativos, Horários e CRUD em JSON) - INÉDITO
 */

/**
 * Exercício 2.1: Cálculo de duração de voo com explode e tratamento de virada de dia.
 */
function calcularDuracaoVoo(string $partida, string $chegada): string {
    $pPartida = explode(':', trim($partida));
    $pChegada = explode(':', trim($chegada));

    if (count($pPartida) !== 2 || count($pChegada) !== 2) {
        return '';
    }

    $h1 = (int) $pPartida[0];
    $m1 = (int) $pPartida[1];
    $h2 = (int) $pChegada[0];
    $m2 = (int) $pChegada[1];

    if ($h1 < 0 || $h1 > 23 || $m1 < 0 || $m1 > 59 || $h2 < 0 || $h2 > 23 || $m2 < 0 || $m2 > 59) {
        return '';
    }

    $minutosPartida = ($h1 * 60) + $m1;
    $minutosChegada = ($h2 * 60) + $m2;

    if ($minutosChegada < $minutosPartida) {
        $minutosChegada += 1440; // Adiciona 24 horas para voos que viram a meia-noite
    }

    $duracaoTotal = $minutosChegada - $minutosPartida;
    $horas = intdiv($duracaoTotal, 60);
    $minutos = $duracaoTotal % 60;

    return "{$horas}h e {$minutos}min";
}

/**
 * Exercício 2.2: Catálogo de Filmes em Streaming com Persistência JSON
 */

function buscarIndiceFilme(array $catalogo, int $id): int {
    foreach ($catalogo as $indice => $filme) {
        if (isset($filme['id']) && (int) $filme['id'] === $id) {
            return $indice;
        }
    }
    return -1;
}

function adicionarFilme(array &$catalogo, array $filme): bool {
    if (!isset($filme['id'], $filme['titulo'], $filme['genero'], $filme['ano'], $filme['nota'])) {
        return false;
    }

    $id = (int) $filme['id'];
    $nota = (float) $filme['nota'];

    if ($id <= 0 || $nota < 0.0 || $nota > 10.0) {
        return false;
    }

    if (buscarIndiceFilme($catalogo, $id) !== -1) {
        return false; // ID já cadastrado
    }

    $catalogo[] = [
        'id' => $id,
        'titulo' => (string) $filme['titulo'],
        'genero' => (string) $filme['genero'],
        'ano' => (int) $filme['ano'],
        'nota' => $nota
    ];
    return true;
}

function removerFilme(array &$catalogo, int $id): bool {
    $indice = buscarIndiceFilme($catalogo, $id);
    if ($indice === -1) {
        return false;
    }

    unset($catalogo[$indice]);
    $catalogo = array_values($catalogo); // Reindexa array
    return true;
}

function filtrarPorGenero(array $catalogo, string $genero): array {
    $generoAlvo = mb_strtolower(trim($genero), 'UTF-8');
    $filtrados = [];

    foreach ($catalogo as $filme) {
        if (isset($filme['genero']) && mb_strtolower(trim($filme['genero']), 'UTF-8') === $generoAlvo) {
            $filtrados[] = $filme;
        }
    }

    return $filtrados;
}

function salvarCatalogoJson(string $arquivo, array $catalogo): bool {
    $json = json_encode($catalogo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        return false;
    }
    return file_put_contents($arquivo, $json) !== false;
}

function carregarCatalogoJson(string $arquivo): array {
    if (!file_exists($arquivo)) {
        return [];
    }

    $conteudo = file_get_contents($arquivo);
    if ($conteudo === false || trim($conteudo) === '') {
        return [];
    }

    $dados = json_decode($conteudo, true);
    return is_array($dados) ? $dados : [];
}

// Demonstração se executado diretamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    echo "=== Testando Aula 2 (Inédito) ===\n";
    echo "Voo diurno (08:45 às 13:10): " . calcularDuracaoVoo("08:45", "13:10") . "\n";
    echo "Voo noturno (22:30 às 02:15): " . calcularDuracaoVoo("22:30", "02:15") . "\n";

    $filmes = [
        ['id' => 1, 'titulo' => 'Matrix', 'genero' => 'Ficção Científica', 'ano' => 1999, 'nota' => 8.7]
    ];

    adicionarFilme($filmes, ['id' => 2, 'titulo' => 'Duna', 'genero' => 'Ficção Científica', 'ano' => 2021, 'nota' => 8.0]);
    echo "Total de filmes: " . count($filmes) . "\n";

    $ficcao = filtrarPorGenero($filmes, 'ficção científica');
    echo "Filmes de Ficção Científica: " . count($ficcao) . "\n";
}

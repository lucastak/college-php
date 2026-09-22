<?php
// app.php
require_once 'vacina.php';
require_once 'repositorio-exception.php';
require_once 'repositorio-vacina.php';
require_once 'repositorio-vacina-em-bd.php';

use vac\RepositorioVacinaEmBD;
use vac\RepositorioException;

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=2021-1-p1;charset=utf8', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $repositorio = new RepositorioVacinaEmBD($pdo);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage() . "\n");
}

while (true) {
    echo "\n=== MENU ===\n";
    echo "0 - Sair\n";
    echo "1 - Listar vacinas\n";
    echo "2 - Calcular eficácia\n";
    
    $opcao = readline("Escolha uma opção: ");
    
    if ($opcao === '0') {
        echo "Saindo...\n";
        break;
    } elseif ($opcao === '1') {
        try {
            $vacinas = $repositorio->vacinas();
            if (empty($vacinas)) {
                echo "Nenhuma vacina encontrada.\n";
            } else {
                foreach ($vacinas as $vacina) {
                    $eficaciaFormatada = $vacina->getEficacia() * 100;
                    $eficaciaDeltaFormatada = $vacina->getEficaciaDelta() * 100;
                    echo "ID: {$vacina->getId()} | Nome: {$vacina->getNome()} | Fabricante: {$vacina->getFabricante()} | Doses: {$vacina->getDoses()} | Eficácia: {$eficaciaFormatada}% | Eficácia Delta: {$eficaciaDeltaFormatada}%\n";
                }
            }
        } catch (RepositorioException $e) {
            echo "Erro ao listar vacinas: " . $e->getMessage() . "\n";
        }
    } elseif ($opcao === '2') {
        $idStr = readline("Informe o ID da vacina: ");
        if (!is_numeric($idStr)) {
            echo "ID inválido.\n";
            continue;
        }
        $id = (int) $idStr;
        
        try {
            $vacina = $repositorio->vacinaComId($id);
            if ($vacina === null) {
                echo "Vacina não encontrada.\n";
            } else {
                $mesesStr = readline("Digite o número de meses: ");
                if (!is_numeric($mesesStr)) {
                    echo "Número de meses inválido.\n";
                    continue;
                }
                $meses = (int) $mesesStr;
                
                $consideraDeltaStr = readline("Considerar variante Delta? (S para sim): ");
                $consideraDelta = (mb_strtoupper(trim($consideraDeltaStr)) === 'S');
                
                $eficaciaCalculada = $vacina->eficaciaAposMeses($meses, $consideraDelta);
                $eficaciaCalculadaFormatada = $eficaciaCalculada * 100;
                
                echo "A eficácia da vacina {$vacina->getNome()} após {$meses} meses será de {$eficaciaCalculadaFormatada}%\n";
            }
        } catch (RepositorioException $e) {
            echo "Erro ao calcular eficácia: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Opção inválida!\n";
    }
}

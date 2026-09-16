<?php

/**
 * SIMULADO P1 - Questão 3 (INÉDITO): Gestão Integrada de Frota e Persistência em JSON
 */

namespace simulado;

require_once dirname(__DIR__) . '/aula4_poo/Veiculo.php';
require_once dirname(__DIR__) . '/aula4_poo/ItemLocacao.php';
require_once dirname(__DIR__) . '/aula4_poo/ContratoLocacao.php';
require_once dirname(__DIR__) . '/aula4_poo/NotificadorLocacao.php';
require_once dirname(__DIR__) . '/aula4_poo/EmissorContratoConsole.php';

use Veiculo;
use ContratoLocacao;
use EmissorContratoConsole;

class LocacaoException extends \DomainException {}

interface RepositorioContratosFechados {
    public function salvar(array $dadosContrato): void;
    public function obterTodos(): array;
}

class RepositorioContratosFechadosJson implements RepositorioContratosFechados {
    private $arquivo;

    public function __construct(string $arquivo) {
        $this->arquivo = $arquivo;
    }

    public function salvar(array $dadosContrato): void {
        $todos = $this->obterTodos();
        $todos[] = $dadosContrato;

        $json = json_encode($todos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new \RuntimeException("Erro ao gerar JSON do contrato.");
        }

        $res = @file_put_contents($this->arquivo, $json);
        if ($res === false) {
            throw new \RuntimeException("Falha de I/O ao persistir contrato em '{$this->arquivo}'.");
        }
    }

    public function obterTodos(): array {
        if (!file_exists($this->arquivo)) {
            return [];
        }

        $conteudo = @file_get_contents($this->arquivo);
        if ($conteudo === false || trim($conteudo) === '') {
            return [];
        }

        $dados = json_decode($conteudo, true);
        return is_array($dados) ? $dados : [];
    }
}

/**
 * Executa o ciclo de vida completo de locação com persistência
 */
function executarCicloLocacao(string $arquivoJsonDestino): void {
    echo "--- Iniciando Ciclo Completo de Locação de Veículos ---\n";

    $v1 = new Veiculo(1, "Hyundai HB20 1.0", "Hatch", 95.00);
    $v2 = new Veiculo(2, "Nissan Kicks 1.6", "SUV", 175.00);

    $contrato = new ContratoLocacao("Ana Clara Monteiro");

    if (!$contrato->adicionarItem($v1, 4, 15.00)) {
        throw new LocacaoException("Não foi possível alugar o veículo 1.");
    }
    if (!$contrato->adicionarItem($v2, 2, 25.00)) {
        throw new LocacaoException("Não foi possível alugar o veículo 2.");
    }

    // Desconto de 5%
    $contrato->concederDesconto(5.0);

    // Emite recibo e fecha contrato
    $emissor = new EmissorContratoConsole();
    $contrato->fecharContrato($emissor);

    // Persiste no repositório JSON
    $repo = new RepositorioContratosFechadosJson($arquivoJsonDestino);
    $registro = [
        'cliente' => $contrato->getCliente(),
        'subtotal' => $contrato->subtotal(),
        'desconto' => $contrato->getDescontoPercentual(),
        'total' => $contrato->total(),
        'qtd_veiculos' => count($contrato->getItens()),
        'data_fechamento' => date('Y-m-d H:i:s')
    ];

    $repo->salvar($registro);
    echo "\nContrato arquivado com sucesso no JSON!\n";
    echo "Total de contratos salvos: " . count($repo->obterTodos()) . "\n";
}

// Demonstração se executado diretamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'] ?? '')) {
    $arquivoJson = __DIR__ . '/historico_locacoes_teste.json';
    try {
        executarCicloLocacao($arquivoJson);
    } catch (\Throwable $e) {
        echo "Exceção: " . $e->getMessage() . "\n";
    } finally {
        if (file_exists($arquivoJson)) {
            unlink($arquivoJson);
        }
    }
}

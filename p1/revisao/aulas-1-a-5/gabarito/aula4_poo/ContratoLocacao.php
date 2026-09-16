<?php

require_once __DIR__ . '/Veiculo.php';
require_once __DIR__ . '/ItemLocacao.php';
require_once __DIR__ . '/NotificadorLocacao.php';

/**
 * GABARITO - AULA 4: Classe ContratoLocacao
 */
class ContratoLocacao {
    private $cliente;
    /** @var ItemLocacao[] */
    private $itens = [];
    private $fechado = false;
    private $descontoPercentual = 0.0;

    public function __construct(string $cliente) {
        $this->cliente = $cliente;
    }

    public function adicionarItem(Veiculo $veiculo, int $dias, float $seguroDiario = 20.0): bool {
        if ($this->fechado || $dias <= 0 || $seguroDiario < 0.0) {
            return false;
        }

        if (!$veiculo->isDisponivel()) {
            return false;
        }

        if (!$veiculo->reservar()) {
            return false;
        }

        $this->itens[] = new ItemLocacao($veiculo, $dias, $seguroDiario);
        return true;
    }

    public function removerItem(int $posicao): bool {
        if ($this->fechado) {
            return false;
        }

        if (!isset($this->itens[$posicao])) {
            return false;
        }

        $itemRemovido = $this->itens[$posicao];
        $itemRemovido->veiculo()->liberar();

        array_splice($this->itens, $posicao, 1);
        return true;
    }

    public function concederDesconto(float $percentual): bool {
        if ($this->fechado || $percentual < 0.0 || $percentual > 100.0) {
            return false;
        }

        $this->descontoPercentual = $percentual;
        return true;
    }

    public function subtotal(): float {
        $soma = 0.0;
        foreach ($this->itens as $item) {
            $soma += $item->subtotal();
        }
        return $soma;
    }

    public function total(): float {
        $sub = $this->subtotal();
        $valorDesconto = $sub * ($this->descontoPercentual / 100.0);
        return $sub - $valorDesconto;
    }

    public function fecharContrato(?NotificadorLocacao $notificador = null): void {
        if ($this->fechado) {
            return;
        }

        $this->fechado = true;

        if ($notificador !== null) {
            $notificador->notificar($this);
        }
    }

    public function isFechado(): bool {
        return $this->fechado;
    }

    public function getCliente(): string {
        return $this->cliente;
    }

    public function getItens(): array {
        return $this->itens;
    }

    public function getDescontoPercentual(): float {
        return $this->descontoPercentual;
    }
}

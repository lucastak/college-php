<?php

require_once __DIR__ . '/Veiculo.php';

/**
 * GABARITO - AULA 4: Classe ItemLocacao
 */
class ItemLocacao {
    private $veiculo;
    private $dias;
    private $taxaSeguroDiario;

    public function __construct(Veiculo $veiculo, int $dias, float $taxaSeguroDiario) {
        $this->veiculo = $veiculo;
        $this->dias = $dias;
        $this->taxaSeguroDiario = $taxaSeguroDiario;
    }

    public function veiculo(): Veiculo {
        return $this->veiculo;
    }

    public function dias(): int {
        return $this->dias;
    }

    public function taxaSeguro(): float {
        return $this->taxaSeguroDiario;
    }

    public function subtotal(): float {
        return ($this->veiculo->getDiaria() + $this->taxaSeguroDiario) * $this->dias;
    }
}

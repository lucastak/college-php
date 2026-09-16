<?php

/**
 * GABARITO - AULA 4: Entidade Veiculo
 */
class Veiculo {
    private $id;
    private $modelo;
    private $categoria;
    private $diaria;
    private $disponivel;

    public function __construct(int $id, string $modelo, string $categoria, float $diaria) {
        $this->id = $id;
        $this->modelo = $modelo;
        $this->categoria = $categoria;
        $this->diaria = $diaria;
        $this->disponivel = true;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getModelo(): string {
        return $this->modelo;
    }

    public function getCategoria(): string {
        return $this->categoria;
    }

    public function getDiaria(): float {
        return $this->diaria;
    }

    public function isDisponivel(): bool {
        return $this->disponivel;
    }

    public function reservar(): bool {
        if (!$this->disponivel) {
            return false;
        }
        $this->disponivel = false;
        return true;
    }

    public function liberar(): void {
        $this->disponivel = true;
    }
}

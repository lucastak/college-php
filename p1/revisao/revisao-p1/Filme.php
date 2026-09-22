<?php

class Filme {
    public $id = 0;
    public $titulo = '';
    public $precoDiaria = 0.00;
    public $categoriaId = 0;
    public $atoresIds = []; // array de inteiros

    public function __construct(
        $id = 0,
        $titulo = '',
        $precoDiaria = 0.00,
        $categoriaId = 0,
        array $atoresIds = []
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->precoDiaria = $precoDiaria;
        $this->categoriaId = $categoriaId;
        $this->atoresIds = $atoresIds;
    }
}

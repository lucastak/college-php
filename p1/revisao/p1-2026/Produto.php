<?php

class Produto {
    public $id, $descricao, $preco, $numeroSetores, $totalEstoque;

    public function __construct(
        $id = 0, $descricao = '', $preco = 0, $numeroSetores = 0, $totalEstoque = 0
    ) {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->numeroSetores = $numeroSetores;
        $this->totalEstoque = $totalEstoque;
    }
}

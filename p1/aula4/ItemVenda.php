<?php

require_once 'Produto.php';

class ItemVenda {
    private $produto;
    private int $quantidade;

    public function __construct($produto, $quantidade) {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
    }

    public function produto() {
        return $this->produto;
    }
    
    public function quantidade() {
        return $this->quantidade;
    }

    public function subTotal() {
        return $this->produto->preco * $this->quantidade;
    }
}

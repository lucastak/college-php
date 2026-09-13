<?php

class ItemVenda {

    private $quantidade;
    private $produto;

    /**
     * Construtor
     * @param Produto $produto
     * @param int $quantidade
     */
    public function __construct( $produto, $quantidade ) {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
    }

    public function subtotal() {
        return $this->getQuantidade() * $this->getProduto()->preco;
    }

    public function getProduto() {
        return $this->produto;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

}
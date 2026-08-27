<?php

require_once 'ItemVenda.php';
require_once 'Produto.php';

class Venda {
    private $produtos_disponiveis = [];
    private $itens = [];
    private $finalizada = false;

    public function __construct($produtos) {
        $this->produtos_disponiveis = $produtos;
    }

    public function adicionarItem($idProduto, $quantidade) {
        if ($this->finalizada) {
            return;
        }

        $produtoEncontrado = null;
        
        foreach ($this->produtos_disponiveis as $produto) {
            if ($produto->id === $idProduto) {
                $produtoEncontrado = $produto;
                break;
            }
        }
        
        if ($produtoEncontrado !== null) {
            $this->itens[] = new ItemVenda($produtoEncontrado, $quantidade);
        }
    }

    public function removerItem($posicao) {
        if ($this->finalizada) {
            return;
        }

        if (isset($this->itens[$posicao])) {
            splice($this->itens, $posicao, 1);
        }
    }

    public function finalizar() {
        if ($this->finalizada) {
            return;
        }
        
        foreach ($this->itens as $item) {
            $produto = $item->produto();
            $produto->estoque -= $item->quantidade();
        }

        $this->finalizada = true;
    }

    public function subTotal() {
        $total = 0.0;
        foreach ($this->itens as $item) {
            $total += $item->subTotal();
        }
        return $total;
    }

    public function getItens() {
        return $this->itens;
    }
}

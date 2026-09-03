<?php

require_once 'ItemVenda.php';
require_once 'Produto.php';
require_once 'ImpressoraVenda.php';

class Venda implements ImpressoraVenda {
    private $produtos = [];
    private $itens = [];
    private $finalizada = false;
    private $desconto = 0;

    public function __construct($produtos) {
        $this->produtos = $produtos;
    }

    public function adicionarItem($idProduto, $quantidade) {
        if ($this->finalizada) {
            return;
        }

        $produtoEncontrado = null;
        
        foreach ($this->produtos as $produto) {
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
            array_splice($this->itens, $posicao, 1);
            //$this->itens = array_values($this->itens);
        }
    }

    public function concederDesconto($percentual) {
        if ($this->finalizada) {
            return;
        }
        
        $this->desconto = $percentual;
    }

    public function total() {
        return $this->subTotal() - ($this->subTotal() * $this->desconto / 100);
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
        $this->mprimir($this);
    }

    public function imprimir(Venda $v) {
        echo "Subtotal: " . $v->subTotal() . "\n";
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

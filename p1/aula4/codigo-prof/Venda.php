<?php

class Venda {

    private $produtos;
    private $itens = [];

    /**
     * Construtor
     * @param Produto[] $produtos
     */
    public function __construct( $produtos ) {
        $this->produtos = $produtos;
    }

    public function adicionarItem( $idProduto, $quantidade) {
        $produto = $this->produtoComId( $idProduto );
        if ( $produto === null ) {
            return false;
        }
        $iv = new ItemVenda( $produto, $quantidade );
        $this->itens []= $iv; // Adiciona no array
        return true;
    }

    protected function produtoComId( $id ) {
        foreach ( $this->produtos as $p ) {
            if ( $p->id == $id ) {
                return $p;
            }
        }
        return null;
    }

    public function removerItem( $posicao ) {
        unset( $this->itens[ $posicao ] );
        $this->itens = array_values( $this->itens ); // Refaz os índices
    }

    public function subtotal() {
        $total = 0;
        foreach ( $this->itens as $iv ) {
            $total += $iv->subtotal();
        }
        return $total;
    }
}
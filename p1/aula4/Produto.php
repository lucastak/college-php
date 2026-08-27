<?php

class Produto {
    public $id = 0;
    public $descricao = '';
    public $preco = 0.00;
    public $estoque = 0;

    public function __construct( $id, $descricao, $preco, $estoque ) {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->preco = $preco;
        $this->estoque = $estoque;
    }

    public function inventario() {
        return $this->preco * $this->estoque;
    }

    public static function criar(array $a) {
        return new Produto(
            $a['id'] ?? 0,
            $a['descricao'] ?? '',
            $a['preco'] ?? 0.00,
            $a['estoque'] ?? 0
        );
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'preco' => $this->preco,
            'estoque' => $this->estoque
        ];
    }
}
?>  
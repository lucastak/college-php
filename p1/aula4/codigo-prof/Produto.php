<?php
class Produto {
    public $id = 0;
    public $descricao = '';
    public $estoque = 0;
    public $preco = 0;

    public function __construct( $id, $descricao, $estoque, $preco ) {
        $this->id = $id;
        $this->descricao = $descricao;
        $this->estoque = $estoque;
        $this->preco = $preco;
    }

    public static function criar( array $a ) {
        return new Produto(
            $a['id'] ?? 0,
            $a['descricao'] ?? '',
            $a['estoque'] ?? 0,
            $a['preco'] ?? 0,
        );
    }


    public function inventario() {
        return $this->estoque * $this->preco;
    }

    public function toArray() {
        return [
            'id' => $this->id,
            'descricao' => $this->descricao,
            'estoque' => $this->estoque,
            'preco' => $this->preco
        ];
    }
}
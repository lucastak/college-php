<?php
namespace repositorio;

/** Repositório de Produtos. Apenas lança RepositorioException em seus métodos. */
interface RepositorioProdutos {

    /** Retorna um array de objetos da classe Produto.
        Cada produto deve conter o número de setores em que possui estoque e
        a quantidade total em estoque (soma da quantidade em todos os setores). */
    public function todos();

    /** Aumenta a quantidade em estoque de um certo produto em um certo setor. */
    public function aumentarEstoque( $idProduto, $idSetor, $quantidade );
}

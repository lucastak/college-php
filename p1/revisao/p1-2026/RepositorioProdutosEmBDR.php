<?php
namespace App;

require_once 'Produto.php';
require_once 'RepositorioException.php';
require_once 'RepositorioProdutos.php';

use repositorio\RepositorioProdutos;
use repositorio\RepositorioException;
use PDO;
use PDOException;

class RepositorioProdutosEmBDR implements RepositorioProdutos {
    private PDO $pdo;

    public function __construct( PDO $pdo ) {
        $this->pdo = $pdo;
    }

    public function todos() {
        try {
            $stmtProd = $this->pdo->query('SELECT * FROM produto');
            $stmtEstoque = $this->pdo->prepare('SELECT COUNT(setor_id) AS numeroSetores, SUM(quantidade) AS totalEstoque FROM estoque WHERE produto_id = ?');
            
            $linhas = $stmtProd->fetchAll( PDO::FETCH_ASSOC );
            $produtos = [];

            foreach ( $linhas as $linha ) {
                // Busca a contagem e a soma do estoque deste produto
                $stmtEstoque->execute( [ $linha['id'] ] );
                $dadosEstoque = $stmtEstoque->fetch( PDO::FETCH_ASSOC );

                $numeroSetores = $dadosEstoque['numeroSetores'];
                $totalEstoque = $dadosEstoque['totalEstoque'] ?? 0;

                $produtos[] = new \Produto(
                    $linha['id'],
                    $linha['descricao'],
                    $linha['preco'],
                    $numeroSetores,
                    $totalEstoque
                );
            }

            return $produtos;
        } catch ( PDOException $e ) {
            throw new RepositorioException( 'Erro ao listar produtos: ' . $e->getMessage() );
        }
    }

    public function aumentarEstoque( $idProduto, $idSetor, $quantidade ) {
        try {
            $sql = '
                UPDATE estoque 
                SET quantidade = quantidade + ? 
                WHERE produto_id = ? AND setor_id = ?
            ';

            $stmt = $this->pdo->prepare( $sql );
            $stmt->execute( [ $quantidade, $idProduto, $idSetor ] );
        } catch ( PDOException $e ) {
            throw new RepositorioException( 'Erro ao aumentar estoque: ' . $e->getMessage() );
        }
    }
}

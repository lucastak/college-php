<?php

require_once 'RepositorioFilme.php';

class RepositorioFilmeEmBDR implements RepositorioFilme {
    private $pdo;

    public function __construct( PDO $pdo ) {
        $this->pdo = $pdo;
    }

    public function adicionar( Filme &$filme ) {
        try {
            $this->pdo->beginTransaction();

            // 1. Insere o filme principal
            $sqlFilme = 'INSERT INTO filme (titulo, preco_diaria, categoria_id) VALUES (?, ?, ?)';
            $stmtFilme = $this->pdo->prepare( $sqlFilme );
            $stmtFilme->execute( [
                $filme->titulo,
                $filme->precoDiaria,
                $filme->categoriaId
            ] );

            $filme->id = (int)$this->pdo->lastInsertId();

            // 2. Insere os relacionamentos com atores (tabela associativa NxN)
            if ( !empty( $filme->atoresIds ) ) {
                $sqlAtor = 'INSERT INTO filme_ator (filme_id, ator_id) VALUES (?, ?)';
                $stmtAtor = $this->pdo->prepare( $sqlAtor );

                foreach ( $filme->atoresIds as $atorId ) {
                    $stmtAtor->execute( [ $filme->id, $atorId ] );
                }
            }

            $this->pdo->commit();
        } catch ( PDOException $e ) {
            if ( $this->pdo->inTransaction() ) {
                $this->pdo->rollBack();
            }
            throw new RepositorioException( 'Erro ao adicionar filme: ' . $e->getMessage(), 0, $e );
        }
    }

    public function remover( $id ) {
        try {
            $this->pdo->beginTransaction();

            // 1. Remove primeiro as dependências na tabela associativa
            $sqlRel = 'DELETE FROM filme_ator WHERE filme_id = ?';
            $stmtRel = $this->pdo->prepare( $sqlRel );
            $stmtRel->execute( [ $id ] );

            // 2. Remove o registro principal
            $sqlFilme = 'DELETE FROM filme WHERE id = ?';
            $stmtFilme = $this->pdo->prepare( $sqlFilme );
            $stmtFilme->execute( [ $id ] );

            // Se nenhuma linha do filme foi afetada, o filme não existia
            if ( $stmtFilme->rowCount() === 0 ) {
                $this->pdo->rollBack();
                return false;
            }

            $this->pdo->commit();
            return true;
        } catch ( PDOException $e ) {
            if ( $this->pdo->inTransaction() ) {
                $this->pdo->rollBack();
            }
            throw new RepositorioException( 'Erro ao remover filme: ' . $e->getMessage(), 0, $e );
        }
    }
}

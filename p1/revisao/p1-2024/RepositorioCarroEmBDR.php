<?php
// RepositorioCarroEmBDR.php
// Questão 2 a) - 2024/1

require_once 'Carro.php';
require_once 'RepositorioCarro.php';
require_once 'RepositorioException.php';

use excecoes\RepositorioException;

class RepositorioCarroEmBDR implements RepositorioCarro {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function adicionar(Carro &$carro) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO carro (nome, fabricante, preco) VALUES (?, ?, ?)");
            $stmt->execute([
                $carro->nome,
                $carro->fabricante,
                $carro->preco
            ]);
            $carro->id = $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new RepositorioException("Erro ao adicionar carro: " . $e->getMessage());
        }
    }

    public function atualizar(Carro $carro) {
        try {
            $stmt = $this->pdo->prepare("UPDATE carro SET nome = ?, fabricante = ?, preco = ? WHERE id = ?");
            $stmt->execute([
                $carro->nome,
                $carro->fabricante,
                $carro->preco,
                $carro->id
            ]);
        } catch (PDOException $e) {
            throw new RepositorioException("Erro ao atualizar carro: " . $e->getMessage());
        }
    }

    public function atualizarPrecosEmPercentual($percentual) {
        try {
            $fator = 1 + ($percentual / 100);
            
            $this->pdo->beginTransaction();
            
            $stmt = $this->pdo->prepare("UPDATE carro SET preco = preco * ?");
            $stmt->execute([$fator]);
            
            $this->pdo->commit();
        } catch (PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new RepositorioException("Erro ao atualizar preços em percentual: " . $e->getMessage());
        }
    }

    public function removerPeloId($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM carro WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            throw new RepositorioException("Erro ao remover carro com id {$id}: " . $e->getMessage());
        }
    }

    public function todos() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM carro");
            $carros = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $carros[] = new Carro(
                    $row['id'],
                    $row['nome'],
                    $row['fabricante'],
                    $row['preco']
                );
            }
            return $carros;
        } catch (PDOException $e) {
            throw new RepositorioException("Erro ao buscar todos os carros: " . $e->getMessage());
        }
    }
}

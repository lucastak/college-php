<?php
// repositorio-lutador-em-bdr.php
// Questão 3 b) - 2023/1

namespace Mma;

require_once 'repositorio-lutador.php';

use PDO;
use PDOException;
use Exception;

class RepositorioLutadorEmBDR implements RepositorioLutador {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function adicionar(\Lutador $lutador) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO lutador (nome, peso_em_quilos, altura_em_metros)
                VALUES (?, ?, ?)
            ");
            $stmt->execute([
                $lutador->nome,
                $lutador->pesoEmQuilos,
                $lutador->alturaEmMetros
            ]);
            $lutador->id = $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao adicionar lutador: " . $e->getMessage());
        }
    }

    public function remover($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM lutador WHERE id = ?");
            $stmt->execute([$id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao remover lutador: " . $e->getMessage());
        }
    }
}

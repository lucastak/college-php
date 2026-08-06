<?php
namespace vac;

require_once 'vacina.php';
require_once 'repositorio-vacina.php';
require_once 'repositorio-exception.php';

use PDO;
use PDOException;

class RepositorioVacinaEmBD implements RepositorioVacina {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function vacinas() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM vacina");
            $resultado = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $resultado[] = $this->criarVacinaDoArray($row);
            }
            return $resultado;
        } catch (PDOException $e) {
            throw new RepositorioException("Erro ao buscar vacinas: " . $e->getMessage());
        }
    }

    public function vacinaComId($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM vacina WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $this->criarVacinaDoArray($row);
            }
            return null;
        } catch (PDOException $e) {
            throw new RepositorioException("Erro ao buscar vacina com id {$id}: " . $e->getMessage());
        }
    }

    public function atualizarVacima(Vacina $vacina) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE vacina 
                SET nome = ?, fabricante = ?, doses = ?, eficacia = ?, eficacia_delta = ?, perda_mensal = ? 
                WHERE id = ?
            ");
            $stmt->execute([
                $vacina->getNome(),
                $vacina->getFabricante(),
                $vacina->getDoses(),
                $vacina->getEficacia(),
                $vacina->getEficaciaDelta(),
                $vacina->getPerdaMensal(),
                $vacina->getId()
            ]);
        } catch (PDOException $e) {
            throw new RepositorioException("Erro ao atualizar vacina: " . $e->getMessage());
        }
    }

    private function criarVacinaDoArray($row) {
        return new Vacina(
            $row['id'],
            $row['nome'],
            $row['fabricante'],
            $row['doses'],
            $row['eficacia'],
            $row['eficacia_delta'],
            $row['perda_mensal']
        );
    }
}

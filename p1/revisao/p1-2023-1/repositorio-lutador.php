<?php
// repositorio-lutador.php
// Questão 3 a) - 2023/1

namespace Mma;

require_once 'lutador.php';

interface RepositorioLutador {
    public function adicionar(\Lutador $lutador);
    public function remover($id);
}

<?php

namespace cefet\persistencia;

require_once __DIR__ . '/tarefa.php';

use cefet\Tarefa;

interface RepositorioTarefas {
    public function adicionar(Tarefa $tarefa);
    public function obterTodas(): array;
}

<?php

require_once 'tarefa.php';
require_once 'tarefa-exception.php';

use cefet\Tarefa;
use cefet\excecoes\TarefaExcecao;

try{
    $t1 = new Tarefa('le', false);
    echo $t1->getDescricao();
} catch (TarefaExcecao $e) {
    echo $e->getMessage();
}

<?php

namespace cefet;

use cefet\excecoes\TarefaExcecao;

class Tarefa {
    private $descricao;
    private $feita;

    public function __construct( $descricao, $feita = false ) {
        $this->validar($descricao);

        $this->descricao = $descricao;
        $this->feita = $feita;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getFeita() {
        return $this->feita;
    }

    private function validar($descricao) {
        if (strlen($descricao) < 3 || strlen($descricao) > 50) {
            throw new TarefaExcecao("Descrição inválida");
        }
    }
}

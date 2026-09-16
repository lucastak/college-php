<?php

namespace cefet;

require_once __DIR__ . '/TarefaException.php';
require_once __DIR__ . '/ConversivelParaJson.php';

use cefet\excecoes\TarefaException;

class Tarefa {
    use ConversivelParaJson;

    private $descricao;
    private $feita;

    public function __construct($descricao, $feita = false) {
        $this->descricao = $descricao;
        $this->feita = $feita;
        $this->validar();
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
        $this->validar();
    }

    public function getFeita() {
        return $this->feita;
    }

    public function setFeita($feita) {
        $this->feita = $feita;
    }

    private function validar() {
        $tamanhoDescricao = mb_strlen($this->descricao);
        if ($tamanhoDescricao < 3 || $tamanhoDescricao > 50) {
            throw new TarefaException("A descrição deve ter entre 3 e 50 caracteres. Fornecido: {$tamanhoDescricao}");
        }
    }

    // Abordagem 2: Tabela / Array de Regras (sem múltiplos if)
    private function validar2() {
        $tamanho = mb_strlen(trim($this->descricao));

        $regras = [
            "A descrição não pode ser vazia."
                => empty(trim($this->descricao)),

            "A descrição deve ter entre 3 e 50 caracteres. Fornecido: {$tamanho}"
                => $tamanho < 3 || $tamanho > 50,

            "O campo 'feita' deve ser um booleano."
                => !is_bool($this->feita),

            "Descrição deve ser string"
                => !is_string($this->descricao),
        ];

        foreach ($regras as $mensagem => $invalido) {
            if ($invalido) {
                throw new TarefaException($mensagem);
            }
        }
    }
}

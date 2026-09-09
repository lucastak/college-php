<?php
namespace cefet;

require_once 'TarefaException.php';
require_once 'ConversivelParaJson.php';

use cefet\excecoes\TarefaException;

class Tarefa {

    private $descricao;
    private $feita;

    use ConversivelParaJson;

    public function __construct( $descricao, $feita = false ) {
        $this->descricao = $descricao;
        $this->feita = $feita;
        $this->validar();
    }

    private function validar() {
        $tamanhoDescricao = mb_strlen( $this->descricao );
        if ( $tamanhoDescricao < 3 || $tamanhoDescricao > 50 ) {
            throw new TarefaException( 'A descrição deve ter de 3 a 50 caracteres.' );
        }
    }

    public function getDescricao() { return $this->descricao; }
    public function getFeita() { return $this->feita; }
}


$t = new Tarefa( 'Teste', true );
echo $t->toJson();
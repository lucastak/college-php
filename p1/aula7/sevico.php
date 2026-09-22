<?php

require_once "ValidacaoException.php";

class Servico {
    public $id;
    public $nome;
    public $valor;

    public function __construct(
        $id,
        $nome,
        $valor
    ) {
        $this->id = $id;
        $this->nome = $nome;
        $this->valor = $valor;
        $this->validar();
    }

    protected function validar(){
        $validacoes = [
            "O valor deve ser maior que 0" => $this->valor < 1,
            "O ID deve ser um número não negativo" => !is_numeric($this->id) || $this->id < 0,
            "A descrição não pode ser vazia" => $this->nome == '',
        ];

        foreach( $validacoes as $mensagem => $invalido ) {
            if ( $invalido ) {
                throw new ValidacaoException($mensagem);
            }
        }
    }

}
<?php

namespace cefet\excecoes;

class TarefaExcecao extends \Exception{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}

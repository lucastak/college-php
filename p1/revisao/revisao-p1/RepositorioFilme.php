<?php

require_once 'Filme.php';
require_once 'RepositorioException.php';

interface RepositorioFilme {
    /**
     * Adiciona um filme e suas associações com atores.
     * Atualiza o $filme->id.
     */
    public function adicionar( Filme &$filme );

    /**
     * Remove o filme e suas associações em filme_ator.
     * Retorna true se removeu ou false caso contrário.
     */
    public function remover( $id );
}

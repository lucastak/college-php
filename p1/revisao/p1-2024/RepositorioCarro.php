<?php
// RepositorioCarro.php
// Questão 2 - 2024/1

require_once 'Carro.php';

interface RepositorioCarro {
    function adicionar( Carro &$carro );
    function atualizar( Carro $carro );
    function atualizarPrecosEmPercentual( $percentual );
    function removerPeloId( $id );
    function todos();
}

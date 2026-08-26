<?php
require_once 'estoque-listar.php';
require_once 'estoque-util.php';
require_once 'estoque-cadastrar.php';
require_once 'estoque-remover.php';
require_once 'estoque-alterar.php';

const OPCAO_SAIR = '0';
const OPCAO_LISTAR = '1';
const OPCAO_CADASTRAR = '2';
const OPCAO_REMOVER = '3';
const OPCAO_ALTERAR = '4';

// $produtos = [
//     ['codigo'=>'100', 'descricao'=>'Sapato Nike', 'estoque'=>200, 'preco'=>800.00],
//     ['codigo'=>'101', 'descricao'=>'Placa de Vídeo', 'estoque'=>50, 'preco'=>7_000.00],
//     ['codigo'=>'102', 'descricao'=>'Máquina de Lavar', 'estoque'=>25, 'preco'=>1_800.00],
// ];
// echo $produtos[ 2 ]['preco'];



$jsonString = file_get_contents( 'produtos.json' );
$produtos = json_decode( $jsonString, true );

do {
    echo criarTitulo( 'MENU' );
    echo OPCAO_SAIR, ") Sair\n";
    echo OPCAO_LISTAR, ") Listar\n";
    echo OPCAO_CADASTRAR, ") Cadastrar\n";
    echo OPCAO_REMOVER, ") Remover\n";
    echo OPCAO_ALTERAR, ") Alterar\n";
    $opcao = readline( 'Opção desejada: ' );
    if ( $opcao == OPCAO_LISTAR ) {
        listar( $produtos );
    } else if ( $opcao == OPCAO_CADASTRAR ) {
        cadastrar( $produtos );
    } else if ( $opcao == OPCAO_REMOVER ) {
        remover( $produtos );
    } else if ( $opcao == OPCAO_ALTERAR ) {
        alterar( $produtos );
    }
} while ( $opcao != OPCAO_SAIR );

$jsonString = json_encode( $produtos );
file_put_contents( 'produtos.json', $jsonString );

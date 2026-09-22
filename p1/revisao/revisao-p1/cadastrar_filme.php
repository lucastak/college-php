<?php

require_once 'Filme.php';
require_once 'RepositorioFilmeEmBDR.php';

// Conexão PDO
try {
    $pdo = new PDO( 'mysql:host=localhost;dbname=locadora;charset=utf8', 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ] );
} catch ( PDOException $e ) {
    die( 'Erro ao conectar ao banco de dados: ' . $e->getMessage() . PHP_EOL );
}

// 1. Leitura e Validação do Título
$titulo = trim( readline( 'Título do filme: ' ) );
if ( mb_strlen( $titulo ) < 2 || mb_strlen( $titulo ) > 100 ) {
    die( 'Erro: O título deve ter entre 2 e 100 caracteres.' . PHP_EOL );
}

// 2. Leitura e Validação do Preço
$precoStr = trim( readline( 'Preço da diária (ex: 12.50): ' ) );
if ( !is_numeric( $precoStr ) || (float)$precoStr <= 0 ) {
    die( 'Erro: O preço deve ser um número maior que zero.' . PHP_EOL );
}
$precoDiaria = (float)$precoStr;

// 3. Leitura e Validação da Categoria
$catStr = trim( readline( 'ID da categoria: ' ) );
if ( !is_numeric( $catStr ) || (int)$catStr <= 0 ) {
    die( 'Erro: O ID da categoria deve ser um número inteiro positivo.' . PHP_EOL );
}
$categoriaId = (int)$catStr;

// 4. Leitura opcional dos IDs dos atores (separados por vírgula)
$atoresEntrada = trim( readline( 'IDs dos atores separados por vírgula (ex: 1, 2) [Enter para nenhum]: ' ) );
$atoresIds = [];

if ( $atoresEntrada !== '' ) {
    $partes = explode( ',', $atoresEntrada );
    foreach ( $partes as $parte ) {
        $idAtor = trim( $parte );
        if ( is_numeric( $idAtor ) && (int)$idAtor > 0 ) {
            $atoresIds[] = (int)$idAtor;
        }
    }
}

// 5. Instanciação e Persistência
$filme = new Filme( 0, $titulo, $precoDiaria, $categoriaId, $atoresIds );
$repo = new RepositorioFilmeEmBDR( $pdo );

try {
    $repo->adicionar( $filme );
    echo "Filme cadastrado com sucesso! ID gerado: {$filme->id}" . PHP_EOL;
} catch ( RepositorioException $e ) {
    echo "Falha ao cadastrar filme: " . $e->getMessage() . PHP_EOL;
}

<?php
// remover.php
// Questão 3 c) - 2023/1

require_once 'repositorio-lutador-em-bdr.php';

use Mma\RepositorioLutadorEmBDR;

$id1Str = readline("Informe o ID do primeiro lutador a remover: ");
$id2Str = readline("Informe o ID do segundo lutador a remover: ");

if (!is_numeric($id1Str) || !is_numeric($id2Str)) {
    die("IDs informados devem ser numéricos.\n");
}

$id1 = (int) $id1Str;
$id2 = (int) $id2Str;

try {
    $pdo = new PDO('mysql:host=localhost;dbname=mma;charset=utf8', 'dev', '123456');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $repositorio = new RepositorioLutadorEmBDR($pdo);
    
    $pdo->beginTransaction();
    
    $repositorio->remover($id1);
    $repositorio->remover($id2);
    
    $pdo->commit();
    echo "Lutadores removidos com sucesso.\n";
    
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Erro na remoção. Operação cancelada (Rollback).\nDetalhes: " . $e->getMessage() . "\n";
}

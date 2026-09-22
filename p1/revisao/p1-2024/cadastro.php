<?php
// cadastro.php
// Questão 2 b) - 2024/1

require_once 'Carro.php';
require_once 'RepositorioCarroEmBDR.php';
require_once 'RepositorioException.php';

use excecoes\RepositorioException;

echo "=== CADASTRO DE CARRO ===\n";

$nome = trim(readline("Informe o nome do carro: "));
if (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
    die("Erro: O nome deve ter entre 2 e 100 caracteres.\n");
}

$fabricante = trim(readline("Informe o fabricante: "));
if (mb_strlen($fabricante) > 60) {
    die("Erro: O fabricante deve ter no máximo 60 caracteres.\n");
}

if (preg_match('/[0-9]/', $fabricante)) {
    die("Erro: O fabricante não pode conter números.\n");
}

$precoStr = trim(readline("Informe o preço do carro: "));
if (!is_numeric($precoStr)) {
    die("Erro: O preço deve ser um número numérico.\n");
}
$preco = (float) $precoStr;
if ($preco < 5000) {
    die("Erro: O preço deve ser igual ou superior a cinco mil (5000).\n");
}

try {
    $pdo = new PDO('mysql:host=192.168.0.10;dbname=p1;charset=utf8', 'gerente', 'g3X$t0R');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $repositorio = new RepositorioCarroEmBDR($pdo);
    
    $novoCarro = new Carro(0, $nome, $fabricante, $preco);
    
    $repositorio->adicionar($novoCarro);
    
    echo "Carro salvo com sucesso! O ID do novo carro é: {$novoCarro->id}\n";

} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage() . "\n");
} catch (RepositorioException $e) {
    die("Erro no repositório: " . $e->getMessage() . "\n");
}

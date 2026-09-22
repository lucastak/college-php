<?php
// começa transação -> prepare -> execute -> commit
// catch 
// se tiver em transação -> rollback

$valorMinimo = readline("Digite o valor mínimo: ");

if (is_numeric($valorMinimo)) {
    die("Valor inválido" . PHP_EOL);
}


try {
    $pdo = new PDO("mysql:dbname=cefet;host=127.0.0.1;charset=utf8", "root", "root");

    $pdo->beginTransaction();
    $stmt = $pdo->prepare("UPDATE servico SET valor = valor * 1.1 WHERE valor >= ?");

    $stmt->execute([$valorMinimo]);
    $pdo->commit();

    echo "Total de serviços atualizados: " . $stmt->rowCount() . PHP_EOL;
} catch (PDOException $e) {
    $pdo->rollBack();
    echo "Erro ao atualizar serviços: " . $e->getMessage() . PHP_EOL;
}

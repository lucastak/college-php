<?php

// 1. Leitura e validação dos dados de entrada
$descricao = trim(readline("Descrição do produto: "));
$tamDesc = mb_strlen($descricao);
if ($tamDesc < 2 || $tamDesc > 100) {
    die("Erro: A descrição deve ter entre 2 e 100 caracteres." . PHP_EOL);
}

$precoStr = trim(readline("Preço do produto (ex: 10.50): "));
if (!is_numeric($precoStr) || $precoStr < 0.50) {
    die("Erro: O preço deve ser um número e no mínimo R$ 0,50." . PHP_EOL);
}
$preco = $precoStr;

$qtdStr = trim(readline("Quantidade: "));
if (!is_numeric($qtdStr) || $qtdStr < 1) {
    die("Erro: A quantidade deve ser um número e no mínimo 1." . PHP_EOL);
}
$quantidade = $qtdStr;

$codigoSetor = trim(readline("Código do setor (3 caracteres): "));
if (mb_strlen($codigoSetor) !== 3) {
    die("Erro: O código do setor deve ter exatamente 3 caracteres." . PHP_EOL);
}

// 2. Conexão PDO e controle de transação
try {
    $pdo = new PDO('mysql:host=192.168.0.1;dbname=p1;charset=utf8', 'dev', 'pHp_d3V', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $pdo->beginTransaction();

    // 2.1 Verifica se o setor existe e obtém seu id
    $stmtSetor = $pdo->prepare('SELECT id FROM setor WHERE codigo = ?');
    $stmtSetor->execute([$codigoSetor]);
    $setor = $stmtSetor->fetch(PDO::FETCH_ASSOC);

    if (!$setor) {
        $pdo->rollBack();
        die("Erro: O setor informado não existe." . PHP_EOL);
    }
    $setorId = $setor['id'];

    // 2.2 Cadastra o produto
    $stmtProd = $pdo->prepare('INSERT INTO produto (descricao, preco) VALUES (?, ?)');
    $stmtProd->execute([$descricao, $preco]);
    $produtoId = $pdo->lastInsertId();

    // 2.3 Cadastra o registro de estoque
    $stmtEst = $pdo->prepare('INSERT INTO estoque (produto_id, setor_id, quantidade) VALUES (?, ?, ?)');
    $stmtEst->execute([$produtoId, $setorId, $quantidade]);

    $pdo->commit();
    echo "Produto cadastrado com sucesso! ID: {$produtoId}" . PHP_EOL;

} catch (PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Erro no banco de dados: " . $e->getMessage() . PHP_EOL);
}

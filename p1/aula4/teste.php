<?php 
require_once 'Produto.php';

// $produtos = [
//     new Produto(1, 'celular', 1_700.00, 10),
//     new Produto(2, 'tablet', 2_500.00, 20),
//     new Produto(3, 'fone de ouvido', 121.50, 30)
// ];

// function salvarProdutos(array $produtos) {
//     $novo = [];

//     foreach( $produtos as $p ) {
//         array_push($novo, $p->toArray());
//         //$novo[] = $p->toArray();
//     }

//     file_put_contents(
//         'produtos.json',
//         json_encode($novo, JSON_PRETTY_PRINT)
//     );
// }

//salvarProdutos( $produtos );

function carregarProdutos() {
    $array = json_decode(file_get_contents('produtos.json'), true);
    $produtos = [];

    foreach( $array as $a ) {
        $p = Produto::criar($a);
        array_push($produtos, $p);
    }
    return $produtos;
}

$produtos = carregarProdutos();


//EX1: crie uma função calcularInventario ue receba um array de produtos
// e calcule o inventario totla dos produtos (soma dos inventarios)

function calcularInventarioTotal(array $produtos) {
    $total = 0;

    foreach( $produtos as $p ) {
        $total += $p->inventario();
    }
    return $total;
}

echo calcularInventarioTotal( $produtos ) . "\n";

// EX2: Testando o Modelo de Classes Venda e ItemVenda
require_once 'Venda.php';
require_once 'ItemVenda.php';

$venda = new Venda($produtos);
$venda->adicionarItem(1, 2); // 2 celulares
$venda->adicionarItem(2, 1); // 1 tablet
$venda->adicionarItem(3, 4); // 4 fones de ouvido

echo "\n--- Teste Exercicio 2 ---";
echo "\nItens na venda: " . count($venda->getItens());
echo "\nSubtotal da Venda: R$ " . number_format($venda->subTotal(), 2, ',', '.') . "\n";

echo "\n--- Teste Exercicio 3 ---";
$estoqueAntes = $produtos[0]->estoque; // id 1
echo "\nEstoque do celular ANTES de finalizar a venda: " . $estoqueAntes;
$venda->finalizar();
echo "\nEstoque do celular DEPOIS de finalizar a venda: " . $produtos[0]->estoque;

$venda->adicionarItem(1, 5); // não deve adicionar
echo "\nTentou adicionar itens com a venda finalizada. Total de itens continua: " . count($venda->getItens()) . "\n";
<?php
// produtos.php
// Questão 1 - 2024/1

function inventario(array $produtos): array {
    $resultado = [];
    
    foreach ($produtos as $nome => $dados) {
        $quantidade = $dados['quantidade'];
        $preco = $dados['preço'];
        
        $total = $quantidade * $preco;
        $resultado[$nome] = $total;
    }
    
    return $resultado;
}

// Exemplo de entrada
$entrada = [
    "maçã"   => [ "quantidade" => 10, "preço" => 10.00 ],
    "banana" => [ "quantidade" => 5,  "preço" => 6.00 ]
];

// Teste
$saida = inventario($entrada);
print_r($saida);

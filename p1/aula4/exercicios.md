## EX1

Crie uma função calcularInventario ue receba um array de produtos
e calcule o inventario totla dos produtos (soma dos inventarios)

## EX2

Crie um modelo de classes baseado na estrutura abaixo, adicionando os atributos necessários para os métodos:

### Classe `Venda`
- `constructor(produtos: Produto[])`
- `adicionarItem(idProduto: int, quantidade: int)`
- `removerItem(posicao: int)`
- `subTotal(): float`

> **Relação:** `Venda` 1 -------> * `ItemVenda`

### Classe `ItemVenda`
- `produto(): Produto`
- `subTotal(): float`

## EX3

Crie um meotodo finalizar() em Venda que debite do estoque dos produtos
as quantidades vendidas. Após finalizar a venda, ela não deve mais aceitar adicionar ou remover itens
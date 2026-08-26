# Resumo Aula 2 - Estruturas de Repetição, Arquivos JSON e Sistema de Estoque

Esta aula avança no aprendizado de laços de repetição (como `for` e `foreach`) e na estruturação de dados utilizando arrays associativos. O grande diferencial é a construção de um pequeno sistema CRUD (Criar, Ler, Atualizar, Deletar) para simular um estoque de produtos com persistência local utilizando leitura e escrita em um arquivo JSON.

## Arquivos e Funcionalidades:

* **`codigo-prof/for.php`**
  - **O que faz:** Ensina a iterar sobre um array usando a estrutura `for`.
  - **Detalhes:** Demonstra como construir o loop com 3 partes: inicialização (`$i = 0`), condição (`$i < count($a)`), e incremento (`$i++`). Assim, acessa os elementos sequencialmente por seus índices numéricos (ex: `$a[$i]`).

* **`codigo-prof/foreach.php`**
  - **O que faz:** Mostra como iterar sobre arrays com o laço `foreach`, que é mais legível para coleções.
  - **Detalhes:** O professor explica as duas formas principais:
    1. Apenas com o valor: `foreach ($a as $valor) { ... }`.
    2. Com índice e valor (chave/valor): `foreach ($a as $i => $valor) { ... }`.

* **`codigo-prof/explode.php`**
  - **O que faz:** Explica as funções para manipulação de strings baseadas em um delimitador.
  - **Detalhes:** 
    - A função `explode('/', $nascimento)` divide uma string em partes sempre que encontra o caractere `/`, retornando um array (útil para separar datas).
    - A função inversa é a `implode('/', $partes)`, que pega um array e junta as posições com um caractere, formando novamente uma string.

* **`codigo-prof/meses.php`**
  - **O que faz:** Uma aplicação prática mesclando arrays associativos (chaves numéricas para os meses, valores como string por extenso) com a função `explode`.
  - **Detalhes:** Pega uma data via `readline()` (ex: 01/02/1970), quebra com o `explode` e acessa o array `$meses` usando a posição referente ao mês (`$meses[(int) $mes]`) para retornar a data formatada, evitando o uso de `if` ou `switch` desnecessários.

* **`codigo-prof/cliente.php`**
  - **O que faz:** Demonstração da declaração de dados estruturados em um array associativo (onde a chave é uma string, por exemplo, `'nome' => 'Mauro'`).
  - **Detalhes:** Utiliza o `foreach` com formato `$campo => $dado` para varrer e imprimir todos os atributos da entidade `$cliente`.

### Sistema de Estoque

Esta seção engloba os arquivos `estoque*.php`, desenvolvendo um programa modular interativo em terminal (CLI):

* **`codigo-prof/estoque.php`**
  - **O que faz:** É o arquivo principal que amarra todas as funcionalidades. Contém um laço `do { ... } while()` infinito que exibe o menu e controla a execução do CRUD.
  - **Detalhes:** O arquivo utiliza `file_get_contents` e `json_decode` no início para puxar os produtos previamente salvos no arquivo `produtos.json`. Ao usuário decidir sair (`OPCAO_SAIR`), o script usa `json_encode` e salva as alterações em disco via `file_put_contents`.

* **`codigo-prof/estoque-util.php`**
  - **O que faz:** Concentra funções auxiliares que serão usadas nos demais arquivos.
  - **Detalhes:** 
    - `lerProduto()`: lê dados do terminal (`readline`) e efetua *casts* de tipos (como `(int)` ou `(float)`) para formatar um novo array de produto.
    - `indiceProdutoComCodigo()`: busca a posição de um produto no array pelo seu código. Se achar, retorna o índice (int). Se não achar, retorna `INDICE_NAO_ENCONTRADO` (-1).
    - `criarTitulo()`: string utilitária com a função `str_repeat` para desenhar barras nas telas.

* **`codigo-prof/estoque-cadastrar.php` e `estoque-listar.php`**
  - **O que faz:** Implementam o **C** (Create) e o **R** (Read).
  - **Detalhes:** A função `cadastrar(&$produtos)` usa referência `&` para poder alterar o array original na memória usando `array_push($produtos, $produto)`. A função `listar()` simplesmente itera com `foreach` exibindo tudo formatado.

* **`codigo-prof/estoque-alterar.php` e `estoque-remover.php`**
  - **O que faz:** Implementam o **U** (Update) e o **D** (Delete).
  - **Detalhes:** Ambas usam a função utilitária `indiceProdutoComCodigo()` para encontrar em qual gaveta (índice) do array está o item. 
    - Se for alterar, substitui a posição `[indice]` com novos dados lidos. 
    - Se for remover, o professor utiliza a função nativa `unset($produtos[$indiceEncontrado])` para destruir o item na memória.

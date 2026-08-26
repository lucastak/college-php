# Resumo Aula 1 - Fundamentos do PHP

Esta aula foca nos fundamentos da linguagem, estruturas de controle básicas e manipulação inicial de arrays e strings. O professor demonstrou como criar funções, utilizar condicionais (`if/else`), passar parâmetros por referência, e construir laços (`for`) aplicados a arrays.

## Arquivos e Funcionalidades:

* **`codigo-prof/hello.php`**
  - **O que faz:** Um script simples que recebe o nome do usuário via terminal usando a função `readline()` e exibe uma saudação concatenando o texto usando a função `echo`.
  - **Detalhes:** O uso de vírgula no `echo` (ex: `echo 'Hello ', $nome;`) permite a impressão de múltiplos parâmetros sem precisar concatenar com o operador `.`.

* **`codigo-prof/impar.php`**
  - **O que faz:** Define uma função `impar($numero)` que recebe um número por parâmetro e retorna um booleano (`true` ou `false`).
  - **Detalhes:** Ensina o uso do operador módulo (`%`) para obter o resto da divisão. O professor também mostra a sintaxe do operador ternário `condição ? verdadeiro : falso` para exibir a saída (ex: `echo 'Ímpar: ', impar(4) ? 'Sim' : 'Não';`).

* **`codigo-prof/incrementar.php`**
  - **O que faz:** Mostra como passar parâmetros para uma função **por referência**.
  - **Detalhes:** A função `incrementar(&$x, $valor = 1)` usa o `&` antes da variável `$x` para indicar que a variável original passada será modificada (passagem por referência). Também demonstra o uso de **valores padrão (default)** para parâmetros de função, já que `$valor` recebe 1 caso não seja passado.

* **`codigo-prof/maior-de-dois.php` e `maior-de-tres.php`**
  - **O que faz:** `maior-de-dois.php` possui a função `maiorDeDois($numero1, $numero2)` que usa lógica condicional estruturada (`if/else`) para retornar qual número é maior.
  - **Detalhes:** No `maior-de-tres.php`, ensina-se a reaproveitar código incluindo o primeiro arquivo com a instrução `require_once 'maior-de-dois.php'`. A função `maiorDeTres($n1, $n2, $n3)` reutiliza `maiorDeDois` duas vezes consecutivas para encontrar o maior entre três números, mostrando uma composição simples de funções.

* **`codigo-prof/maior-array.php`**
  - **O que faz:** Percorre um array numérico para encontrar e retornar seu maior valor usando uma iteração.
  - **Detalhes:** Demonstra como saber o tamanho do array com a função `count($numeros)`. O laço `for ($i = 1; $i < $contagem; $i++)` é utilizado para percorrer o array a partir do índice 1, aplicando a função `maiorDeDois()` iterativamente.

* **`codigo-prof/media-array.php`**
  - **O que faz:** Calcula a média dos valores contidos em um array numérico.
  - **Detalhes:** Utiliza novamente a iteração via `for ($i = 0; $i < $contagem; $i++)` inicializando de 0 para somar os valores na variável `$soma`. Há também validação contra divisão por zero, retornando 0 caso o array seja vazio (`if ($contagem == 0)`).

* **`codigo-prof/string.php`**
  - **O que faz:** Demonstração dos diferentes tipos de declaração e interpolação de strings no PHP.
  - **Detalhes:** 
    - Apóstrofo (aspas simples - `''`): Não interpreta variáveis, imprimindo tudo literalmente (incluindo `\n`).
    - Aspas duplas (`""`): Interpreta variáveis dentro da string (ex: `"$x"`, `"{$x}"`) e escapa caracteres especiais.
    - Heredoc (`<<<`): Uma sintaxe poderosa, muito usada pelo professor para montar blocos de código grandes (como `SQL` e `HTML`) preservando espaços e quebras de linha de forma legível.

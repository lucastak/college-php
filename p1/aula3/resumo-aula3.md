# Resumo Aula 3 - Manipulação Avançada de Strings e Arquivos CSV

Esta aula abordou detalhadamente o tratamento de strings, as particularidades de caracteres especiais no PHP (UTF-8), funções básicas de hash (criptografia unidirecional) e a manipulação manual de arquivos do tipo CSV.

## Arquivos e Funcionalidades ensinadas pelo professor:

* **`codigo-prof/comprimento.php`**
  - **O que faz:** Explica a diferença na contagem do tamanho de strings com caracteres acentuados ou especiais (ex: Emojis).
  - **Detalhes:** O professor ensinou que a função padrão `strlen('Pelé')` conta os *bytes*, o que dá erro ao contar letras acentuadas (retorna 5 ao invés de 4). A solução apresentada é a utilização da função `mb_strlen('Pelé')` (Multibyte String Length), que conta corretamente as letras da palavra, lidando perfeitamente com acentos e emojis (👍).

* **`codigo-prof/conta-palavras.php`**
  - **O que faz:** Conta quantas palavras existem numa frase digitada pelo usuário.
  - **Detalhes:** Utiliza uma união de duas técnicas: O `explode(' ', $frase)` separa a string em um array usando o espaço em branco como delimitador. Depois, apenas se usa a função `count($partes)` vista em aulas passadas para saber o tamanho do array resultante, sabendo-se assim o número de palavras.

* **`codigo-prof/maior-palavra.php`**
  - **O que faz:** Varrer uma frase para detectar qual palavra possui mais letras.
  - **Detalhes:** Ensina a inicializar flags para salvar o maior estado encontrado (`$maiorTamanho = 0` e `$indiceMaiorPalavra = -1`). O laço `foreach ($palavras as $indice => $p)` percorre palavra por palavra do `explode`, enquanto o `if (mb_strlen($p) > $maiorTamanho)` atualiza os dados caso ache uma palavra maior, lembrando de usar a função `mb_strlen` (multibyte) vista anteriormente.

* **`codigo-prof/palindromo.php`**
  - **O que faz:** Identifica se uma palavra ou frase é igual quando lida de frente pra trás e vice-versa (ex: ovo, arara, radar).
  - **Detalhes:** O professor ensinou a usar a função `str_replace(' ', '', $frase)` para remover todos os espaços da frase pesquisando-os e substituindo por 'nada'. Em seguida ele ensina como varrer um array "de trás para frente": cria-se um laço `for` invertido, iniciando do último índice (tamanho total - 1) (`$i = $tamanho - 1`), parando em 0 (`$i >= 0`) e decrementando (`$i--`). Dentro do laço usa-se o operador de concatenação `.=` para montar aos poucos a variável com a frase invertida. 

* **`codigo-prof/hash.php`**
  - **O que faz:** Apresenta algoritmos de hash criptográfico usados para mascarar dados sensíveis como senhas (onde não é possível reverter o texto gerado de volta para original).
  - **Detalhes:** Demonstrou na prática quatro algoritmos diferentes do mais antigo ao moderno: `crc32($nome)`, `md5($nome)`, `sha1($nome)` e, finalmente, a aplicação segura moderna com a função genérica de hash, passando o algoritmo por parâmetro: `hash('sha256', $nome)`.

* **`codigo-prof/produtos.php` (e `produtos.csv`)**
  - **O que faz:** Lê um arquivo `.csv` que simula uma planilha com dados delimitados por ponto-e-vírgula e o disseca na memória preparando para cálculos numéricos (calcular o inventário).
  - **Detalhes:** 
    1. O código usa `file_get_contents` para puxar o arquivo.
    2. Divide a tabela em um array de linhas através do caractere de quebra de linha: `explode("\n", $conteudo)`.
    3. Ensina a usar `array_shift($linhas)` para excluir a 1º linha do array (onde ficam os títulos) e `array_pop($linhas)` para excluir a última linha caso ela fique em branco, deixando só os dados limpos.
    4. Num laço que percorre as linhas, usa o `explode(';', $l)` para separar as colunas da planilha.
    5. Por fim, ensina duas funções cruciais de formatação/limpeza de dados para cálculo financeiro: a função `trim($partes[3], ' R$')` limpa os caracteres 'R$' da borda da string de preço, e o `str_replace(',', '.', $preco)` converte a vírgula do padrão brasileiro para o ponto matemático do padrão inglês, permitindo ao PHP finalmente fazer multiplicação e soma com dinheiro.

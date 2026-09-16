# Guia Definitivo de Revisão para a Prova P1 - PHP (Aulas 1 a 5)

**Disciplina:** Programação para a Web | **Instituição:** CEFET/RJ (Nova Friburgo)  
**Professor:** Thiago Delgado Pinto | **Foco:** PHP 7, Orientação a Objetos, PDO, JSON, CSV e Boas Práticas

---

## Sumário Rápido

1. [Checklist Express & Pegadinhas de Prova](#1-checklist-express--pegadinhas-clássicas-de-prova)
2. [Mapeamento Aula por Aula (Aulas 1 a 5)](#2-mapeamento-aula-por-aula)
   - [Aula 1: Fundamentos, Funções e Lógica](#aula-1-fundamentos-funções-e-lógica)
   - [Aula 2: Arrays Associativos, Datas e CRUD em JSON](#aula-2-arrays-associativos-datas-e-crud-em-json)
   - [Aula 3: Strings Multibyte, Limpeza de Dados, CSV e Hash](#aula-3-strings-multibyte-limpeza-de-dados-csv-e-hash)
   - [Aula 4: POO, Encapsulamento, Composição e Interfaces](#aula-4-poo-encapsulamento-composição-e-interfaces)
   - [Aula 5: Namespaces, Exceções Customizadas, Traits e Repositórios](#aula-5-namespaces-exceções-customizadas-traits-e-repositórios)
3. [Dicionário de Funções Nativas da Folha de Consulta](#3-dicionário-de-funções-nativas-da-folha-de-consulta)
4. [Esqueletos de Código "Copia e Cola Mental" para a Prova](#4-esqueletos-de-código-para-a-prova)
   - [Padrão A: Leitura e Processamento de CSV com Cálculo](#padrão-a-leitura-e-processamento-de-csv-com-cálculo)
   - [Padrão B: Repositório com PDO e Transações (BDR)](#padrão-b-repositório-com-pdo-e-transações-bdr)
   - [Padrão C: Script CLI de Cadastro com Validações Estritas](#padrão-c-script-cli-de-cadastro-com-validações-estritas)
   - [Padrão D: Repositório em JSON com Entidade e Trait](#padrão-d-repositório-em-json-com-entidade-e-trait)
   - [Padrão E: Validador de Formatos com Regex (Placa / Telefone)](#padrão-e-validador-de-formatos-com-regex-placa--telefone)

---

## 1. Checklist Express & Pegadinhas Clássicas de Prova

Antes de escrever qualquer linha de código na prova, revise estas regras fundamentais cobradas pelo professor:

| # | Pegadinha / Regra | O que NÃO fazer | O que FAZER (Correto) |
|---|---|---|---|
| **1** | **Contagem de texto UTF-8** | `strlen($str)` (conta bytes e erra acentos) | `mb_strlen($str)` ou `mb_strlen($str, 'UTF-8')` |
| **2** | **Fatiamento de texto** | `substr($str, 0, 5)` | `mb_substr($str, 0, 5)` |
| **3** | **Ordem do `checkdate`** | `checkdate($dia, $mes, $ano)` | `checkdate($mes, $dia, $ano)` ⚠️ **Mês vem antes!** |
| **4** | **Decodificação JSON** | `json_decode($json)` (retorna `stdClass`) | `json_decode($json, true)` (retorna array associativo) |
| **5** | **Codificação JSON** | `json_encode($arr)` | `json_encode($arr, JSON_PRETTY_PRINT \| JSON_UNESCAPED_UNICODE)` |
| **6** | **Global em Namespace** | `class Repo { public function __construct(PDO $pdo)... }` dentro de `namespace X;` | Usar `\PDO`, `\PDOException`, `\RuntimeException`, `\DomainException` ou colocar `use PDO;` no topo |
| **7** | **Passagem por Referência** | `adicionar(Carro $carro)` quando o ID deve ser atribuído | `adicionar(Carro &$carro)` com o `&` comercial para refletir fora |
| **8** | **Rollback em Transação** | Chamar `$pdo->rollBack()` sem checar | `if ($this->pdo->inTransaction()) { $this->pdo->rollBack(); }` |
| **9** | **Conversão Moeda Real** | `(float) "R$ 1.500,50"` (vira `0`) | Limpar `R$`, tirar ponto de milhar `.` e trocar vírgula `,` por ponto `.` |
| **10** | **Cabeçalho de CSV** | Tratar a linha 0 como dados | Usar `array_shift($linhas)` para remover o cabeçalho |
| **11** | **Proteção SQL Injection** | Concatenar `$sql = "SELECT * WHERE id = " . $id` | Usar prepared statements: `prepare(...)` + `execute([$id])` |
| **12** | **Instanciação de PDO** | Dar `new PDO(...)` dentro da classe de Repositório | Receber `$pdo` pronto no construtor (Injeção de Dependência) |

---

## 2. Mapeamento Aula por Aula

---

### Aula 1: Fundamentos, Funções e Lógica

#### Conceitos Centrais
- **PHP 7**: Tipagem em parâmetros e retornos (`int`, `float`, `string`, `bool`, `array`, `void`, e tipos anuláveis `?float`).
- **Passagem de Parâmetros**:
  - **Por valor (padrão):** A função recebe uma cópia do dado. Alterações não afetam a variável de fora.
  - **Por referência (`&`):** A função opera no mesmo endereço de memória. O que for alterado reflete na variável externa.
- **Valores Default:** Parâmetros opcionais devem sempre vir após os parâmetros obrigatórios.
- **Strings e Interpolação:**
  - Aspas simples (`'...'`): Literais, não interpolam variáveis (`$x` sai como `$x`).
  - Aspas duplas (`"..."`): Interpolam variáveis (`"Total: $total"` ou `"Item: {$item['nome']}"`).
  - **Heredoc (`<<<`)**: Permite blocos multilinha interpolados sem necessidade de concatenar.
- **Estruturas de Decisão e Seleção:**
  - Operador ternário: `$status = ($saldo >= 0) ? "Regular" : "Negativo";`
  - Operador de coalescência nula (`??`): `$nome = $item['nome'] ?? 'Anônimo';`

#### Algoritmos Manuais em Arrays (Sem funções prontas)
Nas provas do professor, é muito comum pedir lógica de vetores sem usar `max()`, `min()`, `array_sum()`, `array_filter()`:
```php
// Encontrar o maior valor
function encontrarMaior(array $numeros): ?float {
    if (empty($numeros)) return null;
    $maior = $numeros[0];
    for ($i = 1; $i < count($numeros); $i++) {
        if ($numeros[$i] > $maior) {
            $maior = $numeros[$i];
        }
    }
    return $maior;
}

// Média com prevenção de divisão por zero
function calcularMedia(array $numeros): float {
    $qtd = count($numeros);
    if ($qtd === 0) return 0.0;
    $soma = 0.0;
    for ($i = 0; $i < $qtd; $i++) {
        $soma += $numeros[$i];
    }
    return $soma / $qtd;
}
```

---

### Aula 2: Arrays Associativos, Datas e CRUD em JSON

#### Conceitos Centrais
- **Array Associativo:** Coleção chave $\to$ valor onde as chaves são strings semânticas (ex: `['id' => 1, 'nome' => 'Mouse']`).
- **Iteração com `foreach`:**
  - Apenas valor: `foreach ($colecao as $item)`
  - Chave e valor: `foreach ($colecao as $chave => $item)`
- **`explode` e `implode`:**
  - `explode(string $delimitador, string $texto)`: Quebra string em array.
  - `implode(string $cola, array $partes)`: Junta array em string.
- **Cálculo com Horários (`HH:MM`):**
  - Converter tudo para minutos: `($horas * 60) + $minutos`.
  - Se a chegada for menor que a saída (passou da meia-noite), somar 24 horas (1440 minutos) na chegada.
- **CRUD com Persistência em JSON:**
  1. **Ler:** `file_get_contents($caminho)` + `json_decode($conteudo, true)`.
  2. **Buscar:** Percorrer e comparar `$item['id'] === $id`.
  3. **Adicionar:** `$lista[] = $novoElemento`.
  4. **Remover:** `unset($lista[$indice])` seguido de `$lista = array_values($lista)` (para reindexar de 0 a N).
  5. **Salvar:** `json_encode($lista, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)` + `file_put_contents($caminho, $json)`.

---

### Aula 3: Strings Multibyte, Limpeza de Dados, CSV e Hash

#### Conceitos Centrais
- **Multibyte String (`mb_*`):**
  - Caracteres acentuados (á, é, õ, ç) e emojis ocupam **2 a 4 bytes** em UTF-8.
  - `strlen("Café")` retorna `5` (erro de contagem).
  - `mb_strlen("Café", 'UTF-8')` retorna `4` (correto).
- **Limpeza de Dados:**
  - `trim($str)`: Remove espaços nas extremidades (ou caracteres especificados).
  - `str_replace($procura, $substituto, $texto)`: Substitui todas as ocorrências.
- **Higienização de Valores Monetários em Formato Brasileiro:**
  ```php
  // " R$ 1.250,75 " -> 1250.75 (float)
  $valorLimpo = trim($str);
  $valorLimpo = str_replace('R$', '', $valorLimpo);
  $valorLimpo = trim($valorLimpo);
  $valorLimpo = str_replace('.', '', $valorLimpo); // Tira separador de milhar
  $valorLimpo = str_replace(',', '.', $valorLimpo); // Vírgula vira ponto decimal
  $valorFloat = (float) $valorLimpo;
  ```
- **Parsing Manual de Arquivos CSV:**
  ```php
  $conteudo = file_get_contents('dados.csv');
  $linhas = explode("\n", $conteudo);
  $cabecalho = array_shift($linhas); // Remove primeira linha

  foreach ($linhas as $linha) {
      $linha = trim($linha);
      if ($linha === '') continue; // Ignora linhas em branco
      $colunas = explode(';', $linha);
      // $colunas[0], $colunas[1], etc.
  }
  ```
- **Funções de Hash (Criptografia Unidirecional):**
  - `hash('sha256', $dados)`: Gera hash de 64 caracteres hexadecimais.
  - Usado para integridade de requisições: `$hash = hash('sha256', "$metodo|$url|$payload|$secret");`

---

### Aula 4: POO, Encapsulamento, Composição e Interfaces

#### Conceitos Centrais
- **Visibilidade:**
  - `private`: Apenas a própria classe acessa diretamente. Atributos sempre devem ser privados para garantir encapsulamento.
  - `protected`: A classe e suas subclasses acessam.
  - `public`: Qualquer código externo acessa.
- **Composição de Objetos:**
  - Um objeto contém referências para outros objetos como atributos (ex: `Contrato` possui array de `ItemLocacao`, que por sua vez referencia um `Veiculo`).
- **Controle de Estado Interno:**
  - Flags de fechamento: Se `$fechado === true`, os métodos modificadores (`adicionarItem`, `removerItem`, `concederDesconto`) devem recusar alterações (retornando `false` ou levantando exceção).
- **Remoção de Itens em Array de Objetos:**
  - `array_splice($this->itens, $posicao, 1)`: Remove o elemento no índice informado e reordena automaticamente as posições subsequentes.
- **Interfaces e Polimorfismo:**
  - Uma `interface` define o contrato (métodos que devem existir, sem implementação).
  - Permite desacoplar a lógica de negócio da saída ou persistência (ex: interface `ImpressoraVenda`, implementada por `ImpressoraVendaConsole`).
  - Injeção de dependência via método: `public function finalizar(?ImpressoraVenda $impressora = null)`

---

### Aula 5: Namespaces, Exceções Customizadas, Traits e Repositórios

#### 1. Namespaces
- Declaração na primeira linha do arquivo (apenas comentários ou `<?php` antes):
  ```php
  namespace cefet\dominio;
  ```
- Importação de classes de outros namespaces:
  ```php
  use cefet\excecoes\TarefaException;
  ```
- **Atenção Máxima:** Quando você está dentro de um namespace, classes nativas do PHP precisam de barra invertida `\` na frente:
  ```php
  // DENTRO de um namespace:
  throw new \DomainException("Mensagem");
  $pdo = new \PDO(...);
  $agora = new \DateTime();
  ```

#### 2. Hierarquia de Exceções
- `\DomainException` (subclasse de `\LogicException`): Usada para regras de negócio e validação de domínio inválido (ex: descrição curta demais, valor negativo, data inexistente).
- `\RuntimeException`: Usada para erros em tempo de execução, falha de arquivos, IO ou banco de dados (ex: falha ao ler JSON, falha de query SQL, ID duplicado em repositório).

#### 3. Traits
- Mecanismo de reutilização horizontal de código (evita herança múltipla):
  ```php
  namespace cefet\dominio;

  trait SerializavelJson {
      public function toJson(): string {
          return json_encode(get_object_vars($this), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
      }
  }

  class Tarefa {
      use SerializavelJson; // Agora Tarefa possui o método toJson()
      private $descricao;
      // ...
  }
  ```

#### 4. Padrão Repositório
- Abstração responsável por gerenciar a coleção de entidades no meio de persistência (Banco de Dados Relacional, Arquivo JSON, Memória).
- Separa totalmente a regra de negócio da forma como os dados são salvos.
- Regra de ouro da prova: **A interface do repositório define os métodos, e a implementação só lança a exceção especializada (ex: `RepositorioException`).**

---

## 3. Dicionário de Funções Nativas da Folha de Consulta

Tabela com as funções da folha de cola oficial das provas do CEFET/RJ, detalhando funcionamento e exemplo rápido:

| Função | Parâmetros e Retorno | O que faz / Exemplo de Uso |
|---|---|---|
| `mb_strlen($str)` | `string -> int` | Retorna o número de caracteres em UTF-8. `mb_strlen('Açúcar') === 6`. |
| `mb_substr($str, $ini, $tam)` | `(str, int, ?int) -> str` | Extrai fatia de caracteres. `mb_substr('Programação', 0, 8) === 'Programa'`. |
| `mb_strpos($str, $busca)` | `(str, str, int) -> int\|false` | Posição da primeira ocorrência da busca. Retorna `false` se não achar. |
| `trim($str, $mask)` | `(str, ?str) -> str` | Remove espaços (ou máscara) do início e fim. `trim("  olá  ") === "olá"`. |
| `str_replace($de, $para, $em)` | `(mixed, mixed, mixed) -> mixed` | Substitui substrings. `str_replace(',', '.', '10,5') === '10.5'`. |
| `explode($delim, $str)` | `(string, string) -> array` | Quebra string em array por delimitador. `explode(';', 'A;B;C') === ['A','B','C']`. |
| `implode($glue, $arr)` | `(string, array) -> string` | Junta elementos do array em string. `implode('-', [2024, 12, 31])`. |
| `checkdate($m, $d, $y)` | `(int, int, int) -> bool` | Valida se a data é real no calendário. **Mês é o 1º parâmetro!** `checkdate(2, 29, 2024) === true`. |
| `count($arr)` | `array -> int` | Retorna o total de elementos no array. |
| `isset($var)` | `mixed -> bool` | Retorna `true` se a variável existe e **não é null**. |
| `empty($var)` | `mixed -> bool` | Retorna `true` se a variável for vazia, `0`, `0.0`, `""`, `null`, `false` ou `[]`. |
| `unset($var)` | `mixed -> void` | Destrói a variável ou remove chave de array: `unset($arr['chave'])`. |
| `array_push(&$arr, ...$vals)` | `(array, ...mixed) -> int` | Insere no final do array. Equivalente a `$arr[] = $val`. |
| `array_shift(&$arr)` | `array -> mixed` | Remove e retorna o **primeiro** elemento do array (ótimo para cabeçalho CSV). |
| `array_pop(&$arr)` | `array -> mixed` | Remove e retorna o **último** elemento do array. |
| `array_splice(&$arr, $offset, $len)` | `(array, int, int) -> array` | Remove elementos a partir de `$offset` e reindexa as chaves numéricas. |
| `array_values($arr)` | `array -> array` | Retorna todos os valores com chaves reindexadas numericamente de 0 a N. |
| `in_array($val, $arr, $strict)` | `(mixed, array, bool) -> bool` | Checa se `$val` existe nos valores do array. Use `$strict = true` para comparar tipos. |
| `array_search($val, $arr)` | `(mixed, array) -> key\|false` | Busca o valor no array e retorna a chave/índice correspondente (ou `false`). |
| `is_numeric($val)` | `mixed -> bool` | Retorna `true` se for número ou string numérica (`"123"`, `"45.67"`). |
| `is_array($val)` | `mixed -> bool` | Retorna `true` se for um array. |
| `readline($prompt)` | `?string -> string` | Lê linha digitada pelo usuário no terminal CLI. |
| `file_get_contents($path)` | `string -> string\|false` | Lê todo o conteúdo de um arquivo em disco para uma string. |
| `file_put_contents($path, $dados)`| `(string, mixed) -> int\|false` | Escreve string em um arquivo. Retorna número de bytes gravados ou `false`. |
| `json_encode($dados, $flags)` | `(mixed, int) -> string\|false` | Converte PHP para JSON. Sempre use `JSON_PRETTY_PRINT \| JSON_UNESCAPED_UNICODE`. |
| `json_decode($json, $assoc)` | `(string, bool) -> mixed` | Converte JSON para PHP. Sempre passe `true` no 2º arg para virar array. |
| `preg_match($regex, $str, &$match)`| `(string, string) -> int` | Testa expressão regular. Retorna `1` se casou, `0` se não. |
| `hash($algo, $dados)` | `(string, string) -> string` | Gera hash criptográfico. Ex: `hash('sha256', 'senha123')`. |

---

## 4. Esqueletos de Código para a Prova

Memorize estes 5 esqueletos. Eles cobrem praticamente todas as questões históricas das provas (P1 2021, 2023, 2024 e Simulado).

---

### Padrão A: Leitura e Processamento de CSV com Cálculo

```php
function processarPlanilha(string $caminhoArquivo): array {
    if (!file_exists($caminhoArquivo)) {
        throw new \RuntimeException("Arquivo '{$caminhoArquivo}' não encontrado.");
    }

    $conteudo = file_get_contents($caminhoArquivo);
    $linhas = explode("\n", $conteudo);

    // 1. Remove linha do cabeçalho
    $cabecalho = array_shift($linhas);

    $totalRegistros = 0;
    $somaFinanceira = 0.0;
    $maiorValor = 0.0;
    $itemDestaque = '';

    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if ($linha === '') {
            continue; // Pula linha vazia
        }

        $colunas = explode(';', $linha);
        // Exemplo de colunas: 0: Nome, 1: Categoria, 2: Quantidade, 3: Preço
        $nome = trim($colunas[0]);
        $qtd = (int) trim($colunas[2]);

        // Higienização monetária brasileira: " R$ 1.500,50 " -> 1500.50
        $precoStr = trim($colunas[3]);
        $precoStr = str_replace('R$', '', $precoStr);
        $precoStr = trim($precoStr);
        $precoStr = str_replace('.', '', $precoStr); // Tira separador de milhar
        $precoStr = str_replace(',', '.', $precoStr); // Vírgula vira ponto
        $preco = (float) $precoStr;

        $subtotal = $qtd * $preco;
        $somaFinanceira += $subtotal;
        $totalRegistros++;

        if ($subtotal > $maiorValor) {
            $maiorValor = $subtotal;
            $itemDestaque = $nome;
        }
    }

    return [
        'total_registros' => $totalRegistros,
        'soma_total'      => $somaFinanceira,
        'item_destaque'   => $itemDestaque,
        'maior_valor'     => $maiorValor
    ];
}
```

---

### Padrão B: Repositório com PDO e Transações (BDR)

```php
namespace persistencia;

require_once 'Carro.php';
require_once 'RepositorioCarro.php';
require_once 'RepositorioException.php';

use excecoes\RepositorioException;

class RepositorioCarroEmBDR implements RepositorioCarro {
    private $pdo;

    // Injeção de dependência obrigatória
    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    // 1. Adicionar com passagem por referência para atribuir o ID gerado
    public function adicionar(Carro &$carro) {
        try {
            $sql = "INSERT INTO carro (nome, fabricante, preco) VALUES (?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                $carro->nome,
                $carro->fabricante,
                $carro->preco
            ]);
            $carro->id = (int) $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            throw new RepositorioException("Erro ao adicionar: " . $e->getMessage(), 0, $e);
        }
    }

    // 2. Atualizar com transação segura
    public function atualizarPrecosEmPercentual($percentual) {
        try {
            $fator = 1 + ($percentual / 100.0);

            $this->pdo->beginTransaction();

            $sql = "UPDATE carro SET preco = preco * ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$fator]);

            $this->pdo->commit();
        } catch (\PDOException $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw new RepositorioException("Erro na transação de preços: " . $e->getMessage(), 0, $e);
        }
    }

    // 3. Remover por ID retornando booleano
    public function removerPeloId($id): bool {
        try {
            $sql = "DELETE FROM carro WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([(int) $id]);
            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            throw new RepositorioException("Erro ao remover: " . $e->getMessage(), 0, $e);
        }
    }

    // 4. Buscar todos retornando array de objetos
    public function todos(): array {
        try {
            $stmt = $this->pdo->query("SELECT id, nome, fabricante, preco FROM carro");
            $carros = [];
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $carros[] = new Carro(
                    (int) $row['id'],
                    $row['nome'],
                    $row['fabricante'],
                    (float) $row['preco']
                );
            }
            return $carros;
        } catch (\PDOException $e) {
            throw new RepositorioException("Erro ao buscar todos: " . $e->getMessage(), 0, $e);
        }
    }
}
```

---

### Padrão C: Script CLI de Cadastro com Validações Estritas

```php
<?php
// cadastro.php
require_once 'Carro.php';
require_once 'RepositorioCarroEmBDR.php';
require_once 'RepositorioException.php';

use persistencia\RepositorioCarroEmBDR;
use excecoes\RepositorioException;

// 1. Leitura e validação via readline e mb_*
$nome = trim(readline("Informe o nome do carro: "));
if (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
    die("Erro: O nome deve ter entre 2 e 100 caracteres." . PHP_EOL);
}

$fabricante = trim(readline("Informe o fabricante: "));
if (mb_strlen($fabricante) > 60) {
    die("Erro: O fabricante deve ter no máximo 60 caracteres." . PHP_EOL);
}
if (preg_match('/[0-9]/', $fabricante)) {
    die("Erro: O fabricante não pode conter números." . PHP_EOL);
}

$precoStr = trim(readline("Informe o preço: "));
if (!is_numeric($precoStr)) {
    die("Erro: O preço deve ser um número válido." . PHP_EOL);
}
$preco = (float) $precoStr;
if ($preco < 5000.0) {
    die("Erro: O preço deve ser igual ou superior a 5000." . PHP_EOL);
}

// 2. Conexão PDO com tratamento de erros
try {
    $dsn = 'mysql:host=192.168.0.10;dbname=p1;charset=utf8';
    $pdo = new PDO($dsn, 'gerente', 'g3X$t0R');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $repositorio = new RepositorioCarroEmBDR($pdo);

    $novoCarro = new Carro(0, $nome, $fabricante, $preco);
    $repositorio->adicionar($novoCarro);

    echo "Sucesso! Carro cadastrado com o ID: {$novoCarro->id}" . PHP_EOL;

} catch (PDOException $e) {
    die("Erro de banco de dados: " . $e->getMessage() . PHP_EOL);
} catch (RepositorioException $e) {
    die("Erro no repositório: " . $e->getMessage() . PHP_EOL);
}
```

---

### Padrão D: Repositório em JSON com Entidade e Trait

#### 1. Trait JSON
```php
namespace app\dominio;

trait ConversivelParaJson {
    public function toJson(): string {
        return json_encode(get_object_vars($this), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
```

#### 2. Entidade com Validação Interna
```php
namespace app\dominio;

require_once 'ConversivelParaJson.php';
require_once 'AgendamentoException.php';

use app\excecoes\AgendamentoException;

class Agendamento {
    use ConversivelParaJson;

    private $id;
    private $paciente;
    private $dataConsulta;
    private $valor;

    public function __construct(int $id, string $paciente, string $dataConsulta, float $valor) {
        $this->id = $id;
        $this->paciente = $paciente;
        $this->dataConsulta = $dataConsulta;
        $this->valor = $valor;
        $this->validar();
    }

    private function validar(): void {
        $tam = mb_strlen(trim($this->paciente));
        if ($tam < 3 || $tam > 60) {
            throw new AgendamentoException("Paciente deve ter entre 3 e 60 caracteres.");
        }

        // Validação estrita de data DD/MM/AAAA
        $partes = explode('/', $this->dataConsulta);
        if (count($partes) !== 3 || !checkdate((int)$partes[1], (int)$partes[0], (int)$partes[2])) {
            throw new AgendamentoException("Data da consulta inválida ou fora do padrão DD/MM/AAAA.");
        }

        if ($this->valor <= 0.0) {
            throw new AgendamentoException("Valor deve ser estritamente positivo.");
        }
    }

    public function getId(): int { return $this->id; }
    public function getPaciente(): string { return $this->paciente; }
    public function getDataConsulta(): string { return $this->dataConsulta; }
    public function getValor(): float { return $this->valor; }
}
```

#### 3. Repositório JSON Completo
```php
namespace app\persistencia;

require_once 'Agendamento.php';
require_once 'PersistenciaException.php';

use app\dominio\Agendamento;
use app\excecoes\PersistenciaException;

class RepositorioAgendamentosJson {
    private $arquivo;

    public function __construct(string $arquivo = 'agendamentos.json') {
        $this->arquivo = $arquivo;
    }

    public function obterTodos(): array {
        if (!file_exists($this->arquivo)) {
            return [];
        }

        $conteudo = @file_get_contents($this->arquivo);
        if ($conteudo === false) {
            throw new PersistenciaException("Falha ao ler o arquivo '{$this->arquivo}'.");
        }

        $conteudo = trim($conteudo);
        if ($conteudo === '') return [];

        $dados = json_decode($conteudo, true);
        if ($dados === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new PersistenciaException("JSON corrompido: " . json_last_error_msg());
        }

        $objetos = [];
        foreach ($dados as $item) {
            $objetos[] = new Agendamento(
                (int) $item['id'],
                $item['paciente'],
                $item['dataConsulta'],
                (float) $item['valor']
            );
        }
        return $objetos;
    }

    public function adicionar(Agendamento $novo): void {
        $todos = $this->obterTodos();

        // Checagem de ID duplicado
        foreach ($todos as $ag) {
            if ($ag->getId() === $novo->getId()) {
                throw new PersistenciaException("Já existe agendamento com o ID {$novo->getId()}.");
            }
        }

        $todos[] = $novo;
        $this->salvar($todos);
    }

    private function salvar(array $lista): void {
        $arraySerializado = [];
        foreach ($lista as $item) {
            $arraySerializado[] = [
                'id'           => $item->getId(),
                'paciente'     => $item->getPaciente(),
                'dataConsulta' => $item->getDataConsulta(),
                'valor'        => $item->getValor()
            ];
        }

        $json = json_encode($arraySerializado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new PersistenciaException("Erro na codificação JSON.");
        }

        $res = @file_put_contents($this->arquivo, $json);
        if ($res === false) {
            throw new PersistenciaException("Falha ao escrever no arquivo '{$this->arquivo}'.");
        }
    }
}
```

---

### Padrão E: Validador de Formatos com Regex (Placa / Telefone)

```php
// Formatar Telefone (10 dígitos: fixo / 11 dígitos: celular)
function formatarTelefone(string $tel): string {
    // Remove qualquer caractere não numérico se vier sujo
    $apenasNumeros = preg_replace('/[^0-9]/', '', $tel);
    $tam = mb_strlen($apenasNumeros);

    if ($tam === 10) {
        // (22) 2527-1727
        $ddd = mb_substr($apenasNumeros, 0, 2);
        $parte1 = mb_substr($apenasNumeros, 2, 4);
        $parte2 = mb_substr($apenasNumeros, 6, 4);
        return "({$ddd}) {$parte1}-{$parte2}";
    }

    if ($tam === 11) {
        // (22) 98877-6655
        $ddd = mb_substr($apenasNumeros, 0, 2);
        $parte1 = mb_substr($apenasNumeros, 2, 5);
        $parte2 = mb_substr($apenasNumeros, 7, 4);
        return "({$ddd}) {$parte1}-{$parte2}";
    }

    return "";
}

// Padronizar Placa Veicular (Tradicional vs Mercosul)
function padronizarPlaca(string $placa): string {
    $limpa = mb_strtoupper(trim($placa));

    // Tradicional: 3 letras e 4 números (ex: ABC1234 -> ABC-1234)
    if (preg_match('/^([A-Z]{3})([0-9]{4})$/', $limpa, $m)) {
        return "{$m[1]}-{$m[2]}";
    }

    // Mercosul: 3 letras, 1 número, 1 letra, 2 números (ex: ABC1D23 -> ABC1D23)
    if (preg_match('/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/', $limpa)) {
        return $limpa;
    }

    return "";
}
```

---

## 5. Dicas de Ouro para a Hora da Prova

1. **Leia a prova com atenção e anote os tipos:** O professor sempre especifica tipos de argumentos e tipos de retorno. Preste atenção em `: array`, `: void`, `: bool`, `: ?float`.
2. **Não use bibliotecas externas:** Nunca tente usar `composer`, `autoload` ou classes de terceiros.
3. **Não invente métodos mágicos desnecessários:** Se o enunciado não pediu, faça métodos explícitos (`getId()`, `setValor()`).
4. **Respeite a injeção de dependência:** Se a questão diz *"a classe não deve instanciar PDO, direta ou indiretamente"*, passe `$pdo` no `__construct(\PDO $pdo)`.
5. **A caneta azul ou preta:** O cabeçalho diz expressamente: questões entregues a lápis não têm direito a revisão. Escreva com calma e rascunhe a lógica antes se a prova for física.

Boa prova! Você tem todo o conteúdo mapeado e alinhado ao estilo exato do professor.

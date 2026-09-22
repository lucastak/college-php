# Programação para a Web - Simulado Preparatório P1

**Instituição:** Centro Federal de Educação Tecnológica - Unidade Nova Friburgo  
**Curso:** Bacharelado em Sistemas de Informação  
**Nota:** 10,0 pontos  

---

## Instruções para Prova no Papel

1. Utilize **PHP 7** e apenas funções nativas vistas na disciplina.
2. Utilize **UTF-8** em entradas, saídas e manipulações de *strings* (funções `mb_*`).
3. Utilize **PDO** com *prepared statements* para acesso seguro a banco de dados (proteção contra SQL Injection).
4. Utilize **controle de transação** (`beginTransaction`, `commit`, `rollBack`) sempre que uma operação envolver mais de uma tabela ou alteração em lote.
5. Trate exceções adequadamente (`try / catch`) e use `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION`.
6. Repositórios devem receber a instância de `$pdo` em seu construtor e **nunca instanciar PDO diretamente**.
7. Repositórios **não** devem conter comandos de saída (`echo`, `print_r`, `die`).

---

## Banco de Dados de Referência (`locadora`)

Considere o banco de dados MySQL de nome `locadora`, localizado em `localhost`, usuário `root`, senha `root`, charset `utf8mb4`:

```sql
CREATE TABLE categoria (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL UNIQUE
) ENGINE=INNODB;

CREATE TABLE filme (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    preco_diaria DECIMAL(10,2) NOT NULL,
    categoria_id INT NOT NULL,
    CONSTRAINT fk_filme_categoria FOREIGN KEY (categoria_id) 
        REFERENCES categoria(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=INNODB;

CREATE TABLE ator (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL
) ENGINE=INNODB;

-- Tabela associativa NxN
CREATE TABLE filme_ator (
    filme_id INT NOT NULL,
    ator_id INT NOT NULL,
    PRIMARY KEY (filme_id, ator_id),
    CONSTRAINT fk_fa_filme FOREIGN KEY (filme_id) REFERENCES filme(id),
    CONSTRAINT fk_fa_ator FOREIGN KEY (ator_id) REFERENCES ator(id)
) ENGINE=INNODB;
```

---

## Questão 1 (2,0 pontos) - Manipulação de Strings & Formatação

Crie uma função `formatarCpf(string $cpf): string` que receba uma *string* representando um CPF.
- A função deve remover quaisquer caracteres não numéricos.
- Se a string resultante tiver exatamente 11 dígitos numéricos, a função deve retornar o CPF formatado no padrão `000.000.000-00`.
- Se a quantidade de dígitos for diferente de 11 ou contiver valores inválidos, deve retornar uma string vazia `""`.

*Dica para a prova escrita:* use `preg_replace` ou `str_replace` e `mb_strlen` / `substr`.

---

## Questão 2 (2,5 pontos) - Consulta com JOIN e Agrupamento

Escreva um script PHP completo `relatorio_categorias.php` que conecte via PDO ao banco `locadora` e imprima um relatório contendo:
- Nome da categoria;
- Quantidade total de filmes daquela categoria (inclusive categorias com 0 filmes);
- Média do preço da diária dos filmes dessa categoria (formatada com 2 casas decimais ou `0.00` se não houver filmes).

Ordene o resultado pelo nome da categoria em ordem alfabética. Trate exceções de conexão e consulta.

---

## Questão 3 (4,0 pontos) - Classes, Repositório com Transação e Remoção Associada

Considere a classe `Filme`:

```php
class Filme {
    public $id = 0;
    public $titulo = '';
    public $precoDiaria = 0.00;
    public $categoriaId = 0;
    public $atoresIds = []; // array de inteiros com os IDs dos atores

    public function __construct(
        $id = 0,
        $titulo = '',
        $precoDiaria = 0.00,
        $categoriaId = 0,
        array $atoresIds = []
    ) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->precoDiaria = $precoDiaria;
        $this->categoriaId = $categoriaId;
        $this->atoresIds = $atoresIds;
    }
}
```

Considere a interface `RepositorioFilme`:

```php
interface RepositorioFilme {
    /** 
     * Adiciona o filme e insere seus atores associados na tabela filme_ator.
     * Deve atualizar o $filme->id e usar transação.
     */
    public function adicionar( Filme &$filme );

    /**
     * Remove o filme pelo ID. Antes de remover o filme, deve remover todas as associações
     * do filme na tabela filme_ator dentro da mesma transação.
     * Retorna true se removeu ou false se o filme não existia.
     */
    public function remover( $id );
}
```

Crie a classe `RepositorioFilmeEmBDR` que implemente a interface:
1. Deve receber o objeto `PDO` no construtor.
2. Deve implementar `adicionar(Filme &$filme)` usando transação para inserir na tabela `filme`, obter `lastInsertId()`, e inserir cada ator em `filme_ator`.
3. Deve implementar `remover($id)` usando transação: primeiro executa `DELETE FROM filme_ator WHERE filme_id = ?`, depois executa `DELETE FROM filme WHERE id = ?`. Se nenhuma linha foi afetada no filme, faz *rollback* e retorna `false`.
4. Em caso de erro de banco, lançar `RepositorioException` (classe personalizada).

---

## Questão 4 (1,5 pontos) - Script de Cadastro Interativo e Validação

Escreva o script `cadastrar_filme.php` que:
1. Conecte ao banco com PDO.
2. Solicite via `readline()`:
   - Título do filme (deve ter entre 2 e 100 caracteres);
   - Preço da diária (deve ser numérico e maior que zero);
   - ID da Categoria (numérico inteiro > 0);
   - IDs dos atores separados por vírgula (ex: `1, 3, 5` - opcional).
3. Se alguma validação falhar, imprima a mensagem de erro e aborte.
4. Instancie o `Filme` e o `RepositorioFilmeEmBDR`, salve o filme e imprima o ID gerado.
5. Capture eventuais exceções e mostre mensagens claras ao usuário.

---

## Tabela de Funções & Métodos Rápidos para Lembrar no Papel

```php
// PDO
$pdo = new PDO('mysql:host=localhost;dbname=locadora;charset=utf8', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$pdo->beginTransaction();
$pdo->commit();
$pdo->rollBack();
$stmt = $pdo->prepare('INSERT INTO ... VALUES (?, ?)');
$stmt->execute([$v1, $v2]);
$id = $pdo->lastInsertId();
$stmt->rowCount();
$stmt->fetchAll(PDO::FETCH_ASSOC); // ou foreach ($stmt as $linha)

// Strings & Arrays
mb_strlen($str);
trim($str);
explode(',', $str);
is_numeric($val);
number_format($val, 2, '.', '');
readline('Prompt: ');
```

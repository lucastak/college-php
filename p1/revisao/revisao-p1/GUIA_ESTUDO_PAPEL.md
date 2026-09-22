# Guia Prático de Escrita no Papel - P1 (PHP + PDO + OO)

Este guia foi elaborado sob medida para a prova escrita no papel. O foco é **código limpo, direto, sem firulas e fácil de memorizar e escrever à mão**.

---

## 1. Esqueleto Universal de Conexão PDO

Para qualquer script de teste ou cadastro:

```php
$pdo = new PDO( 'mysql:host=localhost;dbname=locadora;charset=utf8', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
] );
```

---

## 2. Esqueleto Universal de Repositório

### a) Interface
```php
interface RepositorioItem {
    function adicionar( Item &$item );
    function remover( $id );
    function todos();
}
```

### b) Classe BDR (Injeção de PDO no Construtor)
```php
class RepositorioItemEmBDR implements RepositorioItem {
    private $pdo;

    public function __construct( PDO $pdo ) {
        $this->pdo = $pdo;
    }

    // Métodos aqui...
}
```

---

## 3. Remoção de Entidades (1 Comando SQL vs Transação)

### Opção A: 1 Único Comando SQL (Multi-table DELETE com JOIN)
> Deleta o registro pai e todos os registros associados na tabela filha de uma só vez:

```php
public function remover( $id ) {
    try {
        $sql = 'DELETE f, fa 
                FROM filme f 
                LEFT JOIN filme_ator fa ON f.id = fa.filme_id 
                WHERE f.id = ?';

        $stmt = $this->pdo->prepare( $sql );
        $stmt->execute( [ $id ] );

        return $stmt->rowCount() > 0;
    } catch ( PDOException $e ) {
        throw new RepositorioException( $e->getMessage() );
    }
}
```

### Opção B: Com Transação e 2 Comandos Separados
```php
public function remover( $id ) {
    try {
        $this->pdo->beginTransaction();

        // 1. Remove primeiro da tabela filha / associativa
        $this->pdo->prepare( 'DELETE FROM filme_ator WHERE filme_id = ?' )->execute( [ $id ] );

        // 2. Remove da tabela pai
        $stmtPai = $this->pdo->prepare( 'DELETE FROM filme WHERE id = ?' );
        $stmtPai->execute( [ $id ] );

        if ( $stmtPai->rowCount() === 0 ) {
            $this->pdo->rollBack();
            return false;
        }

        $this->pdo->commit();
        return true;
    } catch ( PDOException $e ) {
        if ( $this->pdo->inTransaction() ) {
            $this->pdo->rollBack();
        }
        throw new RepositorioException( $e->getMessage() );
    }
}
```

---

## 4. Inserção (1 Comando SQL - Padrão de Prova)

```php
public function adicionar( Filme &$filme ) {
    try {
        $sql = 'INSERT INTO filme (titulo, preco_diaria, categoria_id) VALUES (?, ?, ?)';
        $stmt = $this->pdo->prepare( $sql );
        $stmt->execute( [ $filme->titulo, $filme->precoDiaria, $filme->categoriaId ] );

        $filme->id = (int)$this->pdo->lastInsertId();
    } catch ( PDOException $e ) {
        throw new RepositorioException( $e->getMessage() );
    }
}
```


---

## 5. Consultas e Relatórios Relacionados

### Opção A: Duas Queries Simples + Laço PHP (Estilo Aula 7 - Mais Fácil no Papel)
> Você não precisa lembrar de sintaxe de `JOIN`, `GROUP BY` nem `IFNULL`. Faz um `query` no pai, e um `prepare` no filho:

```php
$stmtCategorias = $pdo->query( 'SELECT id, nome FROM categoria ORDER BY nome ASC' );
$stmtFilmes = $pdo->prepare( 'SELECT preco_diaria FROM filme WHERE categoria_id = ?' );

foreach ( $stmtCategorias as $cat ) {
    $stmtFilmes->execute( [ $cat['id'] ] );

    $total = 0;
    $soma  = 0.0;
    foreach ( $stmtFilmes as $filme ) {
        $total++;
        $soma += (float)$filme['preco_diaria'];
    }
    $media = $total > 0 ? ( $soma / $total ) : 0.00;

    echo $cat['nome'], ' - Total: ', $total, ' - Média: R$ ', number_format( $media, 2, ',', '.' ), PHP_EOL;
}
```

### Opção B: Query Única com LEFT JOIN + GROUP BY
```php
$sql = "SELECT c.nome, 
               COUNT(f.id) AS total, 
               IFNULL(AVG(f.preco_diaria), 0) AS media
        FROM categoria c
        LEFT JOIN filme f ON c.id = f.categoria_id
        GROUP BY c.id, c.nome
        ORDER BY c.nome ASC";

$stmt = $pdo->query( $sql );
foreach ( $stmt as $r ) {
    echo $r['nome'], ' - Total: ', $r['total'], ' - Média: R$ ', number_format( $r['media'], 2, ',', '.' ), PHP_EOL;
}
```

---

## 6. Validações Clássicas de Entrada (`readline`)

```php
// String com limite de tamanho
$nome = trim( readline( 'Nome: ' ) );
if ( mb_strlen( $nome ) < 2 || mb_strlen( $nome ) > 100 ) {
    die( 'Nome inválido.' );
}

// Número decimal positivo
$preco = trim( readline( 'Preço: ' ) );
if ( !is_numeric( $preco ) || (float)$preco <= 0 ) {
    die( 'Preço inválido.' );
}

// Inteiro positivo (chave estrangeira)
$id = trim( readline( 'ID: ' ) );
if ( !is_numeric( $id ) || (int)$id <= 0 ) {
    die( 'ID inválido.' );
}

// Lista separada por vírgula (ex: 1, 2, 3)
$entrada = trim( readline( 'IDs: ' ) );
$ids = [];
if ( $entrada !== '' ) {
    foreach ( explode( ',', $entrada ) as $item ) {
        $item = trim( $item );
        if ( is_numeric( $item ) && (int)$item > 0 ) {
            $ids[] = (int)$item;
        }
    }
}

// Filtrar apenas números de uma string SEM REGEX (ideal para papel):
$somenteNumeros = '';
for ( $i = 0; $i < mb_strlen( $texto ); $i++ ) {
    $c = mb_substr( $texto, $i, 1 );
    if ( is_numeric( $c ) ) {
        $somenteNumeros .= $c;
    }
}

```

---

## 7. O que NÃO Fazer na Prova Escrita ⚠️

1. **Nunca use `echo` ou `print` dentro de métodos de Repositório.** Repositório só manipula dados e lança `RepositorioException`.
2. **Nunca crie `new PDO` dentro do Repositório.** Receba `$pdo` pronto no construtor.
3. **Não esqueça o `&`** em `adicionar( Objeto &$obj )` se o método atualizar o ID do objeto.
4. **Não concatene variáveis direto no SQL.** Sempre use `?` com `$stmt->execute( [ $var ] )`.
5. **Sempre use `mb_strlen`** ao invés de `strlen` (regra expressa de UTF-8 do professor).
6. **Não esqueça do `rollBack()`** no bloco `catch` de transações.

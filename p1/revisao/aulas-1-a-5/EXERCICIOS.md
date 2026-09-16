# Caderno de Exercícios de Revisão: P1 (Aulas 1 a 5)

**Disciplina:** Programação para a Web  
**Instituição:** CEFET/RJ - Unidade Nova Friburgo  
**Conteúdo:** Aulas 1 a 5 (Fundamentos, Arrays/JSON, Strings/CSV/Hash, POO/Interfaces e Namespaces/Exceptions/Traits/Repositório)

---

## Orientações Gerais

1. Escreva seu código em PHP compatível com PHP 7/8, utilizando apenas funções nativas da linguagem (sem bibliotecas externas como Composer).
2. Para manipulação de texto, utilize sempre funções *multibyte* (`mb_strlen`, `mb_substr`, etc.) para suporte correto a UTF-8.
3. Ao lidar com exceções, utilize subclasses adequadas (`\Exception`, `\RuntimeException`, `\DomainException`).
4. Ao final da resolução, consulte o diretório `gabarito/` para comparar sua implementação e rode `php testar_gabarito.php` para validar todas as asserções.

---

## Módulo 1: Aula 1 - Fundamentos, Funções e Lógica Básica

### Exercício 1.1: Sistema de RPG - Buff de Experiência por Referência
Em jogos de RPG, feitiços e poções aplicam bônus ("buffs") que alteram diretamente a experiência do personagem.  
Crie uma função `aplicarBuffExp(int &$experiencia, float $multiplicador = 1.25, int $bonusFixo = 50): void` que:
- Receba a variável `$experiencia` **por referência** (`&`).
- Possua parâmetros com valor padrão: `$multiplicador = 1.25` e `$bonusFixo = 50`.
- Se `$experiencia < 0` ou `$multiplicador < 1.0` ou `$bonusFixo < 0`, a função não deve alterar a variável original.
- Caso os valores sejam válidos, calcule a nova experiência como `intval(($experiencia * $multiplicador) + $bonusFixo)` e atualize a variável passada.
- Exemplo:
  ```php
  $xp = 1000;
  aplicarBuffExp($xp); // $xp passa a valer 1300 (1000 * 1.25 + 50)
  aplicarBuffExp($xp, 1.10, 100); // $xp passa a valer 1530 (1300 * 1.10 + 100)
  ```

### Exercício 1.2: Monitor Térmico de Servidores (Sem Funções Prontas)
Um data center registra a temperatura em graus Celsius de diversos racks de servidores em um array numérico (ex: `[62.5, 78.0, 55.4, 82.1, 71.0]`).  
Implemente as seguintes funções **sem utilizar funções nativas prontas** como `max()`, `min()`, `array_sum()` ou `array_filter()`:
1. `detectarPicoTermico(array $leituras): ?float`: Retorna a temperatura mais alta registrada. Retorna `null` se o array for vazio.
2. `detectarMenorTemperatura(array $leituras): ?float`: Retorna a temperatura mais baixa registrada. Retorna `null` se o array for vazio.
3. `calcularMediaTermica(array $leituras): float`: Retorna a média das temperaturas com base em iteração manual. Se o array for vazio, deve prevenir divisão por zero e retornar `0.0`.
4. `contarSuperaquecimentos(array $leituras, float $limiar = 75.0): int`: Retorna a contagem de leituras que excederam estritamente o `$limiar`.

### Exercício 1.3: Extrato de Cartão Pré-pago com Heredoc
Crie uma função `gerarExtratoCartao(string $titular, float $saldoAnterior, float $recarga, float $gasto): string` que:
- Calcule o saldo atualizado: `$saldoAtual = $saldoAnterior + $recarga - $gasto`.
- Determine o status do cartão usando o operador ternário: se `$saldoAtual >= 0`, status é `"Regular"`, caso contrário `"Bloqueado / Negativo"`.
- Formate a saída utilizando a sintaxe **Heredoc (`<<<`)**, com valores decimais em 2 casas decimais:
  ```text
  ========================================
  EXTRATO CARTAO PRE-PAGO
  ========================================
  Titular: Lucas Santos
  Saldo Anterior: R$ 50.00
  Recarga: R$ 100.00
  Gasto: R$ 120.00
  Saldo Atual: R$ 30.00
  Status: Regular
  ========================================
  ```

---

## Módulo 2: Aula 2 - Arrays Associativos, Horários e CRUD em JSON

### Exercício 2.1: Calculador de Duração de Voos com Explode
Crie uma função `calcularDuracaoVoo(string $partida, string $chegada): string` que receba os horários nos formatos `"HH:MM"` (ex: `"08:45"` e `"13:10"`):
- Utilize a função `explode(':', ...)` para extrair horas e minutos de cada string.
- Converta os horários para minutos a partir da meia-noite (`horas * 60 + minutos`).
- Caso o voo seja noturno e a chegada ocorra no dia seguinte (ex: partida `"22:30"` e chegada `"02:15"`), compense adicionando 24 horas (1440 minutos) à chegada.
- Retorne a duração formatada exatamente como `"Xh e Ymin"` (ex: `"4h e 25min"`).
- Se qualquer horário não possuir exatamente 2 partes ou tiver valores negativos/inválidos, retorne string vazia `""`.

### Exercício 2.2: Catálogo de Filmes em Streaming com Persistência JSON
Implemente funções para gerenciar um catálogo de filmes em streaming. Cada filme é representado por um array associativo:
`['id' => int, 'titulo' => string, 'genero' => string, 'ano' => int, 'nota' => float]`

Funções obrigatórias:
1. `buscarIndiceFilme(array $catalogo, int $id): int`: Retorna a posição do filme no array pelo seu `$id`, ou `-1` se não for encontrado.
2. `adicionarFilme(array &$catalogo, array $filme): bool`: Adiciona o filme se o `$id` for único e a `nota` estiver entre `0.0` e `10.0`. Retorna `true` se adicionado ou `false` caso contrário.
3. `removerFilme(array &$catalogo, int $id): bool`: Localiza e remove o filme usando `unset()`. Reindexe o array com `array_values()`. Retorna `true` se removeu ou `false` se não existia.
4. `filtrarPorGenero(array $catalogo, string $genero): array`: Retorna todos os filmes que possuem o gênero informado, ignorando diferenças entre maiúsculas e minúsculas (`mb_strtolower`).
5. `salvarCatalogoJson(string $arquivo, array $catalogo): bool`: Salva o catálogo em disco no formato JSON usando `json_encode($catalogo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)`.
6. `carregarCatalogoJson(string $arquivo): array`: Lê o arquivo JSON com `file_get_contents` e converte para array associativo via `json_decode($conteudo, true)`. Retorna `[]` se o arquivo não existir.

---

## Módulo 3: Aula 3 - Strings Multibyte, Hashes e Manipulação de CSV

### Exercício 3.1: Gerador de Slug Web e Contador de Acentos Multibyte
Sistemas web convertem títulos de matérias em URLs amigáveis (slugs), como `"Programação Web Fácil"` -> `"programacao-web-facil"`.  
Implemente duas funções:
1. `gerarSlugWeb(string $titulo): string`:
   - Converte caracteres acentuados comuns para versões sem acento:  
     `á, à, â, ã -> a | é, ê -> e | í -> i | ó, ô, õ -> o | ú -> u | ç -> c` (e maiúsculas equivalentes).
   - Converte tudo para minúsculas usando `mb_strtolower()`.
   - Substitui espaços simples por hífens `-`.
   - Remove caracteres que não sejam letras, números ou hífens.
   - Limita o tamanho final a no máximo 30 caracteres usando `mb_substr()`.
2. `contarCaracteresAcentuados(string $texto): int`:
   - Percorre a string caractere a caractere utilizando um laço `for` com `mb_substr($texto, $i, 1, 'UTF-8')`.
   - Retorna o número total de caracteres acentuados encontrados.

### Exercício 3.2: Assinatura de Requisições com Hash SHA-256
Para garantir que uma chamada de API não foi alterada por terceiros, gera-se uma assinatura digital combinando o método HTTP, a URL, o corpo da mensagem e um segredo compartilhado:
1. `gerarAssinaturaRequisicao(string $metodo, string $url, string $payload, string $segredo): string`:
   - Concatena os campos separados por pipe: `mb_strtoupper($metodo) . '|' . $url . '|' . $payload . '|' . $segredo`.
   - Retorna o hash seguro utilizando `hash('sha256', ...)`.
2. `validarAssinaturaRequisicao(string $assinaturaRecebida, string $metodo, string $url, string $payload, string $segredo): bool`:
   - Gera a assinatura esperada com a função anterior e verifica se é estritamente idêntica à `$assinaturaRecebida`.

### Exercício 3.3: Auditor de Faturas Corporativas em CSV
Considere um arquivo `dados/faturas.csv` com as seguintes colunas:
`NumeroFatura;Cliente;DataVencimento;ValorOriginal;Status` (ex: `FAT-101;Padaria Modelo;15/06/2024; R$ 3.250,50 ;Pendente`).

Crie a função `processarFaturasCsv(string $caminhoCsv): array` que:
- Carregue o arquivo com `file_get_contents` e quebre as linhas por `explode("\n", ...)`.
- Descarte a linha de cabeçalho com `array_shift`.
- Ignore linhas em branco.
- Para cada linha, separe as colunas por `;`.
- Higienize o campo monetário: remova o prefixo `"R$"`, espaços e quebras de linha com `trim()`, remova separadores de milhar `.` e troque a vírgula decimal `,` por ponto `.` com `str_replace()`, convertendo para `(float)`.
- Retorne um array associativo com:
  - `'total_faturas'`: Quantidade total de faturas lidas.
  - `'valor_total_pago'`: Soma dos valores das faturas com Status `"Pago"`.
  - `'valor_total_pendente'`: Soma dos valores das faturas com Status `"Pendente"`.
  - `'maior_fatura_pendente'`: Nome do cliente que possui a maior fatura pendente individual.

---

## Módulo 4: Aula 4 - POO, Composição, Regras de Negócio e Interfaces

Implemente um sistema de **Locação de Veículos de Frota**:

### 1. Classe `Veiculo`
- Atributos privados: `int $id`, `string $modelo`, `string $categoria` (ex: "Sedan", "SUV"), `float $diaria`, `bool $disponivel`.
- Construtor: inicializa os 4 primeiros atributos. `$disponivel` é inicializado como `true`.
- Métodos:
  - Getters: `getId()`, `getModelo()`, `getCategoria()`, `getDiaria()`, `isDisponivel(): bool`.
  - `reservar(): bool`: Se disponível, muda para `false` e retorna `true`. Se indisponível, retorna `false`.
  - `liberar(): void`: Marca `$disponivel = true`.

### 2. Classe `ItemLocacao`
- Atributos privados: `Veiculo $veiculo`, `int $dias`, `float $taxaSeguroDiario`.
- Construtor: recebe `$veiculo`, `$dias` e `$taxaSeguroDiario`.
- Métodos:
  - `veiculo(): Veiculo`
  - `dias(): int`
  - `taxaSeguro(): float`
  - `subtotal(): float`: Retorna `($veiculo->getDiaria() + $this->taxaSeguroDiario) * $this->dias`.

### 3. Interface `NotificadorLocacao`
- Método: `public function notificar(ContratoLocacao $contrato): void;`

### 4. Classe `ContratoLocacao`
- Atributos privados:
  - `string $cliente`
  - `ItemLocacao[] $itens = []`
  - `bool $fechado = false`
  - `float $descontoPercentual = 0.0`
- Construtor: recebe o nome do `$cliente`.
- Métodos:
  - `adicionarItem(Veiculo $veiculo, int $dias, float $seguroDiario = 20.0): bool`:  
    Se o contrato já estiver fechado ou o veículo não estiver disponível (`!$veiculo->isDisponivel()`), recusa e retorna `false`. Caso contrário, reserva o veículo (`$veiculo->reservar()`), instancia um `ItemLocacao`, adiciona ao array e retorna `true`.
  - `removerItem(int $posicao): bool`:  
    Se o contrato já estiver fechado ou o índice não existir, retorna `false`. Caso contrário, libera o veículo (`$item->veiculo()->liberar()`), remove do array via `array_splice` e retorna `true`.
  - `concederDesconto(float $percentual): bool`:  
    Se o contrato estiver fechado ou `$percentual` for menor que 0 ou maior que 100, retorna `false`. Armazena o desconto e retorna `true`.
  - `subtotal(): float`: Soma os subtotais de todos os itens de locação.
  - `total(): float`: Retorna o subtotal com o desconto percentual aplicado.
  - `fecharContrato(?NotificadorLocacao $notificador = null): void`:  
    Marca `$fechado = true`. Se `$notificador !== null`, invoca `$notificador->notificar($this)`. Uma vez fechado, nenhuma adição, remoção ou alteração de desconto é aceita.
  - Getters: `isFechado(): bool`, `getCliente(): string`, `getItens(): array`, `getDescontoPercentual(): float`.

### 5. Classe `EmissorContratoConsole` (implementa `NotificadorLocacao`)
- Implementa `notificar(ContratoLocacao $contrato): void`, exibindo no terminal o contrato discriminando cliente, veículos locados, dias, diárias, seguros, subtotal, desconto aplicado e valor total final.

---

## Módulo 5: Aula 5 - Namespaces, Exceções Customizadas, Traits e Repositório JSON

Implemente um sistema de **Agendamentos Clínicos**:

### 1. Estrutura de Namespaces
- Exceções: `namespace clinica\excecoes;`
- Domínio e Traits: `namespace clinica\dominio;`
- Persistência: `namespace clinica\persistencia;`

### 2. Exceções Customizadas
- `AgendamentoException`: herda de `\DomainException` para violações de regras do paciente/consulta.
- `PersistenciaException`: herda de `\RuntimeException` para erros de arquivos, I/O ou IDs duplicados.

### 3. Trait `SerializavelJson`
- No namespace `clinica\dominio`.
- Método `toJson(): string`: Retorna os atributos do objeto como JSON formatado (`JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE`).

### 4. Entidade `Agendamento`
- No namespace `clinica\dominio`, utilizando o trait `SerializavelJson`.
- Atributos privados: `int $id`, `string $paciente`, `string $medico`, `string $dataConsulta`, `float $valor`.
- Construtor chama o método privado `validar()`:
  - `$paciente` deve ter entre 3 e 60 caracteres (`mb_strlen`). Senão lança `AgendamentoException`.
  - `$medico` deve ter entre 3 e 60 caracteres. Senão lança `AgendamentoException`.
  - `$dataConsulta` deve ser uma data válida no formato `"DD/MM/AAAA"` validada com `checkdate($mes, $dia, $ano)` após `explode`. Senão lança `AgendamentoException`.
  - `$valor` deve ser estritamente maior que zero. Senão lança `AgendamentoException`.
- Getters para todos os atributos.

### 5. Interface `RepositorioAgendamentos`
- No namespace `clinica\persistencia`.
- Métodos:
  - `adicionar(Agendamento $agendamento): void`
  - `obterTodos(): array` (retorna instâncias de `Agendamento`)
  - `buscarPorId(int $id): ?Agendamento`
  - `cancelarPorId(int $id): bool`

### 6. Classe `RepositorioAgendamentosJson`
- No namespace `clinica\persistencia`, implementa a interface `RepositorioAgendamentos`.
- Construtor recebe o caminho do arquivo JSON.
- `adicionar(Agendamento $agendamento)`: Se já existir agendamento com o mesmo ID, lança `PersistenciaException`.
- `obterTodos()`: Retorna `[]` se arquivo não existir. Se houver falha de leitura, lança `PersistenciaException`. Reconstitui instâncias da classe `Agendamento`.
- Ao salvar: codifica e grava no arquivo. Se falhar, lança `PersistenciaException`.

---

## Simulado P1: Desafio Integrado

### Questão 1 (2,5 pontos): Auditoria de Consumo Elétrico em CSV
Considere o arquivo `dados/energia.csv` com colunas:  
`UnidadeConsumidora;BandeiraTarifaria;ConsumoKwh;PrecoKwh`  
Crie a função `auditarConsumoEnergia(string $caminhoCsv, float $limiteKwh): array` que:
- Leia o arquivo CSV descartando o cabeçalho.
- Identifique todas as unidades com consumo estritamente superior ao `$limiteKwh`.
- Se a bandeira for `"Vermelha"`, adicione uma sobretaxa de `10%` sobre o valor total daquela unidade.
- Retorne um array com:
  - `'unidades_acima_limite'`: Lista de códigos das UCs que ultrapassaram o limite.
  - `'consumo_total_kwh'`: Soma total do consumo em kWh de todas as unidades.
  - `'arrecadacao_total'`: Valor financeiro total a ser faturado (considerando a sobretaxa da bandeira vermelha).

### Questão 2 (1,5 ponto): Validador e Padronizador de Placas Veiculares
Crie a função `padronizarPlaca(string $placa): string`:
- Uma placa válida possui exatamente 7 caracteres alfanuméricos.
- Padrão Tradicional: 3 letras seguidas de 4 números (ex: `"ABC1234"`). Deve ser padronizada com hífen: `"ABC-1234"`.
- Padrão Mercosul: 3 letras, 1 número, 1 letra e 2 números (ex: `"ABC1D23"`). Deve ser padronizada sem hífen: `"ABC1D23"`.
- Letras devem ser convertidas para maiúsculas.
- Se a placa não atender a nenhum dos dois padrões, retorne string vazia `""`.

### Questão 3 (6,0 pontos): Fluxo Integrado de Gestão de Frota e Persistência
Implemente um script que execute o ciclo completo:
1. Cadastre veículos no pátio.
2. Crie um contrato para um cliente, adicione locações e aplique desconto.
3. Conclua o contrato emitindo o comprovante pelo terminal.
4. Salve o histórico dos contratos fechados em um repositório JSON, garantindo tratamento adequado de exceções.

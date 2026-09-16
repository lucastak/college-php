<?php

/**
 * TESTADOR AUTOMATIZADO DE TODAS AS SOLUÇÕES DO GABARITO INÉDITO (AULAS 1 A 5)
 * Execução: php gabarito/testar_gabarito.php
 */

$totalTestes = 0;
$testesPassados = 0;

function assercao(bool $condicao, string $descricao): void {
    global $totalTestes, $testesPassados;
    $totalTestes++;
    if ($condicao) {
        $testesPassados++;
        echo "  [\033[32mPASS\033[0m] {$descricao}\n";
    } else {
        echo "  [\033[31mFAIL\033[0m] {$descricao}\n";
    }
}

echo "=======================================================\n";
echo "  BATERIA DE TESTES AUTOMATIZADOS - REVISÃO P1 (INÉDITA)\n";
echo "=======================================================\n\n";

// ==========================================
// 1. TESTES DA AULA 1 (Inédita)
// ==========================================
echo "1. Validando Módulo 1 (Aula 1 - RPG, Monitor Térmico e Heredoc)...\n";
require_once __DIR__ . '/aula1_fundamentos.php';

$xp = 1000;
aplicarBuffExp($xp); // 1000 * 1.25 + 50 = 1300
assercao($xp === 1300, "Buff de XP por referência com valores padrão (1300)");

aplicarBuffExp($xp, 1.10, 100); // 1300 * 1.10 + 100 = 1530
assercao($xp === 1530, "Buff de XP por referência customizado (1530)");

$leituras = [62.5, 78.0, 55.4, 82.1, 71.0];
assercao(detectarPicoTermico($leituras) === 82.1, "Maior temperatura detectada sem funções prontas (82.1°C)");
assercao(detectarMenorTemperatura($leituras) === 55.4, "Menor temperatura detectada sem funções prontas (55.4°C)");
assercao(abs(calcularMediaTermica($leituras) - 69.8) < 0.01, "Média térmica calculada por iteração (69.8°C)");
assercao(calcularMediaTermica([]) === 0.0, "Média de leituras vazias previne divisão por zero (0.0)");
assercao(contarSuperaquecimentos($leituras, 75.0) === 2, "Contagem de superaquecimentos acima de 75°C (2)");

$extrato = gerarExtratoCartao("Lucas Santos", 50.0, 100.0, 120.0);
assercao(strpos($extrato, "Saldo Atual: R$ 30.00") !== false && strpos($extrato, "Status: Regular") !== false, "Extrato formatado com Heredoc e operador ternário");

// ==========================================
// 2. TESTES DA AULA 2 (Inédita)
// ==========================================
echo "\n2. Validando Módulo 2 (Aula 2 - Duração de Voos e Filmes JSON)...\n";
require_once __DIR__ . '/aula2_crud_json.php';

assercao(calcularDuracaoVoo("08:45", "13:10") === "4h e 25min", "Duração de voo diurno (4h e 25min)");
assercao(calcularDuracaoVoo("22:30", "02:15") === "3h e 45min", "Duração de voo noturno com virada de dia (3h e 45min)");
assercao(calcularDuracaoVoo("horario_invalido", "12:00") === "", "Rejeição de horário mal formatado");

$catalogo = [
    ['id' => 1, 'titulo' => 'Matrix', 'genero' => 'Ficção Científica', 'ano' => 1999, 'nota' => 8.7],
    ['id' => 2, 'titulo' => 'O Poderoso Chefão', 'genero' => 'Drama', 'ano' => 1972, 'nota' => 9.2]
];

assercao(buscarIndiceFilme($catalogo, 2) === 1, "Busca de índice de filme por ID (encontrado)");
assercao(buscarIndiceFilme($catalogo, 99) === -1, "Busca de índice de filme por ID (não encontrado)");

$adicionou = adicionarFilme($catalogo, ['id' => 3, 'titulo' => 'Duna', 'genero' => 'Ficção Científica', 'ano' => 2021, 'nota' => 8.0]);
assercao($adicionou && count($catalogo) === 3, "Adição de novo filme com validações");

$adicionouDuplicado = adicionarFilme($catalogo, ['id' => 1, 'titulo' => 'Matrix 2', 'genero' => 'Ficção', 'ano' => 2003, 'nota' => 7.0]);
assercao(!$adicionouDuplicado, "Bloqueio de cadastro de filme com ID duplicado");

$removeu = removerFilme($catalogo, 1);
assercao($removeu && count($catalogo) === 2 && $catalogo[0]['id'] === 2, "Remoção de filme e reorganização de chaves");

$generosFiccao = filtrarPorGenero($catalogo, "ficção científica");
assercao(count($generosFiccao) === 1 && $generosFiccao[0]['titulo'] === 'Duna', "Filtragem case-insensitive por gênero");

$tempJson = __DIR__ . '/teste_temp_filmes.json';
salvarCatalogoJson($tempJson, $catalogo);
$carregados = carregarCatalogoJson($tempJson);
assercao(count($carregados) === 2, "Persistência e recuperação de catálogo em arquivo JSON");
if (file_exists($tempJson)) {
    unlink($tempJson);
}

// ==========================================
// 3. TESTES DA AULA 3 (Inédita)
// ==========================================
echo "\n3. Validando Módulo 3 (Aula 3 - Slugs Multibyte, Hash SHA-256 e Faturas CSV)...\n";
require_once __DIR__ . '/aula3_strings_csv.php';

$slug = gerarSlugWeb("Aprenda Programação Web com Acentos e Emojis!");
assercao($slug === "aprenda-programacao-web-com-ac", "Geração de slug sem acentos e limitado a 30 caracteres");

$totalAcentos = contarCaracteresAcentuados("Programação é ótimo");
assercao($totalAcentos === 4, "Contagem precisa de caracteres acentuados com mb_substr (4)");

$segredo = "segredo_da_api_123";
$assinatura = gerarAssinaturaRequisicao("POST", "/pagamento", '{"valor":200}', $segredo);
assercao(validarAssinaturaRequisicao($assinatura, "POST", "/pagamento", '{"valor":200}', $segredo), "Validação de assinatura digital de requisição com SHA-256");
assercao(!validarAssinaturaRequisicao($assinatura, "GET", "/pagamento", '{"valor":200}', $segredo), "Rejeição de assinatura quando método HTTP é alterado");

$csvFaturas = dirname(__DIR__) . '/dados/faturas.csv';
$resFaturas = processarFaturasCsv($csvFaturas);
assercao($resFaturas['total_faturas'] === 5, "Contagem total de faturas lidas no CSV (5)");
assercao(abs($resFaturas['valor_total_pago'] - 7500.80) < 0.01, "Soma monetária de faturas com status Pago (R$ 7.500,80)");
assercao(abs($resFaturas['valor_total_pendente'] - 7010.70) < 0.01, "Soma monetária de faturas com status Pendente (R$ 7.010,70)");
assercao($resFaturas['maior_fatura_pendente'] === "Restaurante Sabor da Serra", "Identificação do cliente com maior fatura pendente");

// ==========================================
// 4. TESTES DA AULA 4 (Inédita)
// ==========================================
echo "\n4. Validando Módulo 4 (Aula 4 - Locadora de Veículos e Interfaces)...\n";
require_once __DIR__ . '/aula4_poo/Veiculo.php';
require_once __DIR__ . '/aula4_poo/ItemLocacao.php';
require_once __DIR__ . '/aula4_poo/ContratoLocacao.php';

$v1 = new Veiculo(1, "Onix", "Econômico", 100.0);
$v2 = new Veiculo(2, "Corolla", "Sedan", 200.0);

$contrato = new ContratoLocacao("Mariana Silveira");
assercao($contrato->adicionarItem($v1, 3, 20.0), "Adicionar veículo disponível ao contrato");
assercao(!$v1->isDisponivel(), "Alteração de status do veículo para reservado após inserção");
assercao(!$contrato->adicionarItem($v1, 2), "Bloqueio de inserção de veículo já reservado");

assercao($contrato->subtotal() === 360.0, "Cálculo do subtotal da locação (3 x (100 + 20) = 360.0)");

$contrato->concederDesconto(10.0);
assercao($contrato->total() === 324.0, "Cálculo do total com 10% de desconto (360 - 36 = 324.0)");

$contrato->fecharContrato();
assercao($contrato->isFechado(), "Status do contrato alterado para fechado");
assercao(!$contrato->adicionarItem($v2, 1), "Bloqueio de inclusão de novos itens após fechamento");
assercao(!$contrato->removerItem(0), "Bloqueio de remoção de itens após fechamento");

// ==========================================
// 5. TESTES DA AULA 5 (Inédita)
// ==========================================
echo "\n5. Validando Módulo 5 (Aula 5 - Agendamentos Clínicos e Repositório)...\n";
require_once __DIR__ . '/aula5_avancado/excecoes/AgendamentoException.php';
require_once __DIR__ . '/aula5_avancado/excecoes/PersistenciaException.php';
require_once __DIR__ . '/aula5_avancado/dominio/SerializavelJson.php';
require_once __DIR__ . '/aula5_avancado/dominio/Agendamento.php';
require_once __DIR__ . '/aula5_avancado/persistencia/RepositorioAgendamentos.php';
require_once __DIR__ . '/aula5_avancado/persistencia/RepositorioAgendamentosJson.php';

use clinica\dominio\Agendamento;
use clinica\excecoes\AgendamentoException;
use clinica\excecoes\PersistenciaException;
use clinica\persistencia\RepositorioAgendamentosJson;

$agValido = new Agendamento(1, "Rodrigo Faro", "Dra. Beatriz Santos", "25/08/2024", 250.0);
assercao(strpos($agValido->toJson(), "Rodrigo Faro") !== false, "Serialização de agendamento com Trait SerializavelJson");

$recusouPacienteCurto = false;
try {
    new Agendamento(2, "Jo", "Dra. Beatriz Santos", "25/08/2024", 250.0);
} catch (AgendamentoException $e) {
    $recusouPacienteCurto = true;
}
assercao($recusouPacienteCurto, "Lançamento de AgendamentoException para paciente com nome curto (< 3 chars)");

$recusouDataInexistente = false;
try {
    new Agendamento(3, "Carlos Silva", "Dr. Ramos", "31/02/2024", 200.0);
} catch (AgendamentoException $e) {
    $recusouDataInexistente = true;
}
assercao($recusouDataInexistente, "Lançamento de AgendamentoException para data inexistente (31/02 via checkdate)");

$recusouValorZero = false;
try {
    new Agendamento(4, "Carlos Silva", "Dr. Ramos", "15/09/2024", 0.0);
} catch (AgendamentoException $e) {
    $recusouValorZero = true;
}
assercao($recusouValorZero, "Lançamento de AgendamentoException para valor de consulta zerado ou negativo");

$arquivoAgTeste = __DIR__ . '/teste_repo_agendamentos.json';
if (file_exists($arquivoAgTeste)) {
    unlink($arquivoAgTeste);
}
$repoAg = new RepositorioAgendamentosJson($arquivoAgTeste);
$repoAg->adicionar($agValido);
$recuperados = $repoAg->obterTodos();
assercao(count($recuperados) === 1 && $recuperados[0]->getPaciente() === "Rodrigo Faro", "Adição e recuperação de agendamentos no RepositorioAgendamentosJson");
if (file_exists($arquivoAgTeste)) {
    unlink($arquivoAgTeste);
}

// ==========================================
// 6. TESTES DO SIMULADO P1 (Inédito)
// ==========================================
echo "\n6. Validando Simulado P1 (Questões Integradas Inéditas)...\n";
require_once __DIR__ . '/simulado_p1/q1_auditoria_energia.php';
require_once __DIR__ . '/simulado_p1/q2_padronizador_placa.php';

$csvEnergia = dirname(__DIR__) . '/dados/energia.csv';
$auditoria = auditarConsumoEnergia($csvEnergia, 400.0);
assercao(count($auditoria['unidades_acima_limite']) === 2, "Identificação de 2 unidades excedendo limite de 400 kWh");
assercao($auditoria['consumo_total_kwh'] === 1810.0, "Consumo total de energia somado (1810 kWh)");
assercao($auditoria['arrecadacao_total'] > 1500.0, "Cálculo de faturamento com sobretaxa de bandeira vermelha");

assercao(padronizarPlaca("abc1234") === "ABC-1234", "Padronização de placa modelo antigo com hífen (ABC-1234)");
assercao(padronizarPlaca("bra2e19") === "BRA2E19", "Padronização de placa modelo Mercosul sem hífen (BRA2E19)");
assercao(padronizarPlaca("placa_errada") === "", "Rejeição de placa com formato inválido");

echo "\n=======================================================\n";
echo "  RESULTADO FINAL: {$testesPassados}/{$totalTestes} TESTES PASSARAM COM SUCESSO!\n";
echo "=======================================================\n";

if ($testesPassados === $totalTestes) {
    echo "\033[32mPARABÉNS! Todo o gabarito das Aulas 1 a 5 está 100% INÉDITO, validado e funcional.\033[0m\n";
} else {
    echo "\033[31mATENÇÃO: Houve falha em um ou mais testes.\033[0m\n";
    exit(1);
}

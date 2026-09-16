<?php

/**
 * GABARITO - AULA 5: Script de Testes Automatizados (Agendamentos Clínicos)
 */

require_once __DIR__ . '/excecoes/AgendamentoException.php';
require_once __DIR__ . '/excecoes/PersistenciaException.php';
require_once __DIR__ . '/dominio/SerializavelJson.php';
require_once __DIR__ . '/dominio/Agendamento.php';
require_once __DIR__ . '/persistencia/RepositorioAgendamentos.php';
require_once __DIR__ . '/persistencia/RepositorioAgendamentosJson.php';

use clinica\dominio\Agendamento;
use clinica\excecoes\AgendamentoException;
use clinica\excecoes\PersistenciaException;
use clinica\persistencia\RepositorioAgendamentosJson;

echo "=== TESTE AULA 5 (INÉDITO): Agendamentos Clínicos e Repositório JSON ===\n\n";

// 1. Criação de agendamento válido e teste do Trait SerializavelJson
try {
    $ag1 = new Agendamento(1, "Rodrigo Faro", "Dra. Beatriz Santos", "25/08/2024", 250.00);
    echo "Agendamento criado com sucesso:\n";
    echo $ag1->toJson() . "\n\n";
} catch (AgendamentoException $e) {
    echo "ERRO INESPERADO: " . $e->getMessage() . "\n";
}

// 2. Validações estritas do construtor
echo "Testando validações do construtor:\n";

// Paciente curto
try {
    new Agendamento(2, "Al", "Dra. Beatriz Santos", "25/08/2024", 250.00);
    echo "FALHA: Deveria recusar nome curto de paciente.\n";
} catch (AgendamentoException $e) {
    echo "OK -> AgendamentoException capturada (paciente curto): " . $e->getMessage() . "\n";
}

// Data inválida no calendário (31 de fevereiro)
try {
    new Agendamento(3, "Camila Pitanga", "Dr. Marcelo Ramos", "31/02/2024", 300.00);
    echo "FALHA: Deveria recusar data inexistente (31/02).\n";
} catch (AgendamentoException $e) {
    echo "OK -> AgendamentoException capturada (data inexistente): " . $e->getMessage() . "\n";
}

// Valor zerado ou negativo
try {
    new Agendamento(4, "Camila Pitanga", "Dr. Marcelo Ramos", "15/09/2024", 0.00);
    echo "FALHA: Deveria recusar valor zerado.\n";
} catch (AgendamentoException $e) {
    echo "OK -> AgendamentoException capturada (valor inválido): " . $e->getMessage() . "\n";
}

// 3. Teste do RepositorioAgendamentosJson
echo "\nTestando RepositorioAgendamentosJson:\n";
$arquivoTeste = __DIR__ . '/agendamentos_teste.json';
if (file_exists($arquivoTeste)) {
    unlink($arquivoTeste);
}

try {
    $repo = new RepositorioAgendamentosJson($arquivoTeste);

    $iniciais = $repo->obterTodos();
    echo "Consultas iniciais: " . count($iniciais) . " (Esperado: 0)\n";

    $repo->adicionar(new Agendamento(1, "Rodrigo Faro", "Dra. Beatriz Santos", "25/08/2024", 250.00));
    $repo->adicionar(new Agendamento(2, "Fernanda Montenegro", "Dr. Arnaldo Lima", "10/09/2024", 400.00));
    echo "Adicionados 2 agendamentos no repositório.\n";

    // Teste de ID duplicado (deve lançar PersistenciaException)
    try {
        $repo->adicionar(new Agendamento(1, "Outro Paciente", "Dr. Silva", "12/09/2024", 200.00));
        echo "FALHA: Não deveria permitir ID duplicado.\n";
    } catch (PersistenciaException $e) {
        echo "OK -> PersistenciaException capturada (ID duplicado): " . $e->getMessage() . "\n";
    }

    // Busca por ID
    $buscado = $repo->buscarPorId(2);
    echo "Buscado ID 2: " . ($buscado !== null ? $buscado->getPaciente() : "Não encontrado") . "\n";

    // Cancelamento
    $cancelou = $repo->cancelarPorId(1);
    echo "Cancelou ID 1: " . ($cancelou ? "SIM" : "NÃO") . "\n";
    $restantes = $repo->obterTodos();
    echo "Total restante no JSON: " . count($restantes) . " (Esperado: 1)\n";

} catch (PersistenciaException $e) {
    echo "ERRO no repositório: " . $e->getMessage() . "\n";
} finally {
    if (file_exists($arquivoTeste)) {
        unlink($arquivoTeste);
    }
}

echo "\nAula 5 inédita validada com sucesso!\n";

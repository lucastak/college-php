<?php

namespace clinica\persistencia;

require_once dirname(__DIR__) . '/dominio/Agendamento.php';

use clinica\dominio\Agendamento;

/**
 * GABARITO - AULA 5: Interface de Repositório para Agendamentos
 */
interface RepositorioAgendamentos {
    public function adicionar(Agendamento $agendamento): void;
    public function obterTodos(): array;
    public function buscarPorId(int $id): ?Agendamento;
    public function cancelarPorId(int $id): bool;
}

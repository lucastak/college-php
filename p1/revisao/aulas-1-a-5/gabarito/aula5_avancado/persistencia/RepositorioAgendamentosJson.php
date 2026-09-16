<?php

namespace clinica\persistencia;

require_once __DIR__ . '/RepositorioAgendamentos.php';
require_once dirname(__DIR__) . '/excecoes/PersistenciaException.php';
require_once dirname(__DIR__) . '/dominio/Agendamento.php';

use clinica\dominio\Agendamento;
use clinica\excecoes\PersistenciaException;

/**
 * GABARITO - AULA 5: Repositório em JSON com tratamento de erros de persistência
 */
class RepositorioAgendamentosJson implements RepositorioAgendamentos {
    private $caminhoArquivo;

    public function __construct(string $caminhoArquivo) {
        $this->caminhoArquivo = $caminhoArquivo;
    }

    public function adicionar(Agendamento $agendamento): void {
        $agendamentos = $this->obterTodos();

        foreach ($agendamentos as $item) {
            if ($item->getId() === $agendamento->getId()) {
                throw new PersistenciaException("Já existe um agendamento com o ID {$agendamento->getId()}.");
            }
        }

        $agendamentos[] = $agendamento;
        $this->salvar($agendamentos);
    }

    /**
     * @return Agendamento[]
     */
    public function obterTodos(): array {
        if (!file_exists($this->caminhoArquivo)) {
            return [];
        }

        $conteudo = @file_get_contents($this->caminhoArquivo);
        if ($conteudo === false) {
            throw new PersistenciaException("Falha ao ler o arquivo de dados '{$this->caminhoArquivo}'.");
        }

        $conteudo = trim($conteudo);
        if ($conteudo === '') {
            return [];
        }

        $dados = json_decode($conteudo, true);
        if (!is_array($dados) && json_last_error() !== JSON_ERROR_NONE) {
            throw new PersistenciaException("Erro na decodificação do arquivo JSON: " . json_last_error_msg());
        }

        $lista = [];
        foreach ($dados as $item) {
            if (is_array($item) && isset($item['id'], $item['paciente'], $item['medico'], $item['dataConsulta'], $item['valor'])) {
                $lista[] = new Agendamento(
                    (int) $item['id'],
                    (string) $item['paciente'],
                    (string) $item['medico'],
                    (string) $item['dataConsulta'],
                    (float) $item['valor']
                );
            }
        }

        return $lista;
    }

    public function buscarPorId(int $id): ?Agendamento {
        $todos = $this->obterTodos();
        foreach ($todos as $agendamento) {
            if ($agendamento->getId() === $id) {
                return $agendamento;
            }
        }
        return null;
    }

    public function cancelarPorId(int $id): bool {
        $todos = $this->obterTodos();
        $encontrado = false;
        $filtrados = [];

        foreach ($todos as $agendamento) {
            if ($agendamento->getId() === $id) {
                $encontrado = true;
            } else {
                $filtrados[] = $agendamento;
            }
        }

        if ($encontrado) {
            $this->salvar($filtrados);
            return true;
        }

        return false;
    }

    /**
     * @param Agendamento[] $agendamentos
     */
    private function salvar(array $agendamentos): void {
        $dadosParaSalvar = [];
        foreach ($agendamentos as $a) {
            $dadosParaSalvar[] = [
                'id' => $a->getId(),
                'paciente' => $a->getPaciente(),
                'medico' => $a->getMedico(),
                'dataConsulta' => $a->getDataConsulta(),
                'valor' => $a->getValor()
            ];
        }

        $json = json_encode($dadosParaSalvar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new PersistenciaException("Erro ao codificar agendamentos para JSON: " . json_last_error_msg());
        }

        $resultado = @file_put_contents($this->caminhoArquivo, $json);
        if ($resultado === false) {
            throw new PersistenciaException("Falha ao gravar os dados no arquivo '{$this->caminhoArquivo}'.");
        }
    }
}

<?php

namespace clinica\dominio;

require_once dirname(__DIR__) . '/excecoes/AgendamentoException.php';
require_once __DIR__ . '/SerializavelJson.php';

use clinica\excecoes\AgendamentoException;

/**
 * GABARITO - AULA 5: Entidade Agendamento com validação de domínio e Trait
 */
class Agendamento {
    use SerializavelJson;

    private $id;
    private $paciente;
    private $medico;
    private $dataConsulta;
    private $valor;

    public function __construct(int $id, string $paciente, string $medico, string $dataConsulta, float $valor) {
        $this->id = $id;
        $this->paciente = trim($paciente);
        $this->medico = trim($medico);
        $this->dataConsulta = trim($dataConsulta);
        $this->valor = $valor;

        $this->validar();
    }

    private function validar(): void {
        if ($this->id <= 0) {
            throw new AgendamentoException('O ID do agendamento deve ser um número positivo.');
        }

        $tamPaciente = mb_strlen($this->paciente, 'UTF-8');
        if ($tamPaciente < 3 || $tamPaciente > 60) {
            throw new AgendamentoException('O nome do paciente deve ter entre 3 e 60 caracteres.');
        }

        $tamMedico = mb_strlen($this->medico, 'UTF-8');
        if ($tamMedico < 3 || $tamMedico > 60) {
            throw new AgendamentoException('O nome do médico deve ter entre 3 e 60 caracteres.');
        }

        // Validação da data via checkdate
        $partesData = explode('/', $this->dataConsulta);
        if (count($partesData) !== 3) {
            throw new AgendamentoException('A data deve estar no formato DD/MM/AAAA.');
        }

        $dia = (int) $partesData[0];
        $mes = (int) $partesData[1];
        $ano = (int) $partesData[2];

        if (!checkdate($mes, $dia, $ano)) {
            throw new AgendamentoException('A data informada para a consulta não existe no calendário.');
        }

        if ($this->valor <= 0.0) {
            throw new AgendamentoException('O valor da consulta deve ser superior a zero.');
        }
    }

    public function getId(): int {
        return $this->id;
    }

    public function getPaciente(): string {
        return $this->paciente;
    }

    public function getMedico(): string {
        return $this->medico;
    }

    public function getDataConsulta(): string {
        return $this->dataConsulta;
    }

    public function getValor(): float {
        return $this->valor;
    }
}

<?php

namespace cefet\persistencia;

require_once __DIR__ . '/RepositorioTarefas.php';
require_once __DIR__ . '/RepositorioException.php';
require_once __DIR__ . '/tarefa.php';

use cefet\Tarefa;

class RepositorioTarefasJson implements RepositorioTarefas {
    private $caminhoArquivo;

    public function __construct($caminhoArquivo = 'tarefas.json') {
        $this->caminhoArquivo = $caminhoArquivo;
    }

    public function getCaminhoArquivo() {
        return $this->caminhoArquivo;
    }

    public function adicionar(Tarefa $tarefa) {
        $tarefas = $this->obterTodas();
        $tarefas[] = $tarefa;
        $this->salvar($tarefas);
    }

    public function obterTodas(): array {
        if (!file_exists($this->caminhoArquivo)) {
            return [];
        }

        $conteudo = @file_get_contents($this->caminhoArquivo);
        if ($conteudo === false) {
            throw new RepositorioException("Erro ao ler o arquivo '{$this->caminhoArquivo}'.");
        }

        $conteudo = trim($conteudo);
        if ($conteudo === '') {
            return [];
        }

        $dados = json_decode($conteudo, true);
        if ($dados === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new RepositorioException("Erro ao decodificar JSON do arquivo '{$this->caminhoArquivo}': " . json_last_error_msg());
        }

        if (!is_array($dados)) {
            throw new RepositorioException("Conteúdo do arquivo '{$this->caminhoArquivo}' não é um array válido.");
        }

        $listaTarefas = [];
        foreach ($dados as $item) {
            if (is_array($item) && isset($item['descricao'])) {
                $feita = isset($item['feita']) ? (bool)$item['feita'] : false;
                $listaTarefas[] = new Tarefa($item['descricao'], $feita);
            }
        }

        return $listaTarefas;
    }

    private function salvar(array $tarefas) {
        $dadosParaSalvar = [];
        foreach ($tarefas as $tarefa) {
            $dadosParaSalvar[] = [
                'descricao' => $tarefa->getDescricao(),
                'feita' => $tarefa->getFeita()
            ];
        }

        $json = json_encode($dadosParaSalvar, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RepositorioException("Erro ao codificar dados para JSON: " . json_last_error_msg());
        }

        $resultado = @file_put_contents($this->caminhoArquivo, $json);
        if ($resultado === false) {
            throw new RepositorioException("Erro ao gravar dados no arquivo '{$this->caminhoArquivo}'.");
        }
    }
}

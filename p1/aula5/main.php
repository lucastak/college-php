<?php

require_once __DIR__ . '/tarefa.php';
require_once __DIR__ . '/TarefaException.php';
require_once __DIR__ . '/ConversivelParaJson.php';
require_once __DIR__ . '/RepositorioTarefas.php';
require_once __DIR__ . '/RepositorioException.php';
require_once __DIR__ . '/RepositorioTarefasJson.php';

use cefet\Tarefa;
use cefet\excecoes\TarefaException;
use cefet\persistencia\RepositorioTarefas;
use cefet\persistencia\RepositorioTarefasJson;
use cefet\persistencia\RepositorioException;

echo "=== TESTE EXERCÍCIO 1: Tarefa e Validação com TarefaException ===\n";
try {
    $tarefa1 = new Tarefa('Estudar PHP', false);
    echo "Tarefa criada com sucesso: " . $tarefa1->getDescricao() . " (Feita: " . ($tarefa1->getFeita() ? 'Sim' : 'Não') . ")\n";
} catch (TarefaException $e) {
    echo "Erro inesperado: " . $e->getMessage() . "\n";
}

try {
    echo "Tentando criar tarefa com descrição muito curta ('ab')...\n";
    $tarefaInvalida = new Tarefa('ab');
} catch (TarefaException $e) {
    echo "Exceção capturada com sucesso: " . $e->getMessage() . "\n";
}

try {
    echo "Tentando criar tarefa com mais de 50 caracteres...\n";
    $tarefaInvalida = new Tarefa(str_repeat('a', 51));
} catch (TarefaException $e) {
    echo "Exceção capturada com sucesso: " . $e->getMessage() . "\n";
}

echo "\n=== TESTE EXERCÍCIO 2: Trait ConversivelParaJson ===\n";
$tarefaJson = new Tarefa('Fazer trabalho de PHP', true);
echo "JSON gerado pelo método toJson():\n";
echo $tarefaJson->toJson() . "\n";

echo "\n=== TESTE EXERCÍCIOS 3 e 4: Persistência com RepositorioTarefasJson ===\n";
$arquivoTarefas = __DIR__ . '/tarefas.json';

// Limpa arquivo para iniciar o teste do zero
if (file_exists($arquivoTarefas)) {
    unlink($arquivoTarefas);
}

try {
    $repositorio = new RepositorioTarefasJson($arquivoTarefas);

    $t1 = new Tarefa('Estudar para a prova de PHP', false);
    $t2 = new Tarefa('Finalizar exercícios da Aula 5', true);
    $t3 = new Tarefa('Revisar orientação a objetos', false);

    echo "Adicionando tarefas ao repositório...\n";
    $repositorio->adicionar($t1);
    $repositorio->adicionar($t2);
    $repositorio->adicionar($t3);

    echo "Tarefas adicionadas com sucesso no arquivo {$arquivoTarefas}!\n\n";

    echo "Lendo todas as tarefas do repositório:\n";
    $todas = $repositorio->obterTodas();
    foreach ($todas as $indice => $tarefa) {
        $status = $tarefa->getFeita() ? '[Concluída]' : '[Pendente]';
        echo ($indice + 1) . ". {$status} " . $tarefa->getDescricao() . "\n";
    }

    echo "\nConteúdo bruto do arquivo JSON:\n";
    echo file_get_contents($arquivoTarefas) . "\n";

} catch (RepositorioException $e) {
    echo "Erro no repositório: " . $e->getMessage() . "\n";
} catch (TarefaException $e) {
    echo "Erro na tarefa: " . $e->getMessage() . "\n";
}

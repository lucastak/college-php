<?php
// q1_pereciveis.php
// Questão 1 - 2023/1

$hoje = "05/05/2023";
list($diaHoje, $mesHoje, $anoHoje) = explode('/', $hoje);
$timeHoje = mktime(0, 0, 0, (int)$mesHoje, (int)$diaHoje, (int)$anoHoje);

$arquivo = 'pereciveis.csv';

if (file_exists($arquivo)) {
    $linhas = explode(PHP_EOL, file_get_contents($arquivo));
    $primeiraLinha = true;

    foreach ($linhas as $linha) {
        $linha = trim($linha);
        if (empty($linha)) continue;

        if ($primeiraLinha) {
            $primeiraLinha = false;
            continue; // Pular cabeçalho
        }

        $dados = explode(';', $linha);
        if (count($dados) >= 2) {
            $descricao = trim($dados[0]);
            $validade = trim($dados[1]);

            list($diaVal, $mesVal, $anoVal) = explode('/', $validade);
            $timeValidade = mktime(0, 0, 0, (int)$mesVal, (int)$diaVal, (int)$anoVal);

            if ($timeValidade < $timeHoje) {
                $diferencaSegundos = $timeHoje - $timeValidade;
                $diasVencido = floor($diferencaSegundos / (60 * 60 * 24));

                echo "Produto vencido: {$descricao} | Vencido há {$diasVencido} dia(s)\n";
            }
        }
    }
} else {
    echo "Para testar, crie um arquivo pereciveis.csv com os dados do enunciado.\n";
}

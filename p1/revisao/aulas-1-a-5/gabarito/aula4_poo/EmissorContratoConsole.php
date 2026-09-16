<?php

require_once __DIR__ . '/NotificadorLocacao.php';
require_once __DIR__ . '/ContratoLocacao.php';

/**
 * GABARITO - AULA 4: Implementação de NotificadorLocacao no Console
 */
class EmissorContratoConsole implements NotificadorLocacao {
    public function notificar(ContratoLocacao $contrato): void {
        echo "========================================\n";
        echo "      TERMO DE LOCACAO DE VEICULOS      \n";
        echo "========================================\n";
        echo "Cliente: " . $contrato->getCliente() . "\n";
        echo "Status: " . ($contrato->isFechado() ? "CONTRATO FECHADO" : "EM ABERTO") . "\n";
        echo "----------------------------------------\n";

        $itens = $contrato->getItens();
        foreach ($itens as $i => $item) {
            $v = $item->veiculo();
            $num = $i + 1;
            $diaria = number_format($v->getDiaria(), 2, ',', '.');
            $seguro = number_format($item->taxaSeguro(), 2, ',', '.');
            $sub = number_format($item->subtotal(), 2, ',', '.');

            echo "{$num}. {$v->getModelo()} [{$v->getCategoria()}]\n";
            echo "   {$item->dias()} dias x (Diária R$ {$diaria} + Seguro R$ {$seguro}) = R$ {$sub}\n";
        }

        echo "----------------------------------------\n";
        $subtotal = number_format($contrato->subtotal(), 2, ',', '.');
        echo "Subtotal: R$ {$subtotal}\n";

        $desc = $contrato->getDescontoPercentual();
        if ($desc > 0) {
            $valorDesc = $contrato->subtotal() * ($desc / 100.0);
            $valorDescFmt = number_format($valorDesc, 2, ',', '.');
            echo "Desconto Fidelidade ({$desc}%): - R$ {$valorDescFmt}\n";
        }

        $total = number_format($contrato->total(), 2, ',', '.');
        echo "VALOR TOTAL CONTRATADO: R$ {$total}\n";
        echo "========================================\n";
    }
}

<?php

class ImpressoraVendaConsole implements ImpressoraVenda {
    public function imprimir(Venda $v) {
        echo "Subtotal: " . $v->geItens() . "\n";
        echo "Desconto: " . $v->getDesconto() . "\n";
        echo "Total: " . $v->getTotal() . "\n";
    }
}
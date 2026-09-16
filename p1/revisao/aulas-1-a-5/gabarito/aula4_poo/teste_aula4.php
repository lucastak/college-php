<?php

/**
 * GABARITO - AULA 4: Testes Automatizados da Locadora de Veículos
 */

require_once __DIR__ . '/Veiculo.php';
require_once __DIR__ . '/ItemLocacao.php';
require_once __DIR__ . '/ContratoLocacao.php';
require_once __DIR__ . '/NotificadorLocacao.php';
require_once __DIR__ . '/EmissorContratoConsole.php';

echo "=== TESTE AULA 4 (INÉDITO): Sistema de Locadora de Veículos ===\n\n";

$v1 = new Veiculo(101, "Chevrolet Onix 1.0", "Econômico", 90.00);
$v2 = new Veiculo(102, "Jeep Renegade Turbo", "SUV", 180.00);
$v3 = new Veiculo(103, "Toyota Corolla Híbrido", "Sedan", 210.00);

echo "Veículos disponíveis no pátio:\n";
echo "- {$v1->getModelo()}: R$ {$v1->getDiaria()}/dia\n";
echo "- {$v2->getModelo()}: R$ {$v2->getDiaria()}/dia\n";
echo "- {$v3->getModelo()}: R$ {$v3->getDiaria()}/dia\n\n";

$contrato = new ContratoLocacao("Mariana Silveira");

// Adiciona primeiro veículo por 3 dias com seguro de R$ 20/dia (subtotal: (90+20)*3 = 330)
$adicionouV1 = $contrato->adicionarItem($v1, 3, 20.00);
echo "Adicionou Onix: " . ($adicionouV1 ? "OK" : "FALHA") . "\n";
echo "Onix agora está disponível? " . (!$v1->isDisponivel() ? "Não (Reservado com sucesso - OK)" : "FALHA") . "\n";

// Adiciona segundo veículo por 5 dias com seguro padrão de R$ 20/dia (subtotal: (180+20)*5 = 1000)
$adicionouV2 = $contrato->adicionarItem($v2, 5);
echo "Adicionou Renegade: " . ($adicionouV2 ? "OK" : "FALHA") . "\n";

// Tenta adicionar Onix novamente (deve falhar pois já está locado)
$tentouLocarMesmo = $contrato->adicionarItem($v1, 2);
echo "Tentativa de alugar Onix já reservado: " . (!$tentouLocarMesmo ? "Bloqueado com sucesso (OK)" : "FALHA") . "\n";

echo "Subtotal parcial: R$ " . number_format($contrato->subtotal(), 2, ',', '.') . "\n"; // 330 + 1000 = 1330

// Concede 10% de desconto fidelidade
$contrato->concederDesconto(10.0);
echo "Total com 10% desconto: R$ " . number_format($contrato->total(), 2, ',', '.') . "\n\n"; // 1330 - 133 = 1197

// Fechamento do contrato emitindo recibo pelo console
$emissor = new EmissorContratoConsole();
$contrato->fecharContrato($emissor);

// Validação de bloqueio pós-fechamento
$tentouInserirAposFechado = $contrato->adicionarItem($v3, 1);
echo "\nTentativa de adicionar Corolla após contrato fechado: " . (!$tentouInserirAposFechado ? "Bloqueado com sucesso (OK)" : "FALHA") . "\n";

$tentouRemoverAposFechado = $contrato->removerItem(0);
echo "Tentativa de remover item após contrato fechado: " . (!$tentouRemoverAposFechado ? "Bloqueado com sucesso (OK)" : "FALHA") . "\n";

echo "\nAula 4 inédita validada com sucesso!\n";

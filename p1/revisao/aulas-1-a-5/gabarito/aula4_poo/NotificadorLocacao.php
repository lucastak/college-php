<?php

/**
 * GABARITO - AULA 4: Interface NotificadorLocacao
 */
interface NotificadorLocacao {
    public function notificar(ContratoLocacao $contrato): void;
}

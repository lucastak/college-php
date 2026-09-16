<?php

namespace clinica\dominio;

/**
 * GABARITO - AULA 5: Trait para serialização automática de atributos em JSON
 */
trait SerializavelJson {
    public function toJson(): string {
        return (string) json_encode(get_object_vars($this), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

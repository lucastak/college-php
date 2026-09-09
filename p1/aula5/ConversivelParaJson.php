<?php

namespace cefet;

trait ConversivelParaJson {
    public function toJson() {
        return json_encode(get_object_vars($this), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

<?php
namespace vac;

interface RepositorioVacina {
    public function vacinas();
    public function vacinaComId($id);
    public function atualizarVacima(Vacina $vacina);
}

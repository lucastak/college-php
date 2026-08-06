<?php
namespace vac;

class Vacina {
    private $id;
    private $nome;
    private $fabricante;
    private $doses;
    private $eficacia;
    private $eficaciaDelta;
    private $perdaMensal;

    public function __construct(
        $id = 0,
        $nome = '',
        $fabricante = '',
        $doses = 0,
        $eficacia = 0,
        $eficaciaDelta = 0,
        $perdaMensal = 0
    ) {
        $this->setId( $id );
        $this->setNome( $nome );
        $this->setFabricante( $fabricante );
        $this->setDoses( $doses );
        $this->setEficacia( $eficacia );
        $this->setEficaciaDelta( $eficaciaDelta );
        $this->setPerdaMensal( $perdaMensal );
    }

    public function eficaciaAposMeses(int $meses, bool $consideraDelta = false) {
        if ($meses < 0) {
            $meses = 0;
        }
        
        $baseEficacia = $consideraDelta ? $this->getEficaciaDelta() : $this->getEficacia();
        $eficaciaCalculada = $baseEficacia - ($meses * $this->getPerdaMensal());
        
        if ($eficaciaCalculada < 0) {
            return 0.0;
        }
        
        return $eficaciaCalculada;
    }

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }
    
    public function getNome() { return $this->nome; }
    public function setNome($nome) { $this->nome = $nome; }
    
    public function getFabricante() { return $this->fabricante; }
    public function setFabricante($fabricante) { $this->fabricante = $fabricante; }
    
    public function getDoses() { return $this->doses; }
    public function setDoses($doses) { $this->doses = $doses; }
    
    public function getEficacia() { return $this->eficacia; }
    public function setEficacia($eficacia) { $this->eficacia = $eficacia; }
    
    public function getEficaciaDelta() { return $this->eficaciaDelta; }
    public function setEficaciaDelta($eficaciaDelta) { $this->eficaciaDelta = $eficaciaDelta; }
    
    public function getPerdaMensal() { return $this->perdaMensal; }
    public function setPerdaMensal($perdaMensal) { $this->perdaMensal = $perdaMensal; }
}

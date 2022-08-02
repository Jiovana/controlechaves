<?php 

class ModelHook{
    private $id;
    private $codigo;
    private $tipo;
    private $usado;
    
    private function __clone(){}
    
    public function __construct(){}
    
    ////////////////////////////
    public function setId($id){ $this->id = $id; }
    public function getId(){ return $this->id; }
    
     ////////////////////////////
    public function setCodigo($codigo){ $this->codigo = $codigo; }
    public function getCodigo(){ return $this->codigo; }
    
     ////////////////////////////
    public function setTipo($tipo){ $this->tipo = $tipo; }
    public function getTipo(){ return $this->tipo; }
    
     ////////////////////////////
    public function setUsado($usado){ $this->usado = $usado; }
    public function getUsado(){ return $this->usado; }
    
    
    
    
}


?>
<?php 


/**
* 
* Representa a entidade hook (gancho) do banco de dados, possuindo todos seus atributos
*
*/
class ModelHook{
    private $id;
    private $codigo; // o codigo fisico do gancho no painel
    private $tipo; // se eh aluguel ou venda
    private $usado; // usado = 1 significa que tem uma ou mais chaves associadas a esse gancho
    
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
<?php 

class ModelAddress{
    
    private $id;
    private $numero;
    private $bairro;
    private $cidade;
    private $rua;
    private $complemento;
    
    private function __clone(){}
    
    public function __construct($id, $numero, $bairro, $cidade, $rua, $complemento){
        $this->id = $id;
        $this->numero = $numero;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->rua = $rua;
        $this->complemento = $complemento;
    }
    
    public function setId($id){ $this->id = $id; }
    public function getId(){ return $this->id; }
    
    public function setNumero($numero){ $this->numero = $numero; }
    public function getNumero(){ return $this->numero; }
    
    public function setBairro($bairro){ $this->bairro = $bairro; }
    public function getBairro(){ return $this->bairro; }
    
    public function setCidade($cidade){ $this->cidade = $cidade; }
    public function getCidade(){ return $this->cidade; }
    
    public function setRua($rua){ $this->rua = $rua; }
    public function getRua(){ return $this->rua; }
    
    public function setComplemento($complemento){ $this->complemento = $complemento; }
    public function getComplemento(){ return $this->complemento; }
    
    
    
}





?>
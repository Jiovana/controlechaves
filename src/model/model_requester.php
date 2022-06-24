<?php 

class ModelRequester{
    
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $ddd;
    private $documento;
    private $tipo;
    
    private function __clone(){}
    
    public function __construct(){
    }
    
    public function setId($id){ $this->id = $id; }
    public function getId(){ return $this->id; }
    
    public function setNome($nome){ $this->nome = $nome; }
    public function getNome(){ return $this->nome; }
    
    public function setEmail($email){ $this->email = $email; }
    public function getEmail(){ return $this->email; }
    
    public function setTelefone($telefone){ $this->telefone = $telefone; }
    public function getTelefone(){ return $this->telefone; }
    
    public function setDdd($ddd){ $this->ddd = $ddd; }
    public function getDdd(){ return $this->ddd; }
    
    public function setDocumento($documento){ $this->documento = $documento; }
    public function getDocumento(){ return $this->documento; }
    
    public function setTipo($tipo){ $this->tipo = $tipo; }
    public function getTipo(){ return $this->tipo; }
    
}

?>
<?php 

class ModelKey{
    
    private $id; // gerado do banco
    private $data_in; // data de criacao do objeto
    private $gancho; // codigo do gancho fisico da chave
    private $sicadi; // codigo do sicadi do imovel
    private $tipo; // aluguel ou venda
    private $status; // disponivel, emprestada, perdida, pendente, indisponivel (venda ou alugado)
    private $adicional; // info adicional sobre chave ou imovel
    private $endereco_id; // id da tabela endereco da chave
    
    private function __clone(){}
    
    public function __construct($id, $data_in, $gancho, $sicadi, $tipo, $status, $adicional, $endereco_id){
        $this->id = $id;
        $this->data_in = $data_in;
        $this->gancho = $gancho;
        $this->sicadi = $sicadi;
        $this->tipo = $tipo;
        $this->status = $status;
        $this->adicional = $adicional;
        $this->endereco_id = $endereco_id;
    }
    
    public function setId($id){ $this->id = $id; }
    public function getId(){ return $this->id; }
    
    public function setDataIn($data_in){ $this->data_in = $data_in; }
    public function getDataIn(){ return $this->data_in; }
    
    public function setGancho($gancho){ $this->gancho = $gancho; }
    public function getGancho(){ return $this->gancho; }
    
    public function setSicadi($sicadi){ $this->sicadi = $sicadi; }
    public function getSicadi(){ return $this->sicadi; }
    
    public function setTipo($tipo){ $this->tipo = $tipo; }
    public function getTipo(){ return $this->tipo; }
    
    public function setStatus($status){ $this->status = $status; }
    public function getStatus(){ return $this->status; }
    
    public function setAdicional($adicional){ $this->adicional = $adicional; }
    public function getAdicional(){ return $this->adicional; }
    
    public function setEnderecoId($endereco_id){ $this->endereco_id = $endereco_id; }
    public function getEnderecoId(){ return $this->endereco_id; }
    
}


?>
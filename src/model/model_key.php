<?php 

/**
* @Entity
* @Table(name = "user")
*/
class ModelKey{
    
    /**
    * @Id @Column(type="integer")
    * @GeneratedValue
    */
    private $id; // gerado do banco
    /**
    * @Column(type="date")
    */
    private $data_in; // data de criacao do objeto
    /**
    * @Column(type="string", length=10)
    */
    private $gancho; // codigo do gancho fisico da chave
     /**
    * @Column(type="string", length=20)
    */
    private $sicadi; // codigo do sicadi do imovel
    
    private $tipo; // aluguel ou venda
    
    private $status; // disponivel, emprestada, perdida, pendente, indisponivel (venda ou alugado)
    
    private $adicional; // info adicional sobre chave ou imovel
    /**
    * @OneToMany (targetEntity="address", mappedBy="user")
    */
    private $endereco_id; // id da tabela endereco da chave vetor
    
    private function __clone(){}
    
    public function __construct(){    }
    
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
    
    public function setEnderecoId(ModelAddress $endereco){ 
        $this->endereco_id = $endereco; 
    }
    public function getEnderecoId(){ return $this->endereco_id; }
    
}


?>
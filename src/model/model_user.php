<?php 
/**
 * Simula entidade user do banco de dados, possuindo todos seus atributos.
 * 
 * Comunicacao com getters e setters
*/
class ModelUser {
    
    //propriedades do usuario
    private $id = 0; //id do banco com autoincremento
    private $data_in = ""; // banco salva data atual
    private $nome = ""; // primeiro nome do usuario
    private $sobrenome = ""; // sobrenome do usuario
    private $senha = ""; //senha (precisa ser codificada com md5)
    private $email = ""; //email de acesso ao sistema

    //metodos padrao
    private function __clone(){}   
    public function __construct(){}
    
    //getters e setters
    public function getId(){ return $this->id; } 
    public function setId ($id){ $this->id = $id; }
    ///////////////////////////////
    public function getData_in(){ 
        $date = date_create($this->data_in); 
        return date_format($date,'d/m/Y'); 
    } 
    public function setData_in ($data_in){ $this->data_in = $data_in; }
     ///////////////////////////////
    public function setNome ($nome){ $this->nome = $nome; }  
    public function getNome(){ return $this->nome; }
     ///////////////////////////////
    public function setSobrenome ($nome){ $this->sobrenome = $nome; }  
    public function getSobrenome(){ return $this->sobrenome; }
     ///////////////////////////////
    public function setSenha ($senha){ $this->senha = $senha; }
    public function getSenha(){ return $this->senha; }
    ///////////////////////////////
    public function setEmail ($email){ $this->email = $email; }
    public function getEmail(){ return $this->email; }
}

?>
<?php 
/**
* Simula entidade borrowing do banco de dados, possuindo todos seus atributos.
*
* Comunicacao com getters e setters
*/
class ModelBorrowing{
    
    private $id;
    private $data_checkin; // data de devolucao chaves
    private $data_checkout; // data de retirada chaves
    private $requester_id; // id do requerente
    private $user_id; // id do usuario do sistema
    
    private function __clone(){}
    
    public function __construct(){    }
    
    //getters e setters
    public function setId($id){ 
        $this->id = $id; 
    }
    public function getId(){ 
        return $this->id; 
    }
    
    public function setData_checkin($data_checkin){
        $this->data_checkin = $data_checkin;
    }
    public function getData_checkin(){
        return $this->data_checkin; 
    }
    
    public function setData_checkout($data_checkout){
        $this->data_checkout = $data_checkout;
    }
    public function getData_checkout(){
       return $this->data_checkout;
    }
    
    public function setRequester_id($requester_id){
        $this->requester_id = $requester_id;
    }
    public function getRequester_id(){
        return $this->requester_id;
    }
    
    public function setUser_id($user_id){
        $this->user_id = $user_id;
    }
    public function getUser_id(){
        return $this->user_id;
    }
    
    public function setChave(ModelKey $chave){
        $this->chaves[] = $chave;
    }
    public function getChave(){
        return $this->chaves;
    }
}

?>
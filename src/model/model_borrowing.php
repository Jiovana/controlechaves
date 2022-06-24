<?php 

class ModelBorrowing{
    
    private $id;
    private $data_checkin;
    private $data_checkout;
    private $requester_id;
    private $user_id;
    
    /**
    * @ManyToMany(targetEntity="keys", inversedBy="borrowing")
    * @JoinTable(name = "keys_borrowing,
    *           joinColumns={@JoinColumn(name="key_id, referencedColumnName="id")}, 
                inversedJoinColumns={@JoinColumn(name="borrowing_id", referencedColumnName="id")}
    *           )
    */
    private $chaves = array(); // ids das chaves
    // relacionamento nxn
    
    private function __clone(){}
    
    public function __construct(){    }
    
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
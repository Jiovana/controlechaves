<?php 

class Log{
    
    private $id;
    private $description;
    private $date;
    private $keys_id;
    private $user_id;
    
    public function setId($id){
        $this->id = $id;
    }
    public function getId(){
        return $this->id;
    }
    
    public function setDescription($description){
        $this->description = $description;
    }
    public function getDescription(){
        return $this->description;
    }
    
    public function setDate($date){
        $this->$date = $date;
    }
    public function getData(){
        return $this->data;
    }
    
    public function setKeys_id($keys_id){
        $this->keys_id = $keys_id;
    }
    public function getKeys_id(){
        return $this->keys_id;
    }
    
    public function setUser_id($user_id){
        $this->user_id = $user_id;
    }
    public function getUser_id(){
        return $this->user_id;
    }
    
}

?>
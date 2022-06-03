<?php 

require_once "../model/model_key.php";

require_once "../control/connection.php";

class DaoKey{
    public static $instance;
    
    public function __construct(){
        //
    }
    
    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new DaoKey();
        
        return self::$instance;
    }
    
    public function Insert(ModelKey $key){
        try{
            $sql = "INSERT INTO key (gancho, sicadi, tipo, status, adicional,endereço_id) VALUES (:gancho,:sicadi,:tipo,:status,:adicional,:endereço_id)";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":gancho", $key->getGancho());
            $p_sql->bindValue(":sicadi", $key->getSicadi());
            $p_sql->bindValue(":tipo", $key->getTipo());
            $p_sql->bindValue(":status", $key->getStatus());
            $p_sql->bindValue(":adicional", $key->getAdicional());
            $p_sql->bindValue(":endereço_id", $key->getEnderecoId());
            
            return $p_sql->execute();
                        
        }catch(Exception $e){
            echo ("Error while running Insert method in DaoKey.");
        }
    }
    
    public function Update(ModelKey $key){
        try{
            $sql = "UPDATE key SET gancho = :gancho, sicadi = :sicadi, tipo = :tipo, status = :status, adicional = :adicional, endereço_id = :endereço_id WHERE id = :keyid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":gancho", $key->getGancho());
            $p_sql->bindValue(":sicadi", $key->getSicadi());
            $p_sql->bindValue(":tipo", $key->getTipo());
            $p_sql->bindValue(":status", $key->getStatus());
            $p_sql->bindValue(":adicional", $key->getAdicional());
            $p_sql->bindValue(":endereço_id", $key->getEnderecoId());
            $p_sql->bindValue(":keyid", $key->getId);
            
            return $p_sql->execute();
            
        }catch(Exception $e){
            echo ("Error while running Update method in DaoKey.");
        }
    }
    
    public function Delete(ModelUser $user){
        try{
            $sql = "DELETE FROM key WHERE id = :keyid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":keyid", $key->getId());
            
            return $p_sql->execute();           
        }catch(Exception $e){
            echo ("Error while running Delete method in DaoKey.");
        }
    }
    
    public function SearchById($id){
        try{
            $sql = "SELECT * FROM key WHERE id = :keyid";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->bindValue(":keyid", $id);         
            $p_sql->execute();
            
            return $this->FillKey($p_sql->fetch(PDO::FETCH_ASSOC));
        }catch(Exception $e){
             echo ("Error while running SearchById method in DaoKey.");
        }
    }
    
    public function SearchAll(){
        try{
            $sql = "SELECT * FROM key ORDER BY id";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->execute();
            
            return $p_sql->fetchAll();
        }catch(Exception $e){
            echo ("Error while running SearchAll method in DaoKey.");
        }
    }
    
    private function FillKey($singlekey){
        $key = new ModelKey();
        $key->setId = $singlekey['id'];
        $key->setGancho = $singlekey['gancho'];
        $key->setSicadi = $singlekey['sicadi'];
        $key->setTipo = $singlekey['tipo'];
        $key->setStatus = $singlekey['status'];
        $key->setDataIn = $singlekey['data_in'];
        $key->setAdicional = $singlekey['adicional'];
        $key->setEnderecoId = $singlekey['endereço_id'];
        return $key;
    } 




?>
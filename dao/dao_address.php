<?php 

require_once "../model/model_address.php";

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
    
    public function Insert(ModelAddress $address){
        try{
            $sql = "INSERT INTO address (numero, bairro, cidade, rua, complemento) VALUES (:numero,:bairro,:cidade,:rua,:complemento)";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":numero", $address->getNumero());
            $p_sql->bindValue(":bairro", $address->getBairro());
            $p_sql->bindValue(":cidade", $address->getCidade());
            $p_sql->bindValue(":rua", $address->getRua());
            $p_sql->bindValue(":complemento", $address->getComplemento());
            
            return $p_sql->execute();
                        
        }catch(Exception $e){
            echo ("Error while running Insert method in DaoAddress.");
        }
    }
    
    public function Update(ModelAddress $address){
        try{
            $sql = "UPDATE address SET numero = :numero, bairro = :bairro, cidade = :cidade, rua = :rua, complemento = :complemento WHERE id = :addrid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":numero", $address->getNumero());
            $p_sql->bindValue(":bairro", $address->getBairro());
            $p_sql->bindValue(":cidade", $address->getCidade());
            $p_sql->bindValue(":rua", $address->getRua());
            $p_sql->bindValue(":complemento", $address->getComplemento());
            $p_sql->bindValue(":addrid", $address->getId);
            
            return $p_sql->execute();
            
        }catch(Exception $e){
            echo ("Error while running Update method in DaoAddress.");
        }
    }
    
    public function Delete(ModelAddress $address){
        try{
            $sql = "DELETE FROM address WHERE id = :addrid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":addrid", $address->getId());
            
            return $p_sql->execute();           
        }catch(Exception $e){
            echo ("Error while running Delete method in DaoAddress.");
        }
    }
    
    public function SearchById($id){
        try{
            $sql = "SELECT * FROM address WHERE id = :addrid";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->bindValue(":addrid", $id);         
            $p_sql->execute();
            
            return $this->FillAddress($p_sql->fetch(PDO::FETCH_ASSOC));
        }catch(Exception $e){
             echo ("Error while running SearchById method in DaoAddress.");
        }
    }
    
    public function SearchAll(){
        try{
            $sql = "SELECT * FROM address ORDER BY id";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->execute();
            
            return $p_sql->fetchAll();
        }catch(Exception $e){
            echo ("Error while running SearchAll method in DaoAddress.");
        }
    }
    
    private function FillAddress($singleaddress){
        $key = new ModelAdress($singleaddress['id'], $singleaddress['numero'], $singleaddress['bairro'], $singleaddress['cidade'], $singleaddress['rua'], $singleaddress['complemento']);
        
        return $address;
    } 




?>
<?php 

require_once "../model/model_log.php";

require_once "../control/connection.php";

class DaoLog{
    public static $instance;
    
    public function __construct(){
        //
    }
    
    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new DaoLog();
        
        return self::$instance;
    }
    
    public function Insert(ModelLog $log){
        try{
            $sql = "INSERT INTO log (description, keys_id, user_id) VALUES (:desc,:keys_id,:user_id)";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":desc", $log->getDescription());
            $p_sql->bindValue(":keys_id", $log->getKeys_id());
            $p_sql->bindValue(":user_id", $log->getUser_id());
            
            return $p_sql->execute();
                        
        }catch(Exception $e){
            echo ("Error while running Insert method in DaoLog.");
        }
    }
    
    public function Update(ModelLog $log){
        try{
            $sql = "UPDATE log SET description = :description, keys_id = :keys_id, user_id = :user_id WHERE id = :logid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":description", $log->getDescription());
            $p_sql->bindValue(":keys_id", $log->getKeys_id());
            $p_sql->bindValue(":user_id", $log->getUser_id());
            $p_sql->bindValue(":logid", $log->getId);
            
            return $p_sql->execute();
            
        }catch(Exception $e){
            echo ("Error while running Update method in DaoLog.");
        }
    }
    
    public function Delete(ModelLog $log){
        try{
            $sql = "DELETE FROM log WHERE id = :logid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":logid", $log->getId());
            
            return $p_sql->execute();           
        }catch(Exception $e){
            echo ("Error while running Delete method in DaoLog.");
        }
    }
    
    public function SearchById($id){
        try{
            $sql = "SELECT * FROM log WHERE id = :logid";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->bindValue(":logid", $id);         
            $p_sql->execute();
            
            return $this->FillLog($p_sql->fetch(PDO::FETCH_ASSOC));
        }catch(Exception $e){
             echo ("Error while running SearchById method in DaoLog.");
        }
    }
    
    public function SearchAll(){
        try{
            $sql = "SELECT * FROM log ORDER BY id";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->execute();
            
            return $p_sql->fetchAll();
        }catch(Exception $e){
            echo ("Error while running SearchAll method in DaoLog.");
        }
    }
    
    private function FillLog($singlelog){
        $log = new ModelLog($singlelog['id'], $singlelog['description'], $singlelog['bairro']);
        
        return $address;
    } 




?>
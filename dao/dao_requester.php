<?php

require_once "../model/model_requester.php";

require_once "../control/connection.php";

class DaoRequester{
    public static $instance;
    
    public function __construct(){
        //
    }
    
    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new DaoRequester();
        
        return self::$instance;
    }
    
    public function Insert(ModelRequester $requester){
        try{
            $sql = "INSERT INTO requester (nome, telefone, ddd, email, documento, tipo) VALUES (:nome,:telefone, :ddd, :email, :documento, :tipo)";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":nome", $requester->getNome());
            $p_sql->bindValue(":telefone", $requester->getTelefone());
            $p_sql->bindValue(":ddd", $requester->getDdd());
            $p_sql->bindValue(":email", $requester->getEmail());
            $p_sql->bindValue(":documento", $requester->getDocumento());
            $p_sql->bindValue(":tipo", $requester->getTipo());
            
            return $p_sql->execute();
                        
        }catch(Exception $e){
            echo ("Error while running Insert method in DaoRequester.");
        }
    }
    
    public function Update(ModelRequester $requester){
        try{
            $sql = "UPDATE requester SET nome = :nome, telefone = :telefone, ddd = :ddd, email = :email, documento = :documento, tipo = :tipo WHERE id =: reqid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":nome", $user->getNome());
            $p_sql->bindValue(":telefone", $requester->getTelefone());
            $p_sql->bindValue(":ddd", $requester->getDdd());
            $p_sql->bindValue(":email", $requester->getEmail());
            $p_sql->bindValue(":documento", $requester->getDocumento());
            $p_sql->bindValue(":tipo", $requester->getTipo());
            $p_sql->bindValue(":reqid", $requester->getId());
            
            return $p_sql->execute();
        }catch(Exception $e){
            echo ("Error while running Update method in DaoRequester.");
        }
    }
    
    public function Delete(ModelRequester $requester){
        try{
            $sql = "DELETE FROM requester WHERE id = :reqid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":reqid", $requester->getId());
            
            return $p_sql->execute();           
        }catch(Exception $e){
            echo ("Error while running Delete method in DaoRequester.");
        }
    }
    
    public function SearchById($id){
        try{
            $sql = "SELECT * FROM requester WHERE id = :reqid";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->bindValue(":reqid", $id);         
            $p_sql->execute();
            
            return $this->FillRequester($p_sql->fetch(PDO::FETCH_ASSOC));
        }catch(Exception $e){
             echo ("Error while running SearchById method in DaoRequester.");
        }
    }
    
    public function SearchAll(){
        try{
            $sql = "SELECT * FROM requester ORDER BY id";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->execute();
            
            return $p_sql->fetchAll();
        }catch(Exception $e){
            echo ("Error while running SearchAll method in DaoRequester.");
        }
    }
    
    private function FillRequester($singlereq){
        $requester = new ModelRequester($singlereq['id'], $singlereq['nome'], $singlereq['email'], $singlereq['telefone'], $singlereq['ddd'], $singlereq['documento'], $singlereq['tipo']);
        
        return $requester;
    } 
}

?>
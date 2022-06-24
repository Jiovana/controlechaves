<?php

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_requester.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

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
            
            $p_sql = Connection::getConnection()->prepare($sql);
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
            
            $p_sql = Connection::getConnection()->prepare($sql);
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
    
    public function Delete($id){
        try{
            $sql = "DELETE FROM requester WHERE id = :reqid";
            
            $p_sql = Connection::getConnection()->prepare($sql);
            $p_sql->bindValue(":reqid", $id);
            
            return $p_sql->execute();           
        }catch(Exception $e){
            echo ("Error while running Delete method in DaoRequester.");
        }
    }
    
    public function SearchById($id){
        try{
            $sql = "SELECT * FROM requester WHERE id = :reqid";
            
            $p_sql = Connection::getConnection()->prepare($sql);     
            $p_sql->bindValue(":reqid", $id);         
            $p_sql->execute();
            
            $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelRequester' );
            return $p_sql->fetch();
        }catch(Exception $e){
             echo ("Error while running SearchById method in DaoRequester.");
        }
    }
    
    public function SearchAll(){
        try{
            $sql = "SELECT * FROM requester ORDER BY id";
            
            $p_sql = Connection::getConnection()->prepare($sql);     
            $p_sql->execute();
            
            return $p_sql->fetchAll(PDO::FETCH_CLASS, "ModelRequester");
        }catch(Exception $e){
            echo ("Error while running SearchAll method in DaoRequester.");
        }
    }
    
    /**
     * Busca o ultimo id inserido no banco
     * 
     * 
     * @return ModelKey.id id da ultima chave inserida
    */
    public function SearchIdLimit1() {
        try {
            $sql = "SELECT id FROM `requester` ORDER BY id DESC LIMIT 1";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();

            $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelRequester' );

            return $p_sql->fetch();
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAllLimit1 method in DaoRequester: ".$e->getMessage();
        }
    }
    
    
}

?>
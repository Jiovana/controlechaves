<?php

require_once "../model/model_user.php";

require_once "../control/connection.php";

class DaoUser{
    public static $instance;
    
    public function __construct(){
        //
    }
    
    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new DaoUser();
        
        return self::$instance;
    }
    
    public function Insert(ModelUser $user){
        try{
            $sql = "INSERT INTO user (nome, senha, email) VALUES (:nome,:senha,:email)";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":nome", $user->getNome());
            $p_sql->bindValue(":senha", $user->getSenha());
            $p_sql->bindValue(":email", $user->getEmail());
            
            return $p_sql->execute();
                        
        }catch(Exception $e){
            echo ("Error while running Insert method in DaoUser.");
        }
    }
    
    public function Update(ModelUser $user){
        try{
            $sql = "UPDATE user SET nome = :nome, senha = :senha, email = := email WHERE id =: userid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":nome", $user->getNome());
            $p_sql->bindValue(":senha", $user->getSenha());
            $p_sql->bindValue(":email", $user->getEmail());
            $p_sql->bindValue(":userid", $user->getId());
            
            return $p_sql->execute();
        }catch(Exception $e){
            echo ("Error while running Update method in DaoUser.");
        }
    }
    
    public function Delete(ModelUser $user){
        try{
            $sql = "DELETE FROM user WHERE id = :userid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":userid", $user->getId());
            
            return $p_sql->execute();           
        }catch(Exception $e){
            echo ("Error while running Delete method in DaoUser.");
        }
    }
    
    public function SearchById($id){
        try{
            $sql = "SELECT * FROM user WHERE id = :userid";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->bindValue(":userid", $id);         
            $p_sql->execute();
            
            return $this->FillUser($p_sql->fetch(PDO::FETCH_ASSOC));
        }catch(Exception $e){
             echo ("Error while running SearchById method in DaoUser.");
        }
    }
    
    public function SearchAll(){
        try{
            $sql = "SELECT * FROM user ORDER BY id";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->execute();
            
            return $p_sql->fetchAll();
        }catch(Exception $e){
            echo ("Error while running SearchAll method in DaoUser.");
        }
    }
    
    private function FillUser($singleuser){
        $user = new ModelUser($singleuser['id'],$singleuser['data_in'],$singleuser['nome'],$singleuser['senha'],$singleuser['email']);
        return $user;
    } 
}

$daouser = new DaoUser();
print_r($daouser->SearchAll());

echo 'test';
?>
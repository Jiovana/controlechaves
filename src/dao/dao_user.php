<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_user.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

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
            $sql = "UPDATE user SET nome = :nome, senha = :senha, email = :email WHERE id = :userid";
            
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
    
    public function SearchEmail($email){
        try{
            $sql = "SELECT email FROM user WHERE email = :email";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->bindValue(":email", $email);         
            $p_sql->execute();
            
            if ($p_sql->rowCount() == 0)
                return null;
            else{
                $row = $p_sql->fetch(PDO::FETCH_ASSOC);
                return $row["email"] ;
            }       
        }catch(Exception $e){
             echo ("Error while running SearchEmail method in DaoUser.");
        }
    }
    
    public function Login($email, $password){
        try{
            $sql = "SELECT * FROM user WHERE email=:email AND senha=:senha";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":email",$email);
            $p_sql->bindValue(":senha",$password);  
            $p_sql->execute();
            //print_r($p_sql->fetch(PDO::FETCH_ASSOC));
            return $p_sql->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo "Error while running Login method in DaoUser.";
        }
    }
    
    
    private function FillUser($singleuser){
        $user = new ModelUser($singleuser['id'],$singleuser['data_in'],$singleuser['nome'],$singleuser['senha'],$singleuser['email']);
        return $user;
    } 
}

//$dao = new DaoUser();
//echo $dao->SearchEmail("michael@mail.co")."</br>";
//print_r(  $dao->Login("michael@mail.co",("pass1")));

?>
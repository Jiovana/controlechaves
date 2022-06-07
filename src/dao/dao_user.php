<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_user.php";
require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

class DaoUser{
    public static $instance;
    
    public function __construct(){}
    
    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new DaoUser();    
        return self::$instance;
    }
    
    public function Insert(ModelUser $user){
        try{
            $sql = "INSERT INTO user (nome, sobrenome, senha, email) VALUES (:nome,:sobrenome,:senha,:email)";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":nome", $user->getNome());
            $p_sql->bindValue(":sobrenome", $user->getSobrenome());
            $p_sql->bindValue(":senha", md5($user->getSenha()));
            $p_sql->bindValue(":email", $user->getEmail());
            
            return $p_sql->execute();
                        
        }catch(Exception $e){
            echo ("Error while running Insert method in DaoUser.");
        }
    }
    
    public function UpdateAll(ModelUser $user){
        try{
            $sql = "UPDATE user SET nome = :nome, sobrenome = :sobrenome,senha = :senha, email = :email WHERE id = :userid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":nome", $user->getNome());
            $p_sql->bindValue(":sobrenome", $user->getSobrenome());
            $p_sql->bindValue(":senha", $user->getSenha());
            $p_sql->bindValue(":email", $user->getEmail());
            $p_sql->bindValue(":userid", $user->getId());
            
            return $p_sql->execute();
        }catch(Exception $e){
            echo ("Error while running Update method in DaoUser.");
        }
    }
    
    public function UpdatePassword(ModelUser $user){
        try{
            $sql = "UPDATE user SET senha = :senha WHERE email = :email";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":senha", $user->getSenha());
            $p_sql->bindValue(":email", $user->getEmail());
            
            return $p_sql->execute();
        }catch(Exception $e){
            echo ("Error while running UpdatePassword method in DaoUser.");
        }
    }
    
    public function Delete($id){
        try{
            $sql = "DELETE FROM user WHERE id = :userid";
            
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":userid", $id);
            
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
            
            $p_sql->setFetchMode(PDO::FETCH_CLASS, 'ModelUser');
            return $p_sql->fetch();
        }catch(Exception $e){
             echo ("Error while running SearchById method in DaoUser.");
        }
    }
    
    public function SearchByEmail($email){
        try{
            $sql = "SELECT * FROM user WHERE email = :email";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->bindValue(":email", $email);         
            $p_sql->execute();
            $p_sql->setFetchMode(PDO::FETCH_CLASS, 'ModelUser');
            return $p_sql->fetch();
            
        }catch(Exception $e){
             echo ("Error while running SearchByEmail method in DaoUser.");
        }
    }
    
    public function SearchAll(){
        try{
            $sql = "SELECT * FROM user ORDER BY id desc";
            
            $p_sql = Connection::getInstance()->prepare($sql);     
            $p_sql->execute();
            
            return $p_sql->fetchAll(PDO::FETCH_CLASS, "ModelUser");
        }catch(Exception $e){
            echo ("Error while running SearchAll method in DaoUser.");
        }
    }
    
    public function Login($email, $password){
        try{
            $sql = "SELECT * FROM user WHERE email=:email AND senha=:senha";
            $p_sql = Connection::getInstance()->prepare($sql);
            $p_sql->bindValue(":email",$email);
            $p_sql->bindValue(":senha",$password);  
            $p_sql->execute();
            return $p_sql->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo "Error while running Login method in DaoUser.";
        }
    }
    
    
    private function FillUser($singleuser){
        $user = new ModelUser();
        $user->setId($singleuser['id']);
        $user->setData_in($singleuser['data_in']);
        $user->setNome($singleuser['nome']) ;
        $user->setSobrenome($singleuser['sobrenome']) ;
        $user->setEmail($singleuser['email']);
        $user->setSenha($singleuser['senha']);
        return $user;
    } 
    
    
}

$dao = new DaoUser();

if ($dao->SearchbyEmail("doll@mail.com"))
    echo true;
//echo $dao->SearchEmail("michael@mail.co")."</br>";
//print_r(  $dao->Login("michael@mail.co",("pass1")));

?>
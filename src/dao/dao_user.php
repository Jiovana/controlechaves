<?php
// modelo (representa entidade do banco)
include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_user.php";
//conexao com banco
require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

/**
 * Metodos de comunicacao com o banco para o ModelUser
 * 
*/
class DaoUser{
    public static $instance;
    
    public function __construct(){}
    
    public static function getInstance(){
        if(!isset(self::$instance))
            self::$instance = new DaoUser();    
        return self::$instance;
    }
    
    /**
     * Insere um novo usuario no banco de dados
     * 
     * @param ModelUser $user O objeto usuario a ser inserido
     * @return bool o resultado do metodo execute()
    */
    public function Insert(ModelUser $user){
        try{
            $sql = "INSERT INTO user (nome, sobrenome, senha, email) VALUES (:nome,:sobrenome,:senha,:email)";
            
            $p_sql = Connection::getConnection()->prepare($sql);
            $p_sql->bindValue(":nome", $user->getNome());
            $p_sql->bindValue(":sobrenome", $user->getSobrenome());
            $p_sql->bindValue(":senha", md5($user->getSenha()));
            $p_sql->bindValue(":email", $user->getEmail());
            
            return $p_sql->execute();
                        
        }catch(Exception $e){
            echo ("Error while running Insert method in DaoUser.");
        }
    }
    
    /**
     * Atualiza todos campos de um usuario
     * 
     * @param ModelUser $user O objeto usuario a ser atualizado
     * @return bool o resultado do metodo execute()
    */
    public function UpdateAll(ModelUser $user){
        try{
            $sql = "UPDATE user SET nome = :nome, sobrenome = :sobrenome,senha = :senha, email = :email WHERE id = :userid";
            
            $p_sql = Connection::getConnection()->prepare($sql);
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
    
   /**
     * Atualiza a senha de um usuario
     * 
     * @param ModelUser $user O objeto usuario a ser atualizado
     * @return bool o resultado do metodo execute()
    */
    public function UpdatePassword(ModelUser $user){
        try{
            $sql = "UPDATE user SET senha = :senha WHERE email = :email";
            
            $p_sql = Connection::getConnection()->prepare($sql);
            $p_sql->bindValue(":senha", $user->getSenha());
            $p_sql->bindValue(":email", $user->getEmail());
            
            return $p_sql->execute();
        }catch(Exception $e){
            echo ("Error while running UpdatePassword method in DaoUser.");
        }
    }
    
    /**
     * Apaga um usuario do banco de dados
     * 
     * @param int $id O id do usuario a ser apagado
     * @return bool o resultado do metodo execute()
    */
    public function Delete($id){
        try{
            $sql = "DELETE FROM user WHERE id = :userid";
            
            $p_sql = Connection::getConnection()->prepare($sql);
            $p_sql->bindValue(":userid", $id);
            
            return $p_sql->execute();           
        }catch(Exception $e){
            echo ("Error while running Delete method in DaoUser.");
        }
    }
    
    /**
     * Busca um usuario com base no ID
     * 
     * @param int $id o id do usuario a ser buscado
     * @return ModelUser um objeto usuario
    */
    public function SearchById($id){
        try{
            $sql = "SELECT * FROM user WHERE id = :userid";
            
            $p_sql = Connection::getConnection()->prepare($sql);     
            $p_sql->bindValue(":userid", $id);         
            $p_sql->execute();
            
            $p_sql->setFetchMode(PDO::FETCH_CLASS, 'ModelUser');
            return $p_sql->fetch();
        }catch(Exception $e){
             echo ("Error while running SearchById method in DaoUser.");
        }
    }
    
    /**
     * Busca um usuario com base no ID
     * 
     * @param string $email o email do usuario a ser buscado
     * @return ModelUser um objeto usuario
    */
    public function SearchByEmail($email){
        try{
            $sql = "SELECT * FROM user WHERE email = :email";
            
            $p_sql = Connection::getConnection()->prepare($sql);     
            $p_sql->bindValue(":email", $email);         
            $p_sql->execute();
            $p_sql->setFetchMode(PDO::FETCH_CLASS, 'ModelUser');
            return $p_sql->fetch();
            
        }catch(Exception $e){
             echo ("Error while running SearchByEmail method in DaoUser.");
        }
    }
    
    /**
     * Busca todos usuarios cadastrados
     * 
     * 
     * @return ModelUser[] um array de objetos usuario
    */
    public function SearchAll(){
        try{
            $sql = "SELECT * FROM user ORDER BY id desc";
            
            $p_sql = Connection::getConnection()->prepare($sql);     
            $p_sql->execute();
            
            return $p_sql->fetchAll(PDO::FETCH_CLASS, "ModelUser");
        }catch(Exception $e){
            echo ("Error while running SearchAll method in DaoUser.");
        }
    }
    
    /**
     * Busca um usuario com base no email e senha
     * 
     * @param string $email o email do usuario a ser buscado
     * @param string $password a senha informada
     * @return array de fetch assoc
    */
    public function Login($email, $password){
        try{
            $sql = "SELECT * FROM user WHERE email=:email AND senha=:senha";
            $p_sql = Connection::getConnection()->prepare($sql);
            $p_sql->bindValue(":email",$email);
            $p_sql->bindValue(":senha",$password);  
            $p_sql->execute();
            return $p_sql->fetch(PDO::FETCH_ASSOC);
        }catch(Exception $e){
            echo "Error while running Login method in DaoUser.";
        }
    }   
    
}

?>
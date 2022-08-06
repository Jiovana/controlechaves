<?php

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_hook.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

/**
 * Metodos de comunicacao com o banco para o ModelAddress
 * 
*/
class DaoHook {
    public static $instance;

    public function __construct() {
        //
    }

    public static function getInstance() {
        if ( !isset( self::$instance ) )
        self::$instance = new DaoHook();

        return self::$instance;
    }
    
    
    /**
     * Insere um novo gancho no banco de dados
     * 
     * @param ModelHook $hook O objeto hook a ser inserido
     * @return bool o resultado do metodo execute()
    */
    public function Insert( ModelHook $hook ) {
        try {
            $sql = "INSERT INTO hook (codigo, tipo, usado) VALUES (:codigo,:tipo,:usado)";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":codigo", $hook->getCodigo() );
            $p_sql->bindValue( ":tipo", $hook->getTipo() );
            $p_sql->bindValue( ":usado", $hook->getUsado() );                     
            return $p_sql->execute();
        } catch( PDOException  $e ) {
            echo  "Error while running Insert method in DaoHook: ".$e->getMessage();
        }
    }

    /**
     * Atualiza um gancho do banco de dados
     * 
     * @param ModelHook $hook O objeto hook a ser atualizado
     * @return bool o resultado do metodo execute()
    */
    public function Update( ModelHook $hook ) {
        try {
            $sql = "UPDATE hook SET codigo = :codigo, tipo = :tipo, usado = :usado WHERE id = :hookid";
            
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":codigo", $hook->getCodigo() );
            $p_sql->bindValue( ":tipo", $hook->getTipo() );
            $p_sql->bindValue( ":usado", $hook->getUsado() );
            $p_sql->bindValue( ":hookid", $hook->getId() );

            return $p_sql->execute();
        } catch( PDOException  $e ) {
            echo  "Error while running Update method in DaoHook: ".$e->getMessage();
        }
    }
    
    
    /**
     * Deleta um gancho do banco de dados
     * 
     * @param int $id O id do gancho a ser deletado
     * @return bool o resultado do metodo execute()
    */
    public function Delete( $id ) {
        try {
            $sql = "DELETE FROM hook WHERE id = :hookid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":hookid", $id );

            return $p_sql->execute();

        } catch( PDOException  $e ) {
            echo  "Error while running Delete method in DaoHook: ".$e->getMessage();
        }
    }
    
    
    /**
     * Procura um gancho pelo Id
     * 
     * @param int $id O id do gancho a ser buscado
     * @return ModelHook o objeto hook encontrado
    */
    public function SearchById( $id ) {
        try {
            $sql = "SELECT * FROM hook WHERE id = :hookid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":hookid", $id );
            $p_sql->execute();
            
             $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelHook' );

            return $p_sql->fetch();
            
        } catch( PDOException  $e ) {
            echo  "Error while running SearchById method in DaoHook: ".$e->getMessage();
        }
    }
    
   
     /**
     * Procura um gancho pelo codigo
     * 
     * @param int $id O id do gancho a ser buscado
     * @return ModelHook o objeto hook encontrado
    */
    public function SearchHookByCode($code){
        try {
            $sql = "SELECT * FROM hook WHERE codigo = :cod";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":cod", $code );
            $p_sql->execute();
            
             $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelHook' );

            return $p_sql->fetch();
            
        } catch( PDOException  $e ) {
            echo  "Error while running SearchById method in DaoHook: ".$e->getMessage();
        }
    }

     /**
     * Busca todos ganchos do banco
     * 
     * @return ModelHook[] array de objs hook
    */
    public function SearchAll() {
        try {
            $sql = "SELECT * FROM hook ORDER BY id";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->execute();

            return $p_sql->fetchAll(PDO::FETCH_CLASS, "ModelHook");
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAll method in DaoHook: ".$e->getMessage();
        }
    }
    
    /**
     * Busca todos ganchos do banco do tipo informado
     * 
     * @return ModelHook[] array de objs hook
    */
    public function SearchAllByType($type) {
        try {
            $sql = "SELECT * FROM hook WHERE tipo = :tipo ORDER BY codigo";

            $p_sql = Connection::getConnection()->prepare( $sql );
             $p_sql->bindValue( ":tipo", $type);
            $p_sql->execute();

            return $p_sql->fetchAll(PDO::FETCH_CLASS, "ModelHook");
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAll method in DaoHook: ".$e->getMessage();
        }
    }
    
    public function VerifyFreeHooks($type){
        try{
            $sql = "SELECT COUNT(usado) FROM hook WHERE usado = 0 AND tipo = :tipo";
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":tipo", $type);
            $p_sql->execute();
            return $p_sql->fetch(PDO::FETCH_COLUMN,0);
        }catch (PDOException $e){
            echo "Error while running VerifyFreeHooks in DaoHook: ".$e->getMessage();
        }
    }
    
    public function ActivateUsado($hook){
        try{
            $sql = "UPDATE hook SET usado = true WHERE id = :hookid";
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":hookid", $hook->getId());
            $p_sql->execute();
        }catch (PDOException $e){
            echo "Error while running UpdateUsado in DaoHook: ".$e->getMessage();
        }
    }

     

}

$dao = new DaoHook();
//echo $dao->VerifyFreeHooks("Aluguel");
//print_r($dao->SearchAllByType("Aluguel"));

?>
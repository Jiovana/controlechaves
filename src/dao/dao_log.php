<?php

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_log.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

/**
* Metodos de comunicacao com o banco para o ModelLog
*
*/

class DaoLog {
    public static $instance;

    public function __construct() {
        //
    }

    public static function getInstance() {
        if ( !isset( self::$instance ) )
        self::$instance = new DaoLog();

        return self::$instance;
    }

    /**
    * Insere um novo log no banco de dados
    *
    * @param ModelLog $log O objeto log a ser inserido
    * @return bool o resultado do metodo execute()
    */

    public function Insert( ModelLog $log ) {
        try {
            $sql = "INSERT INTO log (description, operation, keys_id, user_id) VALUES (:desc, :oper, :keys_id, :user_id)";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":desc", $log->getDescription() );
            $p_sql->bindValue( ":oper", $log->getOperation() );
            $p_sql->bindValue( ":keys_id", $log->getKeys_id() );
            $p_sql->bindValue( ":user_id", $log->getUser_id() );

            return $p_sql->execute();

        } catch( Exception $e ) {
            echo ( "Error while running Insert method in DaoLog." );
        }
    }

    /**
    * Atualiza um log no banco de dados
    *
    * @param ModelLog $log O objeto log a ser inserido
    * @return bool o resultado do metodo execute()
    */

    public function Update( ModelLog $log ) {
        try {
            $sql = "UPDATE log SET description = :description, operation = :operation, keys_id = :keys_id, user_id = :user_id WHERE id = :logid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":description", $log->getDescription() );
            $p_sql->bindValue( ":operation", $log->getOperation() );
            $p_sql->bindValue( ":keys_id", $log->getKeys_id() );
            $p_sql->bindValue( ":user_id", $log->getUser_id() );
            $p_sql->bindValue( ":logid", $log->getId );

            return $p_sql->execute();

        } catch( Exception $e ) {
            echo ( "Error while running Update method in DaoLog." );
        }
    }

    /**
    * Apaga um log no banco de dados
    *
    * @param int $id O id do objeto a ser removido
    * @return bool o resultado do metodo execute()
    */

    public function Delete( $id ) {
        try {
            $sql = "DELETE FROM log WHERE id = :logid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":logid", $id );

            return $p_sql->execute();

        } catch( Exception $e ) {
            echo ( "Error while running Delete method in DaoLog." );
        }
    }

    /**
    * Procura um log no banco de dados
    *
    * @param int $id O id do objeto a ser procurado
    * @return ModelLog um objeto da classe ModelLog
    */

    public function SearchById( $id ) {
        try {
            $sql = "SELECT * FROM log WHERE id = :logid";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->bindValue( ":logid", $id );

            $p_sql->execute();

            $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelLog' );

            return $p_sql->fetch();

        } catch( Exception $e ) {
            echo ( "Error while running SearchById method in DaoLog." );
        }
    }

    /**
    * Procura todos logs no banco de dados que tenham o id da chave informado
    * Usa um array em vez de objetos da classe Log por causa do Join com User.
    *
    * @param int $id O id da chave
    * @return [] um array com os dados do banco
    */

    public function SearchAllByKey( $id ) {
        try {
            $sql = "SELECT log.date, log.operation, log.description, user.nome FROM log INNER JOIN user ON log.user_id = user.id WHERE keys_id = :keyid ORDER BY log.id DESC";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->bindValue( ":keyid", $id );

            $p_sql->execute();

            return $p_sql->fetchAll( PDO::FETCH_ASSOC );

        } catch( Exception $e ) {
            echo ( "Error while running SearchAllByKey method in DaoLog." );
        }
    }

    /**
    * Procura todos os logs do banco de dados
    *
    *
    * @return ModelLog[] um array de objetos da classe ModelLog
    */

    public function SearchAll() {
        try {
            $sql = "SELECT * FROM log ORDER BY id";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();

            return $p_sql->fetchAll( PDO::FETCH_CLASS, "ModelLog" );
        } catch( Exception $e ) {
            echo ( "Error while running SearchAll method in DaoLog." );
        }
    }
    
    
     /**
    * Procura todos os logs do banco de dados dentro de um período determinado
    *
    *
    * @param string $date_begin A data de inicio do periodo
    * @param string $date_end A data final do periodo
    * @return array[] um array associativo com os resultados da consulta: data, operação, descrição, nome do usuário e gancho da chave. 
    */
    public function SearchAllPeriod($date_begin, $date_end){
        try{
            $sql = "SELECT log.date, log.operation, log.description, user.nome, keys.gancho FROM log INNER JOIN user ON log.user_id = user.id 
            INNER JOIN `keys` ON log.keys_id = keys.id WHERE log.date BETWEEN :fromdate AND :todate ORDER BY log.date DESC";
        
            
            $p_sql = Connection::getConnection()->prepare($sql);
            $p_sql->bindValue(":fromdate", $date_begin);
            $p_sql->bindValue(":todate", $date_end);
            $p_sql->execute();
            
            return $p_sql->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e){
            echo "Error while running SearchAllPeriod method in DaoLog: ".$e->getMessage();
        }
    }
}

?>
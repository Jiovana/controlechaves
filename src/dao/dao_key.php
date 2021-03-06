<?php

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_key.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

/**
* Metodos de comunicacao com o banco para o ModelKey
*
*/

class DaoKey {
    public static $instance;

    public function __construct() {
        //
    }

    public static function getInstance() {
        if ( !isset( self::$instance ) )
        self::$instance = new DaoKey();

        return self::$instance;
    }

    /**
    * Insere uma nova chave no banco de dados
    *
    * @param ModelKey $key O objeto key a ser inserido
    * @return bool o resultado do metodo execute()
    */

    public function Insert( ModelKey $key ) {
        try {
            $sql = "INSERT INTO `keys` (gancho, sicadi, tipo, status, adicional,endereco_id) VALUES (:gancho,:sicadi,:tipo,:status,:adicional,:endereco_id)";

            // print_r( $key );
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":gancho", $key->getGancho() );
            $p_sql->bindValue( ":sicadi", $key->getSicadi() );
            $p_sql->bindValue( ":tipo", $key->getTipo() );
            $p_sql->bindValue( ":status", $key->getStatus() );
            $p_sql->bindValue( ":adicional", $key->getAdicional() );
            $p_sql->bindValue( ":endereco_id", $key->getEnderecoId() );

            return $p_sql->execute();

        } catch( PDOException $e ) {

            echo "Error while running Insert method in DaoKey: ".$e;
        }
    }

    /**
    * Atualiza uma chave no banco de dados
    *
    * @param ModelKey $key O objeto key a ser atualizado
    * @return bool o resultado do metodo execute()
    */

    public function Update( ModelKey $key ) {
        try {
            $sql = "UPDATE `keys` SET gancho = :gancho, sicadi = :sicadi, tipo = :tipo, status = :status, adicional = :adicional, endereco_id = :endereco_id WHERE id = :keyid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":gancho", $key->getGancho() );
            $p_sql->bindValue( ":sicadi", $key->getSicadi() );
            $p_sql->bindValue( ":tipo", $key->getTipo() );
            $p_sql->bindValue( ":status", $key->getStatus() );
            $p_sql->bindValue( ":adicional", $key->getAdicional() );
            $p_sql->bindValue( ":endereco_id", $key->getEnderecoId() );
            $p_sql->bindValue( ":keyid", $key->getId() );

            return $p_sql->execute();

        } catch( PDOException $e ) {

            echo "Error while running Update method in DaoKey: ".$e;
        }
    }

    /**
    * Remove uma chave do banco de dados
    *
    * @param int $id o id da chave a ser removida
    * @return bool o resultado do metodo execute()
    */

    public function Delete( $id ) {
        try {
            $sql = "DELETE FROM `keys` WHERE id = :keyid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":keyid", $id );

            return $p_sql->execute();

        } catch( PDOException $e ) {

            echo "Error while running Delete method in DaoKey: ".$e;
        }
    }

    /**
    * Busca uma chave do banco de dados
    *
    * @param int $id o id da chave a ser buscada
    * @return ModelKey objeto key
    */

    public function SearchById( $id ) {
        try {
            $sql = "SELECT * FROM `keys` WHERE id = :keyid";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->bindValue( ":keyid", $id );

            $p_sql->execute();

            $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelKey' );

            return $p_sql->fetch();

        } catch( PDOException $e ) {

            echo "Error while running SearchById method in DaoKey: ".$e;
        }
    }

    /**
    * Busca todas as chaves do banco
    *
    *
    * @return ModelKey[] array de keys
    */

    public function SearchAll() {
        try {
            $sql = "SELECT * FROM `keys` ORDER BY id";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();

            return $p_sql->fetchAll( PDO::FETCH_CLASS, "ModelKey" );
        } catch( PDOException $e ) {

            echo "Error while running SearchAll method in DaoKey: ".$e;
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
            $sql = "SELECT id FROM `keys` ORDER BY id DESC LIMIT 1";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();

            $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelKey' );

            return $p_sql->fetch();
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAllLimit1 method in DaoKey: ".$e->getMessage();
        }
    }

    /**
    * Busca todas chaves com status disponivel ordenadas pelo codigo do gancho.
    *  Usado em borrowkey.php
    *
    * @return ModelKey[] array de objetos key.
    */

    public function SearchAllByGancho() {
        try {

            $sql = "SELECT * FROM `keys` WHERE status = 'Dispon??vel'  ORDER BY gancho ASC";
            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();
            return $p_sql->fetchAll( PDO::FETCH_CLASS, "ModelKey" );
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAllByGancho method in DaoKey: ".$e->getMessage();
        }
    }

    /**
    * Busca todas chaves com status disponivel ordenadas pelo codigo do sicadi.
    *  Usado em borrowkey.php
    *
    * @return ModelKey[] array de objetos key.
    */

    public function SearchAllBySicadi() {
        try {

            $sql = "SELECT * FROM `keys` WHERE status = 'Dispon??vel' ORDER BY sicadi ASC";
            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();
            return $p_sql->fetchAll( PDO::FETCH_CLASS, "ModelKey" );
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAllBySicadi method in DaoKey: ".$e->getMessage();
        }
    }

    /**
    * Busca o conteudo de uma chave com base no seu id.
    *  Usado em borrowkey.php
    *
    * @param int $id o id da chave
    * @return array array associativo com os dados de key.
    */

    public function SearchByIdAssoc( $id ) {
        try {
            $sql = "SELECT * FROM `keys` WHERE id = :keyid";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->bindValue( ":keyid", $id );

            $p_sql->execute();

            return $p_sql->fetch( PDO::FETCH_ASSOC );

        } catch( PDOException $e ) {

            echo "Error while running SearchByIdAssoc method in DaoKey: ".$e;
        }
    }

    /**
    * Atualiza o status de uma chave no banco de dados
    *
    * @param int $id o id da key a ser atualizada
    * @param string $status o novo status da chave
    * @return bool o resultado do metodo execute()
    */

    public function UpdateStatus( $id, $status ) {
        try {
            $sql = "UPDATE `keys` SET status = :status WHERE id = :keyid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":status", $status );
            $p_sql->bindValue( ":keyid", $id );

            return $p_sql->execute();

        } catch( PDOException $e ) {

            echo "Error while running UpdateStatus method in DaoKey: ".$e;
        }
    }
    
    public function SelectGancho($id){
        try{
            $sql = "SELECT gancho from `keys` WHERE id = :id";
            
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":id", $id );
            $p_sql->execute();
            
            $p_sql->setFetchMode(PDO::FETCH_COLUMN, 0);
            return $p_sql->fetch();
            
        } catch (PDOException $e){
            echo "Error while running SelectGancho method in DaoKey: ".$e;
        }
    }

}

$dao = new DaoKey();
//echo $dao->SelectGancho(4);

?>
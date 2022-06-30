<?php

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_requester.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

/**
* Metodos de comunicacao com o banco para o ModelRequester
*
*/

class DaoRequester {
    public static $instance;

    public function __construct() {
        //
    }

    public static function getInstance() {
        if ( !isset( self::$instance ) )
        self::$instance = new DaoRequester();

        return self::$instance;
    }

    /**
    * Insere um novo requerente no banco de dados
    *
    * @param ModelRequester $requester O objeto requester a ser inserido
    * @return bool o resultado do metodo execute()
    */

    public function Insert( ModelRequester $requester ) {
        try {
            $sql = "INSERT INTO requester (nome, telefone, ddd, email, documento, tipo) VALUES (:nome,:telefone, :ddd, :email, :documento, :tipo)";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":nome", $requester->getNome() );
            $p_sql->bindValue( ":telefone", $requester->getTelefone() );
            $p_sql->bindValue( ":ddd", $requester->getDdd() );
            $p_sql->bindValue( ":email", $requester->getEmail() );
            $p_sql->bindValue( ":documento", $requester->getDocumento() );
            $p_sql->bindValue( ":tipo", $requester->getTipo() );

            return $p_sql->execute();

        } catch( Exception $e ) {
            echo ( "Error while running Insert method in DaoRequester." );
        }
    }

    /**
    * Atualiza todos campos de um requerente
    *
    * @param ModelRequester $requester O objeto requester a ser atualizado
    * @return bool o resultado do metodo execute()
    */

    public function Update( ModelRequester $requester ) {
        try {
            $sql = "UPDATE requester SET nome = :nome, telefone = :telefone, ddd = :ddd, email = :email, documento = :documento, tipo = :tipo WHERE id =: reqid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":nome", $user->getNome() );
            $p_sql->bindValue( ":telefone", $requester->getTelefone() );
            $p_sql->bindValue( ":ddd", $requester->getDdd() );
            $p_sql->bindValue( ":email", $requester->getEmail() );
            $p_sql->bindValue( ":documento", $requester->getDocumento() );
            $p_sql->bindValue( ":tipo", $requester->getTipo() );
            $p_sql->bindValue( ":reqid", $requester->getId() );

            return $p_sql->execute();
        } catch( Exception $e ) {
            echo ( "Error while running Update method in DaoRequester." );
        }
    }

    /**
    * Apaga um requester do banco de dados
    *
    * @param int $id O id do requerente a ser apagado
    * @return bool o resultado do metodo execute()
    */

    public function Delete( $id ) {
        try {
            $sql = "DELETE FROM requester WHERE id = :reqid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":reqid", $id );

            return $p_sql->execute();

        } catch( Exception $e ) {
            echo ( "Error while running Delete method in DaoRequester." );
        }
    }

    /**
    * Busca um requerente com base no ID
    *
    * @param int $id o id do requerente a ser buscado
    * @return ModelRequester um objeto requester
    */

    public function SearchById( $id ) {
        try {
            $sql = "SELECT * FROM requester WHERE id = :reqid";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->bindValue( ":reqid", $id );

            $p_sql->execute();

            $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelRequester' );
            return $p_sql->fetch();
        } catch( Exception $e ) {
            echo ( "Error while running SearchById method in DaoRequester." );
        }
    }

    /**
    * Busca todos requerentes inseridos no sistema
    *
    *
    * @return ModelRequester[] um array de objetos requester
    */

    public function SearchAll() {
        try {
            $sql = "SELECT * FROM requester ORDER BY id";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();

            return $p_sql->fetchAll( PDO::FETCH_CLASS, "ModelRequester" );
        } catch( Exception $e ) {
            echo ( "Error while running SearchAll method in DaoRequester." );
        }
    }

    /**
    * Busca o id do ultimo requester inserido no banco
    *
    *
    * @return ModelRequester um objeto requester
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

    
    /**
    * Busca um requerente com base no nome ou email
    * Usado em borrowkey.php, opcao Procurar.
    *
    * @param string $email o id do requerente a ser buscado
    * @param string $name o nome do requerente a ser buscado
    * @return array um array associativo com os dados do requerente
    */
    public function SearchByEmailOrName( $email, $name ) {
        try {
            $sql = '';
            $p_sql = '';
            if ( $name != null && $email != null ) {
                $em = '%'.$email.'%';
                $na = '%'.$name.'%';
                $sql = "SELECT * FROM `requester` WHERE LOWER(email) LIKE LOWER(:email) OR LOWER(nome) LIKE LOWER(:name) LIMIT 1";
                $p_sql = Connection::getConnection()->prepare( $sql );
                $p_sql->bindValue( ":email", $em );
                $p_sql->bindValue( ":name", $na );
                
            } else if ( $name != null && $email == null ) {
                $na = '%'.$name.'%';
                $sql = "SELECT * FROM `requester` WHERE LOWER(nome) LIKE LOWER(:name) LIMIT 1";
                $p_sql = Connection::getConnection()->prepare( $sql );
                $p_sql->bindValue( ":name", $na );

            } else if ( $name == null && $email != null ) {
                $em = '%'.$email.'%';
                $sql = "SELECT * FROM `requester` WHERE LOWER(email) LIKE LOWER(:email) LIMIT 1";
                $p_sql = Connection::getConnection()->prepare( $sql );
                $p_sql->bindValue( ":email", $em );

            }

            $p_sql->execute();
            $p_sql->setFetchMode( PDO::FETCH_ASSOC );
            return $p_sql->fetch();
        } catch( PDOException  $e ) {
            echo  "Error while running SearchByEmailOrName method in DaoRequester: ".$e->getMessage();
        }
    }

}

?>
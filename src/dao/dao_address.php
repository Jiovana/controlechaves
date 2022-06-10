<?php

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_address.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

/**
 * Metodos de comunicacao com o banco para o ModelAddress
 * 
*/
class DaoAddress {
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
     * Insere um novo endereco no banco de dados
     * 
     * @param ModelAddress $address O objeto address a ser inserido
     * @return bool o resultado do metodo execute()
    */
    public function Insert( ModelAddress $address ) {
        try {
            $sql = "INSERT INTO address (numero, bairro, cidade, rua, complemento) VALUES (:numero,:bairro,:cidade,:rua,:complemento)";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":numero", $address->getNumero() );
            $p_sql->bindValue( ":bairro", $address->getBairro() );
            $p_sql->bindValue( ":cidade", $address->getCidade() );
            $p_sql->bindValue( ":rua", $address->getRua() );
            $p_sql->bindValue( ":complemento", $address->getComplemento() );
             
            return $p_sql->execute();

        } catch( PDOException  $e ) {
            echo  "Error while running Insert method in DaoAddress: ".$e->getMessage();
        }
    }

    /**
     * Atualiza um endereco do banco de dados
     * 
     * @param ModelAddress $address O objeto address a ser atualizado
     * @return bool o resultado do metodo execute()
    */
    public function Update( ModelAddress $address ) {
        try {
            $sql = "UPDATE address SET numero = :numero, bairro = :bairro, cidade = :cidade, rua = :rua, complemento = :complemento WHERE id = :addrid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":numero", $address->getNumero() );
            $p_sql->bindValue( ":bairro", $address->getBairro() );
            $p_sql->bindValue( ":cidade", $address->getCidade() );
            $p_sql->bindValue( ":rua", $address->getRua() );
            $p_sql->bindValue( ":complemento", $address->getComplemento() );
            $p_sql->bindValue( ":addrid", $address->getId );

            return $p_sql->execute();

        } catch( PDOException  $e ) {
            echo  "Error while running Update method in DaoAddress: ".$e->getMessage();
        }
    }
    
    
    /**
     * Deleta um endereco do banco de dados
     * 
     * @param int $id O id do endereco a ser deletado
     * @return bool o resultado do metodo execute()
    */
    public function Delete( $id ) {
        try {
            $sql = "DELETE FROM address WHERE id = :addrid";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":addrid", $id );

            return $p_sql->execute();

        } catch( PDOException  $e ) {
            echo  "Error while running Delete method in DaoAddress: ".$e->getMessage();
        }
    }
    
    
    /**
     * Procura um endereco pelo Id
     * 
     * @param int $id O id do endereco a ser buscado
     * @return ModelAddress o objeto address encontrado
    */
    public function SearchById( $id ) {
        try {
            $sql = "SELECT * FROM address WHERE id = :addrid";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->bindValue( ":addrid", $id );

            $p_sql->execute();
            
             $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelAddress' );

            return $p_sql->fetch();
            
        } catch( PDOException  $e ) {
            echo  "Error while running SearchById method in DaoAddress: ".$e->getMessage();
        }
    }

     /**
     * Busca todos enderecos do banco
     * 
     * 
     * @return ModelAddress[] array de objs address
    */
    public function SearchAll() {
        try {
            $sql = "SELECT * FROM address ORDER BY id";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->execute();

            return $p_sql->fetchAll(PDO::FETCH_CLASS, "ModelAddress");
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAll method in DaoAddress: ".$e->getMessage();
        }
    }

     /**
     * Busca o ultimo endereco inserido no banco, usado apos Insert em control_address para pegar o id do novo registro e salvar com a chave
     * 
     * 
     * @return ModelAddress obj address com id
    */
    public function SearchIdLimit1() {
        try {
            $sql = "SELECT id FROM address ORDER BY id DESC LIMIT 1";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();

            $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelAddress' );

            return $p_sql->fetch();
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAllLimit1 method in DaoAddress: ".$e->getMessage();
        }
    }

}

?>
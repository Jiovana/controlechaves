<?php

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_borrowing.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

class DaoBorrowing {
    public static $instance;

    public function __construct() {
        //
    }

    public static function getInstance() {
        if ( !isset( self::$instance ) )
        self::$instance = new DaoBorrowing();

        return self::$instance;
    }

    //-------> steps to completely insert a borrowing operation in the database
    //1. obtain requester id if it already exists or insert new requester into DB
    //?

    //2. insert new borrowing register using logged user_id and fetched or new requester_id

    public function InsertBorrow( ModelBorrowing $borrow ) {
        try {
            $sql = "INSERT INTO borrowing (data_checkin, data_checkout, requester_id, user_id) VALUES (:data_checkin,:data_checkout,:requester_id,:user_id)";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":data_checkin", $borrow->getData_checkin() );
            $p_sql->bindValue( ":data_checkout", $borrow->getData_checkout() );
            $p_sql->bindValue( ":requester_id", $borrow->getRequester_id() );
            $p_sql->bindValue( ":user_id", $borrow->getUser_id() );

            return $p_sql->execute();

        } catch( Exception $e ) {
            echo ( "Error while running InsertBorrow method in DaoBorrowing." );
        }
    }
    //3. obtain inserted borrow id and insert as much keys_borrowing registers as necessary according to received keys ids
    //when we insert a borrowing operation, we also need to insert the ids of the chosen keys, belonging to keys_borrowing table

    public function InsertBorrowKey( $borrow_id, $keys_id ) {
        try {
            $sql = "INSERT INTO keys_borrowing (keys_id, borrowing_id) VALUES (:keys_id, :borrow_id)";
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":keys_id", $keys_id );
            $p_sql->bindValue( ":borrow_id", $borrow_id );
            $p_sql->execute();
        } catch( Exception $e ) {
            echo ( "Error while running InsertBorrowKey method in DaoBorrowing." );
        }
    }

    /**
    * Busca o ultimo id inserido no banco
    *
    * @return ModelKey.id id da ultima chave inserida
    */

    public function SearchIdLimit1() {
        try {
            $sql = "SELECT id FROM `borrowing` ORDER BY id DESC LIMIT 1";

            $p_sql = Connection::getConnection()->prepare( $sql );

            $p_sql->execute();

            $p_sql->setFetchMode( PDO::FETCH_CLASS, 'ModelBorrowing' );

            return $p_sql->fetch();
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAllLimit1 method in DaoBorrowing: ".$e->getMessage();
        }
    }

}
?>

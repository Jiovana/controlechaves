<?php

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/model/model_borrowing.php";

require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/connection.php";

/**
* Metodos de comunicacao com o banco para o ModelBorrowing 
* e com a tabela keys_borrowing (relacionamento entre borrowing e keys no banco)
*
*/
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

    /**
    * Insere um novo emprestimo no banco de dados
    *
    * @param ModelBorrowing $borrow O objeto borrowing a ser inserido
    * @return bool o resultado do metodo execute()
    */
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
   
    /**
    * Insere no banco uma instancia keys_borrowing, criando o relacionamento entre essas duas tabelas
    * usado em borrowkey.php
    *
    * @param int $borrow_id O id do borrowing 
    * @param int $keys_id O id da chave
    * @return bool o resultado do metodo execute()
    */
    public function InsertBorrowKey( $borrow_id, $keys_id ) {
        try {
            $sql = "INSERT INTO keys_borrowing (keys_id, borrowing_id) VALUES (:keys_id, :borrow_id)";
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":keys_id", $keys_id );
            $p_sql->bindValue( ":borrow_id", $borrow_id );
            return $p_sql->execute();
        } catch( Exception $e ) {
            echo ( "Error while running InsertBorrowKey method in DaoBorrowing." );
        }
    }

    /**
    * Busca o id do ultimo borrowing inserido no banco
    *
    * @return ModelBorrowing objeto borrowing
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
    
    
    /**
    * Atualiza a data de checkin de um borrowing
    * usado ao devolver uma chave para registrar data de devolucao
    *
    * @param int $id O id do borrowing 
    * @param string? $date a nova data de devolucao
    * @return bool o resultado do metodo execute()
    */
    public function UpdateCheckin($id, $date){
        try{
            $sql = "UPDATE borrowing SET data_checkin = :data WHERE id = :id";
            
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":id", $id );
            $p_sql->bindValue( ":data", $date );

            return $p_sql->execute();           

        } catch( PDOException  $e ) {
            echo  "Error while running UpdateCheckin method in DaoBorrowing: ".$e->getMessage();
        }
    }
    
    /**
    * Procura pelo id de um objeto borrowing com base no codigo da chave na tabela keys_borrowing
    * tenta pegar o maior id de borrowing salvo, significando o mais recente.
    * usado para devolver uma chave. (atualizar a data)
    *
    * @param int $key O id da chave
    * 
    * @return int o id encontrado
    */
    public function SearchBorrowByKey($key){
        try{
            $sql = "SELECT MAX(borrowing_id) FROM `keys_borrowing` WHERE keys_id = :keyid ";
            
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":keyid", $key);
            $p_sql->execute();
            
            $p_sql->setFetchMode(PDO::FETCH_COLUMN, 0);
            return $p_sql->fetch();
            
        } catch( PDOException  $e ) {
            echo  "Error while running SearchBorrowByKey method in DaoBorrowing: ".$e->getMessage();
        }      
    }
    
    /**
    * Inativa uma instancia de keys_borrowing 
    * com base no id da chave
    *
    * @param int $id O id da chave
    * @return bool o resultado do metodo execute()
    */
    public function CloseKeysBorrowing($keyid){
        try{
            $sql = "UPDATE `keys_borrowing` SET is_ativo = 0 WHERE keys_id = :id";
            
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":id", $keyid );

            return $p_sql->execute();           

        } catch( PDOException  $e ) {
            echo  "Error while running CloseKeysBorrowing method in DaoBorrowing: ".$e->getMessage();
        }
    }
    
    
    public function SearchActiveBorrowKey(){
        try {
            $sql = "SELECT * FROM `keys_borrowing` WHERE is_ativo = 1";

            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->execute();

            return $p_sql->fetchAll(PDO::FETCH_ASSOC);
        } catch( PDOException  $e ) {
            echo  "Error while running SearchAllLimit1 method in DaoBorrowing: ".$e->getMessage();
        }
    }
    
    public function SelectCheckin($id){
        try{
            $sql = "SELECT data_checkin from borrowing WHERE id = :id";
            
            $p_sql = Connection::getConnection()->prepare( $sql );
            $p_sql->bindValue( ":id", $id );
            $p_sql->execute();
            
            $p_sql->setFetchMode(PDO::FETCH_COLUMN, 0);
            return $p_sql->fetch();
            
        }catch(exception $e){
            echo  "Error while running SelectBorrowInfo method in DaoBorrowing: ".$e->getMessage();
        }
    }

}

$dao = new DaoBorrowing();
//echo $dao->SelectCheckin(14);

?>

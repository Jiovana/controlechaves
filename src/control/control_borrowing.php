<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_borrowing.php";

/**
* Reune metodos para interacao entre a view ( interface ) relacionado a chave e o model( modelos e daos ) - ModelBorrowing e DaoBorrowing
*
*/
class ControlBorrowing {

    /**
     * Insere novo borrowing no banco
     *
     * 
     * @param ModelBorrowing objeto borrowing a ser inserido
     * @return int o id do emprestimo inserido.
     * 
    */
    public function NewBorrowing( ModelBorrowing $borrow ) {
        $dao = new DaoBorrowing();
        try {
            $dao->InsertBorrow( $borrow );
            $borw = $dao->SearchIdLimit1();
            return $borw->getId();

        } catch ( Exception $e ) {
            echo "Error in method NewBorrowing in ControlBorrowing: ".$e->getMessage()."</br>";
        }

    }

     /**
     * Insere novo relacionamento keys_borrowing no banco
     *
     * 
     * @param int $keys_id id da chave
     * @param int $borrow_id id de borrowing
     * 
    */
    public function NewKeysBorrowing( $key_id, $borrow_id ) {
        $dao = new DaoBorrowing();
        try {
            $dao->InsertBorrowKey($borrow_id, $key_id);
        } catch ( Exception $e ) {
            echo "Error in method NewKeysBorrowing in ControlBorrowing: ".$e->getMessage()."</br>";
        }
    }
    
    /**
     * Busca o id de um borrow com base no id da chave associada.
     *
     * 
     * @param int $keyid id da chave
     * @return int id de borrowing
     * 
    */
    public function FetchBorrowIdByKey($keyid){
        $dao = new DaoBorrowing();
        try {
            return $dao->SearchBorrowByKey($keyid);
        } catch ( Exception $e ) {
            echo "Error in method NewKeysBorrowing in ControlBorrowing: ".$e->getMessage()."</br>";
        }
    }
    
     /**
     * Atualiza data de checkin de um borrowing
     *
     * 
     * @param int $borrow_id id do borrowing a ser atualizado
     * 
    */
    public function UpdateCheckin($borrow_id){
        $dao = new DaoBorrowing();
        try {
            date_default_timezone_set('America/Sao_Paulo');
            $date = date('Y-m-d H:i:s', time());
            
            $dao->UpdateCheckin($borrow_id, $date);
        } catch ( Exception $e ) {
            echo "Error in method UpdateCheckin in ControlBorrowing: ".$e->getMessage()."</br>";
        }
    }
    
    public function DeactiveKeysBorrow($keyid){
        $dao = new DaoBorrowing();
        $dao->CloseKeysBorrowing($keyid);
    }
    
    public function FindActiveKeysBorrowing(){
        $dao = new DaoBorrowing();
        return $dao->SearchActiveBorrowKey();
    }
    
    public function FetchCheckinRequester($borrow_id){
        $dao = new DaoBorrowing();
        $arr = $dao->SelectCheckinRequester($borrow_id);   
        $arr["data_checkin"] = date_format( date_create( $arr["data_checkin"]), 'd/m/Y H:i:s' );
        return $arr;       
    }
    
}
$con = new ControlBorrowing();

//print_r($con->FetchCheckinRequester(14));


?>
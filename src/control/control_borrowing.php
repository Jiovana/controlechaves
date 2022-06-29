<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_borrowing.php";

class ControlBorrowing {

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

    public function NewKeysBorrowing( $key_id, $borrow_id ) {
        $dao = new DaoBorrowing();
        try {
            $dao->InsertBorrowKey($borrow_id, $key_id);
        } catch ( Exception $e ) {
            echo "Error in method NewKeysBorrowing in ControlBorrowing: ".$e->getMessage()."</br>";
        }
    }
    
    public function FetchBorrowIdByKey($keyid){
        $dao = new DaoBorrowing();
        try {
            return $dao->SearchBorrowByKey($keyid);
        } catch ( Exception $e ) {
            echo "Error in method NewKeysBorrowing in ControlBorrowing: ".$e->getMessage()."</br>";
        }
    }
    
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
}

?>
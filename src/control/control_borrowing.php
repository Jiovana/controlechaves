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
}

?>
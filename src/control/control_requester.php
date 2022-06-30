<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_requester.php";

/**
 * Reune metodos para interacao entre a view(interface) e o model(modelos e daos)
 * 
*/
class ControlRequester {

    
     /**
     * Insere novo requester no banco
     *
     * 
     * @param ModelRequester objeto requester a ser inserido
     * @return int o id do requerente inserido.
     * 
    */
    public function NewRequester( ModelRequester $requester ) {
        $dao = new DaoRequester();
        try {
            $dao->Insert( $requester );
            $req = $dao->SearchIdLimit1();
            return $req->getId();

        } catch ( Exception $e ) {
            echo "Error in method NewRequester in ControlRequester: ".$e->getMessage()."</br>";
        }
    }


}


// codigo fora da classe usado pela requisicao ajax  em borrowkey.php
//  preenche os dados do requester no form
if ( isset( $_POST['op'] ) ) {
    try {
        $requester = array();
        $dao = new DaoRequester();
        $requester = $dao->SearchByEmailOrName( $_POST['reqemail'], $_POST['reqnome'] );
        
        if ($requester == false) {
            $requester += ['empty' => true];
        } else {
            $requester += ['empty' => false];
        }
            
        echo json_encode( $requester );
    } catch ( Exception $e ) {
        echo json_encode( $e->getMessage() );
        exit();
    }

}

?>
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
    
    
    /**
     * Traz do banco os dados de um requester com base no id
     *
     * 
     * @param int id o id do requester a ser procurado
     * @return ModelRequester os dados do requester
     * 
    */
    public function FetchRequesterModel($id){
        $dao = new DaoRequester();
        return $dao->SearchById($id);
    }


}


// codigo fora da classe usado pela requisicao ajax  em borrowkey.php
// para preencher os dados do requester no form
if ( isset( $_POST['op'] ) ) {
    // se operacao no post é request = essa aqui
    if ($_POST['op'] == "request"){
        try {
            //busca dados
            $requester = array();
            $dao = new DaoRequester();
            $requester = $dao->SearchByEmailOrName( $_POST['reqemail'], $_POST['reqnome'] );
            // flag no array para verificar se a consulta teve retorno ou não, ou seja, se existe alguem ja cadastrado com esses dados
            if ($requester == false) {
                $requester += ['empty' => true];
            } else {
                $requester += ['empty' => false];
            }
            //envia o array de volta para o ajax como um  json
            echo json_encode( $requester );
        } catch ( Exception $e ) {
            echo json_encode( $e->getMessage() );
            exit();
        }
    }

}

?>
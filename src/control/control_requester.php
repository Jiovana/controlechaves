<?php 

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_requester.php";

class ControlRequester{
    
    public function NewRequester(ModelRequester $requester){
        $dao = new DaoRequester();
        try{
            $dao->Insert($requester);
            $req = $dao->SearchIdLimit1();
            return $req->getId();
            
        } catch (Exception $e){
            echo "Error in method NewRequester in ControlRequester: ".$e->getMessage()."</br>";
        }
    }
    
}


?>
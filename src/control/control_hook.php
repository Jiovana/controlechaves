<?php 

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_hook.php";

class ControlHook{
    
     /**
    * Chama a dao que procura chaves com base no gancho
    *
    * Usado em borrowkey.php
    *
    * @return string codigo html para preenchimento do select na tabela
    */
    public function Fill_Select() {

        $output = '';

        $dao = new DaoHook();
        $result = $dao->SearchAll();

        foreach ( $result as $hook ) {
            $output .= '<option value="'.$hook->getId().'" >'.$hook->getCodigo().'</option>';
        }
        return $output;

    }
    
    public function SearchFreeHooks($type){
        $dao = new DaoHook();
        return $dao->VerifyFreeHooks($type);
    }
    
    public function FetchAllByType($type){
        $dao = new DaoHook();
        return $dao->SearchAllByType($type);
    }
        

    public function FetchHookByCode($code){
        $dao = new DaoHook();
        return $dao->SearchHookByCode($code);
    }   
    
    public function TurnOnUsado($hook){
        $dao = new DaoHook();
        return $dao->ActivateUsado($hook);
    }

    
    
    
}
$control = new ControlHook();
//echo $control->SearchFreeHooks( "Aluguel" );


?>
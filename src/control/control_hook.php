<?php 

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_hook.php";

/**
* Reune metodos para interacao entre a view ( interface ) relacionado a gancho e o model( modelos e daos ) - ModelHook e DaoHook
*
*/
class ControlHook{
    
     /**
    * Preenche o Select de hooks de ambos tipos
    * Usado em newkey.php
    *
    * @return string codigo html para preenchimento dos options no select 
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
    
    /**
    * Procura por ganchos livres do tipo informado
    * 
    * @param string $type o tipo do gancho
    * @return int a quantidade de ganchos disponiveis
    */
    public function SearchFreeHooks($type){
        $dao = new DaoHook();
        return $dao->VerifyFreeHooks($type);
    }
    
    /**
    * Traz todos os ganchos do tipo informado
    * 
    * @param string $type o tipo do gancho
    * @return ModelHook[] array de objs hook
    */
    public function FetchAllByType($type){
        $dao = new DaoHook();
        return $dao->SearchAllByType($type);
    }
        
    /**
    * Traz dados de um gancho com base no codigo
    * 
    * @param string $code o codigo do gancho
    * @return ModelHook um objeto hook
    */
    public function FetchHookByCode($code){
        $dao = new DaoHook();
        return $dao->SearchHookByCode($code);
    }   
    
    /**
    * Altera o campo Usado de um hook
    * 
    * @param int $hookid o id do gancho
    * @param int $value o valor booleano a ser alterado
    * @return o resultado do metodo execute ()
    */
    public function ModifyUsado($hookid, $value){
        $dao = new DaoHook();
        return $dao->UpdateUsado($hookid, $value);
    }



    
    
    
}
$control = new ControlHook();
//echo $control->SearchFreeHooks( "Aluguel" );


?>
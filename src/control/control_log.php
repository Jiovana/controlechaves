<?php 

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_log.php";



class ControlLog{
    
    function CreateLog(ModelLog $log){
        $dao = new DaoLog();
        $dao->Insert($log);
    }
    
    public function FillMovTable() {
        $dao = new DaoLog();
        $logs = $dao->SearchAll();
#	Descrição	Usuário	Data
        foreach ( $logs as $log ) {
            echo '<tr>
                    <td>'.$log->getId().'</td>
                    <td>'.$log->getDescription().'</td>
                    <td>'.$log->getData().'</td>                  
                </tr> ';
        }
    }
    
    
    
}




?>
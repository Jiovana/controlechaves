<?php 

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_borrowing.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

date_default_timezone_set('America/Sao_Paulo');

$controlk = new ControlKey();
$controlb = new ControlBorrowing();
$controll = new ControlLog();


// trazer todos keys_borrowing ativos
$actives = $controlb->FindActiveKeysBorrowing();
// comparar data de checkin de cada borrowing associado com a data atual para ver se esta atrasado
foreach($actives as $instance){
    $borrow_checkin = $controlb->FetchCheckin($instance["borrowing_id"]);
    $current_time = date_format(date_create(null),'d/m/Y H:i:s');   
    if($borrow_checkin >= $current_time){
       // se atrasado: trocar status, emitir alerta, enviar email, etc
        $controlk->UpdateStatus($instance["keys_id"],3);
        
    }
}






?>
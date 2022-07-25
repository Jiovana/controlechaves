<?php 

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_borrowing.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_requester.php';

date_default_timezone_set('America/Sao_Paulo');

/*
* VERIFICA SE EXISTEM CHAVES EM ATRASO
* executa a cada 30 minutos como tarefa no servidor
*/

$controlk = new ControlKey();
$controlb = new ControlBorrowing();
$controll = new ControlLog();
$controlr = new ControlRequester();

// trazer todos keys_borrowing ativos
$actives = $controlb->FindActiveKeysBorrowing();
// comparar data de checkin de cada borrowing associado com a data atual para ver se esta atrasado

$_POST["message"] = array();
$flag = true;
$file = fopen('overduemessages.txt','w');

// percorre o array de keys_borrowing ativos e compara as datas e horarios de checkin com a data e hora atual
foreach($actives as $instance){
    $borrow_info = $controlb->FetchCheckinRequester($instance["borrowing_id"]);
    $checkin = $borrow_info["data_checkin"];
    $req_id = $borrow_info["requester_id"];
    $requester = $controlr->FetchRequesterModel($req_id);
    
    $current_time = date_format(date_create(null),'d/m/Y H:i:s');   
    
    // testa se esta atrasado
    if($checkin <= $current_time){
        echo "overdue</br>";
        // trocar status, 
        $controlk->UpdateStatus($instance["keys_id"],3);
        //gerar log
        $log = new ModelLog();
       
        //inserir o log de emprestar chave   
        $log->setKeys_id($instance["keys_id"]);
        $log->setUser_id(20);
        //operation pode ser: 1 - criacao, 2 - alteracao,
        // 3 - emprestimo, 4 - devolucao
        $log->setOperation(2);
        $gancho = $controlk->FetchGancho($instance["keys_id"]);
        $string = "Chave nº Gancho: ".$gancho.", emprestada para ".$requester->getNome()." está ATRASADA.";    
        $log->setDescription($string);

        $controll->CreateLog($log);
        
        //emitir alerta
        // logica precisa ser revista
        if ($flag == true){
            $flag = false;
            fwrite($file, 'overdue_alert;');
        }     
        fwrite($file, $string.";");        
        
        //$_POST["overdue_alert"] = true;
        
        //array_push($_POST["message"], $string);
           
        //enviar email
        if ($requester->getEmail() != ""){
            $controlb->SendEmailOnOverdue($instance["borrowing_id"], $instance["keys_id"]);
        }
        
        //desativar flag para nao emitir mais alertas.
        //nao sei se eh melhor solucao
        $controlb->DeactiveKeysBorrow($instance["keys_id"]);
        
    // caso nao atrasado, testar se faltam 30 minutos para devolver
    } else if ( strtotime(str_replace('/', '-', $current_time)) >=  (strtotime(str_replace('/', '-', $checkin))-1800)) {
        if(!$instance['is_reminder'] && $requester->getEmail() != ""){
           $controlb->SendEmailBeforeOverdue($instance["borrowing_id"], $instance["keys_id"]);   
           $controlb->ChangeRemindStatus($instance["id"]);
        echo "30 minutos restando</br>"; 
        } else{
            echo "ja enviado ou nao tem email</br>";
        }
         
    } else {
        echo "mais de 30 minutos.</br>";
    }
   // echo $current_time."</br>";
    //echo "str".strtotime($current_time)."</br>";
   // echo $checkin."</br>";
   // echo "str".(strtotime($checkin)-1800)."</br>";
   
}
 fclose($file);

if (empty($actives)){
    echo "No active borrowings </br>";
}


 
//print_r( $_POST["message"]);

?>
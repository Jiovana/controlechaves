<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_borrowing.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';

session_start();

$controlk = new ControlKey();
$controlb = new ControlBorrowing();
$controll = new ControlLog();

// chamado pelo ajax em mainlist.php para devolver uma chave atraves do alerta swal
try {
    if ( !isset( $_POST['op'] ) )
    throw new Exception( 'Post info not set' );
    // se a operacao eh ret "retrieve"
    if ($_POST['op'] == 'ret'){
        // atualiza status da chave
        $controlk->UpdateStatus( $_POST['keyid'], 1 );
        $key = $controlk->GetKeyModel($_POST['keyid']);
        $hook = $controlk->FetchHookCode($key->getId());
        //busca pelo ultimo borrowing id associado   
        $borrowid = $controlb->FetchBorrowIdByKey($_POST['keyid']);

        //atualiza a data de checkin e status
        $controlb->UpdateCheckin( $borrowid );
        $controlb->DeactiveKeysBorrow($key->getId());

        //gerar log
        $log = new ModelLog();
        //inserir o log de emprestar chave   
        $log->setKeys_id($_POST['keyid']);
        $log->setUser_id($_SESSION['user_id']);
        //operation pode ser: 1 - criacao, 2 - alteracao,
        // 3 - emprestimo, 4 - devolucao
        $log->setOperation(4);

        $string = "Chave id: ".$key->getId().", nº Gancho: ".$hook." foi DEVOLVIDA.";    
        $log->setDescription($string);

        $controll->CreateLog($log);

        echo json_encode( array( "stat" => "Ok" ) );
        exit();
    }
} catch ( Exception $e ) {
    echo json_encode( $e->getMessage() );
    exit();
}

?>
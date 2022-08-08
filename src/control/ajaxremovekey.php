<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_hook.php';


session_start();

$controlk = new ControlKey();
$controlh = new ControlHook();
$controll = new ControlLog();
$controla = new ControlAddress();

// chamado pelo ajax em editkey.php para remover uma chave atraves do alerta swal
//nao remove a chave de verdade, apenas tira ela de vista, removendo o acesso
// altera status para indisponivel

try {
    if ( !isset( $_POST['op'] ) )
    throw new Exception( 'Post info not set' );
    // se a operacao eh rem "remove"
    if ($_POST['op'] == 'rem'){
        
        //$status_labels =  array( 1 => 'Disponível', 2 => 'Emprestado', 3 => 'Atrasado', 4 => 'Perdido', 5 => 'Indisponível');
        // muda status da chave para 5, indisponivel
        $controlk->UpdateStatus( $_POST['keyid'], 5);
        
        // obtem dados da chave e address
        $key = $controlk->GetKeyModel($_POST['keyid']);
        $address = $controla->GetAddressString($key->getEnderecoId()); 
        
         // altera atributo hook usado
        $controlh->ModifyUsado($key->getGanchoId(),false);
        // tira gancho_id de key
        $controlk->RemoveHook($key->getId());
        
        //gerar log
        $log = new ModelLog();
        //inserir o log de emprestar chave   
        $log->setKeys_id($_POST['keyid']);
        $log->setUser_id($_SESSION['user_id']);
        //operation pode ser: 1 - criacao, 2 - alteracao,
        // 3 - emprestimo, 4 - devolucao, 5 - exclusao
        $log->setOperation(5);

        $string = "Chave id: ".$key->getId().", endereço: ".$address.", foi REMOVIDA DO SISTEMA.";    
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
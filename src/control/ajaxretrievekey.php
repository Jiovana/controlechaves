<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_borrowing.php';

$controlk = new ControlKey();
$controlb = new ControlBorrowing();

// chamado pelo ajax para devolver uma chave
try {
    if ( !isset( $_POST['op'] ) )
    throw new Exception( 'Post info not set' );

    // atualiza status da chave
    $controlk->UpdateStatus( $_POST['keyid'], 1 );
    
    //busca pelo ultimo borrowing id associado
    
    $borrowid = $controlb->FetchBorrowIdByKey($_POST['keyid']);
    
    //atualiza a data de checkin
    $controlb->UpdateCheckin( $borrowid );

    echo json_encode( array( "stat" => "Ok" ) );
    exit();
} catch ( Exception $e ) {
    echo json_encode( $e->getMessage() );
    exit();
}

?>
<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_borrowing.php';

$controlk = new ControlKey();
$controlb = new ControlBorrowing();

try {
    if ( !isset( $_POST['op'] ) )
    throw new Exception( 'Post info not set' );

    $controlk->UpdateStatus( $_POST['keyid'], 1 );
    
    //need to fetch the borrowing id.
    
    $borrowid = $controlb->FetchBorrowIdByKey($_POST['keyid']);
    
    $controlb->UpdateCheckin( $borrowid );

    echo json_encode( array( "stat" => "Ok" ) );
    exit();
} catch ( Exception $e ) {
    echo json_encode( $e->getMessage() );
    exit();
}

?>
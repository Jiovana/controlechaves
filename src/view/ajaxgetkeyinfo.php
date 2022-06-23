<?php
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';

$control = new ControlKey();
$controla = new ControlAddress();

//echo '<script>console.log("teste1");</script>';
try{
    if (!isset($_POST['id']))
        throw new Exception('Post info not set');
         
        $keyid = $_POST['id'];    

        $response = $control->GetKeyAssoc( $keyid );      
        $address = $controla->GetAddressString($response['endereco_id']);       
        $response += ['endereco_string' => $address];

        echo json_encode( $response );
        exit();
    
} catch (Exception $e){
        echo json_encode($e->getMessage());
        exit();
}


?>
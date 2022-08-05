<?php
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';

$control = new ControlKey();
$controla = new ControlAddress();

// chamado pelo ajax para trazer os dados de uma chave selecionada a partir dos selects na tabela em borrowkey.php
try{
    if (!isset($_POST['id']))
        throw new Exception('Post info not set');
         
        $keyid = $_POST['id'];    
        // obtem array da chave
        $response = $control->GetKeyAssoc( $keyid ); 
        $hook = $control->FetchHookCode($keyid);
        $response["gancho"] = $hook;
        //obtem string do endereco
        $address = $controla->GetAddressString($response['endereco_id']);   
        //adiciona string ao array
        $response += ['endereco_string' => $address];

    
    
    
        echo json_encode( $response );
        exit();
    
} catch (Exception $e){
        echo json_encode($e->getMessage());
        exit();
}


?>
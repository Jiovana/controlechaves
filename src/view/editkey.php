<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_user.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_borrowing.php';

session_start();

if ( $_SESSION['user_email'] == "" ) {
    header( 'location:index.php' );
}

include_once 'header.php';

$controlk = new ControlKey();
$controla = new ControlAddress();
$controll = new ControlLog();

$key_id = $_GET['id'];
$key = $controlk->GetKeyModel( $key_id );

$addr_id = $key->getEnderecoId();
$address = $controla->GetAddressModel( $addr_id );

$log = new ModelLog();

//obter valores nao-modificados para comparacao
$addr_num = $address->getNumero();
$addr_bai = $address->getBairro();
$addr_rua = $address->getRua();
$addr_cid = $address->getCidade();
$addr_com = $address->getComplemento();

$key_sic = $key->getSicadi();
$key_gan = $key->getGancho();
$key_tip = $key->getTipo();
$key_sta = $key->getStatus();
$key_adi = $key->getAdicional();

//ao pressionar o botao de atualizar
if ( isset( $_POST['btnupdate'] ) ) {
    //1. atualizar o endereco
    $address->setNumero( $_POST['txtnum'] );
    $address->setBairro( $_POST['txtdistrict'] );
    $address->setRua( $_POST['txtstreet'] );
    $address->setCidade( $_POST['txtcity'] );
    if ( $_POST['txtaddon2'] == "" ) {
        $address->setComplemento( null );
    } else {
        $address->setComplemento( $_POST['txtaddon2'] );
    }

    $controla->UpdateAddress( $address );

    //2. atualizar a chave
    $key->setSicadi( $_POST['txtsicadi'] );
    $key->setGancho( $_POST['txthook'] );
    $key->setTipo( $_POST['select_category'] );
    $key->setStatus( $_POST['select_status'] );
    $key->setAdicional( $_POST['txtaddon'] );
    $key->setEnderecoId( $addr_id );

    $controlk->UpdateKey( $key );

    //3. inserir o log de atualizacao da chave
    $string = "";
    //3.1 - verificar se campos de endereco mudaram
    $mod_addr = false;
    if ( $addr_num != $_POST['txtnum'] or $addr_bai != $_POST['txtdistrict'] or $addr_rua != $_POST['txtstreet'] or $addr_cid != $_POST['txtcity'] or $addr_com != $_POST['txtaddon2'] ) {
        $mod_addr = true;
    }
    //3.2 - verificar se campos da chave mudaram, exceto status
    $mod_key = false;
    if ( $key_sic != $_POST['txtsicadi'] or $key_gan != $_POST['txthook'] or $key_tip != $_POST['select_category'] or $key_adi != $_POST['txtaddon'] ) {
        $mod_key = true;
    }
    //3.3 - verificar se status mudou
    $mod_sta = false;
    if ( $key_sta != $_POST['select_status'] ) {
        $mod_sta = true;
    }

    //3.5 - preencher obj log e inserir no banco.
    $log->setKeys_id( $key->getId() );
    $log->setUser_id( $_SESSION['user_id'] );

    //operation pode ser: 1 - criacao, 2 - alteracao,
    // 3 - emprestimo, 4 - devolucao
    $log->setOperation( 2 );

    //3.6 - comparar as tres flags entre si.
    if ( $mod_addr and $mod_key and $mod_sta ) {
        //verifica se devolucao
        if ( $key_sta == "Emprestado" && $_POST['select_status'] == "Disponível" ) {
            //update borrowing info.
            $controlb = new ControlBorrowing();
            $borrowid = $controlb->FetchBorrowIdByKey( $key->getId() );
            //atualiza a data de checkin
            $controlb->UpdateCheckin( $borrowid );
            //seta operacao log
            $log->setOperation( 4 );
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." foi DEVOLVIDA.";
        } else {
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." teve atualização do endereço, dados da chave e status: ".$key->getStatus();
        }
    } else if ( $mod_addr and $mod_key ) {
        $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." teve atualização do endereço e dados da chave.";
    } else if ( $mod_addr and $mod_sta ) {
        if ( $key_sta == "Emprestado" && $_POST['select_status'] == "Disponível" ) {
            //update borrowing info.
            $controlb = new ControlBorrowing();
            $borrowid = $controlb->FetchBorrowIdByKey( $key->getId() );
            //atualiza a data de checkin
            $controlb->UpdateCheckin( $borrowid );
            //seta operacao log
            $log->setOperation( 4 );
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." foi DEVOLVIDA.";
        } else {
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." teve atualização do endereço e status: ".$key->getStatus();
        }
    } else if ( $mod_key and $mod_sta ) {
        if ( $key_sta == "Emprestado" && $_POST['select_status'] == "Disponível" ) {
            //update borrowing info.
            $controlb = new ControlBorrowing();
            $borrowid = $controlb->FetchBorrowIdByKey( $key->getId());
            //atualiza a data de checkin
            $controlb->UpdateCheckin( $borrowid );
            //seta operacao log
            $log->setOperation( 4 );
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." foi DEVOLVIDA.";
        } else {
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." teve atualização dos dados da chave e status: ".$key->getStatus();
        }
    } else if ( $mod_addr ) {
        $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." teve atualização do endereço.";
    } else if ( $mod_key ) {
        $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." teve atualização dos dados da chave.";
    } else if ( $mod_sta ) {
        if ( $key_sta == "Emprestado" && $_POST['select_status'] == "Disponível" ) {
            //update borrowing info.
            $controlb = new ControlBorrowing();
            $borrowid = $controlb->FetchBorrowIdByKey( $key->getId() );
            //atualiza a data de checkin
            $controlb->UpdateCheckin( $borrowid );
            //seta operacao log
            $log->setOperation( 4 );
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." foi DEVOLVIDA.";
        } else {
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key->getGancho()." teve atualização do status: ".$key->getStatus();
        }
    }

    $log->setDescription( $string );
    $controll->CreateLog( $log );
    
}

?>

<!-- Content Wrapper. Contains page content -->
<div class = "content-wrapper">
<!-- Content Header ( Page header ) -->
<section class = "content-header">
<h1>Visualizar & Editar Chave</h1>
</section>

<!-- Main content -->
<section class = "content container-fluid">
<form id = "newkeyform" role = "form" action = "" method = "post">
<div class = "col-md-6">
<div class = "box box-primary">
<div class = "box-header with-border">
<h3 class = "box-title">Informações da chave e imóvel</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<div class = "box-body">
<div class = "col-md-6">
<div class = "form-group">
<label>Código no Sicadi:</label>
<input type = "text" class = "form-control" name = "txtsicadi" placeholder = "Insira o código do imóvel no sistema SICADI" value = "<?php echo $key->getSicadi();?>" required>
</div>
<div class = "form-group">
<label>Categoria:</label>
<select class = "form-control" name = "select_category" required>
<option value = "" disabled selected>Selecione a categoria</option>
<?php
$tipo_array = array( 1 => 'Aluguel', 2 => 'Venda' );
for ( $i = 1; $i <= 2; $i++ ) {
    ?>
    <option <?php
    if ( $key->getTipo() == $tipo_array[$i] ) {
        ?> selected = "selected" <?php
    }
    ?>>
    <?php
    echo $tipo_array[$i];
    ?>
    </option>
    <?php
}
?>
</select>

</div>
</div>
<div class = "col-md-6">
<div class = "form-group">
<label>Código do Gancho:</label>
<input type = "text" class = "form-control" name = "txthook" placeholder = "Insira o código do gancho onde a chave se localiza no painel" value = "<?php echo $key->getGancho();?>" required>
</div>
<div class = "form-group">
<label>Status:</label>
<select class = "form-control" name = "select_status" required>
<option value = "" disabled selected>Selecione o status</option>
<?php
$status_array = array( 1 => 'Disponível', 2 => 'Emprestado', 3 => 'Atrasado', 4 => 'Perdido', 5 => 'Indisponível' );
for ( $i = 1; $i <= 5; $i++ ) {
    ?>
    <option <?php
    if ( $key->getStatus() == $status_array[$i] ) {
        ?> selected = "selected" <?php
    }
    if($status_array[$i] == 'Emprestado'){
        ?> disabled <?php
    }
    ?>>
    <?php
    echo $status_array[$i];
    ?>
    </option>
    <?php
}
?>
</select>
</div>
</div>
<div class = "col-md-12">
<div class = "form-group">
<label>Adicional</label>
<textarea class = "form-control" rows = "3" name = "txtaddon" placeholder = "Alguma informação adicional sobre a chave ou imóvel" style = "text-align:left;"><?php echo $key->getAdicional();
?></textarea>
</div>
</div>
<div class = "col-md-12">
<hr>
</div>
<div class = "col-md-8">
<div class = "form-group">
<label>Rua</label>
<input type = "text" class = "form-control" name = "txtstreet" placeholder = "Insira a rua do imóvel" value = "<?php echo $address->getRua();?>" required>
</div>
<div class = "form-group">
<label>Bairro</label>
<input type = "text" class = "form-control" name = "txtdistrict" placeholder = "Insira o bairro do imóvel" value = "<?php echo $address->getBairro();?>" required>
</div>
</div>
<div class = "col-md-4">
<div class = "form-group">
<label>Número</label>
<input type = "text" class = "form-control" name = "txtnum" placeholder = "Insira o número do imóvel" value = "<?php echo $address->getNumero();?>" required>
</div>

<div class = "form-group">
<label>Cidade</label>
<input type = "text" class = "form-control" name = "txtcity" placeholder = "Insira a cidade do imóvel" value = "<?php echo $address->getCidade();?>" required>
</div>
</div>
<div class = "col-md-12">
<div class = "form-group">
<label>Complemento</label>
<textarea class = "form-control" rows = "3" name = "txtaddon2" placeholder = "Insira alguma informação adicional do endereço (bloco de apartamento, ponto de referência, etc)" style = "text-align:left;">
<?php echo $address->getComplemento();
?>
</textarea>
</div>
</div>
<div class = "text-center">
<button type = "submit" class = "btn btn-warning" name = "btnupdate">Atualizar</button>
</div>
</div>
</div>
</div>
<div class = "col-md-6">
<div class = "box box-primary">
<div class = "box-header with-border">
<h3 class = "box-title">Movimentações da chave</h3>
</div>
<div class = "box-body">
<div class = "col-md-12" style = "overflow-x:auto;">
<table id = "tablemov" class = "table table-striped table-bordered table-hover">
<thead>
<tr>

<!--[date]  [operation] [description]  [nome] -->
<th style = "width: 15%">Data</th>
<th style = "width: 10%">Usuário</th>
<th style = "width: 10%">Operação</th>
<th style = "width: 65%">Descrição</th>

</tr>
</thead>
<tbody>
<?php
$controll->FillMovTable( $key_id );
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</form>
<!-- /.content -->

</section>
</div>

<!-- /.content-wrapper -->

<?php
include_once 'footer.php';
?>

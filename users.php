<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_user.php';

session_start();

if ( $_SESSION['user_email'] == "" ) {
    header( 'location:index.php' );

}
include_once 'header.php';

//error_reporting( 0 );

$control = new ControlUser();
$user = new ModelUser();

if ( isset( $_GET['id'] ) ) {
    $control->DeleteUser( $_GET['id'] );

}

if ( isset( $_POST['btnsave'] ) ) {

    $user->setNome( $_POST['txtname'] );
    $user->setSobrenome( $_POST['txtsurname'] );
    $user->setEmail( $_POST['txtemail'] );
    $user->setSenha( $_POST['txtpassword'] );

    if ( isset( $_POST['txtemail'] ) ) {
        $control->NewUser( $user );
    }

}

if ( isset( $_POST['btnupdate'] ) ) {
    $user->setNome( $_POST['txtname'] );
    $user->setSobrenome( $_POST['txtsurname'] );
    $user->setEmail( $_POST['txtemail'] );
    $user->setSenha( $_POST['txtpassword'] );

    $control->UpdateUser( $user );
}
?>

<!-- Content Wrapper. Contains page content -->
<div class = "content-wrapper">
<!-- Content Header ( Page header ) -->
<section class = "content-header">
<h1>Usuários</h1>

</section>

<!-- Main content -->
<section class = "content container-fluid">

<div class = "box box-info">
<div class = "box-header with-border">
<h3 class = "box-title">Formulário de cadastro</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<form id = "userform" role = "form" action = "" method = "post">
<div class = "box-body">
<!-- CODE TO CHANGE FORM FROM SAVE TO UPDATE -->
<?php
if ( isset( $_POST['btnedit'] ) ) {
    $control->fillForm( $_POST['btnedit'] );
} else {
    //need to add required field in the future
    //removed now because needs javascript to make it conditional
    echo '
                             <div class="col-md-4">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="txtname" placeholder="Insira o nome">
                        </div>

                        <div class="form-group">
                            <label>Sobrenome</label>
                            <input type="text" class="form-control" name="txtsurname" placeholder="Insira o sobrenome">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="txtemail" placeholder="Insira o email">
                        </div>
                        <div class="form-group">
                            <label>Senha</label>
                            <input type="password" class="form-control" name="txtpassword" placeholder="Insira a senha">
                        </div>
                                                  
                        <button type="submit" class="btn btn-info" name="btnsave">Salvar</button>
                        <input type="reset" value ="Limpar dados" class="btn btn-secondary" style="float: right;" >
                        </div>
                            ';
}
?>

<div class = "col-md-8 ">
<div style = "overflow-x:auto;">
<table id = "tableuser" class = "table table-striped ">
<thead>
<tr>
<th>#</th>
<th>Data de cadastro</th>
<th>Nome</th>
<th>Sobrenome</th>
<th>Email</th>
<th>Editar</th>
<th>Apagar</th>
</tr>
</thead>
<tbody>
<?php
$control->FillTable();
?>
</tbody>

</table>
</div>
</div>
</div>
<!-- /.box-body -->

<div class = "box-footer">

</div>
</form>
</div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
<script>
$( document ).ready( function() {
    $( '#tableuser' ).DataTable( {
        "language": {
            "url": "bower_components/datatables.net/pt-BR.json"

        }
    }
);
</script>

<?php
include_once 'footer.php';
?>

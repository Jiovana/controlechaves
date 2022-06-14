<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_user.php';

session_start();

// controle de sessao para impedir acesso nao autorizado
if ( $_SESSION['user_email'] == "" ) {
    header( 'location:index.php' );

}
include_once 'header.php';

//error_reporting( 0 );

$control = new ControlUser();
$user = new ModelUser();

//ao pressionar botao delete, id eh enviado via post
if ( isset( $_GET['id'] ) ) {
    $control->DeleteUser( $_GET['id'] );

}

//ao pressionar botaosave, obtem dados dos inputs  e chama o controle para inserir user no banco
if ( isset( $_POST['btnsave'] ) ) {

    $user->setNome( $_POST['txtname'] );
    $user->setSobrenome( $_POST['txtsurname'] );
    $user->setEmail( $_POST['txtemail'] );
    $user->setSenha( $_POST['txtpassword'] );

    if ( isset( $_POST['txtemail'] ) ) {
        $control->NewUser( $user );
    }

}

//ao pressionar botao update, obtem dados dos inputs e chama o controle para atualizar o user
if ( isset( $_POST['btnupdate'] ) ) {
    $user->setNome( $_POST['txtname'] );
    $user->setSobrenome( $_POST['txtsurname'] );
    $user->setEmail( $_POST['txtemail'] );
    $user->setSenha( $_POST['txtpassword'] );
    $user->setId($_POST['txtid']);

    $control->UpdateUser( $user );
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header ( Page header ) -->
    <section class="content-header">
        <h1>Usuários</h1>

    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <form id="userform" role="form" action="" method="post">
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Formulário de cadastro</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->            
                    <div class="box-body">
<?php
    // controla qual form mostrar de acordo com botao pressionado
    if ( isset( $_POST['btnedit'] ) ) {
        if(isset( $_POST['btnupdate'])){
            $control->ClearForm();
        }else{
            $control->FillForm( $_POST['btnedit'] );
        }
    } else {
    //need to add required field in the future
    //removed now because needs javascript to make it conditional
        $control->ClearForm();
    }
?>
                    </div>
                
            </div>
        </div>

        <div class="col-md-8 ">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Lista de usuários</h3>
                </div>
                <div class="box-body">
                    <div style="overflow-x:auto;">
                        <table id="tableuser" class="table table-striped table-bordered table-hover">
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
        </div>
        </form>
    </section>
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
        $('#tableuser').DataTable({
            "language": {
                "url": "bower_components/datatables.net/pt-BR.json"
            }
        })
    })

</script>

<?php
include_once 'footer.php';
?>

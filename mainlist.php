<?php
   
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

    session_start();

    if($_SESSION['user_email']==""){
        header('location:index.php');           
    }

$control = new ControlKey();



    include_once 'header.php';
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- <section class="content-header">
        <h1>Lista de Chaves</h1>
    </section>-->

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-success">
            <form action="" method="post" name="">
                <div class="box-header with-border">
                    <h3 class="box-title">Lista de Chaves</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <form id="keylistform" role="form" action="" method="post">
                        <!--<div class="col-md-12">
                            <div class="form-group">
                                <div class="input-group margin">
                                    <input type="text" class="form-control" name="txtsearch" placeholder="Insira o código do SICADI, do gancho ou endereco do imovel">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success btn-flat" name="btnsearch">Procurar</button>
                                    </span>
                                </div>
                                <label>
                                    <input type="checkbox" name="check" class="minimal">
                                    Mostrar chaves inativas
                                </label>
                            </div>
                        </div>-->

                        <div class="col-md-12" style="overflow-x:auto;">
                            <table id="tablekeys" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">Num Gancho</th>
                                        <th style="width: 55%">Endereco</th>
                                        <th style="width: 10%">Tipo</th>
                                        <th style="width: 10%">Status</th>
                                        <th style="width: 10%">Emprestar</th>
                                        <th style="width: 10%">Ver e Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $control->FillTable();
                                    
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </form>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    $(document).ready(function() {
                $('#tablekeys').DataTable({
                    "language": {
                        "url": "bower_components/datatables.net/pt-BR.json"

                    }
                });

</script>


<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>

<?php
    include_once 'footer.php';
?>

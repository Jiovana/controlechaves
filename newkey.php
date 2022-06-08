<?php
   
    session_start();

    if($_SESSION['user_email']==""){
        header('location:index.php');           
    }
    include_once 'header.php';
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Nova Chave</h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <form id="newkeyform" role="form" action="" method="post">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Formulário de cadastro de chaves</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código no Sicadi:</label>
                                <input type="text" class="form-control" name="txtname" placeholder="Insira o nome">
                            </div>

                            <div class="form-group">
                                <label>Categoria:</label>
                                <input type="text" class="form-control" name="txtsurname" placeholder="Insira o sobrenome">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código do Gancho:</label>
                                <input type="text" class="form-control" name="txtname" placeholder="Insira o nome">
                            </div>

                            <div class="form-group">
                                <label>Status:</label>
                                <input type="text" class="form-control" name="txtsurname" placeholder="Insira o sobrenome">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Endereço</label>
                            <input type="text" class="form-control" name="txtname" placeholder="Insira o nome">
                        </div>

                        <div class="form-group">
                            <label>Adicional</label>
                            <input type="text" class="form-control" name="txtsurname" placeholder="Insira o sobrenome">
                        </div>

                        <button type="submit" class="btn btn-info" name="btnsave">Salvar</button>
                        <input type="reset" value="Limpar dados" class="btn btn-secondary" style="float: right;">
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

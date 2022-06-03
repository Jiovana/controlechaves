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
        <h1>Lista de Chaves</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Nivel</a></li>
            <li class="active">Aqui</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <div class="box box-warning">
            <form action="" method="post" name="">
                <div class="box-header with-border">
                    <h3 class="box-title">Blank</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                </div>
            </form>
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
    include_once 'footer.php';
?>

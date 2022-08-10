<?php
   
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

session_start();

if($_SESSION['user_email']==""){
    header('location:index.php');           
}

$control = new ControlKey();



include_once 'header.php';

// verifica mensagens de atraso
$control->CheckOverdueMessages();

// reordena ganchos e recarrega a pagina
if ( isset($_POST['btnorder']) ){
    echo '<script>console.log("button pressed");</script>';
    $control->SortHooks("Aluguel");
    echo '<script> window.setTimeout(function(){
        window.location.href = "/controlechaves/src/view/mainlist.php";

    }, 500);
    </script>   ';
}



?>
<script>
    //checa por atrasos quando carrega a pagina
    window.addEventListener("load", () => {
        var req = new XMLHttpRequest();
        req.open("POST", "../etc/overduechecker.php");
        req.onload = function() {
            console.log(this.response);
        };
        req.send();
    });

</script>



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
                   <div class="col-md-6">
                    <h3 class="box-title">Lista de Chaves - Imóveis para locação</h3>
                    </div>
                    <div class="col-md-6" style="text-align:right;">
                        <button type="submit" class="btn btn-success" name="btnorder">Ordenar Ganchos</button>
                    </div>

                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <div class="box-body">
                    <form id="keylistform" role="form" action="" method="post">
                        <div class="col-md-12" style="overflow-x:auto;">
                            <table id="tablekeys" class="table table-bordered table-hover">
                                <thead>
                                    <tr style="background-color:#9FF781; ">
                                        <th style="width: 6% ; ">Gancho</th>
                                        <th style="width: 54%">Endereço</th>
                                        <th style="width: 10%">Tipo</th>
                                        <th style="width: 10%">Status</th>
                                        <th style="width: 10%">Emprestar ou Devolver</th>
                                        <th style="width: 10%">Ver e Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $control->FillTable("Aluguel");   
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
                "url": "../../bower_components/datatables.net/pt-BR.json"
            }
        })
    })

</script>


<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });

</script>


<!-- ajax code for retrieve button -->
<script>
    $(document).ready(function() {
        $('.btnretrieve').click(function() {
            var tdh = $(this);
            var id = $(this).attr("id");
            swal({
                    title: "Você deseja devolver a chave?",
                    //text: "Once deleted, you can't recover this product!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: '../control/ajaxretrievekey.php',
                            type: 'post',
                            data: {
                                keyid: id,
                                op: 'ret'
                            },
                            success: function(data) {
                                console.log(data);
                                // update table or refresh page
                                location.reload(true);
                            }
                        });

                        swal("Chave devolvida com sucesso.", {
                            icon: "success",
                        });
                    } else {
                        swal("A chave não foi devolvida.");
                    }
                });
        });
    });

</script>


<?php
    include_once 'footer.php';
?>

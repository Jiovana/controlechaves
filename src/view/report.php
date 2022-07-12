<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';
   
    session_start();

    if($_SESSION['user_email']==""){
        header('location:index.php');           
    }
    include_once 'header.php';


$controll = new ControlLog();

$current_date =  date('d/m/Y', time());
$month_date =   date('d/m/Y', strtotime("-1 month", time()));
                     
                     
echo '<script>console.log("teste: '.$month_date.'");</script>';

$dates = explode(' - ', $_POST['daterange']);

    if(isset($_POST['btnsearch'])){
        echo '<script>console.log("teste: '.$_POST['daterange'].'");</script>';
        
    }


?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Relatórios</h1>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">

        <!--------------------------
        | Your Page Content Here |
        -------------------------->
        <form id="reportform" action="" method="post" role="form">
        <div class="box box-primary">
            
                <div class="box-header with-border">
                    <h3 class="box-title">Tabela de Movimentações</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                        <div class="col-md-6">
                            <!-- Date range -->
                            <div class="form-group">
                                <label>Selecione o periodo:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="reservation"
                                    name="daterange">
                                </div>
                                <!-- /.input group -->
                            </div>

                        </div>
                        <div class="col-md-6">

                            <div class="form-group">
                                <label>Filtrar operação:</label>
                                <select class="form-control" name="select_category" id="reqcat">
                                    <option selected>Todas</option>
                                    <option>Criação</option>
                                    <option>Alteração</option>
                                    <option>Empréstimo</option>
                                    <option>Devolução</option>
                                </select>
                            </div>



                        </div>

                        <div class="col-md-6 col-sm-6">
                           
                           <button type="submit" class="btn btn-block btn-social  btn-info btnsearch" name="btnsearch" style="width:150px;margin-bottom:10px;margin-left:35%;"><i class="fa fa-search"></i>Procurar </button>
                           
                        </div>
                        
                        
                        <div class="col-md-6 col-sm-6" align="right">
                           
                           <button type="submit" class="btn btn-block btn-social  btn-default btnprint" name="btnprint" style="width:150px;margin-bottom:10px;margin-right:35%;"><i class="fa fa-print"></i>Imprimir</button>
                        </div>

                 
                    <br>
               
                        <div class="col-md-12" style="overflow-x:auto;">
                            <table id="tablemov" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <!--[date]  [user] [key] [operation] [description]   -->
                                        <th style="width: 10%">Data</th>
                                        <th style="width: 10%">Usuário</th>
                                        <th style="width: 10%">Chave</th>
                                        <th style="width: 10%">Operação</th>
                                        <th style="width: 60%">Descrição</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    //is only loading the table when the search button is pressed.
                                    if (isset($_POST['btnsearch']))
$controll->FillReportTable($dates[0], $dates[1]);
?>
                                </tbody>
                            </table>
                        </div>
               
                </div>
           
        </div>
     </form>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    var start = moment().subtract(30, 'days');
    var end = moment();
    $('#reservation').daterangepicker({
        startDate: start,
        endDate: end,
        "locale": {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "De",
            "toLabel": "Até",
            "customRangeLabel": "Custom",
            "daysOfWeek": [
                "Dom",
                "Seg",
                "Ter",
                "Qua",
                "Qui",
                "Sex",
                "Sáb"
            ],
            "monthNames": [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
            "firstDay": 0
        }
    });

</script>


<script>

    window.addEventListener("load", () => {
        
      $('#tablekeys').append(<?php $controll->FillReportTable($current_date, $month_date);?>);
    });

</script>

<?php
    include_once 'footer.php';
?>

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

                     

  $datesfromlog = array();

    if(isset($_POST['btnsearch'])){
        $dates = explode(' - ', $_POST['daterange']);
        echo '<script>console.log("teste: '.$_POST['daterange'].'");</script>';
        //send the date range via get 
        //parse_url
        
        $datesfromlog = $controll->RetrieveReportDates($dates[0],$dates[1]);
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
                    <h3 class="box-title" id="title">Tabela de Movimentações  </h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">
                    <div class="col-md-6">
                        <!-- Date range -->
                        <div class="form-group">
                            <label>Selecione o período:</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="reservation" name="daterange">
                            </div>
                            <!-- /.input group -->
                        </div>

                    </div>
                   

                    <div class="col-md-3" style="display:flex;justify-content:center;">
                        
                        <button type="submit" class="btn btn-block btn-social  btn-info btnsearch" name="btnsearch" id="btnsearch" style="width:150px;margin-bottom:10px;margin-top:25px;" data-toggle="tooltip" title="Procurar movimentações do período"><i class="fa fa-search"></i>Procurar</button>                       

                    </div>


                    <div class="col-md-3" style="display:flex;justify-content:center;" >
                        <a name="btnprint" class="btn btn-block btn-social  btn-default btnprint" role="button" style="width:150px;margin-bottom:10px;margin-top:25px;" data-toggle="tooltip"  
<?php if (!isset($_POST['btnsearch'])){
     echo 'title="Clique em procurar primeiro!" disabled href="#"';
} else{
    echo ' title="Imprimir a seleção atual"';
    echo 'href="../etc/pdfreport.php?daterange=';
    if (!empty($datesfromlog)){
        echo $datesfromlog[0].'-'.$datesfromlog[1];
     }
    echo '"';
}
                           ?> target="_blank"><i class="fa fa-print"></i>Imprimir</a>   
                        
                    </div>
                    
                      
                        


                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <hr>
                    </div>

                    <div class="col-md-12" style="overflow-x:auto;">
                        <?php
                            if (isset($_POST['btnsearch'])){
                                
                                
                                echo '
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
                            ';
                            $datesfromlog = $controll->FillReportTable($dates[0], $dates[1]);
                                                     
echo '<script>console.log("fromlog: '.$datesfromlog[0].'");</script>';
                                echo '
                                        </tbody>
                                        <tfoot>
                                        <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                       
                                        </tr></tfoot>
                                    </table>                               
                                ';  
                                
                                echo '<script>
                                var heading = document.getElementById("title");
                                heading.innerHTML = "<h4>Tabela de Movimentações  de '.$datesfromlog[0].' a '.$datesfromlog[1].'</h4>";
                                
                                
                                
                                </script>';
                               
                            } else {
                                echo '
                                <div align="center">
                                    <p> Pressione o botão "Procurar" para mostrar os dados do período selecionado. </p>
                                    </div>
                                
                                ';
                                
                            }
                        
                        
                        ?>


                    </div>

                </div>

            </div>
        </form>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
    //need to see a way of keeping the previous selected date when the table loads and no this default data
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
<?php 
if (isset($_POST['btnsearch'])){
    echo '
    <script>
        var datepicker = document.getElementById("reservation");
        datepicker.value =  " '. $datesfromlog[0]. ' - '.$datesfromlog[1].' ";
    </script>
    ';
}



?>

<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();

        $('#tablemov').DataTable({
            "language": {
                "url": "../../bower_components/datatables.net/pt-BR.json"
            },
            initComplete: function() {
                this.api()
                    .columns()
                    .every(function() {
                        var column = this;
                        var select = $('<select><option value=""></option></select>')
                            .appendTo($(column.footer()).empty())
                            .on('change', function() {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());

                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column
                            .data()
                            .unique()
                            .sort()
                            .each(function(d, j) {
                                select.append('<option value="' + d + '">' + d + '</option>');
                            });
                    });
            },
        })
        
   // $(document).on('click', '.btnsearch', function() {
   //      $('#reservation').value(<?php //echo $_POST['daterange']; ?>)
   // });
        
    });

</script>

<?php
    include_once 'footer.php';
?>

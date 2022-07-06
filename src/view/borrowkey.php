<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_requester.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_borrowing.php';

session_start();

if ( $_SESSION['user_email'] == "" ) {
    header( 'location:index.php' );
}

include_once 'header.php';

date_default_timezone_set('America/Sao_Paulo');

$controlk = new ControlKey();
$controll = new ControlLog();
$controlr = new ControlRequester();
$controlb = new ControlBorrowing();
$controla = new ControlAddress();

$key = new ModelKey();
$log = new ModelLog();
$requester = new ModelRequester();
$borrowing = new ModelBorrowing();


if ( isset( $_POST['btnsave'] ) ) {
      
    if (!empty($_POST['txtnome']) && !empty($_POST['select_category']) && !empty($_POST['checkoutdate']) && !empty($_POST['checkindate']) && !empty($_POST['keyid'])){
             
          
        // inserir primeiro informacoes do requerente
        //testa se requrente ja existe (opcao do botao de pesquisa)
    
        if (empty($_POST['txtid'])){       
            $requester->setNome($_POST['txtnome']);
            $requester->setEmail($_POST['txtemail']);
            $requester->setDocumento($_POST['txtdocument']);
            $requester->setDdd($_POST['txtddd']);
            $requester->setTelefone($_POST['txtphone']);
            $requester->setTipo($_POST['select_category']);

            $req_id = $controlr->NewRequester($requester);
            ///////////////////////////////////////////////
            // obter info de borrowing
            // borrowing has : data_checkin, data_checkout, requester_id, user_id
            $borrowing->setRequester_id($req_id);
        } else {
            $borrowing->setRequester_id($_POST['txtid']);
        }   

        $borrowing->setUser_id($_SESSION['user_id']);


        $checkout = date('Y-m-d H:i:s', strtotime (str_replace ('/', '-', $_POST['checkoutdate'])));
        //echo "<script>console.log('test: ". $checkout."');</script>";

        $checkin = date('Y-m-d H:i:s', strtotime (str_replace ('/', '-', $_POST['checkindate'])));
        //echo "<script>console.log('test: ". $checkin."');</script>";

        $borrowing->setData_checkout($checkout);
        $borrowing->setData_checkin($checkin);

        $borrow_id = $controlb->NewBorrowing($borrowing);

        ////////////////////////////////////////////////
        // registrar relacionamento keys_borrowing
        // keys_borrowing has: borrowing_id, keys_id

        //obter array com conteudo das chaves. 
        $arr_keygancho = array();
        //testar antes se existe ...
        if (isset($_POST['keyhook'])){
            $arr_keygancho = $_POST['keyhook'];
        }
        
        if (isset($_POST['listgancho'])){
            array_push($arr_keygancho, $_POST['listgancho']);
        }
       
        
        //array_push($arr_keygancho, $_POST['bkeygancho']);
        //$ble = json_encode($_POST['bkeygancho']);
        //echo '<script>console.log("Gancho: '.$ble.'");</script>';
        foreach ($arr_keygancho as $gan){
            echo '<script>console.log("Gancho: '.$gan.'");</script>';
        }
        
        $arr_keyid = $_POST['keyid'];
        //$arr_keysicadi = $_POST['keysicadi'];
        //$arr_keyaddress = $_POST['keyaddress'];
        //$arr_keycategory = $_POST['keycategory'];

        if($borrowing->getRequester_id() != null){
            for($i=0; $i<count($arr_keyid); $i++){
              $controlb->NewKeysBorrowing($arr_keyid[$i], $borrow_id);
               
              //need to update the status of the selected key.
              // labels array( 1 => 'Disponível', 2 => 'Emprestado', 3 => 'Atrasado', 4 => 'Perdido', 5 => 'Indisponível');
              $controlk->UpdateStatus($arr_keyid[$i], 2);            
              //inserir o log de emprestar chave   
              $log->setKeys_id($arr_keyid[$i]);
              $log->setUser_id($_SESSION['user_id']);
              //operation pode ser: 1 - criacao, 2 - alteracao,
              // 3 - emprestimo, 4 - devolucao
              $log->setOperation(3);

              $string = "Chave nº Gancho: ".$arr_keygancho[$i]." foi EMPRESTADA para ".$_POST['txtnome']." até ".$_POST['checkindate'].".";    
              $log->setDescription($string);

              $controll->CreateLog($log);
           }
        }

        echo '<script type="text/javascript">
                jQuery(function validation(){
                    swal({
                        title: "Sucesso!",
                        text: "Operação registrada",
                        icon: "success",
                        button: "Ok",
                    });
                });
            </script>';
        echo '<script> window.setTimeout(function(){
        window.location.href = "/controlechaves/src/view/mainlist.php";

    }, 2000);
    </script>   ';
    } 
    else {
        echo '<script type="text/javascript">
                jQuery(function validation(){
                    swal({
                        title: "Erro!",
                        text: "Informe no mínimo: nome e categoria do requerente, uma chave da lista, e as datas de retirada e devolução.",
                        icon: "error",
                        button: "Ok",
                    });
                });
            </script>';
    }
}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content container-fluid">
        <form id="newkeyform" role="form" action="" method="post">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Emprestar Chaves</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="col-md-12">
                            <h4>Dados do requerente</h4>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nome:</label>
                                <input type="text" class="form-control" name="txtnome" id="reqnome" placeholder="Insira o nome completo do requerente">
                                <input type="hidden" class="form-control" name="txtid" id="reqid">
                            </div>

                            <div class="form-group">
                                <label>Categoria:</label>
                                <select class="form-control" name="select_category" id="reqcat">
                                    <option value="" disabled selected>Selecione a categoria</option>
                                    <option>Cliente</option>
                                    <option>Diretoria</option>
                                    <option>Manutenção</option>
                                    <option>Marketing</option>
                                    <option>Prestador de serviço</option>
                                    <option>Vistoria</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" class="form-control" name="txtemail" id="reqmail" placeholder="Insira o email do rquerente">
                            </div>

                            <div class="form-group">
                                <label>DDD:</label>
                                <input type="text" class="form-control" name="txtddd" id="reqdd" placeholder="Insira o DDD do telefone">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID:</label>
                                <input type="number" class="form-control" name="txtdocument" id="reqdoc" placeholder="Insira o numero do documento">
                            </div>

                            <div class="form-group">
                                <label>Telefone:</label>
                                <input type="number" class="form-control" name="txtphone" id="reqtel" placeholder="Insira o numero do telefone">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="button" class="btn btn-primary btnsearch" name="btnsearch" style="margin-top: 25px !important;" data-toggle="tooltip" title="Pesquisar por nome ou email">Pesquisar</button>

                            <input type="reset" value="Limpar dados" class="btn btn-secondary" style="margin-top: 40px ">
                        </div>
                        <div class="col-lg-12">
                            <hr>
                        </div>
                        <div class="box-body">
                            <div class="col-md-12">
                                <h4>Chaves a emprestar</h4>
                                <div style="overflow-x:auto;">
                                    <table id="tablekeys" class="table table-bordered table-hover ">
                                        <thead>
                                            <tr>
                                                <th style="display:none;"></th>
                                                <th style="width: 10% ; ">Informe Gancho</th>
                                                <th style="width: 10% ; ">Informe Sicadi</th>
                                                <th style="width: 50% ; ">Endereço</th>
                                                <th style="width: 20% ; ">Categoria</th>
                                                <th style="width: 10% ; ">
                                                    <center><button type="button" name="add" class="btn btn-success btn-sm btnadd"><span class="glyphicon glyphicon-plus"></span></button></center>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php  // this table needs to show only the keys AVAILABLE FOR BORROWING, allowing search by the hook or sicadi and autocomplement of the fields
      // also, it needs to autocomplement automatically from a key selected from the list.
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <hr>
                            <div class="col-md-4">
                                <!-- Date -->
                                <div class="form-group">
                                    <label>Date de Retirada</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="datepicker1" name="checkoutdate" value="<?php echo date("d/m/Y H:i");?>" data-date-format="yyyy-mm-dd hh:mm:ss">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-4">
                                <!-- Date -->
                                <div class="form-group">
                                    <label>Data de Devolução</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="datepicker2" name="checkindate" value="<?php echo date("d/m/Y H:i");?>" data-date-format="yyyy-mm-dd hh:mm:ss">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <button type="submit" class="btn btn-success btnsave" name="btnsave" style="margin-top: 25px !important;">Salvar</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
        <!-- /.content -->

    </section>
</div>

<script>
    //Date picker
    $(function() {
        $.datetimepicker.setLocale('pt-BR');
        $('#datepicker1').datetimepicker({
            format: 'd/m/Y H:i'

        });
        $('#datepicker2').datetimepicker({
            format: 'd/m/Y H:i'
        });
    });
</script>

<script type="text/javascript">
    $(document).on('click', '.btnadd', function() {
        var op = '';
        var html = '';
        html += '<tr>';

        html += '<td style="display:none;"><input type="hidden" class="form-control keyid" name="keyid[]" readonly></td>';

        html += '<td style="display:none;"><input type="hidden" class="form-control keyhook" name="keyhook[]" readonly></td>';

        html += '<td style="padding:0px;"><select class="form-control keygancho" name="keygancho[]" ><option value="">Selecione</option><?php echo $controlk->Fill_Gancho();?></select></td>';

        html += '<td style="padding:0px;"><select class="form-control keysicadi" name="keysicadi[]" ><option value="">Selecione</option><?php echo $controlk->Fill_Sicadi();?></select></td>';

        html += '<td style="padding:0px;"><input type="text" class="form-control keyaddress" name="keyaddress[]" readonly></td>';

        html += '<td style="padding:0px;"><input type="text" class="form-control keycategory" name="keycategory[]" readonly></td>';

        html += '<td style="padding:0px;"><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td>';

        $('#tablekeys').append(html);

        //Initialize Select2 Elements
        $('.keygancho').select2()
        $('.keysicadi').select2()


        if ($('.keygancho').focus()) {
            op = "gancho";
        }
        if ($('.keysicadi').focus()) {
            op = "sicadi";
        }
        var change1 = false;
        var change2 = false;
        $('.keygancho').on('change', function(e) {
            if (!change1) {
                var keyid = this.value;
                var tr = $(this).parent().parent();
                $.ajax({
                    url: '../control/ajaxgetkeyinfo.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: keyid
                    },
                    success: function(data) {
                        console.log(data);
                        changeSelect2();
                        tr.find(".keysicadi option[value=" + data["id"] + "]").attr('selected', 'selected').change();

                        tr.find(".keyid").val(data["id"]);

                        tr.find(".keyhook").val(data["gancho"]);
                        tr.find(".keyaddress").val(data["endereco_string"]);
                        tr.find(".keycategory").val(data["tipo"]);

                    },
                    error: function(data) {
                        console.log('Error: ', data)
                    }
                });
            }
            change1 = false;



        });

        $('.keysicadi').on('change', function(e) {
            if (!change2) {
                var keyid = this.value;
                var tr = $(this).parent().parent();
                $.ajax({
                    url: '../control/ajaxgetkeyinfo.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: keyid
                    },
                    success: function(data) {
                        console.log(data);
                        changeSelect1();
                        tr.find(".keygancho option[value=" + data["id"] + "]").attr('selected', 'selected').change();
                        tr.find(".keyid").val(data["id"]);
                        tr.find(".keyhook").val(data["gancho"]);
                        tr.find(".keyaddress").val(data["endereco_string"]);
                        tr.find(".keycategory").val(data["tipo"]);

                    },
                    error: function(data) {
                        console.log('Error: ', data)
                    }
                });
            }
            change2 = false;
        });

        function changeSelect1() {
            change1 = true;
            $('keygancho').change();
        }

        function changeSelect2() {
            change2 = true;
            $('keysicadi').change();
        }
    });

    // to remove lines from the table dynamically
    $(document).on('click', '.btnremove', function() {
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.btnsearch', function() {
        if ($('#reqnome').val() || $('#reqmail').val()) {
            $.ajax({
                url: '../control/control_requester.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    reqnome: $("#reqnome").val(),
                    reqemail: $("#reqmail").val(),
                    op: 'request'
                },
                success: function(data) {
                    console.log(data);
                    if (data["empty"] == false) {
                        $("#reqnome").val(data["nome"]);
                        $("#reqid").val(data["id"]);
                        $("#reqmail").val(data["email"]);
                        $("#reqdd").val(data["ddd"]);
                        $("#reqdoc").val(data["documento"]);
                        $("#reqtel").val(data["telefone"]);

                        $("select#reqcat option").filter(function() {
                            return $(this).text() == data["tipo"];
                        }).prop('selected', true);
                    } else {
                        jQuery(function validation() {
                            swal({
                                title: "Sem resultados!",
                                text: "Nenhum requerente encontrado com os dados informados.",
                                icon: "error",
                                button: "Ok",
                            });
                        });

                    }


                },
                error: function(data) {
                    console.log('Error: ', data);
                    jQuery(function validation() {
                        swal({
                            title: "Sem resultados!",
                            text: "Nenhum requerente encontrado com os dados informados.",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                }
            });
        } else {
            jQuery(function validation() {
                swal({
                    title: "Aviso!",
                    text: "Informe o nome ou email do requerente para pesquisar.",
                    icon: "warning",
                    button: "Ok",
                });
            });
        }


    });

    $('[data-toggle="tooltip"]').tooltip();
</script>

<script>
    // I need to add one row to the table with the data of the selected key
    // we can add a row with js?

    $(document).ready(function() {
        var keyid = <?php echo (isset($_GET["id"])?$_GET["id"]:-1); ?>;

        if (keyid != -1) {
            <?php   
            if (isset($_GET['id'])){
                $bkey = $controlk->GetKeyModel($_GET['id']); 
                $baddress = $controla->GetAddressString($bkey->getEnderecoId()); 
        ?>

            // $.post("borrowkey.php",)

            var html = '';
            html += '<tr>';

            html += '<td style="display:none;"><input type="hidden" class="form-control keyid" name="keyid[]" value="<?php echo $bkey->getId(); ?>" readonly></td>';

            html += '<td style="padding:0px;"><input type="text" class="form-control bkeygancho" name="listgancho" value="<?php echo $bkey->getGancho(); ?>" readonly></td>';

            html += '<td style="padding:0px;"><input type="text" class="form-control bkeysicadi" name="listsicadi" value="<?php echo $bkey->getGancho(); ?>" readonly></td>';

            html += '<td style="padding:0px;"><input type="text" class="form-control keyaddress" name="keyaddress[]" value="<?php echo $baddress; ?>" readonly></td>';

            html += '<td style="padding:0px;"><input type="text" class="form-control keycategory" name="keycategory[]" value="<?php echo $bkey->getTipo(); ?>" readonly></td>';

            html += '<td style="padding:0px;"><center><button type="button" name="remove" class="btn btn-danger btn-sm btnremove"><span class="glyphicon glyphicon-remove"></span></button></center></td>';

            $('#tablekeys').append(html);

            <?php } ?>
        }
    });
</script>


<!-- /.content-wrapper -->

<?php
include_once 'footer.php';
?>
<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';

session_start();

if ( $_SESSION['user_email'] == "" ) {
    header( 'location:index.php' );

}

include_once 'header.php';


date_default_timezone_set('America/Sao_Paulo');

$controlk = new ControlKey();
$controla = new ControlAddress();
$controll = new ControlLog();

$key = new ModelKey();
$address = new ModelAddress();
$log = new ModelLog();



//ao pressionar o botao de salvar, preenche objetos address e key, inserindo primeiro o endereco, para obter o id e entao inserir os dados da chave. Apos insercao redireciona para  a lista de chaves
if ( isset( $_POST['btnsave'] ) ) {
    // inserir primeeiro informacoes do endereco
    $address->setNumero( $_POST['txtnum'] );
    $address->setBairro( $_POST['txtdistrict'] );
    $address->setRua( $_POST['txtstreet'] );
    $address->setCidade( $_POST['txtcity'] );
    if($_POST['txtaddon2'] != ""){
        $address->setComplemento( $_POST['txtaddon2'] ); 
    }

    $addr_id = $controla->NewAddress( $address );

    //inserir dados da chave
    $key->setSicadi( $_POST['txtsicadi'] );
    $key->setGancho( $_POST['txthook'] );
    $key->setTipo( $_POST['select_category'] );
    $key->setStatus( $_POST['select_status'] );
    $key->setAdicional( $_POST['txtaddon'] );
    $key->setEnderecoId( $addr_id );

    $keyid = $controlk->NewKey( $key );
    
    //inserir o log de criacao da chave   
    $log->setKeys_id($keyid);
    $log->setUser_id($_SESSION['user_id']);
    //operation pode ser: 1 - criacao, 2 - alteracao,
    // 3 - emprestimo, 4 - devolucao
    $log->setOperation(1);
    
    $string = "Chave nº Gancho: ".$key->getGancho()." foi adicionada no sistema com status: ".$key->getStatus().".";    
    $log->setDescription($string);
    
    $controll->CreateLog($log);
    

    echo '<script> window.setTimeout(function(){
        window.location.href = "/controlechaves/src/view/mainlist.php";

    }, 3000);
    </script>   '; 
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
                                <input type="text" class="form-control" name="txtnome" placeholder="Insira o nome completo do requerente" required>
                            </div>
                            <div class="form-group">
                                <label>Categoria:</label>
                                <select class="form-control" name="select_category" required>
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
                                <input type="email" class="form-control" name="txtemail" placeholder="Insira o email do rquerente" required>
                            </div>

                            <div class="form-group">
                                <label>DDD:</label>
                                <input type="text" class="form-control" name="txtddd" placeholder="Insira o DDD do telefone" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>ID:</label>
                                <input type="number" class="form-control" name="txtdocument" placeholder="Insira o numero do documento" required>
                            </div>

                            <div class="form-group">
                                <label>Telefone:</label>
                                <input type="number" class="form-control" name="txtphone" placeholder="Insira o numero do telefone" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary" name="btnsearch" style="margin-top: 25px !important;">Pesquisar</button>
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
                                        <input type="text" class="form-control pull-right" id="datepicker1" name="checkoutdate" value="<?php echo date("d/m/Y H:i");?>" data-date-format="dd/mm/yyyy H:i">
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
                                        <input type="text" class="form-control pull-right" id="datepicker2" name="checkindate" value="<?php echo date("d/m/Y H:i");?>" data-date-format="dd/mm/yyyy H:i">
                                    </div>
                                    <!-- /.input group -->
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <button type="submit" class="btn btn-success" name="btnsave" style="margin-top: 25px !important;">Salvar</button>
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
            if(!change1){
                var keyid = this.value;
                var tr = $(this).parent().parent();
                $.ajax({
                    url: 'ajaxgetkeyinfo.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: keyid
                    },
                    success: function(data) {
                        console.log(data);
                        changeSelect2();
                        tr.find(".keysicadi option[value=" + data["id"] + "]").attr('selected', 'selected').change();
                        
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
            if(!change2){
                var keyid = this.value;
                var tr = $(this).parent().parent();
                $.ajax({
                    url: 'ajaxgetkeyinfo.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        id: keyid
                    },
                    success: function(data) {
                        console.log(data);
                       changeSelect1();
                        tr.find(".keygancho option[value=" + data["id"] + "]").attr('selected', 'selected').change();
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
        
        function changeSelect1(){
            change1 = true;
            $('keygancho').change();
        }
        function changeSelect2(){
            change2 = true;
            $('keysicadi').change();
        }
    });

    // to remove lines from the table dynamically
    $(document).on('click', '.btnremove', function() {
        $(this).closest('tr').remove();
    });

</script>


<!-- /.content-wrapper -->

<?php
include_once 'footer.php';
?>

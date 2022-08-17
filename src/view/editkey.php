<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_user.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_borrowing.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_hook.php';

session_start();

if ( $_SESSION['user_email'] == "" ) {
    header( 'location:index.php' );
}

include_once 'header.php';

$controlk = new ControlKey();
$controla = new ControlAddress();
$controll = new ControlLog();
$controlh = new ControlHook();

$key_id = $_GET['id'];
$key = $controlk->GetKeyModel( $key_id );

$addr_id = $key->getEnderecoId();
$address = $controla->GetAddressModel( $addr_id );

$log = new ModelLog();

//obter valores nao-modificados para comparacao
$addr_num = $address->getNumero();
$addr_bai = $address->getBairro();
$addr_rua = $address->getRua();
$addr_cid = $address->getCidade();
$addr_com = $address->getComplemento();

$key_sic = $key->getSicadi();
$key_gan = $controlk->FetchHookCode( $key->getId() );
$key_tip = $key->getTipo();
$key_sta = $key->getStatus();
$key_adi = $key->getAdicional();
$mod = false; // flag to see if the fields were modified by user
//ao pressionar o botao de atualizar
if ( isset( $_POST['btnupdate'] ) ) {
    //1. atualizar o endereco
    $address->setNumero( $_POST['txtnum'] );
    $address->setBairro( $_POST['txtdistrict'] );
    $address->setRua( $_POST['txtstreet'] );
    $address->setCidade( $_POST['txtcity'] );
    if ( $_POST['txtaddon2'] == "" ) {
        $address->setComplemento( null );
    } else {
        $address->setComplemento( $_POST['txtaddon2'] );
    }

    $controla->UpdateAddress( $address );

    //2. atualizar a chave
    $key->setSicadi( $_POST['txtsicadi'] );
    // $key->setGancho( $_POST['txthook'] );
    $key->setTipo( $_POST['select_category'] );
    $key->setStatus( $_POST['select_status'] );
    $key->setAdicional( $_POST['txtaddon'] );
    $key->setEnderecoId( $addr_id );

    $free = 0; // counter of free hooks
    $fail = false; // flag to see if hooks are available
   
    //se o codigo foi modificado ou se esta salvo como manual e foi alterado para ser automatico, precisa verificar tudo

    if ( (isset($_POST['select_hook']) && $key_gan != $_POST['select_hook'] ) || ( $key->getGanchoManual() == true &&  isset( $_POST['checkhook'] ) ) ){
        echo '<script>console.log("inside first if");</script>';
        $mod = true;
        if ( isset( $_POST['checkhook'] ) ) {
            // opcao automatica
            echo '<script>console.log("inside second if");</script>';
            //1. verify if we have available hooks of the chosen type
             echo '<script>console.log("category: '.$_POST['select_category'].'");</script>';
            $free = $controlh->SearchFreeHooks( $_POST['select_category'] );
            if ( $free > 0 ) {
                echo '<script>console.log("hooks: '.$free.'");</script>';

                //2. sort the key addresses alphabetically and set the hook codes sequentially according to the sorted vector
                $controlk->SortHooks( $_POST['select_category'] );
                $key->setGanchoManual( false );
                $fail = false;
            } else {
                echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Nenhum gancho disponível!",
                            text: "Não foi possível atualizar a chave. Todos os ganchos estão ocupados, mas você pode escolher um código manualmente no seletor.",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
                $fail = true;
            }

        } else {
            // opcao manual
            echo '<script>console.log("'.$_POST['select_hook'].'");</script>';
            $hook_model = $controlh->FetchHookByCode($_POST['select_hook'] );
            echo '<script>console.log("'.$hook_model->getId().'");</script>';
            $key->setGanchoId($hook_model->getId());
            $key->setGanchoManual( true );
            $controlk->UpdateHookId($hook_model->getId(), $key->getId());
            $controlh->ModifyUsado($hook_model->getId(),true);
           $key_gan = $hook_model->getCodigo();
            $fail = false;
            $controlk->SortHooks( $_POST['select_category'] );
        }
    }

    if ( !$fail ) {
         
        echo '<script>console.log("'. $key->getGanchoId().'");</script>';
        
        $controlk->UpdateKey( $key );

        //3. inserir o log de atualizacao da chave
        $string = "";
        //3.1 - verificar se campos de endereco mudaram
        $mod_addr = false;
        if ( $addr_num != $_POST['txtnum'] or $addr_bai != $_POST['txtdistrict'] or $addr_rua != $_POST['txtstreet'] or $addr_cid != $_POST['txtcity'] or $addr_com != $_POST['txtaddon2'] ) {
            $mod_addr = true;
        }
        // teste para reordenar ganchos caso endereco mude
        if($mod_addr && 
           !$key->getGanchoManual()){
        echo '<script>console.log("inside if addr: '.$mod_addr.'");</script>';
        $controlk->SortHooks( $_POST['select_category'] );
        }
                
        
        
        //3.2 - verificar se campos da chave mudaram, exceto status
        echo '<script>console.log("mod: '.$mod.'");</script>';
        $mod_key = false;
        if ( $key_sic != $_POST['txtsicadi'] or $mod  or $key_tip != $_POST['select_category'] or $key_adi != $_POST['txtaddon'] ) {
            $mod_key = true;
        }
        //3.3 - verificar se status mudou
        $mod_sta = false;
        if ( $key_sta != $_POST['select_status'] ) {
            $mod_sta = true;
        }

        //3.5 - preencher obj log e inserir no banco.
        $log->setKeys_id( $key->getId() );
        $log->setUser_id( $_SESSION['user_id'] );

        //operation pode ser: 1 - criacao, 2 - alteracao,
        // 3 - emprestimo, 4 - devolucao, 5 - exclusao
        $log->setOperation( 2 );

        //3.6 - comparar as tres flags entre si.
        if ( $mod_addr and $mod_key and $mod_sta ) {
            //verifica se devolucao
            if ( ( $key_sta == "Emprestado" || $key_sta == "Atrasado" ) && $_POST['select_status'] == "Disponível" ) {
                //update borrowing info.
                $controlb = new ControlBorrowing();
                $borrowid = $controlb->FetchBorrowIdByKey( $key->getId() );
                //atualiza a data de checkin e status
                $controlb->UpdateCheckin( $borrowid );
                $controlb->DeactiveKeysBorrow( $key->getId() );
                //seta operacao log
                $log->setOperation( 4 );
                $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." foi DEVOLVIDA.";
            } else {
                $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." teve atualização do endereço, dados da chave e status: ".$key->getStatus();
            }
        } else if ( $mod_addr and $mod_key ) {
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." teve atualização do endereço e dados da chave.";
        } else if ( $mod_addr and $mod_sta ) {
            if ( ( $key_sta == "Emprestado" || $key_sta == "Atrasado" ) && $_POST['select_status'] == "Disponível" ) {
                //update borrowing info.
                $controlb = new ControlBorrowing();
                $borrowid = $controlb->FetchBorrowIdByKey( $key->getId() );
                //atualiza a data de checkin e status
                $controlb->UpdateCheckin( $borrowid );
                $controlb->DeactiveKeysBorrow( $key->getId() );
                //seta operacao log
                $log->setOperation( 4 );
                $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." foi DEVOLVIDA.";
            } else {
                $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." teve atualização do endereço e status: ".$key->getStatus();
            }
        } else if ( $mod_key and $mod_sta ) {
            if ( $key_sta == "Emprestado" && $_POST['select_status'] == "Disponível" ) {
                //update borrowing info.
                $controlb = new ControlBorrowing();
                $borrowid = $controlb->FetchBorrowIdByKey( $key->getId() );
                //atualiza a data de checkin e status
                $controlb->UpdateCheckin( $borrowid );
                $controlb->DeactiveKeysBorrow( $key->getId() );
                //seta operacao log
                $log->setOperation( 4 );
                $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." foi DEVOLVIDA.";
            } else {
                $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." teve atualização dos dados da chave e status: ".$key->getStatus();
            }
        } else if ( $mod_addr ) {
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." teve atualização do endereço.";
        } else if ( $mod_key ) {
            $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." teve atualização dos dados da chave.";
        } else if ( $mod_sta ) {
            if ( ( $key_sta == "Emprestado" || $key_sta == "Atrasado" ) && $_POST['select_status'] == "Disponível" ) {
                //update borrowing info.
                $controlb = new ControlBorrowing();
                $borrowid = $controlb->FetchBorrowIdByKey( $key->getId() );
                //atualiza a data de checkin e status
                $controlb->UpdateCheckin( $borrowid );
                $controlb->DeactiveKeysBorrow( $key->getId() );
                //seta operacao log
                $log->setOperation( 4 );
                $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." foi DEVOLVIDA.";
            } else {
                $string = "Chave Nº: ".$key->getId().", Gancho: ".$key_gan." teve atualização do status: ".$key->getStatus();
            }
        }

        $log->setDescription( $string );
        $controll->CreateLog( $log );
    }

}


//ao pressionar botao delete na tabela de logs, id eh enviado via post
if ( isset( $_GET['logid'] ) ) {
    $controll->DeleteLog( $_GET['logid'] );

}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header ( Page header ) -->
    <section class="content-header">
        <h1>Visualizar & Editar Chave</h1>

        <button type="button" class="btn btn-info" style="margin-top:10px;" onclick=
        <?php if($key_tip == "Aluguel"){ ?>
        "location.href='mainlist.php';"
        <?php } else {
    ?>"location.href='sellinglist.php';"
<?php } ?>
        >Voltar para lista</button>

    </section>

    <!-- Main content -->
    <section class="content container-fluid">
        <form id="newkeyform" role="form" action="" method="post">
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Informações da chave e imóvel</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Código no Sicadi:</label>
                                <input type="text" class="form-control" name="txtsicadi" placeholder="Insira o código do imóvel no sistema SICADI" value="<?php echo $key->getSicadi();?>" required>
                            </div>
                            <div class="form-group">
                                <label>Categoria:</label>
                                <select class="form-control" name="select_category" required>
                                    <option value="" disabled selected>Selecione a categoria</option>
                                    <?php
$tipo_array = array( 1 => 'Aluguel', 2 => 'Venda' );
for ( $i = 1; $i <= 2; $i++ ) {
    ?>
                                    <option <?php
    if ( $key->getTipo() == $tipo_array[$i] ) {
        ?> selected="selected" <?php
    }
    ?>>
                                        <?php
    echo $tipo_array[$i];
    ?>
                                    </option>
                                    <?php
}
?>
                                </select>

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="margin-bottom:0px;">
                                <label>Código do Gancho:</label>
                                <!--   <input type = "text" class = "form-control" name = "txthook" placeholder = "Insira o código do gancho onde a chave se localiza no painel" value = "<?php //echo $key->getGancho();?>" required>-->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group" style="margin-bottom:0px;">
                                    <label>
                                        <input type="checkbox" class="minimal" name="checkhook" id="checkhook" onclick="validate()" <?php
if ( $key->getGanchoManual() == 0 ) {
    echo ' checked ';
}
?>>
                                        Gancho automático
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">

                                    <select class="form-control" name="select_hook" id="select_hook" 
                                       <?php 
                                            if($key->getGanchoManual() == false){
                                                echo 'disabled';
                                            }
                                            ?>
                                       
                                       >
                                        <option value="" disabled selected>Selecione o código</option>
                                        <?php
$hook_array = $controlh->FetchAllByType( $key->getTipo() );
$code = $controlk->FetchHookCode( $key->getId() );
for ( $i = 0; $i < count( $hook_array ); $i++ ) {
    ?>
                                        <option <?php
    if ( $code == $hook_array[$i]->getCodigo() ) {
        ?> selected="selected" <?php
    }
    ?>>
                                            <?php
    echo $hook_array[$i]->getCodigo() ;
    ?>
                                        </option>
                                        <?php
}
?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Status:</label>
                                    <select class="form-control" name="select_status" required>
                                        <option value="" disabled selected>Selecione o status</option>
                                        <?php
$status_array = array( 1 => 'Disponível', 2 => 'Emprestado', 3 => 'Atrasado', 4 => 'Perdido' );
for ( $i = 1; $i <= 4; $i++ ) {
    ?>
                                        <option <?php
    if ( $key->getStatus() == $status_array[$i] ) {
        ?> selected="selected" <?php
    }
    if ( $status_array[$i] == 'Emprestado' ) {
        ?> disabled <?php
    }
    ?>>
                                            <?php
    echo $status_array[$i];
    ?>
                                        </option>
                                        <?php
}
?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Adicional</label>
                                <textarea class="form-control" rows="3" name="txtaddon" placeholder="Alguma informação adicional sobre a chave ou imóvel" style="text-align:left;"><?php echo $key->getAdicional();
?></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Rua</label>
                                <input type="text" class="form-control" name="txtstreet" placeholder="Insira a rua do imóvel" value="<?php echo $address->getRua();?>" required>
                            </div>
                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" class="form-control" name="txtdistrict" placeholder="Insira o bairro do imóvel" value="<?php echo $address->getBairro();?>" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" class="form-control" name="txtnum" placeholder="Insira o número do imóvel" value="<?php echo $address->getNumero();?>" required>
                            </div>

                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" class="form-control" name="txtcity" placeholder="Insira a cidade do imóvel" value="<?php echo $address->getCidade();?>" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Complemento</label>
                                <textarea class="form-control" rows="3" name="txtaddon2" placeholder="Insira alguma informação adicional do endereço (bloco de apartamento, ponto de referência, etc)" style="text-align:left;">
<?php echo $address->getComplemento();
?>
</textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning" name="btnupdate">Atualizar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Movimentações da chave</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12" style="overflow-x:auto; height:600px;">
                            <table id="tablemov" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>

                                        <!--[date]  [operation] [description]  [nome] -->
                                        <th style="width: 15%">Data</th>
                                        <th style="width: 10%">Usuário</th>
                                        <th style="width: 10%">Operação</th>
                                        <th style="width: 60%">Descrição</th>
                                        <th style="width: 5%">Apagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$controll->FillMovTable( $key_id );
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div  style="text-align:right;" >
             <button type="button" class="btn btn-danger btndelete" name="btndelete" id="<?php echo $key_id;?>'">Remover Chave</button>
             </div>
        </form>
        <!-- /.content -->

    </section>
    
    
   
</div>

<!-- /.content-wrapper -->

<script>
    // ativa/desativa select com base no checkbox
    function validate() {
        if (document.getElementById('checkhook').checked) {
            document.getElementById('select_hook').disabled = true;
        } else {
            document.getElementById('select_hook').disabled = false;
        }
    }

</script>


<!-- ajax code for delete button -->
<script>
    $(document).ready(function() {
        $('.btndelete').click(function() {
            var tdh = $(this);
            var id = $(this).attr("id");
            swal({
                title: "Você deseja excluir a chave?",
                text: "Uma vez apagada, você não terá mais acesso a essa chave!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: '../control/ajaxremovekey.php',
                        type: 'post',
                        data: { 
                            keyid: id,
                            op: 'rem'
                        },
                        success: function(data) {
                            console.log(data);
                            // go back to list
                            <?php if($key_tip == "Aluguel"){ ?>
                            location.href='mainlist.php';
                            <?php } else { ?>
                            location.href='sellinglist.php';
                            <?php } ?>                       
                        }
                    });

                    swal("Chave removida com sucesso.", {
                            icon: "success",
                    });
                } else {
                    swal("A chave não foi removida.");
                }
            });
        });
    });

</script>

<?php
include_once 'footer.php';
?>

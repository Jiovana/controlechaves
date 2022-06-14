<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';

session_start();

if ( $_SESSION['user_email'] == "" ) {
    header( 'location:index.php' );
}

include_once 'header.php';

$controlk = new ControlKey();
$controla = new ControlAddress();

$key_id = $_GET['id'];
$key = $controlk->GetKeyModel( $key_id );

$addr_id = $key->getEnderecoId();
$address = $controla->GetAddressModel( $addr_id );

//ao pressionar o botao de 
if ( isset( $_POST['btnupdate'] ) ) {

    $address->setNumero( $_POST['txtnum'] );
    $address->setBairro( $_POST['txtdistrict'] );
    $address->setRua( $_POST['txtstreet'] );
    $address->setCidade( $_POST['txtcity'] );
    $address->setComplemento( $_POST['txtaddon2'] );

    $controla->UpdateAddress($address);

    $key->setSicadi( $_POST['txtsicadi'] );
    $key->setGancho( $_POST['txthook'] );
    $key->setTipo( $_POST['select_category'] );
    $key->setStatus( $_POST['select_status'] );
    $key->setAdicional( $_POST['txtaddon'] );
    $key->setEnderecoId( $addr_id );

    $controlk->UpdateKey($key);
}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header ( Page header ) -->
    <section class="content-header">
        <h1>Visualizar & Editar Chave</h1>
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
$tipo_array = array( 1 => 'aluguel', 2 => 'venda');
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
                            <div class="form-group">
                                <label>Código do Gancho:</label>
                                <input type="text" class="form-control" name="txthook" placeholder="Insira o código do gancho onde a chave se localiza no painel" value="<?php echo $key->getGancho();?>" required>
                            </div>
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" name="select_status" required>
                                    <option value="" disabled selected>Selecione o status</option>
                                    <?php
$status_array = array( 1 => 'disponível', 2 => 'emprestado', 3 => 'atrasado', 4 => 'perdido', 5 => 'indisponível' );
for ( $i = 1; $i <= 5; $i++ ) {
    ?>
                                    <option <?php
    if ( $key->getStatus() == $status_array[$i] ) {
        ?> selected="selected" <?php
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
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Adicional</label>
                                <textarea class="form-control" rows="3" name="txtaddon" placeholder="Alguma informação adicional sobre a chave ou imóvel"><?php echo $key->getAdicional();?></textarea>
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
                                <textarea class="form-control" rows="3" name="txtaddon2" placeholder="Insira alguma informação adicional do endereço (bloco de apartamento, ponto de referência, etc)" >
                                <?php echo $address->getComplemento();?>
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
                        <h3 class="box-title">Movimentacoes da chave</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12" style="overflow-x:auto;">
                            <table id="tablemov" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 65%">Descricao</th>
                                        <th style="width: 10%">Usuario</th>
                                        <th style="width: 10%">Data</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

?>
                                </tbody>
                            </table>
                        </div>
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

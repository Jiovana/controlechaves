<?php

include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php';
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_log.php';

session_start();

if ( $_SESSION['user_email'] == "" ) {
    header( 'location:index.php' );

}

include_once 'header.php';

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
        window.location.href = "/controlechaves/mainlist.php";

    }, 3000);
    </script>   '; 
}


?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header ( Page header ) -->
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
                            <h4>Informações da chave</h4>
                            <div class="form-group">
                                <label>Código no Sicadi:</label>
                                <input type="text" class="form-control" name="txtsicadi" placeholder="Insira o código do imóvel no sistema SICADI" required>
                            </div>

                            <div class="form-group">
                                <label>Código do Gancho:</label>
                                <input type="text" class="form-control" name="txthook" placeholder="Insira o código do gancho onde a chave se localiza no painel" required>
                            </div>

                            <div class="form-group">
                                <label>Categoria:</label>
                                <select class="form-control" name="select_category" required>
                                    <option value="" disabled selected>Selecione a categoria</option>
                                    <option>Aluguel</option>
                                    <option>Venda</option>
                                </select>

                            </div>

                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" name="select_status" required>
                                    <option value="" disabled selected>Selecione o status</option>
                                    <option>Disponível</option>
                                    <option>Emprestado</option>
                                    <option>Atrasado</option>
                                    <option>Perdido</option>
                                    <option>Indisponível</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Adicional</label>
                                <textarea class="form-control" rows="3" name="txtaddon" placeholder="Alguma informação adicional sobre a chave ou imóvel"></textarea>

                            </div>
                        </div>
                        <div class="hidden-lg hidden-md">
                            <hr>
                        </div>

                        <div class="col-md-6">
                            <h4>Endereço do imóvel</h4>
                            <div class="form-group">
                                <label>Número</label>
                                <input type="text" class="form-control" name="txtnum" placeholder="Insira o número do imóvel" required>
                            </div>

                            <div class="form-group">
                                <label>Rua</label>
                                <input type="text" class="form-control" name="txtstreet" placeholder="Insira a rua do imóvel" required>
                            </div>

                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" class="form-control" name="txtdistrict" placeholder="Insira o bairro do imóvel" required>
                            </div>

                            <div class="form-group">
                                <label>Cidade</label>
                                <input type="text" class="form-control" name="txtcity" placeholder="Insira a cidade do imóvel" required>
                            </div>

                            <div class="form-group">
                                <label>Complemento</label>
                                <textarea id="addon2" class="form-control" rows="3" name="txtaddon2" placeholder="Insira alguma informação adicional do endereço (bloco de apartamento, ponto de referência, etc)"></textarea>
                            </div>

                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-success" name="btnsave" style="margin-right: 10px !important;">Salvar</button>
                            <input type="reset" value="Limpar dados" class="btn btn-secondary">

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

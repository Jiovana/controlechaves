<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_key.php";

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php";

/**
* Reune metodos para interacao entre a view ( interface ) relacionado a chave e o model( modelos e daos ) - ModelKey e DaoKey
*
*/

class ControlKey {

    /**
    * Insere uma nova chave no banco, chamada apartir de newkey.php ao pressionar btn save
    *
    * Envia dados para a dao, mostra alertas swal
    *
    * @param ModelKey $key O objeto key a ser inserido
    *
    */

    public function NewKey( ModelKey $key ) {
        $dao = new DaoKey();
        $dao->Insert( $key );

        $k = $dao->SearchIdLimit1();

        if ( $k != null ) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Sucesso!",
                            text: "Chave cadastrada",
                            icon: "success",
                            button: "Ok",
                        });
                    });
                    </script>';
            return $k->getId();
        } else {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Erro!",
                            text: "Problema ao cadastrar chave",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
            return null;
        }
    }

    /**
    * Preenche a tabela em mainlist.php
    *
    * Busca dados da dao.
    *
    */

    public function FillTable() {
        $dao = new DaoKey();
        $addr = new ControlAddress();
        $keys = $dao->SearchAll();

        // array( 'disponível' => '#9FF781', 'emprestado' => '#F7BE81', 'atrasado' => '#F78181', 'perdido' => '#819FF7', 'indisponível' => '#A4A4A4' );

        $status_labels =  array( 'Disponível' => 'label-success', 'Emprestado' => 'label-warning', 'Atrasado' => 'label-danger', 'Perdido' => 'label-primary', 'Indisponível' => 'label-default' );

        foreach ( $keys as $key ) {
            echo '<tr style="text-align: center; vertical-align: middle;">
                    <td style="background-color:#D8D8D8;"><b>'.$key->getGancho().'</b></td>
                    <td style="text-align: left; vertical-align: middle;">'.$addr->GetAddressString( $key->getEnderecoId() ).'</td>
                    <td>'.$key->getTipo().'</td>
                    <td><h4><span class="label '.$status_labels[$key->getStatus()].'">'.$key->getStatus().'</span></h4></td>
                    ';
            if ($key->getStatus() == 'Emprestado' || $key->getStatus() == 'Atrasado') {
                echo '
                    <td>      
                    
                        <a href="#" class="btn btn-success btn-block btnretrieve" role="button" id="'.$key->getId().'" data-toggle="tooltip" title="Devolver essa chave"><i class="fa fa-rotate-left"></i></a>                   
                    </td>
                   '; 
            } else if ($key->getStatus() == 'Disponível'){
                 echo '
                    <td>      
                        <a href="borrowkey.php?id='.$key->getId().'" class="btn btn-warning btn-block" role="button" data-toggle="tooltip" title="Emprestar essa chave"><i class="glyphicon glyphicon-tags"></i></a>                                  
                    </td>
                   '; 
            } else {
                echo '
                    <td>      
                        <a href="#" class="btn btn-warning btn-block" role="button" data-toggle="tooltip" title="Nao e possivel emprestar essa chave" disabled><i class="glyphicon glyphicon-tags"></i></a>                    
                    </td>
                   '; 
            }
     
            echo '
                    <td>      
                        <a href="editkey.php?id='.$key->getId().'" class="btn btn-info btn-block" role="button" data-toggle="tooltip" title="Visualizar e Editar essa chave"><i class="glyphicon glyphicon-edit"></i></a>
                    </td>                
                </tr> ';
        }
    }

    /**
    * Obtem um objeto key do banco com base no id informado
    *
    * Usado em editkey.php para mostrar dados no form
    *
    * @param int $id id da chave a ser buscada
    * @return ModelKey obj key
    */

    public function GetKeyModel( $id ) {
        $dao = new DaoKey();
        return $dao->SearchById( $id );

    }

    /**
    * Atualiza uma chave no banco de dados
    *
    * Envia dados para a dao, mostra alertas swal
    *
    * @param ModelKey $key O objeto key a ser atualizado
    *
    */

    public function UpdateKey( ModelKey $key ) {
        $dao = new DaoKey();
        if ( $dao->Update( $key ) ) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Sucesso!",
                            text: "Chave atualizada",
                            icon: "success",
                            button: "Ok",
                        });
                    });
                    </script>';
        } else {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Erro!",
                            text: "Problema ao atualizar chave",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
        }

    }

    
    /**
    * Chama a dao que procura chaves com base no gancho
    *
    * Usado em borrowkey.php
    *
    * @return string codigo html para preenchimento do select na tabela
    */
    public function Fill_Gancho() {

        $output = '';

        $dao = new DaoKey();
        $result = $dao->SearchAllByGancho();

        foreach ( $result as $key ) {
            $output .= '<option style="padding:0px;" value="'.$key->getId().'" >'.$key->getGancho().'</option>';
        }
        return $output;

    }
    
    /**
    * Chama a dao que procura chaves com base no sicadi
    *
    * Usado em borrowkey.php
    *
    * @return string codigo html para preenchimento do select na tabela
    */
    public function Fill_Sicadi() {
        $output = '';

        $dao = new DaoKey();
        $result = $dao->SearchAllBySicadi();

        foreach ( $result as $key ) {
            $output .= '<option style="padding:0px;" value="'.$key->getId().'" >'.$key->getSicadi().'</option>';
        }
        return $output;

    }
    
    /**
    * Obtem os atributos de uma chave pelo id
    *
    * Usado em borrowkey.php
    *
    * @param int $id o id da chave
    * @return array array de atributos da key
    */
     public function GetKeyAssoc( $id ) {
        $dao = new DaoKey();
        return $dao->SearchByIdAssoc( $id );
    }
    
    /**
    * Atualiza status de uma chave com base no id 
    *
    * @param int $keyid o id da chave
    * @param string $status o novo status.
    */
    public function UpdateStatus( $keyid, $status){      
        $status_labels =  array( 1 => 'Disponível', 2 => 'Emprestado', 3 => 'Atrasado', 4 => 'Perdido', 5 => 'Indisponível');
        $dao = new DaoKey();      
        $dao->UpdateStatus($keyid, $status_labels[$status]);       
    } 
    
    public function FetchGancho($keyid){
        $dao = new DaoKey();
        return $dao->SelectGancho($keyid);
    }
    
    public function CheckOverdueMessages(){
        try{
            
            if (($file = fopen('..\etc\overduemessages.txt','r')) != false){
                
                $contents = file_get_contents('..\etc\overduemessages.txt');
                $lines = explode(';', $contents);
                //print_r($lines);
                //echo $lines[0];
                if ($lines[0] == 'overdue_alert'){
                   // echo "test";
                   
                    echo '<script>
                            swal({          
                            title: "Chave(s) em atraso!",
                            text: "';
                            for($i = 1; $i < count($lines); $i++){
                                echo $lines[$i].'\n';
                            }
                            echo '\n",
                                icon: "warning",
                                button: "Ok",
                            }); 
                            </script>';
                }
                fclose($file);
                $file = fopen('..\etc\overduemessages.txt','w');
                fclose($file);
            }
        } catch (Exception $e){
            echo "Problem with CheckOverdueMessages in ControlKey";
        }
    }

}

$control = new ControlKey();
//$control->CheckOverdueMessages();

?>
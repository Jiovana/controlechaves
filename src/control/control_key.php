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
                    
                    <td>      
                        <a href="borrow.php?id='.$key->getId().'" class="btn btn-warning btn-block" role="button" data-toggle="tooltip" title="Emprestar essa chave"><i class="glyphicon glyphicon-tags"></i></a>
                        
                        
                    </td>
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

}

?>
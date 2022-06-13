<?php 

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_key.php";

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php";

/**
 * Reune metodos para interacao entre a view (interface) relacionado a chave e o model(modelos e daos) - ModelKey e DaoKey
 * 
*/
class ControlKey{
    
    /**
     * Insere uma nova chave no banco, chamada apartir de newkey.php ao pressionar btn save
     *
     * Envia dados para a dao, mostra alertas swal
     * 
     * @param ModelKey $key O objeto key a ser inserido
     * 
    */
    public function NewKey(ModelKey $key){
        $dao = new DaoKey();
         if ( $dao->Insert( $key ) ) {
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
            }
    }
    
    function FillTable() {
        $dao = new DaoKey();
        $addr = new ControlAddress();
        $keys = $dao->SearchAll();

        $status_colors =  array('disponível' => '#9FF781', 'emprestado' => '#F7BE81', 'atrasado' => '#F78181', 'perdido' => '#819FF7', 'indisponível' => '#A4A4A4');
        
        foreach ( $keys as $key ) {
            echo '<tr>
                    <td>'.$key->getGancho().'</td>
                    <td>'.$addr->GetAddressString($key->getEnderecoId()).'</td>
                    <td>'.$key->getTipo().'</td>
                    <td style="background-color:'. $status_colors[$key->getStatus()].';">'.$key->getStatus().'</td>
                    
                    <td>      
                        <a href="borrow.php?id='.$key->getId().'" class="btn btn-warning" role="button"><i class="glyphicon glyphicon-tags" style="margin-right:10px;"></i>Emprestar </a>
                        
                        
                    </td>
                    <td>      
                        <a href="editkey.php?id='.$key->getId().'" class="btn btn-info" role="button"><i class="glyphicon glyphicon-edit" style="margin-right:10px;"></i>Editar</a>
                    </td>                
                </tr> ';
        }
    }
    
    
    
    
    
}

?>
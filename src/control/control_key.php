<?php 

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_key.php";

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
    
    
    
}

?>
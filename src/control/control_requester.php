<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_requester.php";

class ControlRequester {

    public function NewRequester( ModelRequester $requester ) {
        $dao = new DaoRequester();
        try {
            $dao->Insert( $requester );
            $req = $dao->SearchIdLimit1();
            return $req->getId();

        } catch ( Exception $e ) {
            echo "Error in method NewRequester in ControlRequester: ".$e->getMessage()."</br>";
        }
    }


    public function FillForm( $email, $nome ) {
        $dao = new DaoRequester();
        // precisa testar antes de salvar o emprestimo se requerente ja esta no banco para nao reinserir.
        //salvar id dele.
        if ( $requester = $dao->SearchByEmailOrName( $email, $nome ) ) {
            echo "<script>console.log('dentro do if');</script>";
            echo "<script>console.log('nome: ".$requester->getNome()."');</script>";
            echo '
            <div class="col-md-4">
                <div class="form-group">
                    <label>Nome:</label>
                        <input type="text" class="form-control" name="txtnome" value="'.$requester->getNome().'" placeholder="Insira o nome completo do requerente" >
                </div>
                
                <div class="form-group">
                    <label>Categoria:</label>
                    <select class="form-control" name="select_category" >
                        <option value="" disabled selected>Selecione a categoria</option>
                        ';

            $array = array( 1 => 'Cliente', 2 => 'Diretoria', 3 => 'Manutenção', 4 => 'Prestador de serviço', 5 => 'Vistoria' );
            for ( $i = 1; $i <= 2; $i++ ) {
                if ( $requester->getTipo() == $array[$i] ) {
                    echo '<option selected="selected">'.$array[$i].'</option>';
                } else {
                    echo '<option>'.$array[$i].'</option>';
                }
            }

            echo '            
                        
                    </select>
                </div>
            </div>
            


                <div class="col-md-3">
                    <div class="form-group">
                        <label>Email:</label>
                        <input type="email" class="form-control" name="txtemail" value="'.$requester->getEmail().'" placeholder="Insira o email do rquerente" >
                    </div>

                    <div class="form-group">
                        <label>DDD:</label>
                        <input type="text" class="form-control" name="txtddd" value="'.$requester->getDdd().'" placeholder="Insira o DDD do telefone" >
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>ID:</label>
                        <input type="number" class="form-control" name="txtdocument" 
                        value="'.$requester->getDocumento().'" placeholder="Insira o numero do documento" >
                    </div>

                    <div class="form-group">
                        <label>Telefone:</label>
                        <input type="number" class="form-control" name="txtphone" value="'.$requester->getTelefone().'"
                        placeholder="Insira o numero do telefone" >
                    </div>
                </div>
          ';
        } else {
            $this->ClearForm();
        }
    }

}

if ( isset( $_POST['op'] ) ) {
    try {
        $dao = new DaoRequester();
        $requester = $dao->SearchByEmailOrName( $_POST['reqemail'], $_POST['reqnome'] );
        echo json_encode( $requester );
    } catch ( Exception $e ) {
        echo json_encode( $e->getMessage() );
        exit();
    }

}

?>
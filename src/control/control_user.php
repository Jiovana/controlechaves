<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_user.php";

/**
* Reune metodos para interacao entre a view( interface ) e o model( modelos e daos )
*
*/

class ControlUser {

    /**
    * Realiza login no sistema, chamada de index.php ao pressionar o botao de login
    *
    * Envia os dados do usuario via session,
    * Emite avisos com sweetalerts
    *
    * @param string $email email digitado
    * @param string $password a senha informada
    *
    */

    public function Login( $email, $password ) {

        $daouser = new DaoUser;

        if ( $email != null && $password != null ) {
            $md5pass = md5( $password );
            $row = $daouser->Login( $email, $md5pass );
            if ( $row ) {
                if ( $row['email'] == $email && $row['senha'] == $md5pass ) {

                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['user_name'] = $row['nome'];
                    $_SESSION['user_surname'] = $row['sobrenome'];
                    $_SESSION['user_email'] = $row['email'];

                    echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Bem vindo(a)'.$_SESSION['user_name'].'!",
                            text: "Carregando...",
                            icon: "success",
                            button: "Ok",
                        });
                    });
                    </script>';
                    $url = "../view/mainlist.php";
                    header( 'refresh:2;'.$url );
                }
            } else {
                echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Falha no login!",
                            text: "Email ou senha incorretos.",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
            }
        }
    }

    /**
    * Insere um novo usuario no banco de dados, chamda de users.php ao clicar no botao salvar
    *
    * Envia dados para a dao, emite alertas swal
    *
    * @param ModelUser $user O objeto usuario a ser inserido
    *
    */

    public function NewUser( ModelUser $newuser ) {

        $dao = new DaoUser();

        if ( $dao->SearchbyEmail( $newuser->getEmail() ) != false ) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Aviso!",
                            text: "Email ja cadastrado no sistema.",
                            icon: "warning",
                            button: "Ok",
                        });
                    });
                    </script>';

        } else {
            if ( $dao->Insert( $newuser ) ) {
                echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Sucesso!",
                            text: "Usuario cadastrado",
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
                            text: "Problema ao cadastrar usuario",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
            }
        }
    }

    /**
    * Apaga um usuario, chamda de users.php ao clicar no botao delete da tabela
    *
    * @param int $id id do usuario a ser apagado
    *
    */

    public function DeleteUser( $id ) {
        $dao = new DaoUser();
        if ( $dao->Delete( $id ) ) {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Sucesso!",
                            text: "Usuario removido do sistema",
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
                            text: "O usuario nao pode ser removido. ",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
        }
    }

    /**
    * Altera a senha de um usuario, testa as senhas new e conf, emite alertas swal, chamada de changepassword.php
    *
    * @param string $email email do usuario vindo da session
    * @param string $oldpass senha antiga do usuario
    * @param string $newpass nova senha
    * @param string $confpass senha de confirmacao
    *
    */

    public function ChangePassword( $email, $oldpass, $newpass, $confpass ) {

        $daouser = new DaoUser();
        $user = new ModelUser();

        $user = $daouser->SearchByEmail( $email );

        //we compare user input and database values
        if ( md5( $oldpass ) == $user->getSenha() ) {
            if ( $newpass == $confpass ) {
                $md5pass = md5( $newpass );
                $user->setSenha( $md5pass );

                if ( $daouser->UpdatePassword( $user ) ) {
                    echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Tudo Certo!",
                            text: "Senha atualizada com sucessso.",
                            icon: "success",
                            button: "Ok",
                        });
                    });
                    </script>';
                } else {
                    echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Erro no banco de dados!",
                            text: "Problema ao atualizar a senha.",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
                }

            } else {
                echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Oops!",
                            text: "Sua nova senha precisa ser igual a senha de confirmação",
                            icon: "warning",
                            button: "Ok",
                        });
                    });
                    </script>';
            }
        } else {
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Warning",
                            text: "Sua senha antiga está errada",
                            icon: "warning",
                            button: "Ok",
                        });
                    });
                    </script>';
        }
    }

    /**
    * Preenche a tabela de usuarios em users.php
    *
    * Pega os usuarios do banco como um array e os percorre, preenchendo a tabela
    *
    */

    public function FillTable() {
        $dao = new DaoUser();
        $users = $dao->SearchAll();

        foreach ( $users as $user ) {
            echo '<tr>
                    <td>'.$user->getId().'</td>
                    <td>'.$user->getData_in().'</td>
                    <td>'.$user->getNome().'</td>
                    <td>'.$user->getSobrenome().'</td>
                    <td>'.$user->getEmail().'</td>
                    <td><button type="submit" value="'.$user->getId().'" class="btn btn-success" name="btnedit"><span class="glyphicon glyphicon-edit" title="Editar"></span></button></td>
                    <td>
<a href="users.php?id='.$user->getId().'" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-trash"  title="Apagar"></span></a></td>
                </tr> ';
        }
    }

    /**
    * Atualiza um usuario em users.php ao clicar no botao de update
    *
    * @param ModelUser $user o usuario a ser atualizado
    *
    */

    public function UpdateUser( ModelUser $user ) {
        if ( empty( $user->getNome() ) ||  empty( $user->getSobrenome() ) || empty( $user->getEmail() ) || empty( $user->getSenha() ) ) {
            $errorupdate = '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Erro!",
                            text: "Algum campo esta vazio: Por favor complete o formulario.",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
            echo $errorupdate;
        }
        if ( !isset( $errorupdate ) ) {
            $dao = new DaoUser();
            $user->setSenha( md5( $user->getSenha() ) );
            if ( $dao->UpdateAll( $user ) ) {
                echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Sucesso!",
                            text: "Usuario atualizado.",
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
                            text: "Problema ao atualizar o usuario.",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
            }
        }
    }

    /**
    * Preenche o formulario em users.php quando o botao editar da lista de usuarios e clicado, preenchendo o form com os dados do usuario selecionado
    *
    * @param int $id o id do usuario a ser preenchido o form
    *
    *
    */

    public function FillForm( $id ) {
        $dao = new DaoUser();
        if ( $user = $dao->SearchById( $id ) ) {
            echo '
            <div class="form-group">
                <label>Nome</label>
                <input type="hidden" class="form-control" name="txtid" value="'.$user->getId().'" >
                <input type="text" class="form-control" name="txtname" value="'.$user->getNome().'" placeholder="Insira o nome" >
            </div>

            <div class="form-group">
                <label>Sobrenome</label>
                <input type="text" class="form-control" name="txtsurname" 
                value="'.$user->getSobrenome().'" placeholder="Insira o sobrenome" >
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="txtemail" 
                value="'.$user->getEmail().'" placeholder="Insira o email" >
            </div>
            
            <div class="form-group">
                <label>Senha</label>
                <input type="password" class="form-control" name="txtpassword" 
                value="'.$user->getSenha().'" placeholder="Insira a senha" >
            </div>

            <button type="submit" class="btn btn-warning" name="btnupdate">Atualizar</button>
          ';
        }
    }

    /**
    * Apresenta um formulario 'limpo' em users.php, mostrando os campos em branco e opcao para inserir novo user
    *
    *
    *
    */

    public function ClearForm() {
        echo '
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" class="form-control" name="txtname" placeholder="Insira o nome">
                        </div>

                        <div class="form-group">
                            <label>Sobrenome</label>
                            <input type="text" class="form-control" name="txtsurname" placeholder="Insira o sobrenome">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="txtemail" placeholder="Insira o email">
                        </div>
                        <div class="form-group">
                            <label>Senha</label>
                            <input type="password" class="form-control" name="txtpassword" placeholder="Insira a senha">
                        </div>
                                                  
                        <button type="submit" class="btn btn-info" name="btnsave">Salvar</button>
                        <input type="reset" value ="Limpar dados" class="btn btn-secondary" style="float: right;" >
                            ';
    }

    /**
    * Apenas chama o metodo searchbyid de Dao User
    *
    * @param int $id O id do usuario a ser buscado
    * @return ModelUser objeto user
    */

    public function SearchUser( $id ) {
        $dao = new DaoUser();
        return $dao->SearchById( $id );
    }

}
?>
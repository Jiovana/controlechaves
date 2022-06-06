<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_user.php";

function Login( $email, $password ) {

    $daouser = new DaoUser;

    if ( $email != null && $password != null ) {
        $md5pass = md5( $password );
        $row = $daouser->Login( $email, $md5pass );
        if ( $row ) {
            //print_r( $row );
            if ( $row['email'] == $email && $row['senha'] == $md5pass ) {

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['nome'];
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
                $url = "../controlechaves/mainlist.php";
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

function NewUser() {

}


?>
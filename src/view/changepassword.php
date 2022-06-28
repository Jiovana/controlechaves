<?php
 include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_user.php';
    session_start();

    if($_SESSION['user_email']==""){
        header('location:index.php');           
    }
    
    include_once 'header.php';
    

    //when click on update pass button we get out values from user into variables
    if(isset($_POST['btn_update'])){
        $oldpass = $_POST['txt_oldpass'];
        $newpass = $_POST['txt_newpass'];
        $confpass = $_POST['txt_confpass'];
        $email=$_SESSION['user_email'];
        
        $control = new ControlUser();
        $control->ChangePassword($email,$oldpass,$newpass,$confpass);

    }

?>
    <script>
                function onChange() {
                const password = document.querySelector('input[name=txt_newpass]');
                const confirm = document.querySelector('input[name=txt_confpass]');
                    if (confirm.value === password.value) {
                    confirm.setCustomValidity('');
                    } else {
                    confirm.setCustomValidity('As senhas não conferem.);
                    }
                }
    </script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    

    <!-- Main content -->
    <section class="content container-fluid">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Trocar senha</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Senha antiga</label>
                  <input type="password" class="form-control" id="exampleInputPass1" placeholder="Senha antiga" name="txt_oldpass"  required >
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword2">Senha nova</label>
                  <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Senha nova" name="txt_newpass" onChange="onChange()" required >
                </div>            
                <div class="form-group">
                  <label for="exampleInputPassword3">Confirmar a senha nova</label>
                  <input type="password" class="form-control" id="exampleInputPassword3" placeholder="Senha de confirmação" name="txt_confpass" onChange="onChange()" required >
                </div>
             
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="btn_update" >Atualizar senha</button>
              </div>
            </form>
          </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 <?php
    include_once 'footer.php';
?>
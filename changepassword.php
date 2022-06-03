<?php
    include_once 'dbconnect.php';
    session_start();

    if($_SESSION['user_email']==""){
        header('location:index.php');           
    }
    
    include_once 'headeruser.php';
    

    //when click on update pass button we get out values from user into variables
    if(isset($_POST['btn_update'])){
        $oldpass = $_POST['txt_oldpass'];
        $newpass = $_POST['txt_newpass'];
        $confpass = $_POST['txt_confpass'];
        
       // echo $oldpass. " - " .$newpass. " - ".$confpass;
    
    //using of select query we get out database record according to useremail
        $email=$_SESSION['email'];
        $select = $pdo->prepare("select * from tbl_user where email='$email'");
        
        $select->execute();
        $row=$select->fetch(PDO::FETCH_ASSOC);
        
        $email_db = $row['email'];
        $pass_db = $row['password'];
        
        //we compare user input and database values
        if($oldpass == $pass_db) {
           if($newpass == $confpass){
               $update = $pdo->prepare("update tbl_user set password=:pass where email=:email");
               
               $update->bindParam(':pass', $confpass);
               $update->bindParam(':email', $email);
               
               if($update->execute()){
                   echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "All Fine",
                            text: "Your password was updated",
                            icon: "success",
                            button: "Ok",
                        });
                    });
                    </script>';
               }else{
                   echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Database error",
                            text: "Problem updating password",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
               }
               
           }else{
               echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Oops!",
                            text: "Your new password needs to match the confirm password",
                            icon: "warning",
                            button: "Ok",
                        });
                    });
                    </script>';
           }
        }else{
            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Warning",
                            text: "Your old password is wrong",
                            icon: "warning",
                            button: "Ok",
                        });
                    });
                    </script>';
        }

    //if values match then we run update query

    }

?>
    <script>
                function onChange() {
                const password = document.querySelector('input[name=txt_newpass]');
                const confirm = document.querySelector('input[name=txt_confpass]');
                    if (confirm.value === password.value) {
                    confirm.setCustomValidity('');
                    } else {
                    confirm.setCustomValidity('Passwords do not match');
                    }
                }
    </script>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Admin Dashboard</h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content container-fluid">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" method="post">
              <div class="box-body">
                <div class="form-group">
                  <label for="exampleInputEmail1">Old Password</label>
                  <input type="password" class="form-control" id="exampleInputPass1" placeholder="Old password" name="txt_oldpass"  required >
                </div>
                <div class="form-group">
                  <label for="exampleInputPassword2">New Password</label>
                  <input type="password" class="form-control" id="exampleInputPassword2" placeholder="New Password" name="txt_newpass" onChange="onChange()" required >
                </div>            
                <div class="form-group">
                  <label for="exampleInputPassword3">Confirm Password</label>
                  <input type="password" class="form-control" id="exampleInputPassword3" placeholder="Confirm Password" name="txt_confpass" onChange="onChange()" required >
                </div>
             
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary" name="btn_update" >Update Password</button>
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
<?php
include_once '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php';

$control = new ControlKey();

echo '<script>console.log("teste1");</script>';

    $keyid = $_GET['id'];
    $operation = $_GET['op'];
    echo $keyid;
    echo $operation;
    echo '<script>console.log("teste2");</script>';
    
    if ( $operation == "gancho" ) {

        $response = $control->GetKeyAssoc( $keyid );

        echo json_encode( $response );
        exit();
    }

?>


 <!--$('.keygancho').on('change', function(e) {
            var keyid = this.value;
            console.log(keyid);
            var tr = $(this).parent().parent();
            $.ajax({
                url: "borrowkey.php",
                method: "POST",
                dataType: "json",
                data: {
                    id: keyid,
                    op: "gancho"
                },
                success: function(data) {
                    console.log("ihatethis");
                    console.log(data);
                    tr.find(".keygancho").val(data["gancho"]);
                    tr.find(".keysicadi").val(data["sicadi"]);
                    tr.find(".keyaddress").val(data["endereco_id"]);
                    tr.find(".keycategory").val(data["tipo"]);
                }
            });
        });-->
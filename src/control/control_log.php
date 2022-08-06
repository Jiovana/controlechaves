<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_log.php";

/**
* Reune metodos para interacao entre a view ( interface ) relacionado a log e o model( modelos e daos ) - ModelLog e DaoLog
*
*/

class ControlLog {

    /**
    * Insere um novo log no sistema, simplesmente chama Insert de DaoLog.
    *
    * Envia dados para a dao
    *
    * @param ModelLog $log O objeto log a ser inserido
    *
    */

    function CreateLog( ModelLog $log ) {
        $dao = new DaoLog();
        $dao->Insert( $log );
    }

    /**
    * Preenche a tabela de movimentacoes em editkey.php
    *
    * Busca no banco os logs referentes a chave informada pelo id
    *
    * @param int $id Id da chave que se quer mostrar os logs relacionados.
    */

    public function FillMovTable( $id ) {
        $dao = new DaoLog();
        $logs = $dao->SearchAllByKey( $id );
        #	[date]  [operation] [description]  [nome]
        foreach ( $logs as $log ) {

            $date = date_create( $log['date'] );

            $date = date_format( $date, 'd/m/Y' );

            echo '<tr>
                    <td>'.$date.'</td>
                    <td>'.$log['nome'].'</td>
                    <td>'.$log['operation'].'</td>
                    <td>'.$log['description'].'</td> 
                    <td>
<a href="editkey.php?id='.$id.'&logid='.$log['id'].'" class="btn btn-danger" role="button"><span class="glyphicon glyphicon-trash"  title="Apagar"></span></a></td>
                </tr> ';
        }
    }
    
    /**
    * Preenche as linhas da tabela de movimentacoes em report.php
    *
    * Busca todos os logs com datas entre o periodo delimitado por date_begin e date_end
    *
    * @param string $date_begin a data de inicio do periodo
    * @param string $date_end a data de fim do periodo
    * @return array retorna as próprias datas enviadas (como a pagina atualiza, esses dados se perdem, assim é uma forma de recuperar eles)
    */
    public function FillReportTable($date_begin, $date_end){
        $dao = new DaoLog();
        $fromdate =date('Y-m-d', strtotime (str_replace ('/', '-', $date_begin)));
        $todate =date('Y-m-d', strtotime (str_replace ('/', '-', $date_end)));
        
        
        $logs = $dao->SearchAllPeriod($fromdate, $todate);
       // [date]  [user] [key] [operation] [description]
        foreach ( $logs as $log ) {
            $date = date_create( $log['date'] );
            $date = date_format( $date, 'd/m/Y' );

            echo '<tr>
                    <td>'.$date.'</td>
                    <td>'.$log['nome'].'</td>
                    <td>'.$log['codigo'].'</td>
                    <td>'.$log['operation'].'</td>
                    <td>'.$log['description'].'</td>          
                </tr> ';
        }
        
        return array($date_begin, $date_end);
    }
    
    
    /**
    * Retorna as próprias datas usadas como inputs.
    *
    *
    * @param string $date_begin a data de inicio do periodo
    * @param string $date_end a data de fim do periodo
    * @return array retorna as próprias datas enviadas (como a pagina atualiza, esses dados se perdem, assim é uma forma de recuperar eles)
    */
    public function RetrieveReportDates($date_begin, $date_end){
         return array($date_begin, $date_end);
    }
    
    
    /**
    * Busca todos os dados do periodo. Usada para gerar o pdf
    *
    *
    * @param string $date_begin a data de inicio do periodo
    * @param string $date_end a data de fim do periodo
    * @return array array associativo com todos dados
    */
    public function FetchReportData($date_begin, $date_end){
        $dao = new DaoLog();
        $fromdate =date('Y-m-d', strtotime (str_replace ('/', '-', $date_begin)));
        $todate =date('Y-m-d', strtotime (str_replace ('/', '-', $date_end)));
        $logs = $dao->SearchAllPeriod($fromdate, $todate);
        return $logs;
    }
    
    /**
    * Apaga um log, chamda de editkey.php ao clicar no botao delete da tabela
    *
    * @param int $id id do log a ser apagado
    *
    */

    public function DeleteLog( $id ) {
        $dao = new DaoLog();
        if ( $dao->Delete( $id ) ) {

            echo '<script type="text/javascript">
                    jQuery(function validation(){
                        swal({
                            title: "Sucesso!",
                            text: "Log removido do sistema",
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
                            text: "O Log não pode ser removido. ",
                            icon: "error",
                            button: "Ok",
                        });
                    });
                    </script>';
            
        }
    }
   

}

?>
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
                </tr> ';
        }
    }
    
    
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
                    <td>'.$log['gancho'].'</td>
                    <td>'.$log['operation'].'</td>
                    <td>'.$log['description'].'</td>          
                </tr> ';
        }
        
        return array($date_begin, $date_end);
    }
    
    public function RetrieveReportDates($date_begin, $date_end){
         return array($date_begin, $date_end);
    }
    
    public function FetchReportData($date_begin, $date_end){
        $dao = new DaoLog();
        $fromdate =date('Y-m-d', strtotime (str_replace ('/', '-', $date_begin)));
        $todate =date('Y-m-d', strtotime (str_replace ('/', '-', $date_end)));
        $logs = $dao->SearchAllPeriod($fromdate, $todate);
        return $logs;
    }
    
   

}

?>
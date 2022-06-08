<?php

class Connection {
    // pega os dados de conexao do banco em um arquivo .ini
    public static function getConnection($cfg = '//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/etc/db.ini') {
        if ( !$settings = parse_ini_file( $cfg, TRUE ) ) {
            throw new Exception( 'Unable to open '.$file );
        }
        
        //define primeira parte da string de conexao
        $dns = $settings['database']['driver']. ':host=' . $settings['database']['host']. ( ( !empty( $settings['database']['port'] ) ) ? ( ';port=' . $settings['database']['port'] ) : '' ). ';dbname=' . $settings['database']['schema'];
        
        try{
            $connect = new PDO($dns, $settings['database']['username'], $settings['database']['password'], array( PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ) );
            $connect->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            $connect->setAttribute( PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING );
            return $connect;
        }catch(PDOException $e){
            print "Error!: ".$e->getMessage()."</br>";
            die();
        }
        
    }

    
}
?>
<?php

/**
 * Reune operações para conexão do sistema ao banco de dados local cujos dados estão salvos no arquivo db.ini
 * 
*/
class Connection {
    
     /**
     * Cria uma conexão com o banco de dados, pega os dados  do banco de um arquivo .ini
     * Usada por todas classes DAO
     * 
     * @param string $cfg caminho para o arquivo .ini com os dados do banco de dados
     * @return PDO um objeto PDO com a conexão
    */
    // 
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
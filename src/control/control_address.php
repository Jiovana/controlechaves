<?php 

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_address.php";

/**
 * Reune metodos para interacao entre a view (interface) relacionado a endereco  e o model(modelos e daos) - ModelAddress e DaoAddress
 * 
*/
class ControlAddress{
    
    /**
     * Insere um novo endereco no banco, chamada apartir de newkey.php ao pressionar btn save
     *
     * Envia dados para a dao
     * 
     * @param ModelAddress $address O objeto endereco a ser inserido
     * @return int $id o id do endereco recem inserido
    */
    public function NewAddress(ModelAddress $address){
        $dao = new DaoAddress();     
           try {
                $dao->Insert( $address );
                $addr = $dao->SearchIdLimit1();
                return $addr->getId();
            } catch(Exception $e) {
                echo "Error in method NewAddress in ControlAddres: ".$e->getMessage()."</br>";
            }            
    }
    
     /**
     * Busca um endereco do banco, retorna string com endereco completo
     *
     * Envia dados mainlist.php
     * 
     * @param int $id id do endereco a ser buscado
     * @return string todos campos de ModelAddress concatenados como string formando o endereco
    */
    public function GetAddressString($id){
        $dao = new DaoAddress();
        try{
           $addr = $dao->SearchById($id);           
           return $addr->toString();
        }catch(Exception $e) {
                echo "Error in method GetAddress in ControlAddres: ".$e->getMessage()."</br>";
            }            
    }
    
    public function GetAddressModel($id){
        $dao = new DaoAddress();
        return $dao->SearchById($id); 
    }
    
     public function UpdateAddress(ModelAddress $addr){
        $dao = new DaoAddress();
        $dao->Update($addr);         
    
    }
    
 
}



?>
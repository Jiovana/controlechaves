<?php

include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/dao/dao_borrowing.php";
require_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/etc/mailsender.php";
include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_requester.php";
include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_key.php";
include_once "//SERVIDOR/BKP-Novo/Financeiro-5/ControleChaves/XAMPP/htdocs/controlechaves/src/control/control_address.php";
/**
* Reune metodos para interacao entre a view ( interface ) relacionado a chave e o model( modelos e daos ) - ModelBorrowing e DaoBorrowing
*
*/
class ControlBorrowing {

    /**
     * Insere novo borrowing no banco
     *
     * 
     * @param ModelBorrowing objeto borrowing a ser inserido
     * @return int o id do emprestimo inserido.
     * 
    */
    public function NewBorrowing( ModelBorrowing $borrow ) {
        $dao = new DaoBorrowing();
        try {
            $dao->InsertBorrow( $borrow );
            $borw = $dao->SearchIdLimit1();
            return $borw->getId();

        } catch ( Exception $e ) {
            echo "Error in method NewBorrowing in ControlBorrowing: ".$e->getMessage()."</br>";
        }

    }

     /**
     * Insere novo relacionamento keys_borrowing no banco
     *
     * 
     * @param int $keys_id id da chave
     * @param int $borrow_id id de borrowing
     * 
    */
    public function NewKeysBorrowing( $key_id, $borrow_id ) {
        $dao = new DaoBorrowing();
        try {
            $dao->InsertBorrowKey($borrow_id, $key_id);
        } catch ( Exception $e ) {
            echo "Error in method NewKeysBorrowing in ControlBorrowing: ".$e->getMessage()."</br>";
        }
    }
    
    /**
     * Busca o id de um borrow com base no id da chave associada.
     *
     * 
     * @param int $keyid id da chave
     * @return int id de borrowing
     * 
    */
    public function FetchBorrowIdByKey($keyid){
        $dao = new DaoBorrowing();
        try {
            return $dao->SearchBorrowByKey($keyid);
        } catch ( Exception $e ) {
            echo "Error in method NewKeysBorrowing in ControlBorrowing: ".$e->getMessage()."</br>";
        }
    }
    
     /**
     * Atualiza data de checkin de um borrowing
     *
     * 
     * @param int $borrow_id id do borrowing a ser atualizado
     * 
    */
    public function UpdateCheckin($borrow_id){
        $dao = new DaoBorrowing();
        try {
            date_default_timezone_set('America/Sao_Paulo');
            $date = date('Y-m-d H:i:s', time());
            
            $dao->UpdateCheckin($borrow_id, $date);
        } catch ( Exception $e ) {
            echo "Error in method UpdateCheckin in ControlBorrowing: ".$e->getMessage()."</br>";
        }
    }
    
    public function DeactiveKeysBorrow($keyid){
        $dao = new DaoBorrowing();
        $dao->CloseKeysBorrowing($keyid);
    }
    
    public function FindActiveKeysBorrowing(){
        $dao = new DaoBorrowing();
        return $dao->SearchActiveBorrowKey();
    }
    
    public function FetchCheckinRequester($borrow_id){
        $dao = new DaoBorrowing();
        $arr = $dao->SelectCheckinRequester($borrow_id);   
        $arr["data_checkin"] = date_format( date_create( $arr["data_checkin"]), 'd/m/Y H:i:s' );
        return $arr;       
    }
    
    //this one sends emails when a key is borrowed.
     public function SendEmailBorrowing($borrow_id, $key_id){ 
         $arr = $this->FetchCheckinRequester($borrow_id);
         // needs requester info, email and name
         $conr = new ControlRequester();
         $requester = $conr->FetchRequesterModel($arr['requester_id']);
         // neeed checkin date
         $date = $arr['data_checkin'];
         // need key info - num gancho, adress string
         $conk = new ControlKey();
         $cona = new ControlAddress();
         $key = $conk->GetKeyModel($key_id);
         $address = $cona->GetAddressString($key->getEnderecoId());
         //create mail fields - $recipient_mail, $recipient_name, $subject, $body, $altbody
         $subject = "Notificação de Empréstimo de Chave";
         $body = "Olá, ".$requester->getNome().".<br>Você pegou emprestada a chave ".$key->getGancho()." na JW Imobiliária.<br>O endereço do imóvel é <b>".$address."</b><br>Por favor, lembre-se de devolver até o horário especificado: <b>".$date."</b>.";
         $altbody = "Ola, ".$requester->getNome().". Voce pegou emprestada a chave ".$key->getGancho()." na JW Imobiliaria. O endereço do imovel e ".$address." Por favor, lembre-se de devolver ate o horario especificado: ".$date.".";
        
         // if requester is client:
          if ($requester->getTipo() == "Cliente"){
              $body = $body."<br> Boa visitação!";
              $altbody = $altbody." Boa visitacao!";
          }
              
        // echo $body;
         MailSender($requester->getEmail(), $requester->getNome(), $subject, $body, $altbody);
         
     }
    
    //this one sends emails when a key is overdue.
    public function SendEmailOnOverdue($borrow_id, $key_id){       
         $arr = $this->FetchCheckinRequester($borrow_id);
         // needs requester info, email and name
         $conr = new ControlRequester();
         $requester = $conr->FetchRequesterModel($arr['requester_id']);
         // neeed checkin date
         $date = $arr['data_checkin'];
         // need key info - num gancho, adress string
         $conk = new ControlKey();
         $cona = new ControlAddress();
         $key = $conk->GetKeyModel($key_id);
         $address = $cona->GetAddressString($key->getEnderecoId());
         //create mail fields - $recipient_mail, $recipient_name, $subject, $body, $altbody
         $subject = "Notificação de Atraso na Devolução de Chave";
         $body = "Olá, ".$requester->getNome().".<br>Lembramos que você pegou emprestada a chave ".$key->getGancho().", endereço <b>".$address."</b> na JW Imobiliária.<br>O horário de devolução especificado foi: <b>".$date."</b>. <br> A chave agora está <b>atrasada</b>.<br> Por favor, dirija-se à imobiliária para devolver a chave assim que possível.";
         $altbody = "Ola, ".$requester->getNome().". Lembramos que voce pegou emprestada a chave ".$key->getGancho().", endereço ".$address." na JW Imobiliaria. O horario de devolucao especificado foi: ".$date.". A chave agora esta ATRASADA. Por favor, dirija-se a imobiliaria para devolver a chave assim que possivel.";
              
        // echo $body;
         MailSender($requester->getEmail(), $requester->getNome(), $subject, $body, $altbody);
    }
    
    //this one sends emails 30 minutes before a key is overdue.
    public function SendEmailBeforeOverdue($borrow_id, $key_id){       
         $arr = $this->FetchCheckinRequester($borrow_id);
         // needs requester info, email and name
         $conr = new ControlRequester();
         $requester = $conr->FetchRequesterModel($arr['requester_id']);
         // neeed checkin date
         $date = $arr['data_checkin'];
         // need key info - num gancho, adress string
         $conk = new ControlKey();
         $cona = new ControlAddress();
         $key = $conk->GetKeyModel($key_id);
         $address = $cona->GetAddressString($key->getEnderecoId());
         //create mail fields - $recipient_mail, $recipient_name, $subject, $body, $altbody
         $subject = "Lembrete de Devolução de Chave";
         $body = "Olá, ".$requester->getNome().".<br>Lembramos que você pegou emprestada a chave ".$key->getGancho().", endereço <b>".$address."</b> na JW Imobiliária.<br>O horário de devolução especificado foi: <b>".$date."</b>. <br> Por favor, dirija-se à imobiliária para devolver a chave até o horário previsto.";
         $altbody = "Ola, ".$requester->getNome().". Lembramos que voce pegou emprestada a chave ".$key->getGancho().", endereço ".$address." na JW Imobiliaria. O horario de devolucao especificado foi: ".$date.". Por favor, dirija-se a imobiliaria para devolver a chave ate o horario previsto.";
              
        // echo $body;
         MailSender($requester->getEmail(), $requester->getNome(), $subject, $body, $altbody);
    }
    
    
    public function ChangeRemindStatus($keys_borrow_id){
        $dao = new DaoBorrowing();
        $dao->ActivateReminder($keys_borrow_id);
    }
         

    
}
$con = new ControlBorrowing();

//$con->SendMailBorrowing(25,18);


?>
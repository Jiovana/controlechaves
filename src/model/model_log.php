<?php
/**
* Simula entidade log do banco de dados, possuindo todos seus atributos. Armazena logs de atualizacao das chaves.
*
* Comunicacao com getters e setters
*/

class ModelLog {

    private $id;
    private $description;
    // breve descricao da operacao
    private $operation;
    // acao que ocorreu na chave, pode ser 1 - criacao, 2 - atualizacao, 3 - emprestimo, 4 - devolucao
    private $data;
    // data autmatica pelo banco
    private $keys_id;
    // chave que esta sendo alterada
    private $user_id;
    // usuario que efetuou a acao

    //////////////////////////////////////

    public function setId( $id ) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
    /////////////////////////////////////////////

    public function setDescription( $description ) {
        $this->description = $description;
    }

    public function getDescription() {
        return $this->description;
    }
    //////////////////////////////////////////

    public function setData( $date ) {
        $this->data = $date;
    }

    public function getData() {
        $date = date_create( $this->data );

        return date_format( $date, 'd/m/Y' );

    }
    ///////////////////////////////////////////

    public function setKeys_id( $keys_id ) {
        $this->keys_id = $keys_id;
    }

    public function getKeys_id() {
        return $this->keys_id;
    }
    ////////////////////////////////////////////

    public function setUser_id( $user_id ) {
        $this->user_id = $user_id;
    }

    public function getUser_id() {
        return $this->user_id;
    }
    //////////////////////////////////////////

    public function setOperation( $operation ) {
        $this->operation = $operation;
    }

    public function getOperation() {
        return $this->operation;
    }

}

?>
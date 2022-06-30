<?php
/**
* Simula entidade requester do banco de dados, possuindo todos seus atributos.
*
* Comunicacao com getters e setters
*/

class ModelRequester {

    private $id;
    private $nome;
    // nome completo do requerente
    private $email;
    // opcional
    private $telefone;
    // opcional
    private $ddd;
    private $documento;
    // opcional - usada por pessoas externas
    private $tipo;
    // cliente, prestador de servico ou interno ( manutencao, vistoria, diretoria )

    private function __clone() {
    }

    public function __construct() {
    }

    //getters e setters dos atributos

    public function setId( $id ) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setNome( $nome ) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setEmail( $email ) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setTelefone( $telefone ) {
        $this->telefone = $telefone;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setDdd( $ddd ) {
        $this->ddd = $ddd;
    }

    public function getDdd() {
        return $this->ddd;
    }

    public function setDocumento( $documento ) {
        $this->documento = $documento;
    }

    public function getDocumento() {
        return $this->documento;
    }

    public function setTipo( $tipo ) {
        $this->tipo = $tipo;
    }

    public function getTipo() {
        return $this->tipo;
    }

}

?>
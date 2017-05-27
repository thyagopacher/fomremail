<?php

/*
 * classe para rma_cliente_final
 */

Class Cliente {

    public $id_rma;
    private $conexao;

    public function __construct($conn) {
        $this->conexao = $conn;
    }

    public function __destruct() {
        unset($this);
    }

    public function inserir() {
        return $this->conexao->inserir("rma_cliente_final", $this);
    }

    public function atualizar() {
        return $this->conexao->atualizar("rma_cliente_final", $this);
    }

    public function excluir() {
        return $this->conexao->excluir("rma_cliente_final", $this);
    }

}

<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
date_default_timezone_set('America/Sao_Paulo');

/**
 * pega o dia de ontem no padrão americano 
 * @return string data de ontem
 */


class Conexao {
 
    public $host = '';
    public $usuario = '';
    public $senha = '';
    public $banco = '';
    public $porta = '3306';
    private $resultado;
    public $colunas = array();
    public $chave_primaria;
    public $conexao;

    function __construct($usuario = null, $senha = null, $enderecoip = null, $banco = null) {
        if (isset($usuario) && $usuario != NULL && $usuario != "") {
            $this->banco = $banco;
            $this->host = $enderecoip;
            $this->usuario = $usuario;
            $this->senha = $senha;
        }
        $this->conectar();
    }

    function __destruct() {
        if ($this->conexao != FALSE) {
            mysqli_close($this->conexao);
        }
    }

    public function conectar() {
        $this->conexao = mysqli_connect($this->host, $this->usuario, $this->senha, $this->banco, $this->porta);
        mysqli_set_charset($this->conexao, 'utf8');
    }
  
    /* retorna mysql_query */
    public function comando($query) {
        if(isset($query) && $query != NULL && $query != ""){
            return mysqli_query($this->conexao, $query);
        }else{
            return false;
        }
    }

    public function comandoArray($query) {
        return mysqli_fetch_array($this->comando($query));
    }

    /*     * retorna a quantidade de resultados da consulta */

    public function qtdResultado($resultado) {
        return mysqli_num_rows($resultado);
    }

    public function resultadoArray($resultado = null) {
        if ($resultado != NULL) {
            $this->resultado = $resultado;
        }
        return mysqli_fetch_array($this->resultado);
    }

    public function inserir($tabela, $objeto) {
        $valores = '';
        $campos = '';
        $res = $this->comando('DESC ' . $tabela);
        if ($this->qtdResultado($res) > 0) {
            while ($campo = $this->resultadoArray($res)) {
                $campoNome = $campo['Field'];
                $campoChave = $campo['Key'];
                if (($campoChave != 'PRI' || $campoNome == "codempresa") && isset($objeto->$campoNome) && $objeto->$campoNome != NULL && $objeto->$campoNome != '') {
                    $objeto->$campoNome = addslashes($objeto->$campoNome);
                    $campos .= "{$campoNome},";
                    if ($campoNome == "dtcadastro" && ($objeto->$campoNome == NULL || $objeto->$campoNome == "")) {
                        $valores .= '"' . date("Y-m-d H:i:s") . '",';
                    } elseif ($campo['Type'] === 'text') {
                        $valores .= '"' . $objeto->$campoNome . '",';
                    } elseif ($campo['Type'] === 'date' && strpos($campo['Type'], '/')) {
                        $valores .= '"' . implode('-', array_reverse(explode('/', $objeto->$campoNome))) . '",';
                    } elseif ($campo['Type'] === 'double' && strpos($objeto->$campoNome, ',')) {
                        $valores .= '"' . str_replace(',', '.', $objeto->$campoNome) . '",';
                    } elseif ($campo['Type'] == "int(11)") {
                        $valores .= '"' . (int) $objeto->$campoNome . '",';
                    } elseif ($campoNome == "codempresa" && ($objeto->$campoNome == NULL || $objeto->$campoNome == "")) {
                        $valores .= '"' . (int) $_SESSION["codempresa"] . '",';
                    } elseif ($campoNome == "codfuncionario" && ($objeto->$campoNome == NULL || $objeto->$campoNome == "")) {
                        $valores .= '"' . (int) $_SESSION["codpessoa"] . '",';
                    } else {
                        $valores .= '"' . $objeto->$campoNome . '",';
                    }
                }
            } 
        }

        $sql = 'insert into ' . $tabela . '(' . substr($campos, 0, strlen(trim($campos)) - 1) . ') values(' . substr($valores, 0, strlen(trim($valores)) - 1) . ')';
        return mysqli_query($this->conexao, $sql);
    }

    public function atualizar($tabela, $objeto) {
        $setar = '';
        $where = '';
        $chavePrimaria = 0;
        $res = $this->comando('DESC ' . $tabela);
        if ($this->qtdResultado($res) > 0) {
            while ($campo = $this->resultadoArray($res)) {
                $campoNome = $campo['Field'];
                $campoChave = $campo['Key'];
                $objeto->$campoNome = addslashes($objeto->$campoNome);
                if ($campoChave != 'PRI' && isset($objeto->$campoNome) && $objeto->$campoNome != NULL && $objeto->$campoNome != '') {

                    if ($campo['Type'] === 'text') {
                        $setar .= $campoNome . ' = "' . $objeto->$campoNome . '", ';
                    } elseif ($campo['Type'] === 'date' && strpos($campo['Type'], '/')) {
                        $setar .= $campoNome . ' = "' . implode('-', array_reverse(explode('/', $objeto->$campoNome))) . '", ';
                    } elseif ($campo['Type'] === 'double' && strpos($objeto->$campoNome, ',')) {
                        $setar .= $campoNome . ' = "' . (double) str_replace(',', '.', $objeto->$campoNome) . '", ';
                    } elseif ($campo['Type'] == "int(11)") {
                        $setar .= $campoNome . ' = "' . (int) $objeto->$campoNome . '", ';
                    } else {
                        $setar .= $campoNome . ' = "' . $objeto->$campoNome . '", ';
                    }
                } elseif ($campoChave === 'PRI') {
                    $chavePrimaria = $objeto->$campoNome;
                    $where .= $campoNome . ' = "' . $chavePrimaria . '"';
                }
            }
        }

        $sql = 'update ' . $tabela . ' set ' . substr($setar, 0, strlen(trim($setar)) - 1) . ' where ' . $where;
        return mysqli_query($this->conexao, $sql);
    }

    public function excluir($tabela, $objeto) {
        $where = '';
        $res = $this->comando('DESC ' . $tabela);
        $chavePrimaria = 0;
        if ($this->qtdResultado($res) > 0) {
            while ($campo = $this->resultadoArray($res)) {
                $campoNome = $campo['Field'];
                $campoChave = $campo['Key'];
                if ($campoChave == 'PRI') {
                    $chavePrimaria = $objeto->$campoNome;
                    $where .= $campoNome . '= "' . $chavePrimaria . '"';
                    break;
                }
            }
        }
 
        $sql = 'delete from ' . $tabela . ' where ' . $where;
        return mysqli_query($this->conexao, $sql);
    }

    public function procurarCodigo($tabela, $objeto) {
        $where = '';
        $res = $this->comando('DESC ' . $tabela);
        $qtdTabela = $this->qtdResultado($res);
        if ($qtdTabela > 0) {
            while ($campo = $this->resultadoArray($res)) {
                $campoNome = $campo['Field'];
                $campoChave = $campo['Key'];
                if ($campoChave == 'PRI') {
                    $where .= $campoNome . '= "' . $objeto->$campoNome . '"';
                    break;
                }
            }
        }

        $sql = 'select * from ' . $tabela . ' where ' . $where;
        return $this->comandoArray($sql);
    }
 
    
}

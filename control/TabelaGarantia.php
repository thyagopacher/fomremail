<?php

function __autoload($class_name) {
    if (file_exists("../model/" . $class_name . '.php')) {
        include "../model/" . $class_name . '.php';
    } elseif (file_exists("../visao/" . $class_name . '.php')) {
        include "../visao/" . $class_name . '.php';
    } elseif (file_exists("./" . $class_name . '.php')) {
        include "./" . $class_name . '.php';
    }
}

$conexao = new Conexao();
$cliente = new Cliente($conexao);

$clientep = $conexao->comandoArray("select * from rma_cliente_final where id_rma = '{$_GET["id_rma"]}'");
$nomeCompleto = strtoupper($clientep["cliente_nome"]. ' '. $clientep["cliente_sobrenome"]);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Solicitação de Garantia - <?=$nomeCompleto?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <style>
            body {
                font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
                font-size: 14px;
                line-height: 1.42857143;
                color: #333;
                background-color: #fff;
            }            
         
            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                border-top: 1px solid #ddd;
            }            
        </style>
      <!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
    </head>
    <body>

        <div class="container">        
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="color: orange" colspan="3">Solicitação de Garantia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Nome</td>
                        <td><?=$nomeCompleto?></td>
                    </tr>
                    <tr>
                        <td>E-mail</td>
                        <td><?=$clientep["cliente_email"]?></td>
                    </tr>
                    <tr>
                        <td>Número de Telefone</td>
                        <td><?=$clientep["cliente_telefone"]?></td>
                    </tr>
                    <tr>
                        <td>Endereço</td>
                        <td>
                            <?php
                                echo "Endereço: ".strtoupper($clientep["cliente_endereco"])." - Número: {$clientep["cliente_numero"]}<br>";
                                echo "Bairro: ".strtoupper($clientep["cliente_bairro"])."<br>";
                                echo "Complemento: ".strtoupper($clientep["cliente_endereco2"])."<br>";
                                echo "Cidade: ".strtoupper($clientep["cliente_cidade"])."<br>";
                                echo "Estado: ".strtoupper($clientep["cliente_estado"])."<br>";
                                echo "CEP: ".$clientep["cliente_cep"]."<br>";
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Produto</td>
                        <td><?= strtoupper($clientep["cliente_produto"])?></td>
                    </tr>
                    <tr>
                        <td>O que aconteceu</td>
                        <td><?=strtoupper($clientep["cliente_problema"])?></td>
                    </tr>
                    <tr>
                        <td>Arquivos</td>
                        <td>
                            <?php
                                $resarquivo = $conexao->comando("select arquivo from rma_arquivos_final where id_rma = {$_GET["id_rma"]}");
                                $qtdarquivo = $conexao->qtdResultado($resarquivo);
                                if($qtdarquivo > 0){
                                    $linhaArquivo = 1;
                                    while($arquivo = $conexao->resultadoArray($resarquivo)){
                                        echo " - <a target='_blank' href='{$arquivo["arquivo"]}'>Arquivo {$linhaArquivo}</a> ";
                                        $linhaArquivo++;
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </body>
</html>


<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$email_sac = 'sac@bhxbrands.com';
//$email_sac = 'thyago.pacher@gmail.com';
                
$chaveGoogle = '';

if (isset($_POST['g-recaptcha-response'])) {
    $captcha_data = $_POST['g-recaptcha-response'];
}
$resposta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$chaveGoogle}&response=" . $captcha_data . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
$resposta = json_decode($resposta);

if (!$resposta->success) {
    die(json_encode(array('mensagem' => "Usuário mal intencionado detectado. A mensagem não foi enviada!", 'situacao' => false, 'recaptcha' => true)));
}

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

$variables = (strtolower($_SERVER['REQUEST_METHOD']) == 'GET') ? $_GET : $_POST;
foreach ($variables as $key => $value) {
    $key = 'cliente_' . $key;
    $cliente->$key = $value;
}
if (!isset($cliente->cliente_email) || $cliente->cliente_email == NULL || $cliente->cliente_email == "") {
    die(json_encode(array('mensagem' => 'Por favor preencha e-mail!', 'situacao' => false)));
}
if (!isset($cliente->cliente_nome) || $cliente->cliente_nome == NULL || $cliente->cliente_nome == "") {
    die(json_encode(array('mensagem' => 'Por favor preencha nome!', 'situacao' => false)));
}

if (isset($cliente->id_rma) && $cliente->id_rma != NULL && $cliente->id_rma != "") {
    $resSalvarCliente = $cliente->atualizar();
} else {
    $sql = "select id_rma 
    from rma_cliente_final 
    where cliente_email = '{$cliente->cliente_email}' 
    and cliente_problema like '{$cliente->cliente_problema}'    
    and cliente_produto = '{$cliente->cliente_produto}'";
    $clientep = $conexao->comandoArray($sql);
    if (isset($clientep["id_rma"]) && $clientep["id_rma"] != NULL && $clientep["id_rma"] != "") {
        die(json_encode(array('mensagem' => 'Por favor aguarde, sua mensagem já foi enviada e em breve estaremos respondendo!', 'situacao' => false)));
    }
    $resSalvarCliente = $cliente->inserir();
    $cliente->id_rma = mysqli_insert_id($conexao->conexao);
}

if ($resSalvarCliente != FALSE) {
    if ($_FILES['arquivo']) {
        $linhaArquivo = 0;
        $file_ary = reArrayFiles($_FILES['arquivo']);
        foreach ($file_ary as $file) {
            $separa_arquivo = explode('.', $file['name']);
            $nome_arquivo = "garantia_idrma{$cliente->id_rma}-$linhaArquivo-data" . date("YmdHis") . '.' . $separa_arquivo[1];
            $resMoveUpload = move_uploaded_file($file['tmp_name'], '../arquivos/' . $nome_arquivo);
            if ($resMoveUpload == FALSE) {
                die(json_encode(array('mensagem' => 'Solicitação cadastrada, porém arquivos não poderam ser carregados ao servidor!', 'situacao' => false)));
            } else {
                $arquivo = new Arquivo($conexao);
                $arquivo->id_rma = $cliente->id_rma;
                $arquivo->arquivo = 'http://' . $_SERVER['SERVER_NAME'] . '/arquivos/' . $nome_arquivo;
                $arquivo->inserir();
            }
            $linhaArquivo++;
        }
    }

    $assunto = "Solicitação de Garantia - " . strtoupper($cliente->cliente_nome . ' ' . $cliente->cliente_sobrenome);
    $headers = "From: Thyago Henrique Pacher <programador@sitesesistemaspg.com>\n";
    $headers .= "Content-type: text/html; charset=utf-8\n";
    $headers .= "Bcc: {$cliente->cliente_email}\r\n";
    $mensagem = AbreSite('http://' . $_SERVER['SERVER_NAME'] . '/control/TabelaGarantia.php?id_rma=' . $cliente->id_rma);
    mail($email_sac, $assunto, $mensagem, $headers);
    die(json_encode(array('mensagem' => 'Mensagem enviada, aguarde em breve responderemos!!!', 'situacao' => true)));
} else {
    die(json_encode(array('mensagem' => 'Mensagem não enviada, erro: ' . mysqli_error($conexao->conexao), 'situacao' => false)));
}

function reArrayFiles(&$file_post) {
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);
    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }
    return $file_ary;
}

function AbreSite($url, $dados = NULL) {
    $site_url = $url;
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, $site_url);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    if (isset($dados) && $dados != NULL) {
        //parametros em post
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
    }
    ob_start();
    curl_exec($ch);
    curl_close($ch);
    $file_contents = ob_get_contents();
    ob_end_clean();
    return $file_contents;
}

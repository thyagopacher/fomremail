<?php
$array_produtos = array('Energy2U - Powerbank Portatil', 'Energy2U - Cabo',
    'Energy2U - Tomada', 'Energy2U - Fone de Ouvido', 'Energy2U - Fond de Ouvido Bluetooth', 'Energy2U - Caixa de Som', 'Outro');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Formulário de Contato</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
        <script src='https://www.google.com/recaptcha/api.js'></script>
    </head>
    <body>
        <div class="container-fluid text-center">    
            <div class="row content">
                <div class="col-sm-2"></div>
                <div class="col-sm-8 text-left"> 
                    <form id="fcontato" action="../control/EnviarEmail.php" method="post" class="form-horizontal">
                        <legend>Solicitação de Garantia</legend>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Nome</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="nome" id="nome" minlength="3" maxlength="100" placeholder="Digite nome">
                            </div>
                            <label class="control-label col-sm-2">Sobrenome</label>
                            <div class="col-sm-4">
                                <input type="text" required class="form-control" name="sobrenome" id="sobrenome" minlength="3" maxlength="100" placeholder="Digite nome">
                            </div>                            
                        </div>                        
                        <div class="form-group">
                            <label class="control-label col-sm-2">Email:</label>
                            <div class="col-sm-10">
                                <input type="email" required class="form-control" name="email" id="email" minlength="5" maxlength="50" placeholder="Digite email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Telefone:</label>
                            <div class="col-sm-10">
                                <input type="tel" required class="form-control" name="telefone" id="telefone" minlength="5" maxlength="20" placeholder="Digite telefone">
                            </div>
                        </div>                        
                        <div class="form-group">
                            <label class="control-label col-sm-2">CEP:</label>
                            <div class="col-sm-10"> 
                                <input type="text" required class="form-control" name="cep" id="cep" minlength="8" maxlength="8" placeholder="Digite cep">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Endereço:</label>
                            <div class="col-sm-6"> 
                                <input type="text" required class="form-control" name="endereco" id="endereco" minlength="5" maxlength="200" placeholder="Digite endereço">
                            </div>
                            <label class="control-label col-sm-2">Número:</label>
                            <div class="col-sm-2"> 
                                <input type="text" class="form-control" name="numero" id="numero" minlength="1" maxlength="20" placeholder="Digite número">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Bairro:</label>
                            <div class="col-sm-4"> 
                                <input type="text" required class="form-control" name="bairro" id="bairro" minlength="5" maxlength="40" placeholder="Digite bairro">
                            </div>
                            <label class="control-label col-sm-2">Complemento:</label>
                            <div class="col-sm-4"> 
                                <input type="text" class="form-control" name="endereco2" id="endereco2" minlength="5" maxlength="50" placeholder="Digite complemento">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Cidade:</label>
                            <div class="col-sm-4"> 
                                <input type="text" required class="form-control" name="cidade" id="cidade" minlength="5" maxlength="50" placeholder="Digite cidade">
                            </div>
                            <label class="control-label col-sm-2">Estado:</label>
                            <div class="col-sm-4"> 
                                <input type="text" required class="form-control" name="estado" id="estado" minlength="2" maxlength="2" placeholder="Digite estado">
                            </div>                            
                        </div>
                        <legend>Dados do Produto</legend>
                        <div class="form-group">
                            <label class="control-label col-sm-2">Produto:</label>
                            <div class="col-sm-10"> 
                                <select name="produto" id="produto" required class="form-control">
                                    <?php
                                    if (count($array_produtos) > 0) {
                                        echo "<option value=''>--Selecione--</option>";
                                        foreach ($array_produtos as $key => $produto) {
                                            echo "<option value='$produto'>$produto</option>";
                                        }
                                    } else {
                                        echo "<option value=''>--Nada encontrado--</option>";
                                    }
                                    ?>
                                </select>
                            </div>                          
                        </div>   
                        <div class="form-group">
                            <label class="control-label col-sm-2">O que aconteceu:</label>
                            <div class="col-sm-10"> 
                                <textarea required name="problema" id="problema" minlength="5" maxlength="300" class="form-control" placeholder="Digite o problema"></textarea>
                            </div>                          
                        </div>                         
                        <div class="form-group">
                            <label class="control-label col-sm-2">Arquivos:</label>
                            <div class="col-sm-10"> 
                                <input type="file" name="arquivo[]" id="arquivos" class="form-control" multiple  accept="application/msword, application/vnd.ms-excel, text/plain, application/pdf, image/*"/>
                            </div>                          
                        </div>                         
                        <div class="form-group"> 
                            <div class="col-sm-offset-2 col-sm-10">   
                                <div class="g-recaptcha" data-sitekey="6LeXJwoUAAAAABAZDVCn3BajnKvyMqD2pDZIWak3"></div>
                                <button type="submit"  class="btn btn-primary">Enviar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-2"></div>
            </div>
        </div>
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
        <script type="text/javascript" src="../visao/js/Geral.js?<?=date("YmdHis")?>"></script>
        <script type="text/javascript" src="../visao/js/Contato.js"></script>
    </body>
</html>

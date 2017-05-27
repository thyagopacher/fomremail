//faz a mascara dinamica entre celular ou fixo
$("#telefone").keyup(function () {
    var num = $("#telefone").val().toString().replace(')', '').replace('(', '').replace('-', '').substring(2, 3);
    var digitos_celular = ['7', '8', '9'];
    if (digitos_celular.indexOf(num) != -1) {
        $("#telefone").mask("(99)99999-9999");
    } else {
        $("#telefone").mask("(99)9999-9999");
    }
    console.log(num);
});

$("#telefone").mask("(99)99999999#9");

$("#cep").blur(function () {
    if ($("#cep").val() != "" && $("#cep").val().length >= 8) {
        $.ajax({
            url: "../control/BuscaCep.php",
            type: "POST",
            data: {cep: $("#cep").val()},
            dataType: 'json',
            success: function (data, textStatus, jqXHR) {
                $("#endereco").val(data.tipologradouro + ' ' + data.logradouro);
                $("#cidade").val(data.cidade);
                $("#estado").val(data.uf);
                $("#bairro").val(data.bairro);
            }, error: function (jqXHR, textStatus, errorThrown) {
                swal("Erro", "Erro causado por:" + errorThrown, "error");
            }
        });
    }
});
window.onload = function(){
    document.getElementById("nf-field-22").addEventListener("click", enviar);
};

var enviar = function () {
    var nome = document.getElementById("nf-field-19").value
    var telefone = document.getElementById("nf-field-23").value
    var email = document.getElementById("nf-field-20").value
    var como = document.getElementById("nf-field-24").value
    var msg = document.getElementById("nf-field-21").value
    
    var url = "https://api.whatsapp.com/send?phone=5511985370122&text=" .concat("Nome :",nome,"\nTelefone:",telefone,"\nE-mail: ",email,"\nConheci por: ",como,"\nMensagem: ",msg);
    url = encodeURI(url);
    window.open(url, "_blank");
};

function consultaApolicesAjax() {

	document.getElementById("vendas_banco") ? vendas_banco = document.getElementById("vendas_banco").value : vendas_banco = "";
	 //document.getElementById("vendas_apolice") ? vendas_apolice = document.getElementById("vendas_apolice").value : vendas_apolice = "";
  if(document.getElementsByName('vendas_apolice'))
  {
    var vendas_apolice_element = document.getElementsByName('vendas_apolice');
    var vendas_apolice;
    for(var i = 0; i < vendas_apolice_element.length; i++)
    {
        if(vendas_apolice_element[i].checked)
        {
            vendas_apolice = vendas_apolice_element[i].value;
            vendas_apolice_element[i].innerHTML+="<pre class='nana' style='display:none'>"+vendas_apolice+"</pre>";
            break;
        }
    }
  }else
  {
    vendas_apolice = "";
  }
	document.getElementById("apolice_tipo") ? apolice_tipo = document.getElementById("apolice_tipo").value : apolice_tipo = "";
	document.getElementById("vendas_dia_desconto") ? vendas_dia_desconto = document.getElementById("vendas_dia_desconto").value : vendas_dia_desconto = "";

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("response-ajax-apolices").innerHTML = this.responseText;
     canSave();
    }
  };

  xhttp.open("GET", "sistema/consultas_ajax/consulta_apolices_ajax.php?vendas_banco="+vendas_banco+"&vendas_apolice="+vendas_apolice+"&apolice_tipo="+apolice_tipo+"&vendas_dia_desconto="+vendas_dia_desconto, true);
  xhttp.send();
}

function consultaFormaPagamentoAjax(apolice_pgto, apolice_dia_desconto)
{
  var user_id = document.getElementById("user_id").value;
  var vendas_banco = document.getElementById("vendas_banco").value;
  
  if(apolice_pgto > 0){
	vendas_pgto = apolice_pgto;
  }else{
	document.getElementById("vendas_pgto") ? vendas_pgto = document.getElementById("vendas_pgto").value : vendas_pgto = "";
  }
  document.getElementById("clients_cpf") ? clients_cpf = document.getElementById("clients_cpf").value : clients_cpf = "";
  
  if(vendas_pgto != "" && clients_cpf != ""){
    document.getElementById("response-ajax-formapagamento").innerHTML = "<div style='text-align: center;'><img src='sistema/imagens/loading.gif'></div>";
  }
  
  if(apolice_pgto > 0){
	document.getElementById("response-ajax-apolice-pgto").style.visibility = "hidden";
  }else{document.getElementById("response-ajax-apolice-pgto").style.visibility = "visible";}
  
  if(apolice_dia_desconto > 0){
	document.getElementById("response-ajax-apolice-dia-desconto").style.visibility = "hidden";
  }else{document.getElementById("response-ajax-apolice-dia-desconto").style.visibility = "visible";}

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("response-ajax-formapagamento").innerHTML = this.responseText;
     canSave();
    }
  };
  xhttp.open("GET", "sistema/consultas_ajax/consulta_forma_pagamento_ajax.php?vendas_banco="+vendas_banco+"&vendas_pgto="+vendas_pgto+"&apolice_pgto=1&clients_cpf="+clients_cpf+"&user_id="+user_id, true);
  xhttp.send();
}

function canSave(){
 if(document.getElementById("vendas_banco") &&
    document.getElementById("vendas_pgto") &&
    document.getElementById("vendas_telefone") &&
    document.getElementById("vendas_telefone2")){
    if(document.getElementById("vendas_banco").value !="" &&
       document.getElementById("vendas_pgto").value !="" &&
       document.getElementById("vendas_telefone").value !="" &&
       document.getElementById("vendas_telefone2").value !=""){
        document.getElementById("botao_salvar").innerHTML = "<button name='salvar' type='submit' value='salvar'>Salvar Venda</button>";
    }else
    {
      document.getElementById("botao_salvar").innerHTML = "<div style='color: red'>Preencha os campos corretamente para salvar.</div>";
    }
  }else{
    document.getElementById("botao_salvar").innerHTML = "<div style='color: red'>Preencha os campos corretamente para salvar.</div>";
  }
}
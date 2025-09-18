function consultaApolicesAjax(apoliceId) {

	document.getElementById("vendas_banco"+apoliceId) ? vendas_banco = document.getElementById("vendas_banco"+apoliceId).value : vendas_banco = "";
	document.getElementById("vendas_apolice"+apoliceId) ? vendas_apolice = document.getElementById("vendas_apolice"+apoliceId).value : vendas_apolice = "";
	document.getElementById("apolice_tipo") ? apolice_tipo = document.getElementById("apolice_tipo").value : apolice_tipo = "";
	document.getElementById("vendas_dia_desconto"+apoliceId) ? vendas_dia_desconto = document.getElementById("vendas_dia_desconto"+apoliceId).value : vendas_dia_desconto = "";

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("response-ajax-apolices"+apoliceId).innerHTML = this.responseText;
     //canSave(apoliceId);
    }
  };
  xhttp.open("GET", "sistema/consultas_ajax/consulta_apolices_ajax.php?vendas_banco="+vendas_banco+"&vendas_apolice="+vendas_apolice+"&apoliceId="+apoliceId+"&apolice_tipo="+apolice_tipo+"&vendas_dia_desconto="+vendas_dia_desconto, true);
  xhttp.send();
}

function consultaFormaPagamentoAjax(apoliceId) {

  document.getElementById("vendas_pgto"+apoliceId) ? vendas_pgto = document.getElementById("vendas_pgto"+apoliceId).value : vendas_pgto = "";

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("response-ajax-formapagamento"+apoliceId).innerHTML = this.responseText;
     //canSave(apoliceId);
    }
  };
  xhttp.open("GET", "sistema/consultas_ajax/consulta_forma_pagamento_ajax.php?vendas_pgto="+vendas_pgto, true);
  xhttp.send();
}

function canSave(apoliceId){
 if(document.getElementById("vendas_banco"+apoliceId) &&
    document.getElementById("vendas_pgto"+apoliceId))
    {
    if(document.getElementById("vendas_banco"+apoliceId).value !="" &&
       document.getElementById("vendas_pgto"+apoliceId).value !="")
    {
        document.getElementById("botao_salvar"+apoliceId).innerHTML = "<button id='salvar"+apoliceId+"' name='salvar"+apoliceId+"' type='submit' value='salvar'>Salvar Venda</button>";
    }else
    {
      document.getElementById("botao_salvar"+apoliceId).innerHTML = "<div style='color: red'>Preencha os campos corretamente para salvar.</div>";
    }
  }else{
    document.getElementById("botao_salvar"+apoliceId).innerHTML = "<div style='color: red'>Preencha os campos corretamente para salvar.</div>";
  }
}
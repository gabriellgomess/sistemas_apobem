<script>
$(document).ready(function(){
    consultaAnexosAjax();
});	
function anexoAjax(){

	if(document.getElementById('anexo_tipo')){ 
		if(document.getElementById('anexo_tipo').value != "")
		{
			anexo_tipo = document.getElementById('anexo_tipo').value;	
		}
		else
		{ 
			anexo_tipo = "99";
		}
	}
	

	document.getElementById('anexo_ctrl').style.display = "none";

	var arquivo = document.getElementById('upload');

	var file = arquivo.files[0];
	var fd = new FormData();
	fd.append("file", file);
	// Parametros extras.
	fd.append("vendas_id", "<?php echo $row['vendas_id']; ?>");				//id do funcionário
	fd.append("anexo_usuario", "<?php echo $username; ?>");				// id do usuário logado no sistema
	fd.append("anexo_documento", anexo_tipo);						// tipo de anexo

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'http://anexos.apobem.com.br/anexos/upload_file_seguro_consignado_ajax.php', true);

	var percentComplete = 0;

	xhr.upload.onprogress = function(e) {

		if(percentComplete == 0){
			document.getElementById('anexo_msg').innerHTML = "<span>Anexando Arquivo</span><br><div id='loading' style='display: inline-block; width: 25%; height: 20px; border: solid 2px #fff; background: linear-gradient(90deg, #04f900 0%, #ddd 0%);'></div>";
		}

		if (e.lengthComputable) {
		  percentComplete = (e.loaded / e.total) * 100;
		  console.log(percentComplete + '% uploaded');
		  document.getElementById('loading').style.background = "linear-gradient(90deg, #04f900 "+percentComplete+"%, #ddd 0%)";
		}
	};
	xhr.onload = function() {
		if (this.status == 200) {
		  var resp = this.response;
		  document.getElementById('anexo_msg').innerHTML = resp;

		  document.getElementById('upload').value = "";	  
		  
		  document.getElementById('anexo_ctrl').style.display = "initial";

		  consultaAnexosAjax();
	};
	};
	xhr.send(fd);
}

function consultaAnexosAjax(){	
	//var cpf = document.getElementById('clients_cpf').value;
	document.getElementById("anexos-ajax").innerHTML = "<div style='width: 100%; text-align: center;'><img src='/sistema/sistema/imagens/loading.gif' /></div>";

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("anexos-ajax").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", "sistema/vendas/blocos/anexos_consulta_ajax.php?user_id="+<?php echo $user_id; ?>+"&vendas_consultor="+<?php echo $row['vendas_consultor']; ?>+"&vendas_id="+"<?php echo $vendas_id; ?>"+"&administracao="+"<?php echo $administracao; ?>", true);
    xmlhttp.send();	
}

function removeAnexoAjax(id){
	if(confirm("Você tem certeza que deseja excluir este anexo?\nEsta ação não poderá ser desfeita!"))
	{
		document.getElementById("anexo_msg").innerHTML = "<div style='width: 100%; text-align: center;'><img src='/sistema/sistema/imagens/loading.gif' /></div>";

	    var xmlhttp = new XMLHttpRequest();
	    xmlhttp.onreadystatechange = function() {
	        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
	            document.getElementById("anexo_msg").innerHTML = xmlhttp.responseText;
	            consultaAnexosAjax();
	        }
	    };
	    xmlhttp.open("GET", "sistema/vendas/blocos/anexo_remove.php?anexo_id="+id, true);
	    xmlhttp.send();
	}
}
function enviaSmsAjax(){

	sms_to = document.getElementById('sms_to').value;
	var fd = new FormData();
	  fd.append("id", "<?php echo $vendas_id; ?>");
	  fd.append("to", "55"+sms_to);

	document.getElementById("envia-sms").innerHTML = "<div style='width: 100%; text-align: center;'><img src='/sistema/sistema/imagens/loading.gif' /></div>";
	
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4) {
        	if(xmlhttp.status == 200)
        	{
	        	//json = JSON.parse(xmlhttp.responseText);        	
	            //document.getElementById("envia-sms").innerHTML = json.sendSmsResponse.detailDescription;
	            document.getElementById("envia-sms").innerHTML = xmlhttp.responseText;
        	}else{
        		alert("Ocorreu um erro na tentativa do envio de SMS.\nErro: "+xmlhttp.status+"\nTente novamente.");
        		document.getElementById("envia-sms").innerHTML = '<img onclick="enviaSmsAjax()" src="images/sms.png" title="Enviar SMS!" style="float: left; margin: 0 5px; cursor: pointer;">';
        	}
        }
    };
    xmlhttp.open("POST", "http://www.grupofortune.com.br/portal/sistema/integracao/sms_producao/sms_api.php", true);
    xmlhttp.send(fd);	
}
</script>
<div class="linha">
	<div align="center"><h3 class="mypets2">Anexos:</h3></div>
	<div class="thepet2">
		<div id="anexos-ajax"></div>
		<div id="anexo_msg" class="linha" style="text-align: center;">&nbsp;</div>
		<img onclick="consultaAnexosAjax()" src="images/refresh_green.png" title="Clique aqui para atualizar os anexos!" style="width: 20px; height: 20px; float: right; margin: 0 5px; cursor: pointer;">
		<div align="center"><h3>Adicionar um anexo:</h3></div>
		<div id="anexo_ctrl">
			<div class="linha">
				<?php include("sistema/campos_genericos/select_anexos.php"); ?>
			</div>
			<div class="linha">
				<span class="button" style="height: 20px; font-size: 10px; line-height: 0; float: right;" onclick="anexoAjax()">Adicionar Anexo</span>									
			</div>		
		</div>
			<div class="linha">
				<div class="coluna campo-titulo">Solicitar via SMS:</div>
				<div class="coluna campo-valor">
					<div style="float: left;">
						<input type="text" name="sms_to" id="sms_to" value="<?php echo $row_client["cliente_telefone"];?>" size="15" onKeyPress="return SomenteNumero(event);"/>
					</div>
					<div id="envia-sms">
						<img onclick="enviaSmsAjax()" src="images/sms.png" title="Enviar SMS!" style="float: left; margin: 0 5px; cursor: pointer;">
					</div>
				</div>
			</div>
	</div>
</div>
<?php 
$vendas_id = $_GET['vendas_id']; ?>
<div class="linha">
    <h3 class="mypets2">Anexos: </h3>
    <div class="thepet2">
        <div class="linha">
			    <div class="thepet">

				<div id="anexos-ajax"></div>

				<div id="anexo_msg" class="linha" style="text-align: center;">&nbsp;</div>
			
			<div align="center"><h3>Adicionar um anexo:</h3></div>
				<div id="anexo_ctrl">
					<div class="linha">
						<div class="coluna campo-titulo">Tipo:</div>
						<div class="coluna campo-valor">
							<select id='anexo_documento' onChange="campoSMS();">
							<option value="">-- Selecione --</option>
							<?php
								$result_anexos_tipos = mysql_query("SELECT * FROM jos_users_anexos_tipos")
								or die(mysql_error());
								while($row_anexos_tipos = mysql_fetch_array( $result_anexos_tipos )) {								
									echo "<option value='{$row_anexos_tipos['tipo_nome']}'>{$row_anexos_tipos['tipo_nome']}</option>";
									#echo "<option value='{$row_anexos_tipos['tipo_id']}'>{$row_anexos_tipos['tipo_nome']}</option>";
								}
							?>	
							</select> 
							<span id="anexos_sms"></span> <span id="envia-sms"></span>
						</div>
						<div class="coluna campo-titulo">Anexo:</div>
						<div class="coluna campo-valor">
						  <input type="file" id="upload" />					  
						</div>					
					</div>
					<div class="linha">
						<span class="button" style="height: 20px; font-size: 10px; line-height: 0; float: right;" onclick="anexoAjax()">Adicionar Anexo</span>				
					</div>		
				</div>
			</div>
        </div>
    </div>
</div>

<script>

function anexoAjax(){
	if(document.getElementById('anexo_documento')){ anexo_documento = document.getElementById('anexo_documento').value; }else{ anexo_documento = ""; }
	if(document.getElementById('sms_to')){ sms_to = document.getElementById('sms_to').value; }else{ sms_to = ""; }
	if(document.getElementById('anexo_nota')){ anexo_nota = document.getElementById('anexo_nota').value; }else{ anexo_nota = ""; }

	/*document.getElementById('anexo_ctrl').style.display = "none";*/

	var arquivo = document.getElementById('upload');

	var file = arquivo.files[0];
	var fd = new FormData();
	fd.append("file", file);
	fd.append('vendas_id', "<?php echo $vendas_id ?>");
	fd.append('anexo_usuario', "<?php echo $username; ?>");
	fd.append('anexo_documento', anexo_documento);
	fd.append('sms_to', sms_to);

	var xhr = new XMLHttpRequest();
	if((anexo_documento == "Kit Boas Vindas")||(anexo_documento == "Boleto")){
		url_upload = "https://www.apobem.com.br/arquivos/upload_file_seguro_ajax.php";
	}else{
		url_upload = "http://anexos.apobem.com.br/anexos/upload_file_seguro_ajax.php";
		/* url_upload = "anexos2/upload_file_seguro_ajax.php"; */
	}
	xhr.open('POST', url_upload, true);


	/* MONITORA O PROGRESSO DO UPLOAD */
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
		
		enviaSmsAjax();
	};
	};
	xhr.send(fd);
}

function campoSMS(){
	anexo_documento = document.getElementById('anexo_documento').value;
	if((anexo_documento == "Kit Boas Vindas")||(anexo_documento == "Boleto")){
		document.getElementById('anexos_sms').innerHTML = "Enviar via SMS: <input type='text' name='sms_to' id='sms_to' placeholder='51999999999' size='10' max-length='11' onKeyPress='return SomenteNumero(event);'/>";
	}
}

function enviaSmsAjax(){
	anexo_documento = document.getElementById('anexo_documento').value;
	sms_to = document.getElementById('sms_to').value;
	anexo_caminho = document.getElementById('anexo_caminho').value;
	var fd = new FormData();
	  fd.append("cliente_nome", "<?php echo $row_client['cliente_nome'];?>");
	  fd.append("anexo_caminho", anexo_caminho);
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
	if(anexo_documento == "Boleto"){
		xmlhttp.open("POST", "https://www.grupofortune.com.br/portal/sistema/integracao/sms_producao/sms_boleto.php", true);
		xmlhttp.send(fd);	
	}else{
		xmlhttp.open("POST", "https://www.grupofortune.com.br/portal/sistema/integracao/sms_producao/sms_kit.php", true);
		xmlhttp.send(fd);	
	}	
}

function consultaAnexosAjax(){
	document.getElementById("anexos-ajax").innerHTML = "<div style='width: 100%; text-align: center;'><img src='/sistema/sistema/imagens/loading.gif' /></div>";

	var username = "";

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			document.getElementById("anexos-ajax").innerHTML = xmlhttp.responseText;
		}
	};
	xmlhttp.open("GET", "sistema/usuarios/consulta_anexo_ajax_seguros.php?vendas_id=<?php echo $vendas_id; ?>&username="+username, true);
	xmlhttp.send();	
}


function removeAnexoAjax(id, vendas_id){
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
	    xmlhttp.open("GET", "sistema/usuarios/remove_anexo_seguros.php?anexo_id="+id+"&vendas_id="+vendas_id+"&username=<?php echo $username; ?>", true);
	    xmlhttp.send();
	}
}

document.addEventListener('DOMContentLoaded', function(){
	consultaAnexosAjax();
});
</script>
<style>
	.alert-box{
		height: 30px;
		line-height: 30px;
	}
</style>
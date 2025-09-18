<link href="templates/gk_music/css/template.portal.css" rel="stylesheet" type="text/css" />
<script src="sistema/js/jquery-2.1.4.min.js"></script>
<link rel="stylesheet" href="sistema/js/jquery-ui-1.12.1/jquery-ui.min.css">
<script src="sistema/js/jquery-ui-1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$( ".datepicker" ).datepicker({
						    dateFormat: 'dd/mm/yy',
						    dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
						    dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
						    dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
						    monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
						    monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
						    nextText: 'Próximo',
						    prevText: 'Anterior'
						});
	});

	function mascaraData( campo, e )
	{
	    var kC = (document.all) ? event.keyCode : e.keyCode;
	    var data = campo.value;
	    
	    if( kC!=8 && kC!=46 )
	    {
	        if( data.length==2 )
	        {
	            campo.value = data += '/';
	        }
	        else if( data.length==5 )
	        {
	            campo.value = data += '/';
	        }
	        else
	            campo.value = data;
	    }
	}
</script>
<?php

if ($_GET["dp-normal-1"]){
$pag_data_ini = $_GET["dp-normal-1"];
$data_ini = implode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-1"]) == 0 ? "-" : "/", $_GET["dp-normal-1"])));
$filtros_data = $filtros_data." AND registro_data >= '" . $data_ini . " 00:00:00'";
}

if ($_GET["dp-normal-2"]){
$pag_data_fim = $_GET["dp-normal-2"];
$data_fim = implode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-2"]) == 0 ? "-" : "/", $_GET["dp-normal-2"])));
$filtros_data = $filtros_data." AND registro_data <= '" . $data_fim . " 23:59:59'";
}

if ($_GET["dp-normal-5"]){
$pag_venda_data_ini = $_GET["dp-normal-5"];
$venda_data_ini = implode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-5"]) == 0 ? "-" : "/", $_GET["dp-normal-5"])));
$filtros_data = $filtros_data." AND vendas_dia_venda >= '" . $venda_data_ini . " 00:00:00'";
}

if ($_GET["dp-normal-6"]){
$pag_venda_data_fim = $_GET["dp-normal-6"];
$venda_data_fim = implode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $_GET["dp-normal-6"]) == 0 ? "-" : "/", $_GET["dp-normal-6"])));
$filtros_data = $filtros_data." AND vendas_dia_venda <= '" . $venda_data_fim . " 23:59:59'";
}

if ($_GET["vendas_id"]) {$filtros_sql = $filtros_sql." AND sys_vendas_registros_seg.vendas_id = '" . $_GET['vendas_id'] . "'";}

if ($_GET["transacao_proposta"]) {$filtros_sql = $filtros_sql." AND vendas_proposta = '" . $_GET['transacao_proposta'] . "'";}

if ($_GET["registro_usuario"]) {$filtros_sql= $filtros_sql." AND registro_usuario like '%" . $_GET['registro_usuario'] . "%'";}

if ($_GET["vendas_banco"]) {$filtro_seguradora= $filtro_seguradora." AND vendas_banco = '" . $_GET['vendas_banco'] . "'";}


if ($_GET["registro_status"]){
$registro_status=$_GET["registro_status"];
				for ($i=0;$i<count($registro_status);$i++){
					if ($registro_status[$i] != ""){
						if ($i==0){
							$select_status = " AND (registro_status = '" . $registro_status[$i] . "'";
						}else{$select_status = $select_status." OR registro_status = '" . $registro_status[$i] . "'";}					
					}
					$aux_stat = $i;
				}
				if ($registro_status[$aux_stat] != ""){$select_status = $select_status.")";}
				for ($i=0;$i<count($registro_status);$i++){
					if ($registro_status[$i] != ""){
							$pag_status = $pag_status."&registro_status[]=".$registro_status[$i];					
					}
				}
	$filtros_sql= $filtros_sql.$select_status;
}

if($_GET["registro_cobranca"]){$filtros_sql = $filtros_sql." AND registro_cobranca = '" . $_GET['registro_cobranca'] . "'";}
if($_GET["registro_retencao"]){$filtros_sql = $filtros_sql." AND registro_retencao = '" . $_GET['registro_retencao'] . "'";}

$pesquisar = true;

if ($_GET["ordemi"]) {$ordem=$_GET["ordemi"];} else {$ordem="registro_id";}
if ($_GET["ordenacao"]) {$ordenacao=$_GET["ordenacao"];} else {$ordenacao="DESC";}

$link_excel = "sistema/vendas/historicos/consulta_excel.php?dp-normal-1=".$_GET["dp-normal-1"].
				"&dp-normal-2=".$_GET["dp-normal-2"].
				"&dp-normal-5=".$_GET["dp-normal-5"].
				"&dp-normal-6=".$_GET["dp-normal-6"].
				"&vendas_id=".$_GET["vendas_id"].
				"&transacao_proposta=".$_GET["transacao_proposta"].
				"&registro_usuario=".$_GET["registro_usuario"].
				"&vendas_banco=".$_GET["vendas_banco"].
				$pag_status.
				"&registro_cobranca=".$_GET["registro_cobranca"].
				"&registro_retencao=".$_GET["registro_retencao"].
				"&ordemi=".$_GET["ordemi"].
				"&ordenacao=".$_GET["ordenacao"];

$link_paginacao = "index.php?option=com_k2&view=item&layout=item&id=589&Itemid=619&dp-normal-1=".$_GET["dp-normal-1"].
				"&dp-normal-2=".$_GET["dp-normal-2"].
				"&dp-normal-5=".$_GET["dp-normal-5"].
				"&dp-normal-6=".$_GET["dp-normal-6"].
				"&vendas_id=".$_GET["vendas_id"].
				"&transacao_proposta=".$_GET["transacao_proposta"].
				"&registro_usuario=".$_GET["registro_usuario"].
				"&vendas_banco=".$_GET["vendas_banco"].
				$pag_status.
				"&registro_cobranca=".$_GET["registro_cobranca"].
				"&registro_retencao=".$_GET["registro_retencao"].
				"&ordemi=".$_GET["ordemi"].
				"&ordenacao=".$_GET["ordenacao"];;				

?>

	<a class="itemPrintLink" onclick="window.open(this.href,'printWindow','width=900,height=600,location=no,menubar=no,resizable=yes,scrollbars=yes'); return false;" rel="nofollow" href="<?php echo $link_excel; ?>">Exportar para Excel (Limite 5000 resultados)</a><br>

<?php

// Pegar a página atual por GET
$p = $_GET["p"];
// Verifica se a variável tá declarada, senão deixa na primeira página como padrão
if(isset($p)) {
$p = $p;
} else {
$p = 1;
}
// Defina aqui a quantidade máxima de registros por página.
$qnt = 20;
$inicio = ($p*$qnt) - $qnt;

$filtros_consulta = $filtros_sql . $filtro_seguradora . $filtros_data;

$sql = "SELECT registro_id,
				sys_vendas_registros_seg.vendas_id,
				vendas_banco,
				registro_usuario,
				registro_obs,
				status_nm,
				status_img,
				vendas_valor,
				DATE_FORMAT(registro_data,'%d/%m/%Y %H:%i:%s') AS registro_data 
				FROM sys_vendas_registros_seg
				LEFT JOIN sys_vendas_seguros ON (sys_vendas_registros_seg.vendas_id = sys_vendas_seguros.vendas_id) 
				INNER JOIN sys_vendas_status_seg ON registro_status = status_id 
				WHERE 1=1 ". $filtros_consulta .
				" ORDER BY " . $ordem . " " . $ordenacao . " LIMIT " . $inicio . ", " . $qnt . ";";

echo "<pre style='display: none;'>";
echo $sql;
echo "</pre>";
$result = mysql_query($sql) or die(mysql_error());

?>
<style type="text/css">
	.bloco_consulta_titulo, .bloco_agendamentos {
	    background: #2b5797;
	    color: white;
	    border-radius: 10px 10px 0 0;
	    margin: 0;
	}
	#table_cabecalho{
		margin-bottom: 0;
	}
	#painel_busca{
		border-radius: 0 0 10px 10px;
		margin-bottom: 10px;
		background-color: #f1f1f1;
		box-shadow: 0px 2px 16px -4px rgba(0,0,0,0.3);
		border: none;
	}
	.table_resultados{
	    border-radius: 10px;
	    padding: 10px 5px;
	    background-color: #f1f1f1;
	    margin-top: 10px;
	    margin-bottom: 10px;
	}
	#paginacao{
		border-radius: 10px;
	    background-color: #f1f1f1;
	    border: none;
	    box-shadow: 0px 2px 10px -2px rgba(0,0,0,0.3);
	    margin-bottom: 10px;
	}
	#agendamentos{
	    background-color: #f1f1f1;
	    border-radius: 0 0 10px 10px;
	}
	#bloco_consulta{
		margin-bottom: 10px;
	}
</style>

<?php  $curURL = $_SERVER["REQUEST_URI"]; ?>
<form action="index.php" method="GET">
<input name="option" type="hidden" id="option" value="com_k2" />
<input name="view" type="hidden" id="view" value="item" />
<input name="id" type="hidden" id="id" value="589" />
<input name="Itemid" type="hidden" id="Itemid" value="619" />


<div align="center">
	<h3 class="bloco_consulta_titulo">Filtros</h3>
</div>
<div id="bloco_consulta" class="thepet">
	<div id="painel_busca" class="css_form_container">
		<div class="css_form_group">
			<div class="css_form_campo">Código da venda: <input id="vendas_id" name="vendas_id" value="<?php echo $vendas_id;?>" type="text" maxlength="6" size="5" /></div>
			<div class="css_form_campo">Nº da Proposta: <input id="transacao_proposta" name="transacao_proposta" value="<?php echo $transacao_proposta;?>" type="text" maxlength="11" size="11" /></div>
			<div class="css_form_campo">
				<select name="registro_usuario">
				<option value="">---- Indiferente ----</option>
				<option value='' selected>------ Consultor ------</option>
				<?php
				$result_consultores = mysql_query("SELECT DISTINCT registro_usuario FROM sys_vendas_registros_seg 
													WHERE registro_usuario IS NOT NULL AND registro_usuario != '' ".$filtros_sql." ORDER BY registro_usuario;")
				or die(mysql_error());
				while($row_consultores = mysql_fetch_array( $result_consultores )) {
					if ($row_consultores["registro_usuario"] == $_GET["registro_usuario"]){$selected = " selected";}else{$selected = "";}
					echo "<option value='{$row_consultores['registro_usuario']}' ".$selected.">{$row_consultores['registro_usuario']}</option>";

				}
				?>
				</select> 			
			</div>

				<div class="css_form_campo">
					<select name="vendas_banco">
					<option value="">---- Indiferente ----</option>
					<option value='' selected>------ Seguradora ------</option>
					<?php
					$result_bancos = mysql_query("SELECT * FROM sys_vendas_banco_seg ORDER BY banco_nm;")
					or die(mysql_error());
					while($row_bancos = mysql_fetch_array( $result_bancos )) {
						
						$seguradoras_array[ $row_bancos["banco_id"] ] = $row_bancos['banco_nm'];

						if ($row_bancos["banco_id"] == $_GET["vendas_banco"]){$selected = " selected";}else{$selected = "";}
						echo "<option value='{$row_bancos['banco_id']}' ".$selected.">{$row_bancos['banco_nm']}</option>";
					}
					?>
					</select> 			
				</div>

			<div class="css_form_campo">
				<select name="registro_cobranca">
				<option value="">---- Indiferente ----</option>
				<option value=''<?php if(!$_GET["registro_cobranca"]){echo " selected";} ?>>---- Gatilho de Cobrança ----</option>
				<option value='1'<?php if($_GET["registro_cobranca"] == '1'){echo " selected";} ?>>Não</option>
				<option value='2'<?php if($_GET["registro_cobranca"] == '2'){echo " selected";} ?>>Sim</option>
				</select> 			
			</div>
			<div class="css_form_campo">
				<select name="registro_retencao">
				<option value="">---- Indiferente ----</option>
				<option value=''<?php if(!$_GET["registro_retencao"]){echo " selected";} ?>>---- Gatilho de Retenção ----</option>
				<option value='1'<?php if($_GET["registro_retencao"] == '1'){echo " selected";} ?>>Não</option>
				<option value='2'<?php if($_GET["registro_retencao"] == '2'){echo " selected";} ?>>Sim</option>
				</select> 			
			</div>
		</div>
		<div class="css_form_group">
			<div class="css_form_campo">
				Data do Registro:<br>
				De :<input autocomplete="off" type="text" class="datepicker" id="dp-normal-1" name="dp-normal-1" value="<?php echo $pag_data_ini; ?>" maxlength="10" size="10" placeholder="dd/mm/aaaa" onkeypress="mascaraData( this, event );">
				Até :<input autocomplete="off" type="text" class="datepicker" id="dp-normal-2" name="dp-normal-2" value="<?php echo $pag_data_fim; ?>" maxlength="10" size="10" placeholder="dd/mm/aaaa" onkeypress="mascaraData( this, event );">
			</div>
			<div class="css_form_campo">
				Data da Venda:<br>
				De :<input autocomplete="off" type="text" class="datepicker" id="dp-normal-5" name="dp-normal-5" value="<?php echo $pag_venda_data_ini; ?>" maxlength="10" size="10" placeholder="dd/mm/aaaa" onkeypress="mascaraData( this, event );">
				Até :<input autocomplete="off" type="text" class="datepicker" id="dp-normal-6" name="dp-normal-6" value="<?php echo $pag_venda_data_fim; ?>" maxlength="10" size="10" placeholder="dd/mm/aaaa" onkeypress="mascaraData( this, event );">
			</div>
			<div class="css_form_campo css_multisel">Status:<br>
				<select name="registro_status[]" multiple="multiple">
				<option value="">---- Indiferente ----</option>	
				<?php
				include("sistema/utf8.php");
				$result_status = mysql_query("SELECT * FROM sys_vendas_status_seg ORDER BY status_nm;")
				or die(mysql_error());
				while($row_status = mysql_fetch_array( $result_status )) {
				$selected_status = "";
				for ($i=0;$i<count($registro_status);$i++){if ( $registro_status[$i] == $row_status["status_id"]){$selected_status = "selected";}}
				echo "<option value='{$row_status['status_id']}'{$selected_status}>{$row_status['status_nm']}</option>";
				}
				?>
				</select>
			</div>
		</div>		
		<div class="css_form_group">
			<div class="css_form_campo">
				<a href="index.php?option=com_k2&view=item&layout=item&id=589&Itemid=619">
					<button name="limpar" type="button" value="limpar">Limpar</button>
				</a>
				<button name="buscar" type="submit" value="buscar">Buscar</button>
				<input id="sis_campo" name="ordemi" type="hidden" id="ordem" value="registro_id" />
			</div>
		</div>
	</div>
</div>

		  
<?php if ($pesquisar == false):?>
    <div align="center"><strong><?php echo $mensagem;?></strong></div>
<?php endif;?>

<?php if ($pesquisar == true):?>

<?php 

if($ordenacao == "ASC"){
	$ordenacao_link = "DESC";
	$imgad = "sistema/imagens/desc.png";
}else{
	$ordenacao_link = "ASC";
	$imgad = "sistema/imagens/asc.png";
}

$href_ordem = "index.php?option=com_k2&view=item&layout=item&id=589&Itemid=619&dp-normal-1=".$_GET["dp-normal-1"].
				"&dp-normal-2=".$_GET["dp-normal-2"].
				"&dp-normal-5=".$_GET["dp-normal-5"].
				"&dp-normal-6=".$_GET["dp-normal-6"].
				"&vendas_id=".$_GET["vendas_id"].
				"&transacao_proposta=".$_GET["transacao_proposta"].
				"&registro_usuario=".$_GET["registro_usuario"].
				"&vendas_banco=".$_GET["vendas_banco"].
				$pag_status.
				"&registro_cobranca=".$_GET["registro_cobranca"].
				"&registro_retencao=".$_GET["registro_retencao"].
				"&ordemi=".$_GET["ordemi"].
				"&ordenacao=".$_GET["ordenacao"];
				
?>
<div class="table_resultados">
	<?php if(count($propostas_repetidas)): ?>
		<style type="text/css">
			.mensagem_repetidas{
				font-weight: bold;
				text-align: center;
				padding: 5px;
				line-height: 1.5;
			}
		</style>
		<div class="mensagem_repetidas" style="text-align: center; padding: 5px;">
			Números de proposta que aparecem em mais de uma venda.<br>(A consulta exibe no máximo 10 propostas, consulte novamente após realizar correções):<br>
			<?php echo implode(" | ",$propostas_repetidas); ?>
		</div>
	<?php endif; ?>
	<table id="table_cabecalho" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
	<tbody>
	<tr class="cabecalho">
			<td width="3%">
				<span>#</span>
			</td>
			<td width="15%">
				<a class="style8" href="<?php echo $href_ordem."&ordemi=registro_usuario"; ?>">Consultor:&nbsp;<img src="<?php echo $imgad; ?>"></a><br>
				<span style="color:#cccccc; font-size:8pt"><a class="style8" href="<?php echo $href_ordem."&ordemi=registro_data"; ?>">Data:&nbsp;<img src="<?php echo $imgad; ?>"></a></span>
			</td>
			<td width="30%">
				<span style="color:#cccccc;">Observações:</span>
			</td>
			<td width="10%">
				<span style="color:#cccccc;">Seguradora:</span>
			</td>
			<td width="10%">
				<a class="style8" href="<?php echo $href_ordem."&ordemi=sys_vendas_registros_seg.vendas_id"; ?>">Cód Venda:&nbsp;<img src="<?php echo $imgad; ?>"></a><br>
				<span style="color:#cccccc; font-size:8pt">Valor:</span>
			</td>
			<td width="15%">
				<a class="style8" href="<?php echo $href_ordem."&ordemi=registro_status"; ?>">Status:&nbsp;<img src="<?php echo $imgad; ?>"></a>
			</td>
			<td width="10%">
				<a class="style8" href="<?php echo $href_ordem."&ordemi=registro_id"; ?>">Cód Registro:&nbsp;<img src="<?php echo $imgad; ?>"></a>
			</td>
	</tr>
	</tbody>
	</table>

	<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#849AB0">
	<tbody>
	<?php
	$exibindo = $inicio+1;
	$numero = $exibindo;
	?>

	<?php while($row = mysql_fetch_array( $result )) : ?>

<?php $venda_link = "/sistema/index.php?option=com_k2&view=item&layout=item&id=64&Itemid=398&acao=edita_venda_seguro&vendas_id=".$row['vendas_id']; ?>
		<tr class="even">
			<td width="3%">
				<span style="color:#424242; font-size:8pt"><?php echo $numero; ?></span>				
			</td>
			<td width="15%">				
				<span style="color:#424242;"><a href="<?php echo $venda_link; ?>" target="_blank"><?php echo $row['registro_usuario']; ?></a></span><br>
				<span style="color:#424242; font-size:8pt;"><a href="<?php echo $venda_link; ?>" target="_blank"><?php echo $row['registro_data']; ?></a></span>
			</td>
			<td width="30%">
				<span style="color:#424242; font-size:8pt;"><a href="<?php echo $venda_link; ?>" target="_blank"><?php echo $row['registro_obs']; ?></a></span>
			</td>
			<td width="10%">
				<span style="color:#424242; font-size:8pt;"><a href="<?php echo $venda_link; ?>" target="_blank"><?php echo $seguradoras_array[ $row['vendas_banco'] ]; ?></a></span>
			</td>
			<td width="10%">
				<span style="color:#424242;"><a href="<?php echo $venda_link; ?>" target="_blank"><?php echo $row['vendas_id']; ?></a></span><br>
				<span style="color:#424242; font-size:8pt;"><a href="<?php echo $venda_link; ?>" target="_blank">R$ <?php echo number_format($row['vendas_valor'], 2, ',', '.'); ?></a></span>
			</td>
			<td width="15%">
				<span style="color:#424242;"><a href="<?php echo $venda_link; ?>" target="_blank"><?php echo $row['status_nm']; ?></a></span><br>
				<span style="color:#424242; font-size:8pt;"><?php echo "<img src='sistema/imagens/status_{$row['status_img']}.png'>"; ?></span>
			</td>
			<td width="10%">
				<span style="color:#424242;"><a href="<?php echo $venda_link; ?>" target="_blank"><?php echo $row['registro_id']; ?></a></span>
			</td>
		</tr>

	<?php 
	$exibindo = $exibindo + 1;
	$numero = $numero + 1;
	?>
	<?php endwhile; ?>
	</tbody>
	</table>

	<?php
		$exibindo = $exibindo  - 1;
	?>

	<div id="paginacao" class="css_form_container">
		<div class="css_form_group">
			<?php

			$sql_select_all = mysql_query("SELECT COUNT(registro_id) AS total FROM sys_vendas_registros_seg
											LEFT JOIN sys_vendas_seguros ON (sys_vendas_registros_seg.vendas_id = sys_vendas_seguros.vendas_id) 
											INNER JOIN sys_vendas_status_seg ON registro_status = status_id 
											WHERE 1=1 ". $filtros_consulta .  ";")
			or die(mysql_error());
			/*echo "SELECT COUNT(registro_id) AS total, SUM(transacao_valor) AS total_valor FROM sys_vendas_registros_seg
							INNER JOIN sys_vendas_seguros ON (sys_vendas_registros_seg.vendas_id = sys_vendas_seguros.vendas_id) 
							WHERE 1=1 ". $filtros_sql .";";*/
			$row_total_registros = mysql_fetch_array( $sql_select_all );
			$total_registros = $row_total_registros["total"];
			$total_valor = $row_total_registros["total_valor"];
			$pags = ceil($total_registros/$qnt);
			$max_links = 6;

			?>
			<div class='css_form_campo'>
			<?php
				echo "<a href='".$link_paginacao."&p=1' target='_self'>primeira pagina</a>";
			?>
			</div>
			<?php
			for($i = $p-$max_links; $i <= $p-1; $i++)
			{
				if($i <=0) {
					//faz nada
				} else {
			?>
					<div class='css_form_campo'>
			<?php
					echo "<a href='".$link_paginacao."&p=".$i."' target='_self'>".$i."</a> ";
			?>
					</div>
			<?php
				}
			}
			?>		
			<div class='css_form_campo'>
			<strong> [ <?php echo $p; ?> ] </strong>
			</div>
			<?php		
			for($i = $p+1; $i <= $p+$max_links; $i++)
			{
				if($i > $pags)
				{
					//faz nada
				} else {
			?>
					<div class='css_form_campo'>
			<?php
					echo "<a href='".$link_paginacao."&p=".$i."' target='_self'>".$i."</a> ";
			?>
					</div>
			<?php
				}
			}
			// Exibe o link "última página"
			?>
				<div class='css_form_campo'>
			<?php
				echo "<a href='".$link_paginacao."&p=".$pags."' target='_self'>ultima pagina</a> ";
			?>
				</div>
			<?php
			?>
		</div>
	</div>
	<div align="center">Exibindo <?php echo $exibindo;?> de um total de <?php echo $total_registros;?></div>	
</div>
<?php endif; ?>

</form>
<?php mysql_close($con); ?>


























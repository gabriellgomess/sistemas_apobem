	<?php include("../../connect.php"); ?>
	<?php include("../../utf8.php"); ?>

	<?php
	$user_id = $_GET['user_id'];
	$vendas_consultor = $_GET['vendas_consultor'];
	$vendas_id = $_GET['vendas_id'];
	$administracao = $_GET['administracao'];

	$result_anexos = mysql_query("SELECT *, DATE_FORMAT(anexo_data,'%d/%m/%Y %H:%i:%s') AS anexo_data 
						   FROM sys_vendas_anexos						   
						   WHERE vendas_id = " . $vendas_id . ";")
	or die(mysql_error());
	?>

	<?php
		$cor_fundo = "#f9f9f9";
		$n_linha = 1;
	 ?>

		<div class="linha">	
			<div class="cabecalho-vendas">
				<div class="coluna" style="width: 5%;"><span>#</span></div>
				<div class="coluna" style="width: 10%;"><span>Tipo:</span></div>
				<div class="coluna" style="width: 20%; text-align: center;"><span>Arquivo:</span></div>
				<div class="coluna" style="width: 20%;"><span>Criado por: / Data:</span></div>
			</div>
		</div>

	<?php while($row_anexos = mysql_fetch_array( $result_anexos )): ?>

		<?php
				if(($row_anexos["anexo_tipo"] == "application/msword")
				||($row_anexos["anexo_tipo"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
				||($row_anexos["anexo_tipo"] == "application/rtf")){$anexo_icone = "sistema/imagens/iconeword.png";}
				
				if($row_anexos["anexo_tipo"] == "application/pdf"){$anexo_icone = "sistema/imagens/iconepdf.png";}
				
				if(($row_anexos["anexo_tipo"] == "image/jpeg")
				||($row_anexos["anexo_tipo"] == "image/jpg")
				||($row_anexos["anexo_tipo"] == "image/pjpeg")
				||($row_anexos["anexo_tipo"] == "image/gif")){$anexo_icone = "sistema/imagens/iconejpg.png";}
				
				if(($row_anexos["anexo_tipo"] == "application/zip")
				||($row_anexos["anexo_tipo"] == "application/x-zip-compressed")
				||($row_anexos["anexo_tipo"] == "multipart/x-zip")
				||($row_anexos["anexo_tipo"] == "application/x-compressed")
				||($row_anexos["anexo_tipo"] == "application/octet-stream")){$anexo_icone = "sistema/imagens/iconezip.png";}
				
				if(($row_anexos["anexo_tipo"] == "image/x-png")
				||($row_anexos["anexo_tipo"] == "image/png")){$anexo_icone = "sistema/imagens/iconepng.png";}
				
				if(($row_anexos["anexo_tipo"] == "image/x-png")
				||($row_anexos["anexo_tipo"] == "image/png")){$anexo_icone = "sistema/imagens/iconepng.png";}			
		?>
		<div class="linha-exibe-lista" id="linha-<?php echo $numero; ?>"  style="background: <?php echo $cor_fundo; ?>; color: <?php echo $color; ?>" >
			<div class="coluna" style="width: 5%;"><span><?php echo $n_linha; ?></span></div>
			<?php if ($row_anexos['anexo_documento'] == "99"): ?>
				<div class="coluna" style="width: 10%;"><span>Outros</span></div>
			<?php else: ?>
				<?php
                    $result_tipos_anexos = mysql_query("SELECT tipo_nome FROM sys_vendas_anexos_tipos WHERE tipo_id = '".$row_anexos['anexo_documento']."';") 
                    or die(mysql_error());
                    $row_tipos_anexos = mysql_fetch_array( $result_tipos_anexos );
                 ?>
                 <div class="coluna" style="width: 10%;"><span><?php echo $row_tipos_anexos["tipo_nome"]; ?></span></div>                    
            <?php endif; ?>						
			<div class="coluna" style="width: 20%; text-align: center;">
				<span title="<?php echo $row_anexos['anexo_nome']; ?>">
					<?php
						if (strpos($row_anexos['anexo_caminho'], 'sistema/') !== false)
						{
							$link_portal = "http://portal.grupofortune.com.br/portal/";
						}else{
							$link_portal = "";
						}
					?>
					<a href="<?php echo $link_portal.$row_anexos['anexo_caminho']; ?>" target="_blank"><img width='32' height='32' src="<?php echo $anexo_icone; ?>"><br><?php echo substr($row_anexos['anexo_nome'],0,25); if(strlen($row_anexos['anexo_nome']) > 25) echo " ..."; ?></a>
				</span>
			</div>
			<div class="coluna" style="width: 20%;">
				<span><?php echo $row_anexos['anexo_usuario']; ?></span>
				<br>
				<span><?php echo $row_anexos['anexo_data']; ?></span>
			</div>
			<?php if ($administracao == 1 || $vendas_consultor == $user_id): ?>
				<div class="coluna" style="width: 6%; float: right;"><span class="button" style="height: 20px; font-size: 10px; line-height: 0;" onclick="removeAnexoAjax(<?php echo $row_anexos['anexo_id']; ?>)">remover</span></div>
			<?php endif; ?>
		</div>

		<?php 
			if ($cor_fundo == "#f9f9f9"){$cor_fundo = "#f0f0f0";}else{$cor_fundo = "#f9f9f9";}
			$n_linha += 1;
		?>
	<?php endwhile; ?>
	<?php mysql_close($con); ?>

<?php
	// FUNÇÃO QUE RETORNA A IDADE EM ANOS A PARTIR DA DATA INFORMADA
	function dataBR_to_Idade($dataBr) {

		// Separa em dia, mês e ano
		list($dia, $mes, $ano) = explode('/', $dataBr);

		// Descobre que dia é hoje e retorna a unix timestamp
		$hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));

		// Descobre a unix timestamp da data de nascimento do fulano
		$nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

		// Depois apenas fazemos o cálculo já citado :)
		$idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

		return $idade;
	}
?>	
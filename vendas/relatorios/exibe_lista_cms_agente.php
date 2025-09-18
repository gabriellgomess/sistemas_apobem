<script type="text/javascript">
	function atualizaTotais(echeck,nlinha){
		
		var linha_vendas_valor = document.getElementById("vendas_valor_"+nlinha).value;
		var linha_cms = document.getElementById("vendas_comissao_vendedor_"+nlinha).value;

		var total_valor = document.getElementById("total_valor").value;
		var total_cms = document.getElementById("total_cms").value;

		linha_vendas_valor = parseFloat(linha_vendas_valor);
		linha_cms = parseFloat(linha_cms);
		total_valor = parseFloat(total_valor);
		total_cms = parseFloat(total_cms);
		
		
		if(echeck.checked==true)
		{
			document.getElementById("linha-"+nlinha).style.opacity = "1";
			total_valor = total_valor + linha_vendas_valor;
			total_cms = total_cms + linha_cms;
		}else{
			document.getElementById("linha-"+nlinha).style.opacity = "0.5";
			total_valor = total_valor - linha_vendas_valor;
			total_cms =  total_cms - linha_cms;
		}

		document.getElementById("total_valor_tela").innerHTML = "R$ " + number_format(total_valor, 2, ',', '.');
		document.getElementById("total_cms_tela").innerHTML = "R$ " + number_format(total_cms, 2, ',', '.');

		document.getElementById("total_valor").value = total_valor;
		document.getElementById("total_cms").value = total_cms;	
	}

	function number_format( numero, decimal, decimal_separador, milhar_separador ){ 
	    numero = (numero + '').replace(/[^0-9+\-Ee.]/g, '');
	    var n = !isFinite(+numero) ? 0 : +numero,
	        prec = !isFinite(+decimal) ? 0 : Math.abs(decimal),
	        sep = (typeof milhar_separador === 'undefined') ? ',' : milhar_separador,
	        dec = (typeof decimal_separador === 'undefined') ? '.' : decimal_separador,
	        s = '',
	        toFixedFix = function (n, prec) {
	            var k = Math.pow(10, prec);
	            return '' + Math.round(n * k) / k;
	        };
	    // Fix para IE: parseFloat(0.55).toFixed(0) = 0;
	    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
	    if (s[0].length > 3) {
	        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	    }
	    if ((s[1] || '').length < prec) {
	        s[1] = s[1] || '';
	        s[1] += new Array(prec - s[1].length + 1).join('0');
	    }
	 
	    return s.join(dec);
	}	
</script>

<?php 
$tempo_sr = 0.01; 
$direcao_sr = "left";
?>
<?php while($row = mysql_fetch_array( $result )) : ?>
	<?php 
	$vendas_valor = ($row['vendas_valor']>0) ? number_format($row['vendas_valor'], 2, ',', '.') : '0' ;
	
	$yr=strval(substr($row["vendas_dia_venda"],0,4));
	$mo=strval(substr($row["vendas_dia_venda"],5,2));
	$da=strval(substr($row["vendas_dia_venda"],8,2));
	$hr=strval(substr($row["vendas_dia_venda"],11,2));
	$mi=strval(substr($row["vendas_dia_venda"],14,2));
	$data_venda = date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));	
	
	if (($row['vendas_dia_pago']) && ($row['vendas_dia_pago'] != "0000-00-00")){
		$vendas_dia_pago = implode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row['vendas_dia_pago']) == 0 ? "-" : "/", $row['vendas_dia_pago'])));
		$pagamento = $vendas_dia_pago." | ".$row['vendas_mes'];
	}else{if ($row['vendas_status'] == "8"){$pagamento = "Data não informada";}else{$pagamento = "Ainda não paga";}}
	$row['vendas_comissao_vendedor'] = rand(157, 452).".".rand(11, 98);
	?>

	<input type="hidden" name="vendas_valor_<?php echo $numero; ?>" id="vendas_valor_<?php echo $numero; ?>" value="<?php echo $row['vendas_valor']; ?>" >
	<input type="hidden" name="vendas_comissao_vendedor_<?php echo $numero; ?>" id="vendas_comissao_vendedor_<?php echo $numero; ?>" value="<?php echo $row['vendas_comissao_vendedor']; ?>" >


	<div class="linha-exibe-lista" id="linha-<?php echo $numero; ?>" style="background: <?php echo $cor_fundo; ?>;" data-sr="enter bottom and move 120px over 0.75s wait <?php echo $tempo_sr; ?>s">
		<div class="coluna" style="width: 10px;">
			<span style="color:<?php echo $cor_lina; ?>; font-size:9pt;"><?php echo $numero; ?></span><br>
			<input style="margin-left: 0;" onchange="atualizaTotais(this,<?php echo $numero; ?>)" type="checkbox" name="linha_<?php echo $numero; ?>" value="<?php echo $row['vendas_id']; ?>" checked/>
		</div>
		<div class="coluna maiusculo" style="width: 20%;">
			<span style="color:<?php echo $cor_lina; ?>; font-size:9pt;"><?php echo $row['cliente_nome']; ?></span><br>
			<span style="color:<?php echo $cor_lina; ?>; font-size:8pt;">CPF: <?php echo $row['clients_cpf']; ?></span>
		</div>
		<div class="coluna" style="width: 12%;">
			<span style="color:<?php echo $cor_lina; ?>; font-size:9pt;"><?php echo $row['vendas_orgao']; ?></span><br>
			<span style="color:<?php echo $cor_lina; ?>; font-size:8pt;"><?php echo $row['vendas_banco']; ?></span>
		</div>
		<div class="coluna" style="width: 10%;">
			<span style="color:<?php echo $cor_lina; ?>; font-size:9pt;">R$ <?php echo $vendas_valor; ?></span><br>			
			<span style="color:<?php echo $cor_lina; ?>; font-size:8pt;"><?php echo $row['tipo_nome']; ?></span>
		</div>
		<div class="coluna" style="width: 15%;">
			<span style="color:<?php echo $cor_lina; ?>; font-size:9pt;"><?php echo $row['name']; ?></span><br>
			<span style="color:<?php echo $cor_lina; ?>; font-size:7pt;"><?php echo $data_venda; ?><br><?php echo $pagamento; ?></span>
		</div>
		<div class="coluna maiusculo" style="width: 20%;">
			<span style="color:<?php echo $cor_lina; ?>; font-size:7pt;"><?php echo $row['status_nm']; ?></span><br>
			<img src="sistema/imagens/status_<?php echo $row['vendas_status']; ?>.png">
			<?php
			if ($row['vendas_produto'] == 1){
				$result_fisicos = mysql_query("SELECT * FROM sys_vendas_fisicos ORDER BY contrato_etapa;")
				or die(mysql_error());
				$largura_fisicos = 50 / (mysql_num_rows( $result_fisicos ) - 1); 
				echo "<div id='container-sbar'>";
				while($row_fisicos = mysql_fetch_array( $result_fisicos )) {
					if ($row_fisicos["contrato_id"] == $row['vendas_contrato_fisico']){echo "<div class='sbar sbar-active' style='background-color: ".$row_fisicos['contrato_cor']."1);' title='Status Físico ".$row_fisicos['contrato_nome']."'>".$row_fisicos['contrato_nome']."</div>";}
					else {echo "<div class='sbar' style='width: ".$largura_fisicos."%;'><div class='sbar-inside'></div></div>";}
				}
				echo "</div>";
			}
			?>
		</div>
		<div class="coluna" style="width: 15%; margin-top: 12px;">
			<span style="color:<?php echo $cor_lina; ?>; font-size:9pt;"><?php echo $row['vendas_id']; ?></span><br>
			<?php $vendas_comissao_vendedor = ($row['vendas_comissao_vendedor']) ? "R$ ".number_format($row['vendas_comissao_vendedor'], 2, ',', '.') : 'Aguardando cálculo.' ; ?>
			<span style="color:<?php echo $cor_lina; ?>; font-size:11pt; font-weight: bold;"><?php echo $vendas_comissao_vendedor; ?></span>
		</div>
	</div>
	<?php 
	$total_cms = $total_cms + $row['vendas_comissao_vendedor'];
	$exibindo = $exibindo + 1;
	$numero = $numero + 1;
	if ($cor_fundo == "#f9f9f9"){$cor_fundo = "#f0f0f0";}else{$cor_fundo = "#f9f9f9";}
	$tempo_sr = $tempo_sr + 0.15;
	if ($tempo_sr > 1.4){$tempo_sr = 0.01;}
	?>
<?php endwhile; ?>
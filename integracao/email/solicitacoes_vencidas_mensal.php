<?php 
$con = mysqli_connect("localhost","root","aux@2021","intranet");
if (!$con)
  {
  die('Could not connect: ' . mysqli_error());
  }
mysqli_query($con, "SET NAMES 'utf8'");
mysqli_query($con, "SET CHARACTER SET utf8");
mysqli_query($con, 'SET character_set_connection=utf8');
mysqli_query($con, 'SET character_set_client=utf8');
mysqli_query($con, 'SET character_set_results=utf8');

$data = date('Y-m-d', strtotime('-7 days'));

$result_solicitacoes = mysqli_query($con,"SELECT solicitacao_id, 
											solicitacao_data,
											solicitante.name AS nome_solicitante, 
											usuario.name AS nome_usuario, 
											usuario.email AS email_usuario 
											FROM sis_feedbacks_solicitacoes 
											INNER JOIN g1fda_users AS solicitante ON sis_feedbacks_solicitacoes.solicitacao_solicitante = solicitante.id 
											INNER JOIN g1fda_users AS usuario ON sis_feedbacks_solicitacoes.solicitacao_usuario = usuario.id 
											WHERE solicitante.block = 0 AND usuario.block = 0 AND solicitacao_data < '".$data." 23:59:59' AND fb_id = 0 AND solicitacao_email = 0 
											ORDER BY solicitacao_id ASC 
											LIMIT 0, 1;") or die(mysqli_error($con));
$num_rows_solicitacoes = mysqli_num_rows($result_solicitacoes);

echo "SELECT solicitacao_id, 
											solicitacao_data,
											solicitante.name AS nome_solicitante, 
											usuario.name AS nome_usuario, 
											usuario.email AS email_usuario 
											FROM sis_feedbacks_solicitacoes 
											INNER JOIN g1fda_users AS solicitante ON sis_feedbacks_solicitacoes.solicitacao_solicitante = solicitante.id 
											INNER JOIN g1fda_users AS usuario ON sis_feedbacks_solicitacoes.solicitacao_usuario = usuario.id 
											WHERE solicitante.block = 0 AND usuario.block = 0 AND solicitacao_data < '".$data." 23:59:59' AND fb_id = 0 AND solicitacao_email = 0 
											ORDER BY solicitacao_id ASC 
											LIMIT 0, 1;<br>";
if($num_rows_solicitacoes > 0){
	while($row_solicitacoes = mysqli_fetch_array( $result_solicitacoes )){
		$solicitacao_id = $row_solicitacoes["solicitacao_id"];
		$email_destinatario_email = $row_solicitacoes["email_usuario"];
		//$email_destinatario_email = "mauricio@update.net.br";
		$email_solicitante_nome = $row_solicitacoes['nome_solicitante'];
		$email_assunto = "Conecta Carreira e Feedback | Você ainda não enviou seu feedback para ".$row_solicitacoes['nome_solicitante']."!";
		$email_destinatario_nome = $row_solicitacoes["nome_usuario"];	
		$email_corpo = "
		<table style='background-color:#1eb6b3;padding:0 20px!important' role='presentation' width='100%' border='0' cellspacing='0' cellpadding='0'>
			<tbody>
				<tr height='0'>
					<td colspan='3'>
						<span style='padding-top:0;font-size:0;line-height:0;color:#1eb6b3;background-color:#1eb6b3;display:none;overflow:hidden;max-height:0'>Olá, [nome do usuário]!<br>
							<br>[Nome de quem solicitou] acabou de solicitar um feedback a você.<br><br>Separe um momento para refletir sobre o que você quer dizer e enviar a sua mensagem.<br>
							<br>Confira algumas dicas!<br>…
						</span>
					</td>
				</tr>
				<tr>
					<td></td>
					<td align='center' valign='top' width='600'>
						<table width='600' border='0' cellspacing='0' cellpadding='0' style='width:100%;max-width:600px;background-color:#1eb6b3' bgcolor='#1eb6b3' role='presentation'><tbody><tr><td><table width='100%' border='0' cellspacing='0' cellpadding='0' role='presentation'>
							<tbody>
								<tr>
									<td>
										<table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;background:#fff;background-color:#fff;width:100%' bgcolor='#fff' width='100%'>
											<tbody>
												<tr>
													<td style='border-collapse:collapse;direction:ltr;font-size:0;text-align:center' align='center'><div style='font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%'>
														<table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;background-color:#fff;vertical-align:top' width='100%' bgcolor='#fff' valign='top'>
															<tbody>
																<tr>
																	<td align='center' style='border-collapse:collapse;font-size:0;padding:26px 20px;word-break:break-word'>
																		<img src='https://conecta.auxineon.com.br/site/images/demo/logo_rodape.png' alt='Logotipo' style='display:block;width:auto;max-width:200px;height:auto;max-height:100px;outline:none;text-decoration:none' width='185' height='100' data-image-whitelisted='' class='CToWUd a6T' data-bit='iit' tabindex='0'>
																		<div class='a6S' dir='ltr' style='opacity: 0.01;'><div id=':6sb' class='T-I J-J5-Ji aQv T-I-ax7 L3 a5q' title='Fazer o download' role='button' tabindex='0' aria-label='Fazer o download do anexo Logotipo' data-tooltip-class='a1V'>
																			<div class='akn'>
																				<div class='aSK J-J5-Ji aYr'></div></div></div><div id=':6sc' class='T-I J-J5-Ji aQv T-I-ax7 L3 a5q' title='Adicionar ao Google Drive' role='button' tabindex='0' aria-label='Adicionar anexo ao Drive: Logotipo' jslog='119524; u014N:cOuCgd,xr6bB; 43:WyJpbWFnZS9wbmciLDM1MDI0XQ..' data-tooltip-class='a1V'>
																					<div class='akn'>
																						<div class='wtScjd XG J-J5-Ji aYr'>
																							<div class='T-aT4'>
																								<div></div>
																								<div class='T-aT4-JX'></div>
																							</div>
																						</div>
																					</div>
																				</div>
																				<div id=':6se' class='T-I J-J5-Ji aQv T-I-ax7 L3 a5q' role='button' tabindex='0' aria-label='Salvar uma cópia no Fotos' jslog='54186; u014N:cOuCgd,xr6bB; 43:WyJpbWFnZS9wbmciLDM1MDI0XQ..' data-tooltip-class='a1V' data-tooltip='Salvar uma cópia no Fotos'>
																					<div class='akn'>
																						<div class='J-J5-Ji aYr akS'><div class='T-aT4' style='display: none;'><div>
																					</div>
																					<div class='T-aT4-JX'></div>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table width='100%' border='0' cellspacing='0' cellpadding='0' role='presentation'><tbody><tr><td><table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;background-color:white;width:100%' bgcolor='white' width='100%'>
				<tbody>
					<tr>
						<td style='border-collapse:collapse;direction:ltr;font-size:0;padding-bottom:30px;text-align:center' align='center'>
							<table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;display:inline-table' width='80%'>
								<tbody>
									<tr>
										<td style='border-collapse:collapse;direction:ltr;font-size:0;padding:0;text-align:center' align='center'>
											<div style='font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:middle;width:100%'>
												<table border='0' cellpadding='0' cellspacing='0' role='presentation' width='100%' style='border-collapse:collapse'>
													<tbody>
														<tr>
															<td style='border-collapse:collapse;vertical-align:middle' valign='middle'><table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse' width='100%'>
																<tbody>
																	<tr>
																		<td align='center' style='border-collapse:collapse;font-size:0;padding:0 10px;padding-top:18px;word-break:break-word'>
																			<div style='font-family:arial,sans-serif;font-size:15px;font-weight:400;letter-spacing:0;line-height:1.4;text-align:left;color:#5f6368'>
																				<div style='text-align:left'>
																					<div style='text-align:left'>
																						<span style='letter-spacing:0px'><font color='#000000'>Olá, <i><b>".$email_destinatario_nome."</b></i>!</font></span>
																					</div>
																					<div style='text-align:left'>
																						<font color='#000000'><br></font></div><div style='text-align:left'><span style='letter-spacing:0px'><font color='#000000'><i><b>".$email_solicitante_nome."</b></i> está aguardando o seu feedback. Corre!</font></span>
																					</div>
																					<div style='text-align:left'>
																						<span style='letter-spacing:0px'><font color='#000000'><br></font></span>
																					</div>
																					<div style='text-align:left'><span style='letter-spacing:0px'>
																						<font color='#000000'>Separe um momento para refletir sobre o que você quer dizer e enviar a sua mensagem.</font></span>
																					</div>
																					<div style='text-align:left'>
																						<b style='letter-spacing:0px'><span style='letter-spacing:0px'><font color='#000000'><br></font></span></b>
																					</div>
																					<div style='text-align:left'>
																						<b style='letter-spacing:0px'><span style='letter-spacing:0px'><font color='#000000'>Confira algumas dicas!</font></span></b><br>
																					</div>
																					<div style='text-align:center'>
																						<div style='text-align:left'>
																							<ul><li><span style='letter-spacing:0px'><font color='#000000'>faça referência a uma conduta ou aspecto profissional, nunca sobre questões pessoais;</font></span></li>
																							<li><span style='letter-spacing:0px'><font color='#000000'>elogios são sempre bem-vindos, então enalteça as boas ações desta pessoa;</font></span></li>
																							<li><span style='letter-spacing:0px'><font color='#000000'>ao escrever&nbsp;pense sobre como o seu feedback pode auxiliar na carreira deste(a) colega;</font></span></li>
																							<li><font color='#000000'>não demore muito para responder, seu(sua) colega está&nbsp; esperando ansiosamente&nbsp;pelo seu feedback.</font></li></ul>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<table align='center' border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;display:inline-table' width='80%'>
							<tbody>
								<tr>
									<td style='border-collapse:collapse;direction:ltr;font-size:0;padding:0;text-align:center' align='center'>
										<div style='font-size:0;text-align:left;direction:ltr;display:inline-block;vertical-align:top;width:100%'>
											<table border='0' cellpadding='0' cellspacing='0' role='presentation' style='border-collapse:collapse;vertical-align:top' width='100%' valign='top'>
												<tbody>
													<tr>
														<td align='center' style='border-collapse:collapse;font-size:0;padding:0;padding-top:30px;word-break:break-word'>
															<table border='0' cellpadding='0' cellspacing='0' role='button' style='border-collapse:separate;background:#1eb6b3;border-radius:4px;line-height:100%;padding:8px 24px'>
																<tbody>
																	<tr>
																		<td align='center' bgcolor='#1eb6b3' role='presentation' style='border-collapse:collapse;background:#1eb6b3;border:none' valign='middle'>
																			<a href=\"https://conecta.auxineon.com.br/site/index.php/feedbacks?acao=responder-feedback\" style='display:inline-block;background:#1eb6b3;color:#000000;font-family:arial,sans-serif;font-size:14px;font-weight:bold;line-height:1.4;letter-spacing:0;margin:0;text-decoration:none;text-transform:none'>
																				<span style='display:block;min-width:10px'><font color='#ffffff'>Enviar feedback</font></span>
																			</a>
																		</td>
																	</tr>
																</tbody>
															</table>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		</tbody>
		</table>
		</td>
		<td>
		</td>
		</tr>
		<tr height='0'>
			<td>
				<span style='padding-top:0;font-size:0;line-height:0;color:#1eb6b3;background-color:#1eb6b3;display:none;overflow:hidden;max-height:0'>3ywhr1st7rit</span>
			</td>
		</tr>
		</tbody>
		</table>
		";
		include("../feedbacks/envia_email_notificacao.php");
		$update_solicitacao ="UPDATE sis_feedbacks_solicitacoes SET solicitacao_email = 1, solicitacao_email_data = NOW() WHERE solicitacao_id = '". $solicitacao_id . "';";
		if (mysqli_query($con, $update_solicitacao)){
			echo "<br>Solicitação atualizada com sucesso.";
		} else {
			die('Error: ' . mysqli_error($con));
		}
		echo "<br>";
		echo "solicitacao_id: ".$solicitacao_id.", ";
		echo "email_destinatario_email: ".$email_destinatario_email.", ";
		echo "email_assunto: ".$email_assunto.", ";
		echo "email_solicitante_nome: ".$email_solicitante_nome.", ";
		echo "email_destinatario_nome: ".$email_destinatario_nome."<br>";
		sleep(10);
	}
}else{
	echo "Nenhuma Solicitação Pendente!<br>";
}
?>

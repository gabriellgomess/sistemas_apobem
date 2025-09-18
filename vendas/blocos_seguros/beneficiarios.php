<div class="linha">
   <h3 class="mypets2"><?php if($row['apolice_dep_ben'] == 2){echo "Dependentes";}else{echo "Beneficiários";} ?>:</h3> 
   <div class="thepet2">
   <?php if ($row["vendas_ben"]):?>
      <table width="100%" class="blocos">	
         <tr>
            <td>
               <div align="right"><label for="vendas_ben">Nome do Beneficiário:</label></div>
            </td>
            <td>
            <div align="left">			
               <input type="text" name="vendas_ben" value="<?php echo $row["vendas_ben"];?>" size="25" maxlength="50"<?php if ($edicao == 0){echo " readonly='true'";}?> style="text-transform: uppercase;"/>
            </div>
            </td>
         </tr>
         <tr>
            <td>
               <div align="right"><label for="vendas_parent">Grau de Parentesco:</label></div>
            </td>
            <td>
            <div align="left">			
               <input type="text" name="vendas_parent" value="<?php echo $row["vendas_parent"];?>" size="18" maxlength="40"<?php if ($edicao == 0){echo " readonly='true'";}?> style="text-transform: uppercase;"/>
            </div>
            </td>
         </tr>
      </table>
   <?php endif;?>
<table id="table_dependentes" class="blocos" width="100%" border="0" align="center" cellpadding="0" cellspacing="2">
	<?php if($row['apolice_dep_ben'] == 2): ?>
		<tr style="background-color: #ccc;color: #444;font-weight: bold;font-size: 12px;">
			<td width="30%"><div align="left">Dependente:</div></td>
			<td width="10%"><div align="left">Data de Nascimento:</div></td>
			<td width="10%"><div align="left">Grau de Parentesco:</div></td>
			<td width="10%"><div align="left">Sexo:</div></td>
			<td width="10%"><div align="left">Celular:</div></td>
			<td width="25%"><div align="left">E-mail:</div></td>
         <?php if ($administracao == 1 || $edicao == 1): ?>
            <td width="8%">
               <div align="left">Ações:</div>
               <div align="right" style="float: right;">
                  <span class="material-symbols-rounded" id="adiciona_dependente" onclick="toggleModalCadastro()" style="color: green; cursor: pointer;">person_add</span>
               </div>
            </td>
         <?php endif;?>
		</tr>
		<?php
		$result_dep = mysql_query("SELECT * FROM sys_vendas_dependentes WHERE vendas_id = " . $row['vendas_id'] . ";") 
		or die(mysql_error());
		?>
		<?php while($row_dep = mysql_fetch_array( $result_dep )): ?>
			<tr class='even' id='dependente_<?php echo $row_dep['dependente_id'];?>'>
				<td style="text-transform: uppercase;"><?php echo $row_dep['dependente_nome']; ?> <span style="font-size:10px;">(CPF: <?php echo $row_dep['dependente_cpf']; ?>)</span></td>
				<?php $dependente_nascimento = implode(preg_match("~\/~", $row_dep['dependente_nascimento']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_dep['dependente_nascimento']) == 0 ? "-" : "/", $row_dep['dependente_nascimento']))); ?>
				<td><?php echo $dependente_nascimento; ?></td>
				<td><?php echo $row_dep['dependente_parentesco']; ?></td>
				<td><?php if($row_dep['dependente_sexo'] == 'M'){echo "Masculino";}else{echo "Feminino";} ?></td>
				<td><?php echo $row_dep['dependente_celular']; ?></td>
				<td><?php echo $row_dep['dependente_email']; ?></td>
            <?php if ($edicao == 1):?>
               <td>
                  <span class="material-symbols-outlined" title="Editar" style="cursor: pointer;" onclick="toggleModalEdita(null, <?php echo $row_dep['dependente_id'];?>)">edit</span>
                  <span class="material-symbols-outlined" title="Excluir" style="cursor: pointer; color: #c9485a" onclick="toggleModalDelete(null, <?php echo $row_dep['dependente_id'];?>, '<?php echo $row_dep['dependente_nome'];?>')">delete</span>
               </td>
            <?php endif;?>
			</tr>
		<?php endwhile; ?>
	<?php else: ?>
		<tr>
			<td width="35%"><div align="left">Nome:</div></td>
			<td width="20%"><div align="left">Data de nascimento:</div></td>
			<td width="30%"><div align="left">Grau de Parentesco:</div></td>
			<td width="10%"><div align="left">Percentual:</div></td>
			<td width="5%"><div align="right">
			<?php if ($administracao == 1): ?>
				<a href="index.php?option=com_k2&view=item&id=195:cadastro-de-beneficiario&Itemid=123&tmpl=component&print=1&vendas_id=<?php echo $vendas_id; ?>&acao=novo_beneficiario" rel="lyteframe" rev="width: 700px; height: 650px; scroll:no;" title="Novo beneficiário para <?php echo $row["cliente_nome"]; ?>"><span class="material-symbols-rounded" style="color: green;">person_add</span></a>
			<?php endif;?>
			</div></td>
		</tr>
		<tr>
			<td colspan="5">
			<div class="scroller_calendar">
				<table class="listaValores" width="100%" border="0" align="center" cellpadding="0" cellspacing="2" style="text-transform: uppercase;">
					<tbody>
						<?php
						$result_ben = mysql_query("SELECT * FROM sys_vendas_ben WHERE vendas_id = " . $row['vendas_id'] . ";") 
						or die(mysql_error());
						while($row_ben = mysql_fetch_array( $result_ben )) {
							echo "<tr class='even'>";
							$ben_nome = $row_ben['ben_nome'];
							$ben_nasc = implode(preg_match("~\/~", $row_ben['ben_nasc']) == 0 ? "/" : "-", array_reverse(explode(preg_match("~\/~", $row_ben['ben_nasc']) == 0 ? "-" : "/", $row_ben['ben_nasc'])));
							echo "<td width='25%'><div align='left'><span style='font-size:8pt;'>{$row_ben['ben_nome']}<br />{$ben_nasc}</span></div></td>";
			
							echo "<td width='22%'><div align='left'><span class='maiusculaParent' style='font-size:8pt;'>{$row_ben['ben_parent']}</span></div></td>";
							$ben_perc = ($row_ben['ben_perc']<>0) ? number_format($row_ben['ben_perc'], 2, ',', '.').'%' : ' ' ;
							echo "<td width='22%'><div align='left'><span style='font-size:8pt;'>{$ben_perc}</span></div></td>";
							echo "<td width='5%'><div align='right'>";
							if ($edicao == 1){
								echo "<a title='EXCLUIR BENEFICIÁRIO Nº: {$row_ben['ben_id']}' href='index.php?option=com_k2&view=item&id=195:excluir-beneficiario&Itemid=123&tmpl=component&print=1&ben_id={$row_ben['ben_id']}&vendas_id={$vendas_id}&acao=exclui_ben' rel='lyteframe' rev='width: 550px; height: 400px; scroll:no;'><img src='sistema/imagens/delete.png'></a>";
							}	
							echo "</div></td>";
							echo "</tr>"; 
						}
						?>
					</tbody>
				</table>
			</div>
			</td>
		</tr>
	<?php endif; ?>
</table>


    </div>
</div>

<div class="modal_resposta" id="modal_resposta"></div>

<div class="base_modal" id="base_modal_add">
   <div id="modal_dependente">
      <span class="material-symbols-outlined modal_close" onclick="toggleModalCadastro(1)">close</span>
      <div class="modal_dados">
         <section id="form_cad_dependente">
            <h3>Cadastro de Dependente</h3>
            <div class="linha">
               <div class="coluna campo-titulo">Nome:</div>
               <div class="coluna campo-valor"><div align="left"><input type="text" id="dependente_nome" name="dependente_nome" size="30"></div></div>

               <div class="coluna campo-titulo">CPF:</div>
               <div class="coluna campo-valor"><div align="left"><input type="text" id="dependente_cpf" name="dependente_cpf" maxlength="14" oninput="this.value = BrazilianValues.formatToCPF(this.value);"></div></div>
            </div>
            <div class="linha">
               <div class="coluna campo-titulo">Sexo:</div>
               <div class="coluna campo-valor">
                  <select name="dependente_sexo" id="dependente_sexo" style="min-width: 100px;" onchange="getParentesco(this)">
                     <option></option>
                     <option value="M">Masculino</option>
                     <option value="F">Feminino</option>
                  </select>
               </div>

               <div class="coluna campo-titulo">Data de Nascimento:</div>
               <div class="coluna campo-valor"><div align="left"><input type="date" id="dependente_nascimento" name="dependente_nascimento"></div></div>
            </div>
            <div class="linha">
            <div class="coluna campo-titulo">Parentesco:</div>
               <div class="coluna campo-valor">
                  <select name="dependente_parentesco" id="dependente_parentesco" style='min-width: 111px;' disabled></select>
               </div>
            </div>
            <div class="linha">
               <div class="coluna campo-titulo">Celular:</div>
               <div class="coluna campo-valor"><div align="left"><input type="text" id="dependente_celular" name="dependente_celular" class="not-required" maxlength="16" size="12" onkeyup="maskPhone(this)"></div></div>

               <div class="coluna campo-titulo">E-mail:</div>
               <div class="coluna campo-valor"><div align="left"><input type="text" id="dependente_email" name="dependente_email" class="not-required" size="30"></div></div>
            </div>

            <input type="hidden" name="vendas_id" value="<?php echo $vendas_id;?>">

            <div class="modal_button_container">
               <button type="button" onclick="toggleModalCadastro(1)">VOLTAR</button>
               <button type="button" onclick="cadastraDependente()">CADASTRAR</button>
            </div>
         </section>
      </div>
   </div>
</div>

<div class="base_modal" id="base_modal_delete">
   <div id="modal_delete">
      <span class="material-symbols-outlined modal_close" onclick="toggleModalDelete(1)">close</span>
      <div class="modal_dados">
         <h3>Exclusão de Dependente</h3>

         <div>Tem certeza que deseja excluir o dependente <span id="delete_dependente_nome"></span>?</div>
         <div><b>Essa ação NÃO poderá ser desfeita!</b></div>

         <div class="modal_button_container">
            <button type="button" onclick="toggleModalDelete(1)">VOLTAR</button>
            <button id="submit_modal_delete" type="button">EXCLUIR</button>
         </div>
      </div>
   </div>
</div>

<div class="base_modal" id="base_modal_edit">
   <div id="modal_edit">
      <span class="material-symbols-outlined modal_close" onclick="toggleModalEdita(1)">close</span>
      <div class="modal_dados">
         <div id="modal_edit_loading"></div>
         <section id="form_edit_dependente" style="display: none;">
            <h3>Edição de Dependente</h3>
            <div class="linha">
               <div class="coluna campo-titulo">Nome:</div>
               <div class="coluna campo-valor"><div align="left"><input type="text" id="dependente_nome" name="dependente_nome" size="30"></div></div>

               <div class="coluna campo-titulo">CPF:</div>
               <div class="coluna campo-valor"><div align="left"><input type="text" id="dependente_cpf" name="dependente_cpf" maxlength="14" oninput="this.value = BrazilianValues.formatToCPF(this.value);"></div></div>
            </div>
            <div class="linha">
               <div class="coluna campo-titulo">Sexo:</div>
               <div class="coluna campo-valor">
                  <select name="dependente_sexo" id="dependente_sexo" style="min-width: 100px;" onchange="getParentesco(this)">
                     <option value="M">Masculino</option>
                     <option value="F">Feminino</option>
                  </select>
               </div>

               <div class="coluna campo-titulo">Data de Nascimento:</div>
               <div class="coluna campo-valor"><div align="left"><input type="date" id="dependente_nascimento" name="dependente_nascimento"></div></div>
            </div>
            <div class="linha">
            <div class="coluna campo-titulo">Parentesco:</div>
               <div class="coluna campo-valor">
                  <select name="dependente_parentesco" id="dependente_parentesco" style='min-width: 111px;' disabled></select>
               </div>
            </div>
            <div class="linha">
               <div class="coluna campo-titulo">Celular:</div>
               <div class="coluna campo-valor"><div align="left"><input type="text" id="dependente_celular" name="dependente_celular" class="not-required" maxlength="16" size="12" onkeyup="maskPhone(this)"></div></div>

               <div class="coluna campo-titulo">E-mail:</div>
               <div class="coluna campo-valor"><div align="left"><input type="text" id="dependente_email" name="dependente_email" class="not-required" size="30"></div></div>
            </div>

            <input type="hidden" name="vendas_id" value="<?php echo $vendas_id;?>">
            <input type="hidden" name="dependente_id" id="dependente_id">

            <div class="modal_button_container">
               <button type="button" onclick="toggleModalEdita(1)">VOLTAR</button>
               <button type="button" onclick="editaDependente()">EDITAR</button>
            </div>
         </section>
      </div>
   </div>
</div>

<script>
const form_cad_dependente = jQuery("#form_cad_dependente")[0];
const form_edit_dependente = jQuery("#form_edit_dependente")[0];

const loading = jQuery("<img>", {
   src: "/sistema/sistema/imagens/loading.gif",
   css: {
      position: 'absolute',
      top: '50%',
      left: '50%',
      transform: 'translate(-50%, -50%)',
      width: '31px'
   }
});

const big_loading = jQuery("<img>", {
   src: "/sistema/sistema/imagens/loading.gif",
   css: {
      position: 'absolute',
      top: '50%',
      left: '50%',
      transform: 'translate(-50%, -50%)',
      width: '56px'
   }
});

function mostraNotificacao(msg, status){
   if(status == "success"){
      jQuery("#modal_resposta").css({"top": "20%"}).html(`<span class='material-symbols-outlined' style='font-size: 13px;'>check_circle</span> <span>${msg}</span>`);
   }
   else{
      jQuery("#modal_resposta").css({"top": "20%", "background": "tomato"}).html(`<span class='material-symbols-outlined' style='font-size: 13px;'>cancel</span> <span>${msg}</span>`);
   }

   setTimeout(() => {
      jQuery("#modal_resposta").css({"top": ""}).html(``);
      
      setTimeout(() => {
         jQuery("#modal_resposta").css({"background": ""})
      }, 1000);
   }, 2000);
}

function toggleModalCadastro(close){
   if(jQuery("#base_modal_add").css("display") == "block" || close){
      jQuery("#base_modal_add").css("display", "none");
   }
   else{
      jQuery("#base_modal_add").css("display", "block");
   }
}

function cadastraDependente(){
   // const data = data_to_JSON(form_cad_dependente);
   const html = document.getElementById('form_cad_dependente').querySelectorAll("input, select, checkbox, textarea");
   const data = {}

   html.forEach(function(item, index){
      data[item.name] = item.value;
   });

   if(formValidation.submitValidation(form_cad_dependente)){
      jQuery.ajax({
         type: "POST",
         url: "/sistema/sistema/vendas/blocos_seguros/cadastra_dependente.php",
         data: data,
         success: function(response){
            console.log(response);
            toggleModalCadastro(1);
            mostraNotificacao(response.msg, response.status);
            form_reset(form_cad_dependente);
            // form_cad_dependente.reset();

            jQuery("#dependente_parentesco").attr("disabled", true).html(""); //disabilita de volta o campo de parentesco

            //insere diretamente o novo dependente na tabela
            const html = `
            <tr class="even" id="dependente_${response.id}">
               <td style="text-transform: uppercase;">${data.dependente_nome} <span style="font-size:10px;">(CPF: ${data.dependente_cpf})</span></td>
               <td>${(data.dependente_nascimento).split("-").reverse().join("/")}</td>
               <td>${data.dependente_parentesco}</td>
               <td>${(data.dependente_sexo == "M") ? "Masculino" : "Feminino"}</td>
               <td>${(data.dependente_celular).replaceAll("(", "").replaceAll(")", "").replaceAll("-", "").replaceAll(" ", "")}</td>
               <td>${data.dependente_email}</td>
               <td>
                  <span class="material-symbols-outlined" title="Editar" style="cursor: pointer;" onclick="toggleModalEdita(null, ${response.id})">edit</span>
                  <span class="material-symbols-outlined" title="Excluir" style="cursor: pointer; color: #c9485a" onclick="toggleModalDelete(null, ${response.id}, '${data.dependente_nome}')">delete</span>
               </td>
            </tr>`
            // console.log(html);

            jQuery("#table_dependentes tbody").append(html);
         }
      });
   }
}

function toggleModalDelete(close, id, nome){
   if(jQuery("#base_modal_delete").css("display") == "block" || close){
      jQuery("#base_modal_delete").css("display", "none");
      jQuery("#delete_dependente_nome").text("");
      jQuery("#submit_modal_delete").attr("onclick", ``);
   }
   else{
      jQuery("#base_modal_delete").css("display", "block");
      jQuery("#delete_dependente_nome").text(nome);
      jQuery("#submit_modal_delete").attr("onclick", `deletaDependente(${id})`);
   }
}

function deletaDependente(id){
   jQuery.ajax({
      type: "POST",
      url: "/sistema/sistema/vendas/blocos_seguros/deleta_dependente.php",
      data: {"dependente_id": id},
      success: function(response){
         toggleModalDelete(1)
         mostraNotificacao(response.msg, response.status);

         jQuery(`#table_dependentes tbody tr#dependente_${id}`).remove();
      }
   })
}

function toggleModalEdita(close, id){
   if(jQuery("#base_modal_edit").css("display") == "block" || close){
      jQuery("#base_modal_edit").css("display", "none");
      jQuery("#form_edit_dependente").css("display", "none");
      form_reset(form_edit_dependente);
      // form_edit_dependente.reset();
   }
   else{
      jQuery("#base_modal_edit").css("display", "block");
      buscaDependente(id);
   }
}

function buscaDependente(id){
   jQuery("#modal_edit_loading").html(big_loading);

   jQuery.ajax({
      type: "GET",
      url: "/sistema/sistema/vendas/blocos_seguros/busca_dependente.php",
      data: {"dependente_id": id},
      success: function(response){
         console.log(response);
         const data = response.data

         //verifica se input existe e põe valor nos que existirem
         for (let key in data) {
            let input = form_edit_dependente.getElementById(key);

            // console.log(input)

            //popula o campo de edição de parentesco ao abrir o modal
            if(input && input.id == "dependente_parentesco"){
               getParentesco(form_edit_dependente.getElementById("dependente_sexo"));
            }

            if (input) {
               input.value = data[key];
            }
         }

         jQuery("#modal_edit_loading").html(""); //remove o loading ao finalizar ajax
         jQuery("#form_edit_dependente").css("display", "block") //mostra o form de edição
      }
   })
}

function editaDependente(data){
   // console.log("editaDependente")

   // const data_form = data_to_JSON(form_edit_dependente);
   const html = document.getElementById('form_edit_dependente').querySelectorAll("input, select, checkbox, textarea");
   const data_form = {}

   html.forEach(function(item, index){
      data_form[item.name] = item.value;
   });

   if(formValidation.submitValidation(form_edit_dependente)){
      jQuery.ajax({
         type: "POST",
         url: "/sistema/sistema/vendas/blocos_seguros/edita_dependente.php",
         data: data_form,
         success: function(response){
            const data = response.data

            const linha = jQuery(`#dependente_${data.dependente_id}`)[0];

            const html = `
               <td style="text-transform: uppercase;">${data.dependente_nome} <span style="font-size:10px;">(CPF: ${data.dependente_cpf})</span></td>
               <td>${(data.dependente_nascimento).split("-").reverse().join("/")}</td>
               <td>${data.dependente_parentesco}</td>
               <td>${(data.dependente_sexo == "M") ? "Masculino" : "Feminino"}</td>
               <td>${(data.dependente_celular).replaceAll("(", "").replaceAll(")", "").replaceAll("-", "").replaceAll(" ", "")}</td>
               <td>${data.dependente_email}</td>
               <td>
                  <span class="material-symbols-outlined" title="Editar" style="cursor: pointer;" onclick="toggleModalEdita(null, ${data.dependente_id})">edit</span>
                  <span class="material-symbols-outlined" title="Excluir" style="cursor: pointer; color: #c9485a" onclick="toggleModalDelete(null, ${data.dependente_id}, '${data.dependente_nome}')">delete</span>
               </td>`

            jQuery(linha).html(html);
            toggleModalEdita(1);
            mostraNotificacao(response.msg, response.status);
         }
      });
   }
}

function maskPhone(event) {
   if (jQuery(event).val() == "") {
      b = false;
   } else {
      jQuery(event).val(BrazilianValues.formatToPhone(jQuery(event).val()));
      if (BrazilianValues.isPhone(jQuery(event).val()) === true) {
         jQuery(event).attr("style", "color: green;");
         b = true;
      } else {
         jQuery(event).attr("style", "color: red;")
         b = false;
      }
   }
}

function getParentesco(element){
   const sexo = element.selectedOptions[0].value;
   const parentesco = jQuery(element).parent().parent().parent().find("#dependente_parentesco");
   let options = "";

   jQuery(parentesco).attr("disabled", false);

   if(sexo == "M"){
      options = 
      `<option></option>
      <option value='PAI'>Pai</option>
      <option value='FILHO(A)'>Filho</option>
      <option value='CONJUGE'>Cônjuge</option>`;
   }
   else if(sexo == "F"){
      options = 
      `<option></option>
      <option value='MAE'>Mãe</option>
      <option value='FILHO(A)'>Filha</option>
      <option value='CONJUGE'>Cônjuge</option>`;
   }
   else{
      jQuery(parentesco).attr("disabled", true);
   }

   jQuery(parentesco).html(options);
}

function form_reset(form){
   const html = form.querySelectorAll("input, select, checkbox, textarea");

   html.forEach(function(item, index){
      if(item.name != "vendas_id"){
         item.value = "";
      }
   });
}
</script>
<style>
	.maiusculaParent{
		text-transform: uppercase;
	}
</style>
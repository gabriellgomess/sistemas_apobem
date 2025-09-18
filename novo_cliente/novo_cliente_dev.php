<!-- <link rel="stylesheet" href="/sistema/templates/gk_writer/css/bootstrap.min.css"> -->
<?php
if ($empresa_seguros) $empresa = "rr";
else $empresa = "fortune";

//group 10 (FORTUNE CONSIGNADO) == grupo de venda de consignado
//group 11 (DE FATO SEGUROS) == grupo de venda de seguros
$user_groups = $user->get('groups');
if (array_key_exists("10", $user_groups)) $fortune_consignado = 1;
if (array_key_exists("11", $user_groups)) $de_fato_seguros = 1;
// echo "fortune_consignado: ".$fortune_consignado;
// echo "de_fato_seguros: ".$de_fato_seguros;

$opcoes_select_banco = $opcoes_select_banco . "<option value='' selected></option>";
$result_bancos_compra = mysqli_query($con, "SELECT * FROM sys_vendas_bancos_compra ORDER BY banco_codigo;") or die(mysqli_error($con));
while ($row_bancos_compra = mysqli_fetch_array($result_bancos_compra)) {
   $opcoes_select_banco = $opcoes_select_banco . "<option value='{$row_bancos_compra['banco_id']}'>{$row_bancos_compra['banco_codigo']} - {$row_bancos_compra['banco_nome']}</option>";
}

$sql_discador_data = "SELECT jos_users.equipe_id AS equipe_id, equipe_email, equipe_ws, equipe_apolices 
                     FROM jos_users 
                     INNER JOIN sys_equipes ON jos_users.equipe_id = sys_equipes.equipe_id
                     WHERE id = $user_id;";
$result_discador_data = mysqli_query($con, $sql_discador_data) or die(mysqli_error($con));
if (mysqli_num_rows($result_discador_data) > 0) {
   $row_discador_data = mysqli_fetch_array($result_discador_data, MYSQLI_ASSOC);
   $equipe_id = $row_discador_data["equipe_id"];
   $equipe_apolices = $row_discador_data['equipe_apolices'];
}
?>
<html>

<?php //echo  "connect: " . $con->host_info;
?>

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="sistema/cliente/novo_cliente/css/novo_cliente.css">
   <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
   <script src="sistema/utils/valida_form.js" defer></script>
   <script src="sistema/utils/mascara_moeda.js" defer></script>
   <script src="sistema/utils/formdata_to_json.js" defer></script>
   <script src="sistema/cliente/novo_cliente/js/novo_cliente.js" defer></script>
   <script src="sistema/cliente/novo_cliente/js/brazilian-values.js" defer></script>
   <script src="sistema/cliente/novo_cliente/js/verfica_cartao_novo_cliente.js?<?php echo filemtime("sistema/cliente/novo_cliente/js/verfica_cartao_novo_cliente.js"); ?>" defer></script>
   <style>
      #retorno_venda {
         -webkit-animation: scale-in-tl 0.5s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
         animation: scale-in-tl 0.8s cubic-bezier(0.250, 0.460, 0.450, 0.940) both;
      }
   </style>
</head>

<body>
   <div style="width: 100%;" id="retorno_venda"></div>
   <div id="conteudo_cadastro">
      <div class="container-apb-seguros">
         <form action="#" id="form_cliente" novalidate>
            <input type="hidden" id="user_id_local" name="user_id_local" value="<?php echo $user_id_local; ?>">
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="username" id="username" value="<?php echo $username; ?>">
            <input type="hidden" name="equipe_apolices" id="equipe_apolices" class="not-required" value="<?php echo $equipe_apolices; ?>">
            <input type="hidden" name="vendas_id" id="vendas_id" value="<?php echo isset($_GET['vendas_id']) ? $_GET['vendas_id'] : ''; ?>">
            <h4 class="mb-3">Dados do Cliente</h4>

            <div class="container-info-clientes">
               <div class="container-inputs-apb-seguros">
                  <div class="input-special">
                     <input type="text" class="info-inputs" name="cliente_cpf" id="cliente_cpf" maxlength="14" size="11" required>
                     <label for="cliente_cpf">CPF</label>
                     <!-- <input type="hidden" name="clients_cpf" id="clients_cpf" required> -->
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs" name="cliente_nome" id="cliente_nome" size="40" onchange="visibleButtonSaveClient()" required>
                     <label for="cliente_nome">Nome</label>
                  </div>

                  <div class="input-special no-style-top">
                     <input type="date" class="info-inputs" name="cliente_nascimento" id="cliente_nascimento" onchange="visibleButtonSaveClient()" required>
                     <label for="cliente_nascimento">Nascimento</label>
                  </div>

                  <div class="input-special">
                     <select class="info-inputs not-required" name="cliente_sexo" id="cliente_sexo" style="padding: 5px 15px !important;" onchange="visibleButtonSaveClient()">
                        <option></option>
                        <option value="M">(M) Masculino</option>
                        <option value="F">(F) Feminino</option>
                     </select>
                     <label for="cliente_sexo">Sexo</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" name="cliente_orgao" id="cliente_orgao" onchange="visibleButtonSaveClient()">
                     <label for="cliente_orgao">Órgão</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" name="cliente_empregador" id="cliente_empregador" onchange="visibleButtonSaveClient()">
                     <label for="cliente_empregador">Empregador</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" name="cliente_categoria" id="cliente_categoria" onchange="visibleButtonSaveClient()">
                     <label for="cliente_categoria">Categoria</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" size="14" name="cliente_beneficio" id="cliente_beneficio" onchange="visibleButtonSaveClient()">
                     <label for="cliente_beneficio">Matrícula</label>
                  </div>

                  <div class="input-special">
                     <input type="text" size="2" class="info-inputs not-required" name="cliente_especie" id="cliente_especie" onchange="visibleButtonSaveClient()">
                     <label for="cliente_especie">Espécie</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" size="11" name="cliente_rg" id="cliente_rg" onkeyup="somenteNumeros(this)" onchange="visibleButtonSaveClient()">
                     <label for="cliente_rg">RG</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" name="cliente_situacao" id="cliente_situacao" onchange="visibleButtonSaveClient()">
                     <label for="cliente_situacao">Situação</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" size="40" name="cliente_pai" id="cliente_pai" onchange="visibleButtonSaveClient()">
                     <label for="cliente_pai">Nome do pai</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" size="40" name="cliente_mae" id="cliente_mae" onchange="visibleButtonSaveClient()">
                     <label for="cliente_mae">Nome da mãe</label>
                  </div>
               </div>
            </div>

            <div class="space-divider"></div>

            <h4 class="mb-3">Dados bancários</h4>

            <div class="container-info-clientes">
               <div class="container-inputs-apb-seguros">
                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" name="cliente_banco" id="cliente_banco" onchange="visibleButtonSaveClient()">
                     <label for="cliente_banco">Banco</label>
                  </div>

                  <div class="input-special">
                     <input type="text" size="6" class="info-inputs not-required" name="cliente_agencia" id="cliente_agencia" onkeyup="somenteNumeros(this)" onchange="visibleButtonSaveClient()">
                     <label for="cliente_agencia">Agência</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" name="cliente_conta" id="cliente_conta" onkeyup="somenteNumeros(this)" onchange="visibleButtonSaveClient()">
                     <label for="cliente_conta">Conta</label>
                  </div>
               </div>
            </div>

            <div class="space-divider"></div>

            <h4 class="mb-3">Endereço</h4>

            <div class="container-info-clientes">
               <div class="container-inputs-apb-seguros">
                  <div class="input-special">
                     <input type="text" size="12" class="info-inputs not-required" name="cliente_cep" id="cliente_cep" maxlength="9" onkeyup="//cepMask(this)" onchange="visibleButtonSaveClient()">
                     <label for="cliente_cep">CEP</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" name="cliente_bairro" id="cliente_bairro" onchange="visibleButtonSaveClient()">
                     <label for="cliente_bairro">Bairro</label>
                  </div>

                  <div class="input-special">
                     <input type="text" size="15" class="info-inputs not-required" name="cliente_cidade" id="cliente_cidade" onchange="visibleButtonSaveClient()">
                     <label for="cliente_cidade">Cidade</label>
                  </div>

                  <div class="input-special">
                     <input type="text" size="48" class="info-inputs not-required" name="cliente_endereco" id="cliente_endereco" onchange="visibleButtonSaveClient()">
                     <label for="cliente_endereco">Endereço</label>
                  </div>

                  <div class="input-special">
                     <input type="text" size="2" class="info-inputs not-required" name="cliente_uf" id="cliente_uf" onchange="visibleButtonSaveClient()">
                     <label for="cliente_uf">Estado</label>
                  </div>
               </div>
            </div>

            <div class="space-divider"></div>

            <h4 class="mb-3">Contatos</h4>

            <div class="container-info-clientes">
               <div class="container-inputs-apb-seguros">
                  <div class="input-special">
                     <input type="text" size="12" class="info-inputs" name="cliente_celular" id="cliente_celular" maxlength="16" onkeyup="maskPhone(this)" onchange="visibleButtonSaveClient()" required>
                     <label for="cliente_celular">Celular</label>
                  </div>

                  <div class="input-special">
                     <input type="text" class="info-inputs not-required" name="cliente_telefone" id="cliente_telefone" maxlength="16" size="12" onkeyup="maskPhone(this)" onchange="visibleButtonSaveClient()">
                     <label for="cliente_telefone">Telefone</label>
                  </div>

                  <div class="input-special">
                     <input type="email" size="48" class="info-inputs not-required" name="cliente_email" id="cliente_email" onkeyup="" onchange="visibleButtonSaveClient()">
                     <label for="cliente_email">E-mail</label>
                  </div>
               </div>
            </div>

            <div style="display: flex; margin-top: 10px; align-items: center;">
               <button id="saveNewClient" type="submit">Salvar</button>
               <span id="retorno_save_user" style="font-size: 15px;"></span>
            </div>

            <div class="space-divider-venda"></div>
         </form>

         <?php if (($de_fato_seguros && $fortune_consignado) || $super_user) : ?>
            <div id="consig_ou_seg">
               <button type="button" onclick="toggleTipoVenda(jQuery('#campos_seguro')[0])">Venda Seguro</button>
               <button type="button" onclick="toggleTipoVenda(jQuery('#campos_consig')[0])">Venda Consignado</button>
            </div>
         <?php endif; ?>

         <div id="campos_seguro" style='<?php echo ($de_fato_seguros && !$fortune_consignado) ? "display: block" : "display: none"; ?>'>
            <form action="#" id="form_seguros" novalidate>
               <div class="container_vendas" style="min-height: 0;">
                  <h4 style="margin-top: 16px;">
                     Cadastro Venda Seguro
                     <label class="message_save_cliente" style="color: tomato !important;">
                        *Insira e salve os dados do cliente!
                     </label>
                  </h4>
                  <div class="container-info-clientes blocoCadastroVendas">
                     <div class="container-inputs-apb-seguros">
                        <?php if ($administracao): ?>
                           <div class="input-special no-style-top">
                              <input type="date" class="info-inputs cad-venda dia_venda" name="vendas_dia_venda" id="vendas_dia_venda" disabled required>
                              <label for="vendas_dia_venda" class="cad-venda">Data da Venda</label>
                           </div>
                        <?php else: ?>
                           <input type="hidden" name="vendas_dia_venda" id="vendas_dia_venda" class="dia_venda">
                        <?php endif; ?>

                        <input type="hidden" name="order_id" id="order_id">

                        <?php if ($administracao || $coordenador_plataformas == 1 || $super_user): ?>
                           <div class="input-special no-style-top">
                              <select id="vendas_consultor" class="info-inputs cad-venda" name="vendas_consultor" style="width: 300px" disabled required>
                                 <option value='<?php echo $user_id; ?>'><?php echo $consultor; ?></option>
                                 <?php
                                 //pega ids de equipes que user supervisiona
                                 $equipes = [];
                                 $result_supervisor = mysqli_query($con, "SELECT equipe_id FROM sys_equipes WHERE equipe_supervisor = $user_id") or die(mysqli_error($con));
                                 $num_equipes = mysqli_num_rows($result_supervisor);
                                 while ($row_supervisor = mysqli_fetch_array($result_supervisor)) {
                                    $equipes[] = $row_supervisor["equipe_id"];
                                 }

                                 $equipes = implode(",", $equipes); //junta os ids

                                 //poe os equipe_id como filtro se user supervisionar alguma | else mostra toda a tabela
                                 $busca_por_equipe = ($num_equipes > 0) ? "WHERE equipe_id IN ($equipes)" : "";

                                 $result_user_form = mysqli_query($con, "SELECT id,name,unidade FROM jos_users $busca_por_equipe ORDER BY name;") or die(mysqli_error($con));
                                 while ($row_user_form = mysqli_fetch_array($result_user_form)) {
                                    if ($row_user_form["id"] == $_GET["vendas_consultor"]) {
                                       $selected = "selected";
                                    } else {
                                       $selected = "";
                                    }
                                    echo "<option value='{$row_user_form['id']}'{$selected}>{$row_user_form['name']}</option>";
                                 }
                                 ?>
                              </select>
                              <label for="vendas_consultor" class="cad-venda">Consultor</label>
                           </div>
                        <?php endif; ?>

                        <?php if ($administracao): ?>
                           <div class="input-special no-style-top">
                              <select id="vendas_status" class="info-inputs cad-venda" name="vendas_status" style="width: 200px" disabled required>
                                 <option value="" selected></option>
                                 <?php
                                 $result_status = mysqli_query($con, "SELECT * FROM sys_vendas_status_seg ORDER BY status_id;") or die(mysqli_error($con));
                                 while ($row_status = mysqli_fetch_array($result_status)) {
                                    if ($row_status["status_id"] == $_GET["vendas_status"]) {
                                       $selected = "selected";
                                    } else {
                                       $selected = "";
                                    }
                                    $status_nome = ucwords(strtolower($row_status['status_nm']));
                                    echo "<option value='{$row_status['status_id']}'{$selected}>$status_nome</option>";
                                 }
                                 ?>
                              </select>
                              <label for="vendas_status" class="cad-venda">Status</label>
                           </div>
                        <?php endif; ?>

                        <div class="input-special no-style-top">
                           <select id="forma_envio_kitcert" class="info-inputs cad-venda" name="forma_envio_kitcert" disabled required>
                              <option value=""></option>
                              <?php
                              $sql_kc = "SELECT * FROM sys_vendas_seg_kitcert_envio_tipo";
                              $result_kc = mysqli_query($con, $sql_kc) or die(mysqli_error($con));
                              while ($row_kc = mysqli_fetch_array($result_kc)) :
                              ?>
                                 <option value="<?php echo $row_kc['id']; ?>"><?php echo $row_kc['nome']; ?></option>
                              <?php
                              endwhile;
                              ?>
                           </select>
                           <label class="cad-venda" for="cliente_endereco">Kit certificado</label>
                        </div>

                        <?php //echo $con->host_info;
                        ?>

                        <div class="input-special no-style-top">
                           <select name="vendas_banco" id="vendas_banco" class="info-inputs cad-venda" disabled required>
                              <option value=""></option>
                              <?php
                              // busca seguradores
                              $apolice_tipo = 1; //Apólices para empresa RR
                              $sql_banco = "SELECT * FROM sys_vendas_banco_seg WHERE banco_ativo = 1 ORDER BY banco_nm ASC;";
                              $result_banco = mysqli_query($con, $sql_banco) or die(mysqli_error($con));
                              if ($equipe_apolices) {
                                 $filtro_apolices = " AND apolice_id IN (" . trim($equipe_apolices, ',') . ")";
                              }

                              while ($row_banco = mysqli_fetch_array($result_banco)) {
                                 $sql_apolice = "SELECT *
                                                   FROM sys_vendas_apolices
                                                   WHERE apolice_banco = {$row_banco["banco_id"]} 
                                                   AND apolice_ativa = 1 
                                                   AND apolice_tipo = $apolice_tipo $filtro_apolices 
                                                   ORDER BY apolice_nome;";
                                 $result_apolice = mysqli_query($con, $sql_apolice)    or die(mysqli_error($con));

                                 // Caso o banco possua alguma apólice.
                                 if (mysqli_num_rows($result_apolice) > 0) {
                                    echo "<option value='{$row_banco['banco_id']}'>{$row_banco['banco_nm']}</option>";
                                 }
                              }
                              ?>
                           </select>
                           <label class="cad-venda" for="cliente_endereco">Seguradora</label>
                        </div>

                        <div class="input-special no-style-top" style="margin: 0">
                           <select name="vendas_apolice" id="vendas_apolice" class="info-inputs cad-venda requires-input" style="min-width: 160px;" disabled required></select>
                           <label class="cad-venda" for="vendas_apolice">Apólices</label>
                        </div>

                        <div class="input-special no-style-top" style="margin: 0; display: none;" id="campo_apolice_valor">
                           <label for="vendas_valor_parcela" class="label_rs" style="font-size: 10px !important;">R$</label>
                           <input type="text" class="info-inputs cad-venda requires-input not-required" name="apolice_valor" id="apolice_valor" onkeyup="mascaraMoeda(this);" disabled required>
                           <label class="cad-venda" for="apolice_valor">Valor da Apólice</label>
                        </div>

                        <div class="input-special no-style-top">
                           <select name="vendas_orgao" id="vendas_orgao" class="info-inputs cad-venda requires-input" style="min-width: 160px;" disabled required></select>
                           <label class="cad-venda" for="vendas_orgao">Orgão</label>
                        </div>

                        <div class="input-special no-style-top" style="margin: 0">
                           <select id="vendas_dia_desconto" class="info-inputs cad-venda not-required" name="vendas_dia_desconto" style="width: 140px;" disabled>
                              <option value='' selected></option>
                              <?php
                              for ($i = 1; $i < 31; $i++) {
                                 if ($i < 10) {
                                    $dia = "0" . $i;
                                 } else {
                                    $dia = $i;
                                 }
                                 echo "<option value='" . $dia . "'>" . $dia . "</option>";
                              }
                              ?>
                           </select>
                           <label class="cad-venda" for="vendas_dia_desconto">Dia de Desconto</label>
                        </div>

                        <div id="getApolices" style="display: flex; gap: 5px"></div>

                        <div class="input-special no-style-top" id="response-ajax-apolice-pgto">
                           <select name="" id="vendas_pgto" class="info-inputs cad-venda requires-input" disabled required>
                              <option value=""></option>
                              <?php
                              $sql_pgto = "SELECT * FROM sys_vendas_pgto ORDER BY pgto_nm;";
                              $result_pgto = mysqli_query($con, $sql_pgto) or die(mysqli_error($con));
                              while ($row_pgto = mysqli_fetch_array($result_pgto)) {
                                 echo "<option value='{$row_pgto['pgto_id']}'>{$row_pgto['pgto_nm']}</option>";
                              }
                              ?>
                           </select>
                           <label class="cad-venda " for="vendas_pgto">Forma de Pagamento</label>
                        </div>
                     </div>
                  </div>

                  <div style="width: 100%;" id="blocoCadastroVendasSeguradora" class="blocoCadastroVendasSeguradora"></div>

                  <hr style="width: 100%; margin: 10px 0 0 0; background: #ddd;">

                  <div style="width: 100%; display: flex; flex-direction: column; gap: 5px;" class="blocoCadastroVendasButtons">
                     <div id="beneficiario_ou_dependente" style="display: flex; flex-direction: column; gap: 6px;"></div>

                     <div>
                        <label style="font-size: 14px !important;" class="valor_venda_seguro_label">Valor da venda: </label>
                        <label class="valor_venda_seguro_label" id="valor_venda_seguro_label" style="color: #555; font-weight: bold; font-size: 18px !important;">R$ 0,00</label>
                        <input type="hidden" name="vendas_valor_seguro" id="vendas_valor_seguro">
                     </div>

                     <div class="input-special no-style-top">
                        <textarea id="vendas_obs" class="info-inputs cad-venda not-required" name="vendas_obs" cols="70" rows="3" style="min-height: 200px; resize:none;" disabled></textarea>
                        <label class="cad-venda" for="vendas_obs">Observações</label>
                     </div>
                  </div>
                  <div class="linha_flex" style="margin-top: 10px;">
                     <button type="submit" class="cad-venda" id="salva_venda_seguro" style="width: fit-content;" disabled>Salvar venda</button>
                     <span id="retorno_venda_seguro"></span>
                     <img src="/sistema/sistema/imagens/loading.gif" id="loaoding_nova_venda" width="50" style="margin: 10px; display: none;" alt="">
                  </div>
               </div>
            </form>
         </div>

         <div id="campos_consig" style='<?php echo (!$de_fato_seguros && $fortune_consignado) ? "display: block" : "display: none"; ?>'>
            <form action="#" id="form_consig" novalidate>
               <!-- <input type="hidden" id="clients_cpf" name="clients_cpf"> -->
               <input type="hidden" id="empresa" name="empresa" value="<?php echo $empresa; ?>">

               <div class="container_vendas" style="min-height: 0;">
                  <h4 style="margin-top: 16px;">
                     Cadastro Venda Consignado
                     <label class="message_save_cliente" style="color: tomato !important;">
                        *Insira e salve os dados do cliente!
                     </label>
                  </h4>
                  <div class="container-info-clientes blocoCadastroVendas">
                     <div class="container-inputs-apb-seguros">
                        <?php if ($administracao): ?>
                           <div class="input-special no-style-top">
                              <input type="date" class="info-inputs cad-venda dia_venda" name="vendas_dia_venda" id="vendas_dia_venda" required disabled>
                              <label for="vendas_dia_venda" class="cad-venda">Data da Venda</label>
                           </div>
                        <?php else: ?>
                           <input type="hidden" name="vendas_dia_venda" id="vendas_dia_venda" class="dia_venda">
                        <?php endif; ?>

                        <div class="input-special no-style-top">
                           <select name="vendas_orgao" id="vendas_orgao" class="info-inputs cad-venda" required disabled>
                              <option value=""></option>
                              <?php
                              $result_orgao = mysqli_query($con, "SELECT * FROM sys_orgaos ORDER BY orgao_nome;") or die(mysqli_error($con));
                              while ($row_orgao = mysqli_fetch_array($result_orgao)) {
                                 $selected = ($row_orgao["orgao_nome"] == $vendas_orgao) ? "selected" : "";
                                 echo "<option value='{$row_orgao['orgao_nome']}'{$selected}>{$row_orgao['orgao_label']}</option>";
                              }
                              ?>
                           </select>
                           <label class="cad-venda" for="vendas_orgao">Orgão</label>
                        </div>
                        <div class="input-special no-style-top">
                           <select name="vendas_origem" class="info-inputs cad-venda" required disabled>
                              <option value=""></option>
                              <?php
                              $result_origem = mysqli_query($con, "SELECT * FROM sys_vendas_origens ORDER BY origem_id;")
                                 or die(mysqli_error($con));
                              while ($row_origem = mysqli_fetch_array($result_origem)) {
                                 if ($row_origem["origem_id"] == $_GET["vendas_origem"]) {
                                    $selected_promo = "selected";
                                 } else {
                                    $selected_promo = "";
                                 }
                                 echo "<option value='{$row_origem['origem_id']}'{$selected_promo}>{$row_origem['origem_nome']}</option>";
                              }
                              ?>
                           </select>
                           <label class="cad-venda" for="vendas_origem">Origem</label>
                        </div>
                        <div class="container-inputs-apb-seguros">
                           <div class="input-special no-style-top">
                              <label for="vendas_valor_parcela" class="label_rs">R$</label>
                              <input type="text" class="info-inputs cad-venda" name="vendas_valor_parcela" id="vendas_valor_parcela" required disabled>
                              <label class="cad-venda" for="vendas_valor_parcela">Valor da Parcela</label>
                           </div>
                           <div class="input-special no-style-top">
                              <select name="vendas_produto" id="vendas_produto" class="info-inputs cad-venda" required disabled>
                                 <option value=""></option>
                                 <option value="1">Consignado Físico</option>
                                 <option value="2">Consignado Fonado</option>
                                 <option value="3">Consignado Digital</option>
                                 <option value="4">CP Digital</option>
                                 <option value="5">FGTS</option>
                              </select>
                              <label class="cad-venda" for="vendas_produto">Produto</label>
                           </div>
                           <div class="input-special no-style-top">
                              <select name="vendas_banco" id="vendas_banco_id" class="info-inputs cad-venda requires-input" style="min-width: 120px;" required disabled>
                                 <option value=""></option>
                              </select>
                              <label class="cad-venda" for="vendas_banco">Banco</label>
                           </div>
                           <div class="input-special no-style-top">
                              <select name="vendas_tipo_contrato" id="vendas_tipo_contrato" class="info-inputs cad-venda requires-input" style="min-width: 120px;" required disabled>
                                 <option value=""></option>
                              </select>
                              <label class="cad-venda" for="vendas_tipo_contrato">Contrato</label>
                           </div>
                           <div class="input-special no-style-top">
                              <select name="vendas_percelas" id="vendas_percelas" class="info-inputs cad-venda requires-input" style="min-width: 120px;" required disabled>
                                 <option value=""></option>
                              </select>
                              <label class="cad-venda" for="vendas_percelas">Prazo</label>
                           </div>
                           <div class="input-special no-style-top" style="display: flex; flex-direction: column;">
                              <select name="vendas_tabela" id="vendas_tabela" class="info-inputs cad-venda requires-input" style="min-width: 550px;" required disabled>
                                 <option value=""></option>
                              </select>
                              <label class="cad-venda" for="vendas_tabela">Tabela</label>
                              <span id="span_vendas_coeficiente" style="margin-left: 10px;"></span>
                              <input type="hidden" name="vendas_coeficiente" id="vendas_coeficiente" class="not-required">
                           </div>
                           <div class="input-special no-style-top">
                              <label for="vendas_valor_parcela" class="label_rs">R$</label>
                              <input type="text" class="info-inputs cad-venda" name="vendas_valor" id="vendas_valor" required disabled>
                              <label class="cad-venda" for="vendas_valor">AF. Valor do Contrato</label>
                           </div>
                           <div class="input-special no-style-top">
                              <label for="vendas_valor_parcela" class="label_rs">R$</label>
                              <input type="text" class="info-inputs cad-venda" name="vendas_margem" id="vendas_margem" required disabled>
                              <label class="cad-venda" for="vendas_margem">Margem</label>
                           </div>
                           <div class="input-special no-style-top">
                              <label for="vendas_valor_parcela" class="label_rs">R$</label>
                              <input type="text" class="info-inputs cad-venda" name="vendas_liquido" id="vendas_liquido" required disabled>
                              <label class="cad-venda" for="vendas_liquido">Líquido</label>
                           </div>
                           <div class="input-special no-style-top">
                              <select name="vendas_estoque" id="vendas_estoque" class="info-inputs cad-venda not-required" style="width: 155px;" required disabled>
                                 <option value=""></option>
                                 <option value="0">Não</option>
                                 <option value="1">Sim</option>
                              </select>
                              <label class="cad-venda" for="vendas_estoque">Vendas em Estoque</label>
                           </div>
                           <div class="input-special no-style-top">
                              <select name="vendas_jud" id="vendas_jud" class="info-inputs cad-venda not-required" style="width: 166px;" required disabled>
                                 <option value=""></option>
                                 <option value="1">Normal</option>
                                 <option value="2">Via Jurídico</option>
                              </select>
                              <label class="cad-venda" for="vendas_estoque">Liberação de Margem</label>
                           </div>
                           <div class="input-special no-style-top">
                              <select name="vendas_cartao_consig" id="vendas_cartao_consig" class="info-inputs cad-venda not-required" required disabled>
                                 <option value=""></option>
                                 <option value="0">- Não possui cartão consignado -</option>
                                 <?php
                                 $sql_bancos = "SELECT vendas_bancos_id, vendas_bancos_nome FROM sys_vendas_bancos;";
                                 $result_bancos = mysqli_query($con, $sql_bancos) or die(mysqli_error($con));
                                 while ($row_bancos = mysqli_fetch_array($result_bancos)): ?>
                                    <option value="<?php echo $row_bancos['vendas_bancos_id']; ?>" <?php echo $selected; ?>><?php echo $row_bancos['vendas_bancos_nome']; ?></option>
                                 <?php endwhile; ?>
                              </select>
                              <label class="cad-venda" for="vendas_cartao_consig">Cartão Consignado</label>
                           </div>
                        </div>

                        <!-- <div class="space-divider"></div> -->

                        <h5>Seguro</h5>
                        <div class="container-inputs-apb-seguros">
                           <div class="input-special no-style-top">
                              <label for="vendas_valor_parcela" class="label_rs">R$</label>
                              <input type="text" class="info-inputs cad-venda not-required" name="vendas_applus_valor" id="vendas_applus_valor" required disabled>
                              <label class="cad-venda" for="vendas_applus_valor">Valor do Seguro</label>
                           </div>
                           <div class="input-special no-style-top">
                              <select name="vendas_seguro_protegido" id="vendas_seguro_protegido" class="info-inputs cad-venda not-required" style="width: 150px;" required disabled>
                                 <option value=""></option>
                                 <option value="1">Não</option>
                                 <option value="2">Sim</option>
                              </select>
                              <label class="cad-venda" for="vendas_seguro_protegido">Seguro Prestamista</label>
                           </div>
                        </div>
                        <div class="container-inputs-apb-seguros input-special no-style-top">
                           <textarea id="vendas_obs" class="info-inputs cad-venda not-required" name="vendas_obs" cols="70" rows="3" style="min-height: 200px; resize:none;" required disabled></textarea>
                           <label class="cad-venda" for="vendas_obs">Observações</label>
                        </div>
                        <div id="compra_d" class="container-inputs-apb-seguros input-special" style="display:none; flex-direction: column;">
                           <h5 class="">Compra de Dívida</h5>
                           <div id="campo_compra_divida" style="display: none;"></div>
                           <div class="container">
                              <span id="add_btn_divida">
                                 <button type="button" onclick="addCampoDivida();">Adicionar Dívida</button>
                              </span>

                              <span id="remove_btn_divida"></span>
                           </div>
                        </div>
                        <span id="conteudo_select" style="display:none"> <?php echo $opcoes_select_banco; ?> </span>
                     </div>
                  </div>
                  <div class="linha_flex" style="margin-top: 10px;">
                     <button type="submit" class="cad-venda" id="salva_venda_consig" style="width: fit-content;" disabled>Salvar venda</button>
                     <span id="retorno_venda_consig" style="display: flex; align-items:center"></span>
                     <img src="/sistema/sistema/imagens/loading.gif" id="loaoding_nova_venda" width="50" style="margin: 10px; display: none;" alt="">
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
</body>

</html>
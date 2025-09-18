<?php

class ASAASAPI{
    
    private $apiUrl;
    private $accessToken;
    private $con;
    private $ambiente;

    public function __construct($apiUrl, $accessToken, $con, $ambiente){
        $this->apiUrl = $apiUrl;
        $this->accessToken = $accessToken;
        $this->con = $con;
        $this->ambiente = $ambiente;  

    }
    
    // BUSCA OS BOLETOS POR DATA DE VENCIMENTO NA API DA ASAAS
    public function getPayments($startDate, $endDate){
        $url = $this->apiUrl . "/payments?dueDate%5Bge%5D=" . $startDate . "&dueDate%5Ble%5D=" . $endDate . "&limit=100";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "access_token: " . $this->accessToken,
            "Content-Type: application/json"
        ));

        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        return $response->data;
    }

    // BUSCA OS BOLETOS POR DATA DE PAGAMENTO NA API DA ASAAS
    public function getReceiveds($startDate2, $endDate2){
        $url = $this->apiUrl . "/payments?paymentDate%5Bge%5D=" . $endDate2 . "&paymentDate%5Ble%5D=" . $startDate2 . "&limit=100";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "access_token: " . $this->accessToken,
            "Content-Type: application/json"
        ));

        $response = json_decode(curl_exec($ch));
        curl_close($ch);

        return $response->data;
    }

    // -------------------------------------------------------------------------

    // BUSCA O ID DA VENDA PELO ID DO BOLETO
    public function buscaVendaId($id_boleto){
        $con = $this->con;
        $query = "SELECT * FROM `sys_vendas_transacoes_boleto` WHERE `id_boleto` = '$id_boleto'";
        $verifica_status = mysqli_query($con, $query) or die("Erro da query " . mysqli_error($con));
        $row = mysqli_fetch_array( $verifica_status );
        if($row['vendas_id'] == ''){
            return "Boleto não encontrado (buscaVendaId)";
        }else{
            return $row['vendas_id'];
        }
        
    }
    // -------------------------------------------------------------------------
    // *************************************************************************
    // VERIFICA PARCELAS BOLETO EM ATRASO **************************************
    // *************************************************************************
    public function verificaParcelasBoletoAtrasado($id_boleto, $vendas_id){
        $ambiente = $this->ambiente;
        $respostas = "<b style='color: tomato'>BOLETO ATRASADO</b> <br>";
        if($vendas_id == "Boleto não encontrado (buscaVendaId)"){
            return false;
        }else{
            $con = $this->con;

            // Busca em sys_vendas_seguros o status da venda ******************************************
            $verifica_status_venda = mysqli_query($con, "SELECT vendas_status FROM `sys_vendas_seguros` WHERE `vendas_id` = '$vendas_id'") or die(mysqli_error($con));
            $row_status_venda = mysqli_fetch_array($verifica_status_venda);
            $status_venda = $row_status_venda['vendas_status'];
            // ****************************************************************************************
            // Array dos status que NÃO devem ser alterados *******************************************
            $status_nao_atualizar = Array('19', '58', '76', '77', '86', '87', '90', '91');
            // ****************************************************************************************
            // Verifica se o status da venda está no array de status que não devem ser alterados *******
            if(in_array($status_venda, $status_nao_atualizar)){
                $atualizar = 0;
            }else{
                $atualizar = 1;
            }
            // ****************************************************************************************


            $test_query = '';
            // Busca as parcelas pagas da transação ***************************************************
            $result_pagas = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $vendas_id . "' AND transacao_recebido = 1;")
            or die(mysqli_error($con));
            if(!$result_pagas){
                $test_query .= "Erro na query de parcelas pagas<br>";
            }
            $row_pagas = mysqli_fetch_array($result_pagas);        
            $parcelas_pagas = $row_pagas["total"];
            // ****************************************************************************************

            // Busca o total de parcelas da transação *************************************************
            $total = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $vendas_id."'")
            or die(mysqli_error($con));
            if(!$total){
                $test_query .= "Erro na query de total de parcelas<br>";
            }
            $row_total_parcelas = mysqli_fetch_array($total);   
            // ****************************************************************************************

            // Busca o ID do boleto *******************************************************************
            $ver_id_boleto = mysqli_query($con, "SELECT * FROM `sys_vendas_transacoes_boleto` WHERE `id_boleto` = '$id_boleto'") or die(mysqli_error($con));
            if(!$ver_id_boleto){
                $test_query .= "Erro na query de ID do boleto<br>";
            }
            $row_ver_id_boleto = mysqli_fetch_array($ver_id_boleto);

            
            // *********************************************************************
            // ****************** GERAÇÃO DO LINK CRIPTOGRAFADO ********************
            // *********************************************************************
            $data = array("sale_id" => $vendas_id, "cpf" => $row_ver_id_boleto['cliente_cpf'], "billingBy" => "portal");
            $data_string = json_encode($data);
            $secret_key = "update2023";
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
            $encrypted_data = openssl_encrypt($data_string, 'aes-256-cbc', $secret_key, 0, $iv);
            $encrypted_data = base64_encode($encrypted_data . '::' . $iv);
            $link = "https://www.apobem.com.br/portal/?schdl=1&" . http_build_query(array("data" => $encrypted_data));

            $respostas .= "Link criptografado: <a href=". $link ." target='blank'>Link criptografado</a><br>";

            // *********************************************************************

            // ****************************************************************************************

            $respostas .= "<a href=".$row_ver_id_boleto['invoiceUrl']." target='blank'>Link do Boleto</a><br>";
            $respostas .= "Data da criação do boleto: ".date('d/m/Y', strtotime($row_ver_id_boleto['dateCreated']))." | Data de vencimento: ".date('d/m/Y', strtotime($row_ver_id_boleto['dueDate']))."<br>"; 
            
            //Busca as parcelas relacionadas ao boleto ************************************************
            $relacionadas = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE id_boleto = '" .$row_ver_id_boleto['transacao_id']."'")
            or die(mysqli_error($con));
            if(!$relacionadas){
                $test_query .= "Erro na query de parcelas relacionadas ao boleto<br>";
            }
            $row_total_parcelas_boleto = mysqli_fetch_array($relacionadas);
            // ****************************************************************************************

            // Verifica a quantidade de parcelas pagas para definir o texto ***************************
            if($parcelas_pagas == 0){
                $parc_pagas = "Nenhuma foi paga";
            }elseif($parcelas_pagas == 1){
                $parc_pagas = "Uma foi paga";
            }else{
                $parc_pagas = $parcelas_pagas . " foram pagas";
            }
            $respostas .= "Total de parcelas: " . $row_total_parcelas['total'] . ", ".$parc_pagas.", sendo que ".$row_total_parcelas_boleto['total']." são relacionadas ao boleto<br>";
            // ****************************************************************************************
            
            // Verifica se o total de parcelas da transação é igual ao total de parcelas do boleto ****
            if($row_total_parcelas_boleto['total'] == $row_total_parcelas['total']){

                $query_atualiza_boleto = "UPDATE `sys_vendas_transacoes_boleto` SET `status` = 'Boleto vencido', `description` = 'Boleto está em atraso - integrador.automático' WHERE `id_boleto` = '".$id_boleto."'";

                // Insere registro na venda *****************************************************
                $insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) 
                VALUES (NULL, '".$vendas_id."','integrador.automatico','Boleto atualizado como vencido, não altera venda nem parcelas','',NOW());";

                // Verifica se está em produção ou homologação *************************************
                if($ambiente == 'producao'){                                     
                    $atualiza_boleto = mysqli_query($con, $query_atualiza_boleto) or die(mysqli_error($con));
                    $respostas .= "<i>".$insere_registro_venda_query."</i>"."<br>";
                    $insere_registro_venda = mysqli_query($con, $insere_registro_venda_query) or die(mysqli_error($con));  
                    // Verifica se a query foi executada com sucesso *******************************
                    if(!$atualiza_boleto){
                        $test_query .= "Erro na query de atualização do boleto onde todas as parcelas da transação estão no boleto<br>";
                    }else{
                        $respostas .= "Todas as parcelas da transação estão no boleto, boleto é atualizado como Vencido, não altera venda nem parcelas (PRODUÇÃO)<br>";
                        $respostas .= "<i>".$query_atualiza_boleto."</i>"."<br>";
                    }
                }else{                    
                    $respostas .= "Todas as parcelas da transação estão no boleto, boleto é atualizado como Vencido,  não altera venda nem parcelas (HOMOLOGAÇÃO)<br>";
                    $respostas .= "<i>".$query_atualiza_boleto."</i>"."<br>";
                    $respostas .= "<i>".$insere_registro_venda_query."</i>"."<br>";
                      
                }
                
            }else{
                $respostas .= "Boleto vencido, com parcelas fora do boleto<br>";
                $query_atualiza_boleto = "UPDATE `sys_vendas_transacoes_boleto` SET `status` = 'Boleto vencido', `description` = 'Boleto está em atraso - integrador.automático' WHERE `id_boleto` = '".$id_boleto."'";
                if($atualizar == 1){
                // Insere registro na venda *****************************************************
                    $insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) 
                    VALUES (NULL, '".$vendas_id."','integrador.automatico','Boleto atualizado como vencido, venda alterada para inadimplente','88',NOW());";
                }else{
                    $insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) 
                    VALUES (NULL, '".$vendas_id."','integrador.automatico','Boleto atualizado como vencido, venda não foi alterada, status = ".$status_venda."','".$status_venda."',NOW());";
                }

                // Verifica se está em produção ou homologação *************************************
                if($ambiente == 'producao'){
                    $atualiza_boleto = mysqli_query($con, $query_atualiza_boleto) or die(mysqli_error($con));
                    // Verifica se a query foi executada com sucesso *******************************
                    if(!$atualiza_boleto){
                        $test_query .= "Erro na query de atualização do boleto onde nem todas as parcelas estão no boleto<br>";
                    }else{
                        $respostas .= "Boleto é atualizado como Vencido, não altera venda nem parcelas (PRODUÇÃO)<br>";
                        $respostas .= "<i>".$query_atualiza_boleto."</i><br>";
                        $respostas .= "<i>".$insere_registro_venda_query."</i>"."<br>";
                        $insere_registro_venda = mysqli_query($con, $insere_registro_venda_query) or die(mysqli_error($con));                          
                    }
                }else{                    
                    $respostas .= "Boleto é atualizado como Vencido, não altera venda nem parcelas (HOMOLOGAÇÃO)<br>";
                    $respostas .= "<i>".$query_atualiza_boleto."</i><br>";
                    $respostas .= "<i>".$insere_registro_venda_query."</i>"."<br>";
                                     
                }          
                
                // Verifica se é para atualizar a venda para inadimplente ******************************
                if($atualizar == 1){
                    $respostas .= "Atualizando a venda para inadimplente<br>";
                $query_atualiza_venda = "UPDATE `sys_vendas_seguros` SET `vendas_status` = '88' WHERE `vendas_id` = '".$vendas_id."'";
                    // Verifica se está em produção ou homologação *************************************
                    if($ambiente == 'producao' ){
                        $atualiza_venda = mysqli_query($con, $query_atualiza_venda) or die(mysqli_error($con));
                    // Verifica se a query foi executada com sucesso *******************************
                        if(!$atualiza_venda){
                            $test_query .= "Erro na query de atualização da venda<br>";
                        }else{
                            $respostas .= "Venda atualizada para inadimplente (PRODUÇÃO)<br>";
                            $respostas .= "<i>".$query_atualiza_venda."</i><br>";
                            $respostas .= "Não altera as parcelas e insere a cobrança em campanha<br>";
                        }
                    }else{                    
                        $respostas .= "Venda atualizada para inadimplente (HOMOLOGAÇÃO)<br>";
                        $respostas .= "<i>".$query_atualiza_venda."</i><br>";
                    }

                    // Função que coloca o cliente em campanha de cobrança ***********************************
                $respostas .= "<p style='color: orange; margin: 0; padding: 0'>ALOCAÇÃO DE CLIENTES INADIMPLENTES EM CAMPANHA DE COBRANÇA</p>";
                // Seleciona os dados do cliente em sys_vendas_seguros fazendo INNER JOIN 
                // em sys_vendas_apolices se vendas_apolice for igual a apolice_id onde 
                // vendas_id for igual a $vendas_id
                $result_venda = mysqli_query($con, "SELECT vendas_id, cliente_cpf, vendas_apolice, apolice_nome, vendas_status, vendas_receita, vendas_recebido_prolabore, vendas_dia_ativacao, vendas_banco 
                FROM sys_vendas_seguros INNER JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id WHERE vendas_id = '" . $vendas_id . "' AND vendas_status != 96;")
                or die(mysqli_error($con));
                if(!$result_venda){
                    $test_query .= "Erro na query de seleção de dados da venda, colocando o cliente em campanha<br>";
                }
                $row_venda = mysqli_fetch_array($result_venda);
                // *********************************************************************

                // Definindo o usuário como integrador-automático **********************
                $username = "integrador.automático";
                // *********************************************************************

                // Condição para definir o grupo da campanha ***************************
                if($row_venda['vendas_banco'] == 3){
                    $campanha_grupo_id = 13;
                }else{
                    $campanha_grupo_id = 12;
                }
                // *********************************************************************

                // Encerra a conexão com o banco de dados, para estabelecer conexão com
                // outro banco de dados
                // mysqli_close($con);
                // *********************************************************************
                $respostas .= "ID da venda: " . $vendas_id . "<br>";
                $respostas .= "Username: " . $username . "<br>";
                $respostas .= "Vendas Banco: " . $row_venda['vendas_banco'] . "<br>";
                $respostas .= "ID do grupo da campanha: " . $campanha_grupo_id . "<br>";
                $respostas .= "*Conexão com o banco de dados encerrada.<br>";

                // Conecta com o banco de dados do sistema de campanhas ****************
                $path_includes = "/var/www/html/sistema/sistema/";
				//$path_includes = "../var/www/html/sistema/sistema/";
                include($path_includes."connect_db02.php");
                $respostas .= "*Conexão com o banco de dados do sistema de campanhas estabelecida.<br>";
                // *********************************************************************

                // Seleciona o menor número de clientes na campanha ********************
                $sql_menor_campanha = mysqli_query($con,"
                SELECT campanha_id, campanha_nome, (SELECT COUNT(cliente_cpf) AS total FROM sys_inss_clientes WHERE cliente_campanha_id = campanha_id AND cliente_parecer = 100) AS total FROM sys_campanhas 
                INNER JOIN sys_campanhas_grupos ON sys_campanhas.campanha_grupo_id = sys_campanhas_grupos.grupo_id 
                WHERE campanha_grupo_id = " . $campanha_grupo_id . " 
                ORDER BY total ASC 
                LIMIT 0, 1;") or die (mysqli_error($con));
                if(!$sql_menor_campanha){
                    $test_query .= "Erro na query de seleção da menor campanha, colocando o cliente em campanha<br>";
                }
                // *********************************************************************
                
                // Seleciona o grupo da campanha pelo ID do grupo ***********************
                $result_campanha_grupos = mysqli_query($con,"SELECT grupo_id, grupo_nome FROM sys_campanhas_grupos WHERE grupo_id = '" . $campanha_grupo_id . "';")
                or die(mysqli_error($con));
                if(!$result_campanha_grupos){
                    $test_query .= "Erro na query de seleção do grupo da campanha, colocando o cliente em campanha<br>";
                }
                $row_campanha_grupos = mysqli_fetch_array( $result_campanha_grupos );
                // *********************************************************************

                // Monta a observação do acionamento ***********************************
                $acionamento_obs .= " (Campanha de destino: Grupo ".$row_campanha_grupos['grupo_nome'];
                
                $respostas .= "Grupo de campanha: " . $row_campanha_grupos['grupo_nome'] . "<br>";
                $respostas .= "Campanha ativa: " . mysqli_num_rows($sql_menor_campanha) . "<br>"; 
                if(mysqli_num_rows($sql_menor_campanha)){
                    $row_menor_campanha = mysqli_fetch_array( $sql_menor_campanha );
                    $cliente_campanha_id_update = ", cliente_campanha_id = '".$row_menor_campanha['campanha_id']."'";
                    $acionamento_obs .= " - Nome: ".$row_menor_campanha['campanha_nome']." ".$row_menor_campanha['campanha_id'].").";
                    $respostas .= "Número de campanha ativa: " . mysqli_num_rows($sql_menor_campanha) . "<br>";               
                }else{
                    $acionamento_obs .= " - Nenhuma campanha ativa neste grupo).";
                    $respostas .= "Nenhuma campanha ativa neste grupo.<br>";
                }
                // *********************************************************************

                // Espelha o cliente no banco de dados do sistema de campanhas *********
                $clients_cpf = $row_venda['cliente_cpf'];
              
                include($path_includes."cliente/espelha_confere.php");

                if(!$row_espelha_confere["total"]){
                    include($path_includes.$Arquivo_conect);
                    include($path_includes."utf8.php");
                    
                    include($path_includes."cliente/espelha.php");
                    
                    include($path_includes."connect_db02.php");
                    include($path_includes."utf8.php");
                    
                    include($path_includes."cliente/espelha_insere.php");
                }                
                $respostas .= "Espelha cliente " . $row_espelha_confere["total"] . "<br>";
                // *********************************************************************

                // Atualiza o cliente no banco de dados do sistema de campanhas ********
                $query_atualiza_cliente_campanha = "UPDATE sys_inss_clientes SET cliente_parecer = '100', cliente_usuario = '$username', cliente_alteracao = NOW()". $cliente_campanha_id_update." WHERE cliente_cpf='$cliente_cpf';";
                if($ambiente == "producao"){
                    $query = mysqli_query($con,$query_atualiza_cliente_campanha) or die('Error UPDATE sys_inss_clientes - '.mysqli_error($con));
                    if(!$query){
                        $test_query .= "Erro na query de atualização do cliente, colocando o cliente em campanha<br>";
                    }else{
                        $respostas .= "Cliente atualizado com sucesso na campanha! (PRODUÇÃO)<br>";
                        $respostas .= $query_atualiza_cliente_campanha."<br>";
                    }
                }else{
                    $respostas .= "Cliente atualizado com sucesso na campanha! (HOMOLOGAÇÃO)<br>";
                    $respostas .= $query_atualiza_cliente_campanha."<br>";
                }                
                
                // *********************************************************************

                // Insere o acionamento no banco de dados do sistema de campanhas *******
                $respostas .= "Inserindo acionamento: <br>";
                $query_sql= "INSERT INTO `sistema`.`sys_acionamentos` (`acionamento_id`, 
                                    `clients_cpf`, 
                                    `acionamento_usuario`, 
                                    `acionamento_obs`, 
                                    `acionamento_parecer`, 
                                    `acionamento_empregador`, 
                                    `acionamento_data`, 
                                    `acionamento_campanha`,
                                    `acionamento_equipe_id`) 
                                    VALUES (NULL, 
                                    '$clients_cpf',
                                    '$username',
                                    '$acionamento_obs',
                                    '29',
                                    'INSS',
                                    NOW(),
                                    '".$row_menor_campanha['campanha_id']."',
                                    '94');";
                if($ambiente == "producao"){
                    $query = mysqli_query($con,$query_sql) or die('Error INSERT sys_acionamentos - '.mysqli_error($con));
                    if(!$query){
                        $test_query .= "Erro na query de inserção do acionamento<br>";
                    }else{
                        $respostas .= "Acionamento inserido com sucesso! (PRODUÇÃO)<br>";
                        $respostas .= $query_sql."<br>";
                    }
                }else{
                    $respostas .= "Acionamento inserido com sucesso! (HOMOLOGAÇÃO)<br>";
                    $respostas .= $query_sql."<br>";
                }
                
                // *********************************************************************

                }else{
                    $respostas .= "Não foi atualizada a venda e nem colocado em campanha, pois o status é ".$status_venda."<br>";
                }
            
                
            }                           
                
        }
        return $respostas;
    }

    // -------------------------------------------------------------------------

    // *************************************************************************
    // VERIFICA PARCELAS DE BOLETO RECEBIDO ************************************
    // *************************************************************************
    public function verificaParcelasBoletoRecebido($paymentDate, $id_boleto, $vendas_id){
        $con = $this->con;
        $ambiente = $this->ambiente;
        $test_query = "";
        $respostas = "<b style='color: green'>BOLETO RECEBIDO</b> <br>";
        if($vendas_id == "Boleto não encontrado (buscaVendaId)"){
            return false;
        }else{

            // Busca em sys_vendas_seguros o status da venda ******************************************
            $verifica_status_venda = mysqli_query($con, "SELECT vendas_status FROM `sys_vendas_seguros` WHERE `vendas_id` = '$vendas_id'") or die(mysqli_error($con));
            $row_status_venda = mysqli_fetch_array($verifica_status_venda);
            $status_venda = $row_status_venda['vendas_status'];
            // ****************************************************************************************
            // Array dos status que NÃO devem ser alterados *******************************************
            $status_nao_atualizar = Array('19', '58', '76', '77', '86', '87', '90', '91');
            // ****************************************************************************************
            // Verifica se o status da venda está no array de status que não devem ser alterados *******
            if(in_array($status_venda, $status_nao_atualizar)){
                $atualizar = 0;
            }else{
                $atualizar = 1;
            }
            // ****************************************************************************************
            
            // Busca o ID do boleto *******************************************************************
            $ver_id_boleto = mysqli_query($con, "SELECT * FROM `sys_vendas_transacoes_boleto` WHERE `id_boleto` = '$id_boleto'") or die(mysqli_error($con));
            $row_ver_id_boleto = mysqli_fetch_array($ver_id_boleto);
            // ****************************************************************************************

            $respostas .= "<a href=".$row_ver_id_boleto['invoiceUrl']." target='blank'>Link do Boleto</a><br>";
            $respostas .= "Data da criação do boleto: ".date('d/m/Y', strtotime($row_ver_id_boleto['dateCreated']))." | Data de vencimento: ".date('d/m/Y', strtotime($row_ver_id_boleto['dueDate']))."<br>"; 

            //Atualiza o status do boleto para "Boleto recebido" **************************************
			$query_atualiza_boleto = "UPDATE `sys_vendas_transacoes_boleto` SET `status` = 'Boleto Recebido', `description` = 'Boleto foi pago - integrador.automático' WHERE `id_boleto` = '".$id_boleto."'";


            // Verifica se é produção ou homologação ***********************************************
            if($ambiente == "producao"){
                $query = mysqli_query($con,$query_atualiza_boleto) or die('Error UPDATE sys_vendas_transacoes_boleto - '.mysqli_error($con));
                // Verifica se a query foi executada com sucesso *************************************
                if(!$query){
                    $test_query .= "Erro na query de atualização do boleto<br>";
                }else{
                    $respostas .= "Boleto atualizado com sucesso para o status Recebido! (PRODUÇÃO)<br>";
                    $respostas .= $query_atualiza_boleto."<br>";
                }
            }else{
                $respostas .= "Boleto atualizado com sucesso para o status Recebido! (HOMOLOGAÇÃO)<br>";
                $respostas .= $query_atualiza_boleto."<br>";
                
            }

            // ****************************************************************************************

            $respostas .= "Parcelas correspondentes ao id deste do boleto (".$row_ver_id_boleto['transacao_id'].") que serão atualizadas como <i><b>PAGAS</b></i>: <br>";

            // Busca as parcelas relacionadas ao boleto e atualiza como pagas *************************
            $parcelas_correspondentes = mysqli_query($con, "SELECT transacao_id FROM `sys_vendas_transacoes_seg` WHERE `id_boleto` = '".$row_ver_id_boleto['transacao_id']."'") or die(mysqli_error($con));
            $row_parcelas_correspondentes = '';
            
           
            // Verifica se é produção ou homologação ***********************************************
            if($ambiente == 'producao'){
                
                while($row_parcelas_correspondentes = mysqli_fetch_array($parcelas_correspondentes)){
                    $query_atualiza_parcelas_correspondentes = "UPDATE `sys_vendas_transacoes_seg` SET `transacao_recebido` = '1', transacao_motivo = '0', transacao_data = '".$paymentDate."'  WHERE `transacao_id` = '".$row_parcelas_correspondentes['transacao_id']."'";
                    $atualiza_parcelas_correspondentes = mysqli_query($con, $query_atualiza_parcelas_correspondentes) or die(mysqli_error($con));
                    // Verifica se a query foi executada com sucesso *************************************
                    if(!$atualiza_parcelas_correspondentes){
                        $test_query .= "Erro na query de atualização das parcelas correspondentes<br>";
                    }else{
                        $respostas .= "Parcela atualizada com sucesso! (PRODUÇÃO)<br>";
                        $respostas .= $query_atualiza_parcelas_correspondentes."<br>";
                        // Parcelas que serão atualizadas
                        $respostas .= "Parcela: ".$row_parcelas_correspondentes['transacao_id']."<br>";
                    }                             
                }
            }else{
                while($row_parcelas_correspondentes = mysqli_fetch_array($parcelas_correspondentes)){
                    $query_atualiza_parcelas_correspondentes = "UPDATE `sys_vendas_transacoes_seg` SET `transacao_recebido` = '1', transacao_motivo = '0', transacao_data = '".$paymentDate."' WHERE `transacao_id` = '".$row_parcelas_correspondentes['transacao_id']."'";
                    $respostas .= "Parcela atualizada com sucesso! (HOMOLOGAÇÃO)<br>";
                    $respostas .= $query_atualiza_parcelas_correspondentes."<br>";
                }
            }
            
            // ****************************************************************************************

            // Busca o total de parcelas da transação *************************************************
            $total_parcelas = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $vendas_id."'")
            or die(mysqli_error($con));
            $row_total_parcelas = mysqli_fetch_array($total_parcelas);
            // ****************************************************************************************

            // Busca as parcelas pagas da transação ***************************************************
            $result_pagas = mysqli_query($con, "SELECT COUNT(*) AS total FROM sys_vendas_transacoes_seg WHERE transacao_id_venda = '" . $vendas_id . "' AND transacao_recebido = 1;")
            or die(mysqli_error($con));            
            $row_pagas = mysqli_fetch_array($result_pagas);
            $parcelas_pagas = $row_pagas["total"];
            // ****************************************************************************************

            $respostas .= "Total de Parcelas: " . $row_total_parcelas["total"].", sendo que ".$parcelas_pagas." estão pagas.<br>";
            

            // Atualiza o status da venda caso todas as parcelas estejam pagas ************************            
            // Caso as quantidade de parcelas pagas no boleto seja igual a 
            // quantidade de parcelas da transação, a venda é atualizada para ATIVA, 
            // senão, somente atualiza as parcelas como pagas
            if($parcelas_pagas == $row_total_parcelas['total']){
                $respostas .= "QUITADO<br>";
                if($atualizar == 1){
                    $query_atualiza_vendas = "UPDATE `sys_vendas_seguros` SET `vendas_status` = '67' WHERE `vendas_id` = '".$vendas_id."'";

                    // Insere o registro de venda ************************************************************
                        $insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) 
                                                        VALUES (NULL, '".$vendas_id."','integrador.automatico','Boleto atualizado como recebido a venda ATIVA','67',NOW());";
                    
                    // Verifica se é produção ou homologação ***********************************************
                    if($ambiente == 'producao'){
                        $atualiza_vendas = mysqli_query($con, $query_atualiza_vendas) or die(mysqli_error($con));                   
                        // Verifica se a query foi executada com sucesso *************************************
                        if(!$atualiza_vendas){
                            $test_query .= "Erro na query de atualização da venda<br>";
                        }else{
                            $respostas .= "Colocando a venda como ATIVA, pois todas as parcelas estão pagas. <br>";
                            $respostas .= "Venda atualizada com sucesso! (PRODUÇÃO)<br>";
                            $respostas .= $query_atualiza_vendas."<br>";
                            // Insere o registro de venda ****************************************************
                            $insere_registro_venda = mysqli_query($con, $insere_registro_venda_query) or die(mysqli_error($con));
                            $respostas .= $insere_registro_venda_query."<br>";
                            // Aqui remove o cliente de campanha de cobrança, caso esteja
                            $respostas .= "Removendo o cliente da campanha de cobrança, caso esteja. (PRODUÇÃO)<br>";
                             // Seleciona os dados do cliente em sys_vendas_seguros fazendo INNER JOIN 
                            // em sys_vendas_apolices se vendas_apolice for igual a apolice_id onde 
                            // vendas_id for igual a $vendas_id
                            $result_venda = mysqli_query($con, "SELECT vendas_id, cliente_cpf, vendas_apolice, apolice_nome, vendas_status, vendas_receita, vendas_recebido_prolabore, vendas_dia_ativacao, vendas_banco 
                            FROM sys_vendas_seguros INNER JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id WHERE vendas_id = '" . $vendas_id . "' AND vendas_status != 96;")
                            or die(mysqli_error($con));
                            if(!$result_venda){
                                $test_query .= "Erro na query de seleção de dados da venda, colocando o cliente em campanha<br>";
                            }
                            $row_venda = mysqli_fetch_array($result_venda);
                            $respostas .= "CPF: ".$row_venda['cliente_cpf']."<br>";
                            // *********************************************************************
                            include($path_includes.$Arquivo_conect);
                            include($path_includes."utf8.php");                            
                            include($path_includes."cliente/espelha.php");                            
                            include($path_includes."connect_db02.php");
                            include($path_includes."utf8.php");                            
                            include($path_includes."cliente/espelha_insere.php");
                            // Atualiza o cliente no banco de dados do sistema de campanhas ********
                            // Query que remove da campanha de cobrança
                            $query_atualiza_cliente_campanha = "UPDATE sys_inss_clientes SET cliente_campanha_id = '0', cliente_usuario = 'integrador.automatico', cliente_alteracao = NOW() WHERE cliente_cpf='".$row_venda['cliente_cpf']."';";
                            $query = mysqli_query($con,$query_atualiza_cliente_campanha) or die('Error UPDATE sys_inss_clientes - '.mysqli_error($con));
                            if(!$query){
                                $test_query .= "Erro na query de atualização do cliente, colocando o cliente em campanha<br>";
                            }else{
                                $respostas .= "Cliente atualizado com sucesso na campanha! (PRODUÇÃO)<br>";
                                $respostas .= $query_atualiza_cliente_campanha."<br>";
                            }

                        }
                    }else{
                        $respostas .= "Colocando a venda como ATIVA, pois todas as parcelas estão pagas. <br>";
                        $respostas .= "Venda atualizada com sucesso! (HOMOLOGAÇÃO)<br>";
                        $respostas .= $query_atualiza_vendas."<br>";
                        // Insere o registro de venda ****************************************************
                        $respostas .= $insere_registro_venda_query."<br>";

                        // Aqui remove o cliente de campanha de cobrança, caso esteja
                        $respostas .= "Removendo o cliente da campanha de cobrança, caso esteja. (HOMOLOGAÇÃO)<br>";

                        // Seleciona os dados do cliente em sys_vendas_seguros fazendo INNER JOIN 
                        // em sys_vendas_apolices se vendas_apolice for igual a apolice_id onde 
                        // vendas_id for igual a $vendas_id
                        $result_venda = mysqli_query($con, "SELECT vendas_id, cliente_cpf, vendas_apolice, apolice_nome, vendas_status, vendas_receita, vendas_recebido_prolabore, vendas_dia_ativacao, vendas_banco 
                        FROM sys_vendas_seguros INNER JOIN sys_vendas_apolices ON sys_vendas_seguros.vendas_apolice = sys_vendas_apolices.apolice_id WHERE vendas_id = '" . $vendas_id . "' AND vendas_status != 96;")
                        or die(mysqli_error($con));
                        if(!$result_venda){
                            $test_query .= "Erro na query de seleção de dados da venda, colocando o cliente em campanha<br>";
                        }
                        $row_venda = mysqli_fetch_array($result_venda);
                        $respostas .= "CPF: ".$row_venda['cliente_cpf']."<br>";
                        // Query que remove da campanha de cobrança
                        $query_atualiza_cliente_campanha = "UPDATE sys_inss_clientes SET cliente_campanha_id = '0', cliente_usuario = 'integrador.automatico', cliente_alteracao = NOW() WHERE cliente_cpf='".$row_venda['cliente_cpf']."';";
                        // *********************************************************************
                        $respostas .= "Cliente atualizado com sucesso na campanha! (HOMOLOGAÇÃO)<br>";
                        $respostas .= $query_atualiza_cliente_campanha."<br>";
                    }
                }else{
                    $respostas .= "Boleto foi atualizado, mas não foi alterado o status da venda, pois o status atual é ".$status_venda."";
                    // Insere o registro de venda ************************************************************
                    $insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) 
                    VALUES (NULL, '".$vendas_id."','integrador.automatico','Boleto atualizado como recebido, não foi alterada a venda, pois o status atual é ".$status_venda."','".$status_venda."',NOW());";
                    $insere_registro_venda = mysqli_query($con, $insere_registro_venda_query) or die(mysqli_error($con));
                    
                }
                
            }else{
                // Insere o registro de venda ************************************************************
                $insere_registro_venda_query = "INSERT INTO `sistema`.`sys_vendas_registros_seg` (`registro_id`, `vendas_id`, `registro_usuario`, `registro_obs`, `registro_status`, `registro_data`) 
                                                VALUES (NULL, '".$vendas_id."','integrador.automatico','Boleto atualizado como recebido, mas não quitou todas as parcelas',".$status_venda.",NOW());";
                $insere_registro_venda = mysqli_query($con, $insere_registro_venda_query) or die(mysqli_error($con));
                $respostas .= $insere_registro_venda_query."<br>";
                $respostas .= "As parcelas correspondentes ao boleto foram atualizadas como pagas, mas a venda não foi atualizada, pois não quitou todas as parcelas.<br>";
            }
            // ****************************************************************************************
        }
        return $respostas;
    }
    // -------------------------------------------------------------------------

    // *************************************************************************
    // VERIFICA PARCELAS DE BOLETO PENDENTE ************************************
    // *************************************************************************
    public function verificaParcelasBoletoPendente($id_boleto, $vendas_id){
        $respostas = "<b style='color: orange'>BOLETO PENDENTE</b> <br>";
        if($vendas_id == "Boleto não encontrado (buscaVendaId)"){
            return false;
        }else{
            $respostas .= "Nenhuma ação foi tomada, pois o boleto ".$id_boleto." está pendente.<br>";
        }

        return $respostas;
    }
    // -------------------------------------------------------------------------

}


?>
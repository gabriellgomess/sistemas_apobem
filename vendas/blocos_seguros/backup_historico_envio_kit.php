<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="linha">
    <h3 class="mypets2" style="text-align: center">Histórico de Envio do Kit Boas Vindas:</h3>
    <div class="thepet2">
        <div class="linha">
            <div class="wrapper-btn-modal">
                <div class="dropdown">
                    <div onclick="toggleDropdown()" class="btn-modal btn-modal-primary">Reenvio do kit</div>
                    <div id="myDropdown" class="dropdown-content">
                        <h3>Reenviar kit boas vindas</h3>
                        <div class="wrapper-content">
                            <label class="label-modal" for="telefone">Telefone (ddd+numero)</label>
                            <div class="centralize">
                                <input id="telefone" type="text" value="<?php echo $kits[0]['telefone']; ?>"><input type="checkbox" id="send-sms">
                            </div>
                            <label class="label-modal" for="email">Email</label>
                            <div class="centralize">
                                <input id="email" type="text" value="<?php echo $kits[0]['email']; ?>"><input type="checkbox" id="send-email">
                            </div>
                        </div>
                        <div class="wrapper-actions">
                            <div id="send-btn" class="btn-modal btn-modal-success btn-modal-disable">Enviar</div>
                            <div onclick="toggleDropdown()" class="btn-modal btn-modal-danger">Cancelar</div>
                        </div>
                        <div id="loading" style="display: none; text-align: center; margin-top: 10px;">
                            <i class="fas fa-spinner fa-spin"></i> Enviando...
                        </div>
                        <div id="message" style="display: none; text-align: center; margin-top: 10px;"></div>
                    </div>
                </div>
            </div>
            <?php
            $data_acessos = $kits_acesso;
            // colunas id, vendas_id, navegador, sistema, ip, cidade, pais, estado e dataHora

            function formatData($dataHora)
            {
                return date('d/m/Y H:i:s', strtotime($dataHora));
            }

            if (!empty($data_acessos)) {
                echo '<table border="1" style="width:100%;">';
                echo '<tr>';
                echo '<th colspan="9" style="font-weight: bold; font-size: 16px; text-align: center">Histórico de acessos ao kit</th>';
                echo '<tr>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Venda</th>';
                echo '<th>Navegador</th>';
                echo '<th>Sistema</th>';
                echo '<th>IP</th>';
                echo '<th>Cidade</th>';
                echo '<th>País</th>';
                echo '<th>Estado</th>';
                echo '<th>Data</th>';
                echo '</tr>';

                foreach ($data_acessos as $item) {
                    echo '<tr>';
                    echo '<td style="font-size: 12px">' . $item['id'] . '</td>';
                    echo '<td style="font-size: 12px">' . $item['vendas_id'] . '</td>';
                    echo '<td style="font-size: 12px">' . $item['navegador'] . '</td>';
                    echo '<td style="font-size: 12px">' . $item['sistema'] . '</td>';
                    echo '<td style="font-size: 12px">' . $item['ip'] . '</td>';
                    echo '<td style="font-size: 12px">' . $item['cidade'] . '</td>';
                    echo '<td style="font-size: 12px">' . $item['pais'] . '</td>';
                    echo '<td style="font-size: 12px">' . $item['estado'] . '</td>';
                    echo '<td style="font-size: 12px">' . formatData($item['dataHora']) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<h3 style="font-weight: bold; color: lightgrey">Nenhum acesso registrado.</h3>';
            }

            ?>

        </div>

        <div class="linha">
            <?php

            $data = $kits;

            if (!empty($data)) {
                echo '<table border="1" style="width:100%;">';
                echo '<tr>';
                echo '<th colspan="12" style="font-weight: bold; font-size: 16px; text-align: center">Histórico de envio do kit</th>';
                echo '<tr>';
                echo '<tr>';
                echo '<th>ID</th>';
                echo '<th>Venda</th>';
                echo '<th><i class="fa-solid fa-mobile-retro" style="color: #FFF; font-size: 14px"></i></th>';
                echo '<th><i class="fa-solid fa-share" style="color: #FFF; font-size: 14px"></i></th>';
                echo '<th>Retorno do envio</th>';
                echo '<th>Telefone</th>';
                echo '<th><i class="fa-solid fa-envelope" style="color: #FFF; font-size: 14px"></i></th>';
                echo '<th><i class="fa-solid fa-share" style="color: #FFF; font-size: 14px"></i></th>';
                echo '<th>Retorno do envio</th>';
                echo '<th>Email</th>';
                echo '<th>URL</th>';
                echo '<th>Data</th>';
                echo '</tr>';

                foreach ($data as $item) {
                    echo '<tr>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['id']) . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['venda_id']) . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['envio_sms'] == 1 ? 'Sim' : 'Não') . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['status_send_sms'] == 1 ? 'Enviado' : 'Não enviado') . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['message_sms']) . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['telefone']) . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['envio_email'] == 1 ? 'Sim' : 'Não') . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['status_send_email'] == 1 ? 'Enviado' : 'Não enviado') . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['message_email']) . '</td>';
                    echo '<td style="font-size: 12px">' . htmlspecialchars($item['email']) . '</td>';
                    echo '<td style="font-size: 12px"><a href="' . htmlspecialchars($item['url']) . '" target="blank">Link</td>';
                    echo '<td style="font-size: 12px">' . formatData(htmlspecialchars($item['created_at'])) . '</td>';
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<h3 style="font-weight: bold; color: lightgrey">Nenhum kit enviado.</h3>';
            }
            ?>
        </div>
    </div>
</div>

<style>
    #myDropdown {
        padding: 15px;
        border-radius: 20px 0px 20px 20px;
        background: #F8F8F8;
        border: solid #BDBDBD 1px;
        box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.5);
        -webkit-box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.5);
        -moz-box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.5);
    }

    .btn-modal {
        color: white;
        padding: 10px;
        font-size: 14px;
        border: none;
        cursor: pointer;
        min-width: 100px;
    }

    .btn-modal-primary {
        background-color: #3498DB;
    }

    .btn-modal-success {
        background-color: #4CAF50;
    }

    .btn-modal-danger {
        background-color: #f44336;
    }

    .btn-modal-disable {
        background-color: #BDBDBD;
        cursor: not-allowed;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .wrapper-btn-modal {
        padding: 10px;
        display: flex;
        justify-content: end;
        align-items: center
    }

    .wrapper-actions {
        width: 100%;
        display: flex;
        justify-content: start;
        align-items: center;
        gap: 15px;
    }

    .wrapper-content {
        width: 270px;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;

    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1
    }

    .show {
        display: block;
        position: absolute;
        right: 0;
        transition: all 0.5s;
    }

    .centralize {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .label-modal {
        margin: 5px;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function toggleDropdown() {
        var dropdown = jQuery("#myDropdown");
        dropdown.toggleClass("show");
    }

    jQuery(document).ready(function() {
        jQuery('#send-sms, #send-email').change(function() {
            var sendSms = jQuery('#send-sms').is(':checked');
            var sendEmail = jQuery('#send-email').is(':checked');

            if (sendSms || sendEmail) {
                jQuery('#send-btn').removeClass('btn-modal-disable');
            } else {
                jQuery('#send-btn').addClass('btn-modal-disable');
            }
        });

        jQuery('#send-btn').click(function() {
            if (jQuery('#send-btn').hasClass('btn-modal-disable')) {
                return;
            }

            var telefone = jQuery('#telefone').val();
            var email = jQuery('#email').val();
            var sendSms = jQuery('#send-sms').is(':checked');
            var sendEmail = jQuery('#send-email').is(':checked');
            
            jQuery('#loading').show();
            jQuery('#message').hide();

            jQuery.ajax({
                url: 'https://sistema.apobem.com.br/integracao/robo_envio_kit/reenvia_kit.php',
                type: 'POST',
                data: {
                    telefone: telefone,
                    email: email,
                    sendSms: sendSms,
                    sendEmail: sendEmail,
                    vendas_id: <?php echo $kits[0]['venda_id']; ?>
                },
                success: function(response) {
                    console.log('Dados enviados com sucesso!');
                    jQuery('#loading').hide();
                    jQuery('#message').show().html('<span style="color: green;">Kit reenviado com sucesso!</span>');
                    toggleDropdown();
                    // Atualize a tabela aqui, se necessário
                    setTimeout(function() {
                        location.reload(); // Atualiza a página para refletir as mudanças
                    }, 2000);
                },
                error: function(error) {
                    console.log("ERRO: ", error);
                    jQuery('#loading').hide();
                    jQuery('#message').show().html('<span style="color: red;">Erro ao reenviar o kit.</span>');
                }
            });
        });
    });
</script>

<?php
        $path_includes = "/var/www/html/sistema/sistema/";
        $arquivo_conect = $path_includes."connect_seguro.php";
        include($arquivo_conect);

        $query = "SELECT t.*, s.* 
                    FROM sys_vendas_transacoes_boleto t
                    JOIN sys_vendas_seguros s ON t.vendas_id = s.vendas_id
                    WHERE s.vendas_status NOT IN (19, 92, 90, 76)
                    ORDER BY t.transacao_id DESC
                    LIMIT 200;
                    ";

        $result = mysqli_query($con, $query);

        // Montar uma lista

        $rows = array();
        ?>
        <h3>Links de acesso (homologação)</h3>
        <table>
            <tr>
                <th>Id</th>
                <th>Status</th>               
                <th>CPF</th>
                <th>LINK</th>
            </tr>
        <?php
        // $data = array("sale_id" => '124233', "cpf" => '02284783082');

        while($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
            ?>
            <tr>
                <td><?php echo $row['vendas_id'] ?></td>
                <td><?php echo $row['vendas_status'] ?></td>
                <td><?php echo $row['cliente_cpf'] ?></td>
                <td>
                    <?php
                        // chave secreta
                        $secret_key = "u";
                        // dados a serem enviados
                        $data = array("code" => $secret_key."-".$row['cliente_cpf']."-".$row['vendas_id']);
                        $data_string = json_encode($data);                        
                        // criptografia
                        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
                        $encrypted_data = openssl_encrypt($data_string, 'aes-128-cbc', $secret_key, 0, $iv);
                        $encrypted_data = base64_encode($encrypted_data . '::' . $iv);

                        // enviando os dados criptografados
                        $link = "http://localhost:3000/portal/?schdl=1&" . http_build_query(array("data" => $encrypted_data));
                    ?>
                    <a href="<?php echo $link ?>" target="_blank">Acessar</a>                    
                </td>
            </tr>
            <?php
            
            
        }

        

        ?>
        </table>


<?php


        
        



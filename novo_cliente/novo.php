<?php 
include("sistema/connect_db02.php");
include("sistema/utf8.php");
?>
<?php
if($_GET["submit"] == "salvar"):    
    include("insere.php");
else:
?>
<form action="#" name="formulario_cadastro" onsubmit="return validateForm()">
    <h3 class="discador_bloco_titulo">Cadastrar Cliente</h3>    
    <div class="linha">
        <label class="coluna campo-titulo" for="nome">Nome completo:</label>
        <div class="coluna campo-valor">
            <input type="text" id="nome" name="nome" value="<?php echo $_GET['nome']; ?>" utils="somenteletras" size="40">
        </div>

        <label class="coluna campo-titulo" for="cpf">CPF:</label>
        <div class="coluna campo-valor">
            <input type="text" id="cpf" name="cpf" maxlength="11" value="<?php echo $_GET['cpf']; ?>" utils="numerico">
        </div>

        <label class="coluna campo-titulo" for="nasc">Nasc:</label>
        <div class="coluna campo-valor">
            <input type="date" id="nasc" name="nasc" value="<?php echo $_GET['nasc']; ?>">
        </div>    
        <div class="linha"></div>
        <div class="linha">
            <span>Nota 1: Após salvar, você será encaminhado para ficha do cliente para preencher o restante dos dados.</span><br>
            <span>Nota 2: Se já existir um cliente cadastrado com o mesmo CPF, os dados inseridos não serão levados em consideração e você apenas será direcionado para a ficha do cliente já existente.</span>
        </div>
    </div>

    <div class="linha"></div>
    <input type="submit" name="submit" value="salvar">
</form>
<script>
function validateForm(){
    var nome = document.forms["formulario_cadastro"]["nome"].value;
    if (nome.trim() == "") {
        custom_alert("O nome é obrigatório.");
        return false;
    }
    var cpf = document.forms["formulario_cadastro"]["cpf"].value;
    if (cpf.trim().length != 11) {
        custom_alert("O cpf é obrigatório e deve conter 11 dígitos.");
        return false;
    }
    var nasc = document.forms["formulario_cadastro"]["nasc"].value;
    if (nasc == "") {
        custom_alert("A data de nascimento é obrigatória.");
        return false;
    }
}
</script>
<?php endif; ?>

<?php

function geraOptionOrgao($con, $orgao = "")
{
    $options = "<select name='orgao'>
                <option value=''>Selecione";

    $result_orgao = mysqli_query($con, "SELECT * FROM sys_orgaos ORDER BY orgao_nome;")
                    or die(mysqli_error($con));
                    
    while( $row_orgao = mysqli_fetch_array( $result_orgao ) )
    {
        $selected = $row_orgao["orgao_nome"] == $orgao ? "selected":"";

        $options  .= "<option ".$selected." value='".$row_orgao['orgao_nome']."'>".$row_orgao['orgao_label']."</option>";
    }
    $options .= "</select>";
    return $options;
}


function geraOptionUF($myUF = "")
{
    $options = "<select name='uf'>
                <option value=''>Selecione";
    $estadosBrasileiros = Array(
                                'AC'=>'Acre (AC)',
                                'AL'=>'Alagoas (AL)',
                                'AP'=>'Amapá (AP)',
                                'AM'=>'Amazonas (AM)',
                                'BA'=>'Bahia (BA)',
                                'CE'=>'Ceará (CE)',
                                'DF'=>'Distrito Federal (DF)',
                                'ES'=>'Espírito Santo (ES)',
                                'GO'=>'Goiás (GO)',
                                'MA'=>'Maranhão (MA)',
                                'MT'=>'Mato Grosso (MT)',
                                'MS'=>'Mato Grosso do Sul (MS)',
                                'MG'=>'Minas Gerais (MG)',
                                'PA'=>'Pará (PA)',
                                'PB'=>'Paraíba (PB)',
                                'PR'=>'Paraná (PR)',
                                'PE'=>'Pernambuco (PE)',
                                'PI'=>'Piauí (PI)',
                                'RJ'=>'Rio de Janeiro (RJ)',
                                'RN'=>'Rio Grande do Norte (RN)',
                                'RS'=>'Rio Grande do Sul (RS)',
                                'RO'=>'Rondônia (RO)',
                                'RR'=>'Roraima (RR)',
                                'SC'=>'Santa Catarina (SC)',
                                'SP'=>'São Paulo (SP)',
                                'SE'=>'Sergipe (SE)',
                                'TO'=>'Tocantins (TO)'
                                );
        foreach ($estadosBrasileiros as $uf => $estado)
        {
            $selected = $uf == $myUF ? "selected":"";
            $options  .= "<option ".$selected." value='".$uf."'>".$estado."</option>";
        }
        $options .= "</select>";
        return $options;
}
?>
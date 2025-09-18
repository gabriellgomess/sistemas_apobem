<?php date_default_timezone_set('America/Sao_Paulo'); ?>
<?php
$vendas_id=$_POST["vendas_id"];
$anexo_usuario=$_POST["anexo_usuario"];
$anexo_documento=$_POST["anexo_documento"];

$allowedExts = array("gif", "jpeg", "jpg", "png", "pdf", "doc", "docx","zip","ZIP","GIF", "JPEG", "JPG", "PNG", "PDF", "DOC", "DOCX");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

if ((($_FILES["file"]["type"] == "image/gif")
  || ($_FILES["file"]["type"] == "image/jpeg")
  || ($_FILES["file"]["type"] == "image/jpg")
  || ($_FILES["file"]["type"] == "image/pjpeg")
  || ($_FILES["file"]["type"] == "application/pdf")
  || ($_FILES["file"]["type"] == "application/x-download")
  || ($_FILES["file"]["type"] == "application/msword")
  || ($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document")
  || ($_FILES["file"]["type"] == "application/rtf")
  || ($_FILES["file"]["type"] == "image/x-png")
  || ($_FILES["file"]["type"] == "application/zip")
  || ($_FILES["file"]["type"] == "application/x-zip-compressed")
  || ($_FILES["file"]["type"] == "multipart/x-zip")
  || ($_FILES["file"]["type"] == "application/x-compressed")
  || ($_FILES["file"]["type"] == "application/octet-stream")
  || ($_FILES["file"]["type"] == "image/png"))
  && ($_FILES["file"]["size"] < 30720760)
  && in_array($extension, $allowedExts))
  {
      if ($_FILES["file"]["error"] > 0)
        { ?>
          <div class="alert-box merror" style="width: 100%; display: inline-block; text-align: center;">
            <span><?php echo "Erro: " . $_FILES["afile"]["error"]; ?></span>
            <span class="msg-close" onclick="this.parentElement.remove()">X</span>
          </div>
      <?php 
      }else
      {
        $pasta = "/var/www/html/sistema/anexos2/upload/vendas/".$vendas_id;
        if(!file_exists($pasta)){mkdir($pasta, 0755);}
        //echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        //echo "Type: " . $_FILES["file"]["type"] . "<br>";
        //echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        //echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

        if (file_exists("/var/www/html/sistema/anexos2/upload/vendas/". $vendas_id ."/". $_FILES["file"]["name"]))
        { ?>
        <div class="alert-box merror" style="width: 100%; display: inline-block; text-align: center;">
          <span><?php echo "Erro: O arquivo ". $_FILES["file"]["name"] . " já existe. "; ?></span>
          <span class="msg-close" onclick="this.parentElement.remove()">X</span>
        </div>
      <?php 
        }
        else
        {
          move_uploaded_file($_FILES["file"]["tmp_name"],"/var/www/html/sistema/anexos2/upload/vendas/" . $vendas_id ."/". $_FILES["file"]["name"]);
          //echo "Stored in: " . "upload/vendas/" . $vendas_id ."/". $_FILES["file"]["name"] . "<br>";
          $anexo_caminho = "sistema/anexos2/upload/vendas/" . $vendas_id ."/". $_FILES["file"]["name"];
          $link_completo = "http://acionamento.grupofortune.com.br/".$anexo_caminho;
          $anexo_nome = $_FILES["file"]["name"];
          $anexo_tipo = $_FILES["file"]["type"];
          $anexo_data = date("Y-m-d H:i:s");
          //echo "<a href='".$link_completo."' target='_blank'>Download do arquivo</a><br>";
          //echo "<span style='font-size:6pt'>Horário do registro: ".date("Y-m-d H:i:s")."</span><br>";

        include("../../connect.php");
        include("../../utf8.php");
        $sql = "INSERT INTO `sistema`.`sys_vendas_anexos` (`anexo_id`, 
        `vendas_id`, 
        `anexo_nome`, 
        `anexo_caminho`, 
        `anexo_data`, 
        `anexo_usuario`, 
        `anexo_tipo`,
        `anexo_documento`) 
        VALUES (NULL, 
        '$vendas_id',
        '$anexo_nome',
        '$anexo_caminho',
        '$anexo_data',
        '$anexo_usuario',
        '$anexo_tipo',
        '$anexo_documento');"; 
        if (!mysql_query($sql,$con))
          {
          die('Error: ' . mysql_error());
          }
        ?>
        <div class="alert-box msuccess" style="width: 100%; display: inline-block; text-align: center;">
          <span>Arquivo anexado com sucesso!</span>
          <span class="msg-close" onclick="this.parentElement.remove()">X</span>
        </div>
      <?php
      mysql_close($con);      
        }
      }
}else
    { ?>
    <div class="alert-box merror" style="width: 100%; display: inline-block; text-align: center;">
      <span><?php echo "Tipo de arquivo inválido: ". $_FILES["afile"]["type"]; ?></span>
      <span class="msg-close" onclick="this.parentElement.remove()">X</span>
    </div>
<?php } ?>
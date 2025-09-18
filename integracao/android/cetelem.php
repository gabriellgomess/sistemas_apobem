<?php
header("Access-Control-Allow-Origin: *");
include("../../sistema/sistema/connect_db02.php");
//$_GET['link'] = "Link para assinar: https://app.clicksign.com/sign/03f9d52b-f270-466a-8576-b88fc84726d6?token_sms=SXVPHV - API Digital";
//echo "<br>Get link: ".$_GET['link']."<br>";
$link = substr($_GET['link'], 19);
//echo "<br>link: ".$link."<br>";
$link = substr($link, 0, strrpos($link, '-') - 1);
//echo "<br>link: ".$link."<br>";

$sql = "INSERT INTO sistema.sys_vmargem (id, url) VALUES (NULL, '".$link."');";
if (mysqli_query($con, $sql)){
	$id = mysqli_insert_id($con);
	echo "URL '".$link."' Cadastrada com Sucesso. </br>";
} else {
	die('Error insert sys_vmargem: ' . mysqli_error($con));
}
?>
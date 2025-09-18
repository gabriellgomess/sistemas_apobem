<?php 
/* ----- PRODUÇÃO ----- 
			"Accept: application/json",
			"merchant_id: 34064579000178",
			"merchant_key: 7B9117D92A089159AF650D2C28D73C17A6331938C477CC088E013AED2446EDAF",
			"Content-Type: application/json",
			"cache-control: no-cache"
			
	----- HOMOLOGAÇÃO ----- 
			"Accept: application/json",
			"merchant_id: defatoseguros",
			"merchant_key: 90B039BE764D1CD4A76389AA6446822556C8B8A676B4DA23E1878F1FE83625FD",
			"Content-Type: application/json",
			"cache-control: no-cache"
*/
$CURLOPT_HTTPHEADER = array(
			"Accept: application/json",
			"merchant_id: 34064579000178",
			"merchant_key: 7B9117D92A089159AF650D2C28D73C17A6331938C477CC088E013AED2446EDAF",
			"Content-Type: application/json",
			"cache-control: no-cache"
);
/*
---PRODUÇÃO---
https://esitef-ec.softwareexpress.com.br
---HOMOLOGAÇÃO---
https://esitef-homologacao.softwareexpress.com.br
*/
$link_prefixo = "https://esitef-ec.softwareexpress.com.br";

?>
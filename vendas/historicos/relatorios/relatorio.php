<style type="text/css">
	#extrair_rel{
		all: initial;
		all: unset;
		background: #2b5797;
	    color: white;
	    border-radius: 3px;
	    padding: 2px 5px;
	    cursor: pointer;
	}
	#extrair_rel:hover{
		background: gray;
	}
	#relatorio_form {
	    background: #f1f1f1;
	    padding: 5px 10px;
	    border-radius: 10px;
	    margin: 4px 0;
	    box-shadow: 0px 2px 3px -1px rgba(0,0,0,0.3);
	}
</style>
<script type="text/javascript">
	function submeter(event){
		
	  	var data_inicio = document.getElementById('data_inicio').value;
	  	var data_fim = document.getElementById('data_fim').value;
	  	if (!data_inicio || !data_fim)
	  	{
	  		event.preventDefault();
	  		alert("As datas de início e fim são obrigatórias!");	  		
    		return false;
		}
		return true;
	}
</script>
<form id="relatorio_form" onsubmit="submeter(event);" target="_blank" action="sistema/transacoes/relatorios/relatorio_xls.php">
	<div>Relatório geral de transações:</div>
	Data início: <input type="date" id="data_inicio" name="data_inicio"/>
	Data fim: <input type="date" id="data_fim" name="data_fim"/>
	<input type="submit" id="extrair_rel" value="Extrair Excel">
</form>
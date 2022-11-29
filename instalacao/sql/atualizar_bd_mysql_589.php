<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$resultado = $bd->Execute("SHOW COLUMNS FROM instrumento_campo LIKE 'instrumento_financero_projeto_leg'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if($existe) $bd->Execute("ALTER TABLE instrumento_campo CHANGE COLUMN instrumento_financero_projeto_leg instrumento_financeiro_projeto_leg VARCHAR(50) COLLATE latin1_swedish_ci DEFAULT NULL;");
	
	$sql->adTabela('os');
	$sql->adCampo('os_id');	
	$sql->adOnde('os_cnpj IS NULL');
	$lista=$sql->carregarColuna();
	$sql->limpar();
	
	foreach($lista as $os_id){
		$sql->adTabela('os_gestao');
		$sql->esqUnir('instrumento', 'instrumento', 'os_gestao_instrumento=instrumento_id');
		$sql->adCampo('instrumento_entidade_cnpj');
		$sql->adOnde('instrumento_entidade_cnpj IS NOT NULL');
		$sql->adOnde('os_gestao_os='.(int)$os_id);
		$sql->adOrdem('os_gestao_ordem DESC');
		$cnpj=$sql->linha();
		$sql->limpar();
		
		if (isset($cnpj['instrumento_entidade_cnpj']) && $cnpj['instrumento_entidade_cnpj']){
			$sql->adTabela('os');
			$sql->adAtualizar('os_cnpj', $cnpj['instrumento_entidade_cnpj']);
			$sql->adOnde('os_id='.(int)$os_id);
			$sql->exec();
			$sql->limpar();
			}
	
		}
	}

?>
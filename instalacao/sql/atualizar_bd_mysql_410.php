<?php
global $config, $bd;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	$sql->adTabela('patrocinadores');	
	$sql->adCampo('patrocinador_id, patrocinador_dddtel, patrocinador_tel, patrocinador_dddtel2, patrocinador_tel2, patrocinador_dddcel, patrocinador_cel');
	$sql->adOnde('patrocinador_dddtel IS NOT NULL OR patrocinador_dddtel2 IS NOT NULL OR patrocinador_dddcel IS NOT NULL');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		$sql->adTabela('patrocinadores');
		if ($linha['patrocinador_dddtel']) $sql->adAtualizar('patrocinador_tel', '('.$linha['patrocinador_dddtel'].') '.$linha['patrocinador_tel']);
		if ($linha['patrocinador_dddtel2']) $sql->adAtualizar('patrocinador_tel2', '('.$linha['patrocinador_dddtel2'].') '.$linha['patrocinador_tel2']);
		if ($linha['patrocinador_dddcel']) $sql->adAtualizar('patrocinador_cel', '('.$linha['patrocinador_dddcel'].') '.$linha['patrocinador_cel']);
		$sql->adOnde('patrocinador_id ='.(int)$linha['patrocinador_id']);
		$sql->exec();
		$sql->limpar();
		}
	}

?>
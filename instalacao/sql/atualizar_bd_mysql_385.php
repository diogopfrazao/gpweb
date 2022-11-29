<?php
global $config, $bd;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	$sql->adTabela('licao_arquivo');	
	$sql->adCampo('licao_arquivo.*');
	$sql->adOrdem('licao_arquivo_licao');
	$lista = $sql->lista();
	$sql->limpar();
	$qnt=0;
	$licao_atual=0;
	foreach($lista AS $linha) {
		if ($licao_atual!=$linha['licao_arquivo_licao']){
			$licao_atual==$linha['licao_arquivo_licao'];
			$qnt=0;
			}
		$qnt++;

		$sql->adTabela('arquivo');
		$sql->adInserir('arquivo_dono', $linha['licao_arquivo_usuario']);
		$sql->adInserir('arquivo_usuario_upload', $linha['licao_arquivo_usuario']);
		$sql->adInserir('arquivo_local', 'licao/'.$linha['licao_arquivo_licao'].'/');
		$sql->adInserir('arquivo_data', $linha['licao_arquivo_data']);
		$sql->adInserir('arquivo_nome', $linha['licao_arquivo_nome']);
		$sql->adInserir('arquivo_nome_real',$linha['licao_arquivo_id'].'_'.$linha['licao_arquivo_nome']);
		$sql->adInserir('arquivo_tipo', $linha['licao_arquivo_tipo'].'/'.$linha['licao_arquivo_extensao']);
		$sql->exec();
		$arquivo_id=$bd->Insert_ID('arquivo','arquivo_id');
		$sql->limpar();

		$sql->adTabela('arquivo_gestao');
		$sql->adInserir('arquivo_gestao_arquivo', $arquivo_id);
		$sql->adInserir('arquivo_gestao_licao', $linha['licao_arquivo_licao']);
		$sql->adInserir('arquivo_gestao_ordem', $qnt);
		$sql->exec();
		$sql->limpar();
		}	
	}
?>
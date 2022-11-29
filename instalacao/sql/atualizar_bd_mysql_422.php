<?php
global $config, $bd;
$sql = new BDConsulta;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	
	$sql->adTabela('agenda_arquivos');	
	$sql->adCampo('agenda_arquivos.*');
	$sql->adOrdem('agenda_arquivo_agenda_id');
	$lista = $sql->lista();
	$sql->limpar();
	$qnt=0;
	$agenda_atual=0;
	foreach($lista AS $linha) {
		if ($agenda_atual!=$linha['agenda_arquivo_agenda_id']){
			$agenda_atual==$linha['agenda_arquivo_agenda_id'];
			$qnt=0;
			}
		$qnt++;
		$sql->adTabela('arquivo');
		$sql->adInserir('arquivo_dono', $linha['agenda_arquivo_usuario']);
		$sql->adInserir('arquivo_usuario_upload', $linha['agenda_arquivo_usuario']);
		$sql->adInserir('arquivo_local', 'agendas/'.$linha['agenda_arquivo_agenda_id'].'/');
		$sql->adInserir('arquivo_data', $linha['agenda_arquivo_data']);
		$sql->adInserir('arquivo_nome', $linha['agenda_arquivo_nome']);
		$sql->adInserir('arquivo_nome_real',$linha['agenda_arquivo_id'].'_'.$linha['agenda_arquivo_nome']);
		$sql->adInserir('arquivo_tipo', $linha['agenda_arquivo_tipo'].'/'.$linha['agenda_arquivo_extensao']);
		$sql->exec();
		$arquivo_id=$bd->Insert_ID('arquivo','arquivo_id');
		$sql->limpar();
		$sql->adTabela('arquivo_gestao');
		$sql->adInserir('arquivo_gestao_arquivo', $arquivo_id);
		$sql->adInserir('arquivo_gestao_agenda', $linha['agenda_arquivo_agenda_id']);
		$sql->adInserir('arquivo_gestao_ordem', $qnt);
		$sql->exec();
		$sql->limpar();
		}	
		
		
	$sql->adTabela('evento_arquivos');	
	$sql->adCampo('evento_arquivos.*');
	$sql->adOrdem('evento_arquivo_evento_id');
	$lista = $sql->lista();
	$sql->limpar();
	$qnt=0;
	$evento_atual=0;
	foreach($lista AS $linha) {
		if ($evento_atual!=$linha['evento_arquivo_evento_id']){
			$evento_atual==$linha['evento_arquivo_evento_id'];
			$qnt=0;
			}
		$qnt++;
		$sql->adTabela('arquivo');
		$sql->adInserir('arquivo_dono', $linha['evento_arquivo_usuario']);
		$sql->adInserir('arquivo_usuario_upload', $linha['evento_arquivo_usuario']);
		$sql->adInserir('arquivo_local', 'eventos/'.$linha['evento_arquivo_evento_id'].'/');
		$sql->adInserir('arquivo_data', $linha['evento_arquivo_data']);
		$sql->adInserir('arquivo_nome', $linha['evento_arquivo_nome']);
		$sql->adInserir('arquivo_nome_real',$linha['evento_arquivo_id'].'_'.$linha['evento_arquivo_nome']);
		$sql->adInserir('arquivo_tipo', $linha['evento_arquivo_tipo'].'/'.$linha['evento_arquivo_extensao']);
		$sql->exec();
		$arquivo_id=$bd->Insert_ID('arquivo','arquivo_id');
		$sql->limpar();
		$sql->adTabela('arquivo_gestao');
		$sql->adInserir('arquivo_gestao_arquivo', $arquivo_id);
		$sql->adInserir('arquivo_gestao_evento', $linha['evento_arquivo_evento_id']);
		$sql->adInserir('arquivo_gestao_ordem', $qnt);
		$sql->exec();
		$sql->limpar();
		}				
	}
	
	
$sql->adTabela('agenda');	
$sql->esqUnir('usuarios', 'usuarios', 'agenda_dono=usuario_id');	
$sql->esqUnir('contatos', 'contatos', 'usuario_contato=contato_id');	
$sql->adCampo('agenda_id, contato_cia');
$lista = $sql->lista();
$sql->limpar();
foreach($lista AS $linha) {
	if ($linha['agenda_id'] && $linha['contato_cia']) {
		$sql->adTabela('agenda');
		$sql->adAtualizar('agenda_cia', $linha['contato_cia']);
		$sql->adOnde('agenda_id ='.(int)$linha['agenda_id']);
		$sql->exec();
		$sql->limpar();
		}
	}
	
	
	
	
?>
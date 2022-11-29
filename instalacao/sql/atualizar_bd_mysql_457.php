<?php
global $config, $bd;

require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once BASE_DIR.'/classes/ui.class.php';
$Aplic = new CAplic();
include_once BASE_DIR.'/classes/aplic.class.php';
require_once BASE_DIR.'/classes/data.class.php';
require_once BASE_DIR.'/modulos/admin/admin.class.php';
require_once BASE_DIR.'/modulos/sistema/perfis/perfis.class.php';


if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	require_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
	$cache = CTarefaCache::getInstance();

  $sql = new BDConsulta;
	
	$sql->adTabela('problema');
	$sql->adCampo('problema_id, problema_cia, problema_inicio, problema_fim');
	$lista=$sql->lista();
	$sql->limpar();
	foreach ($lista as $linha){
		$exped1 = $cache->getExpedienteParaHoje((int)$linha['problema_cia'], null, null, substr($linha['problema_inicio'], 0, 10));
		$exped2 = $cache->getExpedienteParaHoje((int)$linha['problema_cia'], null, null, substr($linha['problema_fim'], 0, 10));
		$duracao=$cache->horasPeriodo($exped1['inicio'], $exped2['fim'], (int)$linha['problema_cia']);
		$sql->adTabela('problema');
		$sql->adAtualizar('problema_inicio', $exped1['inicio']);
		$sql->adAtualizar('problema_fim', $exped2['fim']);
		$sql->adAtualizar('problema_duracao', $duracao);
		$sql->adOnde('problema_id='.$linha['problema_id']);
		$sql->exec();
		$sql->limpar();	
		}
	
	
	$sql->adTabela('eventos');
	$sql->adCampo('evento_id, evento_cia, evento_inicio, evento_fim');
	$lista=$sql->lista();
	$sql->limpar();
	foreach ($lista as $linha){
		$duracao=$cache->horasPeriodo($linha['evento_inicio'], $linha['evento_fim'], (int)$linha['evento_cia']);
		$sql->adTabela('eventos');
		$sql->adAtualizar('evento_duracao', $duracao);
		$sql->adOnde('evento_id='.$linha['evento_id']);
		$sql->exec();
		$sql->limpar();	
		}
	
	
	$sql->adTabela('baseline_eventos');
	$sql->adCampo('baseline_id, evento_id, evento_cia, evento_inicio, evento_fim');
	$lista=$sql->lista();
	$sql->limpar();
	foreach ($lista as $linha){
		$duracao=$cache->horasPeriodo($linha['evento_inicio'], $linha['evento_fim'], (int)$linha['evento_cia']);
		$sql->adTabela('baseline_eventos');
		$sql->adAtualizar('evento_duracao', $duracao);
		$sql->adOnde('evento_id='.$linha['evento_id']);
		$sql->adOnde('baseline_id='.$linha['baseline_id']);
		$sql->exec();
		$sql->limpar();	
		}
		
		
	$sql->adTabela('agenda');
	$sql->adCampo('agenda_id, agenda_cia, agenda_inicio, agenda_fim');
	$lista=$sql->lista();
	$sql->limpar();
	foreach ($lista as $linha){
		$duracao=$cache->horasPeriodo($linha['agenda_inicio'], $linha['agenda_fim'], (int)$linha['agenda_cia']);
		$sql->adTabela('agenda');
		$sql->adAtualizar('agenda_duracao', $duracao);
		$sql->adOnde('agenda_id='.$linha['agenda_id']);
		$sql->exec();
		$sql->limpar();	
		}	

	}
?>
<?php
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('instrumento');
	$sql->adCampo('instrumento_id, instrumento_valor');	
	$lista=$sql->lista();
	$sql->limpar();
	
	
	foreach($lista as $linha){
		
		$sql->adTabela('instrumento_gestao');
		$sql->esqUnir('instrumento', 'instrumento', 'instrumento_id=instrumento_gestao_semelhante');
		$sql->adCampo('SUM(instrumento_valor)');
		$sql->adOnde('instrumento_gestao_semelhante IS NOT NULL');
		$sql->adOnde('instrumento_gestao_instrumento='.(int)$linha['instrumento_id']);
		$soma_filhos=$sql->Resultado();
		$sql->limpar();

		
		
		
		
		//if ($linha['instrumento_id']==168) ver($soma_filhos);
		
		//$soma_filhos=0;
		
		$sql->adTabela('instrumento_gestao');
		$sql->esqUnir('instrumento', 'instrumento', 'instrumento_id=instrumento_gestao_instrumento');
		$sql->adCampo('SUM(instrumento_valor)');
		$sql->adOnde('instrumento_gestao_instrumento IS NOT NULL');
		$sql->adOnde('instrumento_gestao_semelhante='.(int)$linha['instrumento_id']);
		$soma_pais=$sql->Resultado();
		$sql->limpar();
		
		$sql->adTabela('instrumento');
		$sql->adAtualizar('instrumento_valor_atual', $linha['instrumento_valor']+$soma_filhos+$soma_pais);
		$sql->adOnde('instrumento_id='.(int)$linha['instrumento_id']);
		$sql->exec();
		$sql->limpar();
		}
	}

?>
<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	
	$sql->adTabela('os');	
	$sql->adCampo('os_id, os_data, os_cia, ano(os_data) AS ano');
	$sql->adOrdem('os_cia, os_data ASC, os_id ASC');
	$linhas=$sql->Lista();
	$sql->limpar();
	
	$ano=null;
	$cia_atual=null;
	$primeiro=true;
	$primeira_cia=null;
	$numero=0;
	
	foreach($linhas as $linha){
		if ($primeiro) {
			$ano=$linha['ano'];
			$cia_atual=$linha['os_cia'];
			$primeira_cia=$linha['os_cia'];
			$numero=0;
			$primeiro=false;
			}
		else if ($cia_atual!=$linha['os_cia']){
			$sql->adTabela('numeracao');
			$sql->adInserir('numeracao_modulo', 'os');
			$sql->adInserir('numeracao_cia', $cia_atual);
			$sql->adInserir('numeracao_ano', $ano);
			$sql->adInserir('numeracao_numero', $numero);
			$sql->exec();
			$sql->limpar();
			
			$ano=$linha['ano'];
			$cia_atual=$linha['os_cia'];
			$numero=0;
			}
		else if ($ano!=$linha['ano']){
			$ano=$linha['ano'];
			$numero=0;
			}
		
		$numero++;
		if ($numero < 10) $saida='00'.$numero; 
		elseif ($numero < 100) $saida='0'.$numero; 
		$saida=$saida.'/'.$linha['ano'];
		
		$sql->adTabela('os');
		$sql->adAtualizar('os_numero', $saida);
		$sql->adOnde('os_id='.(int)$linha['os_id']);
		$sql->exec();
		$sql->limpar();
		}
	
	if ($primeira_cia==$cia_atual && $numero){
		$sql->adTabela('numeracao');
		$sql->adInserir('numeracao_modulo', 'os');
		$sql->adInserir('numeracao_cia', $cia_atual);
		$sql->adInserir('numeracao_ano', $ano);
		$sql->adInserir('numeracao_numero', $numero);
		$sql->exec();
		$sql->limpar();
		}
	}
?>
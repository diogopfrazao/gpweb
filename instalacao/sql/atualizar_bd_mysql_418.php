<?php
global $config;


$sql = new BDConsulta;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){	
	$sql->adTabela('tema_perspectiva');	
	$sql->adCampo('tema_perspectiva.*');
	$sql->adOrdem('tema_perspectiva_tema, tema_perspectiva_ordem');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		//checar se nao existe
		$sql->adTabela('tema_gestao');	
		$sql->adCampo('tema_gestao_id');
		$sql->adOnde('tema_gestao_tema ='.(int)$linha['tema_perspectiva_tema']);
		$sql->adOnde('tema_gestao_perspectiva ='.(int)$linha['tema_perspectiva_perspectiva']);
		$existe = $sql->Resultado();
		$sql->limpar();
		if (!$existe){
			$sql->adTabela('tema_gestao');	
			$sql->adCampo('count(tema_gestao_id)');
			$sql->adOnde('tema_gestao_tema ='.(int)$linha['tema_perspectiva_tema']);
			$qnt = $sql->Resultado();
			$sql->limpar();
			
			$sql->adTabela('tema_gestao');
			$sql->adInserir('tema_gestao_tema', $linha['tema_perspectiva_tema']);
			$sql->adInserir('tema_gestao_perspectiva', $linha['tema_perspectiva_perspectiva']);
			$sql->adInserir('tema_gestao_ordem', ($qnt+1));
			$sql->exec();
			$sql->limpar();
			}
		}

	$sql->adTabela('objetivo_perspectiva');	
	$sql->adCampo('objetivo_perspectiva.*');
	$sql->adOrdem('objetivo_perspectiva_objetivo, objetivo_perspectiva_ordem');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		//checar se nao existe
		$sql->adTabela('objetivo_gestao');	
		$sql->adCampo('objetivo_gestao_id');
		$sql->adOnde('objetivo_gestao_objetivo ='.(int)$linha['objetivo_perspectiva_objetivo']);
		if ($linha['objetivo_perspectiva_perspectiva']) $sql->adOnde('objetivo_gestao_perspectiva ='.(int)$linha['objetivo_perspectiva_perspectiva']);
		else $sql->adOnde('objetivo_gestao_tema ='.(int)$linha['objetivo_perspectiva_tema']);
		$existe = $sql->Resultado();
		$sql->limpar();
		if (!$existe){
			$sql->adTabela('objetivo_gestao');	
			$sql->adCampo('count(objetivo_gestao_id)');
			$sql->adOnde('objetivo_gestao_objetivo ='.(int)$linha['objetivo_perspectiva_objetivo']);
			$qnt = $sql->Resultado();
			$sql->limpar();
			
			$sql->adTabela('objetivo_gestao');
			$sql->adInserir('objetivo_gestao_objetivo', $linha['objetivo_perspectiva_objetivo']);
			if ($linha['objetivo_perspectiva_perspectiva']) $sql->adInserir('objetivo_gestao_perspectiva', $linha['objetivo_perspectiva_perspectiva']);
			else $sql->adInserir('objetivo_gestao_tema', $linha['objetivo_perspectiva_tema']);
			$sql->adInserir('objetivo_gestao_ordem', ($qnt+1));
			$sql->exec();
			$sql->limpar();
			}
		}	
		
	$sql->adTabela('me_objetivo');	
	$sql->adCampo('me_objetivo.*');
	$sql->adOrdem('me_objetivo_me, me_objetivo_ordem');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		//checar se nao existe
		$sql->adTabela('me_gestao');	
		$sql->adCampo('me_gestao_id');
		$sql->adOnde('me_gestao_me ='.(int)$linha['me_objetivo_me']);
		$sql->adOnde('me_gestao_objetivo ='.(int)$linha['me_objetivo_objetivo']);
		$existe = $sql->Resultado();
		$sql->limpar();
		if (!$existe){
			$sql->adTabela('me_gestao');	
			$sql->adCampo('count(me_gestao_id)');
			$sql->adOnde('me_gestao_me ='.(int)$linha['me_objetivo_me']);
			$qnt = $sql->Resultado();
			$sql->limpar();
			
			$sql->adTabela('me_gestao');
			$sql->adInserir('me_gestao_me', $linha['me_objetivo_me']);
			$sql->adInserir('me_gestao_objetivo', $linha['me_objetivo_objetivo']);
			$sql->adInserir('me_gestao_ordem', ($qnt+1));
			$sql->exec();
			$sql->limpar();
			}
		}

	$sql->adTabela('fator_objetivo');	
	$sql->adCampo('fator_objetivo.*');
	$sql->adOrdem('fator_objetivo_fator, fator_objetivo_ordem');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		//checar se nao existe
		$sql->adTabela('fator_gestao');	
		$sql->adCampo('fator_gestao_id');
		$sql->adOnde('fator_gestao_fator ='.(int)$linha['fator_objetivo_fator']);
		if ($linha['fator_objetivo_objetivo']) $sql->adOnde('fator_gestao_objetivo ='.(int)$linha['fator_objetivo_objetivo']);
		else $sql->adOnde('fator_gestao_me ='.(int)$linha['fator_objetivo_me']);
		$existe = $sql->Resultado();
		$sql->limpar();
		if (!$existe){
			$sql->adTabela('fator_gestao');	
			$sql->adCampo('count(fator_gestao_id)');
			$sql->adOnde('fator_gestao_fator ='.(int)$linha['fator_objetivo_fator']);
			$qnt = $sql->Resultado();
			$sql->limpar();
			
			$sql->adTabela('fator_gestao');
			$sql->adInserir('fator_gestao_fator', $linha['fator_objetivo_fator']);
			if ($linha['fator_objetivo_objetivo']) $sql->adInserir('fator_gestao_objetivo', $linha['fator_objetivo_objetivo']);
			else $sql->adInserir('fator_gestao_me', $linha['fator_objetivo_me']);
			$sql->adInserir('fator_gestao_ordem', ($qnt+1));
			$sql->exec();
			$sql->limpar();
			}
		}	
				
	$sql->adTabela('estrategia_fator');	
	$sql->adCampo('estrategia_fator.*');
	$sql->adOrdem('estrategia_fator_estrategia, estrategia_fator_ordem');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		//checar se nao existe
		$sql->adTabela('estrategia_gestao');	
		$sql->adCampo('estrategia_gestao_id');
		$sql->adOnde('estrategia_gestao_estrategia ='.(int)$linha['estrategia_fator_estrategia']);
		if ($linha['estrategia_fator_perspectiva']) $sql->adOnde('estrategia_gestao_perspectiva ='.(int)$linha['estrategia_fator_perspectiva']);
		if ($linha['estrategia_fator_tema']) $sql->adOnde('estrategia_gestao_tema ='.(int)$linha['estrategia_fator_tema']);
		if ($linha['estrategia_fator_objetivo']) $sql->adOnde('estrategia_gestao_objetivo ='.(int)$linha['estrategia_fator_objetivo']);
		if ($linha['estrategia_fator_me']) $sql->adOnde('estrategia_gestao_me ='.(int)$linha['estrategia_fator_me']);
		else $sql->adOnde('estrategia_gestao_fator ='.(int)$linha['estrategia_fator_fator']);
		$existe = $sql->Resultado();
		$sql->limpar();
		if (!$existe){
			$sql->adTabela('estrategia_gestao');	
			$sql->adCampo('count(estrategia_gestao_id)');
			$sql->adOnde('estrategia_gestao_estrategia ='.(int)$linha['estrategia_fator_estrategia']);
			$qnt = $sql->Resultado();
			$sql->limpar();
			
			$sql->adTabela('estrategia_gestao');
			$sql->adInserir('estrategia_gestao_estrategia', $linha['estrategia_fator_estrategia']);
			if ($linha['estrategia_fator_perspectiva']) $sql->adInserir('estrategia_gestao_perspectiva', $linha['estrategia_fator_perspectiva']);
			if ($linha['estrategia_fator_tema']) $sql->adInserir('estrategia_gestao_tema', $linha['estrategia_fator_tema']);
			if ($linha['estrategia_fator_objetivo']) $sql->adInserir('estrategia_gestao_objetivo', $linha['estrategia_fator_objetivo']);
			if ($linha['estrategia_fator_me']) $sql->adInserir('estrategia_gestao_me', $linha['estrategia_fator_me']);
			if ($linha['estrategia_fator_fator']) $sql->adInserir('estrategia_gestao_fator', $linha['estrategia_fator_fator']);
			$sql->adInserir('estrategia_gestao_ordem', ($qnt+1));
			$sql->exec();
			$sql->limpar();
			}
		}		
	
	}	
	
	
	
?>
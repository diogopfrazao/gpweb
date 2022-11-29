<?php
global $config;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')){
	$sql = new BDConsulta;
	$sql->adTabela('painel');	
	$sql->adCampo('painel.*');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		
		if ($linha['painel_tarefa']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'tarefa');
			$sql->adAtualizar('painel_link_chave', $linha['painel_tarefa']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_projeto']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'projeto');
			$sql->adAtualizar('painel_link_chave', $linha['painel_projeto']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_pratica']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'pratica');
			$sql->adAtualizar('painel_link_chave', $linha['painel_pratica']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_acao']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'acao');
			$sql->adAtualizar('painel_link_chave', $linha['painel_acao']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_perspectiva']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'perspectiva');
			$sql->adAtualizar('painel_link_chave', $linha['painel_perspectiva']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_tema']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'tema');
			$sql->adAtualizar('painel_link_chave', $linha['painel_tema']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_objetivo']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'objetivo');
			$sql->adAtualizar('painel_link_chave', $linha['painel_objetivo']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_me']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'me');
			$sql->adAtualizar('painel_link_chave', $linha['painel_me']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}	
		elseif ($linha['painel_fator']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'fator');
			$sql->adAtualizar('painel_link_chave', $linha['painel_fator']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_estrategia']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'estrategia');
			$sql->adAtualizar('painel_link_chave', $linha['painel_estrategia']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}	
		elseif ($linha['painel_meta']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'meta');
			$sql->adAtualizar('painel_link_chave', $linha['painel_meta']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_indicador']){
			$sql->adTabela('painel');
			$sql->adAtualizar('painel_link_tipo', 'indicador');
			$sql->adAtualizar('painel_link_chave', $linha['painel_indicador']);
			$sql->adOnde('painel_id='.(int)$linha['painel_id']);
			$sql->exec();
			$sql->limpar();
			}		
		}
		
		
		
	$sql->adTabela('painel_odometro');	
	$sql->adCampo('painel_odometro.*');
	$lista = $sql->lista();
	$sql->limpar();
	foreach($lista AS $linha) {
		
		if ($linha['painel_odometro_tarefa']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'tarefa');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_tarefa']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_projeto']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'projeto');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_projeto']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_pratica']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'pratica');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_pratica']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_acao']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'acao');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_acao']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_perspectiva']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'perspectiva');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_perspectiva']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_tema']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'tema');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_tema']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_objetivo']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'objetivo');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_objetivo']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_me']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'me');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_me']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}	
		elseif ($linha['painel_odometro_fator']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'fator');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_fator']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_estrategia']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'estrategia');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_estrategia']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}	
		elseif ($linha['painel_odometro_meta']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'meta');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_meta']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}
		elseif ($linha['painel_odometro_indicador']){
			$sql->adTabela('painel_odometro');
			$sql->adAtualizar('painel_odometro_link_tipo', 'indicador');
			$sql->adAtualizar('painel_odometro_link_chave', $linha['painel_odometro_indicador']);
			$sql->adOnde('painel_odometro_id='.(int)$linha['painel_odometro_id']);
			$sql->exec();
			$sql->limpar();
			}		
		}	
		
	}
?>
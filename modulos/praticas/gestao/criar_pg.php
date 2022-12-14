<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

$sql = new BDConsulta;

$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
$dept_id = ($Aplic->getEstado('dept_id') !== null && $Aplic->profissional ? $Aplic->getEstado('dept_id') : null);

if (getParam($_REQUEST, 'salvar', 0)){
	//verificar se j? existe do ano pedido
	$sql->adTabela('plano_gestao');
	$sql->adCampo('count(pg_id)');
	$sql->adOnde('pg_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
	if (getParam($_REQUEST, 'pg_ano', '')) $sql->adOnde('pg_ano='.getParam($_REQUEST, 'pg_ano', ''));
	$existe=$sql->Resultado();
	$sql->limpar();
	if ($existe) ver2('J? existe um plano de gest?o criado no ano escolhido!');
	else {	
		$ano_importar=getParam($_REQUEST, 'pg_ano_importar', 0);
		$sql->adTabela('plano_gestao');
		$sql->adInserir('pg_cia', $cia_id);
		$sql->adInserir('pg_ano', getParam($_REQUEST, 'pg_ano', '0'));
		$sql->adInserir('pg_ultima_alteracao', date('Y-m-d H:i:s'));
		$sql->adInserir('pg_usuario_ultima_alteracao', $Aplic->usuario_id);
		if ($dept_id) $sql->adInserir('pg_dept', $dept_id);
		$sql->exec();
		$pg_id=$bd->Insert_ID('plano_gestao','pg_id');
		$sql->limpar();
		
		$sql->adTabela('plano_gestao2');
		$sql->adInserir('pg_id', $pg_id);
		$sql->exec();
		$sql->limpar();
		
		
		if ($ano_importar){
			$sql->adTabela('plano_gestao');
			$sql->adCampo('plano_gestao.*');
			$sql->adOnde('pg_cia='.(int)$cia_id);
			if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
			else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
			$sql->adOnde('pg_ano='.(int)$ano_importar);
			$plano_gestao_antigo=$sql->Linha();
			$pg_id_antigo=$plano_gestao_antigo['pg_id'];
			$sql->limpar();
			if ($pg_id_antigo){
				$sql->adTabela('plano_gestao');
				foreach($plano_gestao_antigo as $chave => $valor) if ($chave!='pg_id' && $chave!='pg_ano' && $chave!='pg_ultima_alteracao' && $chave!='pg_usuario_ultima_alteracao') $sql->adAtualizar($chave, $valor);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				}
				
			
			
			$sql->adTabela('plano_gestao2');
			$sql->adCampo('plano_gestao2.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$plano_gestao_antigo2=$sql->Linha();
			$sql->limpar();
			if ($plano_gestao_antigo2['pg_id']){
				$sql->adTabela('plano_gestao2');
				foreach($plano_gestao_antigo2 as $chave => $valor) if ($chave!='pg_id') $sql->adAtualizar($chave, $valor);
				$sql->adOnde('pg_id='.(int)$pg_id);
				$sql->exec();
				$sql->limpar();
				}
	
			
			$sql->adTabela('plano_gestao_tema');
			$sql->adCampo('plano_gestao_tema.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_tema');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('tema_id', $linha['tema_id']);
					$sql->adInserir('tema_ordem', $linha['tema_ordem']);
					$sql->exec();
					$sql->limpar();
					}
				}	
			
			
			
				
	
			$sql->adTabela('plano_gestao_objetivo');
			$sql->adCampo('plano_gestao_objetivo.*');
			$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['plano_gestao_objetivo_plano_gestao']){
					$sql->adTabela('plano_gestao_objetivo');
					$sql->adInserir('plano_gestao_objetivo_plano_gestao', $pg_id);
					$sql->adInserir('plano_gestao_objetivo_objetivo', $linha['plano_gestao_objetivo_objetivo']);
					$sql->adInserir('plano_gestao_objetivo_ordem', $linha['plano_gestao_objetivo_ordem']);
					$sql->exec();
					$sql->limpar();
					}
				}	
				
				
			
			$sql->adTabela('plano_gestao_estrategias');
			$sql->adCampo('plano_gestao_estrategias.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_estrategias');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('pg_estrategia_id', $linha['pg_estrategia_id']);
					$sql->adInserir('pg_estrategia_ordem', $linha['pg_estrategia_ordem']);
					$sql->exec();
					$sql->limpar();
					}
				}	
				
		
			
			$sql->adTabela('plano_gestao_fator');
			$sql->adCampo('plano_gestao_fator.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_fator');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('fator_id', $linha['fator_id']);
					$sql->adInserir('fator_ordem', $linha['fator_ordem']);
					$sql->exec();
					$sql->limpar();
					}
				}	
				
			
			$sql->adTabela('plano_gestao_perspectivas');
			$sql->adCampo('plano_gestao_perspectivas.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_perspectivas');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('pg_perspectiva_id', $linha['pg_perspectiva_id']);
					$sql->adInserir('pg_perspectiva_ordem', $linha['pg_perspectiva_ordem']);
					$sql->exec();
					$sql->limpar();
					}
				}	
			
	
			$sql->adTabela('plano_gestao_metas');
			$sql->adCampo('plano_gestao_metas.*');
			$sql->adOnde('pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $linha){
				if ($linha['pg_id']){
					$sql->adTabela('plano_gestao_metas');
					$sql->adInserir('pg_id', $pg_id);
					$sql->adInserir('pg_meta_id', $linha['pg_meta_id']);
					$sql->adInserir('pg_meta_ordem', $linha['pg_meta_ordem']);
					$sql->exec();
					$sql->limpar();
					}
				}	
			
			
			$sql->adTabela('plano_gestao_arquivo');
			$sql->adCampo('plano_gestao_arquivo.*');
			$sql->adOnde('plano_gestao_arquivo_plano_gestao='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['plano_gestao_arquivo_plano_gestao']){
					$sql->adTabela('plano_gestao_arquivo');
					$sql->adInserir('plano_gestao_arquivo_plano_gestao', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='plano_gestao_arquivo_id' && $chave!='plano_gestao_arquivo_plano_gestao') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->limpar();
					}
				}		
				
		
			$sql->adTabela('plano_gestao_diretrizes');
			$sql->adCampo('plano_gestao_diretrizes.*');
			$sql->adOnde('pg_diretriz_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_diretriz_pg_id']){
					$sql->adTabela('plano_gestao_diretrizes');
					$sql->adInserir('pg_diretriz_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_diretriz_id' && $chave!='pg_diretriz_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->limpar();
					}
				}
			
			
			$sql->adTabela('plano_gestao_diretrizes_superiores');
			$sql->adCampo('plano_gestao_diretrizes_superiores.*');
			$sql->adOnde('pg_diretriz_superior_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_diretriz_superior_pg_id']){
					$sql->adTabela('plano_gestao_diretrizes_superiores');
					$sql->adInserir('pg_diretriz_superior_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_diretriz_superior_id' && $chave!='pg_diretriz_superior_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->limpar();
					}
				}
			
			
	
			$sql->adTabela('plano_gestao_fornecedores');
			$sql->adCampo('plano_gestao_fornecedores.*');
			$sql->adOnde('pg_fornecedor_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_fornecedor_pg_id']){
					$sql->adTabela('plano_gestao_fornecedores');
					$sql->adInserir('pg_fornecedor_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_fornecedor_id' && $chave!='pg_fornecedor_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->limpar();
					}
				}
	
		
			$sql->adTabela('plano_gestao_pessoal');
			$sql->adCampo('plano_gestao_pessoal.*');
			$sql->adOnde('pg_pessoal_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_pessoal_pg_id']){
					$sql->adTabela('plano_gestao_pessoal');
					$sql->adInserir('pg_pessoal_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_pessoal_id' && $chave!='pg_pessoal_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->limpar();
					}
				}		
			
			
			$sql->adTabela('pg_swot');
			$sql->adCampo('pg_swot.*');
			$sql->adOnde('pg_swot_pg='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_swot_pg']){
					$sql->adTabela('pg_swot');
					$sql->adInserir('pg_swot_pg', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_swot_id' && $chave!='pg_swot_pg') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->limpar();
					}
				}		
			
			
			$sql->adTabela('plano_gestao_premiacoes');
			$sql->adCampo('plano_gestao_premiacoes.*');
			$sql->adOnde('pg_premiacao_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_premiacao_pg_id']){
					$sql->adTabela('plano_gestao_premiacoes');
					$sql->adInserir('pg_premiacao_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_premiacao_id' && $chave!='pg_premiacao_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->limpar();
					}
				}		
			
			$sql->adTabela('plano_gestao_principios');
			$sql->adCampo('plano_gestao_principios.*');
			$sql->adOnde('pg_principio_pg_id='.(int)$pg_id_antigo);
			$linhas=$sql->Lista();
			$sql->limpar();
			foreach($linhas as $campos){
				if ($campos['pg_principio_pg_id']){
					$sql->adTabela('plano_gestao_principios');
					$sql->adInserir('pg_principio_pg_id', $pg_id);
					foreach($campos as $chave => $valor) if ($chave!='pg_principio_id' && $chave!='pg_principio_pg_id') $sql->adInserir($chave, $valor);
					$sql->exec();
					$sql->limpar();
					}
				}		
			}
			
		$Aplic->setEstado('pg_ano', getParam($_REQUEST, 'pg_ano', null));
		$Aplic->setEstado('editarPG', 1);
		

		if ($Aplic->profissional) echo '<script>parent.gpwebApp._popupCallback('.$pg_id.');</script>';
		else echo '<script>alert("Foi criad'.$config['genero_plano_gestao'].' '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'");window.opener.criar_pg('.$pg_id.'); window.close();</script>';
		
		
		
		}
	}

$anos=array();
$anos_antigos=array();
$sql->adTabela('plano_gestao');
$sql->adCampo('DISTINCT pg_ano');
$sql->adOnde('pg_cia='.(int)$cia_id);
if ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);	
else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
$sql->adOrdem('pg_ano DESC');
$listaanos=$sql->Lista();
$sql->limpar();
$anos_antigos[0]='';
foreach ($listaanos as $ano) $anos_antigos[(int)$ano['pg_ano']]=(int)$ano['pg_ano'];

$anos[0]='';
for($i=(int)date('Y')+10; $i > (int)date('Y')-20; $i--)$anos[$i]=$i; 

echo '<form name="env" id="env" method="POST">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="criar_pg" />';
echo '<input type="hidden" name="u" value="gestao" />';
echo '<input type="hidden" name="salvar" value="0" />';

echo estiloTopoCaixa();
echo '<table id="tblPraticas" border=0 cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td colspan=2 align=center><h1>Cria??o de nov'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'</td></tr>'; 

echo '<tr><td colspan=2 align=center><h1>'.nome_cia($cia_id).'</td></tr>'; 
if ($dept_id) echo '<tr><td colspan=2 align=center><h1>'.nome_dept($dept_id).'</td></tr>'; 

echo '<tr><td align=right style="white-space: nowrap">'.dica('Ano d'.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']), 'Selecione o ano d'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].' a ser criado.').'Ano:'.dicaF().'</td><td width="100%">'.selecionaVetor($anos, 'pg_ano', 'class="texto"',(int)date('Y')).'</td></tr>';

echo '<tr><td align=right style="white-space: nowrap">'.dica('Importar', 'Caso deseje importar os dados de outr'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].', selecione o ano do mesmo.').'Importar de:'.dicaF().'</td><td width="100%">'.selecionaVetor($anos_antigos, 'pg_ano_importar', 'class="texto"',0).'</td></tr>';

echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('criar', 'Criar', 'Criar '.$config['genero_plano_gestao'].' nov'.$config['genero_plano_gestao'].' '.$config['plano_gestao'],'','env.salvar.value=1; env.submit();').'</td><td width="80%">&nbsp;</td><td>'.botao('cancelar', 'Cancelar', 'Clique neste bot?o para cancelar a cria??o','','window.opener = window; window.close()').'</td></tr></table></td></tr>';



echo '</table>';
echo estiloFundoCaixa();
echo '</form>';
?>
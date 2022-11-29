<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
  
require_once BASE_DIR.'/modulos/projetos/baseline.class.php';

function incluir_baseline_ajax($projeto_id=0, $baseline_id=0, $baseline_nome=null, $baseline_descricao=null){
	global $bd, $Aplic, $config;
	$sql = new BDConsulta;

	$baseline_nome=previnirXSS(utf8_decode($baseline_nome));
	$baseline_descricao=previnirXSS(utf8_decode($baseline_descricao));

	if ($baseline_id){
		$sql->adTabela('baseline');
		$sql->adAtualizar('baseline_nome', $baseline_nome);
		$sql->adAtualizar('baseline_descricao', $baseline_descricao);
		$sql->adOnde('baseline_id ='.(int)$baseline_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {
		
		$baseline = new CBaseline;
		$baseline->setProjeto($projeto_id, $baseline_nome, $baseline_descricao);
		$baseline->gerar();
		

		}
	$saida=atualizar_baselines($projeto_id);
	$objResposta = new xajaxResponse();
	
	$objResposta->assign("combo_baselines","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("incluir_baseline_ajax");	


function excluir_baseline_ajax($baseline_id, $projeto_id){
	$sql = new BDConsulta;
	$sql->setExcluir('baseline');
	$sql->adOnde('baseline_id='.$baseline_id);
	$sql->exec();
	$saida=atualizar_baselines($projeto_id);
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_baselines","innerHTML", $saida);
	return $objResposta;
	}

$xajax->registerFunction("excluir_baseline_ajax");	


function atualizar_baselines($projeto_id){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('baseline');
	$sql->adCampo('baseline.*');
		$sql->adOnde('baseline_projeto_id = '.(int)$projeto_id);
	$sql->adOrdem('baseline_data');
	$baselines=$sql->ListaChave('baseline_id');
	$sql->limpar();
	$saida='';
	if (count($baselines)) {
		$saida.= '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th>'.utf8_encode('Nome').'</th><th>'.utf8_encode('descrição').'</th><th></th></tr>';
		foreach ($baselines as $baseline_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td align="left">'.utf8_encode($linha['baseline_nome']).'</td>';
			$saida.= '<td>'.utf8_encode($linha['baseline_descricao']).'</td>';
			$saida.= '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_baseline('.$linha['baseline_id'].');">'.imagem('icones/editar.gif').'</a>';
			
			$sql->adTabela('baseline_tarefas');
			$sql->adCampo('tempo_em_segundos(diferenca_tempo(NOW(),MIN(tarefa_inicio)))/3600');
			$sql->adOnde('baseline_id='.(int)$baseline_id);
			$resultado=$sql->resultado();
			$sql->limpar();
	
			if ($resultado > 0) $saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta baseline?\')) {excluir_baseline('.$linha['baseline_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta baseline.').'</a>';
			$saida.= '</td>';
		
			$saida.= '</tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	return $saida;
	}	

$xajax->registerFunction("atualizar_baselines");		
	
function editar_baseline($baseline_id){
	global $config;
	
	$objResposta = new xajaxResponse();
	
	$sql = new BDConsulta;

	$sql->adTabela('baseline');
	$sql->adCampo('baseline_nome, baseline_descricao');
	$sql->adOnde('baseline_id = '.(int)$baseline_id);
	$linha=$sql->Linha();
	$sql->limpar();
	$saida='';	
	$objResposta->assign("baseline_id","value", $baseline_id);
	$objResposta->assign("baseline_nome","value", utf8_encode($linha['baseline_nome']));	
	$objResposta->assign("apoio_baseline_descricao","value", utf8_encode($linha['baseline_descricao']));
	return $objResposta;
	}	

$xajax->registerFunction("editar_baseline");	


$xajax->processRequest();
?>
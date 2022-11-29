<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

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
		$saida.= '<table cellspacing=0 cellpadding=0><tr><td></td><td><table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th>'.utf8_encode('Nome').'</th><th>'.utf8_encode('descri��o').'</th><th></th></tr>';
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
	
			if ($resultado > 0) $saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta baseline?\')) {excluir_baseline('.$linha['baseline_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir esta baseline.').'</a>';
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
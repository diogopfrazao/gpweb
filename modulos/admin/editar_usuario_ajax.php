<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function exibir_usuarios($usuarios){
	global $config;
	$usuarios_selecionados=explode(',', $usuarios);
	$saida_usuarios='';
	if (count($usuarios_selecionados)) {
			$saida_usuarios.= '<table cellpadding=0 cellspacing=0>';
			$saida_usuarios.= '<tr><td class="texto" style="width:400px;">'.link_usuario($usuarios_selecionados[0],'','','esquerda');
			$qnt_lista_usuarios=count($usuarios_selecionados);
			if ($qnt_lista_usuarios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';		
					$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
					}
			$saida_usuarios.= '</td></tr></table>';
			} 
	else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_usuarios',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios");

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_dept($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($depts_selecionados[$i]).'<br>';		
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			} 
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");




function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");


function qnt_permitida($usuario_id=null, $usuario_observador=null){
	global $config;
	
	$objResposta = new xajaxResponse();
	
	if (isset($config['contas']) && !$usuario_observador) {
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->adCampo('count(usuario_id)');
		$sql->adOnde('usuario_ativo=1');
		$sql->adOnde('usuario_observador!=1');
		$ativos = $sql->resultado();
		$sql->limpar();
	
		
		$permitido=$config['contas'];
		
		if ($ativos+($usuario_id ? 0 : 1) > $permitido) {
			$objResposta->assign("qnt_permitida","value", -1);
			return $objResposta;
			}
		else {
			$objResposta->assign("qnt_permitida","value", 0);
			return $objResposta;
			}
		}
	else if (isset($config['contas']) && $usuario_observador) {
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->adCampo('count(usuario_id)');
		$sql->adOnde('usuario_ativo=1');
		$sql->adOnde('usuario_observador=1');
		$observador = $sql->resultado();
		$sql->limpar();
		
		$permitido=($config['contas']*2);
		
		if ($observador+($usuario_id ? 0 : 1) > $permitido) {
			$objResposta->assign("qnt_permitida","value", -2);
			return $objResposta;
			}
		else {
			$objResposta->assign("qnt_permitida","value", 0);
			return $objResposta;
			}		
		}	
		
	$objResposta->assign("qnt_permitida","value", 0);
	return $objResposta;
}	
$xajax->registerFunction("qnt_permitida");	




function existe_identidade_ajax($identidade='', $usuario_id=0){
	if (!$identidade) {
		$objResposta = new xajaxResponse();
		$objResposta->assign("existe_identidade","value", 0);
		return $objResposta;
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->esqUnir('usuarios', 'usuarios', 'contato_id=usuario_contato');
		$sql->adCampo('count(contato_id)');
		$sql->adOnde('contato_identidade=\''.previnirXSS(utf8_decode($identidade)).'\'');
		if ($usuario_id) $sql->adOnde('usuario_id!='.(int)$usuario_id);
		$existe=$sql->Resultado();
		$sql->limpar();
		$objResposta = new xajaxResponse();
		$objResposta->assign("existe_identidade","value", (int)$existe);
		return $objResposta;
		}
	}	
$xajax->registerFunction("existe_identidade_ajax");	


function existe_login_ajax($login=''){
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->adCampo('count(usuario_id)');
	$sql->adOnde('usuario_login=\''.previnirXSS(utf8_decode($login)).'\''); 
	$existe=$sql->Resultado();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_login","value", (int)$existe);
	return $objResposta;
}	
$xajax->registerFunction("existe_login_ajax");	

function checar_secao($cia_id=null, $dept_id=null){
	
	if ($dept_id){
		$sql = new BDConsulta;
		$sql->adTabela('depts');
		$sql->adCampo('count(dept_id)');
		$sql->adOnde('dept_id='.(int)$dept_id);
		$sql->adOnde('dept_cia='.(int)$cia_id);
		$existe=$sql->Resultado();
		$sql->limpar();
		}
	
	if (!$dept_id) $nao_existe_dept=0;
	elseif ($existe>=1) $nao_existe_dept=0;
	else $nao_existe_dept=1;
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("nao_existe_dept","value", $nao_existe_dept);
	return $objResposta;
}		
$xajax->registerFunction("checar_secao");		
	


$xajax->processRequest();
?>
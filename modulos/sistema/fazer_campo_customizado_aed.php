<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
if (!$Aplic->usuario_super_admin)	$Aplic->redirecionar('m=publico&a=acesso_negado');
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$uuid=getParam($_REQUEST, 'uuid', null);
$campo_customizado_id=getParam($_REQUEST, 'campo_customizado_id', 0);
$nome_modulo=getParam($_REQUEST, 'nome_modulo', '');
$modulo=getParam($_REQUEST, 'modulo', null);
$campo_customizado_nome=getParam($_REQUEST, 'campo_customizado_nome', null);
$campo_customizado_descricao=getParam($_REQUEST, 'campo_customizado_descricao', null);
$campo_customizado_formula=getParam($_REQUEST, 'campo_customizado_formula', null);
$campo_customizado_tipo_html=getParam($_REQUEST, 'campo_customizado_tipo_html', null);
$campo_customizado_tipo_dado=getParam($_REQUEST, 'campo_customizado_tipo_dado', 'alpha');
$campo_customizado_publicado=getParam($_REQUEST, 'campo_customizado_publicado', 0);
$campo_customizado_ordem=getParam($_REQUEST, 'campo_customizado_ordem', 0);
$campo_customizado_tags_extras=getParam($_REQUEST, 'campo_customizado_tags_extras', null);

$campo_customizado_descendente=getParam($_REQUEST, 'campo_customizado_descendente', 0);
$campo_customizado_por_chave=getParam($_REQUEST, 'campo_customizado_por_chave', 0);

$lista_itensSelecionados=getParam($_REQUEST, 'selecionarItens', null);
$selecionarNovoItem=getParam($_REQUEST, 'selecionarNovoItem', null);
$selecionarNovoItemChave=getParam($_REQUEST, 'selecionarNovoItemChave', null);

if ($selecionarNovoItem != null) $lista_itensSelecionados[$selecionarNovoItemChave] = $selecionarNovoItem;

$campos_customizados = new CampoCustomizados(strtolower($modulo), null, null);

if (!$campo_customizado_id) $campos_customizados->adicionar(
	$uuid, 
	$campo_customizado_nome, 
	$campo_customizado_descricao, 
	$campo_customizado_formula, 
	$campo_customizado_tipo_html, 
	$campo_customizado_tipo_dado, 
	$campo_customizado_tags_extras, 
	$campo_customizado_ordem, 
	$campo_customizado_publicado, 
	$campo_customizado_descendente, 
	$campo_customizado_por_chave, 
	$msg);
else {
	$campos_customizados->atualizar(
	$campo_customizado_id, 
	$campo_customizado_nome, 
	$campo_customizado_descricao, 
	$campo_customizado_formula, 
	$campo_customizado_tipo_html, 
	$campo_customizado_tipo_dado, 
	$campo_customizado_tags_extras, 
	$campo_customizado_ordem, 
	$campo_customizado_publicado, 
	$campo_customizado_descendente, 
	$campo_customizado_por_chave, 
	$msg);
	}
if ($msg) $Aplic->setMsg('Erro ao adicionar campo customizado :'.$msg, UI_MSG_ALERTA, true);
else {
	if (!isset ($o_msg) ||(isset ($o_msg) && strlen($o_msg)<=1))$Aplic->setMsg('Campo customizado '.($campo_customizado_id ? 'editado' : 'adicionado').' com sucesso!', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=sistema&a='.($selecionarNovoItemChave ? 'campo_customizado_editar&campo_customizado_id='.$campo_customizado_id.'&modulo='.$modulo.'&nome_modulo='.$nome_modulo : 'campo_customizado'));
?>
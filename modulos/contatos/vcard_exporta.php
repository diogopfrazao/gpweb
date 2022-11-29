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

$contato_id = intval(getParam($_REQUEST, 'contato_id', ''));
$podeAcessar = $Aplic->checarModulo('contatos', 'acesso');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (isset($_REQUEST['contato_id']) && !(getParam($_REQUEST, 'contato_id', '') == '')) {
	$sql = new BDConsulta;
	$sql->adTabela('contatos');
	$sql->adUnir('cias', 'cp', 'cp.cia_id = contato_cia');
	$sql->adOnde('contato_id = '.(int)$contato_id);
	$contato = $sql->linha();
	$sql->limpar();
	
	require_once BASE_DIR.'/modulos/contatos/construir_vcard.class.php';
	$vcard = new construir_vcard();
	$vcard->setVersao('2.1');
	$vcard->setNomeFormatado($contato['contato_posto'].' '.$contato['contato_nomeguerra']);
	$vcard->setNome($contato['contato_nomeguerra'], $contato['contato_posto'], $contato['contato_tipo'], '', '');
	$vcard->setOrigem($config['nome_om'].' '.$config['gpweb'].': '.$config['dominio_site'].($Aplic->profissional ? '/server' : ''));
	if ($contato['contato_funcao']) $vcard->setTitulo($contato['contato_funcao']);
	if ($contato['contato_url']) {
		$vcard->setURL($contato['contato_url']);
		$vcard->adParametro('WORK', null);
		}
	if ($contato['contato_skype']) {
		$vcard->adSkype($contato['contato_skype']);
		$vcard->adParametro('WORK', null);
		}
	if ($contato['contato_nascimento']) $vcard->setAniversario($contato['contato_nascimento']);
	
	if ($contato['contato_notas']){
		$contato['contato_notas'] = str_replace("\r", ' ', $contato['contato_notas']);
		$contato['contato_notas'] = str_replace("\n", '=0D=0A=<br>', $contato['contato_notas']);
		if ($contato['contato_cpf']) $contato['contato_notas'] = 'CPF:'.$contato['contato_cpf'].('=0D=0A=<br>'.$contato['contato_notas']);
		if ($contato['contato_cnpj']) $contato['contato_notas'] = 'CNPJ:'.$contato['contato_cnpj'].'=0D=0A=<br>'.$contato['contato_notas'];
		}
	else {
		if ($contato['contato_cpf']) $contato['contato_notas'] = 'CPF:'.$contato['contato_cpf'];
		if ($contato['contato_cnpj']) $contato['contato_notas'] = 'CNPJ:'.$contato['contato_cnpj'].($contato['contato_cpf'] ? '=0D=0A=<br>'.$contato['contato_notas'] : '');
		}	
	if ($contato['contato_notas']) $vcard->setNota($contato['contato_notas']);
	$vcard->adParametro('ENCODING', 'QUOTED-PRINTABLE');
	$vcard->adOrganizacao($contato['cia_nome']);
	
	if ($contato['contato_dept']) $vcard->adDepartamento(nome_dept($contato['contato_dept']));
	
	$vcard->setIdExclusivo($contato['contato_cia']);
	
	if ($contato['contato_tel']) {
		$vcard->adTelefone($contato['contato_tel']);
		$vcard->adParametro('VOICE', null);
		$vcard->adParametro('WORK', null);
		}
	if ($contato['contato_tel2']) {	
		$vcard->adTelefone($contato['contato_tel2']);
		$vcard->adParametro('VOICE', null);
		$vcard->adParametro('HOME', null);
		}
	if ($contato['contato_cel']) {		
		$vcard->adTelefone($contato['contato_cel']);
		$vcard->adParametro('VOICE', null);
		$vcard->adParametro('CELL', null);
		$vcard->adParametro('WORK', null);
		}
	if ($contato['contato_email']) {	
		$vcard->adEmail($contato['contato_email']);
		$vcard->adParametro('PREF', null);
		$vcard->adParametro('INTERNET', null);
		}
	if ($contato['contato_email2']) {		
		$vcard->adEmail($contato['contato_email2']);
		$vcard->adParametro('INTERNET', null);
		}
	if ($contato['contato_endereco1']) $vcard->adEndereco('','', $config['nome_om']."=0D=0A=<br>".$contato['contato_endereco1']."=0D=0A=<br>".$contato['contato_endereco2'], $contato['contato_cidade'], $contato['contato_estado'], $contato['contato_cep'], $contato['contato_pais']);
	$vcard->adParametro('ENCODING', 'QUOTED-PRINTABLE');
	$vcard->adParametro('WORK', null);
	$vcard->adParametro('PREF', null);
	$texto = $vcard->fetch();
	header('Pragma: ');
	header('Cache-Control: ');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store, no-cache, must-revaldataInicio'); 
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('MIME-Version: 1.0');
	header('Content-Type: text/x-vcard');
	$saida=$contato['contato_posto'].' '.$contato['contato_nomeguerra'].'.vcf';
	$saida=str_replace(' ', '_', $saida);
	header('Content-Disposition: attachment; filename='.$saida);
	print_r($texto);
	} 
else {
	$Aplic->setMsg('Um manipulador inv�lido de contatos foi passado � fun��o', UI_MSG_ERRO);
	$Aplic->redirecionar('m=contatos');
	}
?>
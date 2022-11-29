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

$obj = new CContato();
$msg = '';
$nao_eh_novo=getParam($_REQUEST, 'contato_id', null);
$del=getParam($_REQUEST, 'del', 0);
$excluir_foto=getParam($_REQUEST, 'excluir_foto', 0);
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

$_REQUEST['contato_ativo']=(isset($_REQUEST['contato_ativo']) && $_REQUEST['contato_ativo'] ? 1 : 0);
$_REQUEST['contato_vivo']=(isset($_REQUEST['contato_vivo']) && $_REQUEST['contato_vivo'] ? 1 : 0);



if ($del && !$Aplic->checarModulo('contatos', 'excluir')) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($nao_eh_novo && !$Aplic->checarModulo('contatos', 'editar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$nao_eh_novo && !$Aplic->checarModulo('contatos', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$notificarSolicitado=getParam($_REQUEST, 'contato_atualizarSolicitado', 0);

if ($notificarSolicitado != 0) $notificarSolicitado = 1;

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=contatos&a=index');
	}
require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$del=getParam($_REQUEST, 'del', 0);
$Aplic->setMsg('Contatos');

if ($del) {
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=contatos&a=index');
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=contatos');
		}
	} 
else {
	if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else {
		$campos_customizados = new CampoCustomizados($m, $obj->contato_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$sql = $campos_customizados->armazenar($obj->contato_id);
		$chave_atual = $obj->getChaveAtualizada();
		if ($notificarSolicitado && !$chave_atual) {
			$agora = new CData(date("Y-m-d H:i:s"));
			$obj->contato_chave_atualizacao = MD5($agora->format('%Y%m%dT%H%M%S'));
			$obj->contato_pedido_atualizacao = $agora->format('%Y-%m-%d %H:%M:%S');
			$obj->contato_ultima_atualizacao = '';
			$obj->atualizarNotificar();
			} 
		elseif ($notificarSolicitado && $chave_atual) {
			//nada?
			} 
		else $obj->contato_chave_atualizacao = '';
		$Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
		}
	
	
	if ($excluir_foto){
		
		if ($obj->contato_foto) @unlink($base_dir.'/arquivos/contatos/'.$obj->contato_foto);
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->adAtualizar('contato_foto', null);
		$sql->adOnde('contato_id = '.(int)$obj->contato_id);
		$retorno=$sql->exec();
		$sql->limpar();
		}
	
	//acrescentar foto
	if (isset($_FILES['logo']['name']) && file_exists($_FILES['logo']['tmp_name']) && !empty($_FILES['logo']['tmp_name'])){
		
		if($_FILES['logo']['size']<153600){
			//apagar antigo
			if ($obj->contato_foto) @unlink($base_dir.'/arquivos/contatos/'.$obj->contato_foto);
			$caminho = $obj->contato_id.'/'.$_FILES['logo']['name'];
			$caminho_completo = $base_dir.'/arquivos/contatos/'.$caminho;
			if (!is_dir($base_dir.'/arquivos/contatos/'.$obj->contato_id)){
					$res = mkdir($base_dir.'/arquivos/contatos/'.$obj->contato_id, 0777);
					if (!$res) {
						$Aplic->setMsg('A pasta para '.$config['genero_contato'].' '.$config['contato'].' n�o foi configurada para receber arquivos - mude as permiss�es em arquivos\contatos.', UI_MSG_ALERTA);
						return false;
						}
					}
					
			move_uploaded_file($_FILES['logo']['tmp_name'], $caminho_completo);
			$sql = new BDConsulta;
			$sql->adTabela('contatos');
			$sql->adAtualizar('contato_foto', $caminho);
			$sql->adOnde('contato_id = '.(int)$obj->contato_id);
			$retorno=$sql->exec();
			$sql->limpar();
			}
		else ver2('O tamanho m�ximo permitido � de 150KB!  Enviou foto 3x4 de '.arquivo_tamanho((int)$_FILES['logo']['size']));
		}
	
	$Aplic->redirecionar('m=contatos&a=ver&contato_id='.$obj->contato_id);
	}
$Aplic->redirecionar('m=contatos');	
?>
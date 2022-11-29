<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
//Para o caso de ter dado problema na versão 16
$sql = new BDConsulta;
$sql->adTabela('config');
$sql->adCampo('config_nome');
$sql->adOnde('config_grupo=\'social\'');
$lista=$sql->listaVetorChave('config_nome', 'config_nome');
$sql->limpar();

if (!isset($lista['genero_beneficiario'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('genero_beneficiario','o','social','select');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}

if (!isset($lista['beneficiario'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('beneficiario','beneficiário','social','text');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}
	
if (!isset($lista['beneficiarios'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('beneficiarios','beneficiários','social','text');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}
	
if (!isset($lista['nis_obrigatorio'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('nis_obrigatorio','true','social','checkbox');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}
	
if (!isset($lista['cpf_obrigatorio'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES	('cpf_obrigatorio','true','social','checkbox');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}			

$sql = new BDConsulta;
$sql->adTabela('config_lista');
$sql->adCampo('DISTINCT config_nome');
$lista=$sql->listaVetorChave('config_nome', 'config_nome');
$sql->limpar();

if (!isset($lista['genero_beneficiario'])){
	$script="INSERT INTO config_lista (config_nome, config_lista_nome) VALUES ('genero_beneficiario','o'),('genero_beneficiario','a');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}

?>

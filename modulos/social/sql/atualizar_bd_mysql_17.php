<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
//Para o caso de ter dado problema na vers�o 16
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
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('beneficiario','benefici�rio','social','text');";
	$sql->executarScript($script);
	$sql->exec();
	$sql->limpar();
	}
	
if (!isset($lista['beneficiarios'])){
	$script="INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES ('beneficiarios','benefici�rios','social','text');";
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

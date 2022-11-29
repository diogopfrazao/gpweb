<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $config;


$sql = new BDConsulta;
$sql->adTabela('log_arquivo');
$sql->adCampo('log_arquivo.*');
$sql->adOnde('log_arquivo_id ='.(int)getParam($_REQUEST, 'log_arquivo_id', null));	
$arquivo=$sql->Linha();	
$sql->limpar();		


$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

if ($arquivo['log_arquivo_local']){
	
	$fnome = $base_dir.'/arquivos/'.$arquivo['log_arquivo_local'].$arquivo['log_arquivo_nome_real'];

	if (!file_exists($fnome)) {
		$Aplic->setMsg('Arquivo n�o foi encontrado.', UI_MSG_ERRO);
		exit();
		}
	header('MIME-Version: 1.0');
	header('Pragma: ');
	header('Cache-Control: public');
	if ($arquivo['log_arquivo_tamanho']) header('Content-length: '.$arquivo['log_arquivo_tamanho']);
	header('Content-type: '.$arquivo['log_arquivo_tipo']);
	header('Content-transfer-encoding: 8bit');
	header('Content-disposition: attachment; filename="'.$arquivo['log_arquivo_nome'].'"');
	$handle = fopen($base_dir.'/arquivos/'.$arquivo['log_arquivo_local'].$arquivo['log_arquivo_nome_real'], 'rb');
	if ($handle) {
		while (!feof($handle)) print fread($handle, 8192);
		fclose($handle);
		}
	flush();
	}
else {
	//arquivos de clientes antigos
	$caminho_completo = $base_dir.'/arquivos/log/'.$arquivo['log_arquivo_endereco'];
	if (file_exists ($caminho_completo) && !empty($arquivo['log_arquivo_nome'])){
	  $tamanho = filesize ($caminho_completo);
	  header("Content-Type: application/open");
		header("Content-Length: ".$tamanho);
		header("Content-Disposition: attachment; filename=".$arquivo['log_arquivo_nome']);
		header("Content-Transfer-Encoding: binary");
	  readfile($caminho_completo);
	  } 
	else $Aplic->setMsg('Arquivo n�o foi encontrado.', UI_MSG_ERRO);  
	}

?>



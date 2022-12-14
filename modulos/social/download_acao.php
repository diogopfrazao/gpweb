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
$sql->adTabela('social_acao_arquivo');
$sql->adCampo('social_acao_arquivo.*');
$sql->adOnde('social_acao_arquivo_id ='.(int)getParam($_REQUEST, 'social_acao_arquivo_id', null));	
$rs=$sql->Linha();	
$sql->limpar();		
global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');
$caminho_completo = $base_dir.'/arquivos/'.getParam($_REQUEST, 'pasta', null).'/'.$rs['social_acao_arquivo_endereco'];

if (file_exists ($caminho_completo) && !empty($rs['social_acao_arquivo_nome'])){
  $tamanho = filesize ($caminho_completo);
  header("Content-Type: application/open");
	header("Content-Length: ".$tamanho);
	header("Content-Disposition: attachment; filename=".$rs['social_acao_arquivo_nome']);
	header("Content-Transfer-Encoding: binary");
  readfile($caminho_completo);
  } 
else echo 'Arquivo n?o encontrado';
?>



<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
if (!($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'importa_notebook')) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
if (getParam($_REQUEST, 'importar', '')){
	require_once BASE_DIR.'/codigo/instalacao.inc.php';
	$arquivo=getParam($_REQUEST, 'arquivo', '');
	if (isset($_FILES['arquivo'])) {	
		$upload = $_FILES['arquivo'];
		if ($upload['size'] < 1)echo '<script>alert("Arquivo enviado tem tamanho zero. Processo abortado.")</script>';
		else {
			$extensao = substr($_FILES['arquivo']['name'], -3, 3);
			if ($extensao=='zip') $nome_importado=str_replace('.zip', '', $_FILES['arquivo']['name']);
			else {
				ver2('Estens�o do arquivo n�o � zip! Processo abortado.');
				exit();
				}
			move_uploaded_file($_FILES['arquivo']['tmp_name'], $base_dir.'/arquivos/temp/'.$_FILES['arquivo']['name']);
			$zip = new ZipArchive;
	    $zip->open($base_dir.'/arquivos/temp/'.$_FILES['arquivo']['name']);
	    $zip->extractTo($base_dir.'/arquivos/temp/');
	    $zip->close(); 
			@unlink($base_dir.'/arquivos/temp/'.$_FILES['arquivo']['name']);
			
			//fazer backup dos dados atuais
			include_once BASE_DIR.'/modulos/social/funcoes.php';
			$nome=exportar_social();
			
			instalacao_carregarSQL($base_dir.'/arquivos/temp/'.$nome_importado.'.sql');
			
			ver2('Dados carregados. O backup dos dados antes da importa��o encontra-se em '.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/temp/'.$nome.'.zip');
			}
		}
	}










echo '<form name="env" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="" value="importar" />';
echo '<input type="hidden" name="importar" value="0" />';


$botoesTitulo = new CBlocoTitulo('Instalar em Dispositivo Off-Line o Arquivo de Prepara��o', 'importar.jpg', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administra��o do Sistema','Voltar � tela de Administra��o do Sistema.');
$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo'<tr><td colspan=20 align=center&nbsp;</td></tr>';
echo '<tr><td align=center><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="60"></td><td>'.botao('importar', 'Importar', 'Clique neste bot�o para enviar o arquivo selecionado � esquerda para o servidor e importar os benefici�rios existentes no mesmo.','','env.importar.value=1; env.submit()').'</td></tr></table></td></tr>';
echo'<tr><td colspan=20 align=center&nbsp;</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';	


?>
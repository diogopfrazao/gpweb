<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
$contato_id=getParam($_REQUEST, 'contato_id', 0);

$incluir=getParam($_REQUEST, 'incluir', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);

$sql = new BDConsulta;

$sql->adTabela('contatos');
$sql->adCampo('contato_foto');
$sql->adOnde('contato_id = '.$contato_id);
$contato_foto=$sql->Resultado();
$sql->limpar();

if ($incluir && isset($_FILES['logo']['name']) && file_exists($_FILES['logo']['tmp_name']) && !empty($_FILES['logo']['tmp_name'])){
	
	if($_FILES['logo']['size']<153600){
	
		//apagar antigo
		if ($contato_foto) @unlink($base_dir.'/arquivos/contatos/'.$contato_foto);
		$caminho = $contato_id.'/'.$_FILES['logo']['name'];
		$caminho_completo = $base_dir.'/arquivos/contatos/'.$caminho;
		
		
		if (!is_dir($base_dir.'/arquivos/contatos/'.$contato_id)){
				$res = mkdir($base_dir.'/arquivos/contatos/'.$contato_id, 0777);
				if (!$res) {
					$Aplic->setMsg('A pasta para '.$config['genero_contato'].' '.$config['contato'].' n�o foi configurada para receber arquivos - mude as permiss�es em arquivos\contatos.', UI_MSG_ALERTA);
					return false;
					}
				}
		
		move_uploaded_file($_FILES['logo']['tmp_name'], $caminho_completo);
		$sql->adTabela('contatos');
		$sql->adAtualizar('contato_foto', $caminho);
		$sql->adOnde('contato_id = '.(int)$contato_id);
		$retorno=$sql->exec();
		$sql->limpar();
		}
	else ver2('O tamanho m�ximo permitido � de 150KB!  Enviou foto 3x4 de '.arquivo_tamanho((int)$_FILES['logo']['size']));
	}

if ($excluir){
	if ($contato_foto) @unlink($base_dir.'/arquivos/contatos/'.$contato_foto);
	$sql->adTabela('contatos');
	$sql->adAtualizar('contato_foto', null);
	$sql->adOnde('contato_id = '.(int)$contato_id);
	$retorno=$sql->exec();
	$sql->limpar();
	$contato_foto='';
	}


echo '<form method="POST" id="env" name="env" enctype="multipart/form-data">';
echo '<input type=hidden id="m" name="m" value="'.$m.'">';
echo '<input type=hidden id="a" name="a" value="'.$a.'">';
echo '<input type=hidden id="dialogo" name="dialogo" value="1">';
echo '<input type=hidden id="contato_id" name="contato_id" value="'.$contato_id.'">';	
echo '<input type=hidden id="incluir" name="incluir" value="">';	
echo '<input type=hidden id="excluir" name="excluir" value="">';	





$botoesTitulo = new CBlocoTitulo('Foto 3X4 d'.$config['genero_contato'].' '.ucfirst($config['contato']).'', 'contatos.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
echo estiloTopoCaixa();

echo '<table width="100%" align="center" class="std" cellspacing=0 cellpadding=0 >';

if ($contato_foto) echo '<tr><td colspan=20><table><tr><td><img src="'.$base_url.'/arquivos/contatos/'.$contato_foto.'" width=100 height=133 /></td></tr><tr><td align=center>'.botao('excluir','','','','env.excluir.value=1; env.submit()').'</td></tr></table></td></tr>';
echo '<tr><td colspan=20>Envie foto no formato 3X4 com tamanho m�ximo de 150KB</td></tr>';
echo '<tr><td align="left">'.dica('Foto','Clique no bot�o de escolher arquivo para selecionar uma foto no formato 3X4 com tamanho m�ximo de 150KB.').'Foto:'.dicaF().'<input type="File" class="arquivo" name="logo" size="59" /></td></tr>';


echo '<tr><td align=left>'.botao(($contato_foto ? 'atualizar' : 'enviar'), '','','','env.incluir.value=1; env.submit()').'</td><td align=right>'.botao('retornar', '','','',"url_passar(0,'m=contatos&a=ver&contato_id=".$contato_id."');").'</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';
?>
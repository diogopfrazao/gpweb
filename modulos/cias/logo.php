<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
require BASE_DIR.'/incluir/validar_autorizado.php';

global $Aplic, $config, $m, $a, $u;

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
$cia_id=getParam($_REQUEST, 'cia_id', 0);

$incluir=getParam($_REQUEST, 'incluir', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);

$sql = new BDConsulta;

$sql->adTabela('cias');
$sql->adCampo('cia_logo');
$sql->adOnde('cia_id = '.$cia_id);
$caminho=$sql->Resultado();
$sql->limpar();

$nome_arquivo_fisico = (isset($_FILES, $_FILES['logo'], $_FILES['logo']['tmp_name']) && !empty($_FILES['logo']['tmp_name']))
    ? $_FILES['logo']['tmp_name'] : null;

$nome_original = (isset($_FILES, $_FILES['logo'], $_FILES['logo']['name']) && !empty($_FILES['logo']['name']))
    ? $_FILES['logo']['name'] : '';

if ( $incluir && !empty($nome_arquivo_fisico) && file_exists( $nome_arquivo_fisico ) ){
    $pos = mb_strrpos($nome_original, '.');
    $extensao_arquivo = mb_strtolower( $pos !== false ? mb_substr( $nome_original, $pos ) : '');

    if( $extensao_arquivo !== '.jpg' && $extensao_arquivo !== '.png' && $extensao_arquivo !== '.gif' && $extensao_arquivo !== '.bmp'){
        $Aplic->setMsg('A extens�o do arquivo informado n�o � permitida, apenas arquivos .jpg, .png, .gif e .bmp s�o permitidos.', UI_MSG_ALERTA);
        return false;
    }

    $isImage = false;
    if(@is_array(getimagesize($nome_arquivo_fisico))){
        $isImage = true;
    }

    if(!$isImage){
        $Aplic->setMsg('O arquivo informado n�o � uma imagem v�lida.', UI_MSG_ALERTA);
        return false;
    }

    //apagar antigo
    if ($caminho) @unlink($base_dir.'/arquivos/organizacoes/'.$caminho);

    $nome_destino = uuid() . $extensao_arquivo;

	$caminho = $cia_id.'/'.$nome_destino;
	$caminho_completo = $base_dir.'/arquivos/organizacoes/'.$caminho;

	if (!is_dir($base_dir.'/arquivos/organizacoes/'.$cia_id)){
			$res = mkdir($base_dir.'/arquivos/organizacoes/'.$cia_id, 0777);
			if (!$res) {
				$Aplic->setMsg('A pasta para '.$config['genero_organizacao'].' '.$config['organizacao'].' n�o foi configurada para receber arquivos - mude as permiss�es em arquivos\organizacoes.', UI_MSG_ALERTA);
				return false;
				}
			}
	
	move_uploaded_file( $nome_arquivo_fisico, $caminho_completo);
	$sql->adTabela('cias');
	$sql->adAtualizar('cia_logo', $caminho);
	$sql->adOnde('cia_id = '.(int)$cia_id);
	$retorno=$sql->exec();
	$sql->limpar();
	}

if ($excluir){
	if ($caminho)@unlink($base_dir.'/arquivos/organizacoes/'.$caminho);
	$sql->adTabela('cias');
	$sql->adAtualizar('cia_logo', '');
	$sql->adOnde('cia_id = '.$cia_id);
	$retorno=$sql->exec();
	$sql->limpar();
	$caminho='';
	}




$sql->adTabela('cias');
$sql->adCampo('cia_logo');
$sql->adOnde('cia_id = '.$cia_id);
$caminho=$sql->Resultado();
$sql->limpar();




echo '<form method="POST" id="env" name="env" enctype="multipart/form-data">';
echo '<input type=hidden id="m" name="m" value="'.$m.'">';
echo '<input type=hidden id="a" name="a" value="'.$a.'">';
echo '<input type=hidden id="dialogo" name="dialogo" value="1">';
echo '<input type=hidden id="cia_id" name="cia_id" value="'.$cia_id.'">';	
echo '<input type=hidden id="incluir" name="incluir" value="">';	
echo '<input type=hidden id="excluir" name="excluir" value="">';	





$botoesTitulo = new CBlocoTitulo('Logotipo d'.$config['genero_organizacao'].' '.ucfirst($config['organizacao']).'', 'organizacao.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
echo estiloTopoCaixa();

echo '<table width="100%" align="center" class="std" cellspacing=0 cellpadding=0 >';

if ($caminho) echo '<tr><td colspan=20><table><tr><td><img src="'.$base_url.'/arquivos/organizacoes/'.$caminho.'" /></td></tr><tr><td align=center>'.botao('excluir','','','','env.excluir.value=1; env.submit()').'</td></tr></table></td></tr>';
echo '<tr><td align="left"><b>Imagem:</b><input type="File" class="arquivo" name="logo" size="59" /></td></tr>';
echo '<tr><td align=left>'.botao(($caminho ? 'atualizar' : 'enviar'), '','','','env.incluir.value=1; env.submit()').'</td><td align=right>'.botao('retornar', '','','',"url_passar(0,'m=cias&a=ver&cia_id=".$cia_id."');").'</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';
?>
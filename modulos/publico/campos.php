<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


$campo_formulario_tipo=getParam($_REQUEST, 'campo_formulario_tipo', 'atas');
$campo_formulario_tipo_extra=getParam($_REQUEST, 'campo_formulario_tipo_extra', null);

if(empty($campo_formulario_tipo_extra)){
    $campo_formulario_tipo_extra = $campo_formulario_tipo;
    }

$campo_formulario_tipo_extra .= '_ex';

$sql = new BDConsulta;


if (getParam($_REQUEST, 'salvar', null)){
	$campo=getParam($_REQUEST, 'campo', array());
	
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo, campo_formulario_descricao, campo_formulario_tipo, campo_formulario_customizado');
  //campos extras s�o do mesmo tipo porem com sufixo 'ex'
	$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$lista = $sql->lista();
	$sql->limpar();
	
	$sql->setExcluir('campo_formulario');
  //campos extras s�o do mesmo tipo porem com sufixo 'ex'
  $sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
	$sql->adOnde('campo_formulario_usuario ='.(int)$Aplic->usuario_id);
	$sql->exec();
	$sql->limpar();	

	
	foreach($lista as $linha) {
		$sql->adTabela('campo_formulario');
		$sql->adInserir('campo_formulario_ativo', (isset($campo[$linha['campo_formulario_campo']]) ? '1' : '0'));
		$sql->adInserir('campo_formulario_campo', $linha['campo_formulario_campo']);
		$sql->adInserir('campo_formulario_tipo', $linha['campo_formulario_tipo']);
    $sql->adInserir('campo_formulario_descricao', $linha['campo_formulario_descricao']);
    if(isset($linha['campo_formulario_customizado'])) $sql->adInserir('campo_formulario_customizado', $linha['campo_formulario_customizado']);
		$sql->adInserir('campo_formulario_usuario', $Aplic->usuario_id);
		$sql->exec();
		$sql->limpar();
		}

	echo '<script type="text/javascript">parent.gpwebApp._popupCallback();</script>';	
	}

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_descricao');
//campos extras s�o do mesmo tipo porem com sufixo 'ex'
$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$sql->adOrdem('campo_formulario_descricao');
$legenda = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_descricao');
$sql->limpar();


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
//campos extras s�o do mesmo tipo porem com sufixo 'ex'
$sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
$sql->adOrdem('campo_formulario_descricao');
$lista = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if (!count($lista)){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	//campos extras s�o do mesmo tipo porem com sufixo 'ex'
  $sql->adOnde('campo_formulario_tipo = \''.$campo_formulario_tipo.'\' OR campo_formulario_tipo = \''.$campo_formulario_tipo_extra.'\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$sql->adOrdem('campo_formulario_descricao');
	$lista = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();
	}	



foreach($legenda as $chave => $valor){
	$valor=str_replace('Se��o', ucfirst($config['departamento']), $valor);
	$valor=str_replace('Se��es', ucfirst($config['departamentos']), $valor);
	
	$valor=str_replace('Organiza��o', ucfirst($config['organizacao']), $valor);
	$valor=str_replace('Organiza��es', ucfirst($config['organizacoes']), $valor);
	
	$legenda[$chave]=$valor;
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input name="a" type="hidden" value="'.$a.'" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="salvar" type="hidden" value="" />';
echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width="100%" align="center">';
foreach($legenda as $chave => $legenda) echo '<tr><td width=16 ><input class="texto" type="checkbox" name="campo['.$chave.']" value="1" '.(isset($lista[$chave]) && $lista[$chave] ? 'checked="checked"': '').'  /></td><td>'.(isset($config[$chave]) ? ucfirst($config[$chave]) : (isset($config[strtolower($legenda)]) ? ucfirst($config[strtolower($legenda)]) : ucfirst($legenda))).'</td></tr>';
echo '<tr><td colspan=2><table><tr><td>'.botao('salvar', '', '','','env.salvar.value=1; env.submit();').'</td></tr></table></td></tr>';
echo '</table></form>';
echo estiloFundoCaixa();
?>
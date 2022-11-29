<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
$Aplic->carregarCKEditorJS();

$plano_acao_item_id=getParam($_REQUEST, 'plano_acao_item_id', null);
$plano_acao_item_observacao=getParam($_REQUEST, 'plano_acao_item_observacao', null);

$sql = new BDConsulta;

$sql->adTabela('plano_acao_item');
$sql->adCampo('plano_acao_item_observacao');
$sql->adOnde('plano_acao_item_id='.(int)$plano_acao_item_id);
$obs=$sql->Resultado();
$sql->limpar();


if (getParam($_REQUEST, 'salvar', null)){
	
	$sql->adTabela('plano_acao_item');
	$sql->adAtualizar('plano_acao_item_observacao', $plano_acao_item_observacao);
	$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
	$sql->exec();
	$sql->limpar();
	
	echo '<script type="text/javascript">parent.gpwebApp._popupCallback('.$plano_acao_item_id.');</script>';
	}
	
echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" id="plano_acao_item_id" name="plano_acao_item_id" value="'.$plano_acao_item_id.'" />';
echo '<input type="hidden" id="salvar" name="salvar" value="1" />';
echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 class="std" width=100%>';
echo '<tr><td align="right" width="80">'.dica('Observa��o', 'Observa��o do item do plano de a��o.').'Observa��o:'.dicaF().'</td><td style="width:750px;"><textarea name="plano_acao_item_observacao" id="plano_acao_item_observacao" style="width:720px;" class="textarea" data-gpweb-cmp="ckeditor">'.$obs.'</textarea></td></tr>';
echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','salvar_obs();').'</td></tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();

echo '</form>';
?>
<script type="text/javascript">

function salvar_obs(){	
	env.submit();
	}

</script>
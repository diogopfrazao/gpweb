<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
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
echo '<tr><td align="right" width="80">'.dica('Observação', 'Observação do item do plano de ação.').'Observação:'.dicaF().'</td><td style="width:750px;"><textarea name="plano_acao_item_observacao" id="plano_acao_item_observacao" style="width:720px;" class="textarea" data-gpweb-cmp="ckeditor">'.$obs.'</textarea></td></tr>';
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
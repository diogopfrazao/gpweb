<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
$botoesTitulo = new CBlocoTitulo('Modelos de documentos vs '.ucfirst($config['organizacao']), 'email1.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administra��o do Sistema','Voltar � tela de Administra��o do Sistema.');
$botoesTitulo->mostrar();

$Aplic->carregarCKEditorJS();

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);
if (!$dialogo) $Aplic->salvarPosicao();


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%" class="std">';


echo '<tr><td align="right" width=150>'.dica(ucfirst($config['organizacao']), 'De qual '.$config['organizacao'].' ser� verificado os documentos permitidos.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:284px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

$sql = new BDConsulta;
$sql->adTabela('modelos_tipo');
$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, descricao');
$sql->adOnde('organizacao='.(int)$config['militar']);
$tipos=$sql->lista();
$sql->limpar();

$sql->adTabela('modelo_cia');
$sql->adCampo('modelo_cia_tipo');
$sql->adOnde('modelo_cia_cia='.(int)$cia_id);
$selecionados=$sql->carregarColuna();
$sql->limpar();
echo '<tr><td colspan=20><div id="combo_tabela"><table cellspacing=0 cellpadding=0 class="tbl1">';
echo '<tr><th></th><th>Nome</th><th>Descri��o</th></tr>';
foreach($tipos as $linha){
	echo '<tr><td><input type="checkbox" value="1" name="priorizacao_'.$linha['modelo_tipo_id'].'" id="priorizacao_'.$linha['modelo_tipo_id'].'" '.(in_array($linha['modelo_tipo_id'], $selecionados) ? 'checked="checked"' : '').' onchange="mudar('.$linha['modelo_tipo_id'].')" /></td><td align=left>'.$linha['modelo_tipo_nome'].'</td><td align=left>'.$linha['descricao'].'</td></tr>';
	}
echo '</table></div></td></tr>';	

echo '</table>';	
echo estiloFundoCaixa();
?>



<script type="text/javascript">

function mudar(modelo_tipo_id){
	xajax_mudar(document.getElementById('cia_id').value, modelo_tipo_id, document.getElementById('priorizacao_'+modelo_tipo_id).checked);
	}

function mudar_om(){
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:284px;" onchange="javascript:mudar_om();"');
	}


</script>
<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if ($editarPG) $Aplic->carregarCKEditorJS();

echo '<input type="hidden" id="pg_swot_swot" name="pg_swot_swot" value="" />';
echo '<input type="hidden" id="pg_swot_id" name="pg_swot_id" value="" />';
echo '<input type="hidden" id="apoio1" name="apoio1" value="" />';

echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Ambiente Externo - Lista de Amea�as</h1></td></tr>';

//ponto_fortes
$swot_ativo=($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null));

if ($editarPG){
	if ($swot_ativo) {
		echo '<tr><td align="right" width="10" style="white-space: nowrap">'.dica('Amea�a', 'Selecione uma amea�a.').'Amea�a:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" id="nome_swot" name="nome_swot" value="" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT2();">'.imagem('icones/swot_p.png','Selecionar','Clique neste �cone '.imagem('icones/swot_p.png').' para selecionar uma for�a.').'</a></td></tr></table></td></tr>';
		echo '<input type="hidden" id="pg_swot_nome" name="pg_swot_nome" value="" />';
		}
	else {
		echo '<tr><td width="810"><textarea data-gpweb-cmp="ckeditor" rows="3" name="pg_swot_nome" id="pg_swot_nome" ></textarea></td>';
		echo '<td id="adicionar_swot" style="display:"><a href="javascript: void(0);" onclick="inserir_swot();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste �cone '.imagem('icones/adicionar.png').' para incluir.').'</a></td>';
		echo '<td id="confirmar_swot" style="display:none"><a href="javascript: void(0);" onclick="cancelar_edicao_swot();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar.').'</a><a href="javascript: void(0);" onclick="inserir_swot();">'.imagem('icones/ok_g.png','Confirmar','Clique neste �cone '.imagem('icones/ok.png').' para confirmar.').'</a></td></tr>';
		}
	}


echo '<tr><td colspan=20><div id="combo_swots">';


$sql->adTabela('pg_swot');
$sql->esqUnir('swot', 'swot', 'pg_swot_swot=swot_id');
$sql->adCampo('pg_swot_id, pg_swot_nome, swot_nome, pg_swot_ordem');
$sql->adOnde('pg_swot_pg='.(int)$pg_id);
$sql->adOnde('pg_swot_tipo=\'t\'');
$sql->adOrdem('pg_swot_ordem ASC');
$swots=$sql->Lista();
$sql->limpar();

if (count($swots)) echo '<table class="tbl1" cellspacing=0 cellpadding=0><tr>'.($editarPG ? '<th></th>' : '').'<th style="white-space: nowrap">Amea�a</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($swots as $swot) {
	echo'<tr>';
	if ($editarPG){
		echo' <td td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_swot('.$swot['pg_swot_ordem'].', '.$swot['pg_swot_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td>'.($swot['swot_nome'] ? $swot['swot_nome'] : $swot['pg_swot_nome']).'</td>';
	if ($editarPG){
		echo ($swot_ativo ?'<td width="16" align="center">' : '<td width="32" align="center"><a href="javascript: void(0);" onclick="editar_swot('.$swot['pg_swot_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar.').'</a>');
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_swot('.$swot['pg_swot_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
		}
	echo '</tr>';
	}
if (count($swots)) echo '</table>';

echo '</div></td></tr>';




echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'ponto_forte_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('pr�ximo', 'Pr�ximo', 'Ir para a pr�xima tela.','','carregar(\'oportunidade_melhoria_geral\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';

?>
<script type="text/javascript">

var swot_ativo=<?php echo ($swot_ativo ? 1 : 0)?>;

function popSWOT2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Amea�a', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setSWOT&swot_tipo=t&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT2, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setSWOT2&swot_tipo=t&tabela=swot&cia_id='+document.getElementById('cia_id').value, 'Amea�a','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setSWOT2(chave, valor){
	document.getElementById('pg_swot_swot').value=chave;
	if (chave > 0) inserir_swot();	
	}
	
	
	
	

	
	
function inserir_swot(){
	if (!swot_ativo && !CKEDITOR.instances['pg_swot_nome'].getData()){
		alert('Escreva um texto');
		document.getElementById('pg_swot_nome').focus();
		return;
		}

	xajax_inserir_swot(
		document.getElementById('pg_swot_id').value,
		document.getElementById('pg_id').value,
		document.getElementById('pg_swot_swot').value,
		(!swot_ativo ? CKEDITOR.instances['pg_swot_nome'].getData() : null),
		't'
		);
	__buildTooltip();	
	cancelar_edicao_swot();
	}

function mudar_ordem_swot(ordem, pg_swot_id, direcao){
	xajax_mudar_ordem_swot(ordem, pg_swot_id, direcao, document.getElementById('pg_id').value, 't');
	__buildTooltip();
	}

function excluir_swot(pg_swot_id){
	xajax_excluir_swot(pg_swot_id, document.getElementById('pg_id').value, 't');
	__buildTooltip();
	}

function editar_swot(pg_swot_id){
	xajax_editar_swot(pg_swot_id);

	CKEDITOR.instances['pg_swot_nome'].setData(document.getElementById('apoio1').value);
	document.getElementById('adicionar_swot').style.display="none";
	document.getElementById('confirmar_swot').style.display='';
	}

function cancelar_edicao_swot(){
	document.getElementById('pg_swot_id').value=null;
	document.getElementById('pg_swot_swot').value=null;
	if (!swot_ativo) {
		CKEDITOR.instances['pg_swot_nome'].setData('');
		document.getElementById('confirmar_swot').style.display='none';
		document.getElementById('adicionar_swot').style.display='';
		}
	}	
	
	

</script>
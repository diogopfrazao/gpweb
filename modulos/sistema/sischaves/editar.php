<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
$sisvalor_titulo=getParam($_REQUEST, 'sisvalor_titulo', null);


$botoesTitulo = new CBlocoTitulo('Edi��o do Campo '.$sisvalor_titulo, 'opcoes.png', $m, $m.'.'.$u.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&u=sischaves&a=index', 'retornar','','Retornar','Voltar � tela anterior.');
$botoesTitulo->mostrar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="a" value="editar" />';
echo '<input type="hidden" name="u" value="sischaves" />';
echo '<input type="hidden" name="sisvalor_titulo" id="sisvalor_titulo" value="'.$sisvalor_titulo.'" />';


echo estiloTopoCaixa();
echo '<table cellpadding=1 cellspacing=0 width="100%" class="std">';
//campos


echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
echo '<tr><td><table cellspacing=0 cellpadding=0>';
echo '<input type="hidden" id="sisvalor_id" name="sisvalor_id" value="" />';
echo '<tr><td align=right>'.dica('Texto', 'Texto apresentado ao usu�rio.').'Texto:'.dicaF().'</td><td><input type="text" id="sisvalor_valor" name="sisvalor_valor" value="" style="width:200px;" class="texto" /></td></tr>';
echo '<tr><td align=right>'.dica('Chave', 'Chave interna que � utilizada ao selecionar o texto da op��o.').'Chave:'.dicaF().'</td><td><input type="text" name="sisvalor_valor_id" id="sisvalor_valor_id" style="width:200px;" class="texto"></td></tr>';
echo '<tr><td align=right>'.dica('Chave do Pai', 'Chave interna do campo pai que � utilizada como filtro.').'Chave do Pai:'.dicaF().'</td><td><input type="text" name="sisvalor_chave_id_pai" id="sisvalor_chave_id_pai" style="width:200px;" class="texto"></td></tr>';
echo '</table></td>';
echo '<td id="adicionar_campo" style="display:"><a href="javascript: void(0);" onclick="incluir_campo();">'.imagem('icones/adicionar_g.png','Incluir Integrante','Clique neste �cone '.imagem('icones/adicionar.png').' para incluir um contato como campo n'.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
echo '<td id="confirmar_campo" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'sisvalor_id\').value=0; document.getElementById(\'sisvalor_valor\').value=\'\'; document.getElementById(\'sisvalor_valor_id\').value=\'\'; document.getElementById(\'sisvalor_chave_id_pai\').value=\'\'; document.getElementById(\'adicionar_campo\').style.display=\'\';	document.getElementById(\'confirmar_campo\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar a edi��o do contato como campo n'.$config['genero_projeto'].' '.$config['projeto'].'.').'</a><a href="javascript: void(0);" onclick="incluir_campo();">'.imagem('icones/ok_g.png','Confirmar','Clique neste �cone '.imagem('icones/ok.png').' para confirmar a edi��o do contato como campo n'.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
echo '</tr></table></td></tr>';



$sql = new BDConsulta;
$sql->adTabela('sisvalores');
$sql->adOnde('sisvalor_titulo = \''.$sisvalor_titulo.'\'');
$sql->adCampo('sisvalores.*');
$sql->adOrdem('sisvalor_id');
$campos=$sql->Lista();
$sql->limpar();


echo '<tr><td colspan=20 align=left><div id="campos">';
if (count($campos)) {
	echo '<table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th>'.dica('Texto', 'Texto apresentado ao usu�rio.').'Texto'.dicaF().'</th><th>'.dica('Chave', 'Chave interna que � utilizada ao selecionar o texto da op��o.').'Chave'.dicaF().'</th><th>'.dica('Chave do Pai', 'Chave interna do campo pai que � utilizada como filtro.').'Chave do Pai'.dicaF().'</th><th></th></tr>';
	foreach ($campos as $campo) {
		echo '<tr align="center">';
		echo '<td align="left">'.$campo['sisvalor_valor'].'</td>';
		echo '<td align="left">'.$campo['sisvalor_valor_id'].'</td>';
		echo '<td align="left">'.$campo['sisvalor_chave_id_pai'].'</td>';
		echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_campo('.$campo['sisvalor_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o campo.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este campo?\')) {excluir_campo('.$campo['sisvalor_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir o campo.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}

echo '</div></td></tr>';



echo '</table>';
echo estiloFundoCaixa();
echo '</form>';
?>

<script type="text/javascript">

//campos

function editar_campo(sisvalor_id){
	xajax_editar_campo(sisvalor_id);
	document.getElementById('adicionar_campo').style.display="none";
	document.getElementById('confirmar_campo').style.display="";
	}

function incluir_campo(){
	xajax_incluir_campo_ajax(
		document.getElementById('sisvalor_titulo').value,
		document.getElementById('sisvalor_id').value,
		document.getElementById('sisvalor_valor').value,
		document.getElementById('sisvalor_valor_id').value,
		document.getElementById('sisvalor_chave_id_pai').value
		);

	document.getElementById('sisvalor_id').value=null;
	document.getElementById('sisvalor_valor').value='';
	document.getElementById('sisvalor_valor_id').value='';
	document.getElementById('sisvalor_chave_id_pai').value='';
	document.getElementById('adicionar_campo').style.display='';
	document.getElementById('confirmar_campo').style.display='none';
	__buildTooltip();
	}

function excluir_campo(sisvalor_id){
	xajax_excluir_campo_ajax(sisvalor_id, document.getElementById('sisvalor_titulo').value);
	__buildTooltip();
	}

</script>
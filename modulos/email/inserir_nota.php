<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
$sql = new BDConsulta;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$Aplic->carregarCKEditorJS();


$pasta=getParam($_REQUEST, 'pasta', null);
$ordem=getParam($_REQUEST, 'ordem', 0);
$pesquisa=getParam($_REQUEST, 'pesquisa', '');
$status=getParam($_REQUEST, 'status', 1);
$usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', null);
$cot_fim=getParam($_REQUEST, 'cot_fim', 0);
$alterar=getParam($_REQUEST, 'alterar', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);
$cor=getParam($_REQUEST, 'cor', 0);
$texto=getParam($_REQUEST, 'texto', '');
$status=getParam($_REQUEST, 'status', 0);

if ($alterar){
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('nota', $texto);
	$sql->adAtualizar('cor', $cor);
	$sql->adOnde('msg_usuario_id='.$msg_usuario_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela msg_usuario!'.$bd->stderr(true));
	$sql->limpar();
	}

if ($excluir){
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('nota', '');
	$sql->adAtualizar('cor', '');
	$sql->adOnde('msg_usuario_id='.$msg_usuario_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela msg_usuario!'.$bd->stderr(true));
	$sql->limpar();
	}

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="inserir_nota">';
echo '<input type=hidden name="m" id="m" value="email">';
echo '<input type=hidden name="cot_fim" id="cot_fim" value="'.$cot_fim.'">';
echo '<input type=hidden name="pasta" id="pasta" value="'.$pasta.'">';
echo '<input type=hidden name="ordem" id="ordem" value="'.$ordem.'">';
echo '<input type=hidden name="pesquisa" id="pesquisa" value="'.$pesquisa.'">';
echo '<input type=hidden name="status" id="status" value="'.$status.'">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="'.$usuario_id.'">';
echo '<input type=hidden name="msg_usuario_id" id="msg_usuario_id" value="'.$msg_usuario_id.'">';
echo '<input type=hidden name="cor" id="cor" value="'.$cor.'">';
echo '<input type=hidden id="status" name="status" value="'.$status.'">';
echo '<input type=hidden name="alterar" id="alterar" value="">';
echo '<input type=hidden name="excluir" id="excluir" value="">';

if ($excluir || $alterar) echo '<script>env.a.value="lista_msg"; env.submit();</script>';

$sql->adTabela('msg_usuario');
$sql->adCampo('cor, nota, msg_id');
$sql->adOnde('msg_usuario_id='.$msg_usuario_id);
$linha = $sql->Linha();
$sql->limpar();


$botoesTitulo = new CBlocoTitulo('Anota��o em '.ucfirst($config['mensagens']), 'email1.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();


echo estiloTopoCaixa();
echo '<table width=100% class="std" align="center" cellspacing=0 cellpadding=0 >';
echo '<tr><td align="center" colspan=2><h1>'.ucfirst($config['mensagem']).' N� '.$linha['msg_id'].'<h1></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap" width=50>'.dica('Anota��o','Texto da anota��o.').'Anota��o:'.dicaF().'</td><td bgcolor="ffffff"><textarea data-gpweb-cmp="ckeditor" rows="10" id="texto" name="texto" style="width:767px;">'.$linha['nota'].'</textarea></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milh�es poss�veis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inser��o do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="cor" value="'.($linha['cor'] ? $linha['cor'] : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';

echo '<tr><td align="center" colspan=2><table width=100%><tr>';

if (strlen($linha['nota'])) echo '<td width=98%>'.dica('Alterar','Clique neste bot�o para alterar uma nota relativa a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<a class="botao" href="javascript:void(0);" onclick="inserir_alterar();"><span><b>alterar</b></span></a>'.dicaF().'</td>';
else echo '<td width=98%>'.dica('Inserir','Clique neste bot�o para inserir uma nota relativa a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<a class="botao" href="javascript:void(0);" onclick="inserir_alterar();"><span><b>inserir</b></span></a>'.dicaF().'</td>';

if (strlen($linha['nota'])) echo '<td>'.dica("Excluir","Clique neste bot�o para excluir esta anota��o.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.excluir.value=1; env.submit();"><span><b>excluir</b></span></a>'.dicaF().'</td>';

echo '<td align=right>'.dica("Cancelar","Clique neste bot�o para cancelar esta opera��o.").'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\'lista_msg\'; env.submit();"><span><b>cancelar</b></span></a>'.dicaF().'</td>';


echo '</tr></table>';

echo '</table></form>';
echo estiloFundoCaixa();

?>

<script LANGUAGE="javascript">
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.cor.value;
	}		
	
function tem_conteudo(){
	var editorcontent = CKEDITOR.instances['texto'].getData().replace(/<[^>]*>/gi, '');
  return (editorcontent.length > 0);
	}
function inserir_alterar(){
	if (tem_conteudo()){
		env.alterar.value=1;
		env.submit();
		}
	else alert ("Digite um texto para este modelo!");
	}
</script>

<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $m, $a, $u;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('tab', getParam($_REQUEST, 'tab', 0), $m, $a, $u);
$tab = $Aplic->getEstado('tab', 0, $m, $a, $u);

if (isset($_REQUEST['despachosmodelotextobusca'])) $Aplic->setEstado('despachosmodelotextobusca', getParam($_REQUEST, 'despachosmodelotextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('despachosmodelotextobusca') ? $Aplic->getEstado('despachosmodelotextobusca') : '');

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

$onde=getParam($_REQUEST, 'onde', '');


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';


$botoesTitulo = new CBlocoTitulo('Despachos de Documentos', 'despacho.gif');

$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
$saida.=dica('Filtros e A��es','Clique nesta barra para esconder/mostrar os filtros e as a��es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/despacho_p.gif').'&nbsp;Filtros e A��es</a></div>'.dicaF();
$saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
$saida.='<table cellspacing=0 cellpadding=0>';
$vazio='<tr><td colspan=2>&nbsp;</td></tr>';


$procurar_usuario='<tr><td align=right style="white-space: nowrap">'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de sele��o � direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descri��o').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="despachosmodelotextobusca" onChange="document.env.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&despachosmodelotextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
		
$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_usuario.$procuraBuffer.'</table></td></tr></table>';
$saida.= '</div></div>';
$botoesTitulo->adicionaCelula($saida);
$botoesTitulo->mostrar();



$caixaTab = new CTabBox('m=email&a=lista_despacho_modelo', BASE_DIR.'/modulos/email/', $tab);
$caixaTab->adicionar('despachos_enviados_modelo', 'Recebidos sem resposta',null,null,'Recebidos Sem Resposta','Clique nesta aba para visualizar os despachos de documentos recebidos que anda n�o foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Enviados sem resposta',null,null,'Enviados Sem Resposta','Clique nesta aba para visualizar os despachos enviados de documentos que anda n�o foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Recebidos j� respondidos',null,null,'Recebidos J� Respondidos','Clique nesta aba para visualizar os despachos recebidos de documentos que j� foram respondidos.');
$caixaTab->adicionar('despachos_enviados_modelo', 'Enviados j� respondidos',null,null,'Enviados J� Respondidos','Clique nesta aba para visualizar os despachos enviados de documentos que j� foram respondidos.');
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
echo '</form>';
?>

<script type="text/javascript">
function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons�vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=(usuario_id ? usuario_id : 0);
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	env.submit();
	}
</script>	
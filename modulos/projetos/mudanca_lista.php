<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $dialogo;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('ListaMudancaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('ListaMudancaTab') !== null ? $Aplic->getEstado('ListaMudancaTab') : 0);

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('projeto_id', getParam($_REQUEST, 'projeto_id', null));
$projeto_id = ($Aplic->getEstado('projeto_id') !== null ? $Aplic->getEstado('projeto_id') : 0);

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);
$editar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
$acessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


if (!$dialogo){
	$botoesTitulo = new CBlocoTitulo('Solicita��o de Mudan�as ', 'anexo_projeto.png', $m, $m.'.'.$a);
	$novo=($podeAdicionar && $editar ?'<tr><td><td style="white-space: nowrap">'.dica('Nova Solicita��o de Mudan�a', 'Criar um nova solicita��o de mudan�as.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=mudanca_editar&projeto_id='.$projeto_id.'\');" >'.imagem('anexo_projeto_novo.png').'</a>'.dicaF().'</td></td></tr>' : '');
	$retornar_projeto='<tr><td><td style="white-space: nowrap">'.dica(ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');" >'.imagem('projeto_p.gif').'</a>'.dicaF().'</td></td></tr>';
	$botoesTitulo->adicionaCelula('<table>'.$novo.$retornar_projeto.'</table>');
	$botoesTitulo->mostrar();
	}

$caixaTab = new CTabBox('m=projetos&a=mudanca_lista', BASE_DIR.'/modulos/projetos/', $tab);
$caixaTab->adicionar('mudanca_tabela', 'Todas',null,null,'Todas','Lista de todas solicita��es de mudan�as.');
$caixaTab->adicionar('mudanca_tabela', 'Aprovadas pelo Requisitante',null,null,'Aprovadas pelo Requisitante','Lista de solicita��es de mudan�as aprovadas pelo requisitante.');
$caixaTab->adicionar('mudanca_tabela', 'Reprovadas pelo Requisitante',null,null,'Reprovadas pelo Requisitante','Lista de solicita��es de mudan�as reprovadas pelo requisitante.');
$caixaTab->adicionar('mudanca_tabela', 'Aprovadas pela Alta Administra��o',null,null,'Aprovadas pela Alta Administra��o','Lista de solicita��es de mudan�as aprovadas pela alta administra��o.');
$caixaTab->adicionar('mudanca_tabela', 'Reprovadas pela Alta Administra��o',null,null,'Reprovadas pela Alta Administra��o','Lista de solicita��es de mudan�as reprovadas pela alta aAdministra��o.');
$caixaTab->mostrar('','','','',true);



if (!$dialogo) echo estiloFundoCaixa('','', $tab);
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';	

?>
<script type="text/javascript">

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
</script>
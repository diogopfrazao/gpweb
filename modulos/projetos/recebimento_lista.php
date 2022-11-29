<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $dialogo;



if (isset($_REQUEST['tab'])) $Aplic->setEstado('RecebimentoListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('RecebimentoListaTab') !== null ? $Aplic->getEstado('RecebimentoListaTab') : 0);


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
	$botoesTitulo = new CBlocoTitulo('Recebimento de Produtos/Serviços', 'anexo_projeto.png', $m, $m.'.'.$a);
	$novo=($podeAdicionar && $editar ?'<tr><td><td style="white-space: nowrap">'.dica('Novo Recebimento', 'Criar um novo recebimento de produtos/serviços.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=recebimento_editar&projeto_id='.$projeto_id.'\');" >'.imagem('anexo_projeto_novo.png').'</a>'.dicaF().'</td></td></tr>' : '');
	$retornar_projeto='<tr><td><td style="white-space: nowrap">'.dica(ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.').'<a href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');" >'.imagem('projeto_p.gif').'</a>'.dicaF().'</td></td></tr>';
	$botoesTitulo->adicionaCelula('<table>'.$novo.$retornar_projeto.'</table>');
	$botoesTitulo->mostrar();
	}

$caixaTab = new CTabBox('m=projetos&a=recebimento_lista', BASE_DIR.'/modulos/projetos/', $tab);
$caixaTab->adicionar('recebimento_tabela', 'Todos',null,null,'Todos','Lista de recebimentos de produtos/serviços.');
$caixaTab->adicionar('recebimento_tabela', 'Recebimentos Provisórios',null,null,'Recebimentos Provisórios','Lista de recebimentos provisórios de produtos/serviços .');
$caixaTab->adicionar('recebimento_tabela', 'Recebimentos Definitivos',null,null,'Recebimentos Definitivos','Lista de recebimentos definitivos de produtos/serviços.');
$caixaTab->mostrar('','','','',true);



if (!$dialogo) echo estiloFundoCaixa('','', $tab);
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';	

?>
<script type="text/javascript">

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
</script>
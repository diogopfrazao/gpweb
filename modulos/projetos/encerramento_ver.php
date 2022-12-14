<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/encerramento.class.php');
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);
$acessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
$editar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);

$podeEditarTudo=($objProjeto->projeto_acesso>=5 ? $editar && (in_array($objProjeto->projeto_responsavel, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);

if (!$projeto_id) {
	$Aplic->setMsg('N?o foi passado um ID de '.$config['projeto'].' ao tentar ver o encerramento.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


$obj = new CEncerramento();
$obj->load($projeto_id);
$sql = new BDConsulta();


if (!$dialogo) $Aplic->salvarPosicao();


$msg = '';
if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Termo de Encerramento d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de op??es de visualiza??o').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste bot?o para visualizar dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$projeto_id."\");");
	
	if ($podeAdicionar && $editar)	$km->Add("root","inserir",dica('Inserir','Menu de op??es').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar && $editar)	$km->Add("inserir","inserir_objeto",dica('Inserir Termo de Encerramento','Inserir os detalhes do termo de encerramento.').'Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=encerramento_editar&projeto_id=".$projeto_id."\");");
	
	
	if ($obj->projeto_encerramento_responsavel){
		$km->Add("root","acao",dica('A??o','Menu de a??es.').'A??o'.dicaF(), "javascript: void(0);'");
		if ($podeEditarTudo && $podeEditar)  $km->Add("acao","editar_encerramento",dica('Editar Termo de Encerramento','Editar os detalhes do termo de encerramento.').'Editar Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=encerramento_editar&projeto_id=".$projeto_id."\");");
		if ($podeEditarTudo && $podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este termo de encerramento.').'Excluir Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='excluir()");
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ?cone '.imagem('imprimir_p.png').' para visualizar as op??es de relat?rios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
		$km->Add("acao_imprimir","acao_imprimir1",dica('Termo de Encerramento', 'Visualize o artefato do termo de encerramento.').' Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=encerramento_imprimir&dialogo=1&projeto_id=".$projeto_id."\");");
		if ($podeEditarTudo && $podeEditar) $km->Add("acao","acao_exportar",dica('Exportar Link', 'Clique neste ?cone '.imagem('icones/exporta_p.png').' para criar um endere?o web para visualiza??o em ambiente externo.').imagem('icones/exporta_p.png').' Exportar Link'.dicaF(), "javascript: void(0);' onclick='exportar_link();");	

		}	
	echo $km->Render();
	echo '</td></tr></table>';
	}
else {
	$botoesTitulo = new CBlocoTitulo('Termo de Encerramento d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	if ($podeEditar && $editar) {
		$botoesTitulo->adicionaBotao('m=projetos&a=encerramento_editar&projeto_id='.$projeto_id, ($obj->projeto_encerramento_responsavel ? 'editar' : 'inserir'),'',($obj->projeto_encerramento_responsavel ? 'Editar' : 'Inserir').' Termo de Encerramento',($obj->projeto_encerramento_responsavel ? 'Editar' : 'Inserir').' o termo de encerramento.');
		if (!$obj->projeto_encerramento_responsavel) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este encerramento.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir o Termo de Encerramento', 'Clique neste ?cone '.imagem('imprimir_p.png').' para imprimir o termo de encerramento.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=encerramento_imprimir&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_encerramento_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';


if($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $objProjeto->projeto_cia;
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
	echo '<table align="left" cellspacing=0 cellpadding=0 width=100%><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	echo 	'<font size="4"><center>Termo de Encerramento</center></font>';
	}

echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%">';
if ($obj->projeto_encerramento_encerrado || $obj->projeto_encerramento_encerrado_ressalvas || $obj->projeto_encerramento_nao_encerrado) {
	echo '<tr><td align="right">'.dica('Decis?o', 'Decis?o quanto ? encerrar ou n?o '.$config['genero_projeto'].' '.$config['projeto'].'.').'Decis?o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">';
 	if($obj->projeto_encerramento_encerrado) echo 'Projeto encerrado';
	elseif ($obj->projeto_encerramento_encerrado_ressalvas) echo 'Projeto encerrado com ressalvas';
	else echo 'Projeto n?o encerrado';
	echo '</td></tr>';
	}
if ($obj->projeto_encerramento_justificativa) echo '<tr><td align="right">'.dica('Justificativa', 'Justificativa do encerramento ou n?o encerramento do projeto.').'Justificativa:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_encerramento_justificativa.'</td></tr>';
if ($obj->projeto_encerramento_responsavel) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Respons?vel', ucfirst($config['usuario']).' respons?vel pelo termo de encerramento.').'Respons?vel:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_encerramento_responsavel, '','','esquerda').'</td></tr>';		
if ($obj->projeto_encerramento_data) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data', 'A data em que o termo de encerramento foi criado ou editado').'Data:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_encerramento_data).'</td></tr>';





require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('projeto_encerramento', $obj->projeto_encerramento_projeto, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				

if (!$obj->projeto_encerramento_responsavel) echo '<tr><td colspan=20 class="realce">Ainda n?o foi inserido um termo de encerramento</td></tr>';

		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script type="text/javascript">

function exportar_link() {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link_pro&dialogo=1&tipo=generico', null, window);
	}
	
function excluir() {
	if (confirm('Tem certeza que deseja excluir este encerramento?')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_encerramento';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}
</script>
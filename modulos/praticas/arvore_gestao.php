<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

global $dialogo;

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
if (!($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1) && !$Aplic->checarModulo('praticas', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

$filtrar=getParam($_REQUEST, 'filtrar', 0);

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : null;
if ($dept_id) $ver_subordinadas = null;

if (isset($_REQUEST['tipo_arvore'])) $Aplic->setEstado('tipo_arvore', getParam($_REQUEST, 'tipo_arvore', 'simples'));
$tipo_arvore = ($Aplic->getEstado('tipo_arvore') !== null ? $Aplic->getEstado('tipo_arvore') : 'simples');



if ($filtrar) $Aplic->setEstado('exibir_painel', getParam($_REQUEST, 'exibir_painel', ($Aplic->profissional ? 0 : 1)));
$exibir_painel = ($Aplic->getEstado('exibir_painel') !== null ? $Aplic->getEstado('exibir_painel') : ($Aplic->profissional ? 0 : 1));

if ($filtrar) $Aplic->setEstado('exibir_painel_composicao', getParam($_REQUEST, 'exibir_painel_composicao', ($Aplic->profissional ? 0 : 1)));
$exibir_painel_composicao = ($Aplic->getEstado('exibir_painel_composicao') !== null ? $Aplic->getEstado('exibir_painel_composicao') : ($Aplic->profissional ? 0 : 1));

if ($filtrar) $Aplic->setEstado('exibir_painel_slideshow', getParam($_REQUEST, 'exibir_painel_slideshow', ($Aplic->profissional ? 0 : 1)));
$exibir_painel_slideshow = ($Aplic->getEstado('exibir_painel_slideshow') !== null ? $Aplic->getEstado('exibir_painel_slideshow') : ($Aplic->profissional ? 0 : 1));





if ($filtrar) $Aplic->setEstado('exibir_projeto', getParam($_REQUEST, 'exibir_projeto', ($Aplic->profissional ? 0 : 1)));
$exibir_projeto = ($Aplic->getEstado('exibir_projeto') !== null ? $Aplic->getEstado('exibir_projeto') : ($Aplic->profissional ? 0 : 1));

if ($filtrar) $Aplic->setEstado('exibir_acao', getParam($_REQUEST, 'exibir_acao', ($Aplic->profissional ? 0 : 1)));
$exibir_acao = ($Aplic->getEstado('exibir_acao') !== null ? $Aplic->getEstado('exibir_acao') : ($Aplic->profissional ? 0 : 1));

if ($filtrar) $Aplic->setEstado('exibir_meta', getParam($_REQUEST, 'exibir_meta', ($Aplic->profissional ? 0 : 1)));
$exibir_meta = ($Aplic->getEstado('exibir_meta') !== null ? $Aplic->getEstado('exibir_meta') : ($Aplic->profissional ? 0 : 1));

if ($filtrar) $Aplic->setEstado('exibir_indicador', getParam($_REQUEST, 'exibir_indicador', ($Aplic->profissional ? 0 : 1)));
$exibir_indicador = ($Aplic->getEstado('exibir_indicador') !== null ? $Aplic->getEstado('exibir_indicador') : ($Aplic->profissional ? 0 : 1));


$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if (isset($_REQUEST['ver_dept_subordinados'])) $Aplic->setEstado('ver_dept_subordinados', getParam($_REQUEST, 'ver_dept_subordinados', null));
$ver_dept_subordinados = ($Aplic->getEstado('ver_dept_subordinados') !== null ? $Aplic->getEstado('ver_dept_subordinados') : (($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) ? $Aplic->usuario_prefs['ver_dept_subordinados'] : 0));
if ($ver_subordinadas) $ver_dept_subordinados=0;

$lista_depts='';
if ($ver_dept_subordinados){
	$vetor_depts=array();
	lista_depts_subordinados($dept_id, $vetor_depts);
	$vetor_depts[]=$dept_id;
	$lista_depts=implode(',',$vetor_depts);
	}


if (isset($_REQUEST['pg_id'])) $Aplic->setEstado('pg_id', getParam($_REQUEST, 'pg_id', null));
$pg_id = ($Aplic->getEstado('pg_id') !== null ? $Aplic->getEstado('pg_id') : 0);

$sql->adTabela('plano_gestao');
$sql->esqUnir('plano_gestao_cia', 'plano_gestao_cia', 'plano_gestao_cia_plano=plano_gestao.pg_id');
$sql->adCampo('DISTINCT pg_id, pg_nome');
if ($ver_subordinadas) $sql->adOnde('pg_cia IN ('.$lista_cias.') OR plano_gestao_cia_cia IN ('.$lista_cias.')');
else $sql->adOnde('pg_cia ='.(int)$cia_id.' OR plano_gestao_cia_cia='.(int)$cia_id);
if ($ver_dept_subordinados && $lista_depts) $sql->adOnde('pg_dept IN ('.$lista_depts.')');
elseif ($dept_id) $sql->adOnde('pg_dept='.(int)$dept_id);
else $sql->adOnde('pg_dept=0 OR pg_dept IS NULL');
$sql->adOrdem('pg_nome ASC');
$planos=array(0=>'')+$sql->listaVetorChave('pg_id','pg_nome');
$sql->limpar();


$sql->adTabela('plano_gestao');
$sql->adCampo('pg_inicio, pg_fim');
$sql->adOnde('pg_id='.(int)$pg_id);
$linha=$sql->Linha();
$sql->limpar();
$inicio=(isset($linha['pg_inicio']) ? $linha['pg_inicio'] : null);
$fim=(isset($linha['pg_fim']) ? $linha['pg_fim'] : null);

echo '<form method="post" name="frm_filtro">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="arvore_gestao" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';
echo '<input type="hidden" name="filtrar" value="1" />';

$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste �cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste �cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '�' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N�o Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste �cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '�' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste �cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste �cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste �cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '�' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N�o Incluir Subordinad'.$config['genero_dept'].'s','Clique neste �cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '�' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');


if (!$dialogo){
	$Aplic->salvarPosicao();
	
	$botoesTitulo = new CBlocoTitulo('�rvore da Gest�o Estrat�gica', 'arvore.gif', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e A��es','Clique nesta barra para esconder/mostrar os filtros e as a��es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/arvore_p.gif').'&nbsp;Filtros e A��es</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$selecao_plano='<tr><td>'.dica('Sele��o do Planejamento Estrat�gico', 'Utilize esta op��o para filtrar de qual planejamento estrat�gico ser� filtrado os dados.').'Planejamento Estrat�gico:'.dicaF().'</td><td>'.selecionaVetor($planos, 'pg_id', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $pg_id).'</td></tr>';
	$tipos=array('nada' => 'Simples', 'simples' => 'Pontua��o do principal indicador', 'percentagem'=>'Progresso');
	$procura_tipo='<tr><td align="right" style="white-space: nowrap">'.dica('Exibi��o', 'Qual a forma de apresentar os objetos principais da �rvore de gest�o').'Exibi��o:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'tipo_arvore', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $tipo_arvore).'</td></tr>';

	$popup='<tr><td style="white-space: nowrap">'.dica('PopUp', 'Abrir uma janela com a �rvore de gest�o para facilitar a navega��o entre os dados.').'<a href="javascript: void(0)" onclick="popup();" ><img src="'.acharImagem('popup.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>';
	$imprimir='<tr><td align="right" style="white-space: nowrap">'.dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir a �rvore da gest�o estrat�gica.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=arvore_gestao&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';
	
	$icone_exibir_painel='<tr><td width="1%" style="white-space: nowrap"><input type="checkbox" name="exibir_painel" id="exibir_painel" '.($exibir_painel ? 'checked="checked"' : '').' onchange="document.frm_filtro.submit()" value="1" /></td><td width="1%" style="white-space: nowrap"><label for="exibir_painel">'.dica('Pain�is', 'Selecione esta op��o caso deseja ver os pain�is envolvidos.').' Pain�is'.dicaF().'</label></td></tr>';
	$icone_exibir_painel_composicao='<tr><td width="1%" style="white-space: nowrap"><input type="checkbox" name="exibir_painel_composicao" id="exibir_painel_composicao" '.($exibir_painel_composicao ? 'checked="checked"' : '').' onchange="document.frm_filtro.submit()" value="1" /></td><td width="1%" style="white-space: nowrap"><label for="exibir_painel_composicao">'.dica('Composi��es de Pain�is', 'Selecione esta op��o caso deseja ver as composi��es de pain�is envolvidas.').' Composi��es de Pain�is'.dicaF().'</label></td></tr>';
	
	$icone_exibir_painel_slideshow='<tr><td width="1%" style="white-space: nowrap"><input type="checkbox" name="exibir_painel_slideshow" id="exibir_painel_slideshow" '.($exibir_painel_slideshow ? 'checked="checked"' : '').' onchange="document.frm_filtro.submit()" value="1" /></td><td width="1%" style="white-space: nowrap"><label for="exibir_painel_slideshow">'.dica('Slideshows', 'Selecione esta op��o caso deseja ver os slideshows envolvidos.').' Slideshows'.dicaF().'</label></td></tr>';
	
	
	
	$icone_exibir_projeto='<tr><td width="1%" style="white-space: nowrap"><input type="checkbox" name="exibir_projeto" id="exibir_projeto" '.($exibir_projeto ? 'checked="checked"' : '').' onchange="document.frm_filtro.submit()" value="1" /></td><td width="1%" style="white-space: nowrap"><label for="exibir_projeto">'.dica(ucfirst($config['projetos']), 'Selecione esta op��o caso deseja ver '.$config['genero_projeto'].'s '.$config['projetos'].' envolvid'.$config['genero_projeto'].'s.').' '.$config['projetos'].dicaF().'</label></td></tr>';
	$icone_exibir_acao='<tr><td width="1%" style="white-space: nowrap"><input type="checkbox" name="exibir_acao" id="exibir_acao" '.($exibir_acao ? 'checked="checked"' : '').' onchange="document.frm_filtro.submit()" value="1" /></td><td width="1%" style="white-space: nowrap"><label for="exibir_acao">'.dica(ucfirst($config['acoes']), 'Selecione esta op��o caso deseja ver '.$config['genero_acao'].'s '.$config['acoes'].' envolvid'.$config['genero_acao'].'s.').' '.$config['acoes'].dicaF().'</label></td></tr>';
	$icone_exibir_meta='<tr><td width="1%" style="white-space: nowrap"><input type="checkbox" name="exibir_meta" id="exibir_meta" '.($exibir_meta ? 'checked="checked"' : '').' onchange="document.frm_filtro.submit()" value="1" /></td><td width="1%" style="white-space: nowrap"><label for="exibir_meta">'.dica(ucfirst($config['metas']), 'Selecione esta op��o caso deseja ver '.$config['genero_meta'].'s '.$config['metas'].' envolvid'.$config['genero_meta'].'s.').' '.$config['metas'].dicaF().'</label></td></tr>';
	$icone_exibir_indicador='<tr><td width="1%" style="white-space: nowrap"><input type="checkbox" name="exibir_indicador" id="exibir_indicador" '.($exibir_indicador ? 'checked="checked"' : '').' onchange="document.frm_filtro.submit()" value="1" /></td><td width="1%" style="white-space: nowrap"><label for="exibir_indicador">'.dica('Indicadores', 'Selecione esta op��o caso deseja ver os indicadores envolvidos.').' indicadores'.dicaF().'</label></td></tr>';
	$exibir='<tr><td align="right" style="white-space: nowrap">'.dica('Exibir', 'Quais objetos da lista � direita deseja exibir na �rvore de gest�o.').'Exibir:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0>'.$icone_exibir_meta.$icone_exibir_indicador.$icone_exibir_projeto.$icone_exibir_acao.$icone_exibir_painel.$icone_exibir_painel_composicao.$icone_exibir_painel_slideshow.'</table></td></tr>';
	$botao_link=($Aplic->profissional && $podeEditar ? '<tr><td><a href="javascript: void(0)" onclick="javascript:exportar_link();">'.imagem('icones/arvore_exporta.png', 'Exportar Link', 'Clique neste �cone '.imagem('icones/arvore_exporta.png').' para criar um endere�o web para visualiza��o da �rvore em ambiente externo.').'</a></td></tr>' : '');
	$saida.='<tr><td valign=top><table cellspacing=0 cellpadding=0>'.$procurar_om.$selecao_plano.$procura_tipo.$exibir.'</table></td><td valign=top><table cellspacing=0 cellpadding=0>'.$imprimir.$botao_link.$popup.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}
elseif($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $cia_id;
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = (isset($linha['artefato_tipo_campos']) ? unserialize($linha['artefato_tipo_campos']) : null);
	
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
	echo 	'<font size="4"><center>�rvore de Gest�o</center></font>';
	}	
	
	
	
echo '</form>';

if($Aplic->profissional) $Aplic->carregarComboMultiSelecaoJS();

require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";
$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;
$arvore->EditNodeEnable = false;
$arvore->DragAndDropEnable=true;
$arvore->multipleSelectEnable = true;

$root = $arvore->getRootNode();
$root->text='Gest�o';
$root->expand=true;
$root->image="ferramentas_p.png";
$root->addData("id", 0);




$sql->adTabela('perspectivas');
$sql->adCampo('perspectivas.pg_perspectiva_id, pg_perspectiva_nome, pg_perspectiva_percentagem');
if ($pg_id){
	$sql->esqUnir('plano_gestao_perspectivas','plano_gestao_perspectivas','plano_gestao_perspectivas.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adOnde('plano_gestao_perspectivas.pg_id='.(int)$pg_id);
	}
else {
	$sql->adOnde('pg_perspectiva_ativo=1');
	if ($dept_id || $lista_depts) {
		$sql->esqUnir('perspectivas_depts','perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
		$sql->adOnde('pg_perspectiva_dept IN ('.($lista_depts ? $lista_depts : $dept_id).') OR perspectivas_depts.dept_id IN ('.($lista_depts ? $lista_depts : $dept_id).')');
		}
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('perspectiva_cia', 'perspectiva_cia', 'perspectivas.pg_perspectiva_id=perspectiva_cia_perspectiva');
		$sql->adOnde('pg_perspectiva_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR perspectiva_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_perspectiva_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('pg_perspectiva_cia IN ('.$lista_cias.')');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_perspectivas.pg_perspectiva_ordem');
else $sql->adOrdem('perspectivas.pg_perspectiva_ordem');
$sql->adGrupo('perspectivas.pg_perspectiva_id');
$perspectivas=$sql->Lista();
$sql->limpar();

$vetor_perspectiva=array();
foreach($perspectivas as $linha){
	$vetor_perspectiva[$linha['pg_perspectiva_id']]=(isset($vetor_perspectiva[$linha['pg_perspectiva_id']]) ? $vetor_perspectiva[$linha['pg_perspectiva_id']]+1 : 1);
	
	if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('perspectiva',$linha['pg_perspectiva_id'],null, $inicio, $fim);
	elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_perspectiva_percentagem'],'%');
	else $cor_indicador='';
	
	$nodulo=$arvore->Add('root', 'p'.$linha['pg_perspectiva_id'], $cor_indicador.' '.link_perspectiva($linha['pg_perspectiva_id']),false, "perspectiva_p.png");
	$nodulo->addData("id", $linha['pg_perspectiva_id']);
	
	}





$sql->adTabela('tema_gestao');
$sql->esqUnir('tema', 'tema','tema_gestao_tema=tema.tema_id');
$sql->adCampo('tema.tema_id, tema_nome, tema_gestao_perspectiva AS tema_perspectiva, tema_percentagem');
if ($pg_id){
	$sql->esqUnir('plano_gestao_tema','plano_gestao_tema','plano_gestao_tema.tema_id=tema.tema_id');
	$sql->adOnde('plano_gestao_tema.pg_id='.(int)$pg_id);
	}
else {
	$sql->adOnde('tema_ativo=1');
	if ($dept_id || $lista_depts) {
		$sql->esqUnir('tema_depts','tema_depts', 'tema_depts.tema_id=tema.tema_id');
		$sql->adOnde('tema_dept IN ('.($lista_depts ? $lista_depts : $dept_id).') OR tema_depts.dept_id IN ('.($lista_depts ? $lista_depts : $dept_id).')');
		}
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('tema_cia', 'tema_cia', 'tema.tema_id=tema_cia_tema');
		$sql->adOnde('tema_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tema_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('tema_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('tema_cia IN ('.$lista_cias.')');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_tema.tema_ordem');
else $sql->adOrdem('tema.tema_ordem');
$sql->adGrupo('tema_gestao_id');
$temas=$sql->Lista();

$sql->limpar();
$vetor_tema=array();
foreach($temas as $linha){
	if (isset($vetor_perspectiva[$linha['tema_perspectiva']])){
		$vetor_tema[$linha['tema_id']]=(isset($vetor_tema[$linha['tema_id']]) ? $vetor_tema[$linha['tema_id']]+1 : 1);
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('tema',$linha['tema_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['tema_percentagem'],'%');
		else $cor_indicador='';
		$nodulo=$arvore->Add('p'.$linha['tema_perspectiva'], 't'.$linha['tema_id'].'_'.$vetor_tema[$linha['tema_id']], $cor_indicador.' '.link_tema($linha['tema_id']),false, "tema_p.png");
		$nodulo->addData("id", $linha['tema_id']);
		}
	}





$sql->adTabela('objetivo_gestao');
$sql->esqUnir('objetivo', 'objetivo','objetivo_gestao_objetivo=objetivo.objetivo_id');
$sql->adCampo('objetivo.objetivo_id, objetivo_gestao_perspectiva AS objetivo_perspectiva, objetivo_gestao_tema AS objetivo_tema, objetivo_nome, objetivo_percentagem');
if ($pg_id){
	$sql->esqUnir('plano_gestao_objetivo','plano_gestao_objetivo','plano_gestao_objetivo_objetivo=objetivo.objetivo_id');
	$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
	}
else {
	$sql->adOnde('objetivo_ativo=1');
	if ($dept_id || $lista_depts) {
		$sql->esqUnir('objetivo_dept','objetivo_dept', 'objetivo_dept_objetivo=objetivo.objetivo_id');
		$sql->adOnde('objetivo_dept IN ('.($lista_depts ? $lista_depts : $dept_id).') OR objetivo_dept_dept IN ('.($lista_depts ? $lista_depts : $dept_id).')');
		}
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('objetivo_cia', 'objetivo_cia', 'objetivo.objetivo_id=objetivo_cia_objetivo');
		$sql->adOnde('objetivo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR objetivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('objetivo_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('objetivo_cia IN ('.$lista_cias.')');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_objetivo_ordem');
else $sql->adOrdem('objetivo.objetivo_ordem');
$sql->adGrupo('objetivo_gestao_id');
$objetivos=$sql->Lista();
$sql->limpar();
$vetor_objetivo=array();

foreach($objetivos as $linha){
	if (isset($vetor_tema[$linha['objetivo_tema']]) && $linha['objetivo_id']){
		
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('objetivo',$linha['objetivo_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['objetivo_percentagem'],'%');
		else $cor_indicador='';
		for ($i=1; $i<=$vetor_tema[$linha['objetivo_tema']]; $i++){
			$vetor_objetivo[$linha['objetivo_id']]=(isset($vetor_objetivo[$linha['objetivo_id']]) ? $vetor_objetivo[$linha['objetivo_id']]+1 : 1);
			$nodulo=$arvore->Add('t'.$linha['objetivo_tema'].'_'.$i, 'o'.$linha['objetivo_id'].'_'.$vetor_objetivo[$linha['objetivo_id']], $cor_indicador.' '.link_objetivo($linha['objetivo_id']),false, "obj_estrategicos_p.gif");
			$nodulo->addData("id", $linha['objetivo_id']);
			}
		}
	
	elseif (isset($vetor_perspectiva[$linha['objetivo_perspectiva']]) && $linha['objetivo_id']){
		$vetor_objetivo[$linha['objetivo_id']]=(isset($vetor_objetivo[$linha['objetivo_id']]) ? $vetor_objetivo[$linha['objetivo_id']]+1 : 1);
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('objetivo',$linha['objetivo_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['objetivo_percentagem'],'%');
		else $cor_indicador='';
		$nodulo=$arvore->Add('p'.$linha['objetivo_perspectiva'], 'o'.$linha['objetivo_id'].'_'.$vetor_objetivo[$linha['objetivo_id']], $cor_indicador.' '.link_objetivo($linha['objetivo_id']),false, "obj_estrategicos_p.gif");
		$nodulo->addData("id", $linha['objetivo_id']);
		}
	}

$sql->adTabela('me_gestao');
$sql->esqUnir('me', 'me','me_gestao_me=me.me_id');
$sql->adCampo('me.me_id, me_nome, me_gestao_objetivo AS me_objetivo, me_percentagem');
if ($pg_id){
	$sql->esqUnir('plano_gestao_me','plano_gestao_me','plano_gestao_me.plano_gestao_me_me=me.me_id');
	$sql->adOnde('plano_gestao_me_pg='.(int)$pg_id);
	}
else {
	$sql->adOnde('me_ativo=1');
	if ($dept_id || $lista_depts) {
		$sql->esqUnir('me_dept','me_dept', 'me_dept_me=me.me_id');
		$sql->adOnde('me_dept IN ('.($lista_depts ? $lista_depts : $dept_id).') OR me_dept_dept IN ('.($lista_depts ? $lista_depts : $dept_id).')');
		}
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('me_cia', 'me_cia', 'me.me_id=me_cia_me');
		$sql->adOnde('me_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR me_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('me_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('me_cia IN ('.$lista_cias.')');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_me_ordem');
else $sql->adOrdem('me_gestao_ordem');
$sql->adGrupo('me_gestao_id');
$mes=$sql->Lista();
$sql->limpar();

$vetor_me=array();
foreach($mes as $linha){
	if (isset($vetor_objetivo[$linha['me_objetivo']]) && $linha['me_id']){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('me',$linha['me_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['me_percentagem'],'%');
		else $cor_indicador='';
		//inserir em cada objetivo a ME, caso esteja em mais de uma
		for ($i=1; $i <= $vetor_objetivo[$linha['me_objetivo']]; $i++){
			$vetor_me[$linha['me_id']]=(isset($vetor_me[$linha['me_id']]) ? $vetor_me[$linha['me_id']]+1 : 1);
			$nodulo=$arvore->Add('o'.$linha['me_objetivo'].'_'.$i, 'y'.$linha['me_id'].'_'.$vetor_me[$linha['me_id']], $cor_indicador.' '.link_me($linha['me_id']),false, "me_p.png");
			$nodulo->addData("id", $linha['me_id']);
			}	
		}
	}


$sql->adTabela('fator_gestao');
$sql->esqUnir('fator', 'fator','fator_gestao_fator=fator.fator_id');
$sql->adCampo('fator.fator_id, fator_nome, fator_gestao_objetivo AS fator_objetivo, fator_gestao_me, fator_percentagem');
if ($pg_id){
	$sql->esqUnir('plano_gestao_fator','plano_gestao_fator','plano_gestao_fator_fator=fator.fator_id');
	$sql->adOnde('plano_gestao_fator_plano_gestao='.(int)$pg_id);
	}
else {
	$sql->adOnde('fator_ativo=1');
	if ($dept_id || $lista_depts) {
		$sql->esqUnir('fator_dept','fator_dept', 'fator_dept_fator=fator.fator_id');
		$sql->adOnde('fator_dept IN ('.($lista_depts ? $lista_depts : $dept_id).') OR fator_dept_dept IN ('.($lista_depts ? $lista_depts : $dept_id).')');
		}
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('fator_cia', 'fator_cia', 'fator.fator_id=fator_cia_fator');
		$sql->adOnde('fator_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR fator_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('fator_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('fator_cia IN ('.$lista_cias.')');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_fator_ordem');
else $sql->adOrdem('fator.fator_ordem');
$sql->adGrupo('fator_gestao_id');
if (!$Aplic->profissional) $sql->adGrupo('fator_id, fator_objetivo, fator_gestao_me');
$fatores=$sql->Lista();
$sql->limpar();
$vetor_fator=array();
foreach($fatores as $linha){
	if (isset($vetor_objetivo[$linha['fator_objetivo']]) && $linha['fator_id']){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('fator',$linha['fator_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['fator_percentagem'],'%');
		else $cor_indicador='';
		for ($i=1; $i<=$vetor_objetivo[$linha['fator_objetivo']]; $i++){
			$vetor_fator[$linha['fator_id']]=(isset($vetor_fator[$linha['fator_id']]) ? $vetor_fator[$linha['fator_id']]+1 : 1);
			$nodulo=$arvore->Add('o'.$linha['fator_objetivo'].'_'.$i, 'f'.$linha['fator_id'].'_'.$vetor_fator[$linha['fator_id']], $cor_indicador.' '.link_fator($linha['fator_id']),false, "fator_p.gif");
			$nodulo->addData("id", $linha['fator_id']);
			}
		}
	elseif (isset($vetor_me[$linha['fator_gestao_me']]) && $linha['fator_gestao_me']){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('fator',$linha['fator_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['fator_percentagem'],'%');
		else $cor_indicador='';
		for ($i=1; $i<=$vetor_me[$linha['fator_gestao_me']]; $i++){
			$vetor_fator[$linha['fator_id']]=(isset($vetor_fator[$linha['fator_id']]) ? $vetor_fator[$linha['fator_id']]+1 : 1);
			$nodulo=$arvore->Add('y'.$linha['fator_gestao_me'].'_'.$i, 'f'.$linha['fator_id'].'_'.$vetor_fator[$linha['fator_id']], $cor_indicador.' '.link_fator($linha['fator_id']),false, "fator_p.gif");
			$nodulo->addData("id", $linha['fator_id']);
			}
		}		
	}

$sql->adTabela('estrategia_gestao');
$sql->esqUnir('estrategias', 'estrategias','estrategia_gestao_estrategia=estrategias.pg_estrategia_id');
$sql->adCampo('estrategias.pg_estrategia_id, pg_estrategia_nome, estrategia_gestao_fator, estrategia_gestao_objetivo, estrategia_gestao_tema, estrategia_gestao_perspectiva, estrategia_gestao_me, pg_estrategia_percentagem');
if ($pg_id){
	$sql->esqUnir('plano_gestao_estrategias','plano_gestao_estrategias','plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('plano_gestao_estrategias.pg_id='.(int)$pg_id);
	}
else {
	$sql->adOnde('pg_estrategia_ativo=1');
	if ($dept_id || $lista_depts) {
		$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
		$sql->adOnde('pg_estrategia_dept IN ('.($lista_depts ? $lista_depts : $dept_id).') OR estrategias_depts.dept_id IN ('.($lista_depts ? $lista_depts : $dept_id).')');
		}
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('estrategia_cia', 'estrategia_cia', 'estrategias.pg_estrategia_id=estrategia_cia_estrategia');
		$sql->adOnde('pg_estrategia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR estrategia_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('pg_estrategia_cia IN ('.$lista_cias.')');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_estrategias.pg_estrategia_ordem');
else $sql->adOrdem('estrategias.pg_estrategia_ordem');
$sql->adGrupo('estrategia_gestao_id');
$estrategias=$sql->Lista();
$sql->limpar();

$vetor_estrategia=array();
foreach($estrategias as $linha){
	if (isset($vetor_perspectiva[$linha['estrategia_gestao_perspectiva']]) && $linha['pg_estrategia_id']){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('estrategia',$linha['pg_estrategia_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_estrategia_percentagem'],'%');
		else $cor_indicador='';
		$vetor_estrategia[$linha['pg_estrategia_id']]=(isset($vetor_estrategia[$linha['pg_estrategia_id']]) ? $vetor_estrategia[$linha['pg_estrategia_id']]+1 : 1);
		$nodulo=$arvore->Add('p'.$linha['estrategia_gestao_perspectiva'], 'e'.$linha['pg_estrategia_id'].'_'.$vetor_estrategia[$linha['pg_estrategia_id']], $cor_indicador.' '.link_estrategia($linha['pg_estrategia_id']),false, "estrategia_p.gif");
		$nodulo->addData("id", $linha['pg_estrategia_id']);
		}	
	elseif (isset($vetor_tema[$linha['estrategia_gestao_tema']]) && $linha['pg_estrategia_id']){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('estrategia',$linha['pg_estrategia_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_estrategia_percentagem'],'%');
		else $cor_indicador='';
		for ($i=1; $i<=$vetor_tema[$linha['estrategia_gestao_tema']]; $i++){
			$vetor_estrategia[$linha['pg_estrategia_id']]=(isset($vetor_estrategia[$linha['pg_estrategia_id']]) ? $vetor_estrategia[$linha['pg_estrategia_id']]+1 : 1);
			$nodulo=$arvore->Add('t'.$linha['estrategia_gestao_tema'].'_'.$i, 'e'.$linha['pg_estrategia_id'].'_'.$vetor_estrategia[$linha['pg_estrategia_id']], $cor_indicador.' '.link_estrategia($linha['pg_estrategia_id']),false, "estrategia_p.gif");
			$nodulo->addData("id", $linha['pg_estrategia_id']);
			}
		}
	if (isset($vetor_me[$linha['estrategia_gestao_me']]) && $linha['pg_estrategia_id']){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('estrategia',$linha['pg_estrategia_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_estrategia_percentagem'],'%');
		else $cor_indicador='';
		for ($i=1; $i<=$vetor_me[$linha['estrategia_gestao_me']]; $i++){
			$vetor_estrategia[$linha['pg_estrategia_id']]=(isset($vetor_estrategia[$linha['pg_estrategia_id']]) ? $vetor_estrategia[$linha['pg_estrategia_id']]+1 : 1);
			$nodulo=$arvore->Add('y'.$linha['estrategia_gestao_me'].'_'.$i, 'e'.$linha['pg_estrategia_id'].'_'.$vetor_estrategia[$linha['pg_estrategia_id']], $cor_indicador.' '.link_estrategia($linha['pg_estrategia_id']),false, "estrategia_p.gif");
			$nodulo->addData("id", $linha['pg_estrategia_id']);
			}
		}	
	if (isset($vetor_fator[$linha['estrategia_gestao_fator']]) && $linha['pg_estrategia_id']){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('estrategia',$linha['pg_estrategia_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_estrategia_percentagem'],'%');
		else $cor_indicador='';
		for ($i=1; $i<=$vetor_fator[$linha['estrategia_gestao_fator']]; $i++){
			$vetor_estrategia[$linha['pg_estrategia_id']]=(isset($vetor_estrategia[$linha['pg_estrategia_id']]) ? $vetor_estrategia[$linha['pg_estrategia_id']]+1 : 1);
			$nodulo=$arvore->Add('f'.$linha['estrategia_gestao_fator'].'_'.$i, 'e'.$linha['pg_estrategia_id'].'_'.$vetor_estrategia[$linha['pg_estrategia_id']], $cor_indicador.' '.link_estrategia($linha['pg_estrategia_id']),false, "estrategia_p.gif");
			$nodulo->addData("id", $linha['pg_estrategia_id']);
			}
		}
	elseif (isset($vetor_objetivo[$linha['estrategia_gestao_objetivo']]) && $linha['pg_estrategia_id']){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('estrategia',$linha['pg_estrategia_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_estrategia_percentagem'],'%');
		else $cor_indicador='';
		
		for ($i=1; $i<=$vetor_objetivo[$linha['estrategia_gestao_objetivo']]; $i++){
			$vetor_estrategia[$linha['pg_estrategia_id']]=(isset($vetor_estrategia[$linha['pg_estrategia_id']]) ? $vetor_estrategia[$linha['pg_estrategia_id']]+1 : 1);
			$nodulo=$arvore->Add('o'.$linha['estrategia_gestao_objetivo'].'_'.$i, 'e'.$linha['pg_estrategia_id'].'_'.$vetor_estrategia[$linha['pg_estrategia_id']], $cor_indicador.' '.link_estrategia($linha['pg_estrategia_id']),false, "estrategia_p.gif");
			$nodulo->addData("id", $linha['pg_estrategia_id']);
			}
		}
	}

$vetor_meta=array();

if ($exibir_meta){
	if (!$Aplic->profissional) {
		$sql->adTabela('metas');
		$sql->adCampo('metas.pg_meta_id, pg_meta_nome, pg_meta_estrategia, pg_meta_perspectiva, pg_meta_tema, pg_meta_objetivo_estrategico, pg_meta_fator, pg_meta_percentagem, 0 AS meta_gestao_me');
		$sql->adGrupo('pg_meta_id, pg_meta_estrategia, pg_meta_perspectiva, pg_meta_tema, meta_gestao_me, pg_meta_objetivo_estrategico, pg_meta_fator, pg_meta_percentagem');
		}
	else {
		$sql->adTabela('meta_gestao');
		$sql->esqUnir('metas','metas','meta_gestao_meta=metas.pg_meta_id');
		$sql->adCampo('metas.pg_meta_id, pg_meta_nome, pg_meta_percentagem');
		$sql->adCampo('
		meta_gestao_perspectiva AS pg_meta_perspectiva, 
		meta_gestao_tema AS pg_meta_tema,
		meta_gestao_objetivo AS pg_meta_objetivo_estrategico,
		meta_gestao_me, 
		meta_gestao_fator AS pg_meta_fator, 
		meta_gestao_estrategia AS pg_meta_estrategia			
		');
		$sql->adGrupo('meta_gestao_id');
		}
	if ($pg_id){
		$sql->esqUnir('plano_gestao_metas','plano_gestao_metas','plano_gestao_metas.pg_meta_id=metas.pg_meta_id');
		$sql->adOnde('plano_gestao_metas.pg_id='.(int)$pg_id);
		}
	else {
		$sql->adOnde('pg_meta_ativo=1');
		if ($dept_id || $lista_depts) {
			$sql->esqUnir('metas_depts','metas_depts', 'metas_depts.pg_meta_id=metas.pg_meta_id');
			$sql->adOnde('pg_meta_dept IN ('.($lista_depts ? $lista_depts : $dept_id).') OR metas_depts.dept_id IN ('.($lista_depts ? $lista_depts : $dept_id).')');
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('meta_cia', 'meta_cia', 'metas.pg_meta_id=meta_cia_meta');
			$sql->adOnde('pg_meta_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR meta_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_meta_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_meta_cia IN ('.$lista_cias.')');
		}
	if ($pg_id) $sql->adOrdem('plano_gestao_metas.pg_meta_ordem');
	else $sql->adOrdem('metas.pg_meta_ordem');
	$metas=$sql->Lista();
	$sql->limpar();
	
	
	foreach($metas as $linha){
		if (isset($vetor_perspectiva[$linha['pg_meta_perspectiva']]) && $linha['pg_meta_id']){
			if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('meta',$linha['pg_meta_id'],null, $inicio, $fim);
			elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_meta_percentagem'],'%');
			else $cor_indicador='';
			$vetor_meta[$linha['pg_meta_id']]=(isset($vetor_meta[$linha['pg_meta_id']]) ? $vetor_meta[$linha['pg_meta_id']]+1 : 1);
			$nodulo=$arvore->Add('p'.$linha['pg_meta_perspectiva'], 'm'.$linha['pg_meta_id'].'_'.$vetor_meta[$linha['pg_meta_id']], $cor_indicador.' '.link_meta($linha['pg_meta_id']),false, "meta_p.gif");
			$nodulo->addData("id", $linha['pg_meta_id']);
			}
		elseif (isset($vetor_tema[$linha['pg_meta_tema']]) && $linha['pg_meta_id']){
			if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('meta',$linha['pg_meta_id'],null, $inicio, $fim);
			elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_meta_percentagem'],'%');
			else $cor_indicador='';
			for ($i=1; $i<=$vetor_tema[$linha['pg_meta_tema']]; $i++){
				$vetor_meta[$linha['pg_meta_id']]=(isset($vetor_meta[$linha['pg_meta_id']]) ? $vetor_meta[$linha['pg_meta_id']]+1 : 1);
				$nodulo=$arvore->Add('t'.$linha['pg_meta_tema'].'_'.$i, 'm'.$linha['pg_meta_id'].'_'.$vetor_meta[$linha['pg_meta_id']], $cor_indicador.' '.link_meta($linha['pg_meta_id']),false, "meta_p.gif");
				$nodulo->addData("id", $linha['pg_meta_id']);
				}
			}
		elseif (isset($vetor_objetivo[$linha['pg_meta_objetivo_estrategico']]) && $linha['pg_meta_id']){
			if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('meta',$linha['pg_meta_id'],null, $inicio, $fim);
			elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_meta_percentagem'],'%');
			else $cor_indicador='';
			for ($i=1; $i<=$vetor_objetivo[$linha['pg_meta_objetivo_estrategico']]; $i++){
				$vetor_meta[$linha['pg_meta_id']]=(isset($vetor_meta[$linha['pg_meta_id']]) ? $vetor_meta[$linha['pg_meta_id']]+1 : 1);
				$nodulo=$arvore->Add('o'.$linha['pg_meta_objetivo_estrategico'].'_'.$i, 'm'.$linha['pg_meta_id'].'_'.$vetor_meta[$linha['pg_meta_id']], $cor_indicador.' '.link_meta($linha['pg_meta_id']),false, "meta_p.gif");
				$nodulo->addData("id", $linha['pg_meta_id']);
				}
			}
		elseif (isset($vetor_me[$linha['meta_gestao_me']]) && $linha['pg_meta_id']){
			if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('me',$linha['meta_gestao_me'],null, $inicio, $fim);
			elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_meta_percentagem'],'%');
			else $cor_indicador='';
			for ($i=1; $i<=$vetor_me[$linha['meta_gestao_me']]; $i++){
				$vetor_meta[$linha['pg_meta_id']]=(isset($vetor_meta[$linha['pg_meta_id']]) ? $vetor_meta[$linha['pg_meta_id']]+1 : 1);
				$nodulo=$arvore->Add('y'.$linha['meta_gestao_me'].'_'.$i, 'm'.$linha['pg_meta_id'].'_'.$vetor_meta[$linha['pg_meta_id']], $cor_indicador.' '.link_meta($linha['pg_meta_id']),false, "meta_p.gif");
				$nodulo->addData("id", $linha['pg_meta_id']);
				}
			}	
		elseif (isset($vetor_fator[$linha['pg_meta_fator']]) && $linha['pg_meta_id']){
			if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('meta',$linha['pg_meta_id'],null, $inicio, $fim);
			elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_meta_percentagem'],'%');
			else $cor_indicador='';
			for ($i=1; $i<=$vetor_fator[$linha['pg_meta_fator']]; $i++){
				$vetor_meta[$linha['pg_meta_id']]=(isset($vetor_meta[$linha['pg_meta_id']]) ? $vetor_meta[$linha['pg_meta_id']]+1 : 1);
				$nodulo=$arvore->Add('f'.$linha['pg_meta_fator'].'_'.$i, 'm'.$linha['pg_meta_id'].'_'.$vetor_meta[$linha['pg_meta_id']], $cor_indicador.' '.link_meta($linha['pg_meta_id']),false, "meta_p.gif");
				$nodulo->addData("id", $linha['pg_meta_id']);
				}
			}
		elseif (isset($vetor_estrategia[$linha['pg_meta_estrategia']]) && $linha['pg_meta_id']){
			if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('meta',$linha['pg_meta_id'],null, $inicio, $fim);
			elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['pg_meta_percentagem'],'%');
			else $cor_indicador='';
			for ($i=1; $i<=$vetor_estrategia[$linha['pg_meta_estrategia']]; $i++){
				$vetor_meta[$linha['pg_meta_id']]=(isset($vetor_meta[$linha['pg_meta_id']]) ? $vetor_meta[$linha['pg_meta_id']]+1 : 1);
				$nodulo=$arvore->Add('e'.$linha['pg_meta_estrategia'].'_'.$i, 'm'.$linha['pg_meta_id'].'_'.$vetor_meta[$linha['pg_meta_id']], $cor_indicador.' '.link_meta($linha['pg_meta_id']),false, "meta_p.gif");
				$nodulo->addData("id", $linha['pg_meta_id']);
				}
			}
		}
	}

$lista_perspectivas=array();
$lista_temas=array();
$lista_objetivos=array();
$lista_mes=array();
$lista_fatores=array();
$lista_estrategias=array();
$lista_metas=array();
$lista_projetos=array();
$lista_plano_acoes=array();

foreach($vetor_perspectiva as $chave => $valor) $lista_perspectivas[]=$chave;
foreach($vetor_tema as $chave => $valor) $lista_temas[]=$chave;
foreach($vetor_objetivo as $chave => $valor) $lista_objetivos[]=$chave;
foreach($vetor_me as $chave => $valor) $lista_mes[]=$chave;
foreach($vetor_fator as $chave => $valor) $lista_fatores[]=$chave;
foreach($vetor_estrategia as $chave => $valor) $lista_estrategias[]=$chave;
foreach($vetor_meta as $chave => $valor) $lista_metas[]=$chave;
$lista_perspectivas=implode(',',$lista_perspectivas);
$lista_temas=implode(',',$lista_temas);
$lista_objetivos=implode(',',$lista_objetivos);
$lista_mes=implode(',',$lista_mes);
$lista_fatores=implode(',',$lista_fatores);
$lista_estrategias=implode(',',$lista_estrategias);
$lista_metas=implode(',',$lista_metas);

if ($exibir_projeto && ($lista_perspectivas || $lista_temas || $lista_objetivos || $lista_mes || $lista_fatores || $lista_estrategias || $lista_metas)){

	$sql->adTabela('projeto_gestao');
	$sql->esqUnir('projetos','projetos','projetos.projeto_id=projeto_gestao_projeto');
	$sql->adCampo('projeto_id, projeto_nome,
	projeto_gestao_tema AS projeto_tema,
	projeto_gestao_objetivo AS projeto_objetivo_estrategico,
	projeto_gestao_me,
	projeto_gestao_fator AS projeto_fator,
	projeto_gestao_estrategia AS projeto_estrategia,
	projeto_gestao_meta AS projeto_meta,
	projeto_gestao_perspectiva AS projeto_perspectiva,
	projeto_percentagem,
	projeto_portfolio');
	$sql->adOnde(
	($lista_objetivos ? 'projeto_gestao_objetivo IN ('.$lista_objetivos.')' : '').
	($lista_mes ? ($lista_objetivos ? ' OR ' : '').'projeto_gestao_me IN ('.$lista_mes.')' : '').
	($lista_fatores ? ($lista_mes || $lista_objetivos ? ' OR ' : '').'projeto_gestao_fator IN ('.$lista_fatores.')' : '').
	($lista_estrategias ? ($lista_mes || $lista_objetivos || $lista_fatores ? ' OR ' : '').'projeto_gestao_estrategia IN ('.$lista_estrategias.')' : '').
	($lista_metas ? ($lista_mes || $lista_objetivos || $lista_fatores || $lista_estrategias ? ' OR ' : '').'projeto_gestao_meta IN ('.$lista_metas.')' : '').
	($lista_temas ? ($lista_mes || $lista_metas || $lista_objetivos || $lista_fatores || $lista_estrategias ? ' OR ' : '').'projeto_gestao_tema IN ('.$lista_temas.')' : '').
	($lista_perspectivas ? ($lista_mes || $lista_metas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_temas ? ' OR ' : '').'projeto_gestao_perspectiva IN ('.$lista_perspectivas.')' : '')
	);
	if (!$pg_id) $sql->adOnde('projeto_ativo=1'); 
	$sql->adOrdem('projeto_gestao_ordem');
	$sql->adGrupo('projeto_gestao_id');
	$projetos=$sql->Lista();
	

	$sql->limpar();
	$vetor_projeto=array();
	foreach($projetos as $linha){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('projeto',$linha['projeto_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['projeto_percentagem'],'%');
		else $cor_indicador='';
		if ($linha['projeto_perspectiva'] && isset($vetor_perspectiva[$linha['projeto_perspectiva']]) && $linha['projeto_id']){
			$vetor_projeto[$linha['projeto_id']]=(isset($vetor_projeto[$linha['projeto_id']]) ? $vetor_projeto[$linha['projeto_id']]+1 : 1);
			$nodulo=$arvore->Add('p'.$linha['projeto_perspectiva'], 'j'.$linha['projeto_id'].'_'.$vetor_projeto[$linha['projeto_id']], $cor_indicador.' '.link_projeto($linha['projeto_id']),false, "projeto_p.gif");
			$nodulo->addData("id", $linha['projeto_id']);
			}
		elseif ($linha['projeto_tema'] && isset($vetor_tema[$linha['projeto_tema']])&& $linha['projeto_id']){
			for ($i=1; $i<=$vetor_tema[$linha['projeto_tema']]; $i++){
				$vetor_projeto[$linha['projeto_id']]=(isset($vetor_projeto[$linha['projeto_id']]) ? $vetor_projeto[$linha['projeto_id']]+1 : 1);
				$nodulo=$arvore->Add('t'.$linha['projeto_tema'].'_'.$i, 'j'.$linha['projeto_id'].'_'.$vetor_projeto[$linha['projeto_id']], $cor_indicador.' '.link_projeto($linha['projeto_id']),false, "projeto_p.gif");
				$nodulo->addData("id", $linha['projeto_id']);
				}
			}
		elseif ($linha['projeto_objetivo_estrategico'] && isset($vetor_objetivo[$linha['projeto_objetivo_estrategico']])&& $linha['projeto_id']){
			for ($i=1; $i<=$vetor_objetivo[$linha['projeto_objetivo_estrategico']]; $i++){
				$vetor_projeto[$linha['projeto_id']]=(isset($vetor_projeto[$linha['projeto_id']]) ? $vetor_projeto[$linha['projeto_id']]+1 : 1);
				$nodulo=$arvore->Add('o'.$linha['projeto_objetivo_estrategico'].'_'.$i, 'j'.$linha['projeto_id'].'_'.$vetor_projeto[$linha['projeto_id']], $cor_indicador.' '.link_projeto($linha['projeto_id']),false, "projeto_p.gif");
				$nodulo->addData("id", $linha['projeto_id']);
				}
			}
		elseif ($Aplic->profissional && $linha['projeto_gestao_me'] && isset($vetor_me[$linha['projeto_gestao_me']])&& $linha['projeto_id']){
			for ($i=1; $i<=$vetor_me[$linha['projeto_gestao_me']]; $i++){
				$vetor_projeto[$linha['projeto_id']]=(isset($vetor_projeto[$linha['projeto_id']]) ? $vetor_projeto[$linha['projeto_id']]+1 : 1);
				$nodulo=$arvore->Add('y'.$linha['projeto_gestao_me'].'_'.$i, 'j'.$linha['projeto_id'].'_'.$vetor_projeto[$linha['projeto_id']], $cor_indicador.' '.link_projeto($linha['projeto_id']),false, "projeto_p.gif");
				$nodulo->addData("id", $linha['projeto_id']);
				}
			}
		elseif ($linha['projeto_fator'] && isset($vetor_fator[$linha['projeto_fator']])&& $linha['projeto_id']){
			for ($i=1; $i<=$vetor_fator[$linha['projeto_fator']]; $i++){
				$vetor_projeto[$linha['projeto_id']]=(isset($vetor_projeto[$linha['projeto_id']]) ? $vetor_projeto[$linha['projeto_id']]+1 : 1);
				$nodulo=$arvore->Add('f'.$linha['projeto_fator'].'_'.$i, 'j'.$linha['projeto_id'].'_'.$vetor_projeto[$linha['projeto_id']], $cor_indicador.' '.link_projeto($linha['projeto_id']),false, "projeto_p.gif");
				$nodulo->addData("id", $linha['projeto_id']);
				}
			}
		elseif ($linha['projeto_estrategia'] && isset($vetor_estrategia[$linha['projeto_estrategia']]) && $linha['projeto_id']){
			for ($i=1; $i<=$vetor_estrategia[$linha['projeto_estrategia']]; $i++){
				$vetor_projeto[$linha['projeto_id']]=(isset($vetor_projeto[$linha['projeto_id']]) ? $vetor_projeto[$linha['projeto_id']]+1 : 1);
				$nodulo=$arvore->Add('e'.$linha['projeto_estrategia'].'_'.$i, 'j'.$linha['projeto_id'].'_'.$vetor_projeto[$linha['projeto_id']], $cor_indicador.' '.link_projeto($linha['projeto_id']),false, "projeto_p.gif");
				$nodulo->addData("id", $linha['projeto_id']);
				}
			}
		elseif ($linha['projeto_meta'] && isset($vetor_meta[$linha['projeto_meta']])&& $linha['projeto_id']){
			for ($i=1; $i<=$vetor_meta[$linha['projeto_meta']]; $i++){
				$vetor_projeto[$linha['projeto_id']]=(isset($vetor_projeto[$linha['projeto_id']]) ? $vetor_projeto[$linha['projeto_id']]+1 : 1);
				$nodulo=$arvore->Add('m'.$linha['projeto_meta'].'_'.$i, 'j'.$linha['projeto_id'].'_'.$vetor_projeto[$linha['projeto_id']], $cor_indicador.' '.link_projeto($linha['projeto_id']),false, "projeto_p.gif");
				$nodulo->addData("id", $linha['projeto_id']);
				}
			}
		}
	foreach($vetor_projeto as $chave => $valor) $lista_projetos[]=$chave;
	$lista_projetos=implode(',',$lista_projetos);
	}

if ($exibir_acao && ($lista_perspectivas || $lista_temas || $lista_objetivos || $lista_mes || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos)){
	

	$sql->adTabela('plano_acao_gestao');
	$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_gestao_acao');
	$sql->adCampo('
	plano_acao_gestao_objetivo AS plano_acao_objetivo, 
	plano_acao_gestao_me,
	plano_acao_gestao_fator AS plano_acao_fator, 
	plano_acao_gestao_estrategia AS plano_acao_estrategia, 
	plano_acao_gestao_meta AS plano_acao_meta, 
	plano_acao_gestao_projeto AS plano_acao_projeto, 
	plano_acao_gestao_tema AS plano_acao_tema, 
	plano_acao_gestao_perspectiva AS plano_acao_perspectiva, 
	plano_acao_gestao_canvas AS plano_acao_canvas');
	$sql->adGrupo('plano_acao_gestao_id');
	$sql->adOnde(
	($lista_objetivos ? 'plano_acao_gestao_objetivo IN ('.$lista_objetivos.')' : '').
	($lista_mes ? ($lista_objetivos ? ' OR ' : '').'plano_acao_gestao_me IN ('.$lista_mes.')' : '').
	($lista_fatores ? ($lista_mes || $lista_objetivos ? ' OR ' : '').'plano_acao_gestao_fator IN ('.$lista_fatores.')' : '').
	($lista_estrategias ? ($lista_mes || $lista_objetivos || $lista_fatores ? ' OR ' : '').'plano_acao_gestao_estrategia IN ('.$lista_estrategias.')' : '').
	($lista_metas ? ($lista_mes || $lista_objetivos || $lista_fatores || $lista_estrategias ? ' OR ' : '').'plano_acao_gestao_meta IN ('.$lista_metas.')' : '').
	($lista_projetos ? ($lista_mes || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas ? ' OR ' : '').'plano_acao_gestao_projeto IN ('.$lista_projetos.')' : '').
	($lista_perspectivas ? ($lista_mes || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'plano_acao_gestao_perspectiva IN ('.$lista_perspectivas.')' : '').
	($lista_temas ? ($lista_mes || $lista_perspectivas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'plano_acao_gestao_tema IN ('.$lista_temas.')' : '')
	);
	$sql->adOrdem('plano_acao_nome');

	$sql->adCampo('plano_acao_id, plano_acao_nome, plano_acao_percentagem');
	$planos_de_acao=$sql->Lista();
	$sql->limpar();
	$vetor_plano_acao=array();

	foreach($planos_de_acao as $linha){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('plano_acao',$linha['plano_acao_id'],null, $inicio, $fim);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=retorna_caixa_cor($linha['plano_acao_percentagem'],'%');
		else $cor_indicador='';
		if ($linha['plano_acao_perspectiva'] && isset($vetor_perspectiva[$linha['plano_acao_perspectiva']]) && $linha['plano_acao_id']){
			$vetor_plano_acao[$linha['plano_acao_id']]=(isset($vetor_plano_acao[$linha['plano_acao_id']]) ? $vetor_plano_acao[$linha['plano_acao_id']]+1 : 1);
			$nodulo=$arvore->Add('p'.$linha['plano_acao_perspectiva'], 'a'.$linha['plano_acao_id'].'_'.$vetor_plano_acao[$linha['plano_acao_id']], $cor_indicador.' '.link_acao($linha['plano_acao_id']),false, "plano_acao_p.gif");
			$nodulo->addData("id", $linha['plano_acao_id']);
			}
		elseif ($linha['plano_acao_tema'] && isset($vetor_tema[$linha['plano_acao_tema']]) && $linha['plano_acao_id']){
			for ($i=1; $i<=$vetor_tema[$linha['plano_acao_tema']]; $i++){
				$vetor_plano_acao[$linha['plano_acao_id']]=(isset($vetor_plano_acao[$linha['plano_acao_id']]) ? $vetor_plano_acao[$linha['plano_acao_id']]+1 : 1);
				$nodulo=$arvore->Add('t'.$linha['plano_acao_tema'].'_'.$i, 'a'.$linha['plano_acao_id'].'_'.$vetor_plano_acao[$linha['plano_acao_id']], $cor_indicador.' '.link_acao($linha['plano_acao_id']),false, "plano_acao_p.gif");
				$nodulo->addData("id", $linha['plano_acao_id']);
				}
			}
		elseif ($linha['plano_acao_objetivo'] && isset($vetor_objetivo[$linha['plano_acao_objetivo']]) && $linha['plano_acao_id']){
			for ($i=1; $i<=$vetor_objetivo[$linha['plano_acao_objetivo']]; $i++){
				$vetor_plano_acao[$linha['plano_acao_id']]=(isset($vetor_plano_acao[$linha['plano_acao_id']]) ? $vetor_plano_acao[$linha['plano_acao_id']]+1 : 1);
				$nodulo=$arvore->Add('o'.$linha['plano_acao_objetivo'].'_'.$i, 'a'.$linha['plano_acao_id'].'_'.$vetor_plano_acao[$linha['plano_acao_id']], $cor_indicador.' '.link_acao($linha['plano_acao_id']),false, "plano_acao_p.gif");
				$nodulo->addData("id", $linha['plano_acao_id']);
				}
			}
		elseif ($linha['plano_acao_gestao_me'] && isset($vetor_me[$linha['plano_acao_gestao_me']]) && $linha['plano_acao_id']){
			for ($i=1; $i<=$vetor_me[$linha['plano_acao_gestao_me']]; $i++){
				$vetor_plano_acao[$linha['plano_acao_id']]=(isset($vetor_plano_acao[$linha['plano_acao_id']]) ? $vetor_plano_acao[$linha['plano_acao_id']]+1 : 1);
				$nodulo=$arvore->Add('y'.$linha['plano_acao_gestao_me'].'_'.$i, 'a'.$linha['plano_acao_id'].'_'.$vetor_plano_acao[$linha['plano_acao_id']], $cor_indicador.' '.link_acao($linha['plano_acao_id']),false, "plano_acao_p.gif");
				$nodulo->addData("id", $linha['plano_acao_id']);
				}
			}
		elseif ($linha['plano_acao_fator'] && isset($vetor_fator[$linha['plano_acao_fator']]) && $linha['plano_acao_id']){
			for ($i=1; $i<=$vetor_fator[$linha['plano_acao_fator']]; $i++){
				$vetor_plano_acao[$linha['plano_acao_id']]=(isset($vetor_plano_acao[$linha['plano_acao_id']]) ? $vetor_plano_acao[$linha['plano_acao_id']]+1 : 1);
				$nodulo=$arvore->Add('f'.$linha['plano_acao_fator'].'_'.$i, 'a'.$linha['plano_acao_id'].'_'.$vetor_plano_acao[$linha['plano_acao_id']], $cor_indicador.' '.link_acao($linha['plano_acao_id']),false, "plano_acao_p.gif");
				$nodulo->addData("id", $linha['plano_acao_id']);
				}
			}
		elseif ($linha['plano_acao_estrategia'] && isset($vetor_estrategia[$linha['plano_acao_estrategia']]) && $linha['plano_acao_id']){
			for ($i=1; $i<=$vetor_estrategia[$linha['plano_acao_estrategia']]; $i++){
				$vetor_plano_acao[$linha['plano_acao_id']]=(isset($vetor_plano_acao[$linha['plano_acao_id']]) ? $vetor_plano_acao[$linha['plano_acao_id']]+1 : 1);
				$nodulo=$arvore->Add('e'.$linha['plano_acao_estrategia'].'_'.$i, 'a'.$linha['plano_acao_id'].'_'.$vetor_plano_acao[$linha['plano_acao_id']], $cor_indicador.' '.link_acao($linha['plano_acao_id']),false, "plano_acao_p.gif");
				$nodulo->addData("id", $linha['plano_acao_id']);
				}
			}
		elseif ($linha['plano_acao_meta'] && isset($vetor_meta[$linha['plano_acao_meta']]) && $linha['plano_acao_id']){
			for ($i=1; $i<=$vetor_meta[$linha['plano_acao_meta']]; $i++){
				$vetor_plano_acao[$linha['plano_acao_id']]=(isset($vetor_plano_acao[$linha['plano_acao_id']]) ? $vetor_plano_acao[$linha['plano_acao_id']]+1 : 1);
				$nodulo=$arvore->Add('m'.$linha['plano_acao_meta'].'_'.$i, 'a'.$linha['plano_acao_id'].'_'.$vetor_plano_acao[$linha['plano_acao_id']], $cor_indicador.' '.link_acao($linha['plano_acao_id']),false, "plano_acao_p.gif");
				$nodulo->addData("id", $linha['plano_acao_id']);
				}
			}
		elseif ($linha['plano_acao_projeto'] && isset($vetor_projeto[$linha['plano_acao_projeto']]) && $linha['plano_acao_id']){
			for ($i=1; $i<=$vetor_projeto[$linha['plano_acao_projeto']]; $i++){
				$vetor_plano_acao[$linha['plano_acao_id']]=(isset($vetor_plano_acao[$linha['plano_acao_id']]) ? $vetor_plano_acao[$linha['plano_acao_id']]+1 : 1);
				$nodulo=$arvore->Add('j'.$linha['plano_acao_projeto'].'_'.$i, 'a'.$linha['plano_acao_id'].'_'.$vetor_plano_acao[$linha['plano_acao_id']], $cor_indicador.' '.link_acao($linha['plano_acao_id']),false, "plano_acao_p.gif");
				$nodulo->addData("id", $linha['plano_acao_id']);
				}
			}
		}
	foreach($vetor_plano_acao as $chave => $valor) $lista_plano_acoes[]=$chave;
	$lista_plano_acoes=implode(',',$lista_plano_acoes);
	}


if ($exibir_indicador && ($lista_objetivos || $lista_mes || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos || $lista_plano_acoes || $lista_plano_acoes || $lista_temas)){

	$sql->adTabela('pratica_indicador_gestao');
	$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo(' pratica_indicador_id, pratica_indicador_nome');
	$sql->adCampo('
	pratica_indicador_gestao_perspectiva AS pratica_indicador_perspectiva,
	pratica_indicador_gestao_tema AS pratica_indicador_tema,
	pratica_indicador_gestao_objetivo AS pratica_indicador_objetivo_estrategico, 
	pratica_indicador_gestao_me,
	pratica_indicador_gestao_fator AS pratica_indicador_fator, 
	pratica_indicador_gestao_estrategia AS pratica_indicador_estrategia, 
	pratica_indicador_gestao_meta AS pratica_indicador_meta, 
	pratica_indicador_gestao_projeto AS pratica_indicador_projeto, 
	pratica_indicador_gestao_acao AS pratica_indicador_acao
	');
	$sql->adGrupo('pratica_indicador_gestao_id');
	$sql->adOnde(
		($lista_perspectivas ? 'pratica_indicador_gestao_perspectiva IN ('.$lista_perspectivas.')' : '').
		($lista_temas ? ($lista_perspectivas ? ' OR ' : '').'pratica_indicador_gestao_tema IN ('.$lista_temas.')' : '').
		($lista_objetivos ? ($lista_perspectivas || $lista_temas ? ' OR ' : '').'pratica_indicador_gestao_objetivo IN ('.$lista_objetivos.')' : '').
		($lista_mes ? ($lista_perspectivas || $lista_temas || $lista_objetivos ? ' OR ' : '').'pratica_indicador_gestao_me IN ('.$lista_mes.')' : '').
		($lista_fatores ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos ? ' OR ' : '').'pratica_indicador_gestao_fator IN ('.$lista_fatores.')' : '').
		($lista_estrategias ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores ? ' OR ' : '').'pratica_indicador_gestao_estrategia IN ('.$lista_estrategias.')' : '').
		($lista_metas ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias ? ' OR ' : '').'pratica_indicador_gestao_meta IN ('.$lista_metas.')' : '').
		($lista_projetos ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas ? ' OR ' : '').'pratica_indicador_gestao_projeto IN ('.$lista_projetos.')' : '').
		($lista_plano_acoes ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'pratica_indicador_gestao_acao IN ('.$lista_plano_acoes.')' : '').
		($lista_temas ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_plano_acoes || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'pratica_indicador_gestao_tema IN ('.$lista_temas.')' : '')
		);
	$sql->adOrdem('pratica_indicador_nome');

	$indicadores=$sql->Lista();
	$sql->limpar();
	

	$vetor_indicador=array();
	foreach($indicadores as $linha){
		if ($tipo_arvore=='simples') $cor_indicador=cor_indicador('indicador',$linha['pratica_indicador_id'], null, $inicio, $fim, $linha['pratica_indicador_id']);
		elseif ($tipo_arvore=='percentagem') $cor_indicador=cor_indicador('indicador',$linha['pratica_indicador_id'], null, $inicio, $fim, $linha['pratica_indicador_id']);
		else $cor_indicador='';
		if ($linha['pratica_indicador_perspectiva'] && isset($vetor_perspectiva[$linha['pratica_indicador_perspectiva']]) && $linha['pratica_indicador_id']){
			$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
			$nodulo=$arvore->Add('p'.$linha['pratica_indicador_perspectiva'], 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
			$nodulo->addData("id", $linha['pratica_indicador_id']);
			}
		elseif ($linha['pratica_indicador_tema'] && isset($vetor_tema[$linha['pratica_indicador_tema']]) && $linha['pratica_indicador_id']){
			for ($i=1; $i<=$vetor_tema[$linha['pratica_indicador_tema']]; $i++){
				$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
				$nodulo=$arvore->Add('t'.$linha['pratica_indicador_tema'].'_'.$i, 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
				$nodulo->addData("id", $linha['pratica_indicador_id']);
				}
			}	
		elseif ($linha['pratica_indicador_objetivo_estrategico'] && isset($vetor_objetivo[$linha['pratica_indicador_objetivo_estrategico']]) && $linha['pratica_indicador_id']){
			for ($i=1; $i<=$vetor_objetivo[$linha['pratica_indicador_objetivo_estrategico']]; $i++){
				$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
				$nodulo=$arvore->Add('o'.$linha['pratica_indicador_objetivo_estrategico'].'_'.$i, 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
				$nodulo->addData("id", $linha['pratica_indicador_id']);
				}
			}
		elseif ($linha['pratica_indicador_gestao_me'] && isset($vetor_me[$linha['pratica_indicador_gestao_me']]) && $linha['pratica_indicador_id']){
			for ($i=1; $i<=$vetor_me[$linha['pratica_indicador_gestao_me']]; $i++){
				$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
				$nodulo=$arvore->Add('y'.$linha['pratica_indicador_gestao_me'].'_'.$i, 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
				$nodulo->addData("id", $linha['pratica_indicador_id']);
				}
			}
		elseif ($linha['pratica_indicador_fator'] && isset($vetor_fator[$linha['pratica_indicador_fator']]) && $linha['pratica_indicador_id']){
			for ($i=1; $i<=$vetor_fator[$linha['pratica_indicador_fator']]; $i++){
				$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
				$nodulo=$arvore->Add('f'.$linha['pratica_indicador_fator'].'_'.$i, 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
				$nodulo->addData("id", $linha['pratica_indicador_id']);
				}
			}
		elseif ($linha['pratica_indicador_estrategia'] && isset($vetor_estrategia[$linha['pratica_indicador_estrategia']]) && $linha['pratica_indicador_id']){
			for ($i=1; $i<=$vetor_estrategia[$linha['pratica_indicador_estrategia']]; $i++){
				$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
				$nodulo=$arvore->Add('e'.$linha['pratica_indicador_estrategia'].'_'.$i, 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
				$nodulo->addData("id", $linha['pratica_indicador_id']);
				}
			}
		elseif ($linha['pratica_indicador_meta'] && isset($vetor_meta[$linha['pratica_indicador_meta']]) && $linha['pratica_indicador_id']){
			for ($i=1; $i<=$vetor_meta[$linha['pratica_indicador_meta']]; $i++){
				$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
				$nodulo=$arvore->Add('m'.$linha['pratica_indicador_meta'].'_'.$i, 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
				$nodulo->addData("id", $linha['pratica_indicador_id']);
				}
			}
		elseif ($linha['pratica_indicador_projeto'] && isset($vetor_projeto[$linha['pratica_indicador_projeto']]) && $linha['pratica_indicador_id']){
			for ($i=1; $i<=$vetor_projeto[$linha['pratica_indicador_projeto']]; $i++){
				$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
				$nodulo=$arvore->Add('j'.$linha['pratica_indicador_projeto'].'_'.$i, 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
				$nodulo->addData("id", $linha['pratica_indicador_id']);
				}
			}
		elseif ($linha['pratica_indicador_acao'] && isset($vetor_plano_acao[$linha['pratica_indicador_acao']]) && $linha['pratica_indicador_id']){
			for ($i=1; $i<=$vetor_plano_acao[$linha['pratica_indicador_acao']]; $i++){
				$vetor_indicador[$linha['pratica_indicador_id']]=(isset($vetor_indicador[$linha['pratica_indicador_id']]) ? $vetor_indicador[$linha['pratica_indicador_id']]+1 : 1);
				$nodulo=$arvore->Add('a'.$linha['pratica_indicador_acao'].'_'.$i, 'i'.$linha['pratica_indicador_id'].'_'.$vetor_indicador[$linha['pratica_indicador_id']], $cor_indicador.' '.link_indicador($linha['pratica_indicador_id']),false, "indicador_p.gif");
				$nodulo->addData("id", $linha['pratica_indicador_id']);
				}
			}
		}
	}
















if ($exibir_painel && ($lista_objetivos || $lista_mes || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos || $lista_plano_acoes || $lista_plano_acoes || $lista_temas)){

	$sql->adTabela('painel_gestao');
	$sql->esqUnir('painel','painel','painel_gestao_painel=painel.painel_id');
	$sql->adCampo(' painel_id, painel_nome');
	$sql->adCampo('
	painel_gestao_perspectiva AS painel_perspectiva,
	painel_gestao_tema AS painel_tema,
	painel_gestao_objetivo AS painel_objetivo_estrategico, 
	painel_gestao_me,
	painel_gestao_fator AS painel_fator, 
	painel_gestao_estrategia AS painel_estrategia, 
	painel_gestao_meta AS painel_meta, 
	painel_gestao_projeto AS painel_projeto, 
	painel_gestao_acao AS painel_acao
	');
	$sql->adGrupo('painel_gestao_id');
	$sql->adOnde(
		($lista_perspectivas ? 'painel_gestao_perspectiva IN ('.$lista_perspectivas.')' : '').
		($lista_temas ? ($lista_perspectivas ? ' OR ' : '').'painel_gestao_tema IN ('.$lista_temas.')' : '').
		($lista_objetivos ? ($lista_perspectivas || $lista_temas ? ' OR ' : '').'painel_gestao_objetivo IN ('.$lista_objetivos.')' : '').
		($lista_mes ? ($lista_perspectivas || $lista_temas || $lista_objetivos ? ' OR ' : '').'painel_gestao_me IN ('.$lista_mes.')' : '').
		($lista_fatores ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos ? ' OR ' : '').'painel_gestao_fator IN ('.$lista_fatores.')' : '').
		($lista_estrategias ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores ? ' OR ' : '').'painel_gestao_estrategia IN ('.$lista_estrategias.')' : '').
		($lista_metas ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias ? ' OR ' : '').'painel_gestao_meta IN ('.$lista_metas.')' : '').
		($lista_projetos ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas ? ' OR ' : '').'painel_gestao_projeto IN ('.$lista_projetos.')' : '').
		($lista_plano_acoes ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'painel_gestao_acao IN ('.$lista_plano_acoes.')' : '').
		($lista_temas ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_plano_acoes || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'painel_gestao_tema IN ('.$lista_temas.')' : '')
		);
	$sql->adOrdem('painel_nome');

	$paineles=$sql->Lista();
	$sql->limpar();


	$vetor_painel=array();
	foreach($paineles as $linha){
		
		if ($linha['painel_perspectiva'] && isset($vetor_perspectiva[$linha['painel_perspectiva']]) && $linha['painel_id']){
			$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
			$nodulo=$arvore->Add('p'.$linha['painel_perspectiva'], 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
			$nodulo->addData("id", $linha['painel_id']);
			}
		elseif ($linha['painel_tema'] && isset($vetor_tema[$linha['painel_tema']]) && $linha['painel_id']){
			for ($i=1; $i<=$vetor_tema[$linha['painel_tema']]; $i++){
				$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
				$nodulo=$arvore->Add('t'.$linha['painel_tema'].'_'.$i, 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
				$nodulo->addData("id", $linha['painel_id']);
				}
			}	
		elseif ($linha['painel_objetivo_estrategico'] && isset($vetor_objetivo[$linha['painel_objetivo_estrategico']]) && $linha['painel_id']){
			for ($i=1; $i<=$vetor_objetivo[$linha['painel_objetivo_estrategico']]; $i++){
				$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
				$nodulo=$arvore->Add('o'.$linha['painel_objetivo_estrategico'].'_'.$i, 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
				$nodulo->addData("id", $linha['painel_id']);
				}
			}
		elseif ($linha['painel_gestao_me'] && isset($vetor_me[$linha['painel_gestao_me']]) && $linha['painel_id']){
			for ($i=1; $i<=$vetor_me[$linha['painel_gestao_me']]; $i++){
				$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
				$nodulo=$arvore->Add('y'.$linha['painel_gestao_me'].'_'.$i, 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
				$nodulo->addData("id", $linha['painel_id']);
				}
			}
		elseif ($linha['painel_fator'] && isset($vetor_fator[$linha['painel_fator']]) && $linha['painel_id']){
			for ($i=1; $i<=$vetor_fator[$linha['painel_fator']]; $i++){
				$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
				$nodulo=$arvore->Add('f'.$linha['painel_fator'].'_'.$i, 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
				$nodulo->addData("id", $linha['painel_id']);
				}
			}
		elseif ($linha['painel_estrategia'] && isset($vetor_estrategia[$linha['painel_estrategia']]) && $linha['painel_id']){
			for ($i=1; $i<=$vetor_estrategia[$linha['painel_estrategia']]; $i++){
				$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
				$nodulo=$arvore->Add('e'.$linha['painel_estrategia'].'_'.$i, 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
				$nodulo->addData("id", $linha['painel_id']);
				}
			}
		elseif ($linha['painel_meta'] && isset($vetor_meta[$linha['painel_meta']]) && $linha['painel_id']){
			for ($i=1; $i<=$vetor_meta[$linha['painel_meta']]; $i++){
				$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
				$nodulo=$arvore->Add('m'.$linha['painel_meta'].'_'.$i, 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
				$nodulo->addData("id", $linha['painel_id']);
				}
			}
		elseif ($linha['painel_projeto'] && isset($vetor_projeto[$linha['painel_projeto']]) && $linha['painel_id']){
			for ($i=1; $i<=$vetor_projeto[$linha['painel_projeto']]; $i++){
				$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
				$nodulo=$arvore->Add('j'.$linha['painel_projeto'].'_'.$i, 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
				$nodulo->addData("id", $linha['painel_id']);
				}
			}
		elseif ($linha['painel_acao'] && isset($vetor_plano_acao[$linha['painel_acao']]) && $linha['painel_id']){
			for ($i=1; $i<=$vetor_plano_acao[$linha['painel_acao']]; $i++){
				$vetor_painel[$linha['painel_id']]=(isset($vetor_painel[$linha['painel_id']]) ? $vetor_painel[$linha['painel_id']]+1 : 1);
				$nodulo=$arvore->Add('a'.$linha['painel_acao'].'_'.$i, 'x'.$linha['painel_id'].'_'.$vetor_painel[$linha['painel_id']], ' '.link_painel($linha['painel_id'], null, null, null, null, null, null,true),false, "painel_p.png");
				$nodulo->addData("id", $linha['painel_id']);
				}
			}
		}
	}





if ($exibir_painel_composicao && ($lista_objetivos || $lista_mes || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos || $lista_plano_acoes || $lista_plano_acoes || $lista_temas)){

	$sql->adTabela('painel_composicao_gestao');
	$sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_gestao_painel_composicao=painel_composicao.painel_composicao_id');
	$sql->adCampo(' painel_composicao_id, painel_composicao_nome');
	$sql->adCampo('
	painel_composicao_gestao_perspectiva AS painel_composicao_perspectiva,
	painel_composicao_gestao_tema AS painel_composicao_tema,
	painel_composicao_gestao_objetivo AS painel_composicao_objetivo_estrategico, 
	painel_composicao_gestao_me,
	painel_composicao_gestao_fator AS painel_composicao_fator, 
	painel_composicao_gestao_estrategia AS painel_composicao_estrategia, 
	painel_composicao_gestao_meta AS painel_composicao_meta, 
	painel_composicao_gestao_projeto AS painel_composicao_projeto, 
	painel_composicao_gestao_acao AS painel_composicao_acao
	');
	$sql->adGrupo('painel_composicao_gestao_id');
	$sql->adOnde(
		($lista_perspectivas ? 'painel_composicao_gestao_perspectiva IN ('.$lista_perspectivas.')' : '').
		($lista_temas ? ($lista_perspectivas ? ' OR ' : '').'painel_composicao_gestao_tema IN ('.$lista_temas.')' : '').
		($lista_objetivos ? ($lista_perspectivas || $lista_temas ? ' OR ' : '').'painel_composicao_gestao_objetivo IN ('.$lista_objetivos.')' : '').
		($lista_mes ? ($lista_perspectivas || $lista_temas || $lista_objetivos ? ' OR ' : '').'painel_composicao_gestao_me IN ('.$lista_mes.')' : '').
		($lista_fatores ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos ? ' OR ' : '').'painel_composicao_gestao_fator IN ('.$lista_fatores.')' : '').
		($lista_estrategias ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores ? ' OR ' : '').'painel_composicao_gestao_estrategia IN ('.$lista_estrategias.')' : '').
		($lista_metas ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias ? ' OR ' : '').'painel_composicao_gestao_meta IN ('.$lista_metas.')' : '').
		($lista_projetos ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas ? ' OR ' : '').'painel_composicao_gestao_projeto IN ('.$lista_projetos.')' : '').
		($lista_plano_acoes ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'painel_composicao_gestao_acao IN ('.$lista_plano_acoes.')' : '').
		($lista_temas ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_plano_acoes || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'painel_composicao_gestao_tema IN ('.$lista_temas.')' : '')
		);
	$sql->adOrdem('painel_composicao_nome');

	$painel_composicaoes=$sql->Lista();
	$sql->limpar();


	$vetor_painel_composicao=array();
	foreach($painel_composicaoes as $linha){
		
		if ($linha['painel_composicao_perspectiva'] && isset($vetor_perspectiva[$linha['painel_composicao_perspectiva']]) && $linha['painel_composicao_id']){
			$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
			$nodulo=$arvore->Add('p'.$linha['painel_composicao_perspectiva'], 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
			$nodulo->addData("id", $linha['painel_composicao_id']);
			}
		elseif ($linha['painel_composicao_tema'] && isset($vetor_tema[$linha['painel_composicao_tema']]) && $linha['painel_composicao_id']){
			for ($i=1; $i<=$vetor_tema[$linha['painel_composicao_tema']]; $i++){
				$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
				$nodulo=$arvore->Add('t'.$linha['painel_composicao_tema'].'_'.$i, 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
				$nodulo->addData("id", $linha['painel_composicao_id']);
				}
			}	
		elseif ($linha['painel_composicao_objetivo_estrategico'] && isset($vetor_objetivo[$linha['painel_composicao_objetivo_estrategico']]) && $linha['painel_composicao_id']){
			for ($i=1; $i<=$vetor_objetivo[$linha['painel_composicao_objetivo_estrategico']]; $i++){
				$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
				$nodulo=$arvore->Add('o'.$linha['painel_composicao_objetivo_estrategico'].'_'.$i, 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
				$nodulo->addData("id", $linha['painel_composicao_id']);
				}
			}
		elseif ($linha['painel_composicao_gestao_me'] && isset($vetor_me[$linha['painel_composicao_gestao_me']]) && $linha['painel_composicao_id']){
			for ($i=1; $i<=$vetor_me[$linha['painel_composicao_gestao_me']]; $i++){
				$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
				$nodulo=$arvore->Add('y'.$linha['painel_composicao_gestao_me'].'_'.$i, 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
				$nodulo->addData("id", $linha['painel_composicao_id']);
				}
			}
		elseif ($linha['painel_composicao_fator'] && isset($vetor_fator[$linha['painel_composicao_fator']]) && $linha['painel_composicao_id']){
			for ($i=1; $i<=$vetor_fator[$linha['painel_composicao_fator']]; $i++){
				$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
				$nodulo=$arvore->Add('f'.$linha['painel_composicao_fator'].'_'.$i, 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
				$nodulo->addData("id", $linha['painel_composicao_id']);
				}
			}
		elseif ($linha['painel_composicao_estrategia'] && isset($vetor_estrategia[$linha['painel_composicao_estrategia']]) && $linha['painel_composicao_id']){
			for ($i=1; $i<=$vetor_estrategia[$linha['painel_composicao_estrategia']]; $i++){
				$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
				$nodulo=$arvore->Add('e'.$linha['painel_composicao_estrategia'].'_'.$i, 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
				$nodulo->addData("id", $linha['painel_composicao_id']);
				}
			}
		elseif ($linha['painel_composicao_meta'] && isset($vetor_meta[$linha['painel_composicao_meta']]) && $linha['painel_composicao_id']){
			for ($i=1; $i<=$vetor_meta[$linha['painel_composicao_meta']]; $i++){
				$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
				$nodulo=$arvore->Add('m'.$linha['painel_composicao_meta'].'_'.$i, 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
				$nodulo->addData("id", $linha['painel_composicao_id']);
				}
			}
		elseif ($linha['painel_composicao_projeto'] && isset($vetor_projeto[$linha['painel_composicao_projeto']]) && $linha['painel_composicao_id']){
			for ($i=1; $i<=$vetor_projeto[$linha['painel_composicao_projeto']]; $i++){
				$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
				$nodulo=$arvore->Add('j'.$linha['painel_composicao_projeto'].'_'.$i, 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
				$nodulo->addData("id", $linha['painel_composicao_id']);
				}
			}
		elseif ($linha['painel_composicao_acao'] && isset($vetor_plano_acao[$linha['painel_composicao_acao']]) && $linha['painel_composicao_id']){
			for ($i=1; $i<=$vetor_plano_acao[$linha['painel_composicao_acao']]; $i++){
				$vetor_painel_composicao[$linha['painel_composicao_id']]=(isset($vetor_painel_composicao[$linha['painel_composicao_id']]) ? $vetor_painel_composicao[$linha['painel_composicao_id']]+1 : 1);
				$nodulo=$arvore->Add('a'.$linha['painel_composicao_acao'].'_'.$i, 'w'.$linha['painel_composicao_id'].'_'.$vetor_painel_composicao[$linha['painel_composicao_id']], ' '.link_painel_composicao($linha['painel_composicao_id'], null, null, null, null, null, null,true),false, "composicao_p.gif");
				$nodulo->addData("id", $linha['painel_composicao_id']);
				}
			}
		}
	}



if ($exibir_painel_slideshow && ($lista_objetivos || $lista_mes || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos || $lista_plano_acoes || $lista_plano_acoes || $lista_temas)){

	$sql->adTabela('painel_slideshow_gestao');
	$sql->esqUnir('painel_slideshow','painel_slideshow','painel_slideshow_gestao_painel_slideshow=painel_slideshow.painel_slideshow_id');
	$sql->adCampo(' painel_slideshow_id, painel_slideshow_nome');
	$sql->adCampo('
	painel_slideshow_gestao_perspectiva AS painel_slideshow_perspectiva,
	painel_slideshow_gestao_tema AS painel_slideshow_tema,
	painel_slideshow_gestao_objetivo AS painel_slideshow_objetivo_estrategico, 
	painel_slideshow_gestao_me,
	painel_slideshow_gestao_fator AS painel_slideshow_fator, 
	painel_slideshow_gestao_estrategia AS painel_slideshow_estrategia, 
	painel_slideshow_gestao_meta AS painel_slideshow_meta, 
	painel_slideshow_gestao_projeto AS painel_slideshow_projeto, 
	painel_slideshow_gestao_acao AS painel_slideshow_acao
	');
	$sql->adGrupo('painel_slideshow_gestao_id');
	$sql->adOnde(
		($lista_perspectivas ? 'painel_slideshow_gestao_perspectiva IN ('.$lista_perspectivas.')' : '').
		($lista_temas ? ($lista_perspectivas ? ' OR ' : '').'painel_slideshow_gestao_tema IN ('.$lista_temas.')' : '').
		($lista_objetivos ? ($lista_perspectivas || $lista_temas ? ' OR ' : '').'painel_slideshow_gestao_objetivo IN ('.$lista_objetivos.')' : '').
		($lista_mes ? ($lista_perspectivas || $lista_temas || $lista_objetivos ? ' OR ' : '').'painel_slideshow_gestao_me IN ('.$lista_mes.')' : '').
		($lista_fatores ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos ? ' OR ' : '').'painel_slideshow_gestao_fator IN ('.$lista_fatores.')' : '').
		($lista_estrategias ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores ? ' OR ' : '').'painel_slideshow_gestao_estrategia IN ('.$lista_estrategias.')' : '').
		($lista_metas ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias ? ' OR ' : '').'painel_slideshow_gestao_meta IN ('.$lista_metas.')' : '').
		($lista_projetos ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas ? ' OR ' : '').'painel_slideshow_gestao_projeto IN ('.$lista_projetos.')' : '').
		($lista_plano_acoes ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'painel_slideshow_gestao_acao IN ('.$lista_plano_acoes.')' : '').
		($lista_temas ? ($lista_mes || $lista_perspectivas || $lista_temas || $lista_plano_acoes || $lista_objetivos || $lista_fatores || $lista_estrategias || $lista_metas || $lista_projetos ? ' OR ' : '').'painel_slideshow_gestao_tema IN ('.$lista_temas.')' : '')
		);
	$sql->adOrdem('painel_slideshow_nome');

	$painel_slideshowes=$sql->Lista();
	$sql->limpar();


	$vetor_painel_slideshow=array();
	foreach($painel_slideshowes as $linha){
		
		if ($linha['painel_slideshow_perspectiva'] && isset($vetor_perspectiva[$linha['painel_slideshow_perspectiva']]) && $linha['painel_slideshow_id']){
			$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
			$nodulo=$arvore->Add('p'.$linha['painel_slideshow_perspectiva'], 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
			$nodulo->addData("id", $linha['painel_slideshow_id']);
			}
		elseif ($linha['painel_slideshow_tema'] && isset($vetor_tema[$linha['painel_slideshow_tema']]) && $linha['painel_slideshow_id']){
			for ($i=1; $i<=$vetor_tema[$linha['painel_slideshow_tema']]; $i++){
				$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
				$nodulo=$arvore->Add('t'.$linha['painel_slideshow_tema'].'_'.$i, 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
				$nodulo->addData("id", $linha['painel_slideshow_id']);
				}
			}	
		elseif ($linha['painel_slideshow_objetivo_estrategico'] && isset($vetor_objetivo[$linha['painel_slideshow_objetivo_estrategico']]) && $linha['painel_slideshow_id']){
			for ($i=1; $i<=$vetor_objetivo[$linha['painel_slideshow_objetivo_estrategico']]; $i++){
				$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
				$nodulo=$arvore->Add('o'.$linha['painel_slideshow_objetivo_estrategico'].'_'.$i, 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
				$nodulo->addData("id", $linha['painel_slideshow_id']);
				}
			}
		elseif ($linha['painel_slideshow_gestao_me'] && isset($vetor_me[$linha['painel_slideshow_gestao_me']]) && $linha['painel_slideshow_id']){
			for ($i=1; $i<=$vetor_me[$linha['painel_slideshow_gestao_me']]; $i++){
				$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
				$nodulo=$arvore->Add('y'.$linha['painel_slideshow_gestao_me'].'_'.$i, 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
				$nodulo->addData("id", $linha['painel_slideshow_id']);
				}
			}
		elseif ($linha['painel_slideshow_fator'] && isset($vetor_fator[$linha['painel_slideshow_fator']]) && $linha['painel_slideshow_id']){
			for ($i=1; $i<=$vetor_fator[$linha['painel_slideshow_fator']]; $i++){
				$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
				$nodulo=$arvore->Add('f'.$linha['painel_slideshow_fator'].'_'.$i, 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
				$nodulo->addData("id", $linha['painel_slideshow_id']);
				}
			}
		elseif ($linha['painel_slideshow_estrategia'] && isset($vetor_estrategia[$linha['painel_slideshow_estrategia']]) && $linha['painel_slideshow_id']){
			for ($i=1; $i<=$vetor_estrategia[$linha['painel_slideshow_estrategia']]; $i++){
				$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
				$nodulo=$arvore->Add('e'.$linha['painel_slideshow_estrategia'].'_'.$i, 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
				$nodulo->addData("id", $linha['painel_slideshow_id']);
				}
			}
		elseif ($linha['painel_slideshow_meta'] && isset($vetor_meta[$linha['painel_slideshow_meta']]) && $linha['painel_slideshow_id']){
			for ($i=1; $i<=$vetor_meta[$linha['painel_slideshow_meta']]; $i++){
				$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
				$nodulo=$arvore->Add('m'.$linha['painel_slideshow_meta'].'_'.$i, 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
				$nodulo->addData("id", $linha['painel_slideshow_id']);
				}
			}
		elseif ($linha['painel_slideshow_projeto'] && isset($vetor_projeto[$linha['painel_slideshow_projeto']]) && $linha['painel_slideshow_id']){
			for ($i=1; $i<=$vetor_projeto[$linha['painel_slideshow_projeto']]; $i++){
				$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
				$nodulo=$arvore->Add('j'.$linha['painel_slideshow_projeto'].'_'.$i, 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
				$nodulo->addData("id", $linha['painel_slideshow_id']);
				}
			}
		elseif ($linha['painel_slideshow_acao'] && isset($vetor_plano_acao[$linha['painel_slideshow_acao']]) && $linha['painel_slideshow_id']){
			for ($i=1; $i<=$vetor_plano_acao[$linha['painel_slideshow_acao']]; $i++){
				$vetor_painel_slideshow[$linha['painel_slideshow_id']]=(isset($vetor_painel_slideshow[$linha['painel_slideshow_id']]) ? $vetor_painel_slideshow[$linha['painel_slideshow_id']]+1 : 1);
				$nodulo=$arvore->Add('a'.$linha['painel_slideshow_acao'].'_'.$i, 'z'.$linha['painel_slideshow_id'].'_'.$vetor_painel_slideshow[$linha['painel_slideshow_id']], ' '.link_painel_slideshow($linha['painel_slideshow_id'], null, null, null, null, null, null,true),false, "slideshow_p.gif");
				$nodulo->addData("id", $linha['painel_slideshow_id']);
				}
			}
		}
	}





echo '<table id="geral" width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr></table>';
echo '<script>treeview.expandAll();</script>';

if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script>self.print();</script>';


?>
<script type="text/javascript">

function exportar_link() {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link_pro&dialogo=1&tipo=arvore_gestao&cia_id='+document.getElementById('cia_id').value, null, window);
	}

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	frm_filtro.submit();
	}

function popup(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('�rvore de gest�o', 800, 500, 'm=praticas&a=arvore_gestao&dialogo=1', null, window);
	else window.open('./index.php?m=praticas&a=arvore_gestao&dialogo=1', 'arvore de gest�o','height=500,width=800,resizable,scrollbars=yes');
	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}


function nodeSelect_handle(sender,arg){
		/*
		var treenode = treeview.getNode(arg.NodeId);
		var chave=treenode.getData("id");
		var tipo1=arg.NodeId;
		var saida='';

		var tipo=tipo1.charAt(0);
		if (tipo=='p') saida='m=praticas&a=perspectiva_ver&pg_perspectiva_id='+chave;
		else if (tipo=='o') saida='m=praticas&a=obj_estrategico_ver&objetivo_id='+chave;
		else if (tipo=='f') saida='m=praticas&a=fator_ver&fator_id='+chave;
		else if (tipo=='e') saida='m=praticas&a=estrategia_ver&pg_estrategia_id='+chave;
		else if (tipo=='m') saida='m=praticas&a=meta_ver&pg_meta_id='+chave;
		else if (tipo=='y') saida='m=praticas&a=me_ver_pro&me_id='+chave;
		else if (tipo=='i') saida='m=praticas&a=indicador_ver&pratica_indicador_id='+chave;
		else if (tipo=='a') saida='m=praticas&a=plano_acao_ver&plano_acao_id='+chave;
		else if (tipo=='j') saida='m=projetos&a=ver&projeto_id='+chave;
		else if (tipo=='t') saida='m=praticas&a=tema_ver&tema_id='+chave;
		else if (tipo=='x') saida='m=praticas&a=painel_ver&painel_id='+chave;
		else if (tipo=='w') saida='m=praticas&a=painel_composicao_ver&painel_composicao_id='+chave;
		else if (tipo=='z') saida='m=praticas&a=painel_slideshow_ver&painel_slideshow_id='+chave;
		if (saida && chave > 0) url_passar(0, saida);
		*/
    }
treeview.registerEvent("OnSelect",nodeSelect_handle);

</script>
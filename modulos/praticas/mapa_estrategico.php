<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
global $dialogo;

$sql = new BDConsulta;

$percentagem=getParam($_REQUEST, 'percentagem', 0);

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));


if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : null;
if ($dept_id) $ver_subordinadas = null;

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



$por_dept=getParam($_REQUEST, 'por_dept', 0);
$por_designados=getParam($_REQUEST, 'por_designados', 0);
$por_responsavel=getParam($_REQUEST, 'por_responsavel', 0);

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


echo '<form method="post" name="frm_filtro">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="mapa_estrategico" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';

$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ?cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ?cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '?' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N?o Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ?cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '?' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ?cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ?cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ?cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '?' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N?o Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ?cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '?' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');


if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();
	
	$botoesTitulo = new CBlocoTitulo('Mapa Estrat?gico', 'mapa_estrategico.gif', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e A??es','Clique nesta barra para esconder/mostrar os filtros e as a??es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/mapa_estrategico_p.gif').'&nbsp;Filtros e A??es</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';
	
	$por_percentagem='<tr><td align="right" style="white-space: nowrap">'.dica('Percentagem', 'Marque esta op??o, caso queira visualizar a percentagem d'.$config['genero_objetivo'].'s '.$config['objetivos'].'.').'Percentagem:'.dicaF().'</td><td><input type="checkbox" name="percentagem" value="1" onchange="document.frm_filtro.submit();" '.($percentagem ? 'checked="checked"': '').' /></td></tr>';
	$filtro_por_responsavel='<tr><td align="right" style="white-space: nowrap">'.dica('Por Respons?vel', 'Marque esta op??o, caso queira visualizar '.$config['genero_usuario'].' '.$config['usuario'].' respons?vel pel'.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Por Respons?vel:'.dicaF().'</td><td><input type="checkbox" name="por_responsavel" value="1" onchange="document.frm_filtro.submit();" '.($por_responsavel ? 'checked="checked"': '').' /></td></tr>';
	$filtro_por_dept='<tr><td align="right" style="white-space: nowrap">'.dica('Por '.ucfirst($config['dept']), 'Marque esta op??o, caso queira visualizar '.$config['genero_dept'].'s '.$config['departamentos'].' envolvid'.$config['genero_dept'].'s em cada '.$config['genero_objetivo'].'.').'Por '.$config['dept'].':'.dicaF().'</td><td><input type="checkbox" name="por_dept" value="1" onchange="document.frm_filtro.submit();" '.($por_dept ? 'checked="checked"': '').' /></td></tr>';
	$por_designado='<tr><td align="right" style="white-space: nowrap">'.dica('Por Designados', 'Marque esta op??o, caso queira visualizar '.$config['genero_usuario'].'s '.$config['usuarios'].' designados para '.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Por designados:'.dicaF().'</td><td><input type="checkbox" name="por_designados" value="1" onchange="document.frm_filtro.submit();" '.($por_designados ? 'checked="checked"': '').' /></td></tr>';
	$selecao_plano='<tr><td>'.dica('Sele??o do Planejamento Estrat?gico', 'Utilize esta op??o para filtrar de qual planejamento estrat?gico ser? filtrado os dados.').'Planejamento Estrat?gico:'.dicaF().'</td><td>'.selecionaVetor($planos, 'pg_id', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $pg_id).'</td></tr>';
	$imprimir='<tr><td align="right" style="white-space: nowrap"><a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=mapa_estrategico&dialogo=1&percentagem='.$percentagem.'&cia_id='.$cia_id.'&pg_id='.$pg_id.'&por_dept='.$por_dept.'&por_designados='.$por_designados.'&por_responsavel='.$por_responsavel.'\');">'.imagem('imprimir_p.png', 'Imprimir '.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']), 'Clique neste ?cone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'.').'</a></td></tr>';
	$botao_link=($podeEditar ? '<tr><td><a href="javascript: void(0)" onclick="javascript:exportar_link();">'.imagem('icones/arvore_exporta.png', 'Exportar Link', 'Clique neste ?cone '.imagem('icones/arvore_exporta.png').' para criar um endere?o web para visualiza??o do mapa estrat?gico em ambiente externo.').'</a></td></tr>' : '');
	$saida.='<tr><td valign=top><table cellspacing=0 cellpadding=0>'.$procurar_om.$selecao_plano.$por_percentagem.$filtro_por_responsavel.$filtro_por_dept.$por_designado.'</table></td><td valign=top><table cellspacing=0 cellpadding=0>'.$imprimir.$botao_link.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();

	}
elseif (!$dialogo && !$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Mapa Estrat?gico', 'mapa_estrategico.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula(dica('Percentagem', 'Marque esta op??o, caso queira visualizar a percentagem d'.$config['genero_objetivo'].'s '.$config['objetivos'].'.').'Percentagem:'.dicaF().'<input type="checkbox" name="percentagem" value="1" onchange="document.frm_filtro.submit();" '.($percentagem ? 'checked="checked"': '').' />');
	$botoesTitulo->adicionaCelula(dica('Por Respons?vel', 'Marque esta op??o, caso queira visualizar '.$config['genero_usuario'].' '.$config['usuario'].' respons?vel pel'.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Por Respons?vel:'.dicaF().'<input type="checkbox" name="por_responsavel" value="1" onchange="document.frm_filtro.submit();" '.($por_responsavel ? 'checked="checked"': '').' />');
	$botoesTitulo->adicionaCelula(dica('Por '.ucfirst($config['dept']), 'Marque esta op??o, caso queira visualizar '.$config['genero_dept'].'s '.$config['departamentos'].' envolvid'.$config['genero_dept'].'s em cada '.$config['genero_objetivo'].'.').'Por '.$config['dept'].':'.dicaF().'<input type="checkbox" name="por_dept" value="1" onchange="document.frm_filtro.submit();" '.($por_dept ? 'checked="checked"': '').' />');
	$botoesTitulo->adicionaCelula(dica('Por Designados', 'Marque esta op??o, caso queira visualizar '.$config['genero_usuario'].'s '.$config['usuarios'].' designados para '.$config['genero_objetivo'].' '.$config['objetivo'].'.').'Por designados:'.dicaF().'<input type="checkbox" name="por_designados" value="1" onchange="document.frm_filtro.submit();" '.($por_designados ? 'checked="checked"': '').' /></form>');
	$selecao_plano='<tr><td>'.dica('Sele??o do Planejamento Estrat?gico', 'Utilize esta op??o para filtrar de qual planejamento estrat?gico ser? filtrado os dados.').'Planejamento Estrat?gico:'.dicaF().'</td><td>'.selecionaVetor($planos, 'pg_id', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $pg_id).'</td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$selecao_plano.'</table>');
	$botoesTitulo->adicionaCelula('<td align="right" style="white-space: nowrap">'.dica('Imprimir '.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']), 'Clique neste ?cone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=mapa_estrategico&dialogo=1&percentagem='.$percentagem.'&cia_id='.$cia_id.'&pg_id='.$pg_id.'&por_dept='.$por_dept.'&por_designados='.$por_designados.'&por_responsavel='.$por_responsavel.'&cia_id='.$cia_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
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
	echo 	'<font size="4"><center>Mapa Estrat?gico</center></font>';
	}	
	

echo '</form>';


$sql->adTabela('plano_gestao2');
$sql->adCampo('pg_visao_futuro, pg_principio, pg_missao, pg_missao_cor, pg_visao_futuro_cor');
$sql->adOnde('pg_id='.(int)$pg_id);
$plano_gestao=$sql->Linha();
$sql->limpar();

$sql->adTabela('plano_gestao');
$sql->adCampo('pg_inicio, pg_fim');
$sql->adOnde('pg_id='.(int)$pg_id);
$linha=$sql->Linha();
$sql->limpar();
$inicio=$linha['pg_inicio'];
$fim=$linha['pg_fim'];



$sql->adTabela('perspectivas');
$sql->adCampo('perspectivas.pg_perspectiva_id, pg_perspectiva_nome, pg_perspectiva_cor');
if ($pg_id) {
	$sql->esqUnir('plano_gestao_perspectivas', 'plano_gestao_perspectivas', 'plano_gestao_perspectivas.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adOnde('plano_gestao_perspectivas.pg_id='.$pg_id);
	}
else 	{
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
	$sql->adOnde('pg_perspectiva_ativo=1');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_perspectivas.pg_perspectiva_ordem ASC');
else $sql->adOrdem('pg_perspectiva_nome ASC');
$sql->adGrupo('perspectivas.pg_perspectiva_id');
$perspectivas=$sql->Lista();
$sql->limpar();

echo '<table width=100%>';

if ($plano_gestao['pg_missao']) {
	echo '<tr><td><table cellpadding=0 cellspacing=0 width=100% style="background-color: #'.$plano_gestao['pg_missao_cor'].'"><tr><td colspan=20><b>Miss?o</b></td></tr><tr><td width="15">&nbsp;</td><td>'.$plano_gestao['pg_missao'].'</td></tr><tr><td style="heigth:5px; font-size:5pt;">&nbsp;</td></tr></table></td></tr>';
	echo '<tr style="heigth:5px;"><td></td></tr>';
	}


if ($plano_gestao['pg_visao_futuro']) {
	echo '<tr><td><table cellpadding=0 cellspacing=0 width=100% style="background-color: #'.$plano_gestao['pg_visao_futuro_cor'].'"><tr><td colspan=20><b>Vis?o</b></td></tr><tr><td width="15">&nbsp;</td><td>'.$plano_gestao['pg_visao_futuro'].'</td></tr><tr><td style="heigth:5px; font-size:5pt;">&nbsp;</td></tr></table></td></tr>';
	echo '<tr style="heigth:5px;"><td></td></tr>';
	}

foreach($perspectivas as $perspectiva){
	echo '<tr><td><table cellpadding=0 cellspacing=0 width=100% style="background-color: #'.$perspectiva['pg_perspectiva_cor'].'">';
	echo '<tr><td colspan=20><b>'.link_perspectiva($perspectiva['pg_perspectiva_id'], true).'</b></td></tr>';
	
	$coluna=0;

	
	$sql->adTabela('tema_gestao');
	$sql->esqUnir('tema', 'tema','tema_gestao_tema=tema.tema_id');
	$sql->adCampo('tema.tema_id, tema_usuario, tema_cor');
	if ($pg_id) {
		$sql->esqUnir('plano_gestao_tema', 'plano_gestao_tema', 'plano_gestao_tema.tema_id=tema.tema_id');
		$sql->adOnde('plano_gestao_tema.pg_id='.(int)$pg_id);
		}
	else {
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
	$sql->adOnde('tema_gestao_perspectiva='.(int)$perspectiva['pg_perspectiva_id']);	
	if ($pg_id) $sql->adOrdem('plano_gestao_tema.tema_ordem ASC');
	else $sql->adOrdem('tema_nome ASC');
	$sql->adGrupo('tema_gestao_id');
	$temas=$sql->Lista();
	$sql->limpar();

	foreach($temas as $tema){
		if (!$coluna++) echo '<tr>';
		echo '<td align=center><table cellpadding=0 cellspacing=0 border=1><tr><td><table style="background-color: #'.$tema['tema_cor'].'">';
		echo '<tr><td align=center><b>'.link_tema($tema['tema_id'], true, null, null, null, null, true, null, $inicio, $fim).'</b></td></tr>';
		if ($por_responsavel){
			$saida='';
			if ($tema['tema_usuario']) $saida.=link_usuario($tema['tema_usuario']);
			if ($saida) echo '<tr><td align=center>'.$saida.'</td></tr>';
			}
		if ($por_dept){
			$sql->adTabela('tema_depts');
			$sql->adCampo('dept_id');
			$sql->adOnde('tema_id='.(int)$tema['tema_id']);
			$depts=$sql->Lista();
			$sql->limpar();
			$saida='';
			$qnt=count($depts);
			$qnt_inserida=0;
			foreach($depts as $dept) {
				$saida.=($qnt_inserida++ && $qnt_inserida!=$qnt ? ', ': '').($qnt_inserida==$qnt && $qnt>1 ? ' e ': '').link_dept($dept['dept_id']);
				}
			if ($saida) echo '<tr><td align=center>'.$saida.'</td></tr>';
			}
		if ($por_designados){
			$sql->adTabela('tema_usuarios');
			$sql->adCampo('usuario_id');
			$sql->adOnde('tema_id='.(int)$tema['tema_id']);
			$usuarios=$sql->Lista();
			$sql->limpar();
			$saida='';
			$qnt=count($usuarios);
			$qnt_inserida=0;
			foreach($usuarios as $usuario) {
				$saida.=($qnt_inserida++ && $qnt_inserida!=$qnt ? ', ': '').($qnt_inserida==$qnt && $qnt>1 ? ' e ': '').link_usuario($usuario['usuario_id']);
				}
			if ($saida) echo '<tr><td align=center>'.$saida.'</td></tr>';
			}
			
		$sql->adTabela('objetivo_gestao');
		$sql->esqUnir('objetivo', 'objetivo','objetivo_gestao_objetivo=objetivo.objetivo_id');
		$sql->adCampo('objetivo.objetivo_id, objetivo_usuario, objetivo_percentagem');
		if ($pg_id) {
			$sql->esqUnir('plano_gestao_objetivo','plano_gestao_objetivo','plano_gestao_objetivo_objetivo=objetivo.objetivo_id');
			$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
			}
		else {
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
		$sql->adOnde('objetivo_gestao_tema='.(int)$tema['tema_id']);	
		if ($pg_id) $sql->adOrdem('plano_gestao_objetivo_ordem ASC');
		else $sql->adOrdem('objetivo_nome ASC');
		$sql->adGrupo('objetivo_gestao_id');
		$objetivos=$sql->Lista();
		$sql->limpar();
		
		$saida='';
		$qnt=count($objetivos);
		$qnt_inserida=0;
		foreach($objetivos as $objetivo) {
			$saida.=($qnt_inserida++ ? '<br>': '').link_objetivo($objetivo['objetivo_id'],$tema['tema_cor'],null,null,null,null,true, null, $inicio, $fim).($percentagem ? ' '.(int)$objetivo['objetivo_percentagem'].'%' : '');
			}
		if ($saida) echo '<tr><td align=left>'.$saida.'</td></tr>';	
			
			
		echo '</table></td></tr></table></td>';
		if ($coluna==1 || $coluna==2) echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		if ($coluna==3) {
			echo '</tr><tr><td style="heigth:5px; font-size:5pt;">&nbsp;</td></tr>';
			$coluna=0;
			}
		}
	

	$sql->adTabela('objetivo_gestao');
	$sql->esqUnir('objetivo', 'objetivo','objetivo_gestao_objetivo=objetivo.objetivo_id');
	$sql->adCampo('objetivo.objetivo_id, objetivo_usuario, objetivo_percentagem');
	if ($pg_id) {
		$sql->esqUnir('plano_gestao_objetivo','plano_gestao_objetivo','plano_gestao_objetivo_objetivo=objetivo.objetivo_id');
		$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
		}
	else 	{
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
	$sql->adOnde('objetivo_gestao_perspectiva='.(int)$perspectiva['pg_perspectiva_id']);	
	if ($pg_id) $sql->adOrdem('plano_gestao_objetivo_ordem ASC');
	else $sql->adOrdem('objetivo_nome ASC');
	$sql->adGrupo('objetivo_gestao_id');
	$objetivos=$sql->Lista();
	$sql->limpar();

	foreach($objetivos as $objetivo){
		if (!$coluna++) echo '<tr>';
		echo '<td align=center><table cellpadding=0 cellspacing=0 border=1><tr><td><table>';
		echo '<tr><td>'.link_objetivo($objetivo['objetivo_id'],$perspectiva['pg_perspectiva_cor'],'','','','',true, null, $inicio, $fim).($percentagem ? ' '.(int)$objetivo['objetivo_percentagem'].'%' : '').'</td></tr>';
		if ($por_responsavel){
			$saida='';
			if ($objetivo['objetivo_usuario']) $saida.=link_usuario($objetivo['objetivo_usuario']);
			if ($saida) echo '<tr><td align=center>'.$saida.'</td></tr>';
			}
		if ($por_dept){
			$sql->adTabela('objetivo_dept');
			$sql->adCampo('objetivo_dept_dept');
			$sql->adOnde('objetivo_dept_objetivo='.(int)$objetivo['objetivo_id']);
			$depts=$sql->Lista();
			$sql->limpar();
			$saida='';
			$qnt=count($depts);
			$qnt_inserida=0;
			foreach($depts as $dept) {
				$saida.=($qnt_inserida++ && $qnt_inserida!=$qnt ? ', ': '').($qnt_inserida==$qnt && $qnt>1 ? ' e ': '').link_dept($dept['objetivo_dept_dept']);
				}
			if ($saida) echo '<tr><td align=center>'.$saida.'</td></tr>';
			}
		if ($por_designados){
			$sql->adTabela('objetivo_usuario');
			$sql->adCampo('objetivo_usuario_usuario');
			$sql->adOnde('objetivo_usuario_objetivo='.(int)$objetivo['objetivo_id']);
			$usuarios=$sql->carregarColuna();
			$sql->limpar();
			$saida='';
			$qnt=count($usuarios);
			$qnt_inserida=0;
			foreach($usuarios as $chave => $usuario) {
				$saida.=($qnt_inserida++ && $qnt_inserida!=$qnt ? ', ': '').($qnt_inserida==$qnt && $qnt>1 ? ' e ': '').link_usuario($usuario);
				}
			if ($saida) echo '<tr><td align=center>'.$saida.'</td></tr>';
			}
		echo '</table></td></tr></table></td>';
		if ($coluna==1 || $coluna==2) echo '<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		if ($coluna==3) {
			echo '</tr><tr><td style="heigth:5px; font-size:5pt;">&nbsp;</td></tr>';
			$coluna=0;
			}
		}
		
		
	echo '<tr><td style="heigth:5px; font-size:5pt;">&nbsp;</td></tr>';
	
	
	echo '</table></td></tr>';
	echo '<tr style="heigth:5px;"><td></td></tr>';
	}
	
if ($plano_gestao['pg_principio']) echo '<tr><td><table cellpadding=0 cellspacing=0 width=100% style="background-color: #99e5b2"><tr><td colspan=20><b>Valores Organizacionais</b></td></tr><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;</td><td>'.$plano_gestao['pg_principio'].'</td></tr><tr><td style="heigth:5px; font-size:5pt;">&nbsp;</td></tr></table></td></tr>';

	
echo '</table>';



if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script>self.print();</script>';
?>
<script type="text/javascript">

function exportar_link() {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link_pro&dialogo=1&tipo=generico&cia_id='+document.getElementById('cia_id').value, null, window);
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

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
	
function carregar(pagina){
	frm_filtro.gestao_pagina.value=pagina;
	frm_filtro.submit();
	}	
</script>
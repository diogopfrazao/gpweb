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


$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['pg_id'])) $Aplic->setEstado('pg_id', getParam($_REQUEST, 'pg_id', null));
$pg_id = ($Aplic->getEstado('pg_id') !== null ? $Aplic->getEstado('pg_id') :  null);

if (isset($_REQUEST['por_dept'])) $Aplic->setEstado('por_dept', getParam($_REQUEST, 'por_dept', null));
$por_dept = ($Aplic->getEstado('por_dept') !== null ? $Aplic->getEstado('por_dept') : 0);

if (isset($_REQUEST['por_designados'])) $Aplic->setEstado('por_designados', getParam($_REQUEST, 'por_designados', null));
$por_designados = ($Aplic->getEstado('por_designados') !== null ? $Aplic->getEstado('por_designados') : 0);

if (isset($_REQUEST['por_responsavel'])) $Aplic->setEstado('por_responsavel', getParam($_REQUEST, 'por_responsavel', null));
$por_responsavel = ($Aplic->getEstado('por_responsavel') !== null ? $Aplic->getEstado('por_responsavel') : 0);

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


$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ?cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=1; document.frm_filtro.dept_id.value=\'\';  document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ?cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '?' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_subordinadas.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N?o Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ?cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? '?' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ?cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ?cone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=1; document.frm_filtro.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ?cone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '?' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.frm_filtro.ver_dept_subordinados.value=0; document.frm_filtro.submit();">'.imagem('icones/nao_sub_om.gif','N?o Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ?cone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? '?' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');

$selecao_plano='<tr><td>'.dica('Sele??o do Planejamento Estrat?gico', 'Utilize esta op??o para filtrar de qual planejamento estrat?gico ser? filtrado os dados.').'Planejamento Estrat?gico:'.dicaF().'</td><td>'.selecionaVetor($planos, 'pg_id', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $pg_id).'</td></tr>';


echo '<form method="post" name="frm_filtro">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="projetos_por_obj_estrategicos" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';

if (!$dialogo && $Aplic->profissional){
	$Aplic->salvarPosicao();
	
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['projetos']).' por '.ucfirst($config['objetivos']), 'mapa_estrategico.gif', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e A??es','Clique nesta barra para esconder/mostrar os filtros e as a??es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/mapa_estrategico_p.gif').'&nbsp;Filtros e A??es</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

		
	$selecao_plano='<tr><td>'.dica('Sele??o do Planejamento Estrat?gico', 'Utilize esta op??o para filtrar de qual planejamento estrat?gico ser? filtrado os dados.').'Planejamento Estrat?gico:'.dicaF().'</td><td>'.selecionaVetor($planos, 'pg_id', 'onchange="document.frm_filtro.submit()" class="texto" style="width:250px;"', $pg_id).'</td></tr>';

	$imprimir='<tr><td align="right" style="white-space: nowrap"><a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=projetos_por_obj_estrategicos&dialogo=1\');">'.imagem('imprimir_p.png', 'Imprimir '.$config['genero_plano_gestao'].' '.ucfirst($config['plano_gestao']), 'Clique neste ?cone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_plano_gestao'].' '.$config['plano_gestao'].'.').'</a></td></tr>';

	$saida.='<tr><td valign=top><table cellspacing=0 cellpadding=0>'.$procurar_om.$selecao_plano.'</table></td><td valign=top><table cellspacing=0 cellpadding=0>'.$imprimir.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();

	}
elseif (!$dialogo && !$Aplic->profissional){
	
	$botoesTitulo = new CBlocoTitulo(ucfirst($config['projetos']).' por '.ucfirst($config['objetivos']), 'mapa_estrategico.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$selecao_plano.'</table>');
	$botoesTitulo->adicionaCelula('<td align="right" style="white-space: nowrap">'.dica('Imprimir '.ucfirst($config['projetos']).' por '.ucfirst($config['objetivos']), 'Clique neste ?cone '.imagem('imprimir_p.png').' para imprimir '.$config['projetos'].' por '.$config['objetivos'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=praticas&a=projetos_por_obj_estrategicos&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	}
	
	
	
	
	
echo '</form>'; 

$sql->adTabela('plano_gestao2');
$sql->adCampo('pg_visao_futuro, pg_principio');
$sql->adOnde('pg_id='.(int)$pg_id);
$plano_gestao=$sql->Linha();
$sql->limpar();



$sql->adTabela('perspectivas');
$sql->adCampo('perspectivas.pg_perspectiva_id, pg_perspectiva_nome, pg_perspectiva_cor');

if ($pg_id) {
	$sql->esqUnir('plano_gestao_perspectivas', 'plano_gestao_perspectivas', 'plano_gestao_perspectivas.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
	$sql->adOnde('plano_gestao_perspectivas.pg_id='.(int)$pg_id);
	}
else 	{
	$sql->adOnde('pg_perspectiva_cia='.(int)$cia_id);
	$sql->adOnde('pg_perspectiva_ativo=1');
	if ($dept_id) $sql->adOnde('pg_perspectiva_dept='.(int)$dept_id);	
	else $sql->adOnde('pg_perspectiva_dept=0 OR pg_perspectiva_dept IS NULL');
	}
if ($pg_id) $sql->adOrdem('plano_gestao_perspectivas.pg_perspectiva_ordem ASC');
else $sql->adOrdem('pg_perspectiva_nome ASC');
$sql->adGrupo('perspectivas.pg_perspectiva_id');
$perspectivas=$sql->Lista();
$sql->limpar();

echo '<table width=100% cellpadding=0 cellspacing=0 class="tbl1"><tr><th>'.ucfirst($config['objetivos']).'</th><th>'.ucfirst($config['projetos']).'</th><th>Indicadores</th></tr>';

foreach($perspectivas as $perspectiva){
	echo '<tr><td colspan=20 style="background-color: #'.$perspectiva['pg_perspectiva_cor'].'"><b>'.$perspectiva['pg_perspectiva_nome'].'</b></td></tr>';
	
	$sql->adTabela('objetivo');
	$sql->esqUnir('objetivo_gestao', 'objetivo_gestao','objetivo_gestao_objetivo=objetivo.objetivo_id');
	if ($pg_id) {
		$sql->esqUnir('plano_gestao_objetivo', 'plano_gestao_objetivo', 'plano_gestao_objetivo_objetivo=objetivo.objetivo_id');
		$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
		}
	else {
		$sql->adOnde('objetivo_ativo=1');
		$sql->adOnde('objetivo_cia='.(int)$cia_id);
		if ($dept_id) $sql->adOnde('objetivo_dept='.(int)$dept_id);	
		else $sql->adOnde('objetivo_dept=0 OR objetivo_dept IS NULL');
		}
	$sql->adCampo('objetivo.objetivo_id, objetivo_nome, objetivo_usuario');
	$sql->adOnde('objetivo_gestao_perspectiva='.(int)$perspectiva['pg_perspectiva_id']);
	if ($pg_id) $sql->adOrdem('plano_gestao_objetivo_ordem ASC');
	else $sql->adOrdem('objetivo_nome ASC');
	$sql->adGrupo('objetivo.objetivo_id');
	$objetivos=$sql->Lista();
	$sql->limpar();
	$qnt=0;
	$saida='';
	foreach($objetivos as $objetivo){
		echo '<tr><td>'.$objetivo['objetivo_nome'].'</td>';
		$sql->adTabela('projetos');
		if ($Aplic->profissional) $sql->esqUnir('projeto_gestao', 'projeto_gestao','projeto_gestao_projeto=projeto_id');
		$sql->adCampo('DISTINCT projeto_id');
		if ($Aplic->profissional) $sql->adOnde('projeto_gestao_objetivo='.(int)$objetivo['objetivo_id']);
		else $sql->adOnde('projeto_objetivo_estrategico='.(int)$objetivo['objetivo_id']);
		$projetos=$sql->carregarColuna();
		$sql->limpar();
		$saida='';
		foreach($projetos as $projeto) $saida.=($qnt++ ? '<br>' : '').link_projeto($projeto);
		echo '<td align=center><a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_'.$objetivo['objetivo_id'].'\');">'.count($projetos).'</a>'.dicaF(). '<span style="display: none" id="lista_'.$objetivo['objetivo_id'].'"><br>'.$saida.'</span></td>';
		
		$sql->adTabela('pratica_indicador');
		if ($Aplic->profissional) $sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador_id');
		$sql->adCampo('DISTINCT pratica_indicador_id');
		if ($Aplic->profissional) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$objetivo['objetivo_id']);
		else $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$objetivo['objetivo_id']);
		$indicadores=$sql->carregarColuna();
		$sql->limpar();
		$saida='';
		foreach($indicadores as $indicador) $saida.=($qnt++ ? '<br>' : '').link_indicador($indicador);
		echo '<td align=center><a href="javascript: void(0);" onclick="expandir_colapsar(\'indicador_'.$objetivo['objetivo_id'].'\');">'.count($indicadores).'</a>'.dicaF(). '<span style="display: none" id="indicador_'.$objetivo['objetivo_id'].'"><br>'.$saida.'</span></td>';
		echo '</tr>';
		}
		

	//objetivos dentro de temas
	$sql->adTabela('objetivo');
	$sql->esqUnir('objetivo_gestao', 'objetivo_gestao','objetivo_gestao_objetivo=objetivo.objetivo_id');
	$sql->esqUnir('tema', 'tema', 'tema_id=objetivo_gestao_tema');
	$sql->esqUnir('tema_gestao', 'tema_gestao','tema_gestao_tema=tema.tema_id');
	if ($pg_id) {
		$sql->esqUnir('plano_gestao_objetivo', 'plano_gestao_objetivo', 'plano_gestao_objetivo_objetivo=objetivo.objetivo_id');
		$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
		}
	else {
		$sql->adOnde('objetivo_ativo=1');
		$sql->adOnde('objetivo_cia='.(int)$cia_id);
		if ($dept_id) $sql->adOnde('objetivo_dept='.(int)$dept_id);	
		else $sql->adOnde('objetivo_dept=0 OR objetivo_dept IS NULL');
		}
	$sql->adCampo('objetivo.objetivo_id, objetivo_nome, objetivo_usuario');
	$sql->adOnde('tema_gestao_perspectiva='.(int)$perspectiva['pg_perspectiva_id']);
	if ($pg_id) $sql->adOrdem('plano_gestao_objetivo_ordem ASC');
	else $sql->adOrdem('objetivo_nome ASC');
	$sql->adGrupo('objetivo.objetivo_id');
	$objetivos=$sql->Lista();
	$sql->limpar();
	$qnt=0;
	$saida='';
	foreach($objetivos as $objetivo){
		echo '<tr><td>'.$objetivo['objetivo_nome'].'</td>';
		$sql->adTabela('projetos');
		if ($Aplic->profissional) $sql->esqUnir('projeto_gestao', 'projeto_gestao','projeto_gestao_projeto=projeto_id');
		$sql->adCampo('DISTINCT projeto_id');
		if ($Aplic->profissional) $sql->adOnde('projeto_gestao_objetivo='.(int)$objetivo['objetivo_id']);
		else $sql->adOnde('projeto_objetivo_estrategico='.(int)$objetivo['objetivo_id']);
		$projetos=$sql->carregarColuna();
		$sql->limpar();
		
		$saida='';
		foreach($projetos as $projeto) $saida.=($qnt++ ? '<br>' : '').link_projeto($projeto);
		echo '<td align=center><a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_'.$objetivo['objetivo_id'].'\');">'.count($projetos).'</a>'.dicaF(). '<span style="display: none" id="lista_'.$objetivo['objetivo_id'].'"><br>'.$saida.'</span></td>';
		
		$sql->adTabela('pratica_indicador');
		if ($Aplic->profissional) $sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador_id');
		$sql->adCampo('DISTINCT pratica_indicador_id');
		if ($Aplic->profissional) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$objetivo['objetivo_id']);
		else $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$objetivo['objetivo_id']);
		$indicadores=$sql->carregarColuna();
		$sql->limpar();
		$saida='';
		foreach($indicadores as $indicador) $saida.=($qnt++ ? '<br>' : '').link_indicador($indicador);
		echo '<td align=center><a href="javascript: void(0);" onclick="expandir_colapsar(\'indicador_'.$objetivo['objetivo_id'].'\');">'.count($indicadores).'</a>'.dicaF(). '<span style="display: none" id="indicador_'.$objetivo['objetivo_id'].'"><br>'.$saida.'</span></td>';
		echo '</tr>';
		}	
		
		
	}
if (!count($perspectivas)) echo '<tr><td colspan=20>Nenhum'.($config['genero_projeto']=='a' ? 'a' : '').' encontrad'.$config['genero_projeto'].'</td></tr>';	
echo '</table>';



if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';
?>
<script type="text/javascript">
	
function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	document.frm_filtro.submit();
	}	
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
	
function carregar(pagina){
	frm_filtro.gestao_pagina.value=pagina;
	frm_filtro.submit();
	}	
</script>
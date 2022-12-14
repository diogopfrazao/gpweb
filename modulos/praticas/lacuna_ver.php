<?php 
/*
Copyright [2008] - S?rgio Fernandes Reinert de Lima
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

$indicador_lacuna_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if (isset($_REQUEST['indicador_lacuna_id'])) $Aplic->setEstado('indicador_lacuna_id', getParam($_REQUEST, 'indicador_lacuna_id', null));
$indicador_lacuna_id = ($Aplic->getEstado('indicador_lacuna_id') !== null ? $Aplic->getEstado('indicador_lacuna_id') : null);

$msg = '';

if (isset($_REQUEST['tab'])) $Aplic->setEstado('LacunaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('LacunaVerTab') !== null ? $Aplic->getEstado('LacunaVerTab') : 0;


if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$obj = new CLacuna();
$obj->load($indicador_lacuna_id);

$sql = new BDConsulta;
$sql->adTabela('indicador_lacuna_nos_marcadores');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('indicador_lacuna_id='.(int)$indicador_lacuna_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();


$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);
asort($anos);

if (isset($_REQUEST['IdxIndicadorAno'])) $Aplic->setEstado('IdxIndicadorAno', getParam($_REQUEST, 'IdxIndicadorAno', null));
$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null && isset($anos[$Aplic->getEstado('IdxIndicadorAno')]) ? $Aplic->getEstado('IdxIndicadorAno') : $ultimo_ano);


if(!(permiteAcessarLacuna($obj->indicador_lacuna_acesso,$indicador_lacuna_id) && $Aplic->checarModulo('praticas', 'acesso', $Aplic->usuario_id, 'lacuna_indicador'))) $Aplic->redirecionar('m=publico&a=acesso_negado');

$podeEditar=$Aplic->checarModulo('praticas', 'editar', $Aplic->usuario_id, 'lacuna_indicador');
$podeAdicionar=$Aplic->checarModulo('praticas', 'adicionar', $Aplic->usuario_id, 'lacuna_indicador');
$podeExcluir=$Aplic->checarModulo('praticas', 'excluir', $Aplic->usuario_id, 'lacuna_indicador');

$editar=permiteEditarLacuna($obj->indicador_lacuna_acesso,$indicador_lacuna_id);

$podeEditarTudo=($obj->indicador_lacuna_acesso>=5 ? $editar && (in_array($obj->indicador_lacuna_responsavel, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);



if (!$dialogo) $Aplic->salvarPosicao();

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="lacuna_ver" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '<input type="hidden" name="indicador_lacuna_id" id="indicador_lacuna_id" value="'.$indicador_lacuna_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';

if (!$dialogo){	
	$botoesTitulo = new CBlocoTitulo('Detalhes da Lacuna de Indicador', 'lacuna.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0><tr><td align="right" style="white-space: nowrap">'.dica('Sele??o de Pauta de Pontua??o', 'Utilize esta op??o para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontua??o de sua prefer?ncia.').'Pauta:'.dicaF().'</td><td>'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="mudar_pauta();" class="texto"', $pratica_modelo_id).'</td></tr><tr><td align=right>'.dica('Sele??o do Ano', 'Utilize esta op??o para visualizar os dados do indicador inseridos no ano selecionado.').'Ano:'.dicaF().'</td><td>'.selecionaVetor($anos, 'IdxLacunaAno', 'onchange="mudar_pauta()" class="texto"', $ano).'</td></tr></table>');

	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de op??es de visualiza??o').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_links",dica('Lista de Lacunas de Indicadores','Visualizar a lista de todos as lacunas de indicadores.').'Lista de Lacunas de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_lista\");");

	if ($podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de op??es').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar)	$km->Add("inserir","inserir_objeto",dica('Nova Lacuna de Indicador', 'Criar uma nova lacuna de indicador.').'Nova Lacuna de Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_editar\");");
	
	$km->Add("root","acao",dica('A??o','Menu de a??es.').'A??o'.dicaF(), "javascript: void(0);'");
	if ($podeEditarTudo && $podeEditar) $km->Add("acao","acao_editar",dica('Editar Lacuna de Indicador','Editar os detalhes desta lacuna de indicador.').'Editar Lacuna de Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=lacuna_editar&indicador_lacuna_id=".(int)$indicador_lacuna_id."\");");
	if ($podeEditarTudo && $podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir Lacuna de Indicador','Excluir esta lacuna de indicador.').'Excluir Lacuna de Indicador'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ?cone '.imagem('imprimir_p.png').' para visualizar as op??es de relat?rios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes', 'Imprimir os detalhes deste link.').' Detalhes'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&u=".$u."&a=".$a."&dialogo=1&indicador_lacuna_id=".$indicador_lacuna_id."\");");
	if ($Aplic->profissional && $podeEditarTudo && $podeEditar) $km->Add("acao","acao_exportar",dica('Exportar Link', 'Clique neste ?cone '.imagem('icones/exporta_p.png').' para criar um endere?o web para visualiza??o em ambiente externo.').imagem('icones/exporta_p.png').' Exportar Link'.dicaF(), "javascript: void(0);' onclick='exportar_link();");	
	echo $km->Render();
	echo '</td></tr></table>';
	}	
else {
	echo '<input type="hidden" name="IdxLacunaAno" id="IdxLacunaAno" value="'.$ano.'" />';
	echo '<input type="hidden" name="pratica_modelo_id" id="pratica_modelo_id" value="'.$pratica_modelo_id.'" />';
	}	
	
	
if($dialogo && $Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $obj->indicador_lacuna_cia;
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
	echo 	'<font size="4"><center>Lacuna de Indicador</center></font>';
	}	
	
	
echo '<table cellpadding=0 cellspacing=1 width="100%" '.(!$dialogo ? 'class="std"' : '').'>';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->indicador_lacuna_cor.'" colspan="2"><font color="'.melhorCor($obj->indicador_lacuna_cor).'"><b>'.$obj->indicador_lacuna_nome.'<b></font></td></tr>';


$sql->adTabela('indicador_lacuna_usuarios', 'indicador_lacuna_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=indicador_lacuna_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, contato_dept');
$sql->adOnde('indicador_lacuna_id = '.(int)$indicador_lacuna_id);
$designados = $sql->Lista();
$sql->limpar();

$saida_quem='';

$sql->adTabela('indicador_lacuna_depts');
$sql->adCampo('indicador_lacuna_depts.dept_id');
$sql->adOnde('indicador_lacuna_id = '.(int)$indicador_lacuna_id);
$departamentos = $sql->Lista();
$saida_depts='';

if ($obj->indicador_lacuna_cia) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' respons?vel.').ucfirst($config['organizacao']).':'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->indicador_lacuna_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('indicador_lacuna_cia');
	$sql->adCampo('indicador_lacuna_cia_cia');
	$sql->adOnde('indicador_lacuna_cia_indicador_lacuna = '.(int)$indicador_lacuna_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est?o envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}


if ($obj->indicador_lacuna_dept) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Respons?vel', ucfirst($config['genero_dept']).' '.$config['departamento'].' respons?vel.').ucfirst($config['departamento']).' respons?vel:'.dicaF().'</td><td class="realce" width="100%">'.link_dept($obj->indicador_lacuna_dept).'</td></tr>';
$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= link_dept($departamentos[0]['dept_id']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($departamentos[$i]['dept_id']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Qual '.strtolower($config['departamento']).' est? envolvid'.$config['genero_dept'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->indicador_lacuna_responsavel) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Respons?vel', ucfirst($config['usuario']).' respons?vel por gerenciar.').'Respons?vel:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->indicador_lacuna_responsavel, '','','esquerda').'</td></tr>';		

$saida_quem='';
if ($designados && count($designados)) {
		$saida_quem.= link_usuario($designados[0]['usuario_id'], '','','esquerda').($designados[0]['contato_dept']? ' - '.link_dept($designados[0]['contato_dept']) : '');
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'], '','','esquerda').($designados[$i]['contato_dept']? ' - '.link_dept($designados[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="designados"><br>'.$lista.'</span>';
				}
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' est?o envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';


if ($obj->indicador_lacuna_descricao) echo '<tr><td align="right" width=100 style="white-space: nowrap">'.dica('Descri??o', 'Descri??o do indicador.').'Descri??o:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->indicador_lacuna_descricao.'</td></tr>';

echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('N?vel de Acesso', 'Os indicadores podem ter cinco n?veis de acesso:<ul><li><b>P?blico</b> - Todos podem ver e editar o indicador.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o respons?vel e os designados para o indicador podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons?vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o respons?vel pode editar.</li><li><b>Participante I</b> - Somente o respons?vel e os designados para o indicador podem ver e editar o mesmo</li><li><b>Participantes II</b> - Somente o respons?vel e os designados podem ver e apenas o respons?vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o respons?vel e os designados para o indicador podem ver o mesmo, e o respons?vel editar.</li></ul>').'N?vel de acesso:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$indicador_lacuna_acesso[$obj->indicador_lacuna_acesso].'</td></tr>';

echo '<tr><td colspan=2><div id="combo_pauta"></div></td></tr>';

echo '</table></td></tr></table>';
if (!$dialogo) echo estiloFundoCaixa();
else if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';

?>

<script type="text/javascript">

function exportar_link() {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link_pro&dialogo=1&tipo=generico', null, window);
	}	

var pauta_atual=document.getElementById('pratica_modelo_id').value; 

function mudar_pauta(){
	xajax_mudar_pauta(document.getElementById('indicador_lacuna_id').value, document.getElementById('pratica_modelo_id').value, document.getElementById('IdxLacunaAno').value);
	}
	

function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta lacuna de indicador?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='lacuna_fazer_sql';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
mudar_pauta();
	
</script>
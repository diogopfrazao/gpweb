<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


if (isset($_REQUEST['calendario_id'])) $Aplic->setEstado('calendario_id', getParam($_REQUEST, 'calendario_id', null), $m, $a, $u);
$calendario_id = $Aplic->getEstado('calendario_id', null, $m, $a, $u);

if (isset($_REQUEST['tab'])) $Aplic->setEstado('tab', getParam($_REQUEST, 'tab', null), $m, $a, $u);
$tab = $Aplic->getEstado('tab', 0, $m, $a, $u);


require_once (BASE_DIR.'/modulos/sistema/calendario/calendario.class.php');
$obj= new CCalendario();
$obj->load($calendario_id);

$sql = new BDConsulta;



if (!permiteAcessarCalendario($obj->calendario_acesso,$calendario_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$msg = '';
$editar=(($podeEditar || $Aplic->usuario_super_admin || $Aplic->usuario_admin) && permiteEditarCalendario($obj->calendario_acesso,$calendario_id));

$podeEditarTudo=($obj->calendario_acesso>=5 ? $editar && (in_array($obj->calendario_usuario, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);

	
echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="calendario_id" value="'.$calendario_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '</form>';



if (!$dialogo){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes da Agenda Coletiva'.'', 'agenda.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de op��es de visualiza��o').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Agenda Coletiva','Clique neste bot�o para visualizar a lista de agenda coletiva.').'Lista de Agenda Coletiva'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=calendario&a=calendario_lista\");");
	
	if (($podeEditar && $editar) || $podeAdicionar)	$km->Add("root","inserir",dica('Inserir','Menu de op��es').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($podeAdicionar)	$km->Add("inserir","inserir_objeto",dica('Nova Agenda Coletiva', 'Criar um nova agenda coletiva.').'Nova Agenda Coletiva'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=calendario&a=calendario_editar\");");
	if ($podeEditar && $editar) {
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorr�ncia','Inserir um novo registro de ocorr�ncia.').'Registro de Ocorr�ncia'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar&calendario_id=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_calendario=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_calendario=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_calendario=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo F�rum', 'Inserir um novo f�rum relacionado.').'F�rum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_calendario=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_calendario=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_calendario=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_calendario=".$calendario_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem&msg_calendario=".$calendario_id."\");");
		if ($config['doc_interno'] && $Aplic->checarModulo('email', 'adicionar', $Aplic->usuario_id, 'criar_modelo')){
			$sql->adTabela('modelos_tipo');
			$sql->esqUnir('modelo_cia', 'modelo_cia', 'modelo_cia_tipo=modelo_tipo_id');
			$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, imagem');
			$sql->adOnde('organizacao='.(int)$config['militar']);
			$sql->adOnde('modelo_cia_cia='.(int)$Aplic->usuario_cia);
			$modelos = $sql->Lista();
			$sql->limpar();
			if (count($modelos)){
				$km->Add("inserir","criar_documentos","Documento");
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_calendario=".$calendario_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reuni�o', 'Inserir uma nova ata de reuni�o relacionada.').'Ata de reuni�o'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_calendario=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_calendario=".$calendario_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) {
			$km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_calendario=".$calendario_id."\");");
			$km->Add("inserir","inserir_risco_resposta", dica('Nov'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Inserir um'.($config['genero_risco_resposta']=='a' ? 'a' : '').' nov'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.').ucfirst($config['risco_resposta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_resposta_pro_editar&risco_resposta_calendario=".$calendario_id."\");");
			}
		}
	
	if ($podeEditarTudo && $podeEditar) $km->Add("acao","acao_editar",dica('Editar Agenda Coletiva','Editar os detalhes desta agenda coletiva.').'Editar Agenda Coletiva'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&u=calendario&a=calendario_editar&calendario_id=".$calendario_id."\");");
	
	if ($podeEditarTudo && $podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir','Excluir esta agenda coletiva.').'Excluir Agenda Coletiva'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para visualizar as op��es de relat�rios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes desta Agenda Coletiva', 'Visualize os detalhes desta agenda coletiva.').' Detalhes desta agenda coletiva'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&calendario_id=".$calendario_id."\");");	
	echo $km->Render();
	echo '</td></tr></table>';
	}
	
	
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%"  >';


echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->calendario_cor.'" colspan="2"><font color="'.melhorCor($obj->calendario_cor).'"><b>'.$obj->calendario_nome.'<b></font></td></tr>';


$sql->adTabela('calendario_usuario');
$sql->adCampo('calendario_usuario_usuario');
$sql->adOnde('calendario_usuario_calendario = '.(int)$calendario_id);
$lista_usuarios = $sql->carregarColuna();
$sql->limpar();

$sql->adTabela('calendario_dept');
$sql->adCampo('calendario_dept_dept');
$sql->adOnde('calendario_dept_calendario = '.(int)$calendario_id);
$departamentos = $sql->carregarColuna();
$sql->limpar();




if ($obj->calendario_cia) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' respons�vel.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->calendario_cia).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('calendario_cia');
	$sql->adCampo('calendario_cia_cia');
	$sql->adOnde('calendario_cia_calendario = '.(int)$calendario_id);
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
	if ($saida_cias) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est�o envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}
if ($obj->calendario_dept) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Respons�vel', ucfirst($config['genero_dept']).' '.$config['departamento'].' respons�vel por esta agenda coletiva.').ucfirst($config['departamento']).' respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_dept($obj->calendario_dept).'</td></tr>';
$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_dept($departamentos[0]);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($departamentos[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' est� envolvid'.$config['genero_dept'].' com esta agenda coletiva.').ucfirst($config['departamento']).' envolvid'.$config['genero_dept'].':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';




if ($obj->calendario_usuario) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Respons�vel pela Agenda Coletiva'.'', ucfirst($config['usuario']).' respons�vel por gerenciar a agenda coletiva.').'Respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->calendario_usuario, '','','esquerda').'</td></tr>';		
		
$saida_usuarios='';
if (count($lista_usuarios)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($lista_usuarios[0],'','','esquerda');
		$qnt_lista_usuarios=count($lista_usuarios);
		if ($qnt_lista_usuarios > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($lista_usuarios[$i],'','','esquerda').'<br>';		
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		} 
if ($saida_usuarios) echo '<tr><td align="right" valign="top" style="white-space: nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' est�o envolvid'.$config['genero_usuario'].' com esta agenda coletiva.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_usuarios.'</td></tr>';		
		
	

if ($obj->calendario_descricao) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Descri��o', 'Descri��o da agenda coletiva.').'Descri��o:'.dicaF().'</td></td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->calendario_descricao.'</td></tr>';
	
	
if ($Aplic->profissional){
	$sql->adTabela('calendario_gestao');
	$sql->adCampo('calendario_gestao.*');
	$sql->adOnde('calendario_gestao_calendario ='.(int)$calendario_id);	
	$sql->adOrdem('calendario_gestao_ordem');
	$lista = $sql->Lista();
	$sql->limpar();
	$qnt_gestao=0;
	
	if (count($lista)) {
		echo '<tr><td align="right" style="white-space: nowrap" valign="middle">'.dica('Relacionada', 'A que �rea a agenda coletiva est� relacionada.').'Relacionada:'.dicaF().'</td></td><td class="realce">';	
		foreach($lista as $gestao_data){
			if ($gestao_data['calendario_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['calendario_gestao_tarefa']);
			elseif ($gestao_data['calendario_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['calendario_gestao_projeto']);
			elseif ($gestao_data['calendario_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['calendario_gestao_pratica']);
			elseif ($gestao_data['calendario_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['calendario_gestao_acao']);
			elseif ($gestao_data['calendario_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['calendario_gestao_perspectiva']);
			elseif ($gestao_data['calendario_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['calendario_gestao_tema']);
			elseif ($gestao_data['calendario_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['calendario_gestao_objetivo']);
			elseif ($gestao_data['calendario_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['calendario_gestao_fator']);
			elseif ($gestao_data['calendario_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['calendario_gestao_estrategia']);
			elseif ($gestao_data['calendario_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['calendario_gestao_meta']);
			elseif ($gestao_data['calendario_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['calendario_gestao_canvas']);
			elseif ($gestao_data['calendario_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['calendario_gestao_risco']);
			elseif ($gestao_data['calendario_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['calendario_gestao_risco_resposta']);
			elseif ($gestao_data['calendario_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['calendario_gestao_indicador']);
			
			elseif ($gestao_data['calendario_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['calendario_gestao_semelhante']);
			
			elseif ($gestao_data['calendario_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['calendario_gestao_monitoramento']);
			elseif ($gestao_data['calendario_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['calendario_gestao_ata']);
			elseif ($gestao_data['calendario_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['calendario_gestao_mswot']);
			elseif ($gestao_data['calendario_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['calendario_gestao_swot']);
			elseif ($gestao_data['calendario_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['calendario_gestao_operativo']);
			elseif ($gestao_data['calendario_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['calendario_gestao_instrumento']);
			elseif ($gestao_data['calendario_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['calendario_gestao_recurso']);
			elseif ($gestao_data['calendario_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['calendario_gestao_problema']);
			elseif ($gestao_data['calendario_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['calendario_gestao_demanda']);	
			elseif ($gestao_data['calendario_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['calendario_gestao_programa']);
			elseif ($gestao_data['calendario_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['calendario_gestao_licao']);
			elseif ($gestao_data['calendario_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['calendario_gestao_evento']);
			elseif ($gestao_data['calendario_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['calendario_gestao_link']);
			elseif ($gestao_data['calendario_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['calendario_gestao_avaliacao']);
			elseif ($gestao_data['calendario_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['calendario_gestao_tgn']);
			elseif ($gestao_data['calendario_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['calendario_gestao_brainstorm']);
			elseif ($gestao_data['calendario_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['calendario_gestao_gut']);
			elseif ($gestao_data['calendario_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['calendario_gestao_causa_efeito']);
			elseif ($gestao_data['calendario_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['calendario_gestao_arquivo']);
			elseif ($gestao_data['calendario_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['calendario_gestao_forum']);
			elseif ($gestao_data['calendario_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['calendario_gestao_checklist']);
			elseif ($gestao_data['calendario_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['calendario_gestao_agenda']);
			elseif ($gestao_data['calendario_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['calendario_gestao_agrupamento']);
			elseif ($gestao_data['calendario_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['calendario_gestao_patrocinador']);
			elseif ($gestao_data['calendario_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['calendario_gestao_template']);
			elseif ($gestao_data['calendario_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['calendario_gestao_painel']);
			elseif ($gestao_data['calendario_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['calendario_gestao_painel_odometro']);
			elseif ($gestao_data['calendario_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['calendario_gestao_painel_composicao']);		
			elseif ($gestao_data['calendario_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['calendario_gestao_tr']);	
			elseif ($gestao_data['calendario_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['calendario_gestao_me']);	
			elseif ($gestao_data['calendario_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['calendario_gestao_acao_item']);	
			elseif ($gestao_data['calendario_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['calendario_gestao_beneficio']);	
			elseif ($gestao_data['calendario_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['calendario_gestao_painel_slideshow']);	
			elseif ($gestao_data['calendario_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['calendario_gestao_projeto_viabilidade']);	
			elseif ($gestao_data['calendario_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['calendario_gestao_projeto_abertura']);	
			elseif ($gestao_data['calendario_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['calendario_gestao_plano_gestao']);	
			}
		echo '</td></tr>';
		}	
	}		
	
	
$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('N�vel de Acesso', 'Pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o respons�vel e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante I</b> - Somente o respons�vel e os designados podem ver e editar</li><li><b>Participantes II</b> - Somente o respons�vel e os designados podem ver e apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o respons�vel e os designados podem ver, e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.(isset($acesso[$obj->calendario_acesso]) ? $acesso[$obj->calendario_acesso] : '').'</td></tr>';
				

		
echo '</table>';

if (!$dialogo) {
	echo estiloFundoCaixa();
	$caixaTab = new CTabBox('m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id, '', $tab);
	
	if ($Aplic->profissional){

		$qnt_aba=0;
	
		if ($Aplic->checarModulo('log', 'acesso')) {
		$sql->adTabela('log');
		$sql->adCampo('count(log_id)');
		$sql->adOnde('log_calendario = '.(int)$calendario_id);
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver', 'Registro',null,null,'Registro das Ocorr�ncia','Visualizar o registro de ocorr�ncia relacionado.');
			}
		}

	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {

			$sql->adTabela('evento_gestao','evento_gestao');
			$sql->adOnde('evento_gestao_calendario = '.(int)$calendario_id);
			$sql->adOnde('evento_gestao_evento IS NOT NULL');
			$sql->adCampo('count(evento_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$data_inicio=null;
			$data_fim=null;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Evento',null,null,'Evento','Visualizar o evento relacionado.');
			}
		}
		
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) {

			$sql->adTabela('arquivo_gestao','arquivo_gestao');
			$sql->adOnde('arquivo_gestao_calendario = '.(int)$calendario_id);
			$sql->adOnde('arquivo_gestao_arquivo IS NOT NULL');
			$sql->adCampo('count(arquivo_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/arquivos/index_tabela', 'Arquivo',null,null,'Arquivo','Visualizar o arquivo relacionado.');
			}
		}
	
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) {

			$sql->adTabela('pratica_indicador_gestao','pratica_indicador_gestao');
			$sql->adOnde('pratica_indicador_gestao_calendario = '.(int)$calendario_id);
			$sql->adOnde('pratica_indicador_gestao_indicador IS NOT NULL');
			$sql->adCampo('count(pratica_indicador_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicador',null,null,'Indicador','Visualizar o indicador relacionado.');
			}
		}
		
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) {

			$sql->adTabela('plano_acao_gestao','plano_acao_gestao');
			$sql->adOnde('plano_acao_gestao_calendario = '.(int)$calendario_id);
			$sql->adOnde('plano_acao_gestao_acao IS NOT NULL');
			$sql->adCampo('count(plano_acao_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acao']),null,null,ucfirst($config['acao']),'Visualizar '.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao_item')) {
		$sql->adTabela('plano_acao_item_gestao');
		$sql->adOnde('plano_acao_item_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('plano_acao_item_gestao_plano_acao_item IS NOT NULL');
		$sql->adCampo('count(plano_acao_item_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_itens_idx','Item de '.$config['acao'],null,null,'Item de '.$config['acao'],'Visualizar o item de '.$config['acao'].' relacionado.');
			}
		}	
		
	if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) {

			$sql->adTabela('projeto_gestao');
			$sql->adOnde('projeto_gestao_calendario = '.(int)$calendario_id);
			$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=projeto_gestao_projeto');
			$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
			$sql->adOnde('projeto_portfolio=0');
			$sql->adCampo('count(projeto_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_projetos', ucfirst($config['projeto']),null,null,ucfirst($config['projeto']),'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.');
			}
		
		
		
		$sql->adTabela('projeto_gestao');
		$sql->adOnde('projeto_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('projeto_gestao_projeto IS NOT NULL');
		$sql->esqUnir('projetos', 'projetos', 'projeto_id=projeto_gestao_projeto');
		$sql->adOnde('projeto_template IS NULL OR projeto_template=0');
		$sql->adOnde('projeto_portfolio=1');
		$sql->adCampo('count(projeto_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
	
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_idx_portifolio_pro', ucfirst($config['portfolio']),null,null,ucfirst($config['portfolio']),'Visualizar '.$config['genero_portfolio'].' '.$config['portfolio'].' relacionad'.$config['genero_portfolio'].'.');
			}	
		
		}		
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) {
		$sql->adTabela('ata_gestao','ata_gestao');
		$sql->adOnde('ata_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('ata_gestao_ata IS NOT NULL');
		$sql->adCampo('count(ata_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Ata',null,null,'Ata','Visualizar a ata de reuni�o relacionada.');
			}
		}
			
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'demanda')) {
		$sql->adTabela('demanda_gestao');
		$sql->adOnde('demanda_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('demanda_gestao_demanda IS NOT NULL');
		$sql->adCampo('count(demanda_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/demanda_tabela','Demanda',null,null,'Demanda','Visualizar a demanda relacionada.');
			}
		}				
			
	if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$sql->adTabela('msg_gestao','msg_gestao');
		$sql->adOnde('msg_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('msg_gestao_msg IS NOT NULL');
		$sql->adCampo('count(msg_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
				$qnt_aba++;
				$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg', ucfirst($config['mensagem']),null,null,ucfirst($config['mensagem']),'Visualizar '.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.');
				}
		if ($config['doc_interno']) {
			$sql->adTabela('modelo_gestao','modelo_gestao');
			$sql->adOnde('modelo_gestao_calendario = '.(int)$calendario_id);
			$sql->adOnde('modelo_gestao_modelo IS NOT NULL');
			$sql->adCampo('count(modelo_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();
			if ($existe) {
				$qnt_aba++;
				$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo', 'Documento',null,null,'Documento','Visualizar o documento relacionado.');
				}
			}
		}	
		
	if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) {

			$sql->adTabela('link_gestao','link_gestao');
			$sql->adOnde('link_gestao_calendario = '.(int)$calendario_id);
			$sql->adOnde('link_gestao_link IS NOT NULL');
			$sql->adCampo('count(link_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Link',null,null,'Link','Visualizar o link relacionado.');
			}
		}
	
	if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) {

			$sql->adTabela('forum_gestao','forum_gestao');
			$sql->adOnde('forum_gestao_calendario = '.(int)$calendario_id);
			$sql->adOnde('forum_gestao_forum IS NOT NULL');
			$sql->adCampo('count(forum_gestao_id)');
			$existe=$sql->resultado();
			$sql->limpar();

		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'F�rum',null,null,'F�rum','Visualizar o f�rum relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) {
		$sql->adTabela('problema_gestao','problema_gestao');
		$sql->adOnde('problema_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('problema_gestao_problema IS NOT NULL');
		$sql->adCampo('count(problema_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problema']),null,null,ucfirst($config['problema']),'Visualizar '.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) {
		$sql->adTabela('risco_gestao','risco_gestao');
		$sql->adOnde('risco_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('risco_gestao_risco IS NOT NULL');
		$sql->adCampo('count(risco_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['risco']),null,null,ucfirst($config['risco']),'Visualizar '.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.');
			}
		}
		
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco_resposta')) {		
		$sql->adTabela('risco_resposta_gestao');
		$sql->esqUnir('risco_resposta','risco_resposta', 'risco_resposta_id=risco_resposta_gestao_risco_resposta');
		$sql->adOnde('risco_resposta_ativo=1');
		$sql->adOnde('risco_resposta_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('risco_resposta_gestao_risco_resposta IS NOT NULL');
		$sql->adCampo('count(risco_resposta_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_resposta_pro_ver_idx', ucfirst($config['risco_resposta']),null,null,ucfirst($config['risco_resposta']),'Visualizar '.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' relacionad'.$config['genero_risco_resposta'].'.');
			}
		}

	if ($Aplic->modulo_ativo('instrumento')  && $Aplic->checarModulo('instrumento', 'acesso')) {
		$sql->adTabela('instrumento_gestao','instrumento_gestao');
		$sql->adOnde('instrumento_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('instrumento_gestao_instrumento IS NOT NULL');
		$sql->adCampo('count(instrumento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/instrumento/instrumento_lista_idx', ucfirst($config['instrumento']),null,null,ucfirst($config['instrumento']),'Visualizar '.$config['genero_instrumento'].' '.$config['instrumento'].' relacionad'.$config['genero_instrumento'].'.');
			}
		}
	
	if ($Aplic->checarModulo('recursos', 'acesso', null, null)) {
		$sql->adTabela('recurso_gestao');
		$sql->adOnde('recurso_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('recurso_gestao_recurso IS NOT NULL');
		$sql->adCampo('count(recurso_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/recursos/ver_recursos', ucfirst($config['recurso']),null,null,ucfirst($config['recurso']),'Visualizar '.$config['genero_recurso'].' '.$config['recurso'].' relacionad'.$config['genero_recurso'].'.');
			}
		}
		
	if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'acesso', null, null)) {
		$sql->adTabela('patrocinador_gestao');
		$sql->adOnde('patrocinador_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('patrocinador_gestao_patrocinador IS NOT NULL');
		$sql->adCampo('count(patrocinador_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/patrocinadores/patrocinador_ver_idx',ucfirst($config['patrocinador']),null,null,ucfirst($config['patrocinador']),'Visualizar '.$config['genero_patrocinador'].' '.$config['patrocinador'].' relacionad'.$config['genero_patrocinador'].'.');
			}
		}
			
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'programa')) {
		$sql->adTabela('programa_gestao');
		$sql->adOnde('programa_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('programa_gestao_programa IS NOT NULL');
		$sql->adCampo('count(programa_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/programa_pro_ver_idx', ucfirst($config['programa']),null,null,ucfirst($config['programa']),'Visualizar '.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_programa'].'.');
			}
		}	
			
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'beneficio')) {
		$sql->adTabela('beneficio_gestao');
		$sql->adOnde('beneficio_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('beneficio_gestao_beneficio IS NOT NULL');
		$sql->adCampo('count(beneficio_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/beneficio_pro_ver_idx',ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.$config['programa'],null,null,ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.$config['programa'],'Visualizar '.$config['genero_beneficio'].' '.$config['beneficio'].' d'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_beneficio'].'.');
			}
		}		
	
	if ($Aplic->checarModulo('projeto', 'acesso', 'licao')) {
		$sql->adTabela('licao_gestao','licao_gestao');
		$sql->adOnde('licao_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('licao_gestao_licao IS NOT NULL');
		$sql->adCampo('count(licao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/licao_tabela', ucfirst($config['licao']),null,null,ucfirst($config['licao']),'Visualizar '.$config['genero_licao'].' '.$config['licao'].' relacionad'.$config['genero_licao'].'.');
			}
		}	
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'pratica')) {
		$sql->adTabela('pratica_gestao');
		$sql->adOnde('pratica_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('pratica_gestao_pratica IS NOT NULL');
		$sql->adCampo('count(pratica_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/praticas_ver_idx', ucfirst($config['pratica']),null,null,ucfirst($config['pratica']),'Visualizar '.$config['genero_pratica'].' '.$config['pratica'].' relacionad'.$config['genero_pratica'].'.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'acesso', null, null)) {
		$sql->adTabela('tr_gestao');
		$sql->adOnde('tr_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('tr_gestao_tr IS NOT NULL');
		$sql->adCampo('count(tr_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/tr/tr_tabela','Termo de Refer�ncia',null,null,'Termo de Refer�ncia','Visualizar o termos de refer�ncia relacionado.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'brainstorm')) {
		$sql->adTabela('brainstorm_gestao');
		$sql->adOnde('brainstorm_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('brainstorm_gestao_brainstorm IS NOT NULL');
		$sql->adCampo('count(brainstorm_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/brainstorm_ver_idx','Brainstorm',null,null,'Brainstorm','Visualizar o brainstorm relacionado.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'gut')) {
		$sql->adTabela('gut_gestao');
		$sql->adOnde('gut_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('gut_gestao_gut IS NOT NULL');
		$sql->adCampo('count(gut_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/gut_ver_idx','GUT',null,null,'GUT','Visualizar a matriz G.U.T. relacionada.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'causa_efeito')) {
		$sql->adTabela('causa_efeito_gestao');
		$sql->adOnde('causa_efeito_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('causa_efeito_gestao_causa_efeito IS NOT NULL');
		$sql->adCampo('count(causa_efeito_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/causa_efeito_ver_idx','Causa-Efeito',null,null,'Causa-Efeito','Visualizar o diagrama de causa-efeito relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'tgn')) {
		$sql->adTabela('tgn_gestao');
		$sql->adOnde('tgn_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('tgn_gestao_tgn IS NOT NULL');
		$sql->adCampo('count(tgn_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/tgn_pro_ver_idx', ucfirst($config['tgn']),null,null,ucfirst($config['tgn']),'Visualizar '.$config['genero_tgn'].' '.$config['tgn'].' relacionad'.$config['genero_tgn'].'.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'canvas')) {
		$sql->adTabela('canvas_gestao');
		$sql->adOnde('canvas_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('canvas_gestao_canvas IS NOT NULL');
		$sql->adCampo('count(canvas_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/canvas_pro_ver_idx', ucfirst($config['canvas']),null,null,ucfirst($config['canvas']),'Visualizar '.$config['genero_canvas'].' '.$config['canvas'].' relacionad'.$config['genero_canvas'].'.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null)) {
		$sql->adTabela('mswot_gestao');
		$sql->adOnde('mswot_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('mswot_gestao_mswot IS NOT NULL');
		$sql->adCampo('count(mswot_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/swot/mswot_tabela','Matriz SWOT',null,null,'Matriz SWOT','Visualizar a matriz SWOT relacionada.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null)) {
		$sql->adTabela('swot_gestao');
		$sql->adOnde('swot_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('swot_gestao_swot IS NOT NULL');
		$sql->adCampo('count(swot_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/swot/swot_tabela','Campo SWOT',null,null,'Campo SWOT','Visualizar o campos SWOT relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'acesso', null, null)) {
		$sql->adTabela('operativo_gestao');
		$sql->adOnde('operativo_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('operativo_gestao_operativo IS NOT NULL');
		$sql->adCampo('count(operativo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/operativo/operativo_tabela','Plano Operativo',null,null,'Plano Operativo','Visualizar o plano operativo relacionado.');
			}
		}	
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'monitoramento')) {
		$sql->adTabela('monitoramento_gestao');
		$sql->adOnde('monitoramento_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('monitoramento_gestao_monitoramento IS NOT NULL');
		$sql->adCampo('count(monitoramento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/monitoramento_ver_idx_pro','Monitoramento',null,null,'Monitoramento','Visualizar o monitoramento relacionado.');
			}
		}
	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'avaliacao_indicador')) {
		$sql->adTabela('avaliacao_gestao');
		$sql->adOnde('avaliacao_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('avaliacao_gestao_avaliacao IS NOT NULL');
		$sql->adCampo('count(avaliacao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/avaliacao_ver_idx','Avalia��o',null,null,'Avalia��o','Visualizar a avalia��o de indicadores relacionada.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'checklist')) {
		$sql->adTabela('checklist_gestao');
		$sql->adOnde('checklist_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('checklist_gestao_checklist IS NOT NULL');
		$sql->adCampo('count(checklist_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/checklist_ver_idx','Checklist',null,null,'Checklist','Visualizar o checklist relacionado.');
			}
		}	
	
	if ($Aplic->profissional) {
		$sql->adTabela('agenda_gestao');
		$sql->adOnde('agenda_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('agenda_gestao_agenda IS NOT NULL');
		$sql->adCampo('count(agenda_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/email/compromisso_ver_idx_pro','Compromisso',null,null,'Compromisso','Visualizar o compromisso relacionado.');
			}
		}	
	
	if ($Aplic->profissional && $Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'acesso', null, null)) {
		$sql->adTabela('agrupamento_gestao');
		$sql->adOnde('agrupamento_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('agrupamento_gestao_agrupamento IS NOT NULL');
		$sql->adCampo('count(agrupamento_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/agrupamento/agrupamento_tabela','Agrupamento',null,null,'Agrupamento','Visualizar o agrupamento relacionado.');
			}
		}
	
	if ($Aplic->profissional && $Aplic->checarModulo('projetos', 'acesso', null, 'modelo')) {
		$sql->adTabela('template_gestao');
		$sql->adOnde('template_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('template_gestao_template IS NOT NULL');
		$sql->adCampo('count(template_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/template_pro_ver_idx','Modelo',null,null,'Modelo','Visualizar o modelo de '.$config['projeto'].' relacionado.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'painel_indicador')) {
		$sql->adTabela('painel_gestao');
		$sql->adOnde('painel_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('painel_gestao_painel IS NOT NULL');
		$sql->adCampo('count(painel_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_pro_lista_idx','Painel',null,null,'Painel','Visualizar o painel de indicador relacionado.');
			}
		}		
		
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'odometro_indicador')) {
		$sql->adTabela('painel_odometro_gestao');
		$sql->adOnde('painel_odometro_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('painel_odometro_gestao_painel_odometro IS NOT NULL');
		$sql->adCampo('count(painel_odometro_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/odometro_pro_lista_idx','Od�metro',null,null,'Od�metro','Visualizar o od�metro de indicador relacionado.');
			}
		}				
		
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'composicao_painel')) {
		$sql->adTabela('painel_composicao_gestao');
		$sql->adOnde('painel_composicao_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('painel_composicao_gestao_painel_composicao IS NOT NULL');
		$sql->adCampo('count(painel_composicao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_composicao_pro_lista_idx','Composi��o de pain�is',null,null,'Composi��o de pain�is','Visualizar a composi��o de pain�is relacionada.');
			}
		}	
			
	if ($Aplic->profissional && $Aplic->checarModulo('praticas', 'acesso', null, 'slideshow_painel')) {
		$sql->adTabela('painel_slideshow_gestao');
		$sql->adOnde('painel_slideshow_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('painel_slideshow_gestao_painel_slideshow IS NOT NULL');
		$sql->adCampo('count(painel_slideshow_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/painel_slideshow_pro_lista_idx','Slideshow',null,null,'Slideshow','Visualizar o slideshow de pain�is relacionado.');
			}
		}		
	
	if ($Aplic->profissional && $Aplic->checarModulo('agenda', 'acesso', null, null)) {
		$sql->adTabela('calendario_gestao');
		$sql->adOnde('calendario_gestao_semelhante = '.(int)$calendario_id);
		$sql->adOnde('calendario_gestao_calendario IS NOT NULL');
		$sql->adCampo('count(calendario_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/sistema/calendario/calendario_ver_idx','Agenda Coletiva',null,null,'Agenda Coletiva','Visualizar a agendas coletiva relacionada.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'perspectiva')) {
		$sql->adTabela('perspectiva_gestao');
		$sql->adOnde('perspectiva_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('perspectiva_gestao_perspectiva IS NOT NULL');
		$sql->adCampo('count(perspectiva_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/perspectivas_ver_idx', ucfirst($config['perspectiva']),null,null,ucfirst($config['perspectiva']),'Visualizar '.$config['genero_perspectiva'].' '.$config['perspectiva'].' relacionad'.$config['genero_perspectiva'].'.');
			}
		}		
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'tema')) {
		$sql->adTabela('tema_gestao');
		$sql->adOnde('tema_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('tema_gestao_tema IS NOT NULL');
		$sql->adCampo('count(tema_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/tema_ver_idx', ucfirst($config['tema']),null,null,ucfirst($config['tema']),'Visualizar '.$config['genero_tema'].' '.$config['tema'].' relacionad'.$config['genero_tema'].'.');
			}
		}				
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'objetivo')) {
		$sql->adTabela('objetivo_gestao');
		$sql->adOnde('objetivo_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('objetivo_gestao_objetivo IS NOT NULL');
		$sql->adCampo('count(objetivo_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/obj_estrategicos_ver_idx', ucfirst($config['objetivo']),null,null,ucfirst($config['objetivo']),'Visualizar '.$config['genero_objetivo'].' '.$config['objetivo'].' relacionad'.$config['genero_objetivo'].'.');
			}
		}
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'me')) {
		$sql->adTabela('me_gestao');
		$sql->adOnde('me_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('me_gestao_me IS NOT NULL');
		$sql->adCampo('count(me_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/me_ver_idx_pro', ucfirst($config['me']),null,null,ucfirst($config['me']),'Visualizar '.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.');
			}
		}	
		
	if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'acesso', null, 'fator')) {
		$sql->adTabela('fator_gestao');
		$sql->adOnde('fator_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('fator_gestao_fator IS NOT NULL');
		$sql->adCampo('count(fator_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/fatores_ver_idx', ucfirst($config['fator']),null,null,ucfirst($config['fator']),'Visualizar '.$config['genero_fator'].' '.$config['fator'].' relacionad'.$config['genero_fator'].'.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'iniciativa')) {
		$sql->adTabela('estrategia_gestao');
		$sql->adOnde('estrategia_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('estrategia_gestao_estrategia IS NOT NULL');
		$sql->adCampo('count(estrategia_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/estrategias_ver_idx', ucfirst($config['iniciativa']),null,null,ucfirst($config['iniciativa']),'Visualizar '.$config['genero_iniciativa'].' '.$config['iniciativa'].' relacionad'.$config['genero_iniciativa'].'.');
			}
		}
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'meta')) {
		$sql->adTabela('meta_gestao');
		$sql->adOnde('meta_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('meta_gestao_meta IS NOT NULL');
		$sql->adCampo('count(meta_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/metas_ver_idx', ucfirst($config['meta']),null,null,ucfirst($config['meta']),'Visualizar '.$config['genero_meta'].' '.$config['meta'].' relacionad'.$config['genero_meta'].'.');
			}
		}	
		
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento')) {
		$sql->adTabela('plano_gestao_gestao');
		$sql->adOnde('plano_gestao_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('plano_gestao_gestao_plano_gestao IS NOT NULL');
		$sql->adCampo('count(plano_gestao_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/gestao/gestao_tabela','Planejamento estrat�gico',null,null,'Planejamento estrat�gico','Visualizar o planejamento estrat�gico relacionado.');
			}
		}				

	if ($Aplic->checarModulo('projetos', 'acesso', null, 'abertura')) {
		$sql->adTabela('projeto_abertura_gestao');
		$sql->adOnde('projeto_abertura_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('projeto_abertura_gestao_projeto_abertura IS NOT NULL');
		$sql->adCampo('count(projeto_abertura_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/termo_abertura_tabela','Termo de abertura',null,null,'Termo de abertura','Visualizar o YYY relacionado.');
			}
		}			
			
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'viabilidade')) {
		$sql->adTabela('projeto_viabilidade_gestao');
		$sql->adOnde('projeto_viabilidade_gestao_calendario = '.(int)$calendario_id);
		$sql->adOnde('projeto_viabilidade_gestao_projeto_viabilidade IS NOT NULL');
		$sql->adCampo('count(projeto_viabilidade_gestao_id)');
		$existe=$sql->resultado();
		$sql->limpar();
		if ($existe) {
			$qnt_aba++;
			$caixaTab->adicionar(BASE_DIR.'/modulos/projetos/viabilidade_tabela','Estudo de viabilidade',null,null,'Estudo de viabilidade','Visualizar o estudo de viabilidade relacionado.');
			}
		}	
		
		if ($qnt_aba) {
			$caixaTab->mostrar('','','','',true);
			echo estiloFundoCaixa('','', $tab);
			}	
		}
	else {
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('eventos', 'acesso')) {
			$data_inicio=null;
			$data_fim=null;
			$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
			}
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/arquivos/index_tabela', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados.');
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'F�runs',null,null,'F�runs','Visualizar os f�runs relacionados.');
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados.');
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
		$caixaTab->mostrar('','','','',true);
		echo estiloFundoCaixa('','', $tab);
		}
	
	}
elseif ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1))  echo '<script type="text/javascript">self.print();</script>';
?>
<script type="text/javascript">

function recalcular(calendario_id, percentagem){
	parent.gpwebApp.popUp('Recalculo de Progresso do F�sico', 550, 100, 'm=sistema&u=calendario&a=calendario_recalcular_fisico_pro&dialogo=1&calendario_id='+calendario_id+'&percentagem='+percentagem, null, window);
	}	

function excluir() {
	if (confirm('Tem certeza que deseja excluir?')) {
		var f = document.env;
		f.del.value=1;
		f.fazerSQL.value='calendario_fazer_sql';
		f.a.value='vazio';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>
<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', 0);
$cia_id=getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$uuid=getParam($_REQUEST, 'uuid', null);


$pratica_indicador_projeto = null;
$pratica_indicador_tarefa = null;
$pratica_indicador_perspectiva = null;
$pratica_indicador_tema = null;
$pratica_indicador_objetivo = null;
$pratica_indicador_fator = null;
$pratica_indicador_estrategia = null;
$pratica_indicador_meta = null;
$pratica_indicador_pratica = null;
$pratica_indicador_acao = null;
$pratica_indicador_canvas = null;
$pratica_indicador_risco = null;
$pratica_indicador_risco_resposta = null;
$pratica_indicador_indicador = null;
$pratica_indicador_calendario = null;
$pratica_indicador_monitoramento = null;
$pratica_indicador_ata = null;
$pratica_indicador_mswot = null;
$pratica_indicador_swot = null;
$pratica_indicador_operativo = null;
$pratica_indicador_instrumento = null;
$pratica_indicador_recurso = null;
$pratica_indicador_problema = null;
$pratica_indicador_demanda = null;
$pratica_indicador_programa = null;
$pratica_indicador_licao = null;
$pratica_indicador_evento = null;
$pratica_indicador_link = null;
$pratica_indicador_avaliacao = null;
$pratica_indicador_tgn = null;
$pratica_indicador_brainstorm = null;
$pratica_indicador_gut = null;
$pratica_indicador_causa_efeito = null;
$pratica_indicador_arquivo = null;
$pratica_indicador_forum = null;
$pratica_indicador_checklist = null;
$pratica_indicador_agenda = null;
$pratica_indicador_agrupamento = null;
$pratica_indicador_patrocinador = null;
$pratica_indicador_template = null;
$pratica_indicador_painel = null;
$pratica_indicador_painel_odometro = null;
$pratica_indicador_painel_composicao = null;
$pratica_indicador_tr = null;
$pratica_indicador_me = null;
$pratica_indicador_acao_item = null;
$pratica_indicador_beneficio = null;
$pratica_indicador_painel_slideshow = null;
$pratica_indicador_projeto_viabilidade = null;
$pratica_indicador_projeto_abertura = null;
$pratica_indicador_plano_gestao = null;



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';



echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
echo '<tr><td align=right>'.dica('Selecionar '.$config['organizacao'], 'Selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que deseja exibir os indicadores.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="mudar_indicadores()">'.imagem('icones/atualizar.png','Atualizar os Indicadores','Clique neste ?cone '.imagem('icones/atualizar.png').' para atualizar a lista de indicadores.').'</a></td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">Pesquisar:</td><td align="left" style="white-space: nowrap"><table cellpadding=0 cellspacing=0><tr><td><input type="text" class="texto" style="width:400px;" name="pesquisar" id="pesquisar" value="" onchange="mudar_indicadores();" /><a href="javascript:void(0);" onclick="env.pesquisar.value=\'\'; mudar_indicadores();">'.imagem('icones/limpar_p.gif').'</a></td></tr></table></td></tr>';

$tipos=array(''=>'');
if ($Aplic->checarModulo('projetos', 'editar', null, 'projetos_lista')) $tipos['projeto']=ucfirst($config['projeto']); 
if ($Aplic->checarModulo('tarefas', 'editar', null, null)) $tipos['tarefa']=ucfirst($config['tarefa']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'perspectiva')) $tipos['perspectiva']=ucfirst($config['perspectiva']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'tema')) $tipos['tema']=ucfirst($config['tema']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'objetivo')) $tipos['objetivo']=ucfirst($config['objetivo']); 
if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'editar', null, 'fator')) $tipos['fator']=ucfirst($config['fator']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'iniciativa')) $tipos['estrategia']=ucfirst($config['iniciativa']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'meta')) $tipos['meta']=ucfirst($config['meta']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao')) $tipos['acao']=ucfirst($config['acao']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'pratica')) $tipos['pratica']=ucfirst($config['pratica']); 
if ($Aplic->checarModulo('praticas', 'editar', null, 'indicador')) $tipos['indicador']='Indicador'; 
if ($Aplic->checarModulo('agenda', 'editar', null, null)) $tipos['calendario']='Agenda';
if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'editar', null, null)) $tipos['instrumento']=ucfirst($config['instrumento']);
if ($Aplic->checarModulo('recursos', 'editar', null, null)) $tipos['recurso']=ucfirst($config['recurso']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'demanda')) $tipos['demanda']='Demanda';
if ($Aplic->checarModulo('projetos', 'editar', null, 'licao')) $tipos['licao']=ucfirst($config['licao']);
if ($Aplic->checarModulo('eventos', 'editar', null, null)) $tipos['evento']='Evento';
if ($Aplic->checarModulo('links', 'editar', null, null)) $tipos['link']='Link';
if ($Aplic->checarModulo('praticas', 'editar', null, 'avaliacao_indicador')) $tipos['avaliacao']='Avalia??o';
if ($Aplic->checarModulo('praticas', 'editar', null, 'brainstorm')) $tipos['brainstorm']='Brainstorm';
if ($Aplic->checarModulo('praticas', 'editar', null, 'gut')) $tipos['gut']='Matriz GUT';
if ($Aplic->checarModulo('praticas', 'editar', null, 'causa_efeito')) $tipos['causa_efeito']='Diagrama de causa-efeito';
if ($Aplic->checarModulo('arquivos', 'editar', null,  null)) $tipos['arquivo']='Arquivo';
if ($Aplic->checarModulo('foruns', 'editar', null, null)) $tipos['forum']='F?rum';
if ($Aplic->checarModulo('praticas', 'editar', null, 'checklist')) $tipos['checklist']='Checklist';
if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'editar', null, null)) $tipos['patrocinador']=ucfirst($config['patrocinador']);
if ($Aplic->checarModulo('praticas', 'editar', null, 'plano_acao_item')) $tipos['acao_item']='Item de '.ucfirst($config['acao']);
if ($Aplic->checarModulo('projetos', 'editar', null, 'viabilidade')) $tipos['projeto_viabilidade']='Estudo de viabilidade';
if ($Aplic->checarModulo('projetos', 'editar', null, 'abertura')) $tipos['projeto_abertura']='Termo de abertura';
if ($Aplic->checarModulo('praticas', 'editar', null, 'planejamento')) $tipos['plano_gestao']='Planejamento estrat?gico';
if ($Aplic->profissional) {
	$tipos['agenda']='Compromisso';
	if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'editar', null, null)) $tipos['operativo']='Plano operativo';
	if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'editar', null, null)) $tipos['ata']='Ata de reuni?o';	
	if ($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'editar', null, null)) {
		$tipos['mswot']='Matriz SWOT';
		$tipos['swot']='Campo SWOT';
		}
	if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'editar', null, null)) $tipos['problema']=ucfirst($config['problema']);
	if ($Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'editar', null, null)) $tipos['agrupamento']='Agrupamento';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'canvas')) $tipos['canvas']=ucfirst($config['canvas']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'risco')) $tipos['risco']=ucfirst($config['risco']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'resposta_risco')) $tipos['risco_resposta']=ucfirst($config['risco_resposta']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'monitoramento')) $tipos['monitoramento']='Monitoramento';
	if ($Aplic->checarModulo('projetos', 'editar', null, 'programa')) $tipos['programa']=ucfirst($config['programa']);
	if ($Aplic->checarModulo('praticas', 'editar', null, 'tgn')) $tipos['tgn']=ucfirst($config['tgn']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'modelo')) $tipos['template']='Modelo';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'painel_indicador')) $tipos['painel']='Painel de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'odometro_indicador')) $tipos['painel_odometro']='Od?metro de indicador';
	if ($Aplic->checarModulo('praticas', 'editar', null, 'composicao_painel')) $tipos['painel_composicao']='Composi??o de pain?is';
	if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'editar', null, null)) $tipos['tr']=ucfirst($config['tr']);
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'editar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	if ($Aplic->checarModulo('projetos', 'editar', null, 'beneficio')) $tipos['beneficio']=ucfirst($config['beneficio']).' de '.$config['programa'];
	if ($Aplic->checarModulo('projetos', 'editar', null, 'slideshow_painel')) $tipos['painel_slideshow']='Slideshow de composi??es';
	}
asort($tipos);

if ($pratica_indicador_tarefa) $tipo='tarefa';
elseif ($pratica_indicador_projeto) $tipo='projeto';
elseif ($pratica_indicador_perspectiva) $tipo='perspectiva';
elseif ($pratica_indicador_tema) $tipo='tema';
elseif ($pratica_indicador_objetivo) $tipo='objetivo';
elseif ($pratica_indicador_fator) $tipo='fator';
elseif ($pratica_indicador_estrategia) $tipo='estrategia';
elseif ($pratica_indicador_meta) $tipo='meta';
elseif ($pratica_indicador_pratica) $tipo='pratica';
elseif ($pratica_indicador_acao) $tipo='acao';
elseif ($pratica_indicador_canvas) $tipo='canvas';
elseif ($pratica_indicador_risco) $tipo='risco';
elseif ($pratica_indicador_risco_resposta) $tipo='risco_resposta';
elseif ($pratica_indicador_indicador) $tipo='pratica_indicador_indicador';
elseif ($pratica_indicador_calendario) $tipo='calendario';
elseif ($pratica_indicador_monitoramento) $tipo='monitoramento';
elseif ($pratica_indicador_ata) $tipo='ata';
elseif ($pratica_indicador_mswot) $tipo='mswot';
elseif ($pratica_indicador_swot) $tipo='swot';
elseif ($pratica_indicador_operativo) $tipo='operativo';
elseif ($pratica_indicador_instrumento) $tipo='instrumento';
elseif ($pratica_indicador_recurso) $tipo='recurso';
elseif ($pratica_indicador_problema) $tipo='problema';
elseif ($pratica_indicador_demanda) $tipo='demanda';
elseif ($pratica_indicador_programa) $tipo='programa';
elseif ($pratica_indicador_licao) $tipo='licao';
elseif ($pratica_indicador_evento) $tipo='evento';
elseif ($pratica_indicador_link) $tipo='link';
elseif ($pratica_indicador_avaliacao) $tipo='avaliacao';
elseif ($pratica_indicador_tgn) $tipo='tgn';
elseif ($pratica_indicador_brainstorm) $tipo='brainstorm';
elseif ($pratica_indicador_gut) $tipo='gut';
elseif ($pratica_indicador_causa_efeito) $tipo='causa_efeito';
elseif ($pratica_indicador_arquivo) $tipo='arquivo';
elseif ($pratica_indicador_forum) $tipo='forum';
elseif ($pratica_indicador_checklist) $tipo='checklist';
elseif ($pratica_indicador_agenda) $tipo='agenda';
elseif ($pratica_indicador_agrupamento) $tipo='agrupamento';
elseif ($pratica_indicador_patrocinador) $tipo='patrocinador';
elseif ($pratica_indicador_template) $tipo='template';
elseif ($pratica_indicador_painel) $tipo='painel';
elseif ($pratica_indicador_painel_odometro) $tipo='painel_odometro';
elseif ($pratica_indicador_painel_composicao) $tipo='painel_composicao';
elseif ($pratica_indicador_tr) $tipo='tr';
elseif ($pratica_indicador_me) $tipo='me';
elseif ($pratica_indicador_acao_item) $tipo='acao_item';
elseif ($pratica_indicador_beneficio) $tipo='beneficio';
elseif ($pratica_indicador_painel_slideshow) $tipo='painel_slideshow';
elseif ($pratica_indicador_projeto_viabilidade) $tipo='projeto_viabilidade';
elseif ($pratica_indicador_projeto_abertura) $tipo='projeto_abertura';
elseif ($pratica_indicador_plano_gestao) $tipo='plano_gestao';
else $tipo='';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado','A qual parte do sistema o indicador est? relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:400px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';

echo '<tr '.($pratica_indicador_projeto ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso seja espec?fico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo dever? constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_projeto" value="'.$pratica_indicador_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($pratica_indicador_projeto).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ?cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso seja espec?fico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever? constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tarefa" value="'.$pratica_indicador_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($pratica_indicador_tarefa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ?cone '.imagem('icones/tarefa_p.gif').' escolher ? qual '.$config['tarefa'].' o arquivo ir? pertencer.<br><br>Caso n?o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo ser? d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso seja espec?fico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo dever? constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_perspectiva" value="'.$pratica_indicador_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($pratica_indicador_perspectiva).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ?cone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso seja espec?fico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo dever? constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tema" value="'.$pratica_indicador_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($pratica_indicador_tema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ?cone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso seja espec?fico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo dever? constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_objetivo" value="'.$pratica_indicador_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($pratica_indicador_objetivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ?cone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso seja espec?fico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo dever? constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_fator" value="'.$pratica_indicador_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($pratica_indicador_fator).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ?cone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso seja espec?fico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo dever? constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_estrategia" value="'.$pratica_indicador_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($pratica_indicador_estrategia).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ?cone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso seja espec?fico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo dever? constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_meta" value="'.$pratica_indicador_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($pratica_indicador_meta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ?cone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso seja espec?fico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo dever? constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_pratica" value="'.$pratica_indicador_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($pratica_indicador_pratica).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ?cone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso seja espec?fico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo dever? constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_acao" value="'.$pratica_indicador_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($pratica_indicador_acao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar A??o','Clique neste ?cone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de a??o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso seja espec?fico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo dever? constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_canvas" value="'.$pratica_indicador_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($pratica_indicador_canvas).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso seja espec?fico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo dever? constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_risco" value="'.$pratica_indicador_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($pratica_indicador_risco).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ?cone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso seja espec?fico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo dever? constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_risco_resposta" value="'.$pratica_indicador_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($pratica_indicador_risco_resposta).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ?cone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" style="white-space: nowrap">'.dica('Indicador', 'Caso seja espec?fico de um indicador, neste campo dever? constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_indicador" value="'.$pratica_indicador_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($pratica_indicador_indicador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ?cone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso seja espec?fico de uma agenda, neste campo dever? constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_calendario" value="'.$pratica_indicador_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($pratica_indicador_calendario).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste ?cone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso seja espec?fico de um monitoramento, neste campo dever? constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_monitoramento" value="'.$pratica_indicador_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($pratica_indicador_monitoramento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ?cone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reuni?o', 'Caso seja espec?fico de uma ata de reuni?o neste campo dever? constar o nome da ata').'Ata de Reuni?o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_ata" value="'.(isset($pratica_indicador_ata) ? $pratica_indicador_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($pratica_indicador_ata) ? $pratica_indicador_ata : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reuni?o','Clique neste ?cone '.imagem('icones/ata_p.png').' para selecionar uma ata de reuni?o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso seja espec?fico de uma matriz SWOT neste campo dever? constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_mswot" value="'.(isset($pratica_indicador_mswot) ? $pratica_indicador_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($pratica_indicador_mswot) ? $pratica_indicador_mswot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste ?cone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso seja espec?fico de um campo de matriz SWOT neste campo dever? constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_swot" value="'.(isset($pratica_indicador_swot) ? $pratica_indicador_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($pratica_indicador_swot) ? $pratica_indicador_swot : null)).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste ?cone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso seja espec?fico de um plano operativo, neste campo dever? constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_operativo" value="'.$pratica_indicador_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($pratica_indicador_operativo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ?cone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso seja espec?fico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo dever? constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_instrumento" value="'.$pratica_indicador_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($pratica_indicador_instrumento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ?cone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso seja espec?fico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo dever? constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_recurso" value="'.$pratica_indicador_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($pratica_indicador_recurso).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste ?cone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso seja espec?fico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo dever? constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_problema" value="'.$pratica_indicador_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($pratica_indicador_problema).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ?cone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso seja espec?fico de uma demanda, neste campo dever? constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_demanda" value="'.$pratica_indicador_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($pratica_indicador_demanda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar Demanda','Clique neste ?cone '.imagem('icones/demanda_p.gif').' para selecionar uma demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso seja espec?fico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo dever? constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_programa" value="'.$pratica_indicador_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($pratica_indicador_programa).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ?cone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso seja espec?fico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo dever? constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_licao" value="'.$pratica_indicador_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($pratica_indicador_licao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste ?cone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso seja espec?fico de um evento, neste campo dever? constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_evento" value="'.$pratica_indicador_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($pratica_indicador_evento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ?cone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso seja espec?fico de um link, neste campo dever? constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_link" value="'.$pratica_indicador_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($pratica_indicador_link).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ?cone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avalia??o', 'Caso seja espec?fico de uma avalia??o, neste campo dever? constar o nome da avalia??o.').'Avalia??o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_avaliacao" value="'.$pratica_indicador_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($pratica_indicador_avaliacao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avalia??o','Clique neste ?cone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avalia??o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso seja espec?fico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo dever? constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tgn" value="'.$pratica_indicador_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($pratica_indicador_tgn).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ?cone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso seja espec?fico de um brainstorm, neste campo dever? constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_brainstorm" value="'.$pratica_indicador_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($pratica_indicador_brainstorm).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ?cone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz G.U.T.', 'Caso seja espec?fico de uma matriz G.U.T., neste campo dever? constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_gut" value="'.$pratica_indicador_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($pratica_indicador_gut).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste ?cone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso seja espec?fico de um diagrama de causa-efeito, neste campo dever? constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_causa_efeito" value="'.$pratica_indicador_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($pratica_indicador_causa_efeito).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ?cone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso seja espec?fico de um arquivo, neste campo dever? constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_arquivo" value="'.$pratica_indicador_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($pratica_indicador_arquivo).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ?cone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('F?rum', 'Caso seja espec?fico de um f?rum, neste campo dever? constar o nome do f?rum.').'F?rum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_forum" value="'.$pratica_indicador_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($pratica_indicador_forum).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar F?rum','Clique neste ?cone '.imagem('icones/forum_p.gif').' para selecionar um f?rum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso seja espec?fico de um checklist, neste campo dever? constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_checklist" value="'.$pratica_indicador_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($pratica_indicador_checklist).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ?cone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso seja espec?fico de um compromisso, neste campo dever? constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_agenda" value="'.$pratica_indicador_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($pratica_indicador_agenda).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste ?cone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso seja espec?fico de um agrupamento, neste campo dever? constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_agrupamento" value="'.$pratica_indicador_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($pratica_indicador_agrupamento).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste ?cone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso seja espec?fico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo dever? constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_patrocinador" value="'.$pratica_indicador_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($pratica_indicador_patrocinador).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste ?cone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso seja espec?fico de um modelo, neste campo dever? constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_template" value="'.$pratica_indicador_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($pratica_indicador_template).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ?cone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso seja espec?fico de um painel de indicador, neste campo dever? constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel" value="'.$pratica_indicador_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($pratica_indicador_painel).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ?cone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Od?metro de Indicador', 'Caso seja espec?fico de um od?metro de indicador, neste campo dever? constar o nome do od?metro.').'Od?metro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_odometro" value="'.$pratica_indicador_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($pratica_indicador_painel_odometro).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Od?metro','Clique neste ?cone '.imagem('icones/odometro_p.png').' para selecionar um od?mtro.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composi??o de Pain?is', 'Caso seja espec?fico de uma composi??o de pain?is, neste campo dever? constar o nome da composi??o.').'Composi??o de Pain?is:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_composicao" value="'.$pratica_indicador_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($pratica_indicador_painel_composicao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composi??o de Pain?is','Clique neste ?cone '.imagem('icones/composicao_p.gif').' para selecionar uma composi??o de pain?is.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja espec?fico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo dever? constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tr" value="'.$pratica_indicador_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($pratica_indicador_tr).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja espec?fico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo dever? constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_me" value="'.$pratica_indicador_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($pratica_indicador_me).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_acao_item ? '' : 'style="display:none"').' id="acao_item" ><td align="right" style="white-space: nowrap">'.dica('Item de '.ucfirst($config['acao']), 'Caso seja espec?fico de um item de '.$config['acao'].', neste campo dever? constar o nome do item de '.$config['acao'].'.').'Item de '.$config['acao'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_acao_item" value="'.$pratica_indicador_acao_item.'" /><input type="text" id="acao_item_nome" name="acao_item_nome" value="'.nome_acao_item($pratica_indicador_acao_item).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcaoItem();">'.imagem('icones/acao_item_p.png','Selecionar Item de '.ucfirst($config['acao']),'Clique neste ?cone '.imagem('icones/acao_item_p.png').' para selecionar um item de '.$config['acao'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_beneficio ? '' : 'style="display:none"').' id="beneficio" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['beneficio']).' de '.ucfirst($config['programa']), 'Caso seja espec?fico de '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].', neste campo dever? constar o nome d'.$config['genero_beneficio'].' '.$config['beneficio'].' de '.$config['programa'].'.').ucfirst($config['beneficio']).' de '.$config['programa'].':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_beneficio" value="'.$pratica_indicador_beneficio.'" /><input type="text" id="beneficio_nome" name="beneficio_nome" value="'.nome_beneficio($pratica_indicador_beneficio).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBeneficio();">'.imagem('icones/beneficio_p.png','Selecionar '.ucfirst($config['beneficio']).' de '.ucfirst($config['programa']),'Clique neste ?cone '.imagem('icones/beneficio_p.png').' para selecionar '.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].' de '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_painel_slideshow ? '' : 'style="display:none"').' id="painel_slideshow" ><td align="right" style="white-space: nowrap">'.dica('Slideshow de Composi??es', 'Caso seja espec?fico de um slideshow de composi??es, neste campo dever? constar o nome do slideshow de composi??es.').'Slideshow de composi??es:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_slideshow" value="'.$pratica_indicador_painel_slideshow.'" /><input type="text" id="painel_slideshow_nome" name="painel_slideshow_nome" value="'.nome_painel_slideshow($pratica_indicador_painel_slideshow).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSlideshow();">'.imagem('icones/slideshow_p.gif','Selecionar Slideshow de Composi??es','Clique neste ?cone '.imagem('icones/slideshow_p.gif').' para selecionar um slideshow de composi??es.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_projeto_viabilidade ? '' : 'style="display:none"').' id="projeto_viabilidade" ><td align="right" style="white-space: nowrap">'.dica('Estudo de Viabilidade', 'Caso seja espec?fico de um estudo de viabilidade, neste campo dever? constar o nome do estudo de viabilidade.').'Estudo de viabilidade:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_projeto_viabilidade" value="'.$pratica_indicador_projeto_viabilidade.'" /><input type="text" id="projeto_viabilidade_nome" name="projeto_viabilidade_nome" value="'.nome_viabilidade($pratica_indicador_projeto_viabilidade).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popViabilidade();">'.imagem('icones/viabilidade_p.gif','Selecionar Estudo de Viabilidade','Clique neste ?cone '.imagem('icones/viabilidade_p.gif').' para selecionar um estudo de viabilidade.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_projeto_abertura ? '' : 'style="display:none"').' id="projeto_abertura" ><td align="right" style="white-space: nowrap">'.dica('Termo de Abertura', 'Caso seja espec?fico de um termo de abertura, neste campo dever? constar o nome do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_projeto_abertura" value="'.$pratica_indicador_projeto_abertura.'" /><input type="text" id="projeto_abertura_nome" name="projeto_abertura_nome" value="'.nome_termo_abertura($pratica_indicador_projeto_abertura).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAbertura();">'.imagem('icones/anexo_projeto_p.png','Selecionar Termo de Abertura','Clique neste ?cone '.imagem('icones/anexo_projeto_p.png').' para selecionar um termo de abertura.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_plano_gestao ? '' : 'style="display:none"').' id="plano_gestao" ><td align="right" style="white-space: nowrap">'.dica('Planejamento Estrat?gico', 'Caso seja espec?fico de um planejamento estrat?gico, neste campo dever? constar o nome do planejamento estrat?gico.').'Planejamento estrat?gico:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_plano_gestao" value="'.$pratica_indicador_plano_gestao.'" /><input type="text" id="plano_gestao_nome" name="plano_gestao_nome" value="'.nome_plano_gestao($pratica_indicador_plano_gestao).'" style="width:400px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPlanejamento();">'.imagem('icones/planogestao_p.png','Selecionar Planejamento Estrat?gico','Clique neste ?cone '.imagem('icones/planogestao_p.png').' para selecionar um planejamento estrat?gico.').'</a></td></tr></table></td></tr>';



echo '</table></td></tr>';

$filtro=array();
$filtro[]='pratica_indicador_ativo=1';
$indicadores=vetor_com_pai_generico('pratica_indicador', 'pratica_indicador_id', 'pratica_indicador_nome', 'pratica_indicador_superior', '', $cia_id, 'pratica_indicador_cia', TRUE, FALSE, 'pratica_indicador_acesso', 'indicador', '', false, $filtro);

echo '<tr><td width="100%"><fieldset><legend class=texto style="color: black;">'.dica('Indicadores Dispon?veis', 'Lista de indicadores que poder?o ser acrescentados ? composi??o. D? um clique duplo em um dos indicadores nesta lista de sele??o para adiciona-lo ? lista de composi??o.<BR><BR>Outra op??o ? selecionar o indicador e clicar no bot?o Adicionar.').'&nbsp;<b>Indicadores Dispon?veis</b>&nbsp</legend>'.dica().'<div id="combo_lista_indicadores">'.selecionaVetor($indicadores, 'lista', 'style="width:100%;" size="15" class="texto" ondblclick="mudar_indicadores_filhos();"').'</div></fieldset></td></tr>';

echo '<tr><td align="left" colspan=20><table cellspacing=0 cellpadding=0><tr><td>'.botao('adicionar', 'Adicionar', 'Utilize este bot?o para adicionar um indicador ? lista dos selecionados.</p>','','adicionar_indicador()','','',0).'</td><td>&nbsp;&nbsp;&nbsp;'.dica('Peso', 'Peso do indicador a ser inserido.O valor do indicador final ser? a m?dia ponderada dos valores dos indicadores selecionados pelos respectivos pesos.').'Peso:'.dicaF().'<input type="text" class="texto" name="pratica_indicador_composicao_peso" id="pratica_indicador_composicao_peso" value="1" style="width:50px;"></td></tr></table></td></tr>';


$sql = new BDConsulta;

$sql->adTabela('pratica_indicador_composicao');
$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_id=pratica_indicador_composicao_filho');
$sql->esqUnir('cias','cias','pratica_indicador_cia=cia_id');
$sql->adCampo('pratica_indicador_composicao.*, pratica_indicador_nome, cia_nome');
if($uuid) $sql->adOnde('pratica_indicador_composicao_uuid =\''.$uuid.'\'');
else $sql->adOnde('pratica_indicador_composicao_pai='.(int)$pratica_indicador_id);
$sql->adOrdem('pratica_indicador_composicao_ordem');
$lista=$sql->Lista();
$sql->limpar();
echo '<tr><td colspan=20><div id="combo_indicador">';
if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0 width=100%><tr><th></th><th>'.dica('Peso', 'Qual o peso do indicador a ser utilizado na composi??o').'Peso'.dicaF().'</th><th>'.dica('Nome', 'Qual o nome do indicador a ser utilizado na f?rmula').'Nome'.dicaF().'</th><th>'.dica(ucfirst($config['organizacao']), ucfirst($config['organizacao']).' do indicador a ser utilizado na composi??o.').ucfirst($config['organizacao']).dicaF().'</th><th></th></tr>';
foreach($lista as $linha){
	echo '<tr align="center">';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posi??o', 'Clique neste ?cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_indicador('.$linha['pratica_indicador_composicao_ordem'].', '.$linha['pratica_indicador_composicao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ?cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_indicador('.$linha['pratica_indicador_composicao_ordem'].', '.$linha['pratica_indicador_composicao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ?cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_indicador('.$linha['pratica_indicador_composicao_ordem'].', '.$linha['pratica_indicador_composicao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posi??o', 'Clique neste ?cone '.imagem('icones/2setabaixo.gif').' para mover para a ?ltima posi??o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_indicador('.$linha['pratica_indicador_composicao_ordem'].', '.$linha['pratica_indicador_composicao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	echo '<td width=20>'.number_format($linha['pratica_indicador_composicao_peso'], 2, ',', '.').'</td>';
	echo '<td align=left>'.$linha['pratica_indicador_nome'].'</td>';
	echo '<td align=left>'.$linha['cia_nome'].'</td>';
	echo '<td width="16" align=center><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_indicador('.$linha['pratica_indicador_composicao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
	}
if (count($lista)) echo '</table>';
echo '</div></td></tr>';


echo '<tr><td>'.botao('aceitar', 'Aceitar', 'Utilize este bot?o para aceitar a edi??o da composi??o.','','Retornar();','','',0).'</td><td>&nbsp;</td></td>'. (!$Aplic->profissional ? '<td  align="right">'.botao('cancelar', 'Cancelar', 'Utilize este bot?o para cancelar a edi??o de f?rmula do indicador.','','window.opener = window; window.close()','','',0).'</td>' : '').'</tr>';
echo '</table></td></tr></table>';


echo estiloFundoCaixa();

echo '</form>';


?>
<script type="text/javascript">

function excluir_indicador(pratica_indicador_composicao_id){
	xajax_excluir_indicador(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value, pratica_indicador_composicao_id);
	__buildTooltip();
	}

function mudar_posicao_indicador(ordem, pratica_indicador_composicao_id, direcao){
	xajax_mudar_posicao_indicador(ordem, pratica_indicador_composicao_id, direcao, document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function adicionar_indicador() {
	if(env.lista.value) xajax_adicionar_indicador(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value, env.lista.value, env.pratica_indicador_composicao_peso.value);
	}

function Retornar(){

	if(parent && parent.gpwebApp){
		parent.gpwebApp._popupCallback();
		return;
		}
	else{
		window.opener = window;
		window.close();
		}
	}



function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}

function mudar_indicadores(){
	var f = document.env;
	var vetor=Array();
	if (f.pesquisar.value) vetor[0]='pratica_indicador_nome LIKE \'%'+f.pesquisar.value+'%\'';
	<?php if ($Aplic->profissional) { ?>
	if (f.pratica_indicador_projeto.value && f.pratica_indicador_projeto.value > 0) vetor[1]='pratica_indicador_gestao_projeto='+f.pratica_indicador_projeto.value;
	else if (f.pratica_indicador_tarefa.value && f.pratica_indicador_tarefa.value > 0) vetor[1]='pratica_indicador_gestao_tarefa='+f.pratica_indicador_tarefa.value;
	else if (f.pratica_indicador_perspectiva.value && f.pratica_indicador_perspectiva.value > 0) vetor[1]='pratica_indicador_gestao_perspectiva='+f.pratica_indicador_perspectiva.value;
	else if (f.pratica_indicador_tema.value && f.pratica_indicador_tema.value > 0) vetor[1]='pratica_indicador_gestao_tema='+f.pratica_indicador_tema.value;
	else if (f.pratica_indicador_objetivo.value && f.pratica_indicador_objetivo.value > 0) vetor[1]='pratica_indicador_gestao_objetivo='+f.pratica_indicador_objetivo.value;
	else if (f.pratica_indicador_fator.value && f.pratica_indicador_fator.value > 0) vetor[1]='pratica_indicador_gestao_fator='+f.pratica_indicador_fator.value;
	else if (f.pratica_indicador_estrategia.value && f.pratica_indicador_estrategia.value > 0) vetor[1]='pratica_indicador_gestao_estrategia='+f.pratica_indicador_estrategia.value;
	else if (f.pratica_indicador_meta.value && f.pratica_indicador_meta.value > 0) vetor[1]='pratica_indicador_gestao_meta='+f.pratica_indicador_meta.value;
	else if (f.pratica_indicador_pratica.value && f.pratica_indicador_pratica.value > 0) vetor[1]='pratica_indicador_gestao_pratica='+f.pratica_indicador_pratica.value;
	else if (f.pratica_indicador_acao.value && f.pratica_indicador_acao.value > 0) vetor[1]='pratica_indicador_gestao_acao='+f.pratica_indicador_acao.value;
	else if (f.pratica_indicador_canvas.value && f.pratica_indicador_canvas.value > 0) vetor[1]='pratica_indicador_gestao_canvas='+f.pratica_indicador_canvas.value;
	else if (f.pratica_indicador_risco.value && f.pratica_indicador_risco.value > 0) vetor[1]='pratica_indicador_gestao_risco='+f.pratica_indicador_risco.value;
	else if (f.pratica_indicador_risco_resposta.value && f.pratica_indicador_risco_resposta.value > 0) vetor[1]='pratica_indicador_gestao_risco_resposta='+f.pratica_indicador_risco_resposta.value;
	else if (f.pratica_indicador_indicador.value && f.pratica_indicador_indicador.value > 0) vetor[1]='pratica_indicador_gestao_indicador='+f.pratica_indicador_indicador.value;
	else if (f.pratica_indicador_calendario.value && f.pratica_indicador_calendario.value > 0) vetor[1]='pratica_indicador_gestao_calendario='+f.pratica_indicador_calendario.value;
	else if (f.pratica_indicador_monitoramento.value && f.pratica_indicador_monitoramento.value > 0) vetor[1]='pratica_indicador_gestao_monitoramento='+f.pratica_indicador_monitoramento.value;
	else if (f.pratica_indicador_ata.value && f.pratica_indicador_ata.value > 0) vetor[1]='pratica_indicador_gestao_ata='+f.pratica_indicador_ata.value;
	else if (f.pratica_indicador_mswot.value && f.pratica_indicador_mswot.value > 0) vetor[1]='pratica_indicador_gestao_mswot='+f.pratica_indicador_mswot.value;
	else if (f.pratica_indicador_swot.value && f.pratica_indicador_swot.value > 0) vetor[1]='pratica_indicador_gestao_swot='+f.pratica_indicador_swot.value;
	else if (f.pratica_indicador_operativo.value && f.pratica_indicador_operativo.value > 0) vetor[1]='pratica_indicador_gestao_operativo='+f.pratica_indicador_operativo.value;
	else if (f.pratica_indicador_instrumento.value && f.pratica_indicador_instrumento.value > 0) vetor[1]='pratica_indicador_gestao_instrumento='+f.pratica_indicador_instrumento.value;
	else if (f.pratica_indicador_recurso.value && f.pratica_indicador_recurso.value > 0) vetor[1]='pratica_indicador_gestao_recurso='+f.pratica_indicador_recurso.value;
	else if (f.pratica_indicador_problema.value && f.pratica_indicador_problema.value > 0) vetor[1]='pratica_indicador_gestao_problema='+f.pratica_indicador_problema.value;
	else if (f.pratica_indicador_demanda.value && f.pratica_indicador_demanda.value > 0) vetor[1]='pratica_indicador_gestao_demanda='+f.pratica_indicador_demanda.value;
	else if (f.pratica_indicador_programa.value && f.pratica_indicador_programa.value > 0) vetor[1]='pratica_indicador_gestao_programa='+f.pratica_indicador_programa.value;
	else if (f.pratica_indicador_licao.value && f.pratica_indicador_licao.value > 0) vetor[1]='pratica_indicador_gestao_licao='+f.pratica_indicador_licao.value;
	else if (f.pratica_indicador_evento.value && f.pratica_indicador_evento.value > 0) vetor[1]='pratica_indicador_gestao_evento='+f.pratica_indicador_evento.value;
	else if (f.pratica_indicador_link.value && f.pratica_indicador_link.value > 0) vetor[1]='pratica_indicador_gestao_link='+f.pratica_indicador_link.value;
	else if (f.pratica_indicador_avaliacao.value && f.pratica_indicador_avaliacao.value > 0) vetor[1]='pratica_indicador_gestao_avaliacao='+f.pratica_indicador_avaliacao.value;
	else if (f.pratica_indicador_tgn.value && f.pratica_indicador_tgn.value > 0) vetor[1]='pratica_indicador_gestao_tgn='+f.pratica_indicador_tgn.value;
	else if (f.pratica_indicador_brainstorm.value && f.pratica_indicador_brainstorm.value > 0) vetor[1]='pratica_indicador_gestao_brainstorm='+f.pratica_indicador_brainstorm.value;
	else if (f.pratica_indicador_gut.value && f.pratica_indicador_gut.value > 0) vetor[1]='pratica_indicador_gestao_gut='+f.pratica_indicador_gut.value;
	else if (f.pratica_indicador_causa_efeito.value && f.pratica_indicador_causa_efeito.value > 0) vetor[1]='pratica_indicador_gestao_causa_efeito='+f.pratica_indicador_causa_efeito.value;
	else if (f.pratica_indicador_arquivo.value && f.pratica_indicador_arquivo.value > 0) vetor[1]='pratica_indicador_gestao_arquivo='+f.pratica_indicador_arquivo.value;
	else if (f.pratica_indicador_forum.value && f.pratica_indicador_forum.value > 0) vetor[1]='pratica_indicador_gestao_forum='+f.pratica_indicador_forum.value;
	else if (f.pratica_indicador_checklist.value && f.pratica_indicador_checklist.value > 0) vetor[1]='pratica_indicador_gestao_checklist='+f.pratica_indicador_checklist.value;
	else if (f.pratica_indicador_agenda.value && f.pratica_indicador_agenda.value > 0) vetor[1]='pratica_indicador_gestao_agenda='+f.pratica_indicador_agenda.value;
	else if (f.pratica_indicador_agrupamento.value && f.pratica_indicador_agrupamento.value > 0) vetor[1]='pratica_indicador_gestao_agrupamento='+f.pratica_indicador_agrupamento.value;
	else if (f.pratica_indicador_patrocinador.value && f.pratica_indicador_patrocinador.value > 0) vetor[1]='pratica_indicador_gestao_patrocinador='+f.pratica_indicador_patrocinador.value;
	else if (f.pratica_indicador_template.value && f.pratica_indicador_template.value > 0) vetor[1]='pratica_indicador_gestao_template='+f.pratica_indicador_template.value;
	else if (f.pratica_indicador_painel.value && f.pratica_indicador_painel.value > 0) vetor[1]='pratica_indicador_gestao_painel='+f.pratica_indicador_painel.value;
	else if (f.pratica_indicador_painel_odometro.value && f.pratica_indicador_painel_odometro.value > 0) vetor[1]='pratica_indicador_gestao_painel_odometro='+f.pratica_indicador_painel_odometro.value;
	else if (f.pratica_indicador_painel_composicao.value && f.pratica_indicador_painel_composicao.value > 0) vetor[1]='pratica_indicador_gestao_painel_composicao='+f.pratica_indicador_painel_composicao.value;
	else if (f.pratica_indicador_tr.value && f.pratica_indicador_tr.value > 0) vetor[1]='pratica_indicador_gestao_tr='+f.pratica_indicador_tr.value;
	else if (f.pratica_indicador_me.value && f.pratica_indicador_me.value > 0) vetor[1]='pratica_indicador_gestao_me='+f.pratica_indicador_me.value;
	else if (f.pratica_indicador_acao_item.value && f.pratica_indicador_acao_item.value > 0) vetor[1]='pratica_indicador_gestao_acao_item='+f.pratica_indicador_acao_item.value;
	else if (f.pratica_indicador_beneficio.value && f.pratica_indicador_beneficio.value > 0) vetor[1]='pratica_indicador_gestao_beneficio='+f.pratica_indicador_beneficio.value;
	else if (f.pratica_indicador_painel_slideshow.value && f.pratica_indicador_painel_slideshow.value > 0) vetor[1]='pratica_indicador_gestao_painel_slideshow='+f.pratica_indicador_painel_slideshow.value;
	else if (f.pratica_indicador_projeto_viabilidade.value && f.pratica_indicador_projeto_viabilidade.value > 0) vetor[1]='pratica_indicador_gestao_projeto_viabilidade='+f.pratica_indicador_projeto_viabilidade.value;
	else if (f.pratica_indicador_projeto_abertura.value && f.pratica_indicador_projeto_abertura.value > 0) vetor[1]='pratica_indicador_gestao_projeto_abertura='+f.pratica_indicador_projeto_abertura.value;
	else if (f.pratica_indicador_plano_gestao.value && f.pratica_indicador_plano_gestao.value > 0) vetor[1]='pratica_indicador_gestao_plano_gestao='+f.pratica_indicador_plano_gestao.value;
		
	vetor[2]='pratica_indicador_ativo=1';
		
	xajax_mudar_indicadores_ajax(document.getElementById('cia_id').value, null, vetor, 'pratica_indicador_gestao', 'pratica_indicador_gestao_indicador=pratica_indicador_id');		
	<?php } else { ?>	
	if (f.pratica_indicador_projeto.value) vetor[1]='pratica_indicador_projeto='+f.pratica_indicador_projeto.value;
	if (f.pratica_indicador_tarefa.value) vetor[1]='pratica_indicador_tarefa='+f.pratica_indicador_tarefa.value;
	if (f.pratica_indicador_pratica.value) vetor[1]='pratica_indicador_pratica='+f.pratica_indicador_pratica.value;
	if (f.pratica_indicador_tema.value) vetor[1]='pratica_indicador_tema='+f.pratica_indicador_tema.value;
	if (f.pratica_indicador_objetivo_estrategico.value) vetor[1]='pratica_indicador_objetivo_estrategico='+f.pratica_indicador_objetivo_estrategico.value;
	if (f.pratica_indicador_estrategia.value) vetor[1]='pratica_indicador_estrategia='+f.pratica_indicador_estrategia.value;
	if (f.pratica_indicador_acao.value) vetor[1]='pratica_indicador_acao='+f.pratica_indicador_acao.value;
	if (f.pratica_indicador_fator.value) vetor[1]='pratica_indicador_fator='+f.pratica_indicador_fator.value;
	if (f.pratica_indicador_perspectiva.value) vetor[1]='pratica_indicador_perspectiva='+f.pratica_indicador_perspectiva.value;
	if (f.pratica_indicador_canvas.value) vetor[1]='pratica_indicador_canvas='+f.pratica_indicador_canvas.value;
	if (f.pratica_indicador_meta.value) vetor[1]='pratica_indicador_meta='+f.pratica_indicador_meta.value;
	
	vetor[2]='pratica_indicador_ativo=1';
	
	xajax_mudar_indicadores_ajax(document.getElementById('cia_id').value, document.getElementById('lista').value, vetor, null, null);
	<?php } ?>	
	}


function mudar_indicadores_filhos(){
	var f = document.env;
	var vetor=Array();
	if (f.pesquisar.value) vetor[0]='pratica_indicador_nome LIKE \'%'+f.pesquisar.value+'%\'';
	<?php if ($Aplic->profissional) { ?>
	if (f.pratica_indicador_projeto.value && f.pratica_indicador_projeto.value > 0) vetor[1]='pratica_indicador_gestao_projeto='+f.pratica_indicador_projeto.value;
	else if (f.pratica_indicador_tarefa.value && f.pratica_indicador_tarefa.value > 0) vetor[1]='pratica_indicador_gestao_tarefa='+f.pratica_indicador_tarefa.value;
	else if (f.pratica_indicador_perspectiva.value && f.pratica_indicador_perspectiva.value > 0) vetor[1]='pratica_indicador_gestao_perspectiva='+f.pratica_indicador_perspectiva.value;
	else if (f.pratica_indicador_tema.value && f.pratica_indicador_tema.value > 0) vetor[1]='pratica_indicador_gestao_tema='+f.pratica_indicador_tema.value;
	else if (f.pratica_indicador_objetivo.value && f.pratica_indicador_objetivo.value > 0) vetor[1]='pratica_indicador_gestao_objetivo='+f.pratica_indicador_objetivo.value;
	else if (f.pratica_indicador_fator.value && f.pratica_indicador_fator.value > 0) vetor[1]='pratica_indicador_gestao_fator='+f.pratica_indicador_fator.value;
	else if (f.pratica_indicador_estrategia.value && f.pratica_indicador_estrategia.value > 0) vetor[1]='pratica_indicador_gestao_estrategia='+f.pratica_indicador_estrategia.value;
	else if (f.pratica_indicador_meta.value && f.pratica_indicador_meta.value > 0) vetor[1]='pratica_indicador_gestao_meta='+f.pratica_indicador_meta.value;
	else if (f.pratica_indicador_pratica.value && f.pratica_indicador_pratica.value > 0) vetor[1]='pratica_indicador_gestao_pratica='+f.pratica_indicador_pratica.value;
	else if (f.pratica_indicador_acao.value && f.pratica_indicador_acao.value > 0) vetor[1]='pratica_indicador_gestao_acao='+f.pratica_indicador_acao.value;
	else if (f.pratica_indicador_canvas.value && f.pratica_indicador_canvas.value > 0) vetor[1]='pratica_indicador_gestao_canvas='+f.pratica_indicador_canvas.value;
	else if (f.pratica_indicador_risco.value && f.pratica_indicador_risco.value > 0) vetor[1]='pratica_indicador_gestao_risco='+f.pratica_indicador_risco.value;
	else if (f.pratica_indicador_risco_resposta.value && f.pratica_indicador_risco_resposta.value > 0) vetor[1]='pratica_indicador_gestao_risco_resposta='+f.pratica_indicador_risco_resposta.value;
	else if (f.pratica_indicador_indicador.value && f.pratica_indicador_indicador.value > 0) vetor[1]='pratica_indicador_gestao_indicador='+f.pratica_indicador_indicador.value;
	else if (f.pratica_indicador_calendario.value && f.pratica_indicador_calendario.value > 0) vetor[1]='pratica_indicador_gestao_calendario='+f.pratica_indicador_calendario.value;
	else if (f.pratica_indicador_monitoramento.value && f.pratica_indicador_monitoramento.value > 0) vetor[1]='pratica_indicador_gestao_monitoramento='+f.pratica_indicador_monitoramento.value;
	else if (f.pratica_indicador_ata.value && f.pratica_indicador_ata.value > 0) vetor[1]='pratica_indicador_gestao_ata='+f.pratica_indicador_ata.value;
	else if (f.pratica_indicador_mswot.value && f.pratica_indicador_mswot.value > 0) vetor[1]='pratica_indicador_gestao_mswot='+f.pratica_indicador_mswot.value;
	else if (f.pratica_indicador_swot.value && f.pratica_indicador_swot.value > 0) vetor[1]='pratica_indicador_gestao_swot='+f.pratica_indicador_swot.value;
	else if (f.pratica_indicador_operativo.value && f.pratica_indicador_operativo.value > 0) vetor[1]='pratica_indicador_gestao_operativo='+f.pratica_indicador_operativo.value;
	else if (f.pratica_indicador_instrumento.value && f.pratica_indicador_instrumento.value > 0) vetor[1]='pratica_indicador_gestao_instrumento='+f.pratica_indicador_instrumento.value;
	else if (f.pratica_indicador_recurso.value && f.pratica_indicador_recurso.value > 0) vetor[1]='pratica_indicador_gestao_recurso='+f.pratica_indicador_recurso.value;
	else if (f.pratica_indicador_problema.value && f.pratica_indicador_problema.value > 0) vetor[1]='pratica_indicador_gestao_problema='+f.pratica_indicador_problema.value;
	else if (f.pratica_indicador_demanda.value && f.pratica_indicador_demanda.value > 0) vetor[1]='pratica_indicador_gestao_demanda='+f.pratica_indicador_demanda.value;
	else if (f.pratica_indicador_programa.value && f.pratica_indicador_programa.value > 0) vetor[1]='pratica_indicador_gestao_programa='+f.pratica_indicador_programa.value;
	else if (f.pratica_indicador_licao.value && f.pratica_indicador_licao.value > 0) vetor[1]='pratica_indicador_gestao_licao='+f.pratica_indicador_licao.value;
	else if (f.pratica_indicador_evento.value && f.pratica_indicador_evento.value > 0) vetor[1]='pratica_indicador_gestao_evento='+f.pratica_indicador_evento.value;
	else if (f.pratica_indicador_link.value && f.pratica_indicador_link.value > 0) vetor[1]='pratica_indicador_gestao_link='+f.pratica_indicador_link.value;
	else if (f.pratica_indicador_avaliacao.value && f.pratica_indicador_avaliacao.value > 0) vetor[1]='pratica_indicador_gestao_avaliacao='+f.pratica_indicador_avaliacao.value;
	else if (f.pratica_indicador_tgn.value && f.pratica_indicador_tgn.value > 0) vetor[1]='pratica_indicador_gestao_tgn='+f.pratica_indicador_tgn.value;
	else if (f.pratica_indicador_brainstorm.value && f.pratica_indicador_brainstorm.value > 0) vetor[1]='pratica_indicador_gestao_brainstorm='+f.pratica_indicador_brainstorm.value;
	else if (f.pratica_indicador_gut.value && f.pratica_indicador_gut.value > 0) vetor[1]='pratica_indicador_gestao_gut='+f.pratica_indicador_gut.value;
	else if (f.pratica_indicador_causa_efeito.value && f.pratica_indicador_causa_efeito.value > 0) vetor[1]='pratica_indicador_gestao_causa_efeito='+f.pratica_indicador_causa_efeito.value;
	else if (f.pratica_indicador_arquivo.value && f.pratica_indicador_arquivo.value > 0) vetor[1]='pratica_indicador_gestao_arquivo='+f.pratica_indicador_arquivo.value;
	else if (f.pratica_indicador_forum.value && f.pratica_indicador_forum.value > 0) vetor[1]='pratica_indicador_gestao_forum='+f.pratica_indicador_forum.value;
	else if (f.pratica_indicador_checklist.value && f.pratica_indicador_checklist.value > 0) vetor[1]='pratica_indicador_gestao_checklist='+f.pratica_indicador_checklist.value;
	else if (f.pratica_indicador_agenda.value && f.pratica_indicador_agenda.value > 0) vetor[1]='pratica_indicador_gestao_agenda='+f.pratica_indicador_agenda.value;
	else if (f.pratica_indicador_agrupamento.value && f.pratica_indicador_agrupamento.value > 0) vetor[1]='pratica_indicador_gestao_agrupamento='+f.pratica_indicador_agrupamento.value;
	else if (f.pratica_indicador_patrocinador.value && f.pratica_indicador_patrocinador.value > 0) vetor[1]='pratica_indicador_gestao_patrocinador='+f.pratica_indicador_patrocinador.value;
	else if (f.pratica_indicador_template.value && f.pratica_indicador_template.value > 0) vetor[1]='pratica_indicador_gestao_template='+f.pratica_indicador_template.value;
	else if (f.pratica_indicador_painel.value && f.pratica_indicador_painel.value > 0) vetor[1]='pratica_indicador_gestao_painel='+f.pratica_indicador_painel.value;
	else if (f.pratica_indicador_painel_odometro.value && f.pratica_indicador_painel_odometro.value > 0) vetor[1]='pratica_indicador_gestao_painel_odometro='+f.pratica_indicador_painel_odometro.value;
	else if (f.pratica_indicador_painel_composicao.value && f.pratica_indicador_painel_composicao.value > 0) vetor[1]='pratica_indicador_gestao_painel_composicao='+f.pratica_indicador_painel_composicao.value;
	else if (f.pratica_indicador_tr.value && f.pratica_indicador_tr.value > 0) vetor[1]='pratica_indicador_gestao_tr='+f.pratica_indicador_tr.value;
	else if (f.pratica_indicador_me.value && f.pratica_indicador_me.value > 0) vetor[1]='pratica_indicador_gestao_me='+f.pratica_indicador_me.value;
	else if (f.pratica_indicador_acao_item.value && f.pratica_indicador_acao_item.value > 0) vetor[1]='pratica_indicador_gestao_acao_item='+f.pratica_indicador_acao_item.value;
	else if (f.pratica_indicador_beneficio.value && f.pratica_indicador_beneficio.value > 0) vetor[1]='pratica_indicador_gestao_beneficio='+f.pratica_indicador_beneficio.value;
	else if (f.pratica_indicador_painel_slideshow.value && f.pratica_indicador_painel_slideshow.value > 0) vetor[1]='pratica_indicador_gestao_painel_slideshow='+f.pratica_indicador_painel_slideshow.value;
	else if (f.pratica_indicador_projeto_viabilidade.value && f.pratica_indicador_projeto_viabilidade.value > 0) vetor[1]='pratica_indicador_gestao_projeto_viabilidade='+f.pratica_indicador_projeto_viabilidade.value;
	else if (f.pratica_indicador_projeto_abertura.value && f.pratica_indicador_projeto_abertura.value > 0) vetor[1]='pratica_indicador_gestao_projeto_abertura='+f.pratica_indicador_projeto_abertura.value;
	else if (f.pratica_indicador_plano_gestao.value && f.pratica_indicador_plano_gestao.value > 0) vetor[1]='pratica_indicador_gestao_plano_gestao='+f.pratica_indicador_plano_gestao.value;
	
	vetor[2]='pratica_indicador_ativo=1';
	
	xajax_mudar_indicadores_ajax(document.getElementById('cia_id').value, document.getElementById('lista').value, vetor, 'pratica_indicador_gestao', 'pratica_indicador_gestao_indicador=pratica_indicador_id');		
	<?php } else { ?>	
	if (f.pratica_indicador_projeto.value) vetor[1]='pratica_indicador_projeto='+f.pratica_indicador_projeto.value;
	if (f.pratica_indicador_tarefa.value) vetor[1]='pratica_indicador_tarefa='+f.pratica_indicador_tarefa.value;
	if (f.pratica_indicador_pratica.value) vetor[1]='pratica_indicador_pratica='+f.pratica_indicador_pratica.value;
	if (f.pratica_indicador_tema.value) vetor[1]='pratica_indicador_tema='+f.pratica_indicador_tema.value;
	if (f.pratica_indicador_objetivo_estrategico.value) vetor[1]='pratica_indicador_objetivo_estrategico='+f.pratica_indicador_objetivo_estrategico.value;
	if (f.pratica_indicador_estrategia.value) vetor[1]='pratica_indicador_estrategia='+f.pratica_indicador_estrategia.value;
	if (f.pratica_indicador_acao.value) vetor[1]='pratica_indicador_acao='+f.pratica_indicador_acao.value;
	if (f.pratica_indicador_fator.value) vetor[1]='pratica_indicador_fator='+f.pratica_indicador_fator.value;
	if (f.pratica_indicador_perspectiva.value) vetor[1]='pratica_indicador_perspectiva='+f.pratica_indicador_perspectiva.value;
	if (f.pratica_indicador_canvas.value) vetor[1]='pratica_indicador_canvas='+f.pratica_indicador_canvas.value;
	if (f.pratica_indicador_meta.value) vetor[1]='pratica_indicador_meta='+f.pratica_indicador_meta.value;
	
	vetor[2]='pratica_indicador_ativo=1';
	
	xajax_mudar_indicadores_ajax(document.getElementById('cia_id').value, document.getElementById('lista').value, vetor, null, null);
	<?php } ?>	
	}

function float2casas_decimais(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);

	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	ret=num+'.'+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
	}





function mostrar(){
	limpar_tudo();
	esconder_tipo();
	if (document.getElementById('tipo_relacao').value){
		document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
		}
	}

function esconder_tipo(){
	document.getElementById('projeto').style.display='none';
	document.getElementById('tarefa').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('objetivo').style.display='none';	
	document.getElementById('fator').style.display='none';	
	document.getElementById('estrategia').style.display='none';
	document.getElementById('meta').style.display='none';
	document.getElementById('pratica').style.display='none';
	document.getElementById('acao').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('risco').style.display='none';
	document.getElementById('risco_resposta').style.display='none';
	document.getElementById('indicador').style.display='none';
	document.getElementById('calendario').style.display='none';
	document.getElementById('monitoramento').style.display='none';
	document.getElementById('ata').style.display='none';
	document.getElementById('mswot').style.display='none';
	document.getElementById('swot').style.display='none';
	document.getElementById('operativo').style.display='none';
	document.getElementById('instrumento').style.display='none';
	document.getElementById('recurso').style.display='none';
	document.getElementById('problema').style.display='none';
	document.getElementById('demanda').style.display='none';
	document.getElementById('programa').style.display='none';
	document.getElementById('licao').style.display='none';
	document.getElementById('evento').style.display='none';
	document.getElementById('link').style.display='none';
	document.getElementById('avaliacao').style.display='none';
	document.getElementById('tgn').style.display='none';
	document.getElementById('brainstorm').style.display='none';
	document.getElementById('gut').style.display='none';
	document.getElementById('causa_efeito').style.display='none';
	document.getElementById('arquivo').style.display='none';
	document.getElementById('forum').style.display='none';
	document.getElementById('checklist').style.display='none';
	document.getElementById('agenda').style.display='none';
	document.getElementById('agrupamento').style.display='none';
	document.getElementById('patrocinador').style.display='none';
	document.getElementById('template').style.display='none';
	document.getElementById('painel').style.display='none';
	document.getElementById('painel_odometro').style.display='none';
	document.getElementById('painel_composicao').style.display='none';
	document.getElementById('tr').style.display='none';
	document.getElementById('me').style.display='none';
	document.getElementById('acao_item').style.display='none';
	document.getElementById('beneficio').style.display='none';
	document.getElementById('painel_slideshow').style.display='none';
	document.getElementById('projeto_viabilidade').style.display='none';
	document.getElementById('projeto_abertura').style.display='none';
	document.getElementById('plano_gestao').style.display='none';
	}
	
	

<?php  if ($Aplic->profissional) { ?>
	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		mudar_indicadores();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		mudar_indicadores();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_template.value = chave;
		document.env.template_nome.value = valor;
		mudar_indicadores();
		}		
<?php } ?>

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	mudar_indicadores();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('cia_id').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.pratica_indicador_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	mudar_indicadores();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	mudar_indicadores();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_tema.value = chave;
	document.env.tema_nome.value = valor;
	mudar_indicadores();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	mudar_indicadores();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_fator.value = chave;
	document.env.fator_nome.value = valor;
	mudar_indicadores();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	mudar_indicadores();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_meta.value = chave;
	document.env.meta_nome.value = valor;
	mudar_indicadores();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	mudar_indicadores();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&edicao=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	mudar_indicadores();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_acao.value = chave;
	document.env.acao_nome.value = valor;
	mudar_indicadores();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	mudar_indicadores();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_risco.value = chave;
	document.env.risco_nome.value = valor;
	mudar_indicadores();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	mudar_indicadores();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	mudar_indicadores();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	mudar_indicadores();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reuni?o', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('cia_id').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_ata.value = chave;
	document.env.ata_nome.value = valor;
	mudar_indicadores();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&edicao=1&chamar_volta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('cia_id').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_mswot.value = chave;
	document.env.mswot_nome.value = valor;
	mudar_indicadores();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Cam?po SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_swot.value = chave;
	document.env.swot_nome.value = valor;
	mudar_indicadores();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	mudar_indicadores();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jur?dico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, 'Instrumento Jur?dico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	mudar_indicadores();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	mudar_indicadores();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_problema.value = chave;
	document.env.problema_nome.value = valor;
	mudar_indicadores();
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	mudar_indicadores();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_programa.value = chave;
	document.env.programa_nome.value = valor;
	mudar_indicadores();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_licao.value = chave;
	document.env.licao_nome.value = valor;
	mudar_indicadores();
	}

	
function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_evento.value = chave;
	document.env.evento_nome.value = valor;
	mudar_indicadores();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_link.value = chave;
	document.env.link_nome.value = valor;
	mudar_indicadores();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avalia??o', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, 'Avalia??o','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	mudar_indicadores();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	mudar_indicadores();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	mudar_indicadores();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 1000, 700, 'm=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, 'Matriz G.U.T.','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_gut.value = chave;
	document.env.gut_nome.value = valor;
	mudar_indicadores();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	mudar_indicadores();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	mudar_indicadores();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('F?rum', 1000, 700, 'm=foruns&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, 'F?rum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_forum.value = chave;
	document.env.forum_nome.value = valor;
	mudar_indicadores();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	mudar_indicadores();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	mudar_indicadores();
	}

function popPainel() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, window.setPainel, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPainel(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_painel.value = chave;
	document.env.painel_nome.value = valor;
	mudar_indicadores();
	}		
	
function popOdometro() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Od?metro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, window.setOdometro, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, 'Od?metro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOdometro(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_painel_odometro.value = chave;
	document.env.painel_odometro_nome.value = valor;
	mudar_indicadores();
	}			
	
function popComposicaoPaineis() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composi??o de Pain?is', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, window.setComposicaoPaineis, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, 'Composi??o de Pain?is','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setComposicaoPaineis(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_painel_composicao.value = chave;
	document.env.painel_composicao_nome.value = valor;
	mudar_indicadores();
	}	
	
function popTR() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, window.setTR, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTR(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_tr.value = chave;
	document.env.tr_nome.value = valor;
	mudar_indicadores();
	}	
		
function popMe() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, window.setMe, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMe(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_me.value = chave;
	document.env.me_nome.value = valor;
	mudar_indicadores();
	}		
		
function popAcaoItem() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('cia_id').value, window.setAcaoItem, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('cia_id').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcaoItem(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_acao_item.value = chave;
	document.env.acao_item_nome.value = valor;
	mudar_indicadores();
	}		

function popBeneficio() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('cia_id').value, window.setBeneficio, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBeneficio(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_beneficio.value = chave;
	document.env.beneficio_nome.value = valor;
	mudar_indicadores();
	}	

function popSlideshow() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composi??es', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('cia_id').value, window.setSlideshow, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('cia_id').value, 'Slideshow de Composi??es','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setSlideshow(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_painel_slideshow.value = chave;
	document.env.painel_slideshow_nome.value = valor;
	mudar_indicadores();
	}	

function popViabilidade() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('cia_id').value, window.setViabilidade, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('cia_id').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setViabilidade(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_projeto_viabilidade.value = chave;
	document.env.projeto_viabilidade_nome.value = valor;
	mudar_indicadores();
	}	
	
function popAbertura() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('cia_id').value, window.setAbertura, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('cia_id').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAbertura(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_projeto_abertura.value = chave;
	document.env.projeto_abertura_nome.value = valor;
	mudar_indicadores();
	}		
	
function popPlanejamento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estrat?gico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&edicao=1&selecao=1&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('cia_id').value, window.setPlanejamento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('cia_id').value, 'Planejamento Estrat?gico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPlanejamento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_plano_gestao.value = chave;
	document.env.plano_gestao_nome.value = valor;
	mudar_indicadores();
	}		

function limpar_tudo(){
	document.env.projeto_nome.value = '';
	document.env.pratica_indicador_projeto.value = null;
	document.env.pratica_indicador_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.pratica_indicador_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.pratica_indicador_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.pratica_indicador_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.pratica_indicador_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.pratica_indicador_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.pratica_indicador_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.pratica_indicador_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.pratica_indicador_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.pratica_indicador_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.pratica_indicador_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.pratica_indicador_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.pratica_indicador_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.pratica_indicador_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.pratica_indicador_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.pratica_indicador_ata.value = null;
	document.env.ata_nome.value = '';
	document.env.pratica_indicador_mswot.value = null;
	document.env.mswot_nome.value = '';
	document.env.pratica_indicador_swot.value = null;
	document.env.swot_nome.value = '';
	document.env.pratica_indicador_operativo.value = null;
	document.env.operativo_nome.value = '';
	document.env.pratica_indicador_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.pratica_indicador_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.pratica_indicador_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.pratica_indicador_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.pratica_indicador_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.pratica_indicador_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.pratica_indicador_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.pratica_indicador_link.value = null;
	document.env.link_nome.value = '';
	document.env.pratica_indicador_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.pratica_indicador_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.pratica_indicador_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.pratica_indicador_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.pratica_indicador_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.pratica_indicador_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.pratica_indicador_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.pratica_indicador_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.pratica_indicador_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.pratica_indicador_agrupamento.value = null;
	document.env.agrupamento_nome.value = '';
	document.env.pratica_indicador_patrocinador.value = null;
	document.env.patrocinador_nome.value = '';
	document.env.pratica_indicador_template.value = null;
	document.env.template_nome.value = '';
	document.env.pratica_indicador_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.pratica_indicador_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.pratica_indicador_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	document.env.pratica_indicador_tr.value = null;
	document.env.tr_nome.value = '';
	document.env.pratica_indicador_me.value = null;
	document.env.me_nome.value = '';
	document.env.pratica_indicador_acao_item.value = null;
	document.env.acao_item_nome.value = '';
	document.env.pratica_indicador_beneficio.value = null;
	document.env.beneficio_nome.value = '';
	document.env.pratica_indicador_painel_slideshow.value = null;
	document.env.painel_slideshow_nome.value = '';
	document.env.pratica_indicador_projeto_viabilidade.value = null;
	document.env.projeto_viabilidade_nome.value = '';
	document.env.pratica_indicador_projeto_abertura.value = null;
	document.env.projeto_abertura_nome.value = '';
	document.env.pratica_indicador_plano_gestao.value = null;
	document.env.plano_gestao_nome.value = '';
	}

</script>
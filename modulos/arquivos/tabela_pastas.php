<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic, $perms, $arquivo_usuario, $pastas_permitidas, $pastas_negadas, $tab, $m, $a,  $mostrarProjeto, $cia_id, $dept_id, $arquivo_pasta_id,
	$projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id,
	$os_id;


$pagina=getParam($_REQUEST, 'pagina', 1);

if (!isset($cia_id)) $cia_id=getParam($_REQUEST, 'cia_id', null);

$xpg_tamanhoPagina = $config['qnt_arquivos'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$arquivo_tipos = getSisValor('TipoArquivo');

$sql = new BDConsulta();
$sql->adTabela('arquivo');
$sql->adCampo('count(DISTINCT arquivo.arquivo_id)');
$sql->esqUnir('usuarios', 'u', 'u.usuario_id = arquivo_usuario');
$sql->esqUnir('contatos', 'contatos', 'u.usuario_contato = contatos.contato_id');
if ($Aplic->profissional){
	$sql->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivo.arquivo_id');
	if ($tarefa_id) $sql->adOnde('arquivo_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=arquivo_gestao_tarefa');
		$sql->adOnde('arquivo_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('arquivo_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('arquivo_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('arquivo_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('arquivo_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('arquivo_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('arquivo_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('arquivo_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('arquivo_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('arquivo_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('arquivo_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('arquivo_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('arquivo_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('arquivo_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('arquivo_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('arquivo_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('arquivo_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('arquivo_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('arquivo_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('arquivo_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('arquivo_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('arquivo_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('arquivo_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('arquivo_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('arquivo_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('arquivo_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('arquivo_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('arquivo_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('arquivo_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('arquivo_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('arquivo_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('arquivo_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('arquivo_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('arquivo_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('arquivo_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('arquivo_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('arquivo_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('arquivo_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('arquivo_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('arquivo_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('arquivo_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('arquivo_gestao_tr IN ('.$tr_id.')');
	elseif ($me_id) $sql->adOnde('arquivo_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('arquivo_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('arquivo_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('arquivo_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('arquivo_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('arquivo_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('arquivo_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('arquivo_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('arquivo_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('arquivo_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('arquivo_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('arquivo_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('arquivo_gestao_pdcl_item IN ('.$pdcl_item_id.')');
	elseif ($os_id) $sql->adOnde('arquivo_gestao_os IN ('.$os_id.')');
	
	else if ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	else $sql->adOnde('arquivo_gestao_usuario=0 OR arquivo_gestao_usuario IS NULL OR arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	}
else {
	if ($tarefa_id) $sql->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
	else if ($projeto_id) $sql->adOnde('arquivo_projeto IN('.$projeto_id.')');
	else if ($pratica_id) $sql->adOnde('arquivo_pratica = '.(int)$pratica_id);
	else if ($demanda_id) $sql->adOnde('arquivo_demanda = '.(int)$demanda_id);
	else if ($instrumento_id) $sql->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
	else if ($pratica_indicador_id) $sql->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
	else if ($tema_id) $sql->adOnde('arquivo_tema = '.(int)$tema_id);
	else if ($objetivo_id) $sql->adOnde('arquivo_objetivo = '.(int)$objetivo_id);
	else if ($pg_estrategia_id) $sql->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
	else if ($fator_id) $sql->adOnde('arquivo_fator = '.(int)$fator_id);
	else if ($pg_meta_id) $sql->adOnde('arquivo_meta = '.(int)$pg_meta_id);
	else if ($pg_perspectiva_id) $sql->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
	else if ($canvas_id) $sql->adOnde('arquivo_canvas = '.(int)$canvas_id);
	else if ($calendario_id) $sql->adOnde('arquivo_calendario = '.(int)$calendario_id);
	else if ($ata_id) $sql->adOnde('arquivo_ata= '.(int)$ata_id);
	else if ($plano_acao_id) $sql->adOnde('arquivo_acao = '.(int)$plano_acao_id);
	else if ($arquivo_usuario) $sql->adOnde('arquivo_usuario = '.(int)$Aplic->usuario_id);
	else $sql->adOnde('arquivo_usuario=0 OR arquivo_usuario IS NULL OR arquivo_usuario = '.(int)$Aplic->usuario_id);
	}
$resultados=$sql->Resultado();
$sql->limpar();	



$xpg_totalregistros = $resultados;
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 1;
$editar=$podeEditar = $Aplic->checarModulo('arquivos', 'editar');
$objPasta = new CPastaArquivo();
if ($arquivo_pasta_id > 0) {
	$objPasta->load($arquivo_pasta_id);
	$msg = '';
	$permiteEditar=permiteEditarPasta($objPasta->arquivo_pasta_acesso, $objPasta->arquivo_pasta_id);
	$editar=($podeEditar&&$permiteEditar);
	echo '<table border=0 cellpadding=0 cellspacing=1 width="100%"><tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id=0\');">'.imagem('icones/inicio.png', 'Voltar para a Ra?z', 'Clique neste ?cone '.imagem('icones/inicio.png').' para voltar ? ra?z do diret?rio.').'</a>';
	echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id='.$objPasta->arquivo_pasta_superior.'\');">'.imagem('icones/voltar.png','Pasta Superior', 'Clique neste ?cone '.imagem('icones/voltar.png').' para volta ? pasta superior.').'</a>';
	if ($editar) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&tab='.$tab.'&a=editar_pasta&arquivo_pasta_id='.$objPasta->arquivo_pasta_id.'\');" >'.imagem('icones/editar.gif','Editar Pasta', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar a pasta.').'</a>';
	echo '</td></tr></table>';
	}

echo '<table width="100%" class="std"><tr><td>';	
echo '<div id="pasta-lista">';
echo '<span class="pasta-nome-atual">'.imagem('icones/pasta_p.png').(isset($objPasta->arquivo_pasta_nome) && $objPasta->arquivo_pasta_nome ? $objPasta->arquivo_pasta_nome : 'Raiz').'</span>';
if (isset($objPasta->arquivo_pasta_descricao) && $objPasta->arquivo_pasta_descricao) echo '<p>'.$objPasta->arquivo_pasta_descricao.'</p>';
if (contarArquivos($arquivo_pasta_id) > 0) echo mostrarArquivos($arquivo_pasta_id);
elseif ($arquivo_pasta_id != 0) echo 'nenhum arquivo';
echo getPastas($arquivo_pasta_id);
echo '</div>';
echo '</td></tr>';	
echo '</table>';


function getPastas($superior, $nivel = 0) {
	global $m, $a, $u, $Aplic, $perms, $pastas_permitidas, $pastas_negadas, $tab, $cia_id, $dept_id, $arquivo_usuario, $arquivo_tipos,
	$projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id,
	$os_id;
	
	
	$podeEditar = $Aplic->checarModulo('arquivos', 'editar');
	$sql = new BDConsulta();
	$sql->adTabela('arquivo_pasta');
	$sql->adCampo('arquivo_pasta.*');
	if ($Aplic->profissional){
		$sql->esqUnir('arquivo_pasta_gestao','arquivo_pasta_gestao','arquivo_pasta_gestao_pasta = arquivo_pasta.arquivo_pasta_id');
		if ($tarefa_id) $sql->adOnde('arquivo_pasta_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=arquivo_pasta_gestao_tarefa');
			$sql->adOnde('arquivo_pasta_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_pasta_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('arquivo_pasta_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('arquivo_pasta_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('arquivo_pasta_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('arquivo_pasta_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('arquivo_pasta_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('arquivo_pasta_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('arquivo_pasta_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('arquivo_pasta_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('arquivo_pasta_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('arquivo_pasta_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('arquivo_pasta_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('arquivo_pasta_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('arquivo_pasta_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('arquivo_pasta_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('arquivo_pasta_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('arquivo_pasta_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('arquivo_pasta_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('arquivo_pasta_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('arquivo_pasta_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('arquivo_pasta_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('arquivo_pasta_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('arquivo_pasta_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('arquivo_pasta_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('arquivo_pasta_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('arquivo_pasta_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('arquivo_pasta_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('arquivo_pasta_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('arquivo_pasta_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('arquivo_pasta_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('arquivo_pasta_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('arquivo_pasta_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('arquivo_pasta_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('arquivo_pasta_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('arquivo_pasta_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('arquivo_pasta_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('arquivo_pasta_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('arquivo_pasta_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('arquivo_pasta_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('arquivo_pasta_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('arquivo_pasta_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('arquivo_pasta_gestao_tr IN ('.$tr_id.')');
		elseif ($me_id) $sql->adOnde('arquivo_pasta_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('arquivo_pasta_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('arquivo_pasta_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('arquivo_pasta_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('arquivo_pasta_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('arquivo_pasta_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('arquivo_pasta_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('arquivo_pasta_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('arquivo_pasta_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('arquivo_pasta_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('arquivo_pasta_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('arquivo_pasta_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('arquivo_pasta_gestao_pdcl_item IN ('.$pdcl_item_id.')');
		elseif ($os_id) $sql->adOnde('arquivo_pasta_gestao_os IN ('.$os_id.')');
		
		
		elseif ($arquivo_usuario) $sql->adOnde('arquivo_pasta_gestao_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_pasta_gestao_usuario=0 OR arquivo_pasta_gestao_usuario IS NULL OR arquivo_pasta_gestao_usuario = '.(int)$Aplic->usuario_id);
		}
	else {
		if ($tarefa_id) $sql->adOnde('arquivo_pasta_tarefa IN ('.$tarefa_id.')');
		else if ($projeto_id) $sql->adOnde('arquivo_pasta_projeto IN('.$projeto_id.')');
		else if ($pratica_id) $sql->adOnde('arquivo_pasta_pratica = '.(int)$pratica_id);
		else if ($demanda_id) $sql->adOnde('arquivo_pasta_demanda = '.(int)$demanda_id);
		else if ($instrumento_id) $sql->adOnde('arquivo_pasta_instrumento = '.(int)$instrumento_id);
		else if ($pratica_indicador_id) $sql->adOnde('arquivo_pasta_indicador = '.(int)$pratica_indicador_id);
		else if ($tema_id) $sql->adOnde('arquivo_pasta_tema = '.(int)$tema_id);
		else if ($objetivo_id) $sql->adOnde('arquivo_pasta_objetivo = '.(int)$objetivo_id);
		else if ($pg_estrategia_id) $sql->adOnde('arquivo_pasta_estrategia = '.(int)$pg_estrategia_id);
		else if ($fator_id) $sql->adOnde('arquivo_pasta_fator = '.(int)$fator_id);
		else if ($pg_meta_id) $sql->adOnde('arquivo_pasta_meta = '.(int)$pg_meta_id);
		else if ($pg_perspectiva_id) $sql->adOnde('arquivo_pasta_perspectiva = '.(int)$pg_perspectiva_id);
		else if ($canvas_id) $sql->adOnde('arquivo_pasta_canvas = '.(int)$canvas_id);
		else if ($calendario_id) $sql->adOnde('arquivo_pasta_calendario = '.(int)$calendario_id);
		else if ($ata_id) $sql->adOnde('arquivo_pasta_ata= '.(int)$ata_id);
		else if ($plano_acao_id) $sql->adOnde('arquivo_pasta_acao = '.(int)$plano_acao_id);
		else if ($arquivo_usuario) $sql->adOnde('arquivo_pasta_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_pasta_usuario=0 OR arquivo_pasta_usuario IS NULL OR arquivo_pasta_usuario = '.(int)$Aplic->usuario_id);
		}

	if ($superior) $sql->adOnde('arquivo_pasta_superior = \''.$superior.'\'');
	else  $sql->adOnde('arquivo_pasta_superior IS NULL');
	
	$sql->adOrdem('arquivo_pasta_nome');
	$sql->adGrupo('arquivo_pasta.arquivo_pasta_id');	
	$pastas = $sql->Lista();
	$sql->limpar();
	$s = '';

	foreach ($pastas as $linha) {
		if (permiteAcessarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id'])){
			$permiteEditar=permiteEditarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id']);
			$editar=($podeEditar&&$permiteEditar);
			$arquivo_contagem = contarArquivos($linha['arquivo_pasta_id']);
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			if ($linha['arquivo_pasta_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Descri??o</b></td><td>'.$linha['arquivo_pasta_descricao'].'</td></tr>';
			$dentro .= '</table>';
			$dentro .= '<br>Clique para abrir esta pasta.';
			$s .= '<ul><li><table width="100%"><tr><td><span class="pasta-nome">';
			for ($i=0 ; $i < $nivel ; $i++) $s .= '&nbsp;';
			$s .=($m == 'arquivos' ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');" name="ff'.$linha['arquivo_pasta_id'].'">'.imagem('icones/pasta_p.png', 'Pasta', 'Clique neste ?cone '.imagem('icones/pasta_p.png').' para mostrar os arquivos dentro da pasta.').'</a><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');" name="ff'.$linha['arquivo_pasta_id'].'">'.dica($linha['arquivo_pasta_nome'], $dentro).$linha['arquivo_pasta_nome'].dicaF().'</a>' : imagem('icones/pasta_p.png').$linha['arquivo_pasta_nome']);
			if ($arquivo_contagem > 0) {
				$plural=(($arquivo_contagem < 2) ? '' : 's');
				$s .= ' <a href="javascript: void(0);" onClick="expandir(\'arquivos_'.$linha['arquivo_pasta_id'].'\')" class="tem-arquivos">'.dica("Ver o$plural Arquivo$plural", "Clique em cima para visualizar o$plural ".(($arquivo_contagem < 2) ? "" :"$arquivo_contagem ")."arquivo$plural")."($arquivo_contagem arquivo$plural)" .dicaF().'</a>';
				}
			$s .= '</td><form name="frm_remover_pasta_'.$linha['arquivo_pasta_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_pasta_aed" /><input type="hidden" name="del" value="1" /><input type="hidden" name="arquivo_pasta_id" value="'.$linha['arquivo_pasta_id'].'" /></form>';
			$s .= '<td align="right" width="64">';
			if ($editar) $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar_pasta&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');">'.imagem('icones/editar.gif','Editar', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar esta pasta.').'</a><a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar_pasta&arquivo_pasta_superior='.$linha['arquivo_pasta_id'].'&arquivo_pasta_id=0\');">'.imagem('icones/adicionar.png', 'Nova Pasta', 'Clique neste ?cone '.imagem('icones/adicionar.png').' para adicionar uma nova subpasta.').'</a><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta pasta?\')) {document.frm_remover_pasta_'.$linha['arquivo_pasta_id'].'.submit()}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir esta pasta.').'</a><a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&arquivo_pasta='.$linha['arquivo_pasta_id'].'&arquivo_id=0\');">'.imagem('icones/dentroPasta.png', 'Novo Arquivo', 'Clique neste ?cone '.imagem('icones/dentroPasta.png').' para adicionar novo arquivo ? pasta.').'</a>';
			$s .= '</td></tr></table></span>';
			if ($arquivo_contagem > 0) $s .= '<div class="arquivos-list" id="arquivos_'.$linha['arquivo_pasta_id'].'" style="display: none;">'.mostrarArquivos($linha['arquivo_pasta_id']).'</div>';
			$s .=getPastas($linha['arquivo_pasta_id'], $nivel + 1);
			$s .= '</li></ul>';
			}
		}
	return $s;
	}

function contarArquivos($arquivo_pasta_id) {
	global $m, $a, $u, $Aplic, $cia_id, $tab, $arquivo_usuario, $mostrarProjeto, $arquivo_tipos,
	$projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id,
	$os_id;
		
	$sql = new BDConsulta();
	$sql->adTabela('arquivo');
	$sql->adCampo('count(DISTINCT arquivo.arquivo_id)');
	$sql->esqUnir('arquivo_pasta', 'ff', 'ff.arquivo_pasta_id = arquivo_pasta');
	if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
	if ($Aplic->profissional){
		$sql->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivo.arquivo_id');
		if ($tarefa_id) $sql->adOnde('arquivo_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=arquivo_gestao_tarefa');
			$sql->adOnde('arquivo_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('arquivo_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('arquivo_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('arquivo_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('arquivo_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('arquivo_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('arquivo_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('arquivo_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('arquivo_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('arquivo_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('arquivo_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('arquivo_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('arquivo_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('arquivo_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('arquivo_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('arquivo_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('arquivo_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('arquivo_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('arquivo_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('arquivo_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('arquivo_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('arquivo_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('arquivo_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('arquivo_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('arquivo_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('arquivo_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('arquivo_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('arquivo_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('arquivo_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('arquivo_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('arquivo_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('arquivo_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('arquivo_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('arquivo_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('arquivo_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('arquivo_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('arquivo_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('arquivo_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('arquivo_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('arquivo_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('arquivo_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('arquivo_gestao_tr IN ('.$tr_id.')');
		elseif ($me_id) $sql->adOnde('arquivo_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('arquivo_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('arquivo_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('arquivo_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('arquivo_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('arquivo_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('arquivo_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('arquivo_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('arquivo_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('arquivo_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('arquivo_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('arquivo_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('arquivo_gestao_pdcl_item IN ('.$pdcl_item_id.')');
		elseif ($os_id) $sql->adOnde('arquivo_gestao_os IN ('.$os_id.')');
		
		
		else if ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_gestao_usuario=0 OR arquivo_gestao_usuario IS NULL OR arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		}
	else {
		if ($tarefa_id) $sql->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
		else if ($projeto_id) $sql->adOnde('arquivo_projeto IN('.$projeto_id.')');
		else if ($pratica_id) $sql->adOnde('arquivo_pratica = '.(int)$pratica_id);
		else if ($demanda_id) $sql->adOnde('arquivo_demanda = '.(int)$demanda_id);
		else if ($instrumento_id) $sql->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
		else if ($pratica_indicador_id) $sql->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
		else if ($tema_id) $sql->adOnde('arquivo_tema = '.(int)$tema_id);
		else if ($objetivo_id) $sql->adOnde('arquivo_objetivo = '.(int)$objetivo_id);
		else if ($pg_estrategia_id) $sql->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
		else if ($fator_id) $sql->adOnde('arquivo_fator = '.(int)$fator_id);
		else if ($pg_meta_id) $sql->adOnde('arquivo_meta = '.(int)$pg_meta_id);
		else if ($pg_perspectiva_id) $sql->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
		else if ($canvas_id) $sql->adOnde('arquivo_canvas = '.(int)$canvas_id);
		else if ($calendario_id) $sql->adOnde('arquivo_calendario = '.(int)$calendario_id);
		else if ($ata_id) $sql->adOnde('arquivo_ata= '.(int)$ata_id);
		else if ($plano_acao_id) $sql->adOnde('arquivo_acao = '.(int)$plano_acao_id);
		else if ($arquivo_usuario) $sql->adOnde('arquivo_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_usuario=0 OR arquivo_usuario IS NULL OR arquivo_usuario = '.(int)$Aplic->usuario_id);
		}

	$arquivos_na_pasta = $sql->Resultado();
	$sql->limpar();
	return $arquivos_na_pasta;
	}

function mostrarArquivos($arquivo_pasta_id) {
	global $m, $a, $tab, $Aplic, $xpg_min, $xpg_tamanhoPagina, $pratica_id, $demanda_id, $instrumento_id, $plano_acao_id, $tema_id, $pratica_indicador_id, $arquivo_usuario, $mostrarProjeto, $arquivo_tipos, $objPasta, $xpg_totalregistros, $xpg_total_paginas, $pagina, $cia_id, $dept_id, $config, $podeAcessar,$perms,
	$projeto_id,
	$tarefa_id,
	$pg_perspectiva_id,
	$tema_id,
	$objetivo_id,
	$fator_id,
	$pg_estrategia_id,
	$pg_meta_id,
	$pratica_id,
	$pratica_indicador_id,
	$plano_acao_id,
	$canvas_id,
	$risco_id,
	$risco_resposta_id,
	$calendario_id,
	$monitoramento_id,
	$ata_id,
	$mswot_id,
	$swot_id,
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id,
	$os_id;
	
	
	$extra='';
	$extra.=($cia_id ? '&cia_id='.(int)$cia_id : '');
	$extra.=($dept_id ? '&dept_id='.(int)$dept_id : '');
	$extra.=($tarefa_id ? '&tarefa_id='.(int)$tarefa_id  : '');
	$extra.=($projeto_id ? '&projeto_id='.(int)$projeto_id  : '');
	$extra.=($pg_perspectiva_id ? '&pg_perspectiva_id='.(int)$pg_perspectiva_id  : '');
	$extra.=($tema_id ? '&tema_id='.(int)$tema_id  : '');
	$extra.=($objetivo_id ? '&objetivo_id='.(int)$objetivo_id  : '');
	$extra.=($fator_id ? '&fator_id='.(int)$fator_id  : '');
	$extra.=($pg_estrategia_id? '&pg_estrategia_id='.(int)$pg_estrategia_id : '');
	$extra.=($pg_meta_id ? '&pg_meta_id='.(int)$pg_meta_id  : '');
	$extra.=($pratica_id ? '&pratica_id='.(int)$pratica_id  : '');
	$extra.=($pratica_indicador_id ? '&pratica_indicador_id='.(int)$pratica_indicador_id  : '');
	$extra.=($plano_acao_id ? '&plano_acao_id='.(int)$plano_acao_id  : '');
	$extra.=($canvas_id ? '&canvas_id='.(int)$canvas_id  : '');
	$extra.=($risco_id? '&risco_id='.(int)$risco_id : '');
	$extra.=($risco_resposta_id? '&risco_resposta_id='.(int)$risco_resposta_id : '');
	$extra.=($calendario_id ? '&calendario_id='.(int)$calendario_id  : '');
	$extra.=($monitoramento_id ? '&monitoramento_id='.(int)$monitoramento_id  : '');
	$extra.=($ata_id ? '&ata_id='.(int)$ata_id  : '');
	$extra.=($mswot_id ? '&mswot_id='.(int)$mswot_id  : '');
	$extra.=($swot_id ? '&swot_id='.(int)$swot_id  : '');
	$extra.=($operativo_id? '&operativo_id='.(int)$operativo_id : '');
	$extra.=($instrumento_id? '&instrumento_id='.(int)$instrumento_id : '');
	$extra.=($recurso_id? '&recurso_id='.(int)$recurso_id : '');
	$extra.=($problema_id? '&problema_id='.(int)$problema_id : '');
	$extra.=($demanda_id? '&demanda_id='.(int)$demanda_id : '');
	$extra.=($programa_id? '&programa_id='.(int)$programa_id : '');
	$extra.=($licao_id? '&licao_id='.(int)$licao_id : '');
	$extra.=($evento_id? '&evento_id='.(int)$evento_id : '');
	$extra.=($link_id? '&link_id='.(int)$link_id : '');
	$extra.=($avaliacao_id? '&avaliacao_id='.(int)$avaliacao_id : '');
	$extra.=($tgn_id? '&tgn_id='.(int)$tgn_id : '');
	$extra.=($brainstorm_id? '&brainstorm_id='.(int)$brainstorm_id : '');
	$extra.=($gut_id? '&gut_id='.(int)$gut_id : '');
	$extra.=($causa_efeito_id? '&causa_efeito_id='.(int)$causa_efeito_id : '');
	$extra.=($arquivo_id? '&arquivo_id='.(int)$arquivo_id : '');
	$extra.=($forum_id? '&forum_id='.(int)$forum_id : '');
	$extra.=($checklist_id? '&checklist_id='.(int)$checklist_id : '');
	$extra.=($agenda_id? '&agenda_id='.(int)$agenda_id : '');
	$extra.=($agrupamento_id? '&agrupamento_id='.(int)$agrupamento_id : '');
	$extra.=($patrocinador_id? '&patrocinador_id='.(int)$patrocinador_id : '');
	$extra.=($template_id? '&template_id='.(int)$template_id : '');
	$extra.=($arquivo_usuario? '&arquivo_usuario='.(int)$arquivo_usuario : '');
	$extra.=($painel_id? '&painel_id='.(int)$painel_id : '');
	$extra.=($painel_odometro_id? '&painel_odometro_id='.(int)$painel_odometro_id : '');
	$extra.=($painel_composicao_id? '&painel_composicao_id='.(int)$painel_composicao_id : '');
	$extra.=($tr_id? '&tr_id='.(int)$tr_id : '');
	$extra.=($me_id? '&me_id='.(int)$me_id : '');
	$extra.=($plano_acao_item_id? '&plano_acao_item_id='.(int)$plano_acao_item_id : '');
	$extra.=($beneficio_id? '&beneficio_id='.(int)$beneficio_id : '');
	$extra.=($painel_slideshow_id? '&painel_slideshow_id='.(int)$painel_slideshow_id : '');
	$extra.=($projeto_viabilidade_id? '&projeto_viabilidade_id='.(int)$projeto_viabilidade_id : '');
	$extra.=($projeto_abertura_id? '&projeto_abertura_id='.(int)$projeto_abertura_id : '');
	$extra.=($pg_id? '&pg_id='.(int)$pg_id : '');
	$extra.=($ssti_id? '&ssti_id='.(int)$ssti_id : '');
	$extra.=($laudo_id? '&laudo_id='.(int)$laudo_id : '');
	$extra.=($trelo_id? '&trelo_id='.(int)$trelo_id : '');
	$extra.=($trelo_cartao_id? '&trelo_cartao_id='.(int)$trelo_cartao_id : '');
	$extra.=($pdcl_id? '&pdcl_id='.(int)$pdcl_id : '');
	$extra.=($pdcl_item_id? '&pdcl_item_id='.(int)$pdcl_item_id : '');
	$extra.=($os_id? '&os_id='.(int)$os_id : '');
	
	
	$podeEditar = $Aplic->checarModulo('arquivos', 'editar');
	$ordenar=getParam($_REQUEST, 'ordenar', 'arquivo_data');
	$ordem=getParam($_REQUEST, 'ordem', '0');
	if ($ordenar=='nome') $ordenar=($ordem ? 'arquivo_nome DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_nome ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='pasta') $ordenar=($ordem ? 'arquivo_pasta_nome DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_pasta_nome ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='categoria') $ordenar=($ordem ? 'arquivo_categoria DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_categoria ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='responsavel') $ordenar=($ordem ? 'contato_posto_valor DESC, contato_nomeguerra DESC, arquivo_data ASC' : 'contato_posto_valor ASC, contato_nomeguerra ASC, arquivo_data ASC');
	if ($ordenar=='tamanho') $ordenar=($ordem ? 'arquivo_tamanho DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_tamanho ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='tipo') $ordenar=($ordem ? 'arquivo_tipo DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_tipo ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='data') $ordenar='arquivo_data'.($ordem ? ' DESC' : ' ASC' ); 
	if ($ordenar=='saida') $ordenar=($ordem ? 'arquivo_motivo_saida DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_motivo_saida ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='descricao') $ordenar=($ordem ? 'arquivo_descricao DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_descricao ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='retirou') $ordenar=($ordem ? 'arquivo_saida DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_saida ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	
	
	$sql = new BDConsulta();
	$sql->adTabela('arquivo');
	
	$sql->esqUnir('usuarios', 'u', 'u.usuario_id = arquivo_dono');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
	
	if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
	
	if ($Aplic->profissional){
		$sql->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivo.arquivo_id');
		if ($tarefa_id) $sql->adOnde('arquivo_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=arquivo_gestao_tarefa');
			$sql->adOnde('arquivo_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('arquivo_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('arquivo_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('arquivo_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('arquivo_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('arquivo_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('arquivo_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('arquivo_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('arquivo_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('arquivo_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('arquivo_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('arquivo_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('arquivo_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('arquivo_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('arquivo_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('arquivo_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('arquivo_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('arquivo_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('arquivo_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('arquivo_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('arquivo_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('arquivo_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('arquivo_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('arquivo_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('arquivo_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('arquivo_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('arquivo_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('arquivo_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('arquivo_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('arquivo_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('arquivo_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('arquivo_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('arquivo_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('arquivo_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('arquivo_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('arquivo_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('arquivo_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('arquivo_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('arquivo_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('arquivo_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('arquivo_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('arquivo_gestao_tr IN ('.$tr_id.')');
		elseif ($me_id) $sql->adOnde('arquivo_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('arquivo_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('arquivo_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('arquivo_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('arquivo_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('arquivo_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('arquivo_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('arquivo_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('arquivo_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('arquivo_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('arquivo_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('arquivo_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('arquivo_gestao_pdcl_item IN ('.$pdcl_item_id.')');
		elseif ($os_id) $sql->adOnde('arquivo_gestao_os IN ('.$os_id.')');

		else if ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_gestao_usuario=0 OR arquivo_gestao_usuario IS NULL OR arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		}
	else {
		if ($tarefa_id) $sql->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
		else if ($projeto_id) $sql->adOnde('arquivo_projeto IN('.$projeto_id.')');
		else if ($pratica_id) $sql->adOnde('arquivo_pratica = '.(int)$pratica_id);
		else if ($demanda_id) $sql->adOnde('arquivo_demanda = '.(int)$demanda_id);
		else if ($instrumento_id) $sql->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
		else if ($pratica_indicador_id) $sql->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
		else if ($tema_id) $sql->adOnde('arquivo_tema = '.(int)$tema_id);
		else if ($objetivo_id) $sql->adOnde('arquivo_objetivo = '.(int)$objetivo_id);
		else if ($pg_estrategia_id) $sql->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
		else if ($fator_id) $sql->adOnde('arquivo_fator = '.(int)$fator_id);
		else if ($pg_meta_id) $sql->adOnde('arquivo_meta = '.(int)$pg_meta_id);
		else if ($pg_perspectiva_id) $sql->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
		else if ($canvas_id) $sql->adOnde('arquivo_canvas = '.(int)$canvas_id);
		else if ($calendario_id) $sql->adOnde('arquivo_calendario = '.(int)$calendario_id);
		else if ($ata_id) $sql->adOnde('arquivo_ata= '.(int)$ata_id);
		else if ($plano_acao_id) $sql->adOnde('arquivo_acao = '.(int)$plano_acao_id);
		else if ($arquivo_usuario) $sql->adOnde('arquivo_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_usuario=0 OR arquivo_usuario IS NULL OR arquivo_usuario = '.(int)$Aplic->usuario_id);
		}

	$sql->esqUnir('arquivo_pasta', 'ff', 'ff.arquivo_pasta_id = arquivo_pasta');
	$sql->adCampo('arquivo.*, arquivo_pasta_id, arquivo_pasta_nome, contato_id, usuario_id');
	if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
	else $sql->adOnde('arquivo_pasta = 0 OR arquivo_pasta IS NULL');
	$sql->adGrupo('arquivo_pasta, arquivo.arquivo_id, ff.arquivo_pasta_id, contato_id, usuario_id');

	$sql->adOrdem($ordenar);
	$sql->setLimite($xpg_min, $xpg_tamanhoPagina);
	$arquivos = $sql->Lista();
	

	$s = '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome do Arquivo', 'Clique para ordenar pelo nome dos arquivos.<br><br>Todo arquivo enviado para o Sistema dever? ter um nome, preferencialmente significativo, para facilitar um futura pesquisa.').'Nome do Arquivo'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Descri??o do Arquivo', 'Clique para ordenar pela descri??o dos arquivos.<br><br>Ao se enviar um arquivo, pode-se escrever um texto explicativo para facilitar a compreens?o do rquivo e facilitar futuras pesquisas.').'Descri??o'.dicaF().'</th>';
	$s .= '<th>'.dica('Vers?o do Arquivo', 'O Sistema registra as modifica??es nos arquivos, mantendo um hist?rico.<ul><li>Para visualizar as modifica??es clique no n?mero que aparecer entre par?nteses. ex: 1.01 <b><font color="#000066">(2)</font></b></li></ul>').'Vers?o'.dicaF().'</th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=categoria&ordem='.($ordem ? '0' : '1').'\');">'.dica('Categoria do Arquivo', 'Clique para ordenar pela categoria dos arquivos.<br><br>Os arquivos podem ser :<ul><li>Documento - normalmente textos e imagens.</li><li>Arquivos - normalmente aplicativos executaveis.</li></ul>').'Categoria'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=responsavel&ordem='.($ordem ? '0' : '1').'\');">'.dica('Respons?vel', 'Clique para ordenar pelo nome d'.$config['genero_usuario'].'s '.$config['usuarios'].' que enviaram os arquivos').'Respons?vel'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=tamanho&ordem='.($ordem ? '0' : '1').'\');">'.dica('Tamanho', 'Clique para ordenar pelo tamanho dos arquivos.<br><br>O tamanho do arquivo ? em bytes').'Tamanho'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=tipo&ordem='.($ordem ? '0' : '1').'\');">'.dica('Tipo de Arquivo', 'Clique para ordenar pela extens?o dos arquivos.<br><br>Pela extens?o do arquivo, o sistema tentar? identificar qual o tipo de arquivo.').'Tipo'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data de Inclus?o', 'Clique para ordenar pela data em que os arquivos foram inseridos no Sistema pela primeira vez.').'Data Inclus?o'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=saida&ordem='.($ordem ? '0' : '1').'\');">'.dica('Texto de Sa?da', 'Clique para ordenar pelo texto de retirada.<br><br>Quando um arquivo tiver um destinat?rio espec?fico, este ao inves de clicar no nome do arquivo para fazer o <i>download</i>, deve utilizar o bot?o de retirada '.imagem('icones/acima.png').' , e poder? deixar um texto comentando sobre a retirada do arquivo que estava na caixa de sa?da.').'Sa?da'.dicaF().'</th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=retirou&ordem='.($ordem ? '0' : '1').'\');">'.dica('Entrada e Sa?da de Arquivos', 'Clique para ordenar pel'.$config['genero_usuario'].'s '.$config['usuarios'].' que retiram os arquivos.<br><br>Nos campos abaixo h? tr?s situa??es :<br><br><li>Retirar arquivo '.imagem('icones/acima.png').'  : Quando um arquivo lhe for destinado, ao inves de clicar no nome do arquivo para fazer o <i>download</i>, utilize este bot?o para ficar registrado no sistema que j? retirou. </li><br><br><li>Alterar o arquivo de sa?da '.imagem('icones/down.png').'  : Caso deseje modificar o arquivo na caixa de sa?da, Clique neste bot?o. </li><br><br><li>Nome do '.ucfirst($config['usuario']).' - caso outr'.$config['genero_usuario'].' '.$config['usuario'].' j? tenha clicado no bot?o retirar arquivo,  constar? neste campo o nome do mesmo.</li>').'E/S'.dicaF().'</th>';
	$s .='<th>&nbsp;</th></tr>';

	$arquivo_data = new CData();
	$id = 0;
	$qnt=0;
	
	foreach ($arquivos as $linha) {
		if (permiteAcessarArquivo($linha['arquivo_acesso'], $linha['arquivo_id'])){
			$qnt++;
			$arquivo_data = new CData($linha['arquivo_data']);
			
			$permiteEditar=permiteEditarArquivo($linha['arquivo_acesso'], $linha['arquivo_id']);
			 
			$editar=($podeEditar&&$permiteEditar);
			
			//$arquivo = ($linha['arquivo_versoes'] > 1 ?  ultimo_arquivo1($arquivo_versoes, $linha['arquivo_versao_id']) : $linha);
			$arquivo=$linha;
			
			$s .= '<form name="frm_remover_file_'.$arquivo['arquivo_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" /><input type="hidden" name="del" value="1" /><input type="hidden" name="arquivo_id" value="'.$arquivo['arquivo_id'].'" /></form><form name="frm_duplicar_file_'.$arquivo['arquivo_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" /><input type="hidden" name="duplicar" value="1" /><input type="hidden" name="arquivo_id" value="'.$arquivo['arquivo_id'].'" /></form><tr><td>';
			$s .= '<form name="frm_remover_file_'.$arquivo['arquivo_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" /><input type="hidden" name="del" value="1" /><input type="hidden" name="arquivo_id" value="'.$arquivo['arquivo_id'].'" /></form><form name="frm_duplicar_file_'.$arquivo['arquivo_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" /><input type="hidden" name="duplicar" value="1" /><input type="hidden" name="arquivo_id" value="'.$arquivo['arquivo_id'].'" /></form><tr><td>';
			
			$arquivo_icone = getIcone($arquivo['arquivo_tipo']);
			$dentro = '<br>Clique para abrir este arquivo.';
			$s .= dica($arquivo['arquivo_nome'], $dentro).'<a href="./codigo/arquivo_visualizar.php?arquivo_id='.$arquivo['arquivo_id'].'"><img border=0 width="16" heigth="16" src="'.acharImagem($arquivo_icone, 'arquivos').'" />&nbsp;'.$arquivo['arquivo_nome'].'</a>'.dicaF().'</td><td>'.($arquivo['arquivo_descricao'] ? limpar_paragrafo($arquivo['arquivo_descricao']) : '&nbsp;').'</td><td align="right">';
			$s .= '</td>';
			$s .='<td align="left">'.(isset($arquivo_tipos[$arquivo['arquivo_categoria']]) ? $arquivo_tipos[$arquivo['arquivo_categoria']] : 'N/A').'</td>'; 
			$s .='<td>'.link_usuario($arquivo['usuario_id'],'','','esquerda').'</td>';
			$s .='<td align="right">'.intval($arquivo['arquivo_tamanho'] / 1024).' kb</td>';
			$s .='<td align="center">'.substr($arquivo['arquivo_tipo'], strpos($arquivo['arquivo_tipo'], '/') + 1).'</td>';
			$s .='<td align="center">'.$arquivo_data->format('%d/%m/%Y %H:%M').'</td><td >'.($linha['arquivo_motivo_saida']? $linha['arquivo_motivo_saida'] : '&nbsp;').'</td><td align="center" width="8">';
	    if ($editar){
		    if (empty($linha['arquivo_saida'])) $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=saida&arquivo_id='.$arquivo['arquivo_id'].'\');">'.imagem('icones/acima.png', 'Caixa de Sa?da', 'Clique neste ?cone '.imagem('icones/acima.png').' para retirar o arquivo.').'</a>';
	    	elseif ($linha['arquivo_saida'] == $Aplic->usuario_id) $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&ci=1&arquivo_id='.$arquivo['arquivo_id'].'\');">'.imagem('icones/down.png', 'Caixa de Entrada', 'Clique neste ?cone '.imagem('icones/down.png').' para depositar arquivo.').'</a>';
	      elseif ($arquivo['arquivo_saida'] == 'final') $s .= 'final';
				else {
					$q4 = new BDConsulta;
					$q4->adCampo('arquivo_id, arquivo_saida, usuario_id');
					$q4->adTabela('arquivo');
					$q4->esqUnir('usuarios', 'cu', 'cu.usuario_id = arquivo_saida');
					$q4->adOnde('arquivo_id = '.(int)$arquivo['arquivo_id']);
					$co_usuario = array();
					$co_usuario = $q4->Lista();
					$co_usuario = $co_usuario[0];
					$q4->limpar();
					$s .= link_usuario($co_usuario['usuario_id'],'','','esquerda').'<br>';
					}
				}
			else $s .='&nbsp;';			
			$s .= '</td><td align="center" width="45">';
			if ($editar && (empty($arquivo['arquivo_saida']) || ($arquivo['arquivo_saida'] == 'final' && $arquivo['projeto_responsavel']==$Aplic->usuario_id))) {
				$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&arquivo_id='.$arquivo['arquivo_id'].'\');">'.imagem('icones/editar.gif','Editar Arquivo', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar o arquivo.').'</a>';	
				$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=ver&arquivo_id='.$arquivo['arquivo_id'].'\');">'.imagem('icones/gnome-mime-application-vnd.ms-powerpoint.png', 'Ver Detalhes', 'Ao clicar neste ?cone '.imagem('icones/gnome-mime-application-vnd.ms-powerpoint.png').' ser? possivel visualizar o detalhamento do arquivo.').'</a>';
				$s .= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este aquivo?\')) {document.frm_remover_file_'.$arquivo['arquivo_id'].'.submit()}">'.imagem('icones/remover.png','Excluir Arquivo', 'Clique neste ?cone '.imagem('icones/remover.png').' para excluir o arquivo.', 'arquivos').'</a>';
				}
			else 	$s .='&nbsp;';
			$s .= '</td>';
			$s .='</tr>';
			}
		}
	if (!count($arquivos)) $s .= '<tr><td colspan="13">Nenhum arquivo encontrado.</td></tr>';
	elseif (!$qnt) $s .= '<tr><td colspan="13">N?o tem autoriza??o para visualizar nenhum dos arquivos.</td></tr>';	
	$s .= '</table>';
	if ($xpg_totalregistros > $xpg_tamanhoPagina) $s .= mostrarfnavbar($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $arquivo_pasta_id);
	$s .= '<br />';
	return $s;
	}

function mostrarfnavbar($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $arquivo_pasta_id) {
	global $Aplic, $tab, $m, $a;
	$xpg_parar = false;
	$xpg_pag_ant = $xpg_pag_prox = 1;
	$s = '<table width="100%" cellspacing=0 cellpadding=0 border=0><tr>';
	if ($xpg_totalregistros > $xpg_tamanhoPagina) {
		$xpg_pag_ant = $pagina - 1;
		$xpg_pag_prox = $pagina + 1;
		if ($xpg_pag_ant > 0) {
			$s .= '<td align="left"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina=1\');"><img src="'.acharImagem('navPrimeira.gif').'" border=0 Alt="First Page"></a>&nbsp;&nbsp;';
			$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina='.$xpg_pag_ant.'\');"><img src="'.acharImagem('navAnterior.gif').'" border=0 Alt="Previous pagina ('.$xpg_pag_ant.')"></a></td>';
			} 
		else $s .= '<td>&nbsp;</td>';
		$s .= '<td align="center" >';
		$s .= $xpg_totalregistros.' Arquivo(s) P?gina(s): [ ';
		for ($n = $pagina > 16 ? $pagina - 16 : 1; $n <= $xpg_total_paginas; $n++) {
			if ($n == $pagina) $s .= '<b>'.$n.'</b></a>';
			else $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina='.$n.'\');"></a>';
			if ($n >= 30 + $pagina - 15) {
				$xpg_parar = true;
				break;
				} 
			elseif ($n < $xpg_total_paginas) $s .= ' | ';
			}
		if (!isset($xpg_parar)) {
			if ($n == $pagina) $s .= '<'.$n.'</a>';
			else $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&pagina='.$xpg_total_paginas.'\');"></a>';
			}
		$s .= ' ] </td>';
		if ($xpg_pag_prox <= $xpg_total_paginas) {
			$s .= '<td align="right"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina='.$xpg_pag_prox.'\');"><img src="'.acharImagem('navProximo.gif').'" border=0 Alt="Next Page ('.$xpg_pag_prox.')"></a>&nbsp;&nbsp;';
			$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina='.$xpg_total_paginas.'\');"><img src="'.acharImagem('navUltima.gif').'" border=0 Alt="Last Page"></a></td>';
			} 
		else $s .= '<td>&nbsp;</td></tr>';
		} 
	else { 
		$s .= '<td align="center">';
		if ($xpg_pag_prox > $xpg_total_paginas) $s .= $xpg_sqlrecs.' Arquivos ';
		$s .= '</td></tr>';
		}
	$s .= '</table>';
	return $s;
	}

function ultimo_arquivo1($arquivo_versoes, $arquivo_versao_id) {
	$ultimo = null;
	if (isset($arquivo_versoes)) foreach ($arquivo_versoes as $arquivo_versao){
			if (($arquivo_versao['arquivo_versao_id'] == $arquivo_versao_id) && ($ultimo == null || $ultimo['arquivo_versao'] < $arquivo_versao['arquivo_versao']))	$ultimo = $arquivo_versao;
			}
	return $ultimo;
	}
	
function limpar_paragrafo($texto){
	$retirar=array('<p>', '</p>');
	$texto=str_replace('<p>', '', $texto);
	return str_replace('</p>', '<br>', $texto);
	}
	
?>
<script type="text/JavaScript">
function expandir(id){
	var element = document.getElementById(id);
	element.style.display = (element.style.display == 'none') ? '' : 'none';
	}
function adBlocoComponente(li) {
	if (document.all || navigator.appName == 'Microsoft Internet Explorer') {
		var form = document.frm_parte;
		var ni = document.getElementById('tbl_parte');
		var newitem = document.createElement('input');
		var htmltxt = '';
		newitem.id = 'parte_selecionado_arquivo['+li+']';
		newitem.name = 'parte_selecionado_arquivo['+li+']';
		newitem.type = 'hidden';
		ni.appendChild(newitem);
		} 
	else {
		var form = document.frm_parte;
		var ni = document.getElementById('tbl_parte');
		var newitem = document.createElement('input');
		newitem.setAttribute('id', 'parte_selecionado_arquivo['+li+']');
		newitem.setAttribute('name', 'parte_selecionado_arquivo['+li+']');
		newitem.setAttribute('type', 'hidden');
		ni.appendChild(newitem);
		}
	}
function removerBlocoComponente(li) {
	var t = document.getElementById('tbl_parte');
	var old = document.getElementById('parte_selecionado_arquivo['+li+']');
	t.removeChild(old);
	}
</script>


<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $plano_acao_id, $perms, $periodo_todo, $este_dia, $primeiraData, $ultimaData, $dept_id, $cia_id, $plano_acao_item_filtro, $plano_acao_item_filtro_lista, $projeto_id, $Aplic, $titulo, $usuario_id, $cia_id, 
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


$db_inicio = $primeiraData->format('%Y-%m-%d %H:%M:%S');
$db_fim = $ultimaData->format('%Y-%m-%d %H:%M:%S');


$tipos = getSisValor('TipoEvento');
$links = array();

$sql = new BDConsulta;
$sql->adTabela('plano_acao_item');
$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao.plano_acao_id = plano_acao_item_acao');
if ($usuario_id) $sql->esqUnir('plano_acao_item_usuario', 'plano_acao_item_usuario', 'plano_acao_item_usuario_item=plano_acao_item.plano_acao_item_id');
if ($dept_id){
		$sql->esqUnir('plano_acao_item_dept', 'plano_acao_item_dept', 'plano_acao_item.plano_acao_item_id = plano_acao_item_dept_plano_acao_item');
		$sql->esqUnir('depts', 'depts', 'depts.dept_id = plano_acao_item_dept_dept');
		$sql->adOnde('plano_acao_item_dept_dept IN ('.$dept_id.') OR plano_acao_item_dept IN ('.$dept_id.')');
		}
$sql->adCampo('plano_acao_item.plano_acao_item_id, plano_acao_item_nome, plano_acao_nome, plano_acao_item_acesso, plano_acao_item_inicio, plano_acao_item_fim, plano_acao_cor AS cor, plano_acao_item_oque, plano_acao_id');


if ($periodo_todo){
	$sql->adOnde('plano_acao_item_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('plano_acao_item_fim >= \''.$db_inicio. '\'');
	} 
else $sql->adOnde('(plano_acao_item_inicio >= \''.$db_inicio.'\' AND plano_acao_item_inicio <=\''.$db_fim.'\') OR (plano_acao_item_fim >= \''.$db_inicio.'\' AND plano_acao_item_fim <=\''.$db_fim.'\')');

$sql->adOnde('plano_acao_item_inicio IS NOT NULL');
$sql->adOnde('plano_acao_item_fim IS NOT NULL');

if ($Aplic->profissional){
	$sql->esqUnir('plano_acao_gestao','plano_acao_gestao','plano_acao_gestao_acao = plano_acao.plano_acao_id');
	if ($tarefa_id) $sql->adOnde('plano_acao_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=plano_acao_gestao_tarefa');
		$sql->adOnde('plano_acao_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('plano_acao_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('plano_acao_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('plano_acao_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('plano_acao_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('plano_acao_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('plano_acao_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('plano_acao_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('plano_acao_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('plano_acao_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('plano_acao_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('plano_acao_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('plano_acao_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('plano_acao_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('plano_acao_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('plano_acao_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('plano_acao_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('plano_acao_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('plano_acao_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('plano_acao_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('plano_acao_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('plano_acao_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('plano_acao_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('plano_acao_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('plano_acao_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('plano_acao_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('plano_acao_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('plano_acao_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('plano_acao_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('plano_acao_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('plano_acao_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('plano_acao_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('plano_acao_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('plano_acao_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('plano_acao_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('plano_acao_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('plano_acao_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('plano_acao_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('plano_acao_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('plano_acao_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('plano_acao_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('plano_acao_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr IN ('.$tr_id.')');
	elseif ($me_id) $sql->adOnde('plano_acao_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('plano_acao_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('plano_acao_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('plano_acao_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('plano_acao_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('plano_acao_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('plano_acao_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('plano_acao_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('plano_acao_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('plano_acao_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('plano_acao_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('plano_acao_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('plano_acao_gestao_pdcl_item IN ('.$pdcl_item_id.')');
	elseif ($os_id) $sql->adOnde('plano_acao_gestao_os IN ('.$os_id.')');
	}
else{
	if ($projeto_id) $sql->adOnde('plano_acao_projeto='.(int)$projeto_id);
	if ($pratica_id) $sql->adOnde('plano_acao_pratica='.(int)$pratica_id);
	if ($pratica_indicador_id) $sql->adOnde('plano_acao_indicador='.(int)$pratica_indicador_id);
	if ($tema_id) $sql->adOnde('plano_acao_tema='.(int)$tema_id);
	if ($objetivo_id) $sql->adOnde('plano_acao_objetivo='.(int)$objetivo_id);
	if ($pg_estrategia_id) $sql->adOnde('plano_acao_estrategia='.(int)$pg_estrategia_id);
	if ($pg_meta_id) $sql->adOnde('plano_acao_meta='.(int)$pg_meta_id);
	if ($fator_id) $sql->adOnde('plano_acao_fator='.(int)$fator_id);
	}
if ($usuario_id) $sql->adOnde('plano_acao_item_usuario_usuario='.(int)$usuario_id.' OR plano_acao_item_responsavel='.(int)$usuario_id);
if ($cia_id) $sql->adOnde('plano_acao_cia = '.(int)$cia_id);
if ($plano_acao_id) $sql->adOnde('plano_acao.plano_acao_id = '.(int)$plano_acao_id);
$sql->adOrdem('plano_acao_item_inicio');
$sql->adGrupo('plano_acao_item.plano_acao_item_id');
$itens = $sql->Lista();
$sql->limpar();

$itens2 = array();
$inicio_hora = $config['cal_dia_inicio'];
$fim_hora = $config['cal_dia_fim'];
$data=getParam($_REQUEST, 'data', '');

//MUDAR DEPOIS POIS N�O EST� CONSIDERANDO EXPEDIENTE POSSIVELMENTE CADASTRADO
foreach ($itens as $linha) {
	if(permiteAcessarPlanoAcaoItem($linha['plano_acao_item_acesso'], $linha['plano_acao_item_id'])){	

		$inicio = new CData($linha['plano_acao_item_inicio']);
		$fim = new CData($linha['plano_acao_item_fim']);
		
		//preciso arredondar os minutos para exibi��o
		if ($inicio->minuto <= 7) $inicio->minuto=0;
		elseif ($inicio->minuto <= 22) $inicio->minuto=15;
		elseif ($inicio->minuto <= 37) $inicio->minuto=30;
		elseif ($inicio->minuto <= 52) $inicio->minuto=45;
		else {
			$inicio->minuto=0;
			if ($inicio->hora < 23) $fim->hora++;
			}
		
		if ($fim->minuto <= 7) $fim->minuto=0;
		elseif ($fim->minuto <= 22) $fim->minuto=15;
		elseif ($fim->minuto <= 37) $fim->minuto=30;
		elseif ($fim->minuto <= 52) $fim->minuto=45;
		else {
			$fim->minuto=0;
			if ($fim->hora < 23) $fim->hora++;
			}
		
		
		
		//inicia e termina no mesmo dia
		if ($inicio->format("%Y%m%d")==$fim->format("%Y%m%d")){
			$itens2[$inicio->format('%H%M%S')][] = $linha;
			if ($inicio_hora > $inicio->format('%H')) $inicio_hora = $inicio->format('%H');
			if ($fim_hora < $fim->format('%H')) $fim_hora = $fim->format('%H');
			}
		elseif($data==$inicio->format("%Y%m%d")){  
			// estou no 1o dia
			if ($inicio_hora > $inicio->format('%H')) $inicio_hora = $inicio->format('%H');
			$linha['plano_acao_item_fim']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_fim']< 10 ? '0' : '').$config['cal_dia_fim'].':00:00';
			$itens2[$inicio->format('%H%M%S')][] = $linha;
			}
		elseif($data==$fim->format("%Y%m%d")){ 
			// estou no ultimo dia
			$linha['plano_acao_item_inicio']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].':00:00';
			$itens2[($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].'0000'][] = $linha;
			if ($fim_hora < $fim->format('%H')) $fim_hora = $fim->format('%H');
			}
		else{
			//um dia no meio
			$linha['plano_acao_item_fim']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_fim']< 10 ? '0' : '').$config['cal_dia_fim'].':00:00';
			$linha['plano_acao_item_inicio']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].':00:00';
			$itens2[($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].'0000'][] = $linha;
			}
		}
	}

	

$diaFormato = $este_dia->format('%Y%m%d');
$inicio=0;
$fim=24;
$inc=15;


$este_dia->setTime($inicio, 0, 0);
$saida = '<table cellpadding=0 cellspacing=0 class="tbl1" width="100%" style="background-color:#ffffff">';
$linhas = 0;
for ($i = 0, $n = (($fim - $inicio) * 60 / $inc); $i <= $n; $i++) {
	$saida .= '<tr>';
	$tm = $este_dia->format('%H:%M');
	$saida .= '<td width="1%" align="right" style="white-space: nowrap">'.($este_dia->getMinute() ? $tm : '<b>'.$tm.'</b>').'</td>';
	$formato_horas = $este_dia->format('%H%M%S');
	if (isset($itens2[$formato_horas]) && $itens2[$formato_horas]) {
		$quantidade = count($itens2[$formato_horas]);
		for ($j = 0; $j < $quantidade; $j++) {
			
			$linha = $itens2[$formato_horas][$j];
			$et = new CData($linha['plano_acao_item_fim']);
			$linhas = ((($et->getHour() * 60 + $et->getMinute()) - ($este_dia->getHour() * 60 + $este_dia->getMinute())) / $inc)+1;
			$saida .= '<td style="color:#'.melhorCor($linha['cor']).';background-color:#'.$linha['cor'].'" rowspan="'.$linhas.'" valign="top">';
			$saida .= link_acao_item($linha['plano_acao_item_id']).'</td>';
			}
		} 
	elseif (--$linhas <= 0) $saida .= '<td>&nbsp;</td>';
	$saida .= '</tr>';
	$este_dia->adSegundos(60 * $inc);
	}
$saida .= '</table>';
echo $saida;
?>
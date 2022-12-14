<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php'; 
//cria gr?fico gantt
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
global $Aplic, $localidade_tipo_caract, $proFiltro, $projetoStatus, $mostrarInativo, $mostrarTodoGantt, $ordenarTarefasPorNome, $usuario_id, $config, $a;
ini_set('memory_limit', $config['resetar_limite_memoria']);
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph'));
include ($Aplic->getClasseBiblioteca('jpgraph/src/jpgraph_gantt'));
include_once ($Aplic->getClasseModulo('projetos'));
$projetoStatus = getSisValor('StatusProjeto');
$projetoStatus = unirVetores(array('-2' => 'Todos sem progresso'), $projetoStatus);
$usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$parafazer=getParam($_REQUEST, 'fazer', 0);
if ($Aplic->usuario_id == $usuario_id) $projetoStatus = unirVetores(array('-3' => 'Meus '.$config['projetos']), $projetoStatus);
else $projetoStatus = unirVetores(array('-3' => ucfirst($config['projetos']).' d'.$config['genero_usuario'].'s '.$config['usuarios']), $projetoStatus);
$proFiltro=getParam($_REQUEST, 'proFiltro', '-1');
$mostrarInativo=getParam($_REQUEST, 'mostrarInativo', 0);
$ordenarTarefasPorNome=getParam($_REQUEST, 'ordenarTarefasPorNome', 0);
$mostrarMarco=getParam($_REQUEST, 'mostrarMarco', 0);
$mostrarEvento=getParam($_REQUEST, 'mostrarEvento', 0);
$horas_trabalhadas = $config['horas_trab_diario'];
$q = new BDConsulta;

$q->adTabela('tarefas', 't1');
$q->adCampo('DISTINCT pr.projeto_id, projeto_cor, projeto_nome, projeto_data_inicio, projeto_data_fim, projeto_status, projeto_ativo');
$q->adCampo('tarefa_marco, ut.perc_designado, tarefa_inicio, tarefa_fim, tarefa_duracao, tarefa_nome, tarefa_percentagem');
$q->esqUnir('projetos', 'pr', 'pr.projeto_id = t1.tarefa_projeto');
$q->esqUnir('tarefa_designados', 'ut', 't1.tarefa_id = ut.tarefa_id');
if ($proFiltro != '-1') $q->adOnde('pr.projeto_status = '.(int)$proFiltro);
$q->adOnde('projeto_template = 0 OR projeto_template IS NULL');
$q->adOnde('ut.usuario_id = '.(int)$usuario_id.' OR ut.usuario_id= '.(int)$usuario_id);
if ($parafazer) $q->adOnde('tarefa_percentagem < 100');
if (!$mostrarMarco)	$q->adOnde('t1.tarefa_marco != 1');
if ($mostrarInativo != '1')	$q->adOnde('pr.projeto_ativo = 1');
if ($ordenarTarefasPorNome) $q->adOrdem('tarefa_nome, '.($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC, pr.projeto_nome');
else $q->adOrdem('pr.projeto_nome, '.($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC');

$tarefas = $q->Lista();
$q->limpar();

if ($mostrarEvento){
	//inserir eventos
	$q->adTabela('eventos', 'eventos');
	$q->esqUnir('evento_participante','evento_participante','evento_participante_evento=eventos.evento_id');
	$q->adCampo('0 AS tarefa_marco, 0 AS perc_designado, evento_inicio AS tarefa_inicio, evento_fim AS tarefa_fim, 0 AS tarefa_duracao, evento_titulo AS tarefa_nome, 0 AS tarefa_percentagem');
	$q->adCampo('0 AS projeto_id, evento_cor AS projeto_cor, \'\' AS projeto_nome, null AS projeto_data_inicio, null AS projeto_data_fim, 0 AS projeto_status, 1 AS projeto_ativo');
	$q->adOnde('evento_participante_usuario = '.(int)$usuario_id);
	$q->adOnde('evento_participante_aceito != -1');
	$eventos=$q->Lista();
	$q->limpar();
	$tarefas=array_merge($tarefas, $eventos);
	}

$largura = min(getParam($_REQUEST, 'width', 600), 1400);
$data_inicio=getParam($_REQUEST, 'data_inicio', 0);
$data_fim=getParam($_REQUEST, 'data_fim', 0);
$mostrarTodoGantt=getParam($_REQUEST, 'mostrarTodoGantt', '0');
$grafico = new GanttGraph($largura);
if (!$data_inicio && !$data_fim) $grafico->ShowHeaders(GANTT_HYEAR| GANTT_HMONTH);
else $grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$grafico->SetFrame(false);
$grafico->SetBox(true, array(0, 0, 0), 2);
$grafico->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);
$pLocale = setlocale(LC_TIME, 0); 
$res = setlocale(LC_TIME, $Aplic->usuario_linguagem[2]);
if ($res) $grafico->scale->SetDateLocale($Aplic->usuario_linguagem[2]);
setlocale(LC_TIME, $pLocale);
if ($data_inicio && $data_fim) $grafico->SetDateRange($data_inicio, $data_fim);
$grafico->scale->actinfo->vgrid->SetColor('gray');
$grafico->scale->actinfo->SetColor('darkgray');
$grafico->scale->actinfo->SetColTitles(array('Nome da '.$config['tarefa'], ucfirst($config['projeto']), '%','hr.',' In?cio ', 'T?rmino'), array(70, 70, 10,10, 70, 70));
$tabelaTitulo = ($proFiltro == '-1') ? 'Tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.ucfirst($config['projetos']) : $projetoStatus[$proFiltro];
$grafico->scale->tableTitle->Set($tabelaTitulo);
if (!$data_inicio && !$data_fim) $grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 8);
else $grafico->scale->tableTitle->SetFont(FF_FONT1, FS_BOLD, 12);
$grafico->scale->SetTableTitleBackground('#eeeeee');
$grafico->scale->tableTitle->Show(true);


if ($data_inicio && $data_fim) {
	$minuto_d_inicio = new CData($data_inicio);
	$minuto_d_fim = new CData($data_fim);
	$grafico->SetDateRange($data_inicio, $data_fim);
	} 
else {
	$minuto_d_inicio = new CData();
	$minuto_d_fim = new CData();
	
	$d_inicio = new CData();
	$d_fim = new CData();
	for ($i = 0, $i_cmp = count($tarefas); $i < $i_cmp; $i++) {
		$inicio = substr($tarefas[$i]['tarefa_inicio'], 0, 10);
		$fim = substr($tarefas[$i]['tarefa_fim'], 0, 10);
		$d_inicio->Date($inicio);
		$d_fim->Date($fim);
		if ($i == 0) {
			$minuto_d_inicio = $d_inicio;
			$minuto_d_fim = $d_fim;
			} 
		else {
			if ($d_inicio->compare($minuto_d_inicio, $d_inicio) > 0) $minuto_d_inicio = $d_inicio;
			if ($d_inicio->compare($minuto_d_fim, $d_fim) < 0) $minuto_d_fim = $d_fim;
			}
		}
	}
$dia_diferenca = $minuto_d_inicio->dataDiferenca($minuto_d_fim);
if ($dia_diferenca > 360) $grafico->ShowHeaders(GANTT_HYEAR);
elseif ($dia_diferenca > 240) $grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH);
elseif ($dia_diferenca > 90) {
		$grafico->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HWEEK);
		$grafico->scale->week->SetStyle(WEEKSTYLE_WNBR);
		}
$linha = 0;
if (!is_array($tarefas) || sizeof($tarefas) == 0) {
	$d = new CData();
	$barra = new GanttBar($linha++, array(' Nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'], ' ', ' ', ' '), $d->getData(), $d->getData(), ' ', 0.6);
	$barra->title->SetCOlor('red');
	$grafico->Add($barra);
	}
if (is_array($tarefas)) {
	foreach ($tarefas as $t) {
		if ($localidade_tipo_caract == 'utf-8' && function_exists('utf8_decode')) $nome = strlen(utf8_decode($t['tarefa_nome'])) > 25 ? substr(utf8_decode($t['tarefa_nome']), 0, 22).'...' : utf8_decode($t['tarefa_nome']);
		else $nome = strlen($t['tarefa_nome']) > 30 ? substr($t['tarefa_nome'], 0, 28).'...' : $t['tarefa_nome'];
		
		if ($localidade_tipo_caract == 'utf-8' && function_exists('utf8_decode')) $nomeProjeto = strlen(utf8_decode($t['projeto_nome'])) > 25 ? substr(utf8_decode($t['projeto_nome']), 0, 22).'...' : utf8_decode($t['projeto_nome']);
		else $nomeProjeto = strlen($t['projeto_nome']) > 20 ? substr($t['projeto_nome'], 0, 18).'...' : $t['projeto_nome'];
		
		$inicio = ($t['tarefa_inicio'] ? $t['tarefa_inicio'] : null);
		$data_fim = ($t['tarefa_fim']  ? $t['tarefa_fim'] : null);
		$data_fim = new CData($data_fim);
		$fim = $data_fim->getData();
		$inicio = new CData($inicio);
		$inicio = $inicio->getData();
		$progresso = (float)$t['tarefa_percentagem'];
		$legenda = '';
		if (!$inicio || $inicio == '0000-00-00') {
			$inicio = !$fim ? date('Y-m-d') : $fim;
			$legenda .= '(sem data in?cio)';
			}
		if (!$fim) {
			$fim = $inicio;
			$legenda .= ' '.'(sem data de t?rmino)';
			} 
		else $cap = '';
		
		$datafim = new CData($fim);
		$datainicio = new CData($inicio);

		if ($t['tarefa_marco'] && $mostrarMarco) {
			
			$datamarco = new CData($inicio);
			$datamarco->setHour(10);
			
			$barra = new MileStone($linha++, array($nome, $nomeProjeto, $t['tarefa_percentagem'], $t['tarefa_duracao'], $datainicio->format('%d/%m/%Y'), ''), substr($inicio, 0, 10));
			$barra->title->SetFont(FF_FONT1, FS_NORMAL, 8);
			$barra->title->SetColor('#CC0000');
			} 

		if (!$t['tarefa_marco']){
			$barra = new GanttBar($linha++, array($nome, $nomeProjeto, $t['tarefa_percentagem'], $t['tarefa_duracao'], $datainicio->format('%d/%m/%Y'), $datafim->format('%d/%m/%Y')), $inicio, $fim, $cap, 0.6);
			$barra->progress->Set(min(($progresso / 100), 1));
			$barra->title->SetFont(FF_FONT1, FS_NORMAL, 8);
			$barra->SetFillColor('#'.$t['projeto_cor']);
			$barra->SetPattern(BAND_SOLID, '#'.$t['projeto_cor']);
			$barra->caption = new TextProperty($legenda);
			$barra->caption->Align('left', 'center');
			if ($t['projeto_ativo'] == '0') {
				$barra->caption->SetColor('darkgray');
				$barra->title->SetColor('darkgray');
				$barra->SetColor('darkgray');
				$barra->SetFillColor('gray');
				$barra->progress->SetFillColor('darkgray');
				$barra->progress->SetPattern(BAND_SOLID, 'darkgray', 98);
				}
			}
		
		$grafico->Add($barra);
		}
	} 
unset($tarefas);
$hoje = date('y-m-d');
$linhaVert = new GanttVLine($hoje, 'Hoje');
$grafico->Add($linhaVert);
$grafico->Stroke();
?>
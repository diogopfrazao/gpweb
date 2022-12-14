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

global $Aplic, $cal_sdf,$ver_todos_projetos;
global $usar_periodo, $reg_data_inicio, $reg_data_fim;

$data_inicio = intval($reg_data_inicio) ? new CData($reg_data_inicio) : new CData(date('Y').'-01-01');
$data_fim = intval($reg_data_fim) ? new CData($reg_data_fim) : new CData(date('Y').'-12-31');
if (!$reg_data_inicio)	$data_inicio->subtrairIntervalo(new Data_Intervalo('14,0,0,0'));
$data_fim->setTime(23, 59, 59);

$mostrarNomeProjeto=nome_projeto($projeto_id);
$Aplic->carregarCalendarioJS();
if (!$Aplic->checarModulo('relatorios', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$fazer_relatorio=getParam($_REQUEST, 'fazer_relatorio', 0);

$log_ignorar=getParam($_REQUEST, 'log_ignorar', 0);
$log_ignorar_valor= getParam($_REQUEST, 'log_ignorar_valor', 0);
$usuario_id=getParam($_REQUEST, 'usuario_id', '0');

$sql = new BDConsulta;



$data = new CData();	
echo '<input type="hidden" name="fazer_relatorio" id="fazer_relatorio" value="" />';


$titulo = 'Registros d'.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($usuario_id ? ' pelo '.link_usuario($usuario_id) : ' por todos '.$config['genero_usuario'].'s '.$config['usuarios']).($projeto_id && (!$ver_todos_projetos) ? ' n'.$config['genero_projeto'].' '.$config['projeto'].' '.$mostrarNomeProjeto : ' em tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos']);
if (!$dialogo){
	$Aplic->salvarPosicao();
	echo '<table width="100%">';
	echo '<tr><td width="22">&nbsp;</td>';
	echo '<td align="center">';
	echo '<font size="4"><center>'.$titulo.'</center></font>';
	echo '</td>';
	echo '<td width="16">'.dica('Imprimir o relat?rio', 'Clique neste ?cone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poder? imprimir o relat?rio a partir do navegador Web.').'<a href="javascript: void(0);" onclick="env.target=\'popup\'; env.dialogo.value=1; env.sem_cabecalho.value=0; env.pdf.value=0; env.submit();"><img src="'.acharImagem('imprimir_p.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td>';
	echo '<td width="16">'.dica('Exportar o relat?rio para Pdf', 'Clique neste ?cone '.imagem('pdf_2.png').' para exportar o relat?rio no formato Pdf.').'<a href="javascript: void(0);" onclick="env.target=\'\'; env.dialogo.value=1; env.sem_cabecalho.value=1; env.pdf.value=1; env.page_orientation.value=\'P\'; env.submit();"><img src="'.acharImagem('pdf_2.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td>';
	if ($Aplic->profissional) echo '<td width="16">'.dica('Exportar Link' , 'Clique neste ?cone '.imagem('relatorio_externo_p.png').' para gerar um endere?o web para visualiza??o em ambiente externo do relat?rio.').'<a href="javascript: void(0)" onclick="exportar_link();"><img src="'.acharImagem('icones/relatorio_externo_p.png').'" border=0 /></a>'.dicaF().'</td>';
	echo '</tr>';
	echo '</table>';
	}
else if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $Aplic->usuario_cia;
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
	echo 	'<font size="4"><center>'.$titulo.'</center></font>';
	}
else echo '<font size="4"><center>'.$titulo.'</center></font>';


if (!$dialogo) echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding="4" border=0 width="100%" '.(!$dialogo ? 'class="std"' : '').'>';
if (!$dialogo) {
	echo '<tr><td align="left" style="white-space: nowrap">';
	echo dica(ucfirst($config['usuario']), 'Selecion para qual '.$config['usuario'].' deseja pesquisar os registros de  '.$config['tarefas'].'.').ucfirst($config['usuario']).':'.dicaF().'<input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a>';
	echo '<input style="vertical-align:middle" type="checkbox" name="usar_periodo" id="usar_periodo" '.($usar_periodo ? 'checked="checked"' : '').' />'.dica('Usar o Per?odo', 'Selecione esta caixa para exibir o resultado da pesquisa na faixa de tempo selecionada.').'Usar o per?odo'.dicaF();
	echo '<input style="vertical-align:middle" type="checkbox" name="log_ignorar" '.($log_ignorar ? "checked" : '').' />'.dica('Ignorar Registros sem Horas Trabalhadas', 'Ignorar os registros de  '.$config['tarefas'].' sem men??o das horas trabalhadas.').'Ignorar sem horas trabalhadas'.dicaF();
	echo '<input style="vertical-align:middle" type="checkbox" name="log_ignorar_valor" '.($log_ignorar_valor ? "checked" : '').' />'.dica('Ignorar Registros sem Valores Gastos', 'Ignorar os registros de  '.$config['tarefas'].' sem men??o de valores gastos.').'Ignorar sem valor'.dicaF();
	echo '</td><td align="right" style="white-space: nowrap">'.botao('exibir', 'Exibir', 'Exibir o resultado da pesquisa.','','env.fazer_relatorio.value=1; env.target=\'\'; env.dialogo.value=0; env.sem_cabecalho.value=0; env.pdf.value=0; env.submit();').'</td></tr>';
	}
if ($fazer_relatorio || $dialogo) {
	echo '<tr><td colspan=20>';
	$sql = new BDConsulta;
	$sql->adTabela('log', 'tl');
  
  $sql->esqUnir('tarefas', 't', 'tl.log_tarefa=t.tarefa_id');	
	$sql->esqUnir('projetos', 'pr', 't.tarefa_projeto = pr.projeto_id');
	$sql->esqUnir('usuarios', 'u', 'log_criador = u.usuario_id');
	$sql->esqUnir('cias', 'cias', 'pr.projeto_cia = cias.cia_id');
	$sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
	$sql->adOnde('pr.projeto_template=0 OR pr.projeto_template IS NULL');
	
	if ($projeto_plano_gestao) {
		$string_projeto_plano_gestao=projetos_plano_gestao($projeto_plano_gestao);
		if ($string_projeto_plano_gestao) $sql->adOnde('pr.projeto_id IN ('.$string_projeto_plano_gestao.')');
		}
	
	$sql->esqUnir('projeto_gestao', 'projeto_gestao', 'pr.projeto_id=projeto_gestao_projeto');
	
	if ($tarefa_id) $sql->adOnde('projeto_gestao_tarefa IN ('.$tarefa_id.')');
	elseif ($gestao_projeto_id){
		$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=projeto_gestao_tarefa');
		$sql->adOnde('projeto_gestao_semelhante IN ('.$gestao_projeto_id.') OR tarefas2.tarefa_projeto IN ('.$gestao_projeto_id.')');
		}
	elseif ($pg_perspectiva_id) $sql->adOnde('projeto_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
	elseif ($tema_id) $sql->adOnde('projeto_gestao_tema IN ('.$tema_id.')');
	elseif ($objetivo_id) $sql->adOnde('projeto_gestao_objetivo IN ('.$objetivo_id.')');
	elseif ($fator_id) $sql->adOnde('projeto_gestao_fator IN ('.$fator_id.')');
	elseif ($pg_estrategia_id) $sql->adOnde('projeto_gestao_estrategia IN ('.$pg_estrategia_id.')');
	elseif ($pg_meta_id) $sql->adOnde('projeto_gestao_meta IN ('.$pg_meta_id.')');
	elseif ($pratica_id) $sql->adOnde('projeto_gestao_pratica IN ('.$pratica_id.')');
	elseif ($pratica_indicador_id) $sql->adOnde('projeto_gestao_indicador IN ('.$pratica_indicador_id.')');
	elseif ($plano_acao_id) $sql->adOnde('projeto_gestao_acao IN ('.$plano_acao_id.')');
	elseif ($canvas_id) $sql->adOnde('projeto_gestao_canvas IN ('.$canvas_id.')');
	elseif ($risco_id) $sql->adOnde('projeto_gestao_risco IN ('.$risco_id.')');
	elseif ($risco_resposta_id) $sql->adOnde('projeto_gestao_risco_resposta IN ('.$risco_resposta_id.')');
	elseif ($calendario_id) $sql->adOnde('projeto_gestao_calendario IN ('.$calendario_id.')');
	elseif ($monitoramento_id) $sql->adOnde('projeto_gestao_monitoramento IN ('.$monitoramento_id.')');
	elseif ($ata_id) $sql->adOnde('projeto_gestao_ata IN ('.$ata_id.')');
	elseif ($mswot_id) $sql->adOnde('projeto_gestao_mswot IN ('.$mswot_id.')');
	elseif ($swot_id) $sql->adOnde('projeto_gestao_swot IN ('.$swot_id.')');
	elseif ($operativo_id) $sql->adOnde('projeto_gestao_operativo IN ('.$operativo_id.')');
	elseif ($instrumento_id) $sql->adOnde('projeto_gestao_instrumento IN ('.$instrumento_id.')');
	elseif ($recurso_id) $sql->adOnde('projeto_gestao_recurso IN ('.$recurso_id.')');
	elseif ($problema_id) $sql->adOnde('projeto_gestao_problema IN ('.$problema_id.')');
	elseif ($demanda_id) $sql->adOnde('projeto_gestao_demanda IN ('.$demanda_id.')');
	elseif ($programa_id) $sql->adOnde('projeto_gestao_programa IN ('.$programa_id.')');
	elseif ($licao_id) $sql->adOnde('projeto_gestao_licao IN ('.$licao_id.')');
	elseif ($evento_id) $sql->adOnde('projeto_gestao_evento IN ('.$evento_id.')');
	elseif ($link_id) $sql->adOnde('projeto_gestao_link IN ('.$link_id.')');
	elseif ($avaliacao_id) $sql->adOnde('projeto_gestao_avaliacao IN ('.$avaliacao_id.')');
	elseif ($tgn_id) $sql->adOnde('projeto_gestao_tgn IN ('.$tgn_id.')');
	elseif ($brainstorm_id) $sql->adOnde('projeto_gestao_brainstorm IN ('.$brainstorm_id.')');
	elseif ($gut_id) $sql->adOnde('projeto_gestao_gut IN ('.$gut_id.')');
	elseif ($causa_efeito_id) $sql->adOnde('projeto_gestao_causa_efeito IN ('.$causa_efeito_id.')');
	elseif ($arquivo_id) $sql->adOnde('projeto_gestao_arquivo IN ('.$arquivo_id.')');
	elseif ($forum_id) $sql->adOnde('projeto_gestao_forum IN ('.$forum_id.')');
	elseif ($checklist_id) $sql->adOnde('projeto_gestao_checklist IN ('.$checklist_id.')');
	elseif ($agenda_id) $sql->adOnde('projeto_gestao_agenda IN ('.$agenda_id.')');
	elseif ($agrupamento_id) $sql->adOnde('projeto_gestao_agrupamento IN ('.$agrupamento_id.')');
	elseif ($patrocinador_id) $sql->adOnde('projeto_gestao_patrocinador IN ('.$patrocinador_id.')');
	elseif ($template_id) $sql->adOnde('projeto_gestao_template IN ('.$template_id.')');
	elseif ($painel_id) $sql->adOnde('projeto_gestao_painel IN ('.$painel_id.')');
	elseif ($painel_odometro_id) $sql->adOnde('projeto_gestao_painel_odometro IN ('.$painel_odometro_id.')');
	elseif ($painel_composicao_id) $sql->adOnde('projeto_gestao_painel_composicao IN ('.$painel_composicao_id.')');
	elseif ($tr_id) $sql->adOnde('projeto_gestao_tr IN ('.$tr_id.')');
	elseif ($me_id) $sql->adOnde('projeto_gestao_me IN ('.$me_id.')');
	elseif ($plano_acao_item_id) $sql->adOnde('projeto_gestao_acao_item IN ('.$plano_acao_item_id.')');
	elseif ($beneficio_id) $sql->adOnde('projeto_gestao_beneficio IN ('.$beneficio_id.')');
	elseif ($painel_slideshow_id) $sql->adOnde('projeto_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
	elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
	elseif ($projeto_abertura_id) $sql->adOnde('projeto_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
	elseif ($pg_id) $sql->adOnde('projeto_gestao_plano_gestao IN ('.$pg_id.')');
	elseif ($ssti_id) $sql->adOnde('projeto_gestao_ssti IN ('.$ssti_id.')');
	elseif ($laudo_id) $sql->adOnde('projeto_gestao_laudo IN ('.$laudo_id.')');
	elseif ($trelo_id) $sql->adOnde('projeto_gestao_trelo IN ('.$trelo_id.')');
	elseif ($trelo_cartao_id) $sql->adOnde('projeto_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
	elseif ($pdcl_id) $sql->adOnde('projeto_gestao_pdcl IN ('.$pdcl_id.')');
	elseif ($pdcl_item_id) $sql->adOnde('projeto_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
	elseif ($os_id) $sql->adOnde('projeto_gestao_os IN ('.$os_id.')');	

	if ($filtro_criterio){
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=projeto_gestao.projeto_gestao_pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		}
		
	if ($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta)	{
		$filtragem=array();
		if ($filtro_criterio) $filtragem[]='pratica_item_criterio IN ('.$filtro_criterio.')';
		if ($filtro_perspectiva) $filtragem[]='projeto_gestao_perspectiva IN ('.$filtro_perspectiva.')';
		if ($filtro_tema) $filtragem[]='projeto_gestao_tema IN ('.$filtro_tema.')';
		if ($filtro_objetivo) $filtragem[]='projeto_gestao_objetivo IN ('.$filtro_objetivo.')';
		if ($filtro_fator) $filtragem[]='projeto_gestao_fator IN ('.$filtro_fator.')';
		if ($filtro_estrategia) $filtragem[]='projeto_gestao_estrategia IN ('.$filtro_estrategia.')';
		if ($filtro_meta) $filtragem[]='projeto_gestao_meta IN ('.$filtro_meta.')';
		if (count($filtragem)) $sql->adOnde(implode(' OR ', $filtragem));
		}	

	
	
	if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
	if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
	if (!$portfolio && !$portfolio_pai) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
	elseif($portfolio && !$portfolio_pai)  $sql->adOnde('pr.projeto_portfolio=1 AND (pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL)');
	if ($portfolio_pai){
		$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
		$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
		}
	if ($favorito_id){
		$sql->internoUnir('favorito_lista', 'favorito_lista', 'pr.projeto_id=favorito_lista_campo');
		$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
		$sql->adOnde('favorito.favorito_id IN ('.$favorito_id.')');
		}
	if($dept_id) $sql->esqUnir('projeto_depts', 'projeto_depts', 'projeto_depts.projeto_id = pr.projeto_id');
	if (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');		
	
	if ($projetostatus && !$projeto_expandido){
		if ($Aplic->profissional){
			$projetostatus=explode(',',$projetostatus);
			if (in_array(-1,$projetostatus) && !in_array(-2,$projetostatus)) $sql->adOnde('projeto_ativo=1');
			if (in_array(-2,$projetostatus) && !in_array(-1,$projetostatus)) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
			$qnt_status=0;
			$status_fim='';
			foreach($projetostatus as $status) if ($status > 0) $status_fim.=($qnt_status++ ? ',' : '').$status;
			if ($status_fim) $sql->adOnde('projeto_status IN ('.$status_fim.')');
			}
		else {
			if ($projetostatus == -1) $sql->adOnde('projeto_ativo=1');
			elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo=0 OR projeto_ativo IS NULL');
			elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
			}
		}

	else $sql->adOnde('projeto_ativo = 1');	
	if($dept_id) $sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.')');	
	if ($cia_id  && !$lista_cias && !$favorito_id)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id);
	elseif ($lista_cias && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.')');
	if ($projeto_tipo > -1)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
	if ($projeto_setor) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
	if ($projeto_segmento) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
	if ($projeto_intervencao) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
	if ($projeto_tipo_intervencao) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
	if ($supervisor) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
	if ($autoridade) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
	if ($responsavel) $sql->adOnde('pr.projeto_responsavel IN ('.$responsavel.')');
	if (trim($pesquisar_texto)) $sql->adOnde('pr.projeto_nome LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_objetivos LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_como LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_codigo LIKE \'%'.$pesquisar_texto.'%\'');

	$sql->adOnde('projeto_template = 0');
	$sql->adCampo('tl.*, projeto_nome, t.tarefa_id, t.tarefa_descricao, projeto_descricao, pr.projeto_id, usuario_id');

	if ($projeto_id) {
		if ($Aplic->profissional) {
				$vetor=array();
				portfolio_projetos($projeto_id, $vetor);
				$projeto_id=implode(',',$vetor);
				}
		$sql->adOnde('pr.projeto_id IN ('.$projeto_id.')');
		}
	
	if ($usar_periodo) {
		$sql->adOnde('log_data >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
		$sql->adOnde('log_data <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'');
		}
		
	if ($log_ignorar) $sql->adOnde('log_horas > 0');
	if ($log_ignorar_valor) $sql->adOnde('log_custo > 0');
	if ($usuario_id) $sql->adOnde('log_criador = '.(int)$usuario_id);
	$sql->adOrdem('cia_nome, projeto_nome');
	$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_nome, log_data');
	$logs = $sql->Lista();
	echo '<table align="center" cellspacing=0 cellpadding="4" class="tbl1"><tr><th>&nbsp;</th><th>Ref</th><th>Data</th><th>T?tulo</th><th>Descri??o</th><th>Horas</th><th>URL</th><th>'.ucfirst($config['usuario']).'</th><th>ND</th><th>Custo</th></tr>';
	$horas = 0;
	$projeto='';
	$custo=array();
	$qnt=0;	
	foreach ($logs as $log) {
		$qnt++;
		$data = new CData($log['log_data']);
		$horas += $log['log_horas'];
		echo '<tr>';
		if ($projeto!=$log['projeto_nome']){
			if ($projeto) {
					$s = '<td align="right" colspan="6">';
					foreach ($custo as $nd => $somatorio) $s .= $nd.' :<br>';
					$s .= '<br><b>Total Geral :</b></td>';
					$s .= '<td align="right">';
					$somatorio_total=0;
					foreach ($custo as $nd => $somatorio) {
						$s .= number_format($somatorio, 2, ',', '.').'<br>';
						$somatorio_total+=$somatorio;
						}
					$s .= '<br><b>'.number_format($somatorio_total, 2, ',', '.').'</b></td>';	
					echo $s.'</tr><tr>';
					$custo=array();	
					}
			echo'<td colspan="10" align="left"><b>'.link_projeto($log['projeto_id']).'</b></td><tr>';
			$projeto=$log['projeto_nome'];
			}
		echo'<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.link_tarefa($log['tarefa_id']).'</td>';
		
		$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
		$RefRegistroTarefaImagem = getSisValor('RefRegistroTarefaImagem');
		$imagem_referencia = '-';
		$imagem_pdf = '-';
		if ($log['log_referencia'] > 0) {
			if (isset($RefRegistroTarefaImagem[$log['log_referencia']])) {
				$imagem_referencia = imagem('icones/'.$RefRegistroTarefaImagem[$log['log_referencia']], imagem('icones/'.$RefRegistroTarefaImagem[$log['log_referencia']]).' '.$RefRegistroTarefa[$log['log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
				$imagem_pdf =$RefRegistroTarefa[$log['log_referencia']];
				}
			elseif (isset($RefRegistroTarefa[$log['log_referencia']])) $imagem_pdf =$imagem_referencia = $RefRegistroTarefa[$log['log_referencia']];
			}
		echo '<td align="center" valign="middle">'.$imagem_referencia.'</td>';
		echo '<td>'.$data->format('%d/%m/%Y').'</td>';
		$estilo = $log['log_corrigir'] ? 'background-color:#cc6666;color:#ffffff' : ''; 
		echo '<td style="'.$estilo.'">'.$log['log_nome'].'</td>';
		echo '<td>'.$log['log_descricao'].'</td>';
		echo '<td align="right">'.number_format($log['log_horas'], 0, ',', '.').'</td>';
		echo !empty($log['log_url_relacionada']) ? '<td align="center" valign="middle">'.dica('Link', 'Clique neste ?cone '.imagem('icones/link.png').' para  acessar:<ul><li>'.$log['log_url_relacionada'].'</ul>').'<a href="'.$log['log_url_relacionada'].'">'.imagem('icones/link.png').'</a>'.dicaF().'</td>' : '<td>&nbsp;</td>';
		echo'<td style="white-space: nowrap">'.link_usuario($log['usuario_id'],'','','esquerda').'</td>';
		$nd=($log['log_categoria_economica'] && $log['log_grupo_despesa'] && $log['log_modalidade_aplicacao'] ? $log['log_categoria_economica'].'.'.$log['log_grupo_despesa'].'.'.$log['log_modalidade_aplicacao'].'.' : '').$log['log_nd'];
		echo'<td align="right">'.$nd.'</td>';
		echo'<td align="right">'.number_format($log['log_custo'], 2, ',', '.').'</td>';
		echo'</tr>';
		if (isset($custo[$nd])) $custo[$nd] += (float)$log['log_custo'];
		else $custo[$nd] = (float)$log['log_custo'];
		}
	if (!$qnt) echo '<tr><td colspan="10"><p>Nenhum registro encontrado</p></td></tr>';		
	if ($projeto) {
		echo '<tr><td align="right" colspan="5" valign="top"><b>Total Horas:</b></td><td align="right" valign="top"><b>'.$horas.'</b></td><td align="right" colspan="3">';
		foreach ($custo as $nd => $somatorio) {
			echo $nd.' : <br>';
			}
		echo '<br><b>Total Geral :</b></td><td align="right">';
		$somatorio_total=0;
		foreach ($custo as $nd => $somatorio) {
			echo number_format($somatorio, 2, ',', '.').'<br>';
			$somatorio_total+=$somatorio;
			}
		echo '<br><b>'.number_format($somatorio_total, 2, ',', '.').'</b></td></tr><tr>';	
		}
	echo '</table>';
	echo '</td></tr></table>';
	}
if (!$dialogo) echo estiloFundoCaixa();	
?>
<script type="text/javascript">

function exportar_link(tipo) {
	parent.gpwebApp.popUp('Link', 900, 100, 'm=publico&a=exportar_link_pro&dialogo=1&tipo=generico', null, window);
	}	
	
	
function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usu?rio','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}	
	
</script>
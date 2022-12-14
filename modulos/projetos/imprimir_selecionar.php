<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));
$baseline_id = intval(getParam($_REQUEST, 'baseline_id', 0));


$obj = new CProjeto(($baseline_id ? $baseline_id : false));
$obj->load($projeto_id, true, $baseline_id);
$editar=(permiteEditar($obj->projeto_acesso, $projeto_id) && $podeEditar);
$podeEditarTudo=($obj->projeto_acesso>=5 ? $editar && (in_array($obj->projeto_responsavel, $Aplic->usuario_lista_grupo_vetor) || $Aplic->usuario_super_admin || $Aplic->usuario_admin) : $editar);
if ($podeEditarTudo && $podeEditar) $pode_exportar=true;
else $pode_exportar=false;






echo '<form name="mudar" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';

echo '<input type="hidden" name="cifrado" id="cifrado" value="" />';




$sql = new BDConsulta;
/*	
$sql->adTabela('projetos');
$sql->adCampo('projeto_portfolio');
$sql->adOnde('projeto_id ='.(int)$projeto_id));
$projeto_portfolio = $sql->Resultado();
$sql->limpar();	
*/

$botoesTitulo = new CBlocoTitulo('Imprimir Documento '.($obj->projeto_portfolio ? 'd'.$config['genero_portfolio'].' '.ucfirst($config['portfolio']) : 'd'.$config['genero_projeto'].' '.ucfirst($config['projeto'])), 'impressao.png');
$botoesTitulo->mostrar();	
	
	
	
echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=2 width="100%" class="std">';

if ($Aplic->profissional) {
	echo '<tr><td colspan=20><table><tr><td>'.dica('Tela', 'Exibir o relat?rio na tela do navegador.').'<input type="radio" id="tela" name="exibicao" value="tela" checked />Tela'.dicaF().dica('PDF','Enviar relat?rio para arquivo PDF.').'</td><td><input type="radio" id="pdf" name="exibicao" value="pdf" />PDF'.dicaF().'</td>'.($pode_exportar ? '<td>'.dica('Exportar Link','Cria o hiperlink para acesso externo ao relat?rio.').'<input type="radio" id="exportar" name="exibicao" value="exportar" />Exportar Link'.dicaF().'</td>' : '').'</tr></table></td></tr>';	
	
	
	
	echo '<tr><td colspan=20><table><tr><td>Rodap?:</td>
	<td>'.dica(ucfirst($config['gerente']), 'Exibir '.$config['gerente'].' para assinar no rodap? do documento.').'<input type="checkbox" id="gerente" name="gerente" value="1" />'.ucfirst($config['gerente']).dicaF().'</td>
	<td>'.dica(ucfirst($config['supervisor']), 'Exibir '.$config['supervisor'].' para assinar no rodap? do documento.').'<input type="checkbox" id="supervisor" name="supervisor" value="1" />'.ucfirst($config['supervisor']).dicaF().'</td>
	<td>'.dica(ucfirst($config['autoridade']), 'Exibir '.$config['autoridade'].' para assinar no rodap? do documento.').'<input type="checkbox" id="autoridade" name="autoridade" value="1" />'.ucfirst($config['autoridade']).dicaF().'</td>
	<td>'.dica(ucfirst($config['cliente']), 'Exibir '.$config['cliente'].' para assinar no rodap? do documento.').'<input type="checkbox" id="cliente" name="cliente" value="1" />'.ucfirst($config['cliente']).dicaF().'</td>
	<td>'.dica('C?digo de Barras', 'Exibir o c?digo de barras no rodap? do documento.').'<input type="checkbox" id="barra" name="barra" value="1" />C?digo de Barra'.dicaF().'</td>
	</tr></table></td></tr>';	
	}
else {
	echo '<input type="hidden" name="pdf" id="pdf" value="" />';
	echo '<input type="hidden" name="exportar" id="exportar" value="" />';
	
	echo '<input type="hidden" name="gerente" id="gerente" value="" />';
	echo '<input type="hidden" name="supervisor" id="supervisor" value="" />';
	echo '<input type="hidden" name="autoridade" id="autoridade" value="" />';
	echo '<input type="hidden" name="cliente" id="cliente" value="" />';
	echo '<input type="hidden" name="barra" id="barra" value="" />';
	}

echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=ver&imprimir_detalhe=1&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Detalhamento</a></td></tr>';
echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_visao_geral&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Vis?o geral</a></td></tr>';


if ($Aplic->profissional) {
	
	$lista_projeto=0;
	if ($Aplic->profissional){
		$vetor=array($projeto_id => $projeto_id);
		portfolio_projetos($projeto_id, $vetor);
		$lista_projeto=implode(',',$vetor);
		}
	
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas', ($baseline_id ? 'tarefas.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('count(tarefa_id)');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$existe=$sql->resultado();
	$sql->limpar();
	if ($existe) {
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=financeiro_pizza_pro&dialogo=1&jquery=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Est?gios da despesa (empenho, liquida??o e pagamento)</a></td></tr>';
		if ($Aplic->modulo_ativo('financeiro')) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=financeiro_siafi_pro&dialogo=1&jquery=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Est?gios da despesa vs anexos d'.$config['genero_siafi'].' '.$config['siafi'].'</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_tarefas_atraso_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">'.ucfirst($config['tarefas']).' em atraso</a></td></tr>';
		if (!$obj->projeto_portfolio) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_geral_tarefas_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Geral d'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		if (!$obj->projeto_portfolio) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_status_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Status d'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		if (!$obj->projeto_portfolio) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_inconsistencia_status_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Inconsist?ncia do status d'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		if (!$obj->projeto_portfolio) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_execucao_tarefa_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Execu??o d'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_cha_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Compet?ncias dos envolvidos n'.$config['genero_tarefa'].'s '.$config['tarefas'].'</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&u=eb&a=cronograma_financeiro_ver&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Cronograma F?sico Financeiro</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&u=eb&a=dicionario_eap_ver&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Dicion?rio da EAP</a></td></tr>';
		echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&u=eb&a=estrutura_analitica_ver&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Estrutura Anal?tica</a></td></tr>';
		}
		
	if ($Aplic->modulo_ativo('financeiro')) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=financeiro_resumo_siafi_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Resumo das informa??es importadas d'.$config['genero_siafi'].' '.$config['siafi'].'</a></td></tr>';
	
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_stakeholder', 'projeto_stakeholder', ($baseline_id ? 'projeto_stakeholder.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('count(projeto_stakeholder_id)');
	$sql->adOnde('projeto_stakeholder_projeto='.(int)$projeto_id);
	$existe=$sql->resultado();
	$sql->limpar();
	if ($existe) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=stakeholder_pro_imprimir&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Matriz de Stakeholders</a></td></tr>';
	
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_consolidacao&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Consolida??o d'.$config['genero_projeto'].' '.$config['projeto'].'</a></td></tr>';
	
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=imprimir_consolidacao2&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Consolida??o extensa d'.$config['genero_projeto'].' '.$config['projeto'].'</a></td></tr>';
	
	
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'priorizacao', 'priorizacao', ($baseline_id ? 'priorizacao.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('count(priorizacao_projeto)');
	$sql->adOnde('priorizacao_projeto='.(int)$projeto_id);
	$existe=$sql->resultado();
	$sql->limpar();
	
	if ($existe) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=priorizacao_imprimir_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Prioriza??o</a></td></tr>';
	}
	
	
if ($config['anexo_mpog']){

	$sql->adTabela('demandas');
	$sql->adCampo('demanda_id, demanda_viabilidade, demanda_termo_abertura, demanda_caracteristica_projeto');
	$sql->adOnde('demanda_projeto='.(int)$projeto_id);
	$linha1=$sql->linha();
	$sql->limpar();
	if (isset($linha1['demanda_id']) && $linha1['demanda_id']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=demanda_imprimir&dialogo=1&demanda_id='.$linha1['demanda_id'].'\');">Documento de Oficializa??o da Demanda (DOD)</a></td></tr>';
	if (isset($linha1['demanda_caracteristica_projeto']) && $linha1['demanda_caracteristica_projeto']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=demanda_analise_imprimir&dialogo=1&demanda_id='.$linha1['demanda_id'].'\');">Mensura??o do Tamanho d'.$config['genero_projeto'].' '.$config['projeto'].' (MTP)</a></td></tr>';
	if (isset($linha1['demanda_viabilidade']) && $linha1['demanda_viabilidade']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=viabilidade_imprimir&dialogo=1&projeto_viabilidade_id='.$linha1['demanda_viabilidade'].'\');">An?lise de Viabilidade d'.$config['genero_projeto'].' '.$config['projeto'].' (AVP)</a></td></tr>';
	if (isset($linha1['demanda_termo_abertura']) && $linha1['demanda_termo_abertura']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=termo_abertura_imprimir&dialogo=1&projeto_abertura_id='.$linha1['demanda_termo_abertura'].'\');">Termo de Abertura d'.$config['genero_projeto'].' '.$config['projeto'].' (TAP)</a></td></tr>';
	
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=gerenciamento_imprimir&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Plano de Gerenciamento d'.$config['genero_projeto'].' '.$config['projeto'].' (PGP)</a></td></tr>';
	
	$sql->adTabela('projeto_qualidade');
	$sql->adCampo('projeto_qualidade_usuario');
	$sql->adOnde('projeto_qualidade_projeto='.(int)$projeto_id);
	$linha3=$sql->linha();
	$sql->limpar();
	if (isset($linha3['projeto_qualidade_usuario']) && $linha3['projeto_qualidade_usuario']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=qualidade_imprimir&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Plano de Qualidade (PQ)</a></td></tr>';
	
	$sql->adTabela('projeto_comunicacao');
	$sql->adCampo('projeto_comunicacao_usuario');
	$sql->adOnde('projeto_comunicacao_projeto='.(int)$projeto_id);
	$linha4=$sql->linha();
	$sql->limpar();
	if (isset($linha4['projeto_comunicacao_usuario']) && $linha4['projeto_comunicacao_usuario']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=comunicacao_imprimir&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Plano de Comunica??o (PC)</a></td></tr>';

	$sql->adTabela('projeto_risco');
	$sql->adCampo('projeto_risco_usuario');
	$sql->adOnde('projeto_risco_projeto='.(int)$projeto_id);
	$linha5=$sql->linha();
	$sql->limpar();
	if (isset($linha5['projeto_risco_usuario']) && $linha5['projeto_risco_usuario']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=risco_imprimir&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Plano de Gerenciamento de Riscos (PGR)</a></td></tr>';
	
	$sql->adTabela('pratica_indicador_gestao');
	$sql->adCampo('count(pratica_indicador_gestao_id)');
	$sql->adOnde('pratica_indicador_gestao_projeto ='.(int)$projeto_id);
	$indicadores = $sql->Resultado();
	$sql->limpar();
	if ($indicadores) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=indicadores_imprimir&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Matriz de Controle de Indicadores</a></td></tr>';

	$sql->adTabela('projeto_encerramento');
	$sql->adCampo('projeto_encerramento_responsavel');
	$sql->adOnde('projeto_encerramento_projeto='.(int)$projeto_id);
	$linha7=$sql->linha();
	$sql->limpar();
	if (isset($linha7['projeto_encerramento_responsavel']) && $linha7['projeto_encerramento_responsavel']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=projetos&a=encerramento_imprimir&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Termo de Encerramento de Projeto (TEP)</a></td></tr>';
	
	if ($config['anexo_eb']) echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=relatorios&a=index&relatorio_tipo=status_negapeb_pro&dialogo=1&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Situa??o dos documentos d'.$config['genero_anexo_eb_nome'].' '.$config['anexo_eb_nome'].'</a></td></tr>';
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=relatorios&a=index&dialogo=1&jquery=1&self_print=0&veio_projeto=1&relatorio_tipo=grafico_financeiro_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Curva S do financeiro</a></td></tr>';
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=relatorios&a=index&dialogo=1&jquery=1&self_print=1&veio_projeto=1&relatorio_tipo=grafico_fisico_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Curva S do f?sico</a></td></tr>';
	echo '<tr><td colspan=20><a href="javascript: void(0);" onclick="ir_para2(\'m=relatorios&a=index&dialogo=1&jquery=1&self_print=0&veio_projeto=1&relatorio_tipo=grafico_fisico_financeiro_pro&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id.'\');">Curva S do f?sico e financeiro</a></td></tr>';


	
	$sql->adTabela('causa_efeito');
	$sql->esqUnir('causa_efeito_gestao','causa_efeito_gestao','causa_efeito_gestao.causa_efeito_gestao_causa_efeito=causa_efeito.causa_efeito_id');
	$sql->adCampo('causa_efeito.causa_efeito_id, causa_efeito_nome');
	$sql->adOnde('causa_efeito_gestao_projeto='.(int)$projeto_id);
	$vetor_mudanca = array(0 => '')+$sql->listaVetorChave('causa_efeito_id','causa_efeito_nome');
	$sql->limpar();
	

	if (count($vetor_mudanca)>1) echo '<tr><td colspan=20>?rvore de Problemas:'.selecionaVetor($vetor_mudanca,'causa_efeito_id','class="texto" onchange="imprimir_causa_efeito()"').'</td></tr>';
	
	$sql->adTabela('projeto_mudanca');
	$sql->adCampo('projeto_mudanca_id, projeto_mudanca_numero');
	$sql->adOnde('projeto_mudanca_projeto='.(int)$projeto_id);
	$vetor_mudanca = array(0 => '')+$sql->listaVetorChave('projeto_mudanca_id','projeto_mudanca_numero');
	$sql->limpar();
	if (count($vetor_mudanca)>1) echo '<tr><td colspan=20>Formul?rio de Solicita??o de Mudan?as (FSM):'.selecionaVetor($vetor_mudanca,'projeto_mudanca_id','class="texto" onchange="imprimir_solicitacao_mudanca();"').'</td></tr>';
	
	$sql->adTabela('projeto_recebimento');
	$sql->adCampo('projeto_recebimento_id, projeto_recebimento_numero');
	$sql->adOnde('projeto_recebimento_projeto='.(int)$projeto_id);
	$vetor_recebimento = array(0 => '')+$sql->listaVetorChave('projeto_recebimento_id','projeto_recebimento_numero');
	$sql->limpar();
	if (count($vetor_recebimento)>1) echo '<tr><td colspan=20>Termo de Recebimento de Produto/Servi?o (TRPS):'.selecionaVetor($vetor_recebimento,'projeto_recebimento_id','class="texto" onchange="imprimir_termo_recebimento();"').'</td></tr>';
	
		
	$sql->adTabela('ata');
	$sql->esqUnir('ata_gestao','ata_gestao','ata_gestao.ata_gestao_ata=ata.ata_id');
	$sql->adCampo('ata_id, ata_numero');
	$sql->adOnde('ata_gestao_projeto='.(int)$projeto_id);
	$vetor_ata = array(0 => '')+$sql->listaVetorChave('ata_id','ata_numero');
	$sql->limpar();
	if (count($vetor_ata)>1) echo '<tr><td colspan=20>Ata de Reuni?o:'.selecionaVetor($vetor_ata,'ata_id','class="texto" onchange="imprimir_ata_reuniao();"').'</td></tr>';
	
	$sql->adTabela('licao');
	$sql->adCampo('licao_id, licao_nome');
	$sql->adOnde('licao_projeto='.(int)$projeto_id);
	$vetor_licao = array(0 => '')+$sql->listaVetorChave('licao_id','licao_nome');
	$sql->limpar();
	if (count($vetor_licao)>1) echo '<tr><td colspan=20>Li??es Aprendidas (LA):'.selecionaVetor($vetor_licao,'licao_id','class="texto" onchange="imprimir_licao_aprendida();"').'</td></tr>';
	
	}
	
if ($Aplic->profissional) {
	$dias=array(''=>'');
	for ($i = 1; $i <= 60; $i++)$dias[$i]=$i;
	echo '<tr><td colspan=20>Resumo d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).' com eventos no per?odo:'.selecionaVetor($dias,'dias','class="texto" onchange="imprimir_resumo_evento();"').' dias</td></tr>';
	

	$sql->adTabela('projeto_area');
	$sql->adCampo('projeto_area_id, projeto_area_nome, projeto_area_obs');
	$sql->adOnde('projeto_area_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$sql->adOrdem('projeto_area_tarefa ASC');
	$lista_areas = array(0=>'', -1=>'?reas d'.$config['genero_tarefa'].'s '.$config['tarefas'].' e '.$config['projeto'], -2=>'?reas d'.$config['genero_tarefa'].'s '.$config['tarefas'], -3=>'?reas d'.$config['genero_projeto'].' '.$config['projeto'])+$sql->listaVetorChave('projeto_area_id','projeto_area_nome');
	$sql->limpar();
	$tipo_area=array(
		'cor'=>'Cor das  ?reas', 
		'fisico_tarefa'=>'F?sico executado d'.$config['genero_tarefa'].'s '.$config['tarefas'], 
		'fisico_projeto'=>'F?sico executado d'.$config['genero_projeto'].' '.$config['projeto'], 
		'status_tarefa'=>'Status d'.$config['genero_tarefa'].'s '.$config['tarefas'], 
		'status_projeto'=>'Status d'.$config['genero_projeto'].' '.$config['projeto']
		);
	if (count($lista_areas)>2) echo '<tr><td colspan=20>?reas:'.selecionaVetor($lista_areas,'lista_areas','class="texto" onchange="imprimir_area();"').selecionaVetor($tipo_area,'tipo_area','class="texto"').'</td></tr>';
	
	
	
	$sql->adTabela('municipio_lista');
	$sql->adCampo('count(municipio_lista_municipio)');
	$sql->adOnde('municipio_lista_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));
	$municipios_projeto=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('municipio_lista');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefa_id=municipio_lista_tarefa');
	$sql->adCampo('count(municipio_lista_municipio)');
	$sql->adOnde('tarefa_projeto '.($lista_projeto ? 'IN('.$lista_projeto.')' : '='.(int)$projeto_id));

	$municipios_tarefa=$sql->Resultado();
	$sql->limpar();
	
	$lista_municipios = array(0=>'');
	if ($municipios_projeto > 0 && $municipios_tarefa > 0) $lista_municipios['projeto_tarefa']='Munic?pios d'.$config['genero_tarefa'].'s '.$config['tarefas'].' e '.$config['projeto'];
	if ($municipios_tarefa> 0) $lista_municipios['tarefa']='Munic?pios d'.$config['genero_tarefa'].'s '.$config['tarefas'];
	if ($municipios_projeto > 0) $lista_municipios['projeto']='Munic?pios d'.$config['genero_projeto'].' '.$config['projeto'];
	
	$tipo_area_municipio=array();
	if ($municipios_projeto > 0) {
		$tipo_area_municipio['fisico_projeto']='F?sico executado d'.$config['genero_projeto'].' '.$config['projeto'];
		$tipo_area_municipio['status_projeto']='Status d'.$config['genero_projeto'].' '.$config['projeto'];
		}
	if ($municipios_tarefa > 0) {
		$tipo_area_municipio['fisico_tarefa']='F?sico executado d'.$config['genero_tarefa'].'s '.$config['tarefas'];
		$tipo_area_municipio['status_tarefa']='Status d'.$config['genero_tarefa'].'s '.$config['tarefas'];
		}
	

	if (count($lista_municipios)>1) echo '<tr><td colspan=20>Munic?pios:'.selecionaVetor($lista_municipios,'lista_municipios','class="texto" onchange="imprimir_municipios();"').selecionaVetor($tipo_area_municipio,'tipo_area_municipio','class="texto" onchange="imprimir_municipios();"').'</td></tr>';
	

	
	}
		


echo '</form>';

echo '</table>';
echo estiloFundoCaixa();
?>
<script type="text/JavaScript">


function imprimir_area(){
	var elmId = document.getElementById('lista_areas');
	var tipo = document.getElementById('tipo_area').value;
	if(!elmId.selectedIndex) return;
	var url = 'm=projetos&a=imprimir_area_pro&dialogo=1&tipo='+tipo+'&projeto_area_id='+ elmId.value+"<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>";
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}


function imprimir_municipios(){
	var opcao_municipio = document.getElementById('lista_municipios');
	var tipo = document.getElementById('tipo_area_municipio').value;
	if(!opcao_municipio.selectedIndex) return;
	if ((tipo=='fisico_tarefa' || tipo=='status_tarefa') && opcao_municipio.value!='tarefa'){
		alert('Combina??o inv?lida!');
		return;
		}
	var url = 'm=projetos&a=imprimir_ municipios_pro&dialogo=1&tipo='+tipo+'&opcao_municipio='+opcao_municipio.value+"<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>";
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}


function ir_para2(url){
	var pdf=document.getElementById('pdf').checked;
	var exportar=document.getElementById('exportar').checked;
	
	
	var gerente=(document.getElementById('gerente').checked ? 1 : 0);
	var supervisor=(document.getElementById('supervisor').checked ? 1 : 0);
	var autoridade=(document.getElementById('autoridade').checked ? 1 : 0);
	var cliente=(document.getElementById('cliente').checked ? 1 : 0);
	var barra=(document.getElementById('barra').checked ? 1 : 0);
	
	var extra='&gerente='+gerente+'&supervisor='+supervisor+'&autoridade='+autoridade+'&cliente='+cliente+'&barra='+barra;
	url +=extra;
	
	if (pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	
	if (exportar) {
	 	xajax_criptografar(url, '254581');
	 	window.open('./index.php?m=publico&a=exportar_link_pro&dialogo=1&tipo=endereco&endereco='+document.getElementById('cifrado').value, 'Link', 'height=100,width=900,resizable,scrollbars=yes');
		}
	else url_passar(1, url);
	}


function ir_para(m, a, u){
	url_passar(1, 'm='+m+'&a='+a+'&u='+u+'&dialogo=1<?php echo "&baseline_id=".$baseline_id."&projeto_id=".$projeto_id ?>');
	}
	
function imprimir_causa_efeito(){
	var elmId = document.getElementById('causa_efeito_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=causa_efeito_imprimir&dialogo=1&causa_efeito_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	
	
	var gerente=(document.getElementById('gerente').checked ? 1 : 0);
	var supervisor=(document.getElementById('supervisor').checked ? 1 : 0);
	var autoridade=(document.getElementById('autoridade').checked ? 1 : 0);
	var cliente=(document.getElementById('cliente').checked ? 1 : 0);
	var barra=(document.getElementById('barra').checked ? 1 : 0);
	
	var extra='&gerente='+gerente+'&supervisor='+supervisor+'&autoridade='+autoridade+'&cliente='+cliente+'&barra='+barra;
	url +=extra;
	
	
	
	
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
         
function imprimir_solicitacao_mudanca(){
	var elmId = document.getElementById('projeto_mudanca_id');
	if(!elmId.selectedIndex) return;
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=mudanca_imprimir&dialogo=1&projeto_mudanca_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	
	var gerente=(document.getElementById('gerente').checked ? 1 : 0);
	var supervisor=(document.getElementById('supervisor').checked ? 1 : 0);
	var autoridade=(document.getElementById('autoridade').checked ? 1 : 0);
	var cliente=(document.getElementById('cliente').checked ? 1 : 0);
	var barra=(document.getElementById('barra').checked ? 1 : 0);
	var extra='&gerente='+gerente+'&supervisor='+supervisor+'&autoridade='+autoridade+'&cliente='+cliente+'&barra='+barra;
	url +=extra;
	
	
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
	
function imprimir_termo_recebimento(){
	var elmId = document.getElementById('projeto_recebimento_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=recebimento_imprimir&dialogo=1&projeto_recebimento_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	
	var gerente=(document.getElementById('gerente').checked ? 1 : 0);
	var supervisor=(document.getElementById('supervisor').checked ? 1 : 0);
	var autoridade=(document.getElementById('autoridade').checked ? 1 : 0);
	var cliente=(document.getElementById('cliente').checked ? 1 : 0);
	var barra=(document.getElementById('barra').checked ? 1 : 0);
	var extra='&gerente='+gerente+'&supervisor='+supervisor+'&autoridade='+autoridade+'&cliente='+cliente+'&barra='+barra;
	url +=extra;
	
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
	
function imprimir_ata_reuniao(){
	var elmId = document.getElementById('ata_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=atas&a=ata_imprimir&dialogo=1&ata_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	
	var gerente=(document.getElementById('gerente').checked ? 1 : 0);
	var supervisor=(document.getElementById('supervisor').checked ? 1 : 0);
	var autoridade=(document.getElementById('autoridade').checked ? 1 : 0);
	var cliente=(document.getElementById('cliente').checked ? 1 : 0);
	var barra=(document.getElementById('barra').checked ? 1 : 0);
	var extra='&gerente='+gerente+'&supervisor='+supervisor+'&autoridade='+autoridade+'&cliente='+cliente+'&barra='+barra;
	url +=extra;
	
	
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}	
	     
function imprimir_licao_aprendida(){
	var elmId = document.getElementById('licao_id');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=licao_imprimir&dialogo=1&licao_id='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	
	var gerente=(document.getElementById('gerente').checked ? 1 : 0);
	var supervisor=(document.getElementById('supervisor').checked ? 1 : 0);
	var autoridade=(document.getElementById('autoridade').checked ? 1 : 0);
	var cliente=(document.getElementById('cliente').checked ? 1 : 0);
	var barra=(document.getElementById('barra').checked ? 1 : 0);
	var extra='&gerente='+gerente+'&supervisor='+supervisor+'&autoridade='+autoridade+'&cliente='+cliente+'&barra='+barra;
	url +=extra;
	
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}	
	     
function imprimir_resumo_evento(){
	var elmId = document.getElementById('dias');
	if(!elmId.selectedIndex) return;
	
	var pdf=document.getElementById('pdf').checked;
	var url = 'm=projetos&a=resumo_evento_imprimir_pro&dialogo=1&dias='+ elmId.value+'<?php echo '&baseline_id='.$baseline_id.'&projeto_id='.$projeto_id?>';
	if(pdf) url += '&sem_cabecalho=1&pdf=1&page_orientation=P';
	
	var gerente=(document.getElementById('gerente').checked ? 1 : 0);
	var supervisor=(document.getElementById('supervisor').checked ? 1 : 0);
	var autoridade=(document.getElementById('autoridade').checked ? 1 : 0);
	var cliente=(document.getElementById('cliente').checked ? 1 : 0);
	var barra=(document.getElementById('barra').checked ? 1 : 0);
	var extra='&gerente='+gerente+'&supervisor='+supervisor+'&autoridade='+autoridade+'&cliente='+cliente+'&barra='+barra;
	url +=extra;
	
	
	url_passar(0, url);
	elmId.selectedIndex = 0;
	}
</script>
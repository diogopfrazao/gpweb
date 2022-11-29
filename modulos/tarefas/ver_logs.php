<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $tarefa_id, $baseline_id, $m, $dialogo;

if (!$tarefa_id) $tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);

if (!$Aplic->checarModulo('log', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

$vetor_tarefa=array();

if ($Aplic->profissional) tarefas_subordinada($tarefa_id, $vetor_tarefa, $baseline_id);
else $vetor_tarefa[$tarefa_id]=(int)$tarefa_id;
$tem_subordinada=(count($vetor_tarefa)>1 ? true : false);
$vetor_tarefa=implode(',', $vetor_tarefa);



$problema = intval(getParam($_REQUEST, 'problem', null));
$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$RefRegistroTarefaImagem = getSisValor('RefRegistroTarefaImagem');
$ordenar=getParam($_REQUEST, 'ordenar', 'log_data');
$ordem=getParam($_REQUEST, 'ordem', '0');
$podeExcluir = $Aplic->checarModulo('log', 'excluir');
echo '<form name="frmExcluir2" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_log_tarefa" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="log_id" value="0" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '</form>';

$sql = new BDConsulta();



$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'log\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();





$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();


$sql->adTabela(($baseline_id ? 'baseline_': '').'projetos', 'projetos', ($baseline_id ? 'projetos.baseline_id='.(int)$baseline_id : ''));
$sql->esqUnir(($baseline_id ? 'baseline_': '').'tarefas','tarefas', 'projetos.projeto_id=tarefas.tarefa_projeto'.($baseline_id ? ' AND tarefas.baseline_id='.(int)$baseline_id : ''));
$sql->adCampo('projeto_moeda');
$sql->adOnde('tarefa_id ='.(int)$tarefa_id);	
$projeto_moeda=$sql->Resultado();
$sql->limpar();

$divisor_cotacao=($projeto_moeda!=1 ? cotacao($projeto_moeda, date('Y-m-d')) : 1);

$sql->adTabela(($baseline_id ? 'baseline_': '').'log', 'log', ($baseline_id ? 'log.baseline_id='.(int)$baseline_id : ''));
$sql->esqUnir(($baseline_id ? 'baseline_': '').'log', 'log_corrigiu', 'log_corrigiu.log_id=log.log_correcao'.($baseline_id ? ' AND log_corrigiu.baseline_id='.(int)$baseline_id : ''));
$sql->esqUnir(($baseline_id ? 'baseline_': '').'log', 'log_corrigido', 'log_corrigido.log_correcao=log.log_id'.($baseline_id ? ' AND log_corrigido.baseline_id='.(int)$baseline_id : ''));
$sql->esqUnir(($baseline_id ? 'baseline_': '').'tarefas','t', 'log.log_tarefa=t.tarefa_id'.($baseline_id ? ' AND t.baseline_id='.(int)$baseline_id : ''));
$sql->esqUnir('usuarios', '', 'log.log_criador = usuario_id');
$sql->esqUnir('contatos', 'ct', 'contato_id = usuario_contato');
$sql->adCampo('log.*, usuario_login, contato_id, tarefa_projeto');
$sql->adCampo('concatenar_tres(formatar_data(log_corrigiu.log_data, "%d/%m/%Y"), \' - \', log_corrigiu.log_nome) AS corrigido, concatenar_tres(formatar_data(log_corrigido.log_data, "%d/%m/%Y"), \' - \', log_corrigido.log_nome) AS corrigiu');
$sql->adOnde('log.log_tarefa IN ('.$vetor_tarefa.')'.($problema ? ' AND log.log_corrigir > 0' : ''));
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$logs = $sql->Lista();
$sql->limpar();




if ($dialogo) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$sql->adTabela(($baseline_id ? 'baseline_': '').'projetos', 'projetos', ($baseline_id ? 'projetos.baseline_id='.(int)$baseline_id : ''));
	$sql->esqUnir(($baseline_id ? 'baseline_': '').'tarefas','tarefas', 'projetos.projeto_id=tarefas.tarefa_projeto'.($baseline_id ? ' AND tarefas.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('projeto_moeda');
	$sql->adOnde('tarefa_id ='.(int)$tarefa_id);	
	$sql->adCampo('projeto_id, projeto_cia, projeto_nome, projeto_codigo, tarefa_nome');
	$dados = $sql->Linha();
	$sql->limpar();
	
	$dados['titulo_cabecalho']='REGISTRO DE OCORRÊNCIA D'.strtoupper($config['genero_tarefa']).' '.strtoupper($config['tarefa']).'<br>'.$dados['tarefa_nome'];
	
	
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_projeto_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
		
	echo '<table width=100%><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	}







echo '<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr><td>';
echo '<table cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

if (!$dialogo) echo '<th width=16></th>';
echo '<th width=50><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data', 'Data de inserção do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='log_data' ? imagem('icones/'.$seta[$ordem]) : '').'Data'.dicaF().'</a></th>';

if ($tem_subordinada) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=tarefa_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica(ucfirst($config['tarefa']), ucfirst($config['tarefa']).' relacionad'.$config['genero_tarefa'].' ao registro.').($ordenar=='tarefa_nome' ? imagem('icones/'.$seta[$ordem]) : '').ucfirst($config['tarefa']).dicaF().'</a></th>';


echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_referencia&ordem='.($ordem ? '0' : '1').'\');">'.dica('Referência', 'A forma como se chegou aos dados que estão registrandos.').($ordenar=='log_referencia' ? imagem('icones/'.$seta[$ordem]) : '').'Ref.'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome', 'Nome do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='log_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Título'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_reg_mudanca_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.dica('Percentagem', 'Caso tenha sido modificada a percentagem, será registrado nesta coluna para qual valor ficou.').($ordenar=='log_reg_mudanca_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').'%'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_reg_mudanca_status&ordem='.($ordem ? '0' : '1').'\');">'.dica('Status', 'Caso tenha sido modificada o status, será registrado nesta coluna para qual situação ficou.').($ordenar=='log_reg_mudanca_status' ? imagem('icones/'.$seta[$ordem]) : '').'Status'.dicaF().'</a></th>';


if ($Aplic->profissional && $exibir['tipo_oorrencia']) {
	$log_tipo_oorrencia = getSisValor('log_tipo_oorrencia');
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_tipo_oorrencia&ordem='.($ordem ? '0' : '1').'\');">'.dica('Tipo', 'Tipo de ocorrência do registro.').($ordenar=='log_tipo_oorrencia' ? imagem('icones/'.$seta[$ordem]) : '').'Tipo'.dicaF().'</a></th>';
	}

if ($Aplic->profissional && $exibir['bioma']) echo '<th>'.dica(ucfirst($config['tarefa_bioma']), 'Tipo de '.$config['tarefa_bioma'].' no registro.').ucfirst($config['tarefa_bioma']).dicaF().'</a></th>';
if ($Aplic->profissional && $exibir['comunidade']) echo '<th>'.dica(ucfirst($config['tarefa_comunidade']), 'Tipo de '.$config['tarefa_comunidade'].' no registro.').ucfirst($config['tarefa_comunidade']).dicaF().'</a></th>';


echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_reg_mudanca_realizado&ordem='.($ordem ? '0' : '1').'\');">'.dica('Quantidade', 'Caso tenha sido modificada a quantidade executada, será registrado nesta coluna para qual valor ficou.').($ordenar=='log_reg_mudanca_realizado' ? imagem('icones/'.$seta[$ordem]) : '').'Qnt'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_url_relacionada&ordem='.($ordem ? '0' : '1').'\');">'.dica('Endereço Eletrônico da Referência', 'Caso exista um link para página ou arquivo na rede que faça referência ao registro.').($ordenar=='log_url_relacionada' ? imagem('icones/'.$seta[$ordem]) : '').'URL'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_criador&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Responsável pela inserção do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Responsável'.dicaF().($ordenar=='log_criador' ? imagem('icones/'.$seta[$ordem]) : '').'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_horas&ordem='.($ordem ? '0' : '1').'\');">'.dica('Horas', 'Horas trabalhadas n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='log_horas' ? imagem('icones/'.$seta[$ordem]) : '').'Horas'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab=0&tarefa_id='.$tarefa_id.'&ordenar=log_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Comentários', 'Comentários sobre o registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='log_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Comentários'.dicaF().'</a></th>';
echo '<th>'.dica('Custos', 'Custos planejados no registro.').'Custos'.dicaF().'</th>';
echo '<th width="100">'.dica('Gastos', 'Gastos efetuados no registro.').'Gastos'.dicaF().'</th>';
if ($Aplic->profissional && !$dialogo) echo '<th width="16">'.dica('Entregadas', 'Entregadas realizadas registradas.').'E'.dicaF().'</th>';
if ($podeExcluir && !$dialogo) echo '<th>&nbsp;</th>';
echo '</tr>';

$hrs = 0;
$custo=array();
$podeEditar = $Aplic->checarModulo('log', 'editar');
$qnt=0;


$status = getSisValor('StatusTarefa');

foreach ($logs as $linha) {

		
	$permiteEditar=permiteEditar($linha['log_acesso'], $linha['tarefa_projeto'], $linha['log_tarefa']);
	$qnt++;
	
	if ($linha['log_aprovado']==1 || $linha['log_aprovado']==-1) $permiteEditar=0;
	
	if ($linha['log_correcao']) $estilo='background-color:#a1fb99;color:#000000';
	else if ($linha['corrigiu'])	$estilo='background-color:#e9ea87;color:#000000';
	else if ($linha['log_corrigir']) $estilo='background-color:#cc6666;color:#ffffff';
	else $estilo='';
		
	
	echo '<tr bgcolor="white" valign="middle">';
	if ($podeEditar && $permiteEditar && !$dialogo) {
		echo '<td>';
		if (isset($tab) && $tab == -1) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.($Aplic->profissional ? 'ver_log_atualizar' : 'ver').'&tarefa_id='.$tarefa_id.'&tab='.$Aplic->getEstado('TarefaLogVerTab');
		else 	echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.($Aplic->profissional ? 'ver_log_atualizar' : 'ver').'&tarefa_id='.$tarefa_id.'&tab=1&log_id='.$linha['log_id'].'\');">'.imagem('icones/editar.gif','Editar Registro','Clique neste ícone '.imagem('icones/editar.gif').' para editar o registro.').'</a>';
		echo '</td>';	
		}
	else if(!$dialogo) echo '<td>&nbsp;</td>';	
	
	echo '<td style="white-space: nowrap" valign="middle">'.retorna_data($linha['log_data'], false).'</td>';
	
	if ($tem_subordinada) echo '<td>'.($linha['log_tarefa'] !=$tarefa_id ? link_tarefa($linha['log_tarefa']) : '&nbsp;').'</td>';
	
	
	$imagem_referencia = '-';
	if ($linha['log_referencia'] > 0) {
		if (isset($RefRegistroTarefaImagem[$linha['log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroTarefaImagem[$linha['log_referencia']], imagem('icones/'.$RefRegistroTarefaImagem[$linha['log_referencia']]).' '.$RefRegistroTarefa[$linha['log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
		elseif (isset($RefRegistroTarefa[$linha['log_referencia']])) $imagem_referencia = $RefRegistroTarefa[$linha['log_referencia']];
		}
	echo '<td align="center" valign="middle">'.$imagem_referencia.'</td>';
	
	if ($Aplic->profissional){
		$sql->adTabela('log_arquivo');
		$sql->adCampo('count(log_arquivo_id)');
		$sql->adOnde('log_arquivo_log='.(int)$linha['log_id']);
		$arquivos=$sql->Resultado();
		$sql->limpar();
		$plural=($arquivos > 1 ? 's' : '');
		$anexo=($arquivos ? '<a href="javascript:popArquivos('.$linha['log_id'].');" >'.imagem('icones/clip.png','Anexo'.$plural,'Clique neste ícone '.imagem('icones/clip.png').' para visualizar o'.$plural.($plural=='s' ? ' '.$arquivos : '').' anexo'.$plural.'.').'</a>' : '');
		}
	else $anexo='';
	
	echo '<td valign="middle" style="'.$estilo.'">'.($linha['log_correcao'] ? dica('Registro em que Solucionou Problema', $linha['corrigido']) : '').($linha['corrigiu'] ? dica('Registro Responsável pela Solução', $linha['corrigiu']) : '').$linha['log_nome'].($linha['corrigiu'] || $linha['corrigido'] ? dicaF() : '').$anexo.'</td>';
	echo '<td valign="middle" align="center">'.($linha['log_reg_mudanca_percentagem'] ? (int)$linha['log_reg_mudanca_percentagem'] : '&nbsp;').'</td>';
	echo '<td valign="middle" align="center">'.($linha['log_reg_mudanca_status'] && isset($status[$linha['log_reg_mudanca_status']]) ? $status[$linha['log_reg_mudanca_status']] : '&nbsp;').'</td>';
	
	
	if ($Aplic->profissional && $exibir['tipo_oorrencia']) echo '<td valign="middle" align="center">'.($linha['log_tipo_oorrencia'] && isset($log_tipo_oorrencia[$linha['log_tipo_oorrencia']]) ? $log_tipo_oorrencia[$linha['log_tipo_oorrencia']] : '&nbsp;').'</td>';
	
	
	if ($Aplic->profissional && $exibir['bioma']) {
		$sql->adTabela('log_bioma');
		$sql->esqUnir('sisvalores', 'sisvalores', 'sisvalor_valor_id=log_bioma_bioma');
		$sql->adOnde('log_bioma_log = '.(int)$linha['log_id']);
		$sql->adOnde('sisvalor_titulo = \'tarefa_bioma\'');
		$sql->adCampo('DISTINCT sisvalor_valor');
		$sql->adOrdem('log_bioma_ordem');
		$sql->adGrupo('log_bioma_bioma');
		$biomas=$sql->carregarColuna();
		$sql->limpar();
		echo '<td valign="middle" align="center">'.implode('<br>', $biomas).'</td>';
		}
	
	if ($Aplic->profissional && $exibir['comunidade']) {
		$sql->adTabela('log_comunidade');
		$sql->esqUnir('sisvalores', 'sisvalores', 'sisvalor_valor_id=log_comunidade_comunidade');
		$sql->adOnde('log_comunidade_log = '.(int)$linha['log_id']);
		$sql->adOnde('sisvalor_titulo = \'tarefa_comunidade\'');
		$sql->adCampo('DISTINCT sisvalor_valor');
		$sql->adOrdem('log_comunidade_ordem');
		$sql->adGrupo('log_comunidade_comunidade');
		$comunidades=$sql->carregarColuna();
		$sql->limpar();
		echo '<td valign="middle" align="center">'.implode('<br>', $comunidades).'</td>';
		}
	
	
	
	
	echo '<td valign="middle" align="center">'.($linha['log_reg_mudanca_realizado'] ? number_format($linha['log_reg_mudanca_realizado'], 1, ',', '.') : '&nbsp;').'</td>';
	echo !empty($linha['log_url_relacionada']) ? '<td align="center" valign="middle">'.dica('Link', 'Clique neste ícone '.imagem('icones/link.png').' para  acessar:<ul><li>'.$linha['log_url_relacionada'].'</ul>').'<a href="'.$linha['log_url_relacionada'].'">'.imagem('icones/link.png').'</a>'.dicaF().'</td>' : '<td>&nbsp;</td>';
	echo '<td valign="middle">'.link_usuario($linha['log_criador'],'','','esquerda').'</td>';
	echo '<td align="right" valign="middle">';
	$minutos = (int)(($linha['log_horas'] - ((int)$linha['log_horas'])) * 60);
	$minutos = ((strlen($minutos) == 1) ? ('0'.$minutos) : $minutos);
	echo($linha['log_horas']!=0 ? (int)$linha['log_horas'].':'.$minutos : '&nbsp;').'</td>';
	echo'<td valign="middle">'.'<a name="tarefalog'.$linha['log_id'].'"></a>'.str_replace("\n", '<br />', ($linha['log_descricao'])).'</td>';
	
	
	
	$sql->adTabela('custo');
	$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS valor');
	$sql->adOnde('custo_log ='.(int)$linha['log_id']);	
	$sql->adOnde('custo_gasto!=1');
	$custo=$sql->Resultado();
	$sql->limpar();

	$sql->adTabela('custo');
	$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS valor');
	$sql->adOnde('custo_log ='.(int)$linha['log_id']);	
	$sql->adOnde('custo_gasto=1');
	$gasto=$sql->Resultado();
	$sql->limpar();
	
	
	echo '<td width="100" align="right" valign="middle" style="white-space: nowrap">'.($custo ? $moedas[$projeto_moeda].' '.number_format($custo/$divisor_cotacao, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:planilha_custo('.$linha['log_id'].', 0)">'.dica('Planilha de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar a planilha de custos estimados.').imagem('icones/planilha_estimado.gif').dicaF().'</a>' : '&nbsp;').'</td>';
	echo '<td width="100" align="right" valign="middle" style="white-space: nowrap">'.($gasto ? $moedas[$projeto_moeda].' '.number_format($gasto/$divisor_cotacao, 2, ',', '.').'<a href="javascript: void(0);" onclick="javascript:planilha_custo('.$linha['log_id'].', 1)">'.dica('Planilha de Gastos Efetuados', 'Clique neste ícone '.imagem('icones/planilha_gasto.gif').' para visualizar a planilha de gastos efetuados.').imagem('icones/planilha_gasto.gif').dicaF().'</a>' : '&nbsp;').'</td>';

	if ($Aplic->profissional){
		$sql->adTabela('log_entrega');
		$sql->esqUnir('tarefa_entrega', 'tarefa_entrega', 'tarefa_entrega_id=log_entrega_entrega');
		$sql->adOnde('log_entrega_log = '.(int)$linha['log_id']);
		$sql->adCampo('log_entrega.*, tarefa_entrega_nome, formatar_data(log_entrega_data, \'%d/%m/%Y\') AS data');
		$sql->adOrdem('log_entrega_data ASC');
		$entregas=$sql->Lista();
		$sql->limpar();
		
		$saida='';
		if (count($entregas)) {
			if (!$dialogo) echo '<td align=center><a href="javascript: void(0);" onclick="if (document.getElementById(\'entrega_'.$linha['log_id'].'\').style.display) document.getElementById(\'entrega_'.$linha['log_id'].'\').style.display=\'\'; else document.getElementById(\'entrega_'.$linha['log_id'].'\').style.display=\'none\';">X</a></td>';
			
			$saida.= '<tr id="entrega_'.$linha['log_id'].'" '.(!$dialogo ? 'style="display:none"' : '').'><td colspan=20><table cellpadding=0 cellspacing=0 class="tbl1" align=left width=100%><tr>
			<th>'.dica('Entrega', 'O nome da entrega.').'Entrega'.dicaF().'</th>
			<th>'.dica('Observação', 'A observação da entrega.').'Observação'.dicaF().'</th>
			<th>'.dica('Data', 'A data da inserção do valor realizado.').'Data'.dicaF().'</th>
			<th>'.dica('Realizado', 'A quantidade de produto/serviço que já foi entregue.').'Realizado'.dicaF().'</th>
			</tr>';
			foreach ($entregas as $entrega) {
				$saida.= '<tr align="center">';
				$saida.= '<td align="left" style="white-space: nowrap">'.$entrega['tarefa_entrega_nome'].'</td>';
				$saida.= '<td align="left">'.$entrega['log_entrega_observacao'].'</td>';
				$saida.= '<td align="center" width=70>'.$entrega['data'].'</td>';
				$saida.= '<td align="right" width=70>'.(int)$entrega['log_entrega_realizado'].'</td>';
				$saida.= '</tr>';
				}
			$saida.= '</table></td></tr>';
			
			}
		else echo '<td></td>';
		}
	
	if ($podeExcluir && $permiteEditar && !$dialogo) echo '<td width="16" valign="middle"><a href="javascript:excluir2('.$linha['log_id'].');" >'.imagem('icones/remover.png','Excluir Registro','Clique neste ícone '.imagem('icones/remover.png').' para excluir o registro.').'</a></td>';
	else if ($podeExcluir && !$dialogo) echo'<td>&nbsp;</td>';
	
	echo '</tr>';
	if ($Aplic->profissional) echo $saida;
	}
if (!$qnt) {
	echo '<tr><td bgcolor="white" colspan=20><p>Nenhum registro encontrado.</p></td></tr></table></td></tr></table>';	
	}
if (!$dialogo && $qnt){	
	echo '<table width="100%" class="std2"><tr><td><table><tr><td>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Legenda:</td>';
	echo '<td>&nbsp; &nbsp;</td><td bgcolor="#ffffff" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro Normal', 'Todos os registros que não forem marcados como tendo problema serão considerados normais.').'Normal'.dicaF().'&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td bgcolor="#cc6666" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro de Problema', 'Todos os registros que forem marcados como tendo problema aparecerão com o sumário na cor vermelha.').'Problema&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';
	echo '<td bgcolor="#e9ea87" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Problema Solucionado', 'Todos os registros marcados como problema em que outro registro tenha sido marcado como tendo solicionado estes problemas.').'Problema Solucionado&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';
	echo '<td bgcolor="#a1fb99" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Solucionou Problema', 'Todos os registros que forem marcados como tendo solucionado problema de outro registro.').'Solucionou Problema&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';
	echo '<td align="right"><a href="javascript: void(0);" onclick ="imprimir_registros('.$tarefa_id.');">'.imagem('imprimir_p.png', 'Imprimir os Registros d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de registros d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</a></td>';
	echo '</tr></table></td></tr></table></td></tr>';
	}

echo '</table>';


function tarefas_subordinada($tarefa_pai=0, &$vetor, $baseline_id=null){
	global $arvore, $Aplic;
	$vetor[$tarefa_pai]=(int)$tarefa_pai;
	$sql = new BDConsulta;
	$sql->adTabela(($baseline_id ? 'baseline_': '').'tarefas', 'tarefas', ($baseline_id ? 'tarefas.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_superior ='.(int)$tarefa_pai.' AND tarefa_id!='.(int)$tarefa_pai);
	$lista=$sql->carregarColuna();
	$sql->limpar();
	foreach($lista as $tarefa_id){
		$vetor[$tarefa_id]=$tarefa_id;
		tarefas_subordinada($tarefa_id, $vetor, $baseline_id);
		}
	}

if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';


?>
<script type="text/javascript">

function planilha_custo(log_id, gasto){
	parent.gpwebApp.popUp('Planilha de '+(gasto ? 'Gasto' : 'Custo'), 1000, 500, 'm=praticas&a=log_custo&log_id='+log_id+'&gasto='+gasto, null, window);
	}

function popArquivos(log_id){
	parent.gpwebApp.popUp("Arquivos", 400, 400, "m=tarefas&a=ver_log_anexos&dialogo=1&log_id="+log_id, null, window);
	}

function excluir2(id) {
	if (confirm( 'Tem certeza que deseja excluir o registro d<?php echo $config["genero_tarefa"]." ".$config["tarefa"]?>?' )) {
		document.frmExcluir2.log_id.value = id;
		document.frmExcluir2.submit();
		}
	}	
	
function imprimir_registros(tarefa_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Registros', 1024, 500, 'm=tarefas&a=ver_logs&dialogo=1&tarefa_id='+tarefa_id, null, window);
	else window.open('./index.php?m=tarefas&a=ver_logs&dialogo=1&tarefa_id='+tarefa_id, 'Registros','height=500,width=1020,resizable,scrollbars=yes');
	}	
	
	
</script>
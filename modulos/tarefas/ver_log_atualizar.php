<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$Aplic->carregarCKEditorJS();

$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);

$log_id = intval(getParam($_REQUEST, 'log_id', 0));


$sql = new BDConsulta;

if ($log_id && !$tarefa_id) {
	
	$sql->adTabela('log');
	$sql->adCampo('log_tarefa');
	$sql->adOnde('log_id='.(int)$log_id);
	$tarefa_id=$sql->Resultado();
	$sql->limpar();
	}





$sql->adTabela('tarefas');
$sql->adCampo('tarefa_cia, tarefa_projeto, tarefa_nome, tarefa_dono, tarefa_dinamica, tarefa_duracao, tarefa_inicio, tarefa_fim, tarefa_percentagem, tarefa_status, tarefa_realizado');
$sql->adOnde('tarefa_id='.(int)$tarefa_id);
$tarefa=$sql->Linha();
$sql->limpar();


$log = new CTarefaLog();
if ($log_id) {
	if (!$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
	$log->load($log_id);
	$tarefa_id=$log->log_tarefa;
	}
else {
	$log->log_tarefa = $tarefa_id;
	$log->log_nome = $tarefa['tarefa_nome'];
	}





$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
$inicio = 0;
$fim = 24;
$inc = 1;
$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	$horas[$chave_atual] = $atual;
	}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;
$tipoDuracao = getSisValor('TipoDuracaoTarefa');
$Aplic->carregarCalendarioJS();




$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


$sql->adTabela('projetos');
$sql->adCampo('projeto_acesso, projeto_trava_data');
$sql->adOnde('projeto_id='.(int)$tarefa['tarefa_projeto']);
$projeto=$sql->Linha();
$sql->limpar();

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();

$permite_editar_data=true;
if ($projeto['projeto_trava_data']){
	if (!permiteEditar($projeto['projeto_acesso'], $tarefa['tarefa_projeto'])) $permite_editar_data=false;
	}






$sql->adTabela('pi');
$sql->adOnde('pi_projeto = '.(int)$tarefa['tarefa_projeto']);
$sql->adCampo('pi_pi');
$sql->adOrdem('pi_ordem');
$pi=array(''=>'')+$sql->listaVetorChave('pi_pi','pi_pi');
$sql->limpar();


$sql->adTabela('ptres');
$sql->adOnde('ptres_projeto = '.(int)$tarefa['tarefa_projeto']);
$sql->adCampo('ptres_ptres');
$sql->adOrdem('ptres_ordem');
$ptres=array(''=>'')+$sql->listaVetorChave('ptres_ptres','ptres_ptres');
$sql->limpar();



$logTipoProblema = getSisValor('logTipoProblema');
$RefRegistroTarefa = getSisValor('RefRegistroTarefa');

$log_data = new CData($log->log_data);

$botoesTitulo = new CBlocoTitulo('Registro de Ocorr�ncia d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'tarefa.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();
echo estiloTopoCaixa();

if ($log_id && $log->log_reg_mudanca_inicio && $log->log_reg_mudanca_fim) $duracao = (float)(($log->log_reg_mudanca_duracao ? (float)$log->log_reg_mudanca_duracao : 0)/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8));
else $duracao = (float)((isset($tarefa['tarefa_duracao']) ? (float)$tarefa['tarefa_duracao'] : 0)/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8));

$data_fim = new CData(($log_id && $log->log_reg_mudanca_fim ? $log->log_reg_mudanca_fim : $tarefa['tarefa_fim']));
$data_inicio = new CData(($log_id  && $log->log_reg_mudanca_inicio ? $log->log_reg_mudanca_inicio : $tarefa['tarefa_inicio']));
$data_texto = new CData(($log_id && $log->log_reg_mudanca_inicio ? $log->log_reg_mudanca_inicio : $tarefa['tarefa_inicio']));


echo '<form name="env" method="post" enctype="multipart/form-data" onsubmit=\'atualizarEmailContatos();\'>';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_log_tarefa" />';
echo '<input type="hidden" name="dialogo" value="'.$dialogo.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="log_id" id="log_id" value="'.$log->log_id.'" />';
echo '<input type="hidden" name="log_tarefa" value="'.$log->log_tarefa.'" />';
echo '<input type="hidden" name="log_criador" value="'.($log->log_criador == 0 ? $Aplic->usuario_id : $log->log_criador).'" />';
$uuid=($log_id ? null : uuid());
echo '<input type="hidden" name="uuid" id="uuid" value="'.$uuid.'" />';
echo '<input name="tarefa_percentagem_antiga" type="hidden" value="'.$tarefa['tarefa_percentagem'].'" />';
echo '<input name="tarefa_fim_antiga" type="hidden" value="'.$tarefa['tarefa_fim'].'" />';
echo '<input name="tarefa_inicio_antiga" type="hidden" value="'.$tarefa['tarefa_inicio'].'" />';
echo '<input name="tarefa_duracao_antiga" type="hidden" value="'.$duracao.'" />';
echo '<input name="tarefa_realizado_antigo" type="hidden" value="'.$tarefa['tarefa_realizado'].'" />';
echo '<input name="tarefa_status_antigo" type="hidden" value="'.$tarefa['tarefa_status'].'" />';


echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td colspan=20 align=center><b>'.$tarefa['tarefa_nome'].'</b></td></tr>';
echo '<tr><td colspan=20 align=center>&nbsp;</td></tr>';
$qnt=0;
if (!$log->log_id){
	$sql->adTabela('log');
	$sql->adCampo('count(log_id)');
	$sql->adOnde('log_tarefa = '.(int)$log->log_tarefa);
	$qnt=$sql->Resultado()+1;
	$sql->limpar();
	}

$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id=tarefa_projeto');
$sql->adOnde('tarefa_id = '.(int)$log->log_tarefa);
$sql->adCampo('projeto_moeda, tarefa_inicio');
$informacao=$sql->linha();
$sql->limpar();


echo '<tr><td align="right" width=155>'.dica('Nome', 'Escreva um texto curto que exprima o motivo deste registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Nome:'.dicaF().'</td><td valign="middle"><input type="text" class="texto" name="log_nome" value="'.($log->log_id ? $log->log_nome : $log->log_nome.' - '.($qnt< 10 ? '0' : '').$qnt).'" maxlength="255" style="width:395px;" /></td></tr>';
echo '<tr><td align="right" valign="middle">'.dica('Descri��o', 'Escreva uma descri��o pormenorizada sobre este registro.').'Descri��o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0 style="width:100%;"><tr><td><textarea data-gpweb-cmp="ckeditor" name="log_descricao" class="textarea" cols="50" rows="6">'.$log->log_descricao.'</textarea></td></tr></table></td></tr>';
echo '<tr><td align="right">'.dica('Data', 'Escolha qual a data deste registro.').'Data:'.dicaF().'</td><td style="white-space: nowrap"><input type="hidden" name="log_data" id="log_data" value="'.$log_data->format('%Y-%m-%d').'" /><input type="text" name="log_data_nome" id="log_data_nome" onchange="setData(\'env\', \'log_data_nome\', \'log_data\');" value="'.$log_data->format('%d/%m/%Y').'" class="texto" style="width:70px;" />'.dica('Data do Registro', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data deste registro.').'<a href="javascript: void(0);" ><img id="f_btn3" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right">'.dica('Problema', 'Caso esta caixa esteja selecionada, este registro ser� marcado como de problema.<br><br>Ele se diferenciar� dos outros registros por ter um fundo vermelho no sum�rio para chamar a aten��o.').'Problema:'.dicaF().'</td><td style="white-space: nowrap"><input type="checkbox" value="1" name="log_corrigir" id="log_corrigir" '.($log->log_corrigir ? 'checked="checked"' : '').'
 onclick="if (env.log_corrigir.checked) {env.log_corrigir.checked=true; document.getElementById(\'tipo_problema\').style.display=\'\';} else {document.getElementById(\'tipo_problema\').style.display=\'none\';}" /></td></tr>';


echo '<tr '.($log->log_corrigir ? ' style="display:"' : ' style="display:none"').' id="tipo_problema"><td align="right" valign="middle">'.dica('Tipo de Problema', 'Escolha de que forma chegou aos dados que aqui est�o registrados.').'Tipo de problema:'.dicaF().'</td><td valign="middle">'.selecionaVetor($logTipoProblema, 'log_tipo_problema', 'size="1" class="texto"', $log->log_tipo_problema).'</td></tr>';


$sql->adTabela('log');
$sql->adCampo('log_id, concatenar_tres(formatar_data(log_data, "%d/%m/%Y"), \' - \', log_nome) AS nome');
$sql->adOnde('log_corrigir=1');
$sql->adOnde('log_tarefa = '.(int)$log->log_tarefa);
$sql->adOrdem('log_data');
$linhas=array(null=>'')+$sql->listaVetorChave('log_id','nome');
$sql->limpar();
if (count($linhas) > 1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Solu��o de problema', 'Caso este registro de ocorr�ncia seja a corre��o de problema apontado em outro registro, informe qual registro est� sendo solucionado.').'Solu��o de problema:'.dicaF().'</td><td>'.selecionaVetor($linhas, 'log_correcao', 'class="texto"', $log->log_correcao).'</td></tr>';
else echo '<input type="hidden" name="log_correcao" id="log_correcao" value="">';



echo '<tr style="display:'.($tarefa['tarefa_dinamica'] ? 'none' : '').'"><td align="right">'.dica('Progresso', 'O progresso d'.$config['genero_tarefa'].' '.$config['tarefa'].' pode estar em algum valor entre 0%(n�o iniciou) e 100%(terminada).<br><br>H� duas formas de se registrar o progresso d'.$config['genero_tarefa'].' '.$config['tarefa'].': <ul><li>Editando diretamente '.$config['genero_tarefa'].' '.$config['tarefa'].'.<li>Registrando neste campo.<br>Sempre o progresso do <b>registro de tarefa</b> mais recente � que ser� considerado pelo Sistema.</ul>').'Progresso:'.dicaF().'</td><td>'.selecionaVetor($percentual, 'tarefa_percentagem', 'size="1" class="texto"', ($log_id && ($log->log_reg_mudanca_percentagem!='' && !is_null($log->log_reg_mudanca_percentagem)) ? $log->log_reg_mudanca_percentagem : (int)$tarefa['tarefa_percentagem'])).'%</td></tr>';
$status = getSisValor('StatusTarefa');

echo '<tr><td align="right">'.dica('Status', ucfirst($config['genero_tarefa']).' '.$config['tarefa'].' deve ter um status que reflita sua situa��o atual.').'Status:'.dicaF().'</td><td>'.selecionaVetor($status, 'tarefa_status', 'size="1" class="texto"', ($log_id  && $log->log_reg_mudanca_status ? $log->log_reg_mudanca_status : $tarefa['tarefa_status'])).'</td></tr>';
echo '<tr style="display:'.($tarefa['tarefa_dinamica'] || !$permite_editar_data ? 'none' : '').'"><td align="right" style="white-space: nowrap">'.dica('Data de In�cio', 'Digite ou escolha no calend�rio a data prov�vel de in�cio d'.$config['genero_tarefa'].' '.$config['tarefa']).'Data de In�cio:'.dicaF().'</td><td style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="oculto_data_inicio" name="oculto_data_inicio"  value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'env\', \'data_inicio\', \'oculto_data_inicio\'); data_ajax();" class="texto" style="width:70px;" id="data_inicio" name="data_inicio" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" /><a href="javascript: void(0);">'.dica('Data de In�cio', 'Clique neste �cone '.imagem('icones/calendario.gif').' para abrir um calend�rio onde poder� selecionar a data prov�vel de in�cio d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 />'.dicaF().'</a>'.dica('Hora do In�cio', 'Selecione na caixa de sele��o a hora do �nicio d'.$config['genero_tarefa'].' '.$config['tarefa']). selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="data_ajax();" class="texto" ', ($data_inicio ? $data_inicio->getHour() : $inicio)).' : '.dica('Minutos do In�cio', 'Selecione na caixa de sele��o os minutos do �nicio d'.$config['genero_tarefa'].' '.$config['tarefa']).selecionaVetor($minutos, 'inicio_minutos', 'size="1" class="texto" onchange="data_ajax();" ', ($data_inicio ? $data_inicio->getMinute() : '00')).'</td></tr></table></td></tr>';
echo '<tr style="display:'.($tarefa['tarefa_dinamica'] || !$permite_editar_data ? 'none' : '').'"><td align="right" style="white-space: nowrap">'.dica('Data de T�rmino', 'Digite ou escolha no calend�rio a data prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'.</p>Caso n�o saiba a data prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').'Data de T�rmino:'.dicaF().'</td><td style="white-space: nowrap"><input type="hidden" id="oculto_data_fim" name="oculto_data_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" onchange="setData(\'env\', \'data_fim\', \'oculto_data_fim\'); horas_ajax();" class="texto" style="width:70px;" id="data_fim" name="data_fim" value="'.($data_fim ? $data_fim->format('%d/%m/%Y') : '').'" /><a href="javascript: void(0);">'.dica('Data de T�rmino', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 />'.dicaF().'</a>'.dica('Hora do T�rmino', 'Selecione na caixa de sele��o a hora do t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'.</p>Caso n�o saiba a hora prov�vel de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($horas, 'hora_fim', 'size="1" onchange="horas_ajax();" class="texto" ', $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do T�rmino', 'Selecione na caixa de sele��o os minutos do t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].'. </p>Caso n�o saiba os minutos prov�veis de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($minutos, 'minuto_fim', 'size="1" class="texto" onchange="horas_ajax();" ', $data_fim ? $data_fim->getMinute() : '00').'</td></tr>';
echo '<tr style="display:'.($tarefa['tarefa_dinamica'] || !$permite_editar_data ? 'none' : '').'"><td align="right" style="white-space: nowrap">'.dica('Dura��o', 'Selecionando o n�mero de horas, ou dias, far� o sistema calcular a data prov�vel de t�rmino.</p>Caso n�o saiba o n�mero de horas/dias que ser�o trabalhas n'.$config['genero_tarefa'].' '.$config['tarefa'].', deixe em branco este campo e clique no bot�o <b>Dura��o</b>').'Dura��o esperada:'.dicaF().'</td><td style="white-space: nowrap"><input type="text" onchange="data_ajax();" class="texto" name="tarefa_duracao" id="tarefa_duracao" maxlength="8" size="2" value="'.float_brasileiro($duracao).'" />&nbsp;dias</td></tr>';
echo '<tr style="display:'.($tarefa['tarefa_dinamica'] || !$permite_editar_data ? 'none' : '').'"><td align="right">'.dica('Horas Trabalhadas', 'Horas trabalhadas n'.$config['genero_tarefa'].' '.$config['tarefa'].'.<br><br>Ex: Para inserir 1h30min digite 1.5').'Horas trabalhadas:'.dicaF().'</td><td style="white-space: nowrap"><input type="text" style="text-align:right;" class="texto" onkeypress="return somenteFloat(event)" name="log_horas" value="'.($log->log_horas!=0 ? number_format($log->log_horas, 2, ',', '.'): '').'" maxlength="8" size="4" /></td></tr>';
$qnt_realizada=($log_id ? $log->log_reg_mudanca_realizado : $tarefa['tarefa_realizado']);
echo '<tr style="display:'.($tarefa['tarefa_dinamica'] ? 'none' : '').'"><td align="right">'.dica('Quantidade Realizada', 'Quantidade realizada n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade realizada:'.dicaF().'</td><td style="white-space: nowrap"><input type="text" style="text-align:right;" class="texto" name="tarefa_realizado" onkeypress="return somenteFloat(event)" value="'.($qnt_realizada!=0 ? number_format($qnt_realizada, 2, ',', '.'): '').'" maxlength="8" size="4" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('N�vel de Acesso', 'O registro de '.$config['tarefa'].' pode ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].' e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante I</b> - Somente o respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].' e os designados podem ver e editar</li><li><b>Participantes II</b> - Somente o respons�vel e os designados podem ver e apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].' e os designados podem ver, e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td colspan="2">'.selecionaVetor($niveis_acesso, 'log_acesso', 'class="texto"', ($log_id ? $log->log_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';
echo '<tr><td align="right" valign="middle">'.dica('Refer�ncia', 'Escolha de que forma chegou aos dados que aqui est�o registrados.').'Refer�ncia:'.dicaF().'</td><td valign="middle">'.selecionaVetor($RefRegistroTarefa, 'log_referencia', 'size="1" class="texto"', $log->log_referencia).'</td></tr>';
echo '<tr><td align="right">'.dica('Endere�o Eletr�nico desta Refer�ncia', 'Escreva, caso exista, um link para p�gina ou arquivo na rede que faz refer�ncia a este registro tal como visualiza na tela no Navegador Web.').'URL:'.dicaF().'</td><td><input type="text" class="texto" name="log_url_relacionada" value="'.($log->log_url_relacionada).'" style="width:395px;" maxlength="255" /></td></tr>';









//entregas
if ($Aplic->profissional) require_once BASE_DIR.'/modulos/tarefas/ver_log_atualizar_pro.php';







echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_custos\').style.display) document.getElementById(\'apresentar_custos\').style.display=\'\'; else document.getElementById(\'apresentar_custos\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Custo/Gasto</b></a></td></tr>';
echo '<tr id="apresentar_custos" style="display:none"><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';

echo '<tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td><table cellspacing=0 cellpadding=0>';

echo '<input type="hidden" name="custo_id" id="custo_id" value="" />';
echo '<input type="hidden" name="apoio1" id="apoio1" value="" />';
echo '<input type="hidden" name="antigo_gasto" id="antigo_gasto" value="" />';

echo '<tr><td align="right" style="white-space: nowrap" width=155>'.dica('Nome', 'Escreva o nome deste item.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="custo_nome" id="custo_nome" value="" maxlength="255" style="width:391px;" /></td></tr>';

$custo_gasto=array(0=>'Custo', 1=>'Gasto');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Tipo', 'O item � um custo planjado ou um gasto efetuado.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($custo_gasto, 'custo_gasto', 'class=texto size=1 style="width:395px;"').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Unidade de Medida', 'Escolha a unidade de medida deste item.').'Unidade de medida:'.dicaF().'</td><td>'.selecionaVetor($unidade, 'custo_tipo', 'class=texto size=1 style="width:395px;"').'</td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quantidade', 'Insira a quantidade deste item.').'Quantidade:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return somenteFloat(event)" onchange="javascript:valor();" onclick="javascript:valor();" name="custo_quantidade" id="custo_quantidade" value="" maxlength="255" style="width:391px;" /></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Valor Unit�rio', 'Insira o valor deste item.').'Valor unit�rio:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return somenteFloat(event)" onchange="javascript:valor();" onclick="javascript:valor();" name="custo_custo" id="custo_custo" value="" style="width:391px;" /></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Moeda', 'Escolha a moeda utilizada neste item.').'Moeda:'.dicaF().'</td><td>'.selecionaVetor($moedas, 'custo_moeda', 'class=texto size=1 style="width:395px;" onchange="mudar_moeda(this.value)"', $informacao['projeto_moeda']).'</td></tr>';
echo '<tr id="combo_data_moeda"><td align="right">'.dica('Data da Cota��o','Data da cota��o da moeda estrangeira.').'Data da cota��o:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="custo_data_moeda" id="custo_data_moeda" value="'.($data_texto ? $data_texto->format('%Y%m%d') : '').'" /><input type="text" name="data5_texto"  id="data5_texto" style="width:70px;" onchange="setData(\'env\', \'data5_texto\', \'custo_data_moeda\');" value="'.($data_texto ? $data_texto->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data da Cota��o', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data da cota��o da moeda estrangeira.').'<a href="javascript: void(0);" ><img id="f_btn5" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio2" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';

if ($config['bdi']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('BDI', 'Benef�cios e Despesas Indiretas, � o elemento or�ament�rio destinado a cobrir todas as despesas que, num empreendimento, segundo crit�rios claramente definidos, classificam-se como indiretas (por simplicidade, as que n�o expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material � m�o-de-obra, equipamento-obra, instrumento-obra etc.), e, tamb�m, necessariamente, atender o lucro.').'BDI (%):'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return somenteFloat(event)" onchange="javascript:valor();" onclick="javascript:valor();" name="custo_bdi" id="custo_bdi" value="" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="custo_bdi" id="custo_bdi" value="0" />';

$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Categoria Econ�mica', 'Escolha a categoria econ�mica deste item.').'Categoria econ�mica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'custo_categoria_economica', 'class=texto size=1 style="width:395px;" onchange="env.custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
$GrupoND=array(''=>'')+getSisValor('GrupoND');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Grupo de Despesa', 'Escolha o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'custo_grupo_despesa', 'class=texto size=1 style="width:395px;"  onchange="env.custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Modalidade de Aplica��o', 'Escolha a modalidade de aplica��o deste item.').'Modalidade de aplica��o:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'custo_modalidade_aplicacao', 'class=texto size=1 style="width:395px;"  onchange="env.custo_nd.value=\'\'; mudar_nd();"').'</td></tr>';
$nd=vetor_nd('', null, null, 3 ,'', '');
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste item.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'custo_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"').'</div></td></tr>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica('Descri��o do Item', 'Insira a descri��o deste item.').'Descri��o do item:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" rows="5" class="textarea" name="custo_descricao" id="custo_descricao" style="width:395px;"></textarea></td></tr>';

if (isset($exibir['codigo']) && $exibir['codigo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['codigo_valor']), 'Insira '.$config['genero_codigo_valor'].' '.$config['codigo_valor'].' deste item.').ucfirst($config['codigo_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="custo_codigo" id="custo_codigo" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="custo_codigo" id="custo_codigo" value="" />';

if (isset($exibir['fonte']) && $exibir['fonte']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fonte_valor']), 'Insira '.$config['genero_fonte_valor'].' '.$config['fonte_valor'].' deste item.').ucfirst($config['fonte_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="custo_fonte" id="custo_fonte" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="custo_fonte" id="custo_fonte" value="" />';

if (isset($exibir['regiao']) && $exibir['regiao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['regiao_valor']), 'Insira '.$config['genero_regiao_valor'].' '.$config['regiao_valor'].' deste item.').ucfirst($config['regiao_valor']).':'.dicaF().'</td><td><input type="text" class="texto"  name="custo_regiao" id="custo_regiao" value="" maxlength="255" style="width:391px;" /></td></tr>';
else echo '<input type="hidden" name="custo_regiao" id="custo_regiao" value="" />';	



if (count($pi)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('PI', 'Escolha o Plano Interno deste item.').'PI:'.dicaF().'</td><td>'.selecionaVetor($pi, 'custo_pi', 'class=texto size=1 style="width:395px;"').'</td></tr>';
else echo '<input type="hidden" name="custo_pi" id="custo_pi" value="" />';

if (count($ptres)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('PTRES', 'Escolha o Plano de Trabalho Resumido deste item.').'PTRES:'.dicaF().'</td><td>'.selecionaVetor($ptres, 'custo_ptres', 'class=texto size=1 style="width:395px;"').'</td></tr>';
else echo '<input type="hidden" name="custo_ptres" id="custo_ptres" value="" />';


echo '<tr><td align="right">'.dica('Data para Recebimento','Data limite para o recebimento do �tem.').'Data para recebimento:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="custo_data_limite" id="custo_data_limite" value="'.($data_texto ? $data_texto->format('%Y%m%d') : '').'" /><input type="text" name="data_texto"  id="data_texto" style="width:65px;" onchange="setData(\'env\', \'data_texto\', \'custo_data_limite\');" value="'.($data_texto ? $data_texto->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Limite', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data limite para o recebimento do �tem.').'<a href="javascript: void(0);" ><img id="f_btn4" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Total', 'O valor total do item.').'Total:'.dicaF().'</td><td><div id="total"></div></td></tr>';


















echo '</table></td>';

echo '<td id="adicionar_custo" style="display:"><a href="javascript: void(0);" onclick="incluir_custo();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste �cone '.imagem('icones/adicionar.png').' para incluir o item.').'</a></td>';
echo '<td id="confirmar_custo" style="display:none"><a href="javascript: void(0);" onclick="limpar_custo();">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar a edi��o do item.').'</a><a href="javascript: void(0);" onclick="incluir_custo();">'.imagem('icones/ok_g.png','Confirmar','Clique neste �cone '.imagem('icones/ok.png').' para confirmar a edi��o do item.').'</a></td>';
echo '</tr>';

echo '</table></td></tr>';


//planilha de custo
$sql->adTabela('custo');
$sql->adCampo('custo.*, ((custo_quantidade*custo_custo)*((100+custo_bdi)/100)) AS valor ');
$sql->adOnde('custo_log ='.(int)$log->log_id);
$sql->adOnde('custo_gasto !=1');
$sql->adOrdem('custo_ordem');
$linhas= $sql->Lista();
$qnt=0;

$ptres=0;
$pi=0;
foreach($linhas as $linha){
	if ($linha['custo_ptres']) $ptres++;
	if ($linha['custo_pi']) $pi++;
	}


echo '<tr><td ><div id="combo_custo">';
if (count($linhas)){
	echo '<table '.($dialogo ? 'width=1080' : '').' cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th colspan=30>Planilha de Custos Estimados</th></tr>';
	echo '<tr>'.(!$dialogo ? '<th></th>' : '').
	'<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descri��o', 'Descri��o do item.').'Descri��o'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de refer�ncia para o item.').'Un.'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade demandada do �tem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unit�rio', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benef�cios e Despesas Indiretas, � o elemento or�ament�rio destinado a cobrir todas as despesas que, num empreendimento, segundo crit�rios claramente definidos, classificam-se como indiretas (por simplicidade, as que n�o expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material � m�o-de-obra, equipamento-obra, instrumento-obra etc.), e, tamb�m, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total', 'O valor total � o pre�o unit�rio multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
	(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').'
	<th>'.dica('Respons�vel', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Respons�vel'.dicaF().'</th>
	<th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
	($pi ? '<th>'.dica('PI', 'PI do item.').'PI'.dicaF().'</th>' : '').
	($ptres ? '<th>'.dica('PTRES', 'PTRES do item.').'PTRES'.dicaF().'</th>' : '').
	(!$dialogo ? '<th></th>' : '').
	'</tr>';
	}
$total=array();
$custo=array();
foreach ($linhas as $linha) {
	echo '<tr align="center">';

	if (!$dialogo) {
		echo '<td width="40" align="right">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverPrimeiro\', false);"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaCima\', false);"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaBaixo\', false);"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverUltimo\', false);"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}

	echo '<td align="left">'.++$qnt.' - '.$linha['custo_nome'].'</td>';
	echo '<td align="left">'.($linha['custo_descricao'] ? $linha['custo_descricao'] : '&nbsp;').'</td>';
	echo '<td>'.$unidade[$linha['custo_tipo']].'</td><td>'.number_format($linha['custo_quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right">'.number_format($linha['custo_bdi'], 2, ',', '.').'</td>';
	$nd=($linha['custo_categoria_economica'] && $linha['custo_grupo_despesa'] && $linha['custo_modalidade_aplicacao'] ? $linha['custo_categoria_economica'].'.'.$linha['custo_grupo_despesa'].'.'.$linha['custo_modalidade_aplicacao'].'.' : '').$linha['custo_nd'];
	echo '<td>'.$nd.'</td>';
	echo '<td align="right">'.$moedas[$linha['custo_moeda']].' '.number_format($linha['valor'], 2, ',', '.').'</td>';
	
	if (isset($exibir['codigo']) && $exibir['codigo']) echo '<td align="center">'.($linha['custo_codigo'] ? $linha['custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte']) echo '<td align="center">'.($linha['custo_fonte'] ? $linha['custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo '<td align="center">'.($linha['custo_regiao'] ? $linha['custo_regiao'] : '&nbsp;').'</td>';  

	echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['custo_usuario'],'','','esquerda').'</td>';
	echo '<td>'.($linha['custo_data_limite']? retorna_data($linha['custo_data_limite'],false) : '&nbsp;').'</td>';
	if ($pi) echo '<td align="center">'.$linha['custo_pi'].'</td>';
	if ($ptres) echo '<td align="center">'.$linha['custo_ptres'].'</td>';
	if (!$dialogo) {
		echo '<td width="32">';
		echo dica('Editar Item', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o item '.$linha['custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:editar_custo('.$linha['custo_id'].', false);">'.imagem('icones/editar.gif').'</a>'.dicaF();
		echo dica('Excluir Item', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir o item '.$linha['custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:excluir_custo('.$linha['custo_id'].', false);">'.imagem('icones/remover.png').'</a>'.dicaF();
		echo '</td>';
		}
	echo '</tr>';
	
	if (isset($custo[$linha['custo_moeda']][$nd])) $custo[$linha['custo_moeda']][$nd] += (float)$linha['valor'];
	else $custo[$linha['custo_moeda']][$nd]=(float)$linha['valor'];
	
	if (isset($total[$linha['custo_moeda']])) $total[$linha['custo_moeda']]+=$linha['valor'];
	else $total[$linha['custo_moeda']]=$linha['valor']; 
	
	}
	
$tem_total=false;
foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
	
if ($tem_total) {
	foreach ($custo as $tipo_moeda => $linha) {
		echo '<tr><td colspan="'.($config['bdi'] ? 8 : 7).'" class="std" align="right">';
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda], 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}	
	}		
if (count($linhas)) echo '</table>';

echo '</div></td></tr>';






//planilha de gasto
$sql->adTabela('custo');
$sql->adCampo('custo.*, ((custo_quantidade*custo_custo)*((100+custo_bdi)/100)) AS valor');
$sql->adOnde('custo_log ='.(int)$log->log_id);
$sql->adOnde('custo_gasto=1');
$sql->adOrdem('custo_ordem');
$linhas=$sql->Lista();
$qnt=0;

$ptres=0;
$pi=0;
foreach($linhas as $linha){
	if ($linha['custo_ptres']) $ptres++;
	if ($linha['custo_pi']) $pi++;
	}

echo '<tr><td><div id="combo_gasto">';
if (count($linhas)){
	echo '<table '.($dialogo ? 'width=1080' : '').' cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th colspan=30>Planilha de Gastos Efetuados</th></tr>';
	echo '<tr>'.(!$dialogo ? '<th></th>' : '').
	'<th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descri��o', 'Descri��o do item.').'Descri��o'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de refer�ncia para o item.').'Un.'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade demandada do �tem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unit�rio', 'O valor de uma unidade do item.').'Valor'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benef�cios e Despesas Indiretas, � o elemento or�ament�rio destinado a cobrir todas as despesas que, num empreendimento, segundo crit�rios claramente definidos, classificam-se como indiretas (por simplicidade, as que n�o expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material � m�o-de-obra, equipamento-obra, instrumento-obra etc.), e, tamb�m, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total', 'O valor total � o pre�o unit�rio multiplicado pela quantidade.').'Total'.dicaF().'</th>'.
	(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').'
	<th>'.dica('Respons�vel', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Respons�vel'.dicaF().'</th>
	<th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
	($pi ? '<th>'.dica('PI', 'PI do item.').'PI'.dicaF().'</th>' : '').
	($ptres ? '<th>'.dica('PTRES', 'PTRES do item.').'PTRES'.dicaF().'</th>' : '').
	(!$dialogo ? '<th></th>' : '').
	'</tr>';
	}
$total=array();
$custo=array();
foreach ($linhas as $linha) {
	echo '<tr align="center">';
	if (!$dialogo) {
		echo '<td width="40" align="right">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverPrimeiro\', true);"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaCima\', true);"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverParaBaixo\', true);"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_custo('.(int)$linha['custo_ordem'].', '.$linha['custo_id'].', \'moverUltimo\', true);"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td align="left">'.++$qnt.' - '.$linha['custo_nome'].'</td>';
	echo '<td align="left">'.($linha['custo_descricao'] ? $linha['custo_descricao'] : '&nbsp;').'</td>';
	echo '<td>'.$unidade[$linha['custo_tipo']].'</td>';
	echo '<td>'.number_format($linha['custo_quantidade'], 2, ',', '.').'</td>';
	echo '<td align="right">'.number_format($linha['custo_custo'], 2, ',', '.').'</td>';
	if ($config['bdi']) echo '<td align="right">'.number_format($linha['custo_bdi'], 2, ',', '.').'</td>';
	$nd=($linha['custo_categoria_economica'] && $linha['custo_grupo_despesa'] && $linha['custo_modalidade_aplicacao'] ? $linha['custo_categoria_economica'].'.'.$linha['custo_grupo_despesa'].'.'.$linha['custo_modalidade_aplicacao'].'.' : '').$linha['custo_nd'];
	echo '<td>'.$nd.'</td>';
	echo '<td align="right">'.number_format($linha['valor'], 2, ',', '.').'</td>';
	if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['custo_codigo'] ? $linha['custo_codigo'] : '&nbsp;').'</td>';
	if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['custo_fonte'] ? $linha['custo_fonte'] : '&nbsp;').'</td>';
	if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['custo_regiao'] ? $linha['custo_regiao'] : '&nbsp;').'</td>';  
	echo '<td align="left" style="white-space: nowrap">'.link_usuario($linha['custo_usuario'],'','','esquerda').'</td>';
	echo '<td>'.($linha['custo_data_limite']? retorna_data($linha['custo_data_limite'],false) : '&nbsp;').'</td>';
	if ($pi) echo '<td align="center">'.$linha['custo_pi'].'</td>';
	if ($ptres) echo '<td align="center">'.$linha['custo_ptres'].'</td>';
	if (!$dialogo) {
		echo '<td width="32">';
		echo dica('Editar Item', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o item '.$linha['custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:editar_custo('.$linha['custo_id'].', true);">'.imagem('icones/editar.gif').'</a>'.dicaF();
		echo dica('Excluir Item', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir o item '.$linha['custo_nome'].'.').'<a href="javascript:void(0);" onclick="javascript:excluir_custo('.$linha['custo_id'].', true);">'.imagem('icones/remover.png').'</a>'.dicaF();
		echo '</td>';
		}
	echo '</tr>';
	
	if (isset($custo[$linha['custo_moeda']][$nd])) $custo[$linha['custo_moeda']][$nd] += (float)$linha['valor'];
	else $custo[$linha['custo_moeda']][$nd]=(float)$linha['valor'];
	
	if (isset($total[$linha['custo_moeda']])) $total[$linha['custo_moeda']]+=$linha['valor'];
	else $total[$linha['custo_moeda']]=$linha['valor']; 
	
	}

$tem_total=false;
foreach($total as $chave => $valor)	if ($valor) $tem_total=true;
	
if ($tem_total) {
	foreach ($custo as $tipo_moeda => $linha) {
		echo '<tr><td colspan="'.($config['bdi'] ? 7 : 6).'" class="std" align="right">';
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
		echo '<br><b>Total</td><td align="right">';	
		foreach ($linha as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.$moedas[$tipo_moeda].' '.number_format($somatorio, 2, ',', '.');
		echo '<br><b>'.$moedas[$tipo_moeda].' '.number_format($total[$tipo_moeda], 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
		}	
	}		


if (count($linhas)) echo '</table>';

echo '</div></td></tr>';

echo '</table></td></tr>';




echo '<tr><td style="height:1px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_notificacao\').style.display) document.getElementById(\'apresentar_notificacao\').style.display=\'\'; else document.getElementById(\'apresentar_notificacao\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notifica��o</b></a></td></tr>';
echo '<tr id="apresentar_notificacao" style="display:none"><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';


echo '<tr><td align="right" valign="top" style="white-space: nowrap" width=155 >'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($log_id > 0 ? 'modifica��o' : 'cria��o').' do registro.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo ($tarefa['tarefa_dono'] != $Aplic->usuario_id ? '<input type="checkbox" name="log_notificar_responsavel" id="log_notificar_responsavel" value=1 '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' />'.dica('Respons�vel', 'Enviar e-mail ao respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<label for="log_notificar_responsavel">Respons�vel</label>'.dicaF().'<br>' : '');
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' value=1 />'.dica('Designados par'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados para est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'<label for="email_designados">Designados</label>'.dicaF().'<br>';
echo '<input type="checkbox" name="email_tarefa_contatos" id="email_tarefa_contatos" '.($Aplic->getPref('informa_contatos') ? 'checked="checked"' : '').' value=1 />'.dica('Contatos d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os contatos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'<label for="email_tarefa_contatos">Contatos</label>'.dicaF().'<br>';
echo '<input type="checkbox" name="email_projeto_responsavel" id="email_projeto_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value=1 />'.dica(ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o gerente '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').'<label for="email_projeto_responsavel">'.ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.$config['projeto'].'</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td></td><td>'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados.').'Destinat�rios extra'.dicaF().'</td></tr><tr><td>'.($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso') ? botao('outros contatos', 'Outros Contatos','Abrir uma caixa de di�logo onde poder� selecionar outras pessoas que ser�o informadas por e-mail sobre este registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.','','popEmailContatos()') : '').'</td>'.($config['email_ativo'] ? ''.($config['email_ativo'] ? '<td><input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';

echo '</table></td></tr>';

echo '<tr><td colspan=2 align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Anexar arquivos','Clique neste link para anexar um arquivo a este registro de ocorr�ncia.<br>Caso necessite anexar m�ltiplos arquivos basta clicar aqui sucessivamente para criar os campos necess�rios.').'<b>Anexar arquivos</b>'.dicaF().'</a></td></tr>';
echo '<tr><td colspan="20" align="center"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';


echo '</form>';


echo '<tr><td colspan="2"><div id="combo_arquivos"><table cellspacing=0 cellpadding=0>';

//arquivo anexo
$sql->adTabela('log_arquivo');
$sql->adCampo('log_arquivo_id, log_arquivo_usuario, log_arquivo_data, log_arquivo_ordem, log_arquivo_nome, log_arquivo_endereco');
$sql->adOnde('log_arquivo_log='.(int)$log_id);
$sql->adOrdem('log_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();
if ($arquivos && count($arquivos)) echo '<tr><td colspan=2>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</td></tr>';
foreach ($arquivos as $arquivo) {
	echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
	echo '<td style="white-space: nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['log_arquivo_ordem'].', '.$arquivo['log_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=praticas&a=log_download&sem_cabecalho=1&log_arquivo_id='.$arquivo['log_arquivo_id'].'\');">'.$arquivo['log_arquivo_nome'].'</a></td>';
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_arquivo('.$arquivo['log_arquivo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
	echo '</tr></table></td></tr>';
	}

echo '</table></div></td></tr>';




echo '<tr><td colspan=2><table width="100%" cellspacing=0 cellpadding=0><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','updateTarefa()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','if(confirm(\'Tem certeza quanto � cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr></table></td></tr>';
echo '</table>';

echo estiloFundoCaixa();


echo selecao_calendarios($data_inicio, $data_fim, $projeto_id,'','oculto_data_inicio','oculto_data_fim','CompararDatas();','data_ajax();','horas_ajax();');


?>
<script type="text/javascript">

//bioma
function mudar_posicao_bioma(ordem, log_bioma_id, direcao){
	xajax_mudar_posicao_bioma(ordem, log_bioma_id, direcao, document.getElementById('log_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function editar_bioma(log_bioma_id){

	xajax_editar_bioma(log_bioma_id);
	document.getElementById('adicionar_bioma').style.display="none";
	document.getElementById('confirmar_bioma').style.display="";

	}

function incluir_bioma(){
	if (document.getElementById('log_bioma_bioma').value !=''){
		xajax_incluir_bioma(document.getElementById('log_id').value, document.getElementById('uuid').value, document.getElementById('log_bioma_id').value, document.getElementById('log_bioma_bioma').value);
		//document.getElementById('log_bioma_id').value=null;
		//document.getElementById('log_bioma_bioma').value='';
		document.getElementById('adicionar_bioma').style.display='';
		document.getElementById('confirmar_bioma').style.display='none';
		__buildTooltip();
		}
	else alert('Insira uma bioma.');
	}

function excluir_bioma(log_bioma_id){
	xajax_excluir_bioma(log_bioma_id, document.getElementById('log_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}	






//comunidade
function mudar_posicao_comunidade(ordem, log_comunidade_id, direcao){
	xajax_mudar_posicao_comunidade(ordem, log_comunidade_id, direcao, document.getElementById('log_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function editar_comunidade(log_comunidade_id){
	xajax_editar_comunidade(log_comunidade_id);
	document.getElementById('adicionar_comunidade').style.display="none";
	document.getElementById('confirmar_comunidade').style.display="";
	}

function incluir_comunidade(){
	if (document.getElementById('log_comunidade_comunidade').value !=''){
		xajax_incluir_comunidade(document.getElementById('log_id').value, document.getElementById('uuid').value, document.getElementById('log_comunidade_id').value, document.getElementById('log_comunidade_comunidade').value);
		//document.getElementById('log_comunidade_id').value=null;
		//document.getElementById('log_comunidade_comunidade').value='';
		document.getElementById('adicionar_comunidade').style.display='';
		document.getElementById('confirmar_comunidade').style.display='none';
		__buildTooltip();
		}
	else alert('Insira uma comunidade.');
	}

function excluir_comunidade(log_comunidade_id){
	xajax_excluir_comunidade(log_comunidade_id, document.getElementById('log_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}	


	
function mudar_moeda(moeda){
	//if (moeda > 1) document.getElementById('combo_data_moeda').style.display='';
	//else document.getElementById('combo_data_moeda').style.display='none';
	}
	
function somenteFloat(e){
	var tecla=new Number();
	if(window.event) tecla = e.keyCode;
	else if(e.which) tecla = e.which;
	else return true;
	if(((tecla < "48") && tecla !="44") || (tecla > "57")) return false;
	}	
		

function valor(){
	var custo=moeda2float(document.getElementById('custo_custo').value);
	var qnt=moeda2float(document.getElementById('custo_quantidade').value);
	var bdi=moeda2float(document.getElementById('custo_bdi').value);
	if (custo=='') custo=0;
	if (valor=='') valor=0;
	if (bdi=='') bdi=0;
	document.getElementById('total').innerHTML ='<b>'+float2moeda((custo*qnt)*((100+bdi)/100))+'</b>';
	}

function editar_custo(custo_id, gasto){
	xajax_editar_custo(custo_id);
	<?php if ($Aplic->profissional) { ?>
		CKEDITOR.instances['custo_descricao'].setData(document.getElementById('apoio1').value);

	<?php } ?>
	document.getElementById('antigo_gasto').value=gasto;
	document.getElementById('adicionar_custo').style.display="none";
	document.getElementById('confirmar_custo').style.display="";
	}


function limpar_custo(){
	CKEDITOR.instances['custo_descricao'].setData('');
	document.getElementById('custo_nome').value='';
	document.getElementById('custo_descricao').value='';
	document.getElementById('custo_quantidade').value='';
	document.getElementById('custo_custo').value='';
	document.getElementById('custo_id').value=null;
	document.getElementById('adicionar_custo').style.display='';
	document.getElementById('confirmar_custo').style.display='none';
	document.getElementById('custo_gasto').disabled=false;
	}

function incluir_custo(edicao){
	
	xajax_incluir_custo(
		document.getElementById('log_id').value,
		document.getElementById('uuid').value,
		document.getElementById('custo_id').value,
		document.getElementById('custo_nome').value,
		document.getElementById('custo_tipo').value,
		document.getElementById('custo_quantidade').value,
		document.getElementById('custo_custo').value,
		CKEDITOR.instances['custo_descricao'].getData(),
		document.getElementById('custo_nd').value,
		document.getElementById('custo_categoria_economica').value,
		document.getElementById('custo_grupo_despesa').value,
		document.getElementById('custo_modalidade_aplicacao').value,
		document.getElementById('custo_data_limite').value,
		document.getElementById('custo_codigo').value,
		document.getElementById('custo_fonte').value,
		document.getElementById('custo_regiao').value,
		document.getElementById('custo_bdi').value,
		document.getElementById('custo_ptres').value,
		document.getElementById('custo_pi').value,
		document.getElementById('custo_gasto').value,
		document.getElementById('custo_moeda').value,
		document.getElementById('custo_data_moeda').value
		);
	__buildTooltip();
	limpar_custo();
	
	}

function excluir_custo(custo_id, gasto){
	if (confirm('Tem certeza que deseja excluir?')) {
		xajax_excluir_custo(custo_id, document.getElementById('log_id').value, document.getElementById('uuid').value, gasto);
		__buildTooltip();
		}
	}

function mudar_posicao_custo(ordem, custo_id, direcao, gasto){
	xajax_mudar_posicao_custo(ordem, custo_id, direcao, document.getElementById('log_id').value, document.getElementById('uuid').value, gasto);
	__buildTooltip();
	}




function excluir_arquivo(log_arquivo_id){
	xajax_excluir_arquivo(log_arquivo_id, document.getElementById('log_id').value);
	}

function mudar_posicao_arquivo(log_arquivo_ordem, log_arquivo_id, direcao){
	xajax_mudar_posicao_arquivo(log_arquivo_ordem, log_arquivo_id, direcao, document.getElementById('log_id').value);
	}


function incluir_arquivo(){
	var r  = document.createElement('tr');
  var ca = document.createElement('td');

	var ta = document.createTextNode(' Arquivo:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'arquivo[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=80;
	campo.className="texto";
	ca.appendChild(campo);

	r.appendChild(ca);

	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}

var cal5 = Calendario.setup({
  	trigger    : "f_btn5",
    inputField : "custo_data_moeda",
  	date :  <?php echo $data_texto->format("%Y%m%d")?>,
  	selection: <?php echo $data_texto->format("%Y%m%d")?>,
    onSelect: function(cal2) {
    var date = cal5.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data5_texto").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("custo_data_moeda").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal5.hide();
  	}
  });  

var cal3 = Calendario.setup({
	trigger    : "f_btn3",
  inputField : "log_data",
	date :  <?php echo $log_data->format("%Y%m%d")?>,
	selection: <?php echo $log_data->format("%Y%m%d")?>,
  onSelect: function(cal3) {
  var date = cal3.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("log_data_nome").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("log_data").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal3.hide();
	}
});



var cal4 = Calendario.setup({
	trigger    : "f_btn4",
  inputField : "custo_data_limite",
	date :  <?php echo $data_texto->format("%Y%m%d")?>,
	selection: <?php echo $data_texto->format("%Y%m%d")?>,
  onSelect: function(cal4) {
  var date = cal4.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("data_texto").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("custo_data_limite").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal4.hide();
	}
});


function mudar_nd(){
	xajax_mudar_nd_ajax(env.custo_nd.value, 'custo_nd', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3, env.custo_categoria_economica.value, env.custo_grupo_despesa.value, env.custo_modalidade_aplicacao.value);
	}


function float2moeda(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);
	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	for (var i=0; i< Math.floor((num.length-(1+i))/3); i++) num=num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	ret=num+','+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}



function updateTarefa() {
	var f = document.env;
	var descricao=CKEDITOR.instances['log_descricao'].getData();
	if (descricao.length < 1) {
		alert( 'Por favor, insira uma descri��o � ocorr�ncia.' );
		f.log_descricao.focus();
		}
	else if (isNaN( parseInt( f.tarefa_percentagem.value+0 ) )) {
		alert( 'Para inserir uma percentagem completa do trabalho, insira um n� inteiro' );
		f.tarefa_percentagem.focus();
		}
	else if(f.tarefa_percentagem.value  < 0 || f.tarefa_percentagem.value > 100) {
		alert( 'A percentagem completa do trabalho deve ser um n� entre 0 e 100' );
		f.tarefa_percentagem.focus();
		}
	else {
		f.tarefa_duracao.value=moeda2float(f.tarefa_duracao.value);
		f.tarefa_realizado.value=moeda2float(f.tarefa_realizado.value);
		f.log_horas.value=moeda2float(f.log_horas.value);
		f.submit();
		}
	}

function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 39 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}




function CompararDatas(){
    var str1 = document.getElementById("data_inicio").value;
    var str2 = document.getElementById("data_fim").value;
    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);
    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);
    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);
    if(date2 < date1){
      document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
      document.getElementById("oculto_data_fim").value=document.getElementById("oculto_data_inicio").value;
    	}
   }


function setData(frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.'+frm_nome+'.'+f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.'+f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
  	else {
  		campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
  		campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';

      //data final fazer ao menos no mesmo dia da inicial
      CompararDatas();

			}
		}
	else campo_data_real.value = '';
	}

 function horas_ajax(){
	var f=document.env;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minutos.value+':00';
	var fim=f.oculto_data_fim.value+' '+f.hora_fim.value+':'+f.minuto_fim.value+':00';
	xajax_calcular_duracao(inicio, fim, <?php echo $tarefa['tarefa_cia'] ?>);
	}


function data_ajax(){
	var f=document.env;
	var inicio=f.oculto_data_inicio.value+' '+f.inicio_hora.value+':'+f.inicio_minutos.value+':00';
	var horas=f.tarefa_duracao.value;
	xajax_data_final_periodo(inicio, horas, <?php echo $tarefa['tarefa_cia'] ?>);
	}



function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contatos) {
	if (!contatos) contatos = '';
	document.getElementById('email_outro').value = contatos;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}





//entregas

function cancelar_edicao_entrega(){
	document.getElementById('log_entrega_id').value=0;
	CKEDITOR.instances['log_entrega_observacao'].setData('');
	document.getElementById(['log_entrega_realizado']).value='';   
	document.getElementById('adicionar_entrega').style.display='';	
	document.getElementById('confirmar_entrega').style.display='none';
	
	document.getElementById('tarefa_entrega_id').value=null;
	}

function editar_entrega(log_entrega_id){
	document.getElementById('adicionar_entrega').style.display="none";
	document.getElementById('confirmar_entrega').style.display="";
	xajax_editar_entrega(log_entrega_id);
	CKEDITOR.instances['log_entrega_observacao'].setData(document.getElementById('apoio_entrega').value);
	}
	
	
function incluir_entrega(){
	if (document.getElementById('tarefa_entrega_id').value!=''){
		xajax_incluir_entrega(
		document.getElementById('log_id').value, 
		document.getElementById('uuid').value, 
		document.getElementById('log_entrega_id').value, 
		document.getElementById('tarefa_entrega_id').value, 
		CKEDITOR.instances['log_entrega_observacao'].getData(),
		document.getElementById('log_entrega_realizado').value
		);
		
		document.getElementById('log_entrega_id').value=null;
		CKEDITOR.instances['log_entrega_observacao'].setData('');
		document.getElementById('log_entrega_realizado').value='';
		document.getElementById('adicionar_entrega').style.display='';	
		document.getElementById('confirmar_entrega').style.display='none';
		}
	else alert('Precisa selecionar uma entrega');	
	}	
	
function excluir_entrega(log_entrega_id){
	xajax_excluir_entrega(log_entrega_id, document.getElementById('log_id').value, document.getElementById('uuid').value);
	}
	
</script>

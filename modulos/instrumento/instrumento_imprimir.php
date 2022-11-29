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

include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
require_once BASE_DIR.'/modulos/instrumento/instrumento.class.php';
$Aplic->carregarCKEditorJS(300);
$instrumento_id=getParam($_REQUEST, 'instrumento_id', 0);

$estilo=($dialogo ? 'font-size:12pt;': '');
$estilo_texto=($dialogo ? 'style="font-size:12pt;"': '');

$obj = new Cinstrumento();
$obj->load($instrumento_id);

$sql = new BDConsulta;
$sql->adTabela('instrumento_campo');
$sql->adCampo('instrumento_campo.*');
$sql->adOnde('instrumento_campo_id ='.(int)$obj->instrumento_campo);
$exibir=$sql->linha();
$sql->limpar();

if ($Aplic->profissional){	
	//tem assinatura que aprova
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_aprova=1');
	$sql->adOnde('assinatura_instrumento='.(int)$instrumento_id);
	$tem_aprovacao = $sql->resultado();
	$sql->limpar();
	}	

$sql->adTabela('moeda');
$sql->adCampo('moeda_id, moeda_simbolo');
$sql->adOrdem('moeda_id');
$moedas=$sql->listaVetorChave('moeda_id','moeda_simbolo');
$sql->limpar();


include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
$dados=array();

$sql->adTabela('instrumento');
$sql->adCampo('instrumento_cia AS projeto_cia');
$sql->adOnde('instrumento_id = '.(int)$instrumento_id);
$linhas=$sql->Linha();
$sql->limpar();


$dados['projeto_cia'] = $linhas['projeto_cia'];

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



















$numeracao_titulo=0;




echo '<table cellpadding=0 cellspacing=0 width=100%>';

$numeracao=0;
if ($exibir['instrumento_identificacao']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_identificacao_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_nome_leg'].':'.$obj->instrumento_nome.'</td></tr>';



if ($exibir['instrumento_entidade'] && $obj->instrumento_entidade) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_leg'].':'.$obj->instrumento_entidade.'</td></tr>';
if ($exibir['instrumento_entidade_cnpj'] && $obj->instrumento_entidade_cnpj) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_cnpj_leg'].':'.$obj->instrumento_entidade_cnpj.'</td></tr>';
if ($exibir['instrumento_entidade_codigo'] && $obj->instrumento_entidade_codigo) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_entidade_codigo_leg'].':'.$obj->instrumento_entidade_codigo.'</td></tr>';

if ($exibir['instrumento_tipo']) {
	$sql->adTabela('instrumento_campo');
	$sql->adCampo('instrumento_campo_nome');
	$sql->adOnde('instrumento_campo_id='.(int)($instrumento_id ? $obj->instrumento_campo : $instrumento_campo));
	$instrumento_campo=$sql->resultado();
	$sql->limpar();
	echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_tipo_leg'].':'.$instrumento_campo.'</td></tr>';

	}

if ($exibir['instrumento_numero'] && $obj->instrumento_numero) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_numero_leg'].':'.$obj->instrumento_numero.'</td></tr>';
if ($exibir['instrumento_ano'] && $obj->instrumento_ano) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_ano_leg'].':'.$obj->instrumento_ano.'</td></tr>';
if ($exibir['instrumento_prorrogavel'] && $obj->instrumento_prorrogavel) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_prorrogavel_leg'].':'.($obj->instrumento_prorrogavel ? 'Sim' : 'Não').'</td></tr>';
if ($exibir['instrumento_situacao'] && $obj->instrumento_situacao) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_situacao_leg'].':'.getSisValorCampo('SituacaoInstrumento', $obj->instrumento_situacao).'</td></tr>';
if ($exibir['instrumento_valor'] && ($obj->instrumento_valor > 0)) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_leg'].':'.number_format($obj->instrumento_valor,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	
if ($exibir['instrumento_valor_atual'] && ($obj->instrumento_valor_atual > 0)) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_leg'].' atual:'.number_format($obj->instrumento_valor_atual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';

if ($exibir['instrumento_valor_contrapartida'] && $obj->instrumento_valor_contrapartida) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_contrapartida_leg'].':'.number_format($obj->instrumento_valor_contrapartida,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
if ($exibir['instrumento_valor_repasse'] && $obj->instrumento_valor_repasse) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_valor_repasse_leg'].':'.number_format($obj->instrumento_valor_repasse,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';

//CHECAR SE TEM os
$sql->adTabela('os_gestao','os_gestao');
$sql->esqUnir('os','os', 'os_gestao_os=os_id');
$sql->adOnde('os_gestao_instrumento = '.(int)$instrumento_id);
$sql->adOnde('os_gestao_os IS NOT NULL');
$sql->adCampo('SUM(os_valor)');
$soma_os=$sql->resultado();
$sql->limpar();

if ($soma_os!=0) {
	echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. Valor d'.$config['genero_os'].'s '.$config['os'].': '.number_format($soma_os,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. Saldo de contrato: '.number_format(($obj->instrumento_valor-$soma_os),($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	}






if ($exibir['instrumento_fim_contrato'] && $obj->instrumento_fim_contrato) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fim_contrato_leg'].':'.retorna_data($obj->instrumento_fim_contrato, false).'</td></tr>';



if ($exibir['instrumento_identificacao']) echo '</table></fieldset></td></tr>';


$numeracao=0;
if ($exibir['instrumento_demandante']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_demandante_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
if ($exibir['instrumento_cia']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['organizacao']).' Responsável', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().''.link_cia($obj->instrumento_cia).'</td></tr>';
if ($exibir['instrumento_cias'] && $Aplic->profissional) {
	$sql->adTabela('instrumento_cia');
	$sql->adCampo('instrumento_cia_cia');
	$sql->adOnde('instrumento_cia_instrumento = '.(int)$instrumento_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();	
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $saida_cias.='<br>'.link_cia($cias_selecionadas[$i]);

				}
		}
	if ($saida_cias) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'s.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().''.$saida_cias.'</td></tr>';
	}
if ($exibir['instrumento_dept'] && $obj->instrumento_dept && $Aplic->profissional) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().''.link_dept($obj->instrumento_dept).'</td></tr>';

if ($exibir['instrumento_depts'] && $Aplic->profissional) {
	$sql->adTabela('instrumento_depts');
	$sql->adCampo('DISTINCT instrumento_depts.dept_id');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$instrumento_depts = $sql->carregarColuna();
	$sql->limpar();
	$saida_depts='';
	if ($instrumento_depts && count($instrumento_depts)) {
		$saida_depts.= link_dept($instrumento_depts[0]);
		$qnt_lista_depts=count($instrumento_depts);
		if ($qnt_lista_depts > 1) {		
			$lista='';
			for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $saida_depts.='<br>'.link_dept($instrumento_depts[$i]);		
			}
		} 
	if ($saida_depts) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s com '.($config['genero_instrumento']=='o' ? 'este' : 'esta').' '.$config['instrumento'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().''.$saida_depts.'</td></tr>';
	}

if ($exibir['instrumento_responsavel'] && $obj->instrumento_responsavel) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Responsável pel'.$config['genero_instrumento'].' '.$config['instrumento'], 'Tod'.$config['genero_instrumento'].' '.$config['instrumento'].' deve ter um responsável.').'Responsável:'.dicaF().''.link_usuario($obj->instrumento_responsavel,'','','esquerda').'</td></tr>';

if ($exibir['instrumento_designados']){
	$sql->adTabela('instrumento_designados');
	$sql->adCampo('usuario_id');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$instrumento_designados = $sql->carregarColuna();
	$sql->limpar();
	$saida_quem='';
	if ($instrumento_designados && count($instrumento_designados)) {
			$saida_quem.= link_usuario($instrumento_designados[0], '','','esquerda');
			$qnt_instrumento_designados=count($instrumento_designados);
			if ($qnt_instrumento_designados > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_instrumento_designados; $i < $i_cmp; $i++) $saida_quem.='<br>'.link_usuario($instrumento_designados[$i], '','','esquerda');		
					}
			} 
	if($saida_quem) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Designado', 'Quais '.$config['usuarios'].' estão designados para '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Designado:'.dicaF().''.$saida_quem.'</td></tr>';
	}

if ($exibir['instrumento_supervisor'] && $obj->instrumento_supervisor) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['supervisor']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_supervisor']=='o' ? 'um' : 'uma').' '.$config['supervisor'].' relacionad'.$config['genero_supervisor'].'.').ucfirst($config['supervisor']).':'.dicaF().''.link_usuario($obj->instrumento_supervisor,'','','esquerda').'</td></tr>';
if ($exibir['instrumento_autoridade'] && $obj->instrumento_autoridade) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['autoridade']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_autoridade']=='o' ? 'um' : 'uma').' '.$config['autoridade'].' relacionad'.$config['genero_autoridade'].'.').ucfirst($config['autoridade']).':'.dicaF().''.link_usuario($obj->instrumento_autoridade,'','','esquerda').'</td></tr>';
if ($exibir['instrumento_cliente'] && $obj->instrumento_cliente) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica(ucfirst($config['cliente']), ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' poderá ter '.($config['genero_cliente']=='o' ? 'um' : 'uma').' '.$config['cliente'].' relacionad'.$config['genero_cliente'].'.').ucfirst($config['cliente']).':'.dicaF().''.link_usuario($obj->instrumento_cliente,'','','esquerda').'</td></tr>';
if ($exibir['instrumento_fiscal_substituto'] && $obj->instrumento_fiscal_substituto) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fiscal_substituto_leg'].':'.link_usuario($obj->instrumento_fiscal_substituto,'','','esquerda').'</td></tr>';

if ($exibir['instrumento_fiscal'] && $obj->instrumento_fiscal) {
	echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fiscal_leg'].':'.link_usuario($obj->instrumento_fiscal,'','','esquerda').'</td></tr>';
	
	
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('contato_email, contato_tel, contato_tel2, contato_cel');
	$sql->adOnde('usuario_id='.(int)$obj->instrumento_fiscal);
	$linha_contato = $sql->linha();
	$sql->limpar();
		
	if ($linha_contato['contato_email']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'E-mail '.$exibir['instrumento_fiscal_leg'].':'.$linha_contato['contato_email'].'</td></tr>';
	if ($linha_contato['contato_tel']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_leg'].':'.$linha_contato['contato_tel'].'</td></tr>';
	else if ($linha_contato['contato_tel2']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_leg'].':'.$linha_contato['contato_tel2'].'</td></tr>';
	else if ($linha_contato['contato_cel']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Celular '.$exibir['instrumento_fiscal_leg'].':'.$linha_contato['contato_cel'].'</td></tr>';
	else {
		
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
		$sql->adCampo('dept_tel');
		$sql->adOnde('usuario_id='.(int)$obj->instrumento_fiscal);
		$dept_tel = $sql->resultado();
		$sql->limpar();
		if ($dept_tel) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_leg'].':'.$dept_tel.'</td></tr>';
		}
	}


if ($exibir['instrumento_fiscal_substituto'] && $obj->instrumento_fiscal_substituto) {
	echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_fiscal_substituto_leg'].':'.link_usuario($obj->instrumento_fiscal_substituto,'','','esquerda').'</td></tr>';
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('contato_email, contato_tel, contato_tel2, contato_cel');
	$sql->adOnde('usuario_id='.(int)$obj->instrumento_fiscal_substituto);
	$linha_contato = $sql->linha();
	$sql->limpar();
		
	if ($linha_contato['contato_email']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'E-mail '.$exibir['instrumento_fiscal_substituto_leg'].':'.$linha_contato['contato_email'].'</td></tr>';
	if ($linha_contato['contato_tel']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_substituto_leg'].':'.$linha_contato['contato_tel'].'</td></tr>';
	else if ($linha_contato['contato_tel2']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_substituto_leg'].':'.$linha_contato['contato_tel2'].'</td></tr>';
	else if ($linha_contato['contato_cel']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Celular '.$exibir['instrumento_fiscal_substituto_leg'].':'.$linha_contato['contato_cel'].'</td></tr>';
	else {
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
		$sql->adCampo('dept_tel');
		$sql->adOnde('usuario_id='.(int)$obj->instrumento_fiscal_substituto);
		$dept_tel = $sql->resultado();
		$sql->limpar();
		if ($dept_tel) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.'Telefone '.$exibir['instrumento_fiscal_substituto_leg'].':'.$dept_tel.'</td></tr>';
		}
	}




if ($exibir['instrumento_demandante']) echo '</table></fieldset></td></tr>';

if ($obj->instrumento_prazo_prorrogacao || ($obj->instrumento_acrescimo > 0) || ($obj->instrumento_supressao > 0)){
	$numeracao=0;
	if ($exibir['instrumento_adtivo']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;" align=left>'.++$numeracao_titulo.'. '.$exibir['instrumento_adtivo_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
	$prorrogacao_tipo=array(0=>'dias', 1=>'meses', 2=>'anos');
	if ($exibir['instrumento_prazo_prorrogacao'] && $obj->instrumento_prazo_prorrogacao) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_prazo_prorrogacao_leg'].':'.$obj->instrumento_prazo_prorrogacao.' '.(isset($prorrogacao_tipo[$obj->instrumento_prazo_prorrogacao_tipo]) ? $prorrogacao_tipo[$obj->instrumento_prazo_prorrogacao_tipo] :'').'</td></tr>';
	if ($exibir['instrumento_acrescimo'] && $obj->instrumento_acrescimo) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_acrescimo_leg'].':'.number_format($obj->instrumento_acrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	if ($exibir['instrumento_supressao'] && $obj->instrumento_supressao) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_supressao_leg'].':'.number_format($obj->instrumento_supressao,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	if ($exibir['instrumento_adtivo']) echo '</table></fieldset></td></tr>';
	}

$data_texto = new CData();
$numeracao=0;
$linhas=null;


$total_acrescimo=0;

if ($exibir['instrumento_avulso_custo']){
	$saida=desenhaPlanilhaCustos((int)$instrumento_id, $exibir);
	if ($saida || ($exibir['instrumento_avulso_custo_acrescimo'] && $total_acrescimo) || ($exibir['instrumento_local_entrega'] && $obj->instrumento_local_entrega)) {
		echo '<tr><td colspan=2 align="right"><fieldset><legend class=texto style="color: black; font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_avulso_custo_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
		if ($saida) echo '<tr><td align="left" style="white-space: nowrap; font-size:12pt;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Itens', 'Itens do serviço/entrega de materiais d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Itens:'.dicaF().'</td><td>'.$saida.'</td></tr>';
		echo '</div></td></tr>';
		if ($exibir['instrumento_avulso_custo_acrescimo']) echo '<tr><td align=left colspan=2 style="white-space: nowrap; font-size:12pt;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Valor Total do Contrato com Acréscimo', 'Soma do valor do contrato ascrescentado da planilha de custo dos itens d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Total com acréscimo:'.dicaF().''.number_format($obj->instrumento_valor+$total_acrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
		if ($exibir['instrumento_local_entrega'] && $obj->instrumento_local_entrega) echo '<tr><td colspan=2 style="font-size:12pt;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Local de Prestação do Serviço/Entrega de Materiais', 'Preencha neste campo o local de prestação do serviço/entrega de materiais d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Local de entrega:'.dicaF().''.$obj->instrumento_local_entrega.'</td></tr>';
		echo '</table></fieldset></td></tr>';
		}
	}






$numeracao=0;
if ($obj->instrumento_objeto || $obj->instrumento_justificativa || $obj->instrumento_resultado_esperado || $obj->instrumento_situacao_atual || $obj->instrumento_vantagem_economica){
	if ($exibir['instrumento_detalhamento']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_detalhamento_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
	if ($exibir['instrumento_objeto'] && $obj->instrumento_objeto) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_objeto_leg'].':'.$obj->instrumento_objeto.'</td></tr>';
	if ($exibir['instrumento_justificativa'] && $obj->instrumento_justificativa) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_justificativa_leg'].':'.$obj->instrumento_justificativa.'</td></tr>';
	if ($exibir['instrumento_resultado_esperado'] && $obj->instrumento_resultado_esperado) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_resultado_esperado_leg'].':'.$obj->instrumento_resultado_esperado.'</td></tr>';
	if ($exibir['instrumento_situacao_atual'] && $obj->instrumento_situacao_atual) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_situacao_atual_leg'].':'.$obj->instrumento_situacao_atual.'</td></tr>';
	if ($exibir['instrumento_vantagem_economica'] && $obj->instrumento_vantagem_economica) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_vantagem_economica_leg'].':'.$obj->instrumento_vantagem_economica.'</td></tr>';
	if ($exibir['instrumento_detalhamento']) echo '</table></fieldset></td></tr>';
	}



$numeracao=0;
$financeiros=null;
if ($exibir['instrumento_financeiro']){ 
if ($obj->instrumento_id) {
		$sql->adTabela('instrumento_financeiro');
		$sql->adOnde('instrumento_financeiro_instrumento = '.(int)$obj->instrumento_id);
		$sql->adCampo('instrumento_financeiro.*');
		$sql->adOrdem('instrumento_financeiro_ordem');
		$financeiros=$sql->ListaChave('instrumento_financeiro_id');
		$sql->limpar();
		}
	}
	
if ($exibir['instrumento_financeiro'] && is_array($financeiros) && count($financeiros)){ 

	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_financeiro_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
	
	
	if ($obj->instrumento_id) {
		$sql->adTabela('instrumento_financeiro');
		$sql->adOnde('instrumento_financeiro_instrumento = '.(int)$obj->instrumento_id);
		$sql->adCampo('instrumento_financeiro.*');
		$sql->adOrdem('instrumento_financeiro_ordem');
		$financeiros=$sql->ListaChave('instrumento_financeiro_id');
		$sql->limpar();
		}
	else $financeiros=null;
	
	echo '<tr><td colspan=2><div id="combo_financeiro">';
	if (is_array($financeiros) && count($financeiros)) {
		$instrumentoFonte = getSisValor('instrumento_fonte');
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr>';
		echo '<td style="font-size:12pt; font-weight:bold;" align=center>'.$exibir['instrumento_financeiro_projeto_leg'].'</td>';
		if ($exibir['instrumento_financeiro_tarefa']) echo '<td style="font-size:12pt; font-weight:bold;" align=center>'.$exibir['instrumento_financeiro_tarefa_leg'].'</td>';
		if ($exibir['instrumento_financeiro_fonte']) echo '<td style="font-size:12pt; font-weight:bold;" align=center>'.$exibir['instrumento_financeiro_fonte_leg'].'</td>';
		if ($exibir['instrumento_financeiro_regiao']) echo '<td style="font-size:12pt; font-weight:bold;" align=center>'.$exibir['instrumento_financeiro_regiao_leg'].'</td>';
		if ($exibir['instrumento_financeiro_classificacao']) echo '<td style="font-size:12pt; font-weight:bold;" align=center>'.$exibir['instrumento_financeiro_classificacao_leg'].'</td>';
		echo '<td style="font-size:12pt; font-weight:bold;" align=center>'.dica('Valor(R$)', 'Valor a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Valor(R$)'.dicaF().'</td>';
		echo '<td style="font-size:12pt; font-weight:bold;" align=center>'.dica('Ano', 'Ano a ser incluído n'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Ano'.dicaF().'</td>';
		echo '</tr>';
		foreach ($financeiros as $instrumento_financeiro_id => $financeiro) {
			echo '<tr>';
			echo '<td align="left" style="font-size:12pt;">'.$financeiro['instrumento_financeiro_projeto'].'</td>';
			if ($exibir['instrumento_financeiro_tarefa']) echo '<td align="left" style="font-size:12pt;">'.$financeiro['instrumento_financeiro_tarefa'].'</td>';
			
			//if ($exibir['instrumento_financeiro_fonte']) echo '<td align="left" style="font-size:12pt;">'.(isset($instrumentoFonte[$financeiro['instrumento_financeiro_fonte']]) ? $instrumentoFonte[$financeiro['instrumento_financeiro_fonte']] : '').'</td>';
			if ($exibir['instrumento_financeiro_fonte']) echo '<td align="left" style="font-size:12pt;">'.$financeiro['instrumento_financeiro_fonte'].'</td>';
			
			
			if ($exibir['instrumento_financeiro_regiao']) echo '<td align="left" style="font-size:12pt;">'.$financeiro['instrumento_financeiro_regiao'].'</td>';
			if ($exibir['instrumento_financeiro_classificacao']) echo '<td align="left" style="font-size:12pt;">'.$financeiro['instrumento_financeiro_classificacao'].'</td>';
			echo '<td align="right" style="font-size:12pt;">'.number_format($financeiro['instrumento_financeiro_valor'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
			echo '<td align="left" style="font-size:12pt;">'.$financeiro['instrumento_financeiro_ano'].'</td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	
	echo '</div></td></tr>';
	
	
	echo '</table></fieldset></td></tr>';
	}

if ($obj->instrumento_data_celebracao || $obj->instrumento_data_inicio || $obj->instrumento_data_termino || $obj->instrumento_data_publicacao){
	$numeracao=0;
	if ($exibir['instrumento_datas']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_datas_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
	if ($exibir['instrumento_data_celebracao'] && $obj->instrumento_data_celebracao) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_celebracao_leg'].':'.retorna_data($obj->instrumento_data_celebracao, false).'</td></tr>';
	if ($exibir['instrumento_data_inicio'] && $obj->instrumento_data_inicio) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_inicio_leg'].':'.retorna_data($obj->instrumento_data_inicio, false).'</td></tr>';
	if ($exibir['instrumento_data_termino'] && $obj->instrumento_data_termino) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_termino_leg'].':'.retorna_data($obj->instrumento_data_termino, false).'</td></tr>';
	if ($exibir['instrumento_data_publicacao'] && $obj->instrumento_data_publicacao) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_data_publicacao_leg'].':'.retorna_data($obj->instrumento_data_publicacao, false).'</td></tr>';
	if ($exibir['instrumento_datas']) echo '</table></fieldset></td></tr>';
	}
	
if ($obj->instrumento_garantia_contratual_modalidade || $obj->instrumento_garantia_contratual_percentual || $obj->instrumento_garantia_contratual_vencimento){
	$numeracao=0;
	if ($exibir['instrumento_garantia_contratual']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_garantia_contratual_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
	if ($exibir['instrumento_garantia_contratual_modalidade'] && $obj->instrumento_garantia_contratual_modalidade) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. Modalidade escolhida:'.$obj->instrumento_garantia_contratual_modalidade.'</td></tr>';
	if ($exibir['instrumento_garantia_contratual_percentual'] && $obj->instrumento_garantia_contratual_percentual) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. Percentual(%):'.number_format($obj->instrumento_garantia_contratual_percentual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td></tr>';
	if ($exibir['instrumento_garantia_contratual_vencimento'] && $obj->instrumento_garantia_contratual_vencimento) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. Vencimento:'.retorna_data($obj->instrumento_garantia_contratual_vencimento, false).'</td></tr>';
	if ($exibir['instrumento_garantia_contratual']) echo '</table></fieldset></td></tr>';
	}	
	
	
if ($obj->instrumento_licitacao || $obj->instrumento_edital_nr || $obj->instrumento_processo){	
	$numeracao=0;
	if ($exibir['instrumento_protocolo']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_protocolo_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
	if ($exibir['instrumento_licitacao'] && $obj->instrumento_licitacao) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_licitacao_leg'].':'.getSisValorCampo('ModalidadeLicitacao',$obj->instrumento_licitacao).'</td></tr>';
	if ($exibir['instrumento_edital_nr'] && $obj->instrumento_edital_nr) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_edital_nr_leg'].':'.$obj->instrumento_edital_nr.'</td></tr>';
	if ($exibir['instrumento_processo'] && $obj->instrumento_processo) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_processo_leg'].':'.$obj->instrumento_processo.'</td></tr>';
	if ($exibir['instrumento_protocolo']) echo '</table></fieldset></td></tr>';
	}

$numeracao=0;
if ($exibir['instrumento_dados']) echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;font-size:12pt;">'.++$numeracao_titulo.'. '.$exibir['instrumento_dados_leg'].'</legend><table cellspacing=0 cellpadding=0 width=100%>';
if ($exibir['instrumento_cor']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_cor_leg'].':'.$obj->instrumento_cor.'</td></tr>';
$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');
if ($exibir['instrumento_acesso']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Nível de Acesso', ucfirst($config['genero_instrumento']).' '.$config['instrumento'].' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante I</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Participantes II</b> - Somente o responsável e os designados podem ver e apenas o responsável pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o responsável  e os designados podem ver, e o responsável editar.</li></ul>').'Nível de Acesso'.dicaF().':'.(isset($niveis_acesso[$obj->instrumento_acesso]) ? $niveis_acesso[$obj->instrumento_acesso] : '').'</td></tr>';
for($i=0; $i<=100; $i++) $percentual[$i]=$i;
if ($exibir['instrumento_porcentagem']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_porcentagem_leg'].':'.(isset($percentual[$obj->instrumento_porcentagem]) ? $percentual[$obj->instrumento_porcentagem].'%' : '').'</td></tr>';
if ($exibir['instrumento_contatos']) {
	$sql->adTabela('instrumento_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$instrumento_contatos = $sql->carregarColuna();
	$sql->limpar();
	$saida_quem='';
	if ($instrumento_contatos && count($instrumento_contatos)) {
			$saida_quem.= link_contato($instrumento_contatos[0], '','','esquerda');
			$qnt_instrumento_contatos=count($instrumento_contatos);
			if ($qnt_instrumento_contatos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_instrumento_contatos; $i < $i_cmp; $i++) $lista.=link_contato($instrumento_contatos[$i], '','','esquerda').'<br>';		
					$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'instrumento_contatos\');">(+'.($qnt_instrumento_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="instrumento_contatos"><br>'.$lista.'</span>';
					}
			} 
	if($saida_quem)echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Contatos', 'Quais são os contatos d'.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Contato:'.dicaF().''.$saida_quem.'</td></tr>';
	}
if ($exibir['instrumento_recursos']) {
	$sql->adTabela('instrumento_recursos');
	$sql->adCampo('DISTINCT recurso_id');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$instrumento_recursos = $sql->carregarColuna();
	$sql->limpar();
	$saida_recurso='';
	if ($instrumento_recursos && count($instrumento_recursos)) {

			$saida_recurso.= link_recurso($instrumento_recursos[0]);
			$qnt_lista_recursos=count($instrumento_recursos);
			if ($qnt_lista_recursos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_recursos; $i < $i_cmp; $i++) $lista.=link_recurso($instrumento_recursos[$i]).'<br>';		
					$saida_recurso.= dica('Outros Indicadores', 'Clique para visualizar os demais recursos.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_recursos\');">(+'.($qnt_lista_recursos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_recursos"><br>'.$lista.'</span>';
					}
			} 
	if ($saida_recurso) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Recurso', 'Qual recurso está relacionado à '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.').'Recurso:'.dicaF().''.$saida_recurso.'</td></tr>';
	}
if ($exibir['instrumento_relacionados']){
	$sql->adTabela('instrumento_gestao');
	$sql->adCampo('instrumento_gestao.*');
	$sql->adOnde('instrumento_gestao_instrumento ='.(int)$instrumento_id);	
	$sql->adOrdem('instrumento_gestao_ordem');
	$lista = $sql->Lista();
	$sql->limpar();
	$qnt_gestao=0;	
	if (count($lista)) {
		echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Relacionad'.$config['genero_instrumento'], 'A que área '.$config['genero_instrumento'].' '.$config['instrumento'].' está relacionad'.$config['genero_instrumento'].'.').'Relacionad'.$config['genero_instrumento'].':'.dicaF().'';	
		foreach($lista as $gestao_data){
			if ($gestao_data['instrumento_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']);
			elseif ($gestao_data['instrumento_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']);
			elseif ($gestao_data['instrumento_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']);
			elseif ($gestao_data['instrumento_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']);
			elseif ($gestao_data['instrumento_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']);
			elseif ($gestao_data['instrumento_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']);
			elseif ($gestao_data['instrumento_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']);
			elseif ($gestao_data['instrumento_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']);
			elseif ($gestao_data['instrumento_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']);
			elseif ($gestao_data['instrumento_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']);
			elseif ($gestao_data['instrumento_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']);
			elseif ($gestao_data['instrumento_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']);
			elseif ($gestao_data['instrumento_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']);
			elseif ($gestao_data['instrumento_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']);
			elseif ($gestao_data['instrumento_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']);
			elseif ($gestao_data['instrumento_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']);
			elseif ($gestao_data['instrumento_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']);
			elseif ($gestao_data['instrumento_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['instrumento_gestao_mswot']);
			elseif ($gestao_data['instrumento_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']);
			elseif ($gestao_data['instrumento_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']);
			
			elseif ($gestao_data['instrumento_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['instrumento_gestao_semelhante']);
			
			elseif ($gestao_data['instrumento_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']);
			elseif ($gestao_data['instrumento_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['instrumento_gestao_problema']);
			elseif ($gestao_data['instrumento_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']);	
			elseif ($gestao_data['instrumento_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']);
			elseif ($gestao_data['instrumento_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']);
			elseif ($gestao_data['instrumento_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']);
			elseif ($gestao_data['instrumento_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']);
			elseif ($gestao_data['instrumento_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']);
			elseif ($gestao_data['instrumento_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']);
			elseif ($gestao_data['instrumento_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['instrumento_gestao_brainstorm']);
			elseif ($gestao_data['instrumento_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['instrumento_gestao_gut']);
			elseif ($gestao_data['instrumento_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['instrumento_gestao_causa_efeito']);
			elseif ($gestao_data['instrumento_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']);
			elseif ($gestao_data['instrumento_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']);
			elseif ($gestao_data['instrumento_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']);
			elseif ($gestao_data['instrumento_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']);
			elseif ($gestao_data['instrumento_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']);
			elseif ($gestao_data['instrumento_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']);
			elseif ($gestao_data['instrumento_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['instrumento_gestao_template']);
			elseif ($gestao_data['instrumento_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['instrumento_gestao_painel']);
			elseif ($gestao_data['instrumento_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']);
			elseif ($gestao_data['instrumento_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']);		
			elseif ($gestao_data['instrumento_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']);	
			elseif ($gestao_data['instrumento_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['instrumento_gestao_me']);	
			elseif ($gestao_data['instrumento_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['instrumento_gestao_acao_item']);	
			elseif ($gestao_data['instrumento_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['instrumento_gestao_beneficio']);	
			elseif ($gestao_data['instrumento_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['instrumento_gestao_painel_slideshow']);	
			elseif ($gestao_data['instrumento_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['instrumento_gestao_projeto_viabilidade']);	
			elseif ($gestao_data['instrumento_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['instrumento_gestao_projeto_abertura']);	
			elseif ($gestao_data['instrumento_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['instrumento_gestao_plano_gestao']);	
			elseif ($gestao_data['instrumento_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['instrumento_gestao_ssti']);
			elseif ($gestao_data['instrumento_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['instrumento_gestao_laudo']);
			elseif ($gestao_data['instrumento_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['instrumento_gestao_trelo']);
			elseif ($gestao_data['instrumento_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['instrumento_gestao_trelo_cartao']);
			elseif ($gestao_data['instrumento_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['instrumento_gestao_pdcl']);
			elseif ($gestao_data['instrumento_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['instrumento_gestao_pdcl_item']);	
			elseif ($gestao_data['instrumento_gestao_os']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/os_p.png').link_os($gestao_data['instrumento_gestao_os']);	
			}
		echo '</td></tr>';
		}	
	}
	
if ($exibir['instrumento_principal_indicador'] && $obj->instrumento_principal_indicador) echo '<tr><td align="right"  style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_principal_indicador_leg'].':'.link_indicador($obj->instrumento_principal_indicador).'</td></tr>';
if ($Aplic->profissional) {
	if (isset($moedas[$obj->instrumento_moeda])) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Moeda', 'A moeda padrão utilizada na meta.').'Moeda:'.dicaF().''.$moedas[$obj->instrumento_moeda].'</td></tr>';	
	}
if ($exibir['instrumento_aprovado'] && $Aplic->profissional && $tem_aprovacao) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.dica('Aprovado', 'Se '.$config['genero_instrumento'].' '.$config['instrumento'].' se encontra aprovad'.$config['genero_instrumento'].'.').'Aprovad'.$config['genero_instrumento'].':'.dicaF().''.($obj->instrumento_aprovado ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';
if ($exibir['instrumento_ativo']) echo '<tr><td align=left style="font-size:12pt; vertical-align:top;">'.$numeracao_titulo.'.'.++$numeracao.'. '.$exibir['instrumento_ativo_leg'].':'.($obj->instrumento_ativo ? 'Sim' : 'Não').'</td></tr>';
if ($exibir['instrumento_dados']) echo '</table></fieldset></td></tr>';


















require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('instrumento', $instrumento_id, 'ver', 0, 'style="font-size:12pt; vertical-align:top; align:right;"', 'style="font-size:12pt; vertical-align:top; white-space: nowrap; text-align:right;');

if ($campos_customizados->count()) {
		echo '<tr><td><table cellspacing=0 cellpadding=0 width=100%>';
		$campos_customizados->imprimirHTML();
		echo '</table></td></tr>';
		}		




$sql->adTabela('cias');
$sql->esqUnir('municipios','municipios','municipio_id=cia_cidade');
$sql->adCampo('concatenar_tres(municipio_nome, \'-\', estado_sigla)');
$sql->adOnde('cia_id = '.$obj->instrumento_cia);
$municipio =$sql->resultado();
$sql->limpar();

echo '<tr><td align=right style="font-size:12pt; vertical-align:top;" colspan=20>'.($municipio ? $municipio.', ' : '').retorna_data_extenso(date('Y-m-d')).'</td></tr>';

$saida='';
$sql->adTabela('assinatura_atesta');
$sql->adCampo('assinatura_atesta_id, assinatura_atesta_nome');
$sql->adOnde('assinatura_atesta_instrumento=1');
$sql->adOrdem('assinatura_atesta_ordem');
$atesta_vetor = $sql->listaVetorChave('assinatura_atesta_id', 'assinatura_atesta_nome');
$sql->limpar();

$assinatura=getParam($_REQUEST, 'assinatura', 0);

$sql->adTabela('instrumento_config');
$sql->adCampo('instrumento_config.*');
$sql->adOnde('instrumento_config_id=1');
$configuracao = $sql->linha();
$sql->limpar();

$sql->adTabela('assinatura');
$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=assinatura_usuario');
$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
$sql->adCampo('IF(tamanho_caractere(contato_nomecompleto)>10 , '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomecompleto)' : 'contato_nomecompleto').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS nome, contato_funcao');
$sql->adCampo('assinatura_atesta, assinatura_usuario, assinatura_funcao, assinatura_atesta_opcao_nome, assinatura_data, formatar_data(assinatura_data, \'%d/%m/%Y\') AS data, assinatura_observacao, assinatura_data');		
$sql->adOnde('assinatura_instrumento = '.(int)$obj->instrumento_id);
$sql->adOrdem('assinatura_ordem');
$lista = $sql->lista();
$sql->limpar();	


$col=0;	
foreach ($lista as $linha) {
	$bloco='<table cellpadding="0" cellspacing="0">';
	$sql->adTabela('usuarios');
	$sql->adCampo('usuario_assinatura_nome, usuario_assinatura_local');
	$sql->adOnde('usuario_id = '.(int)$linha['assinatura_usuario']);
	$caminho = $sql->linha();
	$sql->limpar();
	$col++;
	
	
	if ($linha['assinatura_data']) $bloco.=($assinatura && $caminho['usuario_assinatura_nome'] && file_exists($base_dir.'/arquivos/'.$caminho['usuario_assinatura_local'].$caminho['usuario_assinatura_nome']) ? '<tr><td valign="bottom" align=center style="height:80px; font-size:12pt;"><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/'.$caminho['usuario_assinatura_local'].$caminho['usuario_assinatura_nome'].'" /></td></tr>' : '<tr><td valign="bottom" align=center style="height:80px; font-size:12pt;"></br></br>________________________________________</td></tr>');	
	else $bloco.='<tr><td valign="bottom" align=center style="height:80px; font-size:12pt;"></br></br>________________________________________</td></tr>';
	$bloco.='<tr><td align=center style="font-size:12pt;">'.$linha['nome'].'</td></tr>';
	
	if ($configuracao['instrumento_config_exibe_funcao']) $bloco.='<tr><td align=center style="font-size:12pt;">'.($linha['assinatura_funcao'] ? $linha['assinatura_funcao'] : (isset($atesta_vetor[$linha['assinatura_atesta']]) ? $atesta_vetor[$linha['assinatura_atesta']] : $linha['contato_funcao'])).'</td></tr>';
	
	if ($linha['assinatura_data']) $bloco.='<tr><td align=center style="font-size:12pt;">'.$linha['data'].'</td></tr>';
	
	if ($linha['assinatura_atesta_opcao_nome']) $bloco.='<tr><td align=left style="font-size:12pt;">'.$linha['assinatura_atesta_opcao_nome'].'</td></tr>';
	
	
	if ($linha['assinatura_observacao']) $bloco.='<tr><td align=left style="font-size:12pt;">'.$linha['assinatura_observacao'].'</td></tr>';
	$bloco.='</table>';
	if ($col==1) $saida.='<tr><td><table cellpadding="0" cellspacing="0" width="100%"><tr><td width="50%" align="center" valign=top>'.$bloco.'</td>';
	else $saida.='<td width="50%" align="center" valign=top>'.$bloco.'</td></tr></table></td></tr>';
	if ($col==2)$col=0; 
	}
if ($saida && $col==1) $saida.='<td>&nbsp;</td></tr></table></td></tr>';
echo '<table cellpadding="0" cellspacing="0" width="100%">'.$saida.'</table>';





echo '</table>';

function desenhaPlanilhaCustos($instrumento_id, $exibir){
    global $estilo_interface, $dialogo, $config, $estilo_texto, $estilo, $obj;

    $custosAVulso = loadCustoAVulso($instrumento_id);
    $custosTR = loadCustosOSTR($instrumento_id);

    //tem aditivo?
    $sql = new BDConsulta();
    $sql->adTabela('instrumento_avulso_custo');
    $sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
    $sql->esqUnir('instrumento_gestao', 'instrumento_gestao', 'instrumento_custo_instrumento=instrumento_gestao_instrumento');
    $sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END AS valor');
    $sql->adOnde('instrumento_gestao_semelhante ='.(int)$instrumento_id);
    $tem_aditivo=$sql->resultado();
    $sql->limpar();


    if(empty($custosTR) && empty($custosAVulso) && !($tem_aditivo > 0)) return;

    $desenho= '<table width="100%" cellpadding=2 cellspacing=0 class="tbl1" style="margin-top: 6px;">';
    $desenho.= '<thead>';
    $desenho.= '<th '.$estilo_texto.'>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th><th '.$estilo_texto.'>' . dica('Descrição','Descrição do item.') . 'Descrição' . dicaF() . '</th>';
    $desenho.= '<th '.$estilo_texto.'>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF() . '</th>';
    $desenho.= '<th '.$estilo_texto.'>'.dica('Valor Unitário', 'O valor de uma unidade do item.').'Valor Unit.'.dicaF().'</th>';
    $desenho.= '<th '.$estilo_texto.'>'.dica('Valor Unitário Atualizado', 'O valor de uma unidade do item atualizado.').'Unit. Atual'.dicaF().'</th>';
    $desenho.= '<th '.$estilo_texto.'>'.dica('Quantidade', 'A quantidade do ítem' ).'Qnt.'.dicaF().'</th>';
    $desenho.= '<th '.$estilo_texto.'>'.dica('Quantidade de meses', 'A quantidade de meses de serviço do ítem' ). 'Meses'.dicaF().'</th>';
    if( $config['bdi'] ) $desenho.= '<th '.$estilo_texto.'>' . dica('BDI','Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.') . 'BDI (%)' . dicaF() . '</th>';

    if($exibir['instrumento_avulso_custo_acrescimo']){
        $desenho.= '<th '.$estilo_texto.'>'.($exibir['instrumento_avulso_custo_percentual'] ? $exibir['instrumento_avulso_custo_acrescimo_leg2'] : $exibir['instrumento_avulso_custo_acrescimo_leg']). '</th>';
        $desenho.= '<th '.$estilo_texto.'>'.dica('Valor Total com Acréscimo', 'O valor total é o preço unitário multiplicado pela quantidade e pelo acréscimo.').'Total com Acréscimo'.dicaF(). '</th>';
    }
    else {
        $desenho.= '<th '.$estilo_texto.'>'. dica( 'Valor Total', 'O valor total é o preço unitário multiplicado pela quantidade.' ). 'Total'. dicaF(). '</th>';
    }

    $desenho.= '<th '.$estilo_texto.'>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th>';
    $desenho.= '</tr></thead>';
    $desenho.= '<tbody id="instrumento_lista_itens">';
    $tem_total = false;
    $custo = array();
    $total = array();
    $desenho.=desenhaCustosInstrumentoTR($custosTR, $custo, $total, $tem_total, $exibir);
    $desenho.=desenhaCustosAVulso($custosAVulso, $custo, $total, $tem_total, $exibir);
    $desenho.=desenhaAditivo($instrumento_id, $custo, $total, $tem_total, $exibir);

    if ($tem_total) $desenho.=desenhaTotaisPlanilhaCusto($custo, $total, 'Total Final', $exibir);

    $desenho.='</tbody></table>';
    return $desenho;
}

function desenhaCustosInstrumentoTR($lista, &$custo, &$total, &$tem_total, $exibir) {
    global $config, $moedas, $estilo_texto, $estilo, $instrumento_id, $obj;

    $totalLocal=array();
    $custoLocal=array();

    if (($lista)) $saida= '<tr><td colspan=20 align="center" '.$estilo_texto.'><b>'.ucfirst($config['tr']).'</b></td></tr>';
    else $saida=null;
    $numeroLinha = 0;
    $trAtual = null;
    foreach( $lista as $linha ) {
        $quantidade = (float) $linha['instrumento_custo_quantidade'];
        if( (int) $linha['tr_custo_avulso'] ) {
            $tipo = 'travulso';
            $nome = $linha['tr_avulso_custo_nome'];
            $descricao = $linha['tr_avulso_custo_descricao'];
            $valorUnitario = (float) $linha['tr_avulso_custo_custo'];
            $valorUnitarioAtual = (float) $linha['tr_avulso_custo_custo_atual'];
            $valorAcrescimo = null;
            $bdi = (float) $linha['tr_avulso_custo_bdi'];
            $data = new CData($linha['tr_avulso_custo_data_limite']);
            $nd = $linha['tr_avulso_custo_nd'];
            $categoriaEconomica = $linha['tr_avulso_custo_categoria_economica'];
            $grupoDespesa = $linha['tr_avulso_custo_grupo_despesa'];
            $modalidadeAplicacao = $linha['tr_avulso_custo_modalidade_aplicacao'];
            $moeda = $linha['tr_avulso_custo_moeda'];
            $responsavel = $linha['responsavel_travulsocusto'];
            $quantidadeMeses = $linha['tr_avulso_custo_servico'] == 1 ? (float) $linha['tr_avulso_custo_meses'] : null;
            $servico=$linha['tr_avulso_custo_servico'];
        }
        else if( (int) $linha['tr_custo_tarefa'] ) {
            $tipo = 'tarefa';
            $nome = $linha['tarefa_custos_nome'];
            $descricao = $linha['tarefa_custos_descricao'];
            $valorUnitario = (float) $linha['tarefa_custos_custo'];
            $valorUnitarioAtual = null;
            $valorAcrescimo = null;
            $bdi = (float) $linha['tarefa_custos_bdi'];
            $data = new CData($linha['tarefa_custos_data_limite']);
            $nd = $linha['tarefa_custos_nd'];
            $categoriaEconomica = $linha['tarefa_custos_categoria_economica'];
            $grupoDespesa = $linha['tarefa_custos_grupo_despesa'];
            $modalidadeAplicacao = $linha['tarefa_custos_modalidade_aplicacao'];
            $moeda = $linha['tarefa_custos_moeda'];
            $responsavel = $linha['responsavel_tarefacusto'];
            $quantidadeMeses = null;
            $servico=null;
        }
        else { //demanda
            $tipo = 'demanda';
            $nome = $linha['demanda_custo_nome'];
            $descricao = $linha['demanda_custo_descricao'];
            $valorUnitario = (float) $linha['demanda_custo_custo'];
            $valorUnitarioAtual = null;
            $valorAcrescimo = null;
            $bdi = (float) $linha['demanda_custo_bdi'];
            $data = new CData($linha['demanda_custo_data_limite']);
            $nd = $linha['demanda_custo_nd'];
            $categoriaEconomica = $linha['demanda_custo_categoria_economica'];
            $grupoDespesa = $linha['demanda_custo_grupo_despesa'];
            $modalidadeAplicacao = $linha['demanda_custo_modalidade_aplicacao'];
            $moeda = $linha['demanda_custo_moeda'];
            $responsavel = $linha['responsavel_demandacusto'];
            $quantidadeMeses = null;
            $servico=null;
        }

        $custoId = (int) $linha['tr_custo_id'];
        $valorTotal = ( ( $quantidade * ($valorUnitarioAtual > 0 ? $valorUnitarioAtual : $valorUnitario) ) * ( ( 100 + $bdi ) / 100 ) );

        $valorTotal = ($servico ? $quantidadeMeses * $valorTotal : $valorTotal);


        if( $quantidadeMeses !== null ) $valorTotal *= $quantidadeMeses;
        if( $valorTotal > 0 ) $tem_total = true;
        $saida.= '<tr>';
        if( $trAtual != $linha['tr_id'] ) {
            $trAtual = $linha['tr_id'];
            $saida .= '<td colspan=20 '.$estilo_texto.'><b>'.$linha['tr_nome'] . '</b></td></tr><tr>';
        }
        $saida .= '<td align="left" '.$estilo_texto.'>' . ++$numeroLinha . ' - '.$nome . '</td>';
        $saida .= '<td align="left" '.$estilo_texto.'>' . ( $descricao ? $descricao : '&nbsp;' ) . '</td>';
        $nd = ( $categoriaEconomica && $grupoDespesa && $modalidadeAplicacao ? $categoriaEconomica . '.'.$grupoDespesa . '.'.$modalidadeAplicacao . '.' : '' ) . $nd;
        $saida .= '<td '.$estilo_texto.'>'.$nd . '</td>';
        $moedaTexto = array_key_exists( $moeda, $moedas ) ? $moedas[ $moeda ] : '&nbsp;';
        $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $valorUnitario,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
        $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'.($valorUnitarioAtual > 0 ? $moedaTexto.' '.number_format($valorUnitarioAtual,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '') . '</td>';
        $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">' . number_format( $quantidade,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
        $saida .= '<td align=right '.$estilo_texto.'>' . ( $quantidadeMeses !== null ? $quantidadeMeses : '&nbsp;' ) . '</td>';
        if( $config['bdi'] ) $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">' . number_format( $bdi,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';

        if($exibir['instrumento_avulso_custo_acrescimo']){
            $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'.($valorAcrescimo !== null ? number_format($valorAcrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') : '') . '</td>';
        }

        $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $valorTotal,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
        $saida .= '<td '.$estilo_texto.'>'.$responsavel . '</td>';
        $saida .= '</tr>';
        if( isset( $custo[ $moeda ][ $nd ] ) ) $custo[ $moeda ][ $nd ] += (float) $valorTotal;
        else $custo[ $moeda ][ $nd ] = (float) $valorTotal;

        if( isset( $total[ $moeda ] ) ) $total[ $moeda ] += $valorTotal;
        else $total[ $moeda ] = $valorTotal;

        if( isset($custoLocal[$moeda][$nd])) $custoLocal[$moeda][$nd] += (float) $valorTotal;
        else $custoLocal[$moeda][$nd] = (float) $valorTotal;

        if( isset( $totalLocal[$moeda])) $totalLocal[$moeda] += $valorTotal;
        else $totalLocal[$moeda] = $valorTotal;
    }
    $saida.=desenhaTotaisPlanilhaCusto($custoLocal, $totalLocal, 'Total Parcial', $exibir);
    return $saida;
}

function desenhaAditivo($instrumento_id, &$custo, &$total, &$tem_total, $exibir) {
    global $config, $moedas, $estilo_texto, $estilo, $instrumento_id, $numeroLinha, $obj;
    $saida=null;
    $totalLocal=array();
    $custoLocal=array();

    $sql = new BDConsulta();

    $sql->adTabela('instrumento_gestao');
    $sql->adCampo('instrumento_gestao_instrumento');
    $sql->adOnde('instrumento_gestao_semelhante ='.(int)$instrumento_id);
    $aditivos=$sql->carregarColuna();
    $sql->limpar();

    $primeriro=true;

    foreach ($aditivos as $aditivo){
        $sql->adTabela('instrumento_avulso_custo');
        $sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
        $sql->adCampo('instrumento_custo_id, instrumento_avulso_custo.*, instrumento_custo_quantidade, instrumento_custo_aprovado');
        $sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END AS valor');
        $sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((instrumento_avulso_custo_acrescimo)/100)) END AS acrescimo');
        $sql->adOnde('instrumento_custo_instrumento ='.(int)$aditivo);
        $sql->adOrdem('instrumento_custo_ordem');
        $linhas=$sql->Lista();
        $sql->limpar();


        if (count($linhas) && $primeriro) {
            $saida .= '<tr><td colspan=20 '.$estilo_texto.'><b>Aditivos</b></td></tr>';
            $primeriro=false;
        }

        if (count($linhas)) $saida .= '<tr><td colspan=20 '.$estilo_texto.'><b>'.nome_instrumento($aditivo).'</b></td></tr>';

        foreach ($linhas as $linha) {

            $saida.= '<tr>';
            $saida.= '<td align="left" '.$estilo_texto.'>' . ++$numeroLinha . ' - '.$linha['instrumento_avulso_custo_nome'] . '</td>';
            $saida.= '<td align="left" '.$estilo_texto.'>' . ( $linha['instrumento_avulso_custo_descricao'] ? $linha['instrumento_avulso_custo_descricao'] : '&nbsp;' ) . '</td>';
            $nd=($linha['instrumento_avulso_custo_categoria_economica'] && $linha['instrumento_avulso_custo_grupo_despesa'] && $linha['instrumento_avulso_custo_modalidade_aplicacao'] ? $linha['instrumento_avulso_custo_categoria_economica'].'.'.$linha['instrumento_avulso_custo_grupo_despesa'].'.'.$linha['instrumento_avulso_custo_modalidade_aplicacao'].'.' : '').$linha['instrumento_avulso_custo_nd'];
            $saida.= '<td '.$estilo_texto.'>'.$nd.'</td>';
            $moedaTexto = array_key_exists( $linha['instrumento_avulso_custo_moeda'], $moedas ) ? $moedas[$linha['instrumento_avulso_custo_moeda']] : '&nbsp;';
            $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $linha['instrumento_avulso_custo_custo'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
            $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.($linha['instrumento_avulso_custo_custo_atual'] > 0 ? ($moedaTexto . ' ' .  number_format( $linha['instrumento_avulso_custo_custo_atual'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.')) : '') . '</td>';
            $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">' . number_format( $linha['instrumento_avulso_custo_quantidade'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';

            $saida.= '<td align=right '.$estilo_texto.'>'.($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses'] : '&nbsp;' ).'</td>';

            if($config['bdi']) $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.number_format($linha['instrumento_avulso_custo_bdi'],2,',','.').'</td>';

            if($exibir['instrumento_avulso_custo_acrescimo']){
                $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">'. number_format($linha['instrumento_avulso_custo_acrescimo'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
            }

            $valorTotal = (($linha['instrumento_avulso_custo_quantidade']*($linha['instrumento_avulso_custo_custo_atual'] > 0 ? $linha['instrumento_avulso_custo_custo_atual'] : $linha['instrumento_avulso_custo_custo']))*((100+$linha['instrumento_avulso_custo_bdi'])/100));
            $valorTotal = ($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']* $valorTotal : $valorTotal);
            $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $valorTotal,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
            $saida.= '<td '.$estilo_texto.'>'.nome_usuario($linha['instrumento_avulso_custo_usuario']).'</td>';
            $saida.= '</tr>';


            if (isset($custo[$linha['instrumento_avulso_custo_moeda']][$nd])) $custo[$linha['instrumento_avulso_custo_moeda']][$nd] += (float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
            else $custo[$linha['instrumento_avulso_custo_moeda']][$nd]=(float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);

            if (isset($total[$linha['instrumento_avulso_custo_moeda']])) $total[$linha['instrumento_avulso_custo_moeda']]+=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
            else $total[$linha['instrumento_avulso_custo_moeda']]=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);


            if (isset($custoLocal[$linha['instrumento_avulso_custo_moeda']][$nd])) $custoLocal[$linha['instrumento_avulso_custo_moeda']][$nd] += (float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
            else $custoLocal[$linha['instrumento_avulso_custo_moeda']][$nd]=(float)($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);

            if (isset($totalLocal[$linha['instrumento_avulso_custo_moeda']])) $totalLocal[$linha['instrumento_avulso_custo_moeda']]+=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);
            else $totalLocal[$linha['instrumento_avulso_custo_moeda']]=($linha['instrumento_avulso_custo_servico'] ? $linha['instrumento_avulso_custo_meses']*$linha['valor'] : $linha['valor']);


            if( $valorTotal > 0) $tem_total = true;


            //checar se está em OS
            $sql->adTabela('os_custo');
            $sql->esqUnir('os', 'os', 'os_custo_os=os_id');
            $sql->adCampo('os_nome, os_custo_quantidade');
            $sql->adOnde('os_custo_instrumento ='.(int)$linha['instrumento_custo_id']);
            $oss=$sql->lista();
            $sql->limpar();
            foreach($oss as $os) $saida.= '<tr><td align="left" colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;'.ucfirst($config['os']).':'.$os['os_nome'].'</td><td align=right>'.number_format($os['os_custo_quantidade'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td><td colspan=20></td></tr>';
        }
    }


    $saida.=desenhaTotaisPlanilhaCusto($custoLocal, $totalLocal, 'Total Parcial', $exibir);
    return $saida;
}

function desenhaCustosAVulso($custosAVulso, &$custo, &$total, &$tem_total, $exibir) {
    global $config, $moedas, $estilo_texto, $estilo, $instrumento_id, $numeroLinha, $obj;
    $saida=null;

    $totalLocal=array();
    $custoLocal=array();

    $sql = new BDConsulta();

    if (count($custosAVulso)) $saida .= '<tr><td colspan=20 '.$estilo_texto.'><b>Itens à vulso</b></td></tr>';

    foreach ($custosAVulso as $linha) {
        $isServico = $linha[ 'instrumento_avulso_custo_servico' ] ? true : false;
        $quantidade = $linha[ 'instrumento_avulso_custo_quantidade' ];
        $parcelas =  $isServico ? $linha['instrumento_avulso_custo_meses'] : 1;
        $valorBDI = $linha[ 'instrumento_avulso_custo_bdi' ];

        $saida.= '<tr>';
        $saida.= '<td align="left" '.$estilo_texto.'>' . ++$numeroLinha . ' - '.$linha['instrumento_avulso_custo_nome'] . '</td>';
        $saida.= '<td align="left" '.$estilo_texto.'>' . ( $linha['instrumento_avulso_custo_descricao'] ? $linha['instrumento_avulso_custo_descricao'] : '&nbsp;' ) . '</td>';
        $nd=($linha['instrumento_avulso_custo_categoria_economica'] && $linha['instrumento_avulso_custo_grupo_despesa'] && $linha['instrumento_avulso_custo_modalidade_aplicacao'] ? $linha['instrumento_avulso_custo_categoria_economica'].'.'.$linha['instrumento_avulso_custo_grupo_despesa'].'.'.$linha['instrumento_avulso_custo_modalidade_aplicacao'].'.' : '').$linha['instrumento_avulso_custo_nd'];
        $saida.= '<td '.$estilo_texto.'>'.$nd.'</td>';
        $tipoMoeda = $linha[ 'instrumento_avulso_custo_moeda' ];
        $moedaTexto = array_key_exists( $tipoMoeda, $moedas ) ? $moedas[ $tipoMoeda ] : '&nbsp;';
        $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $linha['instrumento_avulso_custo_custo'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
        $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.($linha['instrumento_avulso_custo_custo_atual'] > 0 ? ($moedaTexto . ' ' .  number_format( $linha['instrumento_avulso_custo_custo_atual'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.')) : '') . '</td>';

        $saida.= '<td align=right style="white-space: nowrap;' . $estilo . '">' . number_format( $quantidade,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';

        $saida.= '<td align=right ' . $estilo_texto . '>' . ( $isServico ? $parcelas : '&nbsp;' ) . '</td>';


        if( $config[ 'bdi']) $saida.= '<td align=right style="white-space: nowrap;' . $estilo . '">' . number_format($valorBDI,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';

        $valorAcrescimo = 0;

        if($exibir['instrumento_avulso_custo_acrescimo']){
            $valorAcrescimo = $linha['instrumento_avulso_custo_acrescimo'];
            $saida .= '<td align=right style="white-space: nowrap;'.$estilo.'">' . number_format($valorAcrescimo,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.') . '</td>';
        }

        $valorCusto = $linha[ 'instrumento_avulso_custo_custo_atual' ] > 0 ? $linha[ 'instrumento_avulso_custo_custo_atual' ] : $linha[ 'instrumento_avulso_custo_custo' ];

        $valorTotal = ( ( $quantidade * $valorCusto ) * ( ( 100 + $valorBDI ) / 100));
        $valorTotal *= $parcelas;

        if($valorAcrescimo){
            if($linha['instrumento_avulso_custo_percentual']){
                $valorTotal += $valorTotal * ($valorAcrescimo/100);
            }
            else{
                $valorTotal += $valorCusto * $valorAcrescimo;
            }
        }

        $saida.= '<td align=right style="white-space: nowrap;'.$estilo.'">'.$moedaTexto . ' ' . number_format( $valorTotal,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td>';
        $saida.= '<td '.$estilo_texto.'>'.nome_usuario($linha['instrumento_avulso_custo_usuario']).'</td>';
        $saida.= '</tr>';


        if (isset( $custo[ $tipoMoeda ][ $nd]))$custo[ $tipoMoeda ][ $nd] += (float)( $parcelas * $linha[ 'valor']);
        else $custo[ $tipoMoeda ][ $nd]=(float)( $parcelas * $linha[ 'valor']);

        if (isset( $total[ $tipoMoeda ]))$total[ $tipoMoeda ]+=( $isServico
            ? $linha[ 'instrumento_avulso_custo_meses'] * $linha[ 'valor'] : $linha[ 'valor']);
        else $total[ $tipoMoeda ]=( $isServico
            ? $linha[ 'instrumento_avulso_custo_meses'] * $linha[ 'valor'] : $linha[ 'valor']);


        if (isset( $custoLocal[ $tipoMoeda ][ $nd]))$custoLocal[ $tipoMoeda ][ $nd] += (float)( $parcelas * $linha[ 'valor']);
        else $custoLocal[ $tipoMoeda ][ $nd]=(float)($parcelas *  $linha[ 'valor']);

        if (isset( $totalLocal[ $tipoMoeda ]))$totalLocal[ $tipoMoeda ]+=( $parcelas *  $linha[ 'valor']);
        else $totalLocal[ $tipoMoeda ]=( $parcelas *  $linha[ 'valor']);


        if( $valorTotal > 0) $tem_total = true;

        //checar se está em OS
        $sql->adTabela('os_custo');
        $sql->esqUnir('os', 'os', 'os_custo_os=os_id');
        $sql->adCampo('os_nome, os_custo_quantidade');
        $sql->adOnde('os_custo_instrumento ='.(int)$linha['instrumento_custo_id']);
        $oss=$sql->lista();
        $sql->limpar();
        foreach($oss as $os) $saida.= '<tr><td align="left" colspan=5>&nbsp;&nbsp;&nbsp;&nbsp;'.ucfirst($config['os']).':'.$os['os_nome'].'</td><td align=right>'.number_format($os['os_custo_quantidade'],($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.').'</td><td colspan=20></td></tr>';
    }


    $saida.=desenhaTotaisPlanilhaCusto($custoLocal, $totalLocal, 'Total Parcial', $exibir);

    return $saida;
}

function desenhaTotaisPlanilhaCusto($custo, $total, $titulo, $exibir){
    global $moedas, $config, $estilo, $estilo_texto, $obj;
    $saidaND = '';
    $saidaTotal = '';
    foreach( $custo as $tipo_moeda => $linha ) {
        $saidaND .= '<div style="'.$estilo.'">';
        foreach( $linha as $indice_nd => $somatorio ) {
            if( $somatorio > 0 ) $saidaND .= '<br>' . ( $indice_nd ? $indice_nd : 'Sem ND' );
        }
        $saidaND .= '<br><b>Total</b></div>';
        $saidaTotal .= '<div style="white-space: nowrap;'.$estilo.'">';
        foreach( $linha as $indice_nd => $somatorio ) {
            if( $somatorio > 0 ) $saidaTotal .= '<br>'.$moedas[ $tipo_moeda ] . ' ' . number_format( $somatorio,($obj->instrumento_casa_significativa ? $obj->instrumento_casa_significativa : $config['casas_decimais']), ',', '.');
        }
        $saidaTotal .= '<br><b>'.$moedas[ $tipo_moeda ] . ' ' . number_format($total[ $tipo_moeda ], 2,',','.').'</b></div>';
    }

    $span = 6;
    if($config['bdi']) ++$span;
    if($exibir['instrumento_avulso_custo_acrescimo']) ++$span;

    if($saidaTotal) return '<tr><td colspan="'. $span . '" '.$estilo_texto.'><b>'.$titulo.'</b></td><td class="std" align="right"><div style="text-align: right; white-space: nowrap;'.$estilo.'">'. $saidaND. '</div></td><td><div style="text-align: right;'.$estilo.'">'.$saidaTotal.'</div></td><td colspan="20">&nbsp;</td></tr>';
    else return null;
}

function loadCustosOSTR($instrumento_id){
    $sql = new BDConsulta();

    $sql->adTabela('instrumento_custo', 'oscusto');
    $sql->esqUnir('tr_custo', 'trcusto', 'trcusto.tr_custo_id = oscusto.instrumento_custo_tr');
    $sql->esqUnir('tr', 'tr', 'tr.tr_id = trcusto.tr_custo_tr');
    $sql->esqUnir('tr_avulso_custo', 'travulsocusto', 'travulsocusto.tr_avulso_custo_id = trcusto.tr_custo_avulso');
    $sql->esqUnir('tarefa_custos', 'tarefacusto', 'tarefacusto.tarefa_custos_id = trcusto.tr_custo_tarefa');
    $sql->esqUnir('demanda_custo', 'demandacusto', 'demandacusto.demanda_custo_id = trcusto.tr_custo_demanda');
    //responsável custo tr avulso
    $sql->esqUnir('usuarios', 'utravulso', 'utravulso.usuario_id = travulsocusto.tr_avulso_custo_usuario');
    $sql->esqUnir('contatos', 'ctravulso', 'ctravulso.contato_id = utravulso.usuario_contato');
    $sql->adCampo('concatenar_tres(ctravulso.contato_posto, \' \', ctravulso.contato_nomeguerra) AS responsavel_travulsocusto');
    //responsável custo tarefa
    $sql->esqUnir('usuarios', 'utarefacusto', 'utarefacusto.usuario_id = tarefacusto.tarefa_custos_usuario');
    $sql->esqUnir('contatos', 'ctarefacusto', 'ctarefacusto.contato_id = utarefacusto.usuario_contato');
    $sql->adCampo('concatenar_tres(ctarefacusto.contato_posto, \' \', ctarefacusto.contato_nomeguerra) AS responsavel_tarefacusto');
    //responsável custo demanda
    $sql->esqUnir('usuarios', 'udemandacusto', 'udemandacusto.usuario_id = demandacusto.demanda_custo_usuario');
    $sql->esqUnir('contatos', 'cdemandacusto', 'cdemandacusto.contato_id = udemandacusto.usuario_contato');
    $sql->adCampo('concatenar_tres(cdemandacusto.contato_posto, \' \', cdemandacusto.contato_nomeguerra) AS responsavel_demandacusto');
    $sql->adCampo('instrumento_custo_id, instrumento_custo_quantidade');
    $sql->adCampo('tr_id, tr_nome');
    $sql->adCampo('tr_custo_id, tr_custo_avulso, tr_custo_tarefa, tr_custo_demanda, tr_custo_quantidade');
    $sql->adCampo('tr_avulso_custo_nome, tr_avulso_custo_descricao, tr_avulso_custo_nd, tr_avulso_custo_custo, tr_avulso_custo_custo_atual, tr_avulso_custo_bdi, tr_avulso_custo_servico, tr_avulso_custo_meses, tr_avulso_custo_moeda, tr_avulso_custo_data_limite, tr_avulso_custo_categoria_economica, tr_avulso_custo_grupo_despesa, tr_avulso_custo_modalidade_aplicacao');
    $sql->adCampo('tarefa_custos_nome, tarefa_custos_descricao, tarefa_custos_nd, tarefa_custos_custo, tarefa_custos_bdi, tarefa_custos_moeda, tarefa_custos_data_limite, tarefa_custos_categoria_economica, tarefa_custos_grupo_despesa, tarefa_custos_modalidade_aplicacao');
    $sql->adCampo('demanda_custo_nome, demanda_custo_descricao, demanda_custo_nd, demanda_custo_data_limite, demanda_custo_custo, demanda_custo_bdi, demanda_custo_moeda, demanda_custo_categoria_economica, demanda_custo_grupo_despesa, demanda_custo_modalidade_aplicacao');
    $sql->adOnde('oscusto.instrumento_custo_tr IS NOT NULL');
    $sql->adOnde('oscusto.instrumento_custo_instrumento='.(int)$instrumento_id);
    $sql->adOrdem('tr_id, instrumento_custo_ordem');
    $lista=$sql->Lista();
    $sql->limpar();
    return $lista;
}

function loadCustoAVulso($instrumento_id){
    $sql = new BDConsulta();

    $sql->adTabela('instrumento_avulso_custo');
    $sql->esqUnir('instrumento_custo', 'instrumento_custo', 'instrumento_custo_avulso=instrumento_avulso_custo_id');
    $sql->adCampo('instrumento_custo_id, instrumento_avulso_custo.*, instrumento_custo_quantidade, instrumento_custo_aprovado');
    $sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_custo_quantidade+instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((100+instrumento_avulso_custo_acrescimo)/100)) END AS valor');
    $sql->adCampo('CASE WHEN instrumento_avulso_custo_percentual=0 THEN (((instrumento_avulso_custo_acrescimo)*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)) ELSE ((instrumento_custo_quantidade*(CASE WHEN instrumento_avulso_custo_custo_atual > 0 THEN instrumento_avulso_custo_custo_atual ELSE instrumento_avulso_custo_custo END))*((100+instrumento_avulso_custo_bdi)/100)*((instrumento_avulso_custo_acrescimo)/100)) END AS acrescimo');
    $sql->adOnde('instrumento_custo_instrumento ='.(int)$instrumento_id);
    $sql->adOrdem('instrumento_custo_ordem');
    $linhas=$sql->Lista();
    $sql->limpar();
    return $linhas;
}
?>
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

global $Aplic, $tarefa_id, $obj, $tarefa_acesso, $secao_selection_list, $perms, $prioridade;
global $opcoes_tarefa_superior, $config, $projetos, $tarefa_projeto, $tab, $projeto_cia, $exibir, $estilo_interface;

$Aplic->carregarCKEditorJS();

$social=$Aplic->modulo_ativo('social');

if ($social) require_once BASE_DIR.'/modulos/social/social.class.php';

$sql = new BDConsulta;
$paises = array('' => '(Selecione um pa�s)') + getPais('Paises');
$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();

$cidades=array(''=>'');
$sql->adTabela('municipios');
$sql->adCampo('municipio_id, municipio_nome');
$sql->adOnde('estado_sigla=\''.$obj->tarefa_estado.'\'');
$sql->adOrdem('municipio_nome');
$cidades+= $sql->listaVetorChave('municipio_id', 'municipio_nome');
$sql->limpar();

$depts_selecionados = array();
$contatos_selecionados = array();
$municipios_selecionados = array();
$cias_selecionadas = array();
if ($tarefa_id) {
	if ($Aplic->profissional) {
		$sql->adTabela('tarefa_cia');
		$sql->adCampo('tarefa_cia_cia');
		$sql->adOnde('tarefa_cia_tarefa = '.(int)$tarefa_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}

	$sql->adTabela('tarefa_depts');
	$sql->adCampo('departamento_id');
	$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('tarefa_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('municipio_lista');
	$sql->adCampo('DISTINCT municipio_lista_municipio');
	$sql->adOnde('municipio_lista_tarefa = '.(int)$tarefa_id);
	$municipios_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}

if($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_tarefa = '.(int)$tarefa_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}

echo '<input name="tarefa_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="tarefa_contatos" id="tarefa_contatos" type="hidden" value="'.implode(',', $contatos_selecionados).'" />';

echo '<input name="tarefa_cias"  id="tarefa_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo '<input type="hidden" name="fazerSQL" value="fazer_tarefa_aed" />';
echo '<input type="hidden" name="nova_tarefa_projeto" value="" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input name="tarefa_municipios" type="hidden" value="'.implode(',', $municipios_selecionados).'" />';
echo '<table class="std" width="100%" cellpadding=0 cellspacing=0>';

echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', 'Mesmo que '.$config['genero_tarefa'].' '.$config['tarefa'].' seja em proveito de outr'.$config['genero_organizacao'].' '.$config['organizacao'].', deve-se selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que ser� encarregada de liderar '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td width="100%" style="white-space: nowrap" colspan="2"><div id="combo_cia">'.selecionar_om((!$obj->tarefa_cia   ? $projeto_cia : $obj->tarefa_cia), 'tarefa_cia', 'class=texto size=1 style="width:350px;" onchange="javascript:mudar_om();"').'</div></td></tr>';

if ($Aplic->profissional) {
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else $saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est�o envolvid'.$config['genero_organizacao'].' com '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:350px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}


echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamento']).' Respons�vel', 'Escolha pressionando o �cone � direita qual '.$config['genero_dept'].' '.$config['dept'].' respons�vel por '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').ucfirst($config['departamento']).' respons�vel:'.dicaF().'</td><td><input type="hidden" name="tarefa_dept" id="tarefa_dept" value="'.($tarefa_id ? $obj->tarefa_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($tarefa_id ? $obj->tarefa_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:350px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';











if ($exibir['depts']) {
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_depts.= '<tr><td>'.link_dept($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_dept($depts_selecionados[$i]).'<br>';
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			}
	else 	$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est�o envolvid'.$config['genero_dept'].' com '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:350px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';
	}
else echo '<input type="hidden" name="tarefa_dept" id="tarefa_dept" value="'.$obj->tarefa_dept.'" />';


echo '<input type="hidden" id="tarefa_dono" name="tarefa_dono" value="'.(!isset($obj->tarefa_dono) ? $Aplic->usuario_id : $obj->tarefa_dono).'" />';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Respons�vel pel'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Tod'.$config['genero_tarefa'].' '.$config['tarefa'].' deve ter um respons�vel. O '.$config['usuario'].' respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].' dever�, preferencialmente, ser o encarregado de atualizar os dados no '.$config['gpweb'].', relativos as su'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Respons�vel:'.dicaF().'</td><td colspan="2"><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om((!isset($obj->tarefa_dono) ? $Aplic->usuario_id : $obj->tarefa_dono), $Aplic->getPref('om_usuario')).'" style="width:350px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';


if ($exibir['contatos'] && $Aplic->ModuloAtivo('contatos')) {

	$saida_contatos='';
	if (count($contatos_selecionados)) {
			$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
			$qnt_lista_contatos=count($contatos_selecionados);
			if ($qnt_lista_contatos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
					$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';
			}
	else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(strtolower($config['contatos']), 'Quais '.strtolower($config['contatos']).' est�o envolvid'.$config['genero_contato'].'s.').ucfirst($config['contatos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:350px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos()').'</td></tr></table></td></tr>';
	}



if ($Aplic->profissional && count($indicadores)>1) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_tarefa'].' '.$config['tarefa'].' mais representativo da situa��o geral d'.$config['genero_tarefa'].' mesm'.$config['genero_tarefa'].'.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'tarefa_principal_indicador', 'class="texto" style="width:350px;"', $obj->tarefa_principal_indicador).'</td></tr>';


if ($exibir['codigo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('C�digo', 'Escreva, caso exista, o c�digo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'C�digo:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_codigo" value="'.$obj->tarefa_codigo.'"  style="width:350px;" maxlength="255" /></td></tr>';



if ($Aplic->profissional){
	$setor = array('' => '') + getSisValor('TarefaSetor');
	if ($exibir['setor']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'tarefa_setor', 'style="width:350px;" class="texto" onchange="mudar_segmento();"', $obj->tarefa_setor).'</td></tr>';
	$segmento=array('' => '');
	if ($obj->tarefa_setor){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaSegmento\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$obj->tarefa_setor.'\'');
		$sql->adOrdem('sisvalor_valor');
		$segmento+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['segmento']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'tarefa_segmento', 'style="width:350px;" class="texto" onchange="mudar_intervencao();"', $obj->tarefa_segmento).'</div></td></tr>';
	$intervencao=array('' => '');
	if ($obj->tarefa_segmento){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaIntervencao\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$obj->tarefa_segmento.'\'');
		$sql->adOrdem('sisvalor_valor');
		$intervencao+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['intervencao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'tarefa_intervencao', 'style="width:350px;" class="texto" onchange="mudar_tipo_intervencao();"', $obj->tarefa_intervencao).'</div></td></tr>';
	$tipo_intervencao=array('' => '');
	if ($obj->tarefa_intervencao){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaTipoIntervencao\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$obj->tarefa_intervencao.'\'');
		$sql->adOrdem('sisvalor_valor');
		$tipo_intervencao+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['tipo_intervencao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'tarefa_tipo_intervencao', 'style="width:350px;" class="texto"', $obj->tarefa_tipo_intervencao).'</div></td></tr>';
	}

$tarefa_tipos=vetor_campo_sistema('TipoTarefa',$obj->tarefa_tipo);
if ($exibir['tipo']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Categoria', 'Definir a categoria d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Categoria:'.dicaF().'</td><td><div id="combo_tarefa_tipo">'.selecionaVetor($tarefa_tipos, 'tarefa_tipo', 'class="texto" size=1 style="width:350px;" onchange="mudar_tarefa_tipo();"', $obj->tarefa_tipo).'</div></td></tr>';

echo '<tr><td align=right style="white-space: nowrap">'.dica('Prioridade', 'A prioridade para fins de filtragem.').'Prioridade:'.dicaF().'</td><td>'.selecionaVetor($prioridade, 'tarefa_prioridade', 'size="1" class="texto" style="width:350px;"', ($obj->tarefa_prioridade ? $obj->tarefa_prioridade : 0)).'</td></tr>';


echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Superior', 'Seleciona de qual '.$config['tarefa'].' est'.($config['genero_tarefa']=='a' ? 'a' : 'e').' � sub'.$config['tarefa'].'.').ucfirst($config['tarefa']).' Superior:'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="tarefa_superior" id="tarefa_superior" value="'.$obj->tarefa_superior.'" /><input type="text" id="nome_tarefa" name="nome_tarefa" value="'.nome_tarefa($obj->tarefa_superior).'" style="width:350px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste �cone '.imagem('icones/tarefa_p.gif').' para seleciona de qual '.$config['tarefa'].' est'.($config['genero_tarefa']=='a' ? 'a' : 'e').' � sub'.$config['tarefa'].'.').'</a></td></tr></table></td></tr>';

if ($exibir['oque']) echo '<tr><td align="right">'.dica('O Que', 'Muito importante escrever um breve resumo da atividade, para servir de guia '.($config['genero_tarefa']=='o'? 'aos' : '�s').' '.$config['tarefas'].' sucessoras e auxiliar na compreens�o d'.$config['genero_projeto'].' '.$config['projeto'].'.').'O Que:'.dicaF().'</td><td width="100%"><table cellpadding=0 cellspacing=0 style="width:100%"><tr><td><textarea data-gpweb-cmp="ckeditor" name="tarefa_descricao" class="textarea" style="width:100%" rows="3">'.$obj->tarefa_descricao.'</textarea></td></tr></table></td></tr>';
if ($exibir['porque']) echo '<tr><td align="right">'.dica('Por Que', 'Por que '.$config['genero_tarefa'].' '.$config['tarefa'].' ser� desenvolvid'.$config['genero_tarefa'].'.').'Por Que:'.dicaF().'</td><td width="100%"><table cellpadding=0 cellspacing=0 style="width:100%"><tr><td><textarea data-gpweb-cmp="ckeditor" name="tarefa_porque" class="textarea" style="width:100%" rows="3">'.$obj->tarefa_porque.'</textarea></td></tr></table></td></tr>';
if ($exibir['como']) echo '<tr><td align="right">'.dica('Como', 'Como '.$config['genero_tarefa'].' '.$config['tarefa'].' ser� desenvolvid'.$config['genero_tarefa'].'.').'Como:'.dicaF().'</td><td width="100%"><table cellpadding=0 cellspacing=0 style="width:100%"><tr><td><textarea data-gpweb-cmp="ckeditor" name="tarefa_como" class="textarea" style="width:100%" rows="3">'.$obj->tarefa_como.'</textarea></td></tr></table></td></tr>';
if ($exibir['onde']) echo '<tr><td align="right">'.dica('Onde', 'Onde '.$config['genero_tarefa'].' '.$config['tarefa'].' ser� desenvolvid'.$config['genero_tarefa'].'.').'Onde:'.dicaF().'</td><td width="100%"><table cellpadding=0 cellspacing=0 style="width:100%"><tr><td><textarea data-gpweb-cmp="ckeditor" name="tarefa_onde" class="textarea" style="width:100%" rows="3">'.$obj->tarefa_onde.'</textarea></td></tr></table></td></tr>';
if ($exibir['tarefa_situacao_atual']) echo '<tr><td align="right">'.dica('Situa��o Atual', 'Situa��o atual d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Situa��o Atual:'.dicaF().'</td><td width="100%"><table cellpadding=0 cellspacing=0 style="width:100%"><tr><td><textarea data-gpweb-cmp="ckeditor" name="tarefa_situacao_atual" style="width:100%" rows="3" class="textarea">'.$obj->tarefa_situacao_atual.'</textarea></td></tr></table></td></tr>';


if ($exibir['link']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('URL d'.$config['genero_tarefa'].' '.$config['tarefa'], 'O endere�o URL dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'URL:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_url_relacionada" value="'.$obj->tarefa_url_relacionada.'" style="width:350px;" maxlength="255" /></td></tr>';


if ($exibir['adquirido']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quantidade Adquirida', 'Insira, caso seja o caso, a quantidade adquirida do item base para a execu��o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade adquirida:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_adquirido"  value="'.($obj->tarefa_adquirido!=0 ? number_format($obj->tarefa_adquirido, 2, ',', '.') : '').'" style="width:350px;" maxlength="50" /></td></tr>';
if ($exibir['previsto']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quantidade Prevista', 'Insira, caso seja o caso, a quantidade prevista a ser realizada baseado no tipo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade prevista:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_previsto" '.($obj->tarefa_social ? 'readonly="readonly"' : '').' value="'.($obj->tarefa_previsto!=0 ? number_format($obj->tarefa_previsto, 2, ',', '.') : '').'" style="width:350px;" maxlength="50" /></td></tr>';
if ($exibir['realizado']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Quantidade Realizada', 'Insira, caso seja o caso, a quantidade realizada baseado no tipo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade realizada:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_realizado" '.($obj->tarefa_social ? 'readonly="readonly"' : '').' value="'.($obj->tarefa_realizado!=0 ? number_format($obj->tarefa_realizado, 2, ',', '.') : '').'" style="width:350px;" maxlength="50" /></td></tr>';
$unidade=array(''=>'')+getSisValor('TipoUnidade');
if ($exibir['unidade']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Unidade de Medida', 'Escolha a unidade de medida dos quantitativo definidos acima, se for o caso.').'Unidade de medida:'.dicaF().'</td><td>'.selecionaVetor($unidade, 'tarefa_unidade', 'class=texto size=1 style="width:350px;"', $obj->tarefa_unidade).'</td></tr>';
if ($exibir['empregos_execucao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Empregos Gerados Durante a Execu��o', 'Insira, caso seja o caso, o n�mero de empregos gerados durante a execu��o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos durante a execu��o:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_emprego_obra" value="'.$obj->tarefa_emprego_obra.'" style="width:350px;" maxlength="50" /></td></tr>';
if ($exibir['empregos_diretos']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Empregos Diretos Ap�s a Conclus�o', 'Insira, caso seja o caso, o n�mero de empregos diretos gerados ap�s a conclus�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos diretos ap�s conclus�o:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_emprego_direto" value="'.$obj->tarefa_emprego_direto.'" style="width:350px;" maxlength="50" /></td></tr>';
if ($exibir['empregos_indiretos']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Empregos Indiretos Ap�s a Conclus�o', 'Insira, caso seja o caso, o n�mero de empregos indiretos gerados ap�s a conclus�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos indiretos ap�s conclus�o:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_emprego_indireto" value="'.$obj->tarefa_emprego_indireto.'" style="width:350px;" maxlength="50" /></td></tr>';
$FormaImplantacao=array(''=>'')+getSisValor('FormaImplantacao');
if ($exibir['implantacao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Forma de Implanta��o', 'Insira a forma de implanta��o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Forma de implanta��o:'.dicaF().'</td><td>'.selecionaVetor($FormaImplantacao, 'tarefa_forma_implantacao', 'class="texto" style="width:350px;"', $obj->tarefa_forma_implantacao).'</td></tr>';
$PopulacaoAtendida=array(''=>'')+getSisValor('PopulacaoAtendida');
if ($exibir['populacao']) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Popula��o atendida', 'Insira, caso seja o caso, o tipo de popula��o atendida quando da conclus�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Popula��o atendida:'.dicaF().'</td><td>'.selecionaVetor($PopulacaoAtendida, 'tarefa_populacao_atendida', 'class="texto" style="width:350px;"', $obj->tarefa_populacao_atendida).'</td></tr>';


if ($social) {
	$comunidades=array(''=>'');
	echo '<tr><td align="right">'.dica('Comunidade', 'A comunidade onde se aplica '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade_social">'.selecionar_comunidade_para_ajax($obj->tarefa_cidade,'tarefa_comunidade', 'class="texto" style="width:350px;"', '', $obj->tarefa_comunidade, false).'</div></td></tr>';
	$lista_programas=array('' => '');
	$sql->adTabela('social');
	$sql->adCampo('social_id, social_nome');
	$sql->adOrdem('social_nome');
	$lista_programas+= $sql->listaVetorChave('social_id', 'social_nome');
	$sql->limpar();
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Programa Social', 'A qual programa social pertence '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Programa:'.dicaF().'</td><td align="left" style="white-space: nowrap">'. selecionaVetor($lista_programas, 'tarefa_social', 'size="1" style="width:350px;" class="texto" onchange="mudar_acao()"', $obj->tarefa_social) .'</td></tr>';
	echo '<tr><td align="right">'.dica('A��o Social', 'Escolha a a��o social d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'A��o:'.dicaF().'</td><td align="left" style="white-space: nowrap"><div id="acao_combo">'.selecionar_acao_para_ajax($obj->tarefa_social, 'tarefa_acao', 'size="1" style="width:350px;" class="texto"', '', $obj->tarefa_acao, false).'</div></td></tr>';
	}






echo '<tr><td align="right" style="white-space: nowrap">'.dica('N�vel de Acesso', ucfirst($config['genero_tarefa']).'s '.ucfirst($config['tarefas']).' podem ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar '.$config['genero_tarefa'].' '.$config['tarefa'].'.</li><li><b>Protegido I</b> - Todos podem ver, porem apenas o respons�vel e os participantes d'.$config['genero_tarefa'].' '.$config['tarefa'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Protegido III</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante I</b> - Somente o respons�vel e '.$config['genero_usuario'].'s '.$config['usuarios'].' designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' podem ver e editar a mesma.</li><li><b>Participantes II</b> - Somente o respons�vel e os designados podem ver e apenas o respons�vel pode editar tudo e os designados editarem os objetos relacionados.</li><li><b>Participantes III</b> - Somente o respons�vel e '.$config['genero_usuario'].'s '.$config['usuarios'].' designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' podem ver, e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td>'.selecionaVetor($tarefa_acesso, 'tarefa_acesso', 'class="texto" style="width:350px;"', ($tarefa_id ? $obj->tarefa_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';


if ($Aplic->profissional) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Alerta Ativo', 'Caso esteja marcado '.$config['genero_tarefa'].' '.$config['tarefa'].' ser� inclu�d'.$config['genero_tarefa'].' no sistema de alertas autom�ticos (precisa ser executado em background o arquivo server/alertas/alertas_pro.php).').'Alerta ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="tarefa_alerta" '.($obj->tarefa_alerta ? 'checked="checked"' : '').' /></td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
global $m;
$campos_customizados = new CampoCustomizados($m, $obj->tarefa_id, 'editar');
if ($campos_customizados->count()) {
	echo '<tr><td colspan="2"><table width="100%" cellpadding=0 cellspacing=0><tr><td>'.$campos_customizados->imprimirHTML().'</td></tr></table></td></tr>';
	}
	






echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'enderecamento\').style.display) document.getElementById(\'enderecamento\').style.display=\'\'; else document.getElementById(\'enderecamento\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Localiza��o e �reas</b></a></td></tr>';
echo '<tr id="enderecamento" style="display:none"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';


if ($exibir['endereco']) {
	echo '<tr><td align="right">'.dica('Endere�o', 'Escreva o ender�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Endere�o:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_endereco1" value="'.$obj->tarefa_endereco1.'" style="width:350px;" maxlength="255" /></td></tr>';
	echo '<tr><td align="right">'.dica('Complemento do Endere�o', 'Escreva o complemento do ender�o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_endereco2" value="'.$obj->tarefa_endereco2.'" style="width:350px;" maxlength="255" /></td></tr>';
	if (!$social) echo '<tr><td align="right">'.dica('Estado', 'Escolha na caixa de op��o � direita o Estado d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'tarefa_estado', 'size="1" class="texto" style="width:350px;" onchange="mudar_cidades();"', $obj->tarefa_estado).'</td></tr>';
	if (!$social) echo '<tr><td align="right">'.dica('Munic�pio', 'Escreva o munic�pio d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Munic�pio:'.dicaF().'</td><td><div id="combo_cidade">'.selecionaVetor($cidades,'tarefa_cidade', 'class="texto" style="width:350px;"', $obj->tarefa_cidade).'</div></td></tr>';
	echo '<tr><td align="right">'.dica('CEP', 'Escreva o CEP d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'CEP:'.dicaF().'</td><td><input type="text" class="texto" style="width:350px;" name="tarefa_cep" value="'.$obj->tarefa_cep.'" maxlength="15" /></td></tr>';
	echo '<tr><td align="right">'.dica('Pa�s', 'Escolha na caixa de op��o � direita o Pa�s d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Pa�s:'.dicaF().'</td><td>'.selecionaVetor($paises, 'tarefa_pais', 'size="1" class="texto" style="width:350px;"', ($obj->tarefa_pais ? $obj->tarefa_pais : 'BR')).'</td></tr>';
	}

if ($exibir['municipios']){
	$saida_municipios='';
	if (count($municipios_selecionados)) {
			$saida_municipios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_municipios.= '<tr><td>'.link_municipio($municipios_selecionados[0]);
			$qnt_lista_municipios=count($municipios_selecionados);
			if ($qnt_lista_municipios > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_municipios; $i < $i_cmp; $i++) $lista.=link_municipio($municipios_selecionados[$i]).'<br>';
					$saida_municipios.= dica('Outros Munic�pios', 'Clique para visualizar os demais munic�pios.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_municipios\');">(+'.($qnt_lista_municipios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_municipios"><br>'.$lista.'</span>';
					}
			$saida_municipios.= '</td></tr></table>';
			}
	else $saida_municipios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Munic�pios Envolvidos', 'Quais munic�pios est�o envolvidos '.($config['genero_tarefa']=='a' ? 'nesta ': 'neste ').$config['tarefa'].'.').'Munic�pios:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:350px;"><div id="combo_municipios">'.$saida_municipios.'</div></td><td>'.botao_icone('municipio_p.gif','Selecionar Munic�pio', 'Clique neste �cone '.botao_icone('municipio_p.gif').' para selecionar munic�pios envolvidos.','popMunicipios()').'</td></tr></table></td></tr>';
	}


if ($exibir['latitude'] || $exibir['longitude']){
	echo '<tr><td align="right">'.dica('Coordenadas', 'As coordenadas geogr�ficas da localiza��o d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Coordenadas:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0>';
	echo '<tr><td colspan=2 align=center>Geogr�fica</td><td colspan=2 align=center>UTM</td></tr>';
	echo '<tr><td align=right>Lon:</td><td><input class="texto" type=text size=15 id="tarefa_longitude" name="tarefa_longitude" value="'.($obj->tarefa_longitude ? $obj->tarefa_longitude : 0).'" onChange="converter_decimal()"></td><td align=right>X:</td><td><input class="texto" type=text size=15 name="txtX" value=""></td></tr>';
	echo '<tr><td align=right>Lat:</td><td><input class="texto" type=text size=15 id="tarefa_latitude" name="tarefa_latitude" value="'.($obj->tarefa_latitude ? $obj->tarefa_latitude : 0).'"  onChange="converter_decimal()"></td><td align=right>Y:</td><td><input class="texto" type=text size=15 name="txtY" value=""></td></tr>';
	echo '<tr><td align=right>Lon:</td><td><input class="texto" type="text" name="txtlongraus" size="2" onChange="btnToUTM_OnClick()" value="0">�<input class="texto" type="text" name="txtlonmin" size="2" onChange="btnToUTM_OnClick()" value="0">\'<input class="texto" type="text" name="txtlonsec" size="2" onChange="btnToUTM_OnClick()" value="0">\'\'</td><td align=right>Zona:</td><td><input class="texto" type=text size=4 name="txtZone" value="22" value="0"></td></tr>';
	echo '<tr><td align=right>Lat:</td><td><input class="texto" type="text" name="txtlatgraus" size="2" onChange="btnToUTM_OnClick()" value="0">�<input class="texto" type="text" name="txtlatmin" size="2" onChange="btnToUTM_OnClick()" value="0">\'<input class="texto" type="text" name="txtlatsec" size="2" onChange="btnToUTM_OnClick()" value="0">\'\'&nbsp;&nbsp;</td><td colspan=2>Hemisf�rio:<input class="texto" type=radio name="rbtnHemisphere" value="N" OnClick="0">N<input class="texto" type=radio name="rbtnHemisphere" value="S" OnClick="0" checked>S</td></tr>';
	echo '<tr><td></td><td align=center>'.botao('>>', 'Transformar em UTM', 'Clique neste bot�o para converter as coordenadas de grau para UTM.','','btnToUTM_OnClick()').'</td><td></td><td align=center>'.botao('<<', 'Transformar em Grau', 'Clique neste bot�o para converter as coordenadas de UTM para grau.','','btnToGeographic_OnClick()').'</td></tr>';
	echo '</table></td></tr>';
	}

if ($tarefa_id && $exibir['area']) echo '<tr><td align="right" style="white-space: nowrap"></td><td valign="top"><table><tr><td>'.botao('�rea', '�rea','Abrir uma janela onde poder� selecionar a �rea '.($config['genero_tarefa']=='a' ? 'desta ': 'deste ').$config['tarefa'].' baseado nas coordenadas de pol�gonos cadastrados.','','popEditarPoligono('.$obj->tarefa_projeto.', '.(int)$tarefa_id.')').'</td>'.($Aplic->profissional ? '<td>'.botao('importar �rea', 'Importar �rea KML','Abrir uma janela onde poder� selecionar a �rea '.($config['genero_tarefa']=='a' ? 'desta ': 'deste ').$config['tarefa'].' s partir de arquivo KML.','','popImportarKML('.$obj->tarefa_projeto.', '.(int)$tarefa_id.')').'</td>' : '').'</tr></table></td></tr>';


echo '</div></td></tr>';
echo '</table></td></tr>';











if ($Aplic->profissional && $exibir['bioma']){
	echo '<tr><td style="height:1px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_bioma\').style.display) document.getElementById(\'apresentar_bioma\').style.display=\'\'; else document.getElementById(\'apresentar_bioma\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>'.ucfirst($config['tarefa_bioma']).'</b></a></td></tr>';
	echo '<tr id="apresentar_bioma" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0>';


	echo '<input type=hidden name="tarefa_bioma_id" id="tarefa_bioma_id" value="">';
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td><table cellspacing=0 cellpadding=0>';
	
	
	$bioma = getSisValor('tarefa_bioma',null,null,'sisvalor_valor', true, '');	

	echo '<tr><td align=right>'.dica(ucfirst($config['tarefa_bioma']), 'Insira '.$config['genero_tarefa_bioma'].' '.$config['tarefa_bioma'].' relacionada com '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa_bioma']).':'.dicaF().'</td><td>'.selecionaVetor($bioma, 'tarefa_bioma_bioma', 'style="width:400px;" size="1" class="texto"').'</td></tr>';
	echo '</table></td>';
	echo '<td id="adicionar_bioma" style="display:"><a href="javascript: void(0);" onclick="incluir_bioma();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste �cone '.imagem('icones/adicionar.png').' para incluir.').'</a></td>';
	echo '<td id="confirmar_bioma" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'tarefa_bioma_id\').value=0;document.getElementById(\'tarefa_bioma_bioma\').value=\'\';	document.getElementById(\'adicionar_bioma\').style.display=\'\';	document.getElementById(\'confirmar_bioma\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar a edi��o.').'</a><a href="javascript: void(0);" onclick="incluir_bioma();">'.imagem('icones/ok_g.png','Confirmar','Clique neste �cone '.imagem('icones/ok.png').' para confirmar a edi��o.').'</a></td>';
	echo '</tr></table></td></tr>';
	if ($tarefa_id) {
		$sql->adTabela('tarefa_bioma');
		$sql->adOnde('tarefa_bioma_tarefa = '.(int)$tarefa_id);
		$sql->adCampo('tarefa_bioma_id, tarefa_bioma_bioma, tarefa_bioma_ordem');
		$sql->adOrdem('tarefa_bioma_ordem');
		$biomas=$sql->ListaChave('tarefa_bioma_id');
		$sql->limpar();
		}
	else $biomas=null;
	echo '<tr><td colspan=20 align=left><div id="combo_bioma">';
	if (is_array($biomas) && count($biomas)) {
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.ucfirst($config['tarefa_bioma']).'</th><th></th></tr>';
		foreach ($biomas as $bioma_id => $bioma_valor) {
			echo '<tr align="center">';
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_bioma('.$bioma_valor['tarefa_bioma_ordem'].', '.$bioma_valor['tarefa_bioma_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_bioma('.$bioma_valor['tarefa_bioma_ordem'].', '.$bioma_valor['tarefa_bioma_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_bioma('.$bioma_valor['tarefa_bioma_ordem'].', '.$bioma_valor['tarefa_bioma_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'"/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_bioma('.$bioma_valor['tarefa_bioma_ordem'].', '.$bioma_valor['tarefa_bioma_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'"/></a>'.dicaF();
			echo '</td>';
			echo '<td align="left">'.(isset($bioma[$bioma_valor['tarefa_bioma_bioma']]) ? $bioma[$bioma_valor['tarefa_bioma_bioma']] : '').'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_bioma('.$bioma_valor['tarefa_bioma_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta regi�o?\')) {excluir_bioma('.$bioma_valor['tarefa_bioma_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</div></td></tr>';

	echo '</table></td></tr>';
	}







//comunidade
if ($exibir['comunidade']){
	echo '<tr><td style="height:1px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'apresentar_comunidade\').style.display) document.getElementById(\'apresentar_comunidade\').style.display=\'\'; else document.getElementById(\'apresentar_comunidade\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>'.ucfirst($config['tarefa_comunidade']).'</b></a></td></tr>';
	echo '<tr id="apresentar_comunidade" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0>';


	echo '<input type=hidden name="tarefa_comunidade_id" id="tarefa_comunidade_id" value="">';
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td><table cellspacing=0 cellpadding=0>';
	
	
	$comunidade = getSisValor('tarefa_comunidade',null,null,'sisvalor_valor', true, '');	

	echo '<tr><td align=right>'.dica(ucfirst($config['tarefa_comunidade']), 'Insira '.$config['genero_tarefa_comunidade'].' '.$config['tarefa_comunidade'].' relacionada com '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa_comunidade']).':'.dicaF().'</td><td>'.selecionaVetor($comunidade, 'tarefa_comunidade_comunidade', 'style="width:400px;" size="1" class="texto"').'</td></tr>';
	echo '</table></td>';
	echo '<td id="adicionar_comunidade" style="display:"><a href="javascript: void(0);" onclick="incluir_comunidade();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste �cone '.imagem('icones/adicionar.png').' para incluir.').'</a></td>';
	echo '<td id="confirmar_comunidade" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'tarefa_comunidade_id\').value=0;document.getElementById(\'tarefa_comunidade_comunidade\').value=\'\';	document.getElementById(\'adicionar_comunidade\').style.display=\'\';	document.getElementById(\'confirmar_comunidade\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar a edi��o.').'</a><a href="javascript: void(0);" onclick="incluir_comunidade();">'.imagem('icones/ok_g.png','Confirmar','Clique neste �cone '.imagem('icones/ok.png').' para confirmar a edi��o.').'</a></td>';
	echo '</tr></table></td></tr>';
	if ($tarefa_id) {
		$sql->adTabela('tarefa_comunidade');
		$sql->adOnde('tarefa_comunidade_tarefa = '.(int)$tarefa_id);
		$sql->adCampo('tarefa_comunidade_id, tarefa_comunidade_comunidade, tarefa_comunidade_ordem');
		$sql->adOrdem('tarefa_comunidade_ordem');
		$comunidades=$sql->ListaChave('tarefa_comunidade_id');
		$sql->limpar();
		}
	else $comunidades=null;
	echo '<tr><td colspan=20 align=left><div id="combo_comunidade">';
	if (is_array($comunidades) && count($comunidades)) {
		echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.ucfirst($config['tarefa_comunidade']).'</th><th></th></tr>';
		foreach ($comunidades as $comunidade_id => $comunidade_valor) {
			echo '<tr align="center">';
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_comunidade('.$comunidade_valor['tarefa_comunidade_ordem'].', '.$comunidade_valor['tarefa_comunidade_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_comunidade('.$comunidade_valor['tarefa_comunidade_ordem'].', '.$comunidade_valor['tarefa_comunidade_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'"/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_comunidade('.$comunidade_valor['tarefa_comunidade_ordem'].', '.$comunidade_valor['tarefa_comunidade_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'"/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_comunidade('.$comunidade_valor['tarefa_comunidade_ordem'].', '.$comunidade_valor['tarefa_comunidade_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'"/></a>'.dicaF();
			echo '</td>';
			echo '<td align="left">'.(isset($comunidade[$comunidade_valor['tarefa_comunidade_comunidade']]) ? $comunidade[$comunidade_valor['tarefa_comunidade_comunidade']] : '').'</td>';
			echo '<td style="white-space: nowrap" width="32"><a href="javascript: void(0);" onclick="editar_comunidade('.$comunidade_valor['tarefa_comunidade_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar.').'</a>';
			echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta regi�o?\')) {excluir_comunidade('.$comunidade_valor['tarefa_comunidade_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</div></td></tr>';

	echo '</table></td></tr>';
	}








echo '<tr><td style="height:1px;"></td></tr>';

echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'notificar\').style.display) document.getElementById(\'notificar\').style.display=\'\'; else document.getElementById(\'notificar\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificar</b></a></td></tr>';
echo '<tr id="notificar" style="display:'.($Aplic->getPref('informa_aberto') ? '' : 'none').'"><td colspan=20><table width="100%" cellspacing=1 cellpadding=0>';

echo '<tr><td valign="middle" align="right" width=130>'.dica('Notificar', 'Marque esta caixa para avisar da '.($tarefa_id > 0 ? 'modifica��o' : 'cria��o').' d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Notificar:'.dicaF().'</td><td>';



echo '<input type="checkbox" name="email_tarefa_responsavel" id="email_tarefa_responsavel" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'": '').' />'.dica('Respons�vel', 'Ao selecionar esta op��o, o respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].' ser� informado '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Respons�vel'.dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_tarefa_designados" id="email_tarefa_designados" '.($Aplic->getPref('informa_designados') ? "checked='checked'": '').' />'.dica('Designados', 'Ao selecionar esta op��o, os designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' ser�o informados '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Designados d'.$config['genero_tarefa'].' '.$config['tarefa'].dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_tarefa_designados_novos" id="email_tarefa_designados_novos" />'.dica('Designados Novos', 'Ao selecionar esta op��o, os novos designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' ser�o informados '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Novos designados d'.$config['genero_tarefa'].' '.$config['tarefa'].dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_tarefa_contatos" id="email_tarefa_contatos" '.($Aplic->getPref('informa_contatos') ? "checked='checked'": '').' />'.dica('Contatos d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Ao selecionar esta op��o, os contatos d'.$config['genero_tarefa'].' '.$config['tarefa'].' ser�o informados '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Contatos d'.$config['genero_tarefa'].' '.$config['tarefa'].dicaF().'&nbsp;&nbsp;';
echo '<br>';

echo '<input type="checkbox" name="email_projeto_responsavel" id="email_projeto_responsavel" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'": '').' />'.dica(ucfirst($config['gerente']), 'Ao selecionar esta op��o, o '.$config['gerente'].' d'.$config['genero_projeto'].' '.$config['projeto'].' ser� informado '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).ucfirst($config['gerente']).dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_supervisor" id="email_projeto_supervisor" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica(ucfirst($config['supervisor']), 'Ao selecionar esta op��o, o '.$config['supervisor'].' d'.$config['genero_projeto'].' '.$config['projeto'].' ser� informado '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).ucfirst($config['supervisor']).dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_autoridade" id="email_projeto_autoridade" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica(ucfirst($config['autoridade']), 'Ao selecionar esta op��o, o '.$config['autoridade'].' d'.$config['genero_projeto'].' '.$config['projeto'].' ser� informado '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).ucfirst($config['autoridade']).dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_cliente" id="email_projeto_cliente" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica(ucfirst($config['cliente']), 'Ao selecionar esta op��o, o '.$config['cliente'].' d'.$config['genero_projeto'].' '.$config['projeto'].' ser� informado '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).ucfirst($config['cliente']).dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_designados" id="email_projeto_designados" '.($Aplic->getPref('informa_designados') ? "checked='checked'" : '').' />'.dica('Integrantes', 'Ao selecionar esta op��o, os integrantes d'.$config['genero_projeto'].' '.$config['projeto'].' ser�o informados '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Integrantes'.dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_stakeholder" id="email_projeto_stakeholder" '.($Aplic->getPref('informa_responsavel') ? "checked='checked'" : '').' />'.dica('Stakeholders', 'Ao selecionar esta op��o, os stakeholders d'.$config['genero_projeto'].' '.$config['projeto'].' ser�o informados '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Stakeholders'.dicaF().'&nbsp;&nbsp;';
echo '<input type="checkbox" name="email_projeto_contatos" id="email_projeto_contatos" '.($Aplic->getPref('informa_contatos') ? "checked='checked'" : '').' />'.dica('Contatos d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Ao selecionar esta op��o, os contatos d'.$config['genero_projeto'].' '.$config['projeto'].' ser�o informados '.($tarefa_id > 0 ? 'das altera��es realizadas.' : 'da cria��o.')).'Contatos d'.$config['genero_projeto'].' '.$config['projeto'].dicaF();
echo '</td></tr>';

echo '<input type="hidden" name="email_contatos" id="email_contatos" value="" />';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Outros '.ucfirst($config['contatos']), ucfirst($config['contatos']).' extras para receberem notifica��o.').'Outros '.$config['contatos'].':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:400px;"><div id="combo_contatos2"><table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table></div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos2()').'</td></tr></table></td></tr>';
echo ($config['email_ativo'] ? ''.($config['email_ativo'] ? '<tr><td align="right">'.dica('Destinat�rios Extra', 'Preencha neste campo os e-mail, separados por v�rgula, dos destinat�rios extras que ser�o avisados.').'Destinat�rios extra:'.dicaF().'</td><td><input type="text" class="texto" name="email_extras" maxlength="255" style="width:400px;" /></td></tr>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />');
echo '<tr><td align="right">'.dica('Texto', 'Texto a ser enviado junto com a notifica��o.').'Texto:'.dicaF().'</td><td><textarea name="email_texto" data-gpweb-cmp="ckeditor" style="width:400px;" rows="3" class="textarea"></textarea></td></tr>';



echo '</table></td></tr>';








	

echo '</table>';
?>
<script>

//bioma
function mudar_posicao_bioma(ordem, tarefa_bioma_id, direcao){
	xajax_mudar_posicao_bioma(ordem, tarefa_bioma_id, direcao, document.getElementById('tarefa_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function editar_bioma(tarefa_bioma_id){

	xajax_editar_bioma(tarefa_bioma_id);
	document.getElementById('adicionar_bioma').style.display="none";
	document.getElementById('confirmar_bioma').style.display="";

	}

function incluir_bioma(){
	if (document.getElementById('tarefa_bioma_bioma').value !=''){
		xajax_incluir_bioma(document.getElementById('tarefa_id').value, document.getElementById('uuid').value, document.getElementById('tarefa_bioma_id').value, document.getElementById('tarefa_bioma_bioma').value);
		document.getElementById('tarefa_bioma_id').value=null;
		document.getElementById('tarefa_bioma_bioma').value='';
		document.getElementById('adicionar_bioma').style.display='';
		document.getElementById('confirmar_bioma').style.display='none';
		__buildTooltip();
		}
	else alert('Insira uma bioma.');
	}

function excluir_bioma(tarefa_bioma_id){
	xajax_excluir_bioma(tarefa_bioma_id, document.getElementById('tarefa_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}	






//comunidade
function mudar_posicao_comunidade(ordem, tarefa_comunidade_id, direcao){
	xajax_mudar_posicao_comunidade(ordem, tarefa_comunidade_id, direcao, document.getElementById('tarefa_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}

function editar_comunidade(tarefa_comunidade_id){

	xajax_editar_comunidade(tarefa_comunidade_id);
	document.getElementById('adicionar_comunidade').style.display="none";
	document.getElementById('confirmar_comunidade').style.display="";

	}

function incluir_comunidade(){
	if (document.getElementById('tarefa_comunidade_comunidade').value !=''){
		xajax_incluir_comunidade(document.getElementById('tarefa_id').value, document.getElementById('uuid').value, document.getElementById('tarefa_comunidade_id').value, document.getElementById('tarefa_comunidade_comunidade').value);
		document.getElementById('tarefa_comunidade_id').value=null;
		document.getElementById('tarefa_comunidade_comunidade').value='';
		document.getElementById('adicionar_comunidade').style.display='';
		document.getElementById('confirmar_comunidade').style.display='none';
		__buildTooltip();
		}
	else alert('Insira uma comunidade.');
	}

function excluir_comunidade(tarefa_comunidade_id){
	xajax_excluir_comunidade(tarefa_comunidade_id, document.getElementById('tarefa_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}	
	








function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('tarefa_cia').value+'&cias_id_selecionadas='+document.getElementById('tarefa_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.frmEditar.tarefa_cias.value = organizacao_id_string;
	document.getElementById('tarefa_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('tarefa_cias').value);
	__buildTooltip();
	}

function popImportarKML(projeto_id, tarefa_id){
	parent.gpwebApp.popUp('Importar �rea', 1024, 500, 'm=projetos&a=editar_poligono_pro&dialogo=1&projeto_id='+projeto_id+'&tarefa_id='+tarefa_id, null, window);
	}

function mudar_segmento(){
	document.getElementById('tarefa_intervencao').length=0;
	document.getElementById('tarefa_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('tarefa_setor').value, 'TarefaSegmento', 'tarefa_segmento','combo_segmento', 'style="width:350px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	document.getElementById('tarefa_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('tarefa_segmento').value, 'TarefaIntervencao', 'tarefa_intervencao','combo_intervencao', 'style="width:350px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('tarefa_intervencao').value, 'TarefaTipoIntervencao', 'tarefa_tipo_intervencao','combo_tipo_intervencao', 'style="width:350px;" class="texto" size=1');
	}


function expandir_colapsar_item(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';
var contatos_id_selecionados = '<?php echo implode(",", $contatos_selecionados); ?>';

function mudar_acao(){
	xajax_acao_ajax(document.getElementById('tarefa_social').value, 0);
	}


function popContatos(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Contatos", 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('tarefa_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('tarefa_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados,'contatos','height=500,width=500,resizable,scrollbars=yes');
	}


function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.getElementById('tarefa_contatos').value=contato_id_string;contatos_id_selecionados=contato_id_string
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados, 'combo_contatos');
	__buildTooltip();
	}




var contatos_id_selecionados2 = '';

function popContatos2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('tarefa_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados2, window.setContatos2, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos2&cia_id='+document.getElementById('tarefa_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados2, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos2(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.getElementById('email_contatos').value = contato_id_string;
	contatos_id_selecionados2 = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados2, 'combo_contatos2');
	__buildTooltip();
	}











function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('tarefa_dept').value+'&cia_id='+document.getElementById('tarefa_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('tarefa_dept').value+'&cia_id='+document.getElementById('tarefa_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('tarefa_cia').value=cia_id;
	document.getElementById('tarefa_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamentos']) ?>", 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('tarefa_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('tarefa_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.frmEditar.tarefa_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}



function mudar_tarefa_tipo(){
	xajax_mudar_tarefa_tipo_ajax(document.getElementById('tarefa_tipo').value, 'tarefa_tipo', 'combo_tarefa_tipo','class=texto size=1 style="width:200px;" onchange="mudar_tarefa_tipo();"');
	}

function popEditarPoligono(projeto_id, tarefa_id) {
		if(parent && parent.gpwebApp && parent.gpwebApp.editarAreaProjeto) parent.gpwebApp.editarAreaProjeto(projeto_id, tarefa_id);
		else window.open('./index.php?m=projetos&a=editar_poligono&dialogo=1&chamar_volta=setCoordenadas&projeto_id='+projeto_id+'&tarefa_id='+tarefa_id, 'Coordenadas','height=500,width=800,resizable,scrollbars=yes, left=0, top=0');
		}

function popTarefa(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['tarefa']) ?>", 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=<?php echo (int)$tarefa_projeto ?>', window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=<?php echo (int)$tarefa_projeto ?>', 'tarefa','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}


function setTarefa(chave, valor){
	document.getElementById('tarefa_superior').value = (chave > 0 ? chave : null);
	document.getElementById('nome_tarefa').value = valor;
	}


function mudar_cidades(){
	document.getElementById('tarefa_cidade').length=0;
	var estado=document.getElementById('tarefa_estado').value;
	<?php
	echo "if (estado) {xajax_selecionar_cidades_ajax(estado,'tarefa_cidade','combo_cidade', \"class='texto' size=1 style='width:350px;' ".($social ? "onchange='mudar_comunidades()'" : '')."\",'');}";
	if ($social) echo "document.getElementById('tarefa_comunidade').length=0;";
	?>
	}

<?php	if ($social){	?>
function mudar_comunidades(){
	var municipio_id=document.getElementById('tarefa_cidade').value;
	xajax_selecionar_comunidade_ajax(municipio_id, 'tarefa_comunidade', 'combo_comunidade_social', 'class="texto" size=1 style="width:350px;"', '', '');
	}
<?php } ?>




function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('tarefa_cia').value,'tarefa_cia','combo_cia', 'class="texto" size=1 style="width:350px;" onchange="javascript:mudar_om();"');
	}


function popGerente() {
		if (window.parent.gpwebApp)parent.gpwebApp.popUp("Respons�vel", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('tarefa_cia').value+'&usuario_id='+document.getElementById('tarefa_dono').value, window.setGerente, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('tarefa_cia').value+'&usuario_id='+document.getElementById('tarefa_dono').value, 'Gerente','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('tarefa_dono').value=usuario_id;
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['projeto']) ?>", 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=0&tabela=projetos&cia_id='+document.getElementById('tarefa_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&cia_id='+document.getElementById('tarefa_cia').value, 'Projetos','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){

	if (confirm("Tem certeza que deseja mover est<?php echo ($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' para outr'.$config['genero_projeto'].' '.$config['projeto'].'?' ?>")) {
		frmEditar.nova_tarefa_projeto.value=(chave > 0 ? chave : null);
		enviarDados(document.frmEditar);
		}
	}



var municipios_selecionados = '<?php echo implode(',', $municipios_selecionados)?>';

function popMunicipios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Munic�pios", 1000, 700, 'm=publico&a=selecionar_municipios&dialogo=1&chamar_volta=setMunicipios&valores='+municipios_selecionados, window.setMunicipios, window);
	else window.open('./index.php?m=publico&a=selecionar_municipios&dialogo=1&chamar_volta=setMunicipios&valores='+municipios_selecionados, 'Munic�pios','height=500,width=500,resizable,scrollbars=yes');
	}


function setMunicipios(municipios_id_string){
	if(!municipios_id_string) municipios_id_string = '';
	document.frmEditar.tarefa_municipios.value = municipios_id_string;
	municipios_selecionados = municipios_id_string;
	xajax_exibir_municipios(municipios_selecionados);
	__buildTooltip();
	}

<?php if ($exibir['latitude'] || $exibir['longitude']){ ?>


	var pi = 3.14159265358979;
	/* Ellipsoide (WGS84) */
	/* var sm_a = 6378137.0; */
	var sm_a = 6378160.0;
	var sm_b = 6356752.314;
	var sm_EccSquared = 6.69437999013e-03;
	var wnumero = 0
	var wgrau = 0
	var wmin = 0
	var wsec = 0
	var UTMScaleFactor = 0.9996;



	function DegToRad (deg){
	  return (deg / 180.0 * pi)
		}

	function RadToDeg (rad){
	  return (rad / pi * 180.0)
		}

	function ArcLengthOfMeridian (phi){
	  var alpha, beta, gamma, delta, epsilon, n;
	  var result;
	  n = (sm_a - sm_b) / (sm_a + sm_b);
	  alpha = ((sm_a + sm_b) / 2.0) * (1.0 + (Math.pow (n, 2.0) / 4.0) + (Math.pow (n, 4.0) / 64.0));
	  beta = (-3.0 * n / 2.0) + (9.0 * Math.pow (n, 3.0) / 16.0) + (-3.0 * Math.pow (n, 5.0) / 32.0);
	  gamma = (15.0 * Math.pow (n, 2.0) / 16.0) + (-15.0 * Math.pow (n, 4.0) / 32.0);
	  delta = (-35.0 * Math.pow (n, 3.0) / 48.0) + (105.0 * Math.pow (n, 5.0) / 256.0);
	  epsilon = (315.0 * Math.pow (n, 4.0) / 512.0);
		result = alpha * (phi + (beta * Math.sin (2.0 * phi)) + (gamma * Math.sin (4.0 * phi)) + (delta * Math.sin (6.0 * phi)) + (epsilon * Math.sin (8.0 * phi)));
		return result;
		}

	function UTMCentralMeridian (zone){
	  var cmeridian;
	  cmeridian = DegToRad (-183.0 + (zone * 6.0));
	  return cmeridian;
		}

	function FootpointLatitude (y){
	  var y_, alpha_, beta_, gamma_, delta_, epsilon_, n;
	  var result;
	  n = (sm_a - sm_b) / (sm_a + sm_b);
	  alpha_ = ((sm_a + sm_b) / 2.0) * (1 + (Math.pow (n, 2.0) / 4) + (Math.pow (n, 4.0) / 64));
	  y_ = y / alpha_;
	  beta_ = (3.0 * n / 2.0) + (-27.0 * Math.pow (n, 3.0) / 32.0) + (269.0 * Math.pow (n, 5.0) / 512.0);
	  gamma_ = (21.0 * Math.pow (n, 2.0) / 16.0) + (-55.0 * Math.pow (n, 4.0) / 32.0);
	  delta_ = (151.0 * Math.pow (n, 3.0) / 96.0) + (-417.0 * Math.pow (n, 5.0) / 128.0);
	  epsilon_ = (1097.0 * Math.pow (n, 4.0) / 512.0);
	  result = y_ + (beta_ * Math.sin (2.0 * y_))  + (gamma_ * Math.sin (4.0 * y_)) + (delta_ * Math.sin (6.0 * y_))  + (epsilon_ * Math.sin (8.0 * y_));
	  return result;
		}

	function MapLatLonToXY (phi, lambda, lambda0, xy){
	  var N, nu2, ep2, t, t2, l;
	  var l3coef, l4coef, l5coef, l6coef, l7coef, l8coef;
	  var tmp;
	  ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
	  nu2 = ep2 * Math.pow (Math.cos (phi), 2.0);
	  N = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nu2));
	  t = Math.tan (phi);
	  t2 = t * t;
	  tmp = (t2 * t2 * t2) - Math.pow (t, 6.0);
	  l = lambda - lambda0;
	  l3coef = 1.0 - t2 + nu2;
	  l4coef = 5.0 - t2 + 9 * nu2 + 4.0 * (nu2 * nu2);
	  l5coef = 5.0 - 18.0 * t2 + (t2 * t2) + 14.0 * nu2 - 58.0 * t2 * nu2;
	  l6coef = 61.0 - 58.0 * t2 + (t2 * t2) + 270.0 * nu2 - 330.0 * t2 * nu2;
	  l7coef = 61.0 - 479.0 * t2 + 179.0 * (t2 * t2) - (t2 * t2 * t2);
	  l8coef = 1385.0 - 3111.0 * t2 + 543.0 * (t2 * t2) - (t2 * t2 * t2);
	  xy[0] = N * Math.cos (phi) * l   + (N / 6.0 * Math.pow (Math.cos (phi), 3.0) * l3coef * Math.pow (l, 3.0)) + (N / 120.0 * Math.pow (Math.cos (phi), 5.0) * l5coef * Math.pow (l, 5.0)) + (N / 5040.0 * Math.pow (Math.cos (phi), 7.0) * l7coef * Math.pow (l, 7.0));
	  xy[1] = ArcLengthOfMeridian (phi) + (t / 2.0 * N * Math.pow (Math.cos (phi), 2.0) * Math.pow (l, 2.0)) + (t / 24.0 * N * Math.pow (Math.cos (phi), 4.0) * l4coef * Math.pow (l, 4.0)) + (t / 720.0 * N * Math.pow (Math.cos (phi), 6.0) * l6coef * Math.pow (l, 6.0)) + (t / 40320.0 * N * Math.pow (Math.cos (phi), 8.0) * l8coef * Math.pow (l, 8.0));
	  return;
		}

	function MapXYToLatLon (x, y, lambda0, philambda){
	  var phif, Nf, Nfpow, nuf2, ep2, tf, tf2, tf4, cf;
	  var x1frac, x2frac, x3frac, x4frac, x5frac, x6frac, x7frac, x8frac;
	  var x2poly, x3poly, x4poly, x5poly, x6poly, x7poly, x8poly;
	  phif = FootpointLatitude (y);
	  ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
	  cf = Math.cos (phif);
	  nuf2 = ep2 * Math.pow (cf, 2.0);
	  Nf = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nuf2));
	  Nfpow = Nf;
	  tf = Math.tan (phif);
	  tf2 = tf * tf;
	  tf4 = tf2 * tf2;
	  x1frac = 1.0 / (Nfpow * cf);
	  Nfpow *= Nf;   /* now equals Nf**2) */
	  x2frac = tf / (2.0 * Nfpow);
	  Nfpow *= Nf;   /* now equals Nf**3) */
	  x3frac = 1.0 / (6.0 * Nfpow * cf);
	  Nfpow *= Nf;   /* now equals Nf**4) */
	  x4frac = tf / (24.0 * Nfpow);
	  Nfpow *= Nf;   /* now equals Nf**5) */
	  x5frac = 1.0 / (120.0 * Nfpow * cf);
	  Nfpow *= Nf;   /* now equals Nf**6) */
	  x6frac = tf / (720.0 * Nfpow);
	  Nfpow *= Nf;   /* now equals Nf**7) */
	  x7frac = 1.0 / (5040.0 * Nfpow * cf);
	  Nfpow *= Nf;   /* now equals Nf**8) */
	  x8frac = tf / (40320.0 * Nfpow);
	  x2poly = -1.0 - nuf2;
	  x3poly = -1.0 - 2 * tf2 - nuf2;
	  x4poly = 5.0 + 3.0 * tf2 + 6.0 * nuf2 - 6.0 * tf2 * nuf2	- 3.0 * (nuf2 *nuf2) - 9.0 * tf2 * (nuf2 * nuf2);
	  x5poly = 5.0 + 28.0 * tf2 + 24.0 * tf4 + 6.0 * nuf2 + 8.0 * tf2 * nuf2;
	  x6poly = -61.0 - 90.0 * tf2 - 45.0 * tf4 - 107.0 * nuf2	+ 162.0 * tf2 * nuf2;
	  x7poly = -61.0 - 662.0 * tf2 - 1320.0 * tf4 - 720.0 * (tf4 * tf2);
	  x8poly = 1385.0 + 3633.0 * tf2 + 4095.0 * tf4 + 1575 * (tf4 * tf2);
	  philambda[0] = phif + x2frac * x2poly * (x * x)	+ x4frac * x4poly * Math.pow (x, 4.0)	+ x6frac * x6poly * Math.pow (x, 6.0)	+ x8frac * x8poly * Math.pow (x, 8.0);
	  philambda[1] = lambda0 + x1frac * x	+ x3frac * x3poly * Math.pow (x, 3.0)	+ x5frac * x5poly * Math.pow (x, 5.0)	+ x7frac * x7poly * Math.pow (x, 7.0);
	  return;
		}

	function LatLonToUTMXY (lat, lon, zone, xy){
	  MapLatLonToXY (lat, lon, UTMCentralMeridian (zone), xy);
	  /* Adjust easting and northing for UTM system. */
	  xy[0] = xy[0] * UTMScaleFactor + 500000.0;
	  xy[1] = xy[1] * UTMScaleFactor;
	  if (xy[1] < 0.0) xy[1] = xy[1] + 10000000.0;
	  return zone;
		}

	function UTMXYToLatLon (x, y, zone, southhemi, latlon){
	  var cmeridian;
	  x -= 500000.0;
	  x /= UTMScaleFactor;
	  /* If in southern hemisphere, adjust y accordingly. */
	  if (southhemi)
	  y -= 10000000.0;
	  y /= UTMScaleFactor;
	 	cmeridian = UTMCentralMeridian (zone);
	  MapXYToLatLon (x, y, cmeridian, latlon);
	  return;
		}

	function btnToUTM_OnClick (){
	  var xy = new Array(2);
	  if (document.frmEditar.txtlongraus.value!=null) {
	   	wgrau = parseFloat (document.frmEditar.txtlongraus.value);
	   	wmin = parseFloat (document.frmEditar.txtlonmin.value) / 60;
	  	wsec = parseFloat (document.frmEditar.txtlonsec.value) / 3600;
	   	wnumero = wgrau + wmin + wsec

	   	if (wmin <0) wmin=wmin*-1;
	   	if (wsec <0) wsec=wsec*-1;

			if (wgrau >= 0) wnumero = wgrau + wmin + wsec ;
			if (wgrau < 0) wnumero = wgrau - wmin - wsec ;

	   	document.frmEditar.tarefa_longitude.value = wnumero;
			}
	  if (isNaN (parseFloat (document.frmEditar.tarefa_longitude.value))) {
	    alert ("Entre com uma longitude v�lida.");
	    return false;
			}
	  lon = parseFloat (document.frmEditar.tarefa_longitude.value);
	  if ((lon < -180.0) || (180.0 <= lon)) {
	    alert ("Entre com um n�mero para latitude entre -180, 180.");
	    return false;
			}
		if (document.frmEditar.txtlatgraus.value!=null) {
	    wgrau = parseFloat (document.frmEditar.txtlatgraus.value);
	    wmin = parseFloat (document.frmEditar.txtlatmin.value) / 60;
	    wsec = parseFloat (document.frmEditar.txtlatsec.value) / 3600;

	   	wnumero = wgrau + wmin + wsec

	   	if (wmin <0) wmin=wmin*-1;
	   	if (wsec <0) wsec=wsec*-1;

			if (wgrau >= 0) wnumero = wgrau + wmin + wsec ;
			if (wgrau < 0) wnumero = wgrau - wmin - wsec ;


	    document.frmEditar.tarefa_latitude.value = wnumero;
	  	}
	  if (isNaN (parseFloat (document.frmEditar.tarefa_latitude.value))) {
	    alert ("Entre com uma latitude v�lida.");
	    return false;
			}
	  lat = parseFloat (document.frmEditar.tarefa_latitude.value);
	  if ((lat < -90.0) || (90.0 < lat)) {
	    alert ("Entre com um n�mero para latitude entre -90, 90.");
	    return false;
			}
	  zone = Math.floor ((lon + 180.0) / 6) + 1;
	  zone = LatLonToUTMXY (DegToRad (lat), DegToRad (lon), zone, xy);
	  document.frmEditar.txtX.value = xy[0];
	  document.frmEditar.txtY.value = xy[1];
	  document.frmEditar.txtZone.value = zone;
	  if (lat < 0) document.frmEditar.rbtnHemisphere[1].checked = true;
	  else document.frmEditar.rbtnHemisphere[0].checked = true;
	  return true;
		}

	function btnToGeographic_OnClick (){
	  latlon = new Array(2);
	  var x, y, zone, southhemi;
	  if (isNaN (parseFloat (document.frmEditar.txtX.value))) {
	    alert ("Entre com uma Coordenada v�ida para X.");
	    return false;
			}
	  x = parseFloat (document.frmEditar.txtX.value);
	  x = x - 75;
	  if (isNaN (parseFloat (document.frmEditar.txtY.value))) {
	    alert ("Entre com uma Coordenada v�ida para Y.");
	    return false;
			}
	  y = parseFloat (document.frmEditar.txtY.value);
	  y = y - 25;
	  if (isNaN (parseInt (document.frmEditar.txtZone.value))) {
	    alert ("Entre com uma Zona v�lida.");
	    return false;
			}
	  zone = parseFloat (document.frmEditar.txtZone.value);
	  if ((zone < 1) || (60 < zone)) {
	    alert ("Zona Inv�lida entre com um n�mero de 1 � 60");
	    return false;
			}
	  if (document.frmEditar.rbtnHemisphere[1].checked == true) southhemi = true;
	  else southhemi = false;
	  UTMXYToLatLon (x, y, zone, southhemi, latlon);
	  document.frmEditar.tarefa_longitude.value = RadToDeg (latlon[1]);
	  document.frmEditar.tarefa_latitude.value = RadToDeg (latlon[0]);
	  wnumero = Math.abs(RadToDeg (latlon[1]));
	  wgrau = Math.floor(wnumero);
	  wmin = Math.floor((wnumero - wgrau) * 60);
	  wsec = Math.floor((((wnumero - wgrau) * 60) - wmin) * 60);
	  document.frmEditar.txtlongraus.value = wgrau;
	  document.frmEditar.txtlonmin.value = wmin;
	  document.frmEditar.txtlonsec.value = wsec;
	  wnumero = Math.abs(RadToDeg (latlon[0]));
	  wgrau = Math.floor(wnumero);
	  wmin = Math.floor((wnumero - wgrau) * 60);
	  wsec = Math.floor((((wnumero - wgrau) * 60) - wmin) * 60);
	  document.frmEditar.txtlatgraus.value = wgrau;
	  document.frmEditar.txtlatmin.value = wmin;
	  document.frmEditar.txtlatsec.value = wsec;
	  return true;
		}

	function converter_decimal(){
		var long=frmEditar.tarefa_longitude.value;
		grau_long = parseInt(long);
		minuto=long-grau_long;
		minuto=minuto*60;
		if (minuto < 0) minuto=minuto*-1;
		minuto_long=parseInt(minuto);
		segundo=minuto-minuto_long;
		segundo=segundo*60;
		segundo_long=parseInt(segundo);
		frmEditar.txtlongraus.value=grau_long;
		frmEditar.txtlonmin.value=minuto_long;
		frmEditar.txtlonsec.value=segundo_long;

		var lat=frmEditar.tarefa_latitude.value;
		grau_lat = parseInt(lat);
		minuto=lat-grau_lat;
		minuto=minuto*60;
		if (minuto < 0) minuto=minuto*-1;
		minuto_lat=parseInt(minuto);
		segundo=minuto-minuto_lat;
		segundo=segundo*60;
		segundo_lat=parseInt(segundo);

		frmEditar.txtlatgraus.value=grau_lat;
		frmEditar.txtlatmin.value=minuto_lat;
		frmEditar.txtlatsec.value=segundo_lat;
		}


	converter_decimal();

<?php } ?>

</script>

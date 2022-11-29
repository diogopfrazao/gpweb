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
global $m, $a, $u, $Aplic;



if (!defined('BASE_DIR')) die('N�o deveria acessar este arquivo diretamente.');

$extra=array();	
$chamarVolta=getParam($_REQUEST, 'chamar_volta', 0);
$tabela=getParam($_REQUEST, 'tabela', 0);
$usuario_id=getParam($_REQUEST, 'usuario_id', 0);

if (isset($_REQUEST['textobusca'])) $Aplic->setEstado('textobusca', getParam($_REQUEST, 'textobusca', null));
$textobusca = ($Aplic->getEstado('textobusca') ? $Aplic->getEstado('textobusca') : '');

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));
	
if (isset($_REQUEST['nao_apenas_superiores'])) $Aplic->setEstado('nao_apenas_superiores', getParam($_REQUEST, 'nao_apenas_superiores', null));
$nao_apenas_superiores = $Aplic->getEstado('nao_apenas_superiores') !== null ? $Aplic->getEstado('nao_apenas_superiores') : 0;

if (isset($_REQUEST['cia_dept']) && $_REQUEST['cia_dept'])	$Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_dept', null));
else if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;
	
if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null ? $Aplic->getEstado('dept_id') : null;
if ($dept_id) $ver_subordinadas = null;

$aceita_portfolio=getParam($_REQUEST, 'aceita_portfolio', null);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);

$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

$edicao=getParam($_REQUEST, 'edicao', 0);


$nao_ha='N�o foi encontrado';
$nenhum='Nenhum';
$ok = $chamarVolta & $tabela;
$titulo = 'Seletor Gen�rico';
$classeModulo = $Aplic->getClasseModulo($tabela);
if ($classeModulo && file_exists($classeModulo)) require_once $classeModulo;

$sql = new BDConsulta;
$sql->adTabela($tabela);
$resultadoConsulta = false;


echo '<script type="text/javascript">function setFechar(chave, valor){
if(parent && parent.gpwebApp){if (chave) parent.gpwebApp._popupCallback(chave, valor); else parent.gpwebApp._popupCallback(null, "");} else {
if (chave) window.opener.'.$chamarVolta.'(chave, valor); else window.opener.'.$chamarVolta.'(null, ""); window.close();}}
function cancelarSelecao(){if(parent && parent.gpwebApp && parent.gpwebApp._popupWin) parent.gpwebApp._popupWin.close(); else window.close();}</script>';


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input name="a" type="hidden" value="selecionar_multiplo" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input name="dialogo" type="hidden" value="1" />';
echo '<input type="hidden" name="chamarVolta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="tabela" value="'.$tabela.'" />';
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
echo '<input type="hidden" name="edicao" value="'.$edicao.'" />';
echo '<input type="hidden" name="cia_dept" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="nao_apenas_superiores" value="'.$nao_apenas_superiores.'" />';
echo '<input type="hidden" name="aceita_portfolio" value="'.$aceita_portfolio.'" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="enviado" value="0" />';


if (getParam($_REQUEST, 'enviado', 0)){
	
	$qnt=0;
	$campos='';
	foreach(getParam($_REQUEST, 'campos', array()) as $chave => $valor) if ($valor) $campos.=($qnt++ ? ',' : '').$valor;
	echo '<script>setFechar("'.$campos.'", "");</script>';
	}



echo estiloTopoCaixa();
echo '<table class="std" width="100%" cellspacing=0 cellpadding=0>';

$procurar_om='<tr><td align=right>'.ucfirst($config['organizacao']).':</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\'; document.env.submit();">'.imagem('icones/organizacao_p.gif').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif').'</a>' : '').'</td>' : '').'</tr>'.($dept_id ? '<tr><td align=right>'.ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif').'</a></td>' : '').'</tr>' : '');
$botao_filtrar='<tr><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png').'</a></td></tr>';

if ($tabela=='projetos'){
	
	$portfolio=getParam($_REQUEST, 'portfolio', null);
	$portfolio_pai=getParam($_REQUEST, 'portfolio_pai', null);
	
	
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'projeto\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

	if (isset($_REQUEST['projeto_tipo'])) $Aplic->setEstado('projeto_tipo', getParam($_REQUEST, 'projeto_tipo', null));
	$projeto_tipo = $Aplic->getEstado('projeto_tipo') !== null ? $Aplic->getEstado('projeto_tipo') : -1;
	
	if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
		
	if (isset($_REQUEST['projetostatus']))	$Aplic->setEstado('projetostatus', getParam($_REQUEST, 'projetostatus', null));
	$projetostatus = $Aplic->getEstado('projetostatus') !== null ? $Aplic->getEstado('projetostatus') : 0;
	
	if (isset($_REQUEST['favorito_id']))	$Aplic->setEstado('projeto_favorito', getParam($_REQUEST, 'favorito_id', null));
	$favorito_id = $Aplic->getEstado('projeto_favorito') !== null ? $Aplic->getEstado('projeto_favorito') : 0;
	
	if (isset($_REQUEST['estado_sigla']))	$Aplic->setEstado('estado_sigla', getParam($_REQUEST, 'estado_sigla', null));
	$estado_sigla = ($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : '');
	
	if (isset($_REQUEST['municipio_id']))	$Aplic->setEstado('municipio_id', getParam($_REQUEST, 'municipio_id', null));
	$municipio_id = ($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '');
	
	if (isset($_REQUEST['responsavel']))	$Aplic->setEstado('responsavel', getParam($_REQUEST, 'responsavel', null));
	$responsavel = $Aplic->getEstado('responsavel') !== null ? $Aplic->getEstado('responsavel') : 0;
	
	if (isset($_REQUEST['supervisor']))	$Aplic->setEstado('supervisor', getParam($_REQUEST, 'supervisor', null));
	$supervisor = $Aplic->getEstado('supervisor') !== null ? $Aplic->getEstado('supervisor') : 0;
	
	if (isset($_REQUEST['autoridade']))	$Aplic->setEstado('autoridade', getParam($_REQUEST, 'autoridade', null));
	$autoridade = $Aplic->getEstado('autoridade') !== null ? $Aplic->getEstado('autoridade') : 0;
	
	if (isset($_REQUEST['projeto_setor']))	$Aplic->setEstado('projeto_setor',getParam($_REQUEST, 'projeto_setor', null));
	$projeto_setor = $Aplic->getEstado('projeto_setor') !== null ? $Aplic->getEstado('projeto_setor') : '';
	
	if (isset($_REQUEST['projeto_segmento']))	$Aplic->setEstado('projeto_segmento',getParam($_REQUEST, 'projeto_segmento', null));
	$projeto_segmento = $Aplic->getEstado('projeto_segmento') !== null ? $Aplic->getEstado('projeto_segmento') : '';
	
	if (isset($_REQUEST['projeto_intervencao']))	$Aplic->setEstado('projeto_intervencao', getParam($_REQUEST, 'projeto_intervencao', null));
	$projeto_intervencao = $Aplic->getEstado('projeto_intervencao') !== null ? $Aplic->getEstado('projeto_intervencao') : '';
	
	if (isset($_REQUEST['projeto_tipo_intervencao']))	$Aplic->setEstado('projeto_tipo_intervencao', getParam($_REQUEST, 'projeto_tipo_intervencao', null));
	$projeto_tipo_intervencao = $Aplic->getEstado('projeto_tipo_intervencao') !== null ? $Aplic->getEstado('projeto_tipo_intervencao') : '';
	
	if (isset($_REQUEST['projtextobusca']))	$Aplic->setEstado('projtextobusca', getParam($_REQUEST, 'projtextobusca', ''));
	$projtextobusca = $Aplic->getEstado('projtextobusca') !== null ? $Aplic->getEstado('projtextobusca') : '';
	
	$projetos_status=array();
	if (!$Aplic->profissional) $projetos_status[0]='&nbsp;';
	$projetos_status[-1]='Ativos';
	$projetos_status[-2]='Inativos';
	$projetos_status += getSisValor('StatusProjeto');
	
	$projeto_tipos=array();
	if(!$Aplic->profissional) $projeto_tipos[-1] = '';
	$projeto_tipos += getSisValor('TipoProjeto');
	
	$estado=array(0 => '&nbsp;');
	$sql->adTabela('estado');
	$sql->adCampo('estado_sigla, estado_nome');
	$sql->adOrdem('estado_nome');
	$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
	$sql->limpar();


	$sql->adTabela('favorito');
	$sql->adCampo('favorito_id, favorito_nome');
	$sql->adOnde('favorito_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('favorito_geral!=1');
	$sql->adOnde('favorito_ativo=1');
	$sql->adOnde('favorito_projeto=1');
	$vetor_favoritos=$sql->ListaChave();
	$sql->limpar();
	
	$sql->adTabela('favorito');
	$sql->esqUnir('favorito_usuario', 'favorito_usuario', 'favorito_usuario_favorito= favorito.favorito_id');
	if ($dept_id) {
		$sql->esqUnir('favorito_dept','favorito_dept', 'favorito_dept_favorito=favorito.favorito_id');
		$sql->adOnde('favorito_dept='.(int)$dept_id.' OR favorito_dept_dept='.(int)$dept_id.' OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
		}
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('favorito_cia', 'favorito_cia', 'favorito.favorito_id=favorito_cia_favorito');
		$sql->adOnde('favorito_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR favorito_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
		}		
	elseif ($cia_id && !$lista_cias) $sql->adOnde('favorito_cia='.(int)$cia_id.' OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
	elseif ($lista_cias) $sql->adOnde('favorito_cia IN ('.$lista_cias.') OR favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('favorito_acesso=1');
	$sql->adOnde('favorito_geral=1');
	$sql->adOnde('favorito_ativo=1');
	$sql->adOnde('favorito_ativo=1');
	$sql->adOnde('favorito_projeto=1');
	$sql->adCampo('favorito_id, favorito_nome');
	$vetor_favoritos1=$sql->ListaChave();
	$sql->limpar();
	
	$sql->adTabela('favorito');
	$sql->esqUnir('favorito_usuario', 'favorito_usuario', 'favorito_usuario_favorito= favorito.favorito_id');
	$sql->adOnde('favorito_usuario_usuario = '.(int)$Aplic->usuario_id.' OR favorito_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('favorito_acesso=2');
	$sql->adOnde('favorito_geral=1');
	$sql->adOnde('favorito_ativo=1');
	$sql->adOnde('favorito_ativo=1');
	$sql->adOnde('favorito_projeto=1');
	$sql->adCampo('favorito_id, favorito_nome');
	$vetor_favoritos2=$sql->ListaChave();
	$sql->limpar();
	
	$vetor_favoritos=$vetor_favoritos+$vetor_favoritos1+$vetor_favoritos2;
	
	$favoritos='';
	if (count($vetor_favoritos)) {
		$vetor_favoritos[0]='';
	if (!isset($vetor_favoritos[(int)$favorito_id])) $favorito_id=0;
		$favoritos='<tr><td align="right" style="white-space: nowrap">'.dica('Favorito', 'Escolha um favorito.').'Favorito:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($vetor_favoritos, 'favorito_id', 'class="texto" style="width:250px;" onchange="document.env.submit()"', $favorito_id).'</td></tr>';
		}
	else $favorito_id=null;


	$projeto_expandido =getParam($_REQUEST, 'projeto_expandido', 0);
	
	if ($favorito_id) $projeto_expandido=0;
	
	
	if ($Aplic->profissional){
		if (isset($_REQUEST['template'])) $Aplic->setEstado('template', getParam($_REQUEST, 'template', null));
		$template = $Aplic->getEstado('template') !== null ? $Aplic->getEstado('template') : 0;
		$opcoes_modelo=array(0=>'N�o', 1=>'Sim');
		$ser_template='<tr><td align="right" style="white-space: nowrap">Modelo:</td><td width="100%" colspan="2">'.selecionaVetor($opcoes_modelo, 'template', 'style="width:250px;" class="texto"', $template).'</div></td></tr>';	
		}
	else {
		$template = false;
		$ser_template='';
		}			

	$procurar_estado='<tr><td align="right">Estado:</td><td>'.selecionaVetor($estado, 'estado_sigla', 'class="texto" style="width:250px;" size="1" onchange="mudar_cidades();"', $estado_sigla).'</td></tr>';
	$procurar_municipio='<tr><td align="right">Munic�pio:</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($estado_sigla, 'municipio_id', 'class="texto"'.($Aplic->profissional ? ' multiple' :'').' style="width:250px;"', '', $municipio_id, true, false).'</div></td></tr>';
	$procurar_status='<tr><td align="right" style="white-space: nowrap">Status:</td><td align="left" style="white-space: nowrap">'. selecionaVetor($projetos_status, 'projetostatus', 'size="1" style="width:250px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $projetostatus) .'</td></tr>';
	$procura_categoria='<tr><td align="right" style="white-space: nowrap">'.ucfirst($config['categoria']).':</td><td align="left" style="white-space: nowrap">'. selecionaVetor($projeto_tipos, 'projeto_tipo', 'size="1" style="width:250px;"'.($Aplic->profissional ? ' multiple' :'').' class="texto"', $projeto_tipo) .'</td></tr>';
	$procura_pesquisa='<tr><td align="right" style="white-space: nowrap">Pesquisar:</td><td align="left" style="white-space: nowrap"><input type="text" class="texto" style="width:250px;" id="projtextobusca" name="projtextobusca" onChange="document.env.submit();" value="'.$projtextobusca.'" /></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&dialogo=1&projtextobusca=\');">'.imagem('icones/limpar_p.gif').'</a></td></tr>';
	$procurar_responsavel='<tr><td align=right>'.ucfirst($config['gerente']).':</td><td><input type="hidden" id="responsavel" name="responsavel" value="'.$responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($responsavel).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif').'</a></td></tr>';
	$procurar_supervisor='<tr><td align=right>'.ucfirst($config['supervisor']).':</td><td><input type="hidden" id="supervisor" name="supervisor" value="'.$supervisor.'" /><input type="text" id="nome_supervisor" name="nome_supervisor" value="'.nome_usuario($supervisor).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSupervisor();">'.imagem('icones/usuarios.gif').'</a></td></tr>';
	$procurar_autoridade='<tr><td align=right>'.ucfirst($config['autoridade']).':</td><td><input type="hidden" id="autoridade" name="autoridade" value="'.$autoridade.'" /><input type="text" id="nome_autoridade" name="nome_autoridade" value="'.nome_usuario($autoridade).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAutoridade();">'.imagem('icones/usuarios.gif').'</a></td></tr>';
	
	$procura_setor='';
	$procura_segmento='';
	$procura_intervencao='';
	$procura_tipo_intervencao='';
	if ($exibir['setor']){

		$setor = array(0 => '&nbsp;') + getSisValor('Setor');
		
		$segmento=array(0 => '&nbsp;');
		if ($projeto_setor){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_setor.'"');
			$sql->adOrdem('sisvalor_valor');
			$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
			
		$intervencao=array(0 => '&nbsp;');
		if ($projeto_segmento){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_segmento.'"');
			$sql->adOrdem('sisvalor_valor');
			$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
		
		$tipo_intervencao=array(0 => '&nbsp;');
		if ($projeto_intervencao){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_chave_id_pai="'.$projeto_intervencao.'"');
			$sql->adOrdem('sisvalor_valor');
			$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
			$sql->limpar();
			}
		
		$procura_setor='<tr><td align="right" style="white-space: nowrap">'.ucfirst($config['setor']).':</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'projeto_setor', 'style="width:250px;" class="texto" onchange="mudar_segmento();"', $projeto_setor).'</td></tr>';
		$procura_segmento='<tr><td align="right" style="white-space: nowrap">'.ucfirst($config['segmento']).':</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'projeto_segmento', 'style="width:250px;" class="texto" onchange="mudar_intervencao();"', $projeto_segmento).'</div></td></tr>';
	 	$procura_intervencao='<tr><td align="right" style="white-space: nowrap">'.ucfirst($config['intervencao']).':</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'projeto_intervencao', 'style="width:250px;" class="texto" onchange="mudar_tipo_intervencao();"', $projeto_intervencao).'</div></td></tr>';
		$procura_tipo_intervencao='<tr><td align="right" style="white-space: nowrap">'.ucfirst($config['tipo']).':</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'projeto_tipo_intervencao', 'style="width:250px;" class="texto"', $projeto_tipo_intervencao).'</div></td></tr>';
		}
	
	if (!$projeto_expandido){
		if ($nao_apenas_superiores) $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=0; env.submit();">'.imagem('icones/projeto_superior.gif').'</a></td></tr>';
		else $botao_superiores='<tr><td><a href="javascript: void(0);" onclick ="env.nao_apenas_superiores.value=1; env.submit();">'.imagem('icones/projeto_superior_cancela.gif').'</a></td></tr>';
		}
	else $botao_superiores='';
	
	

	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procura_setor.$procura_segmento.$procura_intervencao.$procura_tipo_intervencao.$procurar_estado.$procurar_municipio.$procurar_status.'</table></td>';	
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procurar_responsavel.$procurar_supervisor.$procurar_autoridade.$procura_categoria.$procura_pesquisa.$favoritos.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$botao_filtrar.$botao_superiores.'</table></td>';
	echo '</tr></table></td></tr>';


	
	if($Aplic->profissional){


		if (is_array($cia_id)) $cia_id=implode(',', $cia_id);
		if (is_array($dept_id)) $dept_id=implode(',', $dept_id);
		if (is_array($projeto_tipo)) $projeto_tipo=implode(',', $projeto_tipo);
		if (is_array($projeto_setor)) $projeto_setor=implode(',', $projeto_setor);
		if (is_array($projeto_segmento)) $projeto_segmento=implode(',', $projeto_segmento);
		if (is_array($projeto_intervencao)) $projeto_intervencao=implode(',', $projeto_intervencao);
		if (is_array($projeto_tipo_intervencao)) $projeto_tipo_intervencao=implode(',', $projeto_tipo_intervencao);
		if (is_array($estado_sigla)) $estado_sigla=implode(',', $estado_sigla);
		if (is_array($municipio_id)) $municipio_id=implode(',', $municipio_id);
		if (is_array($favorito_id)) $favorito_id=implode(',', $favorito_id);
		if (is_array($projetostatus)) $projetostatus=implode(',', $projetostatus);

		$Aplic->carregarComboMultiSelecaoJS();
		echo '<script type="text/javascript">';
		
		echo 'function criarComboCia(){$jq("#cia_id").multiSelect({multiple:false, onCheck: function(){mudar_om();}});}';
	
		echo 'function criarComboCidades(){$jq("#municipio_id").multiSelect();}';
		if ($exibir['setor']){
			echo 'function criarComboSegmento(){$jq("#projeto_segmento").multiSelect({multiple:false, onCheck: function(){mudar_intervencao();}});}';
			echo 'function criarComboIntervencao(){$jq("#projeto_intervencao").multiSelect({multiple:false, onCheck: function(){mudar_tipo_intervencao();}});}';
			echo 'function criarComboTipoIntervencao(){$jq("#projeto_tipo_intervencao").multiSelect({multiple:false});}';
			}
		echo '$jq(function(){';
		echo '  $jq("#projeto_tipo").multiSelect();';
		echo '  $jq("#projetostatus").multiSelect();';
		
		if (count($vetor_favoritos)) echo '  $jq("#favorito_id").multiSelect();';
		echo '  $jq("#estado_sigla").multiSelect({multiple:false, onCheck: function(){mudar_cidades();}});';                                                                                         
		if ($exibir['setor']) echo '  $jq("#projeto_setor").multiSelect({multiple:false, onCheck: function(){mudar_segmento();}});';
		
		echo 'criarComboCia();';
		echo 'criarComboCidades();';
		if ($exibir['setor']){
			echo 'criarComboSegmento();';
			echo 'criarComboIntervencao();';
			echo 'criarComboTipoIntervencao();';
			}
		echo '});';
		echo '</script>';
		}
	}


elseif ($tabela=='pratica_indicador'){
	$tr_ativo=$Aplic->modulo_ativo('tr');
	$pratica_indicador_projeto=getParam($_REQUEST, 'pratica_indicador_projeto', null);
	$pratica_indicador_tarefa=getParam($_REQUEST, 'pratica_indicador_tarefa', null);
	$pratica_indicador_perspectiva=getParam($_REQUEST, 'pratica_indicador_perspectiva', null);
	$pratica_indicador_tema=getParam($_REQUEST, 'pratica_indicador_tema', null);
	$pratica_indicador_objetivo_estrategico=getParam($_REQUEST, 'pratica_indicador_objetivo_estrategico', null);
	$pratica_indicador_fator=getParam($_REQUEST, 'pratica_indicador_fator', null);
	$pratica_indicador_estrategia=getParam($_REQUEST, 'pratica_indicador_estrategia', null);
	$pratica_indicador_meta=getParam($_REQUEST, 'pratica_indicador_meta', null);
	$pratica_indicador_pratica=getParam($_REQUEST, 'pratica_indicador_pratica', null);
	$pratica_indicador_acao=getParam($_REQUEST, 'pratica_indicador_acao', null);
	$pratica_indicador_canvas=getParam($_REQUEST, 'pratica_indicador_canvas', null);
	$pratica_indicador_risco=getParam($_REQUEST, 'pratica_indicador_risco', null);
	$pratica_indicador_risco_resposta=getParam($_REQUEST, 'pratica_indicador_risco_resposta', null);
	$pratica_indicador_calendario=getParam($_REQUEST, 'pratica_indicador_calendario', null);
	$pratica_indicador_monitoramento=getParam($_REQUEST, 'pratica_indicador_monitoramento', null);
	$pratica_indicador_ata=getParam($_REQUEST, 'pratica_indicador_ata', null);
	$pratica_indicador_mswot=getParam($_REQUEST, 'pratica_indicador_mswot', null);
	$pratica_indicador_swot=getParam($_REQUEST, 'pratica_indicador_swot', null);
	$pratica_indicador_operativo=getParam($_REQUEST, 'pratica_indicador_operativo', null);
	$pratica_indicador_instrumento=getParam($_REQUEST, 'pratica_indicador_instrumento', null);
	$pratica_indicador_recurso=getParam($_REQUEST, 'pratica_indicador_recurso', null);
	$pratica_indicador_problema=getParam($_REQUEST, 'pratica_indicador_problema', null);
	$pratica_indicador_demanda=getParam($_REQUEST, 'pratica_indicador_demanda', null);
	$pratica_indicador_programa=getParam($_REQUEST, 'pratica_indicador_programa', null);
	$pratica_indicador_licao=getParam($_REQUEST, 'pratica_indicador_licao', null);
	$pratica_indicador_evento=getParam($_REQUEST, 'pratica_indicador_evento', null);
	$pratica_indicador_link=getParam($_REQUEST, 'pratica_indicador_link', null);
	$pratica_indicador_avaliacao=getParam($_REQUEST, 'pratica_indicador_avaliacao', null);
	$pratica_indicador_tgn=getParam($_REQUEST, 'pratica_indicador_tgn', null);
	$pratica_indicador_brainstorm=getParam($_REQUEST, 'pratica_indicador_brainstorm', null);
	$pratica_indicador_gut=getParam($_REQUEST, 'pratica_indicador_gut', null);
	$pratica_indicador_causa_efeito=getParam($_REQUEST, 'pratica_indicador_causa_efeito', null);
	$pratica_indicador_arquivo=getParam($_REQUEST, 'pratica_indicador_arquivo', null);
	$pratica_indicador_forum=getParam($_REQUEST, 'pratica_indicador_forum', null);
	$pratica_indicador_checklist=getParam($_REQUEST, 'pratica_indicador_checklist', null);
	$pratica_indicador_agenda=getParam($_REQUEST, 'pratica_indicador_agenda', null);
	$pratica_indicador_agrupamento=getParam($_REQUEST, 'pratica_indicador_agrupamento', null);
	$pratica_indicador_patrocinador=getParam($_REQUEST, 'pratica_indicador_patrocinador', null);
	$pratica_indicador_template=getParam($_REQUEST, 'pratica_indicador_template', null);
	$pratica_indicador_painel=getParam($_REQUEST, 'pratica_indicador_painel', null);
	$pratica_indicador_painel_odometro=getParam($_REQUEST, 'pratica_indicador_painel_odometro', null);
	$pratica_indicador_painel_composicao=getParam($_REQUEST, 'pratica_indicador_painel_composicao', null);
	$pratica_indicador_tr=getParam($_REQUEST, 'pratica_indicador_tr', null);
	$pratica_indicador_me=getParam($_REQUEST, 'pratica_indicador_me', null);

	$tipos=array(
		''=>'',
		'projeto' => ucfirst($config['projeto']),
		'perspectiva'=> ucfirst($config['perspectiva']),
		'tema'=> ucfirst($config['tema']),
		'objetivo'=> ucfirst($config['objetivo']),
		'estrategia'=> ucfirst($config['iniciativa']),
		'meta'=>ucfirst($config['meta']),
		'acao'=> ucfirst($config['acao']),
		'pratica' => ucfirst($config['pratica']),
		);
	if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) $tipos['fator']=ucfirst($config['fator']);		
	if ($Aplic->modulo_ativo('atas')) $tipos['ata']='Ata de Reuni�o';
	if ($Aplic->modulo_ativo('swot')) {
		$tipos['mswot']='Matriz SWOT';
		$tipos['swot']='Campo SWOT';
		}
	if ($Aplic->modulo_ativo('operativo')) $tipos['operativo']='Plano Operativo';
	if ($Aplic->profissional) {
		$tipos['canvas']=ucfirst($config['canvas']);
		$tipos['risco']=ucfirst($config['risco']);
		$tipos['risco_resposta']=ucfirst($config['risco_resposta']);
		$tipos['calendario']='Agenda';
		$tipos['monitoramento']='Monitoramento';
		if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'editar')) $tipos['instrumento']=ucfirst($config['instrumento']);
		$tipos['recurso']=ucfirst($config['recurso']);
		if ($Aplic->modulo_ativo('problema')) $tipos['problema']=ucfirst($config['problema']);
		$tipos['demanda']='Demanda';
		$tipos['programa']=ucfirst($config['programa']);
		$tipos['licao']=ucfirst($config['licao']);
		$tipos['evento']='Evento';
		$tipos['link']='Link';
		$tipos['avaliacao']='Avalia��o';
		$tipos['tgn']=ucfirst($config['tgn']);
		$tipos['brainstorm']='Brainstorm';
		$tipos['gut']='Matriz GUT';
		$tipos['causa_efeito']='Diagrama de Causa-Efeito';
		$tipos['arquivo']='Arquivo';
		$tipos['forum']='F�rum';
		$tipos['checklist']='Checklist';
		$tipos['agenda']='Compromisso';
		if ($Aplic->modulo_ativo('agrupamento')) $tipos['agrupamento']='Agrupamento';
		if ($Aplic->modulo_ativo('patrocinadores')) $tipos['patrocinador']=ucfirst($config['patrocinador']);
		$tipos['template']='Modelo';
		$tipos['painel']='Painel de Indicador';
		$tipos['painel_odometro']='Od�metro de Indicador';
		$tipos['painel_composicao']='Composi��o de Pain�is';
		if ($tr_ativo) $tipos['tr']=ucfirst($config['tr']);
		if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $tipos['me']=ucfirst($config['me']);
		}
	asort($tipos);
	$tipo='';
	$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descri��o').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="textobusca" onChange="document.env.submit();" value="'.$textobusca.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procuraBuffer.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0>'.$botao_filtrar.'</table></td>';
	echo '</tr></table></td></tr>';
	
	
	echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0>';
	echo '<tr><td align="right" style="white-space: nowrap">Relacionado:</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'class="texto" style="width:230px;" onchange="mostrar()"', $tipo).'<td></tr>';
	
	echo '<tr '.($pratica_indicador_projeto || $pratica_indicador_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso o indicador seja espec�fico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo dever� constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_projeto" value="'.$pratica_indicador_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($pratica_indicador_projeto).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste �cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="document.env.submit();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_projeto || $pratica_indicador_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso o indicador seja espec�fico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever� constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tarefa" value="'.$pratica_indicador_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($pratica_indicador_tarefa).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste �cone '.imagem('icones/tarefa_p.gif').' escolher � qual '.$config['tarefa'].' o arquivo ir� pertencer.<br><br>Caso n�o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo ser� d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso o indicador seja espec�fico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo dever� constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_pratica" value="'.$pratica_indicador_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($pratica_indicador_pratica).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste �cone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso o indicador seja espec�fico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo dever� constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_acao" value="'.$pratica_indicador_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($pratica_indicador_acao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar A��o','Clique neste �cone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de a��o.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso o indicador seja espec�fico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo dever� constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_perspectiva" value="'.$pratica_indicador_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($pratica_indicador_perspectiva).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste �cone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso o indicador seja espec�fico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo dever� constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tema" value="'.$pratica_indicador_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($pratica_indicador_tema).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste �cone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_objetivo_estrategico ? '' : 'style="display:none"').' id="objetivo" ><td align="right" style="white-space: nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso o indicador seja espec�fico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo dever� constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_objetivo_estrategico" value="'.$pratica_indicador_objetivo_estrategico.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($pratica_indicador_objetivo_estrategico).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste �cone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso o indicador seja espec�fico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo dever� constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_estrategia" value="'.$pratica_indicador_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($pratica_indicador_estrategia).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste �cone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso o indicador seja espec�fico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo dever� constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_fator" value="'.$pratica_indicador_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($pratica_indicador_fator).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste �cone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['meta']), 'Caso o indicador seja espec�fico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo dever� constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_meta" value="'.$pratica_indicador_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($pratica_indicador_meta).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste �cone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
	
	if ($Aplic->modulo_ativo('agrupamento')) echo '<tr '.($pratica_indicador_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" style="white-space: nowrap">'.dica('Agrupamento', 'Caso o indicador seja espec�fico de um agrupamento, neste campo dever� constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_agrupamento" value="'.$pratica_indicador_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($pratica_indicador_agrupamento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('icones/agrupamento_p.png','Selecionar agrupamento','Clique neste �cone '.imagem('icones/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="pratica_indicador_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';
	
	if ($Aplic->modulo_ativo('patrocinadores')) echo '<tr '.($pratica_indicador_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['patrocinador']), 'Caso o indicador seja espec�fico de um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].', neste campo dever� constar o nome d'.$config['genero_patrocinador'].' '.$config['patrocinador'].'.').ucfirst($config['patrocinador']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_patrocinador" value="'.$pratica_indicador_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($pratica_indicador_patrocinador).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('icones/patrocinador_p.gif','Selecionar '.$config['patrocinador'],'Clique neste �cone '.imagem('icones/patrocinador_p.gif').' para selecionar um'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="pratica_indicador_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';
	
	echo '<tr '.($pratica_indicador_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" style="white-space: nowrap">'.dica('Agenda', 'Caso o indicador seja espec�fico de uma agenda, neste campo dever� constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_calendario" value="'.$pratica_indicador_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($pratica_indicador_calendario).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/agenda_p.png','Selecionar Agenda','Clique neste �cone '.imagem('icones/agenda_p.png').' para selecionar uma agenda.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['instrumento']), 'Caso o indicador seja espec�fico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo dever� constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_instrumento" value="'.$pratica_indicador_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($pratica_indicador_instrumento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste �cone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['recurso']), 'Caso o indicador seja espec�fico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo dever� constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_recurso" value="'.$pratica_indicador_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($pratica_indicador_recurso).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['recurso']),'Clique neste �cone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
	if ($Aplic->modulo_ativo('problema')) echo '<tr '.($pratica_indicador_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['problema']), 'Caso o indicador seja espec�fico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo dever� constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_problema" value="'.$pratica_indicador_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($pratica_indicador_problema).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste �cone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="pratica_indicador_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
	echo '<tr '.($pratica_indicador_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" style="white-space: nowrap">'.dica('Demanda', 'Caso o indicador seja espec�fico de uma demanda, neste campo dever� constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_demanda" value="'.$pratica_indicador_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($pratica_indicador_demanda).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste �cone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['licao']), 'Caso o indicador seja espec�fico de '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].', neste campo dever� constar o nome d'.$config['genero_licao'].' '.$config['licao'].'.').ucfirst($config['licao']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_licao" value="'.$pratica_indicador_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($pratica_indicador_licao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar '.ucfirst($config['licao']),'Clique neste �cone '.imagem('icones/licoes_p.gif').' para selecionar '.($config['genero_licao']=='a' ? 'uma ' : 'um ').$config['licao'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" style="white-space: nowrap">'.dica('Evento', 'Caso o indicador seja espec�fico de um evento, neste campo dever� constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_evento" value="'.$pratica_indicador_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($pratica_indicador_evento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_link ? '' : 'style="display:none"').' id="link" ><td align="right" style="white-space: nowrap">'.dica('link', 'Caso o indicador seja espec�fico de um link, neste campo dever� constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_link" value="'.$pratica_indicador_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($pratica_indicador_link).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste �cone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" style="white-space: nowrap">'.dica('Avalia��o', 'Caso o indicador seja espec�fico de uma avalia��o, neste campo dever� constar o nome da avalia��o.').'Avalia��o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_avaliacao" value="'.$pratica_indicador_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($pratica_indicador_avaliacao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avalia��o','Clique neste �cone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avalia��o.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" style="white-space: nowrap">'.dica('Brainstorm', 'Caso o indicador seja espec�fico de um brainstorm, neste campo dever� constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_brainstorm" value="'.$pratica_indicador_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($pratica_indicador_brainstorm).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste �cone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" style="white-space: nowrap">'.dica('Matriz GUT', 'Caso o indicador seja espec�fico de uma matriz GUT, neste campo dever� constar o nome da matriz GUT.').'Matriz GUT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_gut" value="'.$pratica_indicador_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($pratica_indicador_gut).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz GUT','Clique neste �cone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" style="white-space: nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso o indicador seja espec�fico de um diagrama de causa-efeito, neste campo dever� constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_causa_efeito" value="'.$pratica_indicador_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($pratica_indicador_causa_efeito).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste �cone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" style="white-space: nowrap">'.dica('Arquivo', 'Caso o indicador seja espec�fico de um arquivo, neste campo dever� constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_arquivo" value="'.$pratica_indicador_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($pratica_indicador_arquivo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste �cone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" style="white-space: nowrap">'.dica('F�rum', 'Caso o indicador seja espec�fico de um f�rum, neste campo dever� constar o nome do f�rum.').'F�rum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_forum" value="'.$pratica_indicador_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($pratica_indicador_forum).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar F�rum','Clique neste �cone '.imagem('icones/forum_p.gif').' para selecionar um f�rum.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" style="white-space: nowrap">'.dica('Checklist', 'Caso o indicador seja espec�fico de um checklist, neste campo dever� constar o nome do checklist.').'Checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_checklist2" value="'.$pratica_indicador_checklist.'" /><input type="text" id="checklist_nome2" name="checklist_nome2" value="'.nome_checklist($pratica_indicador_checklist).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist2();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste �cone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" style="white-space: nowrap">'.dica('Compromisso', 'Caso o indicador seja espec�fico de um compromisso, neste campo dever� constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_agenda" value="'.$pratica_indicador_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($pratica_indicador_agenda).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/compromisso_p.png','Selecionar Compromisso','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
	if (!$Aplic->profissional) {
		echo '<input type="hidden" name="pratica_indicador_monitoramento" value="" id="monitoramento" /><input type="hidden" id="monitoramento_nome" name="monitoramento_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_template" value="" id="template" /><input type="hidden" id="template_nome" name="template_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_tgn" value="" id="tgn" /><input type="hidden" id="tgn_nome" name="tgn_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_programa" value="" id="programa" /><input type="hidden" id="programa_nome" name="programa_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_canvas" value="" id="canvas" /><input type="hidden" id="canvas_nome" name="canvas_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_risco" value="" id="risco" /><input type="hidden" id="risco_nome" name="risco_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_risco_resposta" value="" id="risco_resposta" /><input type="hidden" id="risco_resposta_nome" name="risco_resposta_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_painel" value="" id="painel" /><input type="hidden" id="painel_nome" name="painel_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_painel_odometro" value="" id="painel_odometro" /><input type="hidden" id="painel_odometro_nome" name="painel_odometro_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_painel_composicao" value="" id="painel_composicao" /><input type="hidden" id="painel_composicao_nome" name="painel_composicao_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_tr" value="" id="tr" /><input type="hidden" id="tr_nome" name="tr_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';
		}
	else {
		echo '<tr '.($pratica_indicador_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" style="white-space: nowrap">'.dica('Monitoramento', 'Caso o indicador seja espec�fico de um monitoramento, neste campo dever� constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_monitoramento" value="'.$pratica_indicador_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($pratica_indicador_monitoramento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste �cone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_template ? '' : 'style="display:none"').' id="template" ><td align="right" style="white-space: nowrap">'.dica('Modelo', 'Caso o indicador seja espec�fico de um modelo, neste campo dever� constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_template" value="'.$pratica_indicador_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($pratica_indicador_template).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste �cone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tgn']), 'Caso o indicador seja espec�fico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo dever� constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tgn" value="'.$pratica_indicador_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($pratica_indicador_tgn).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste �cone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']), 'Caso o indicador seja espec�fico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo dever� constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_programa" value="'.$pratica_indicador_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($pratica_indicador_programa).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste �cone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso o indicador seja espec�fico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo dever� constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_risco" value="'.$pratica_indicador_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($pratica_indicador_risco).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste �cone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso o indicador seja espec�fico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo dever� constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_risco_resposta" value="'.$pratica_indicador_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($pratica_indicador_risco_resposta).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste �cone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso o indicador seja espec�fico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo dever� constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_canvas" value="'.$pratica_indicador_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($pratica_indicador_canvas).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" style="white-space: nowrap">'.dica('Painel de Indicador', 'Caso o indicador seja espec�fico de um painel de indicador, neste campo dever� constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel" value="'.$pratica_indicador_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($pratica_indicador_painel).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste �cone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" style="white-space: nowrap">'.dica('Od�metro de Indicador', 'Caso o indicador seja espec�fico de um od�metro de indicador, neste campo dever� constar o nome do od�metro.').'Od�metro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_odometro" value="'.$pratica_indicador_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($pratica_indicador_painel_odometro).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Od�metro','Clique neste �cone '.imagem('icones/odometro_p.png').' para selecionar um od�mtro.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" style="white-space: nowrap">'.dica('Composi��o de Pain�is', 'Caso o indicador seja espec�fico de uma composi��o de pain�is, neste campo dever� constar o nome da composi��o.').'Composi��o de Pain�is:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_composicao" value="'.$pratica_indicador_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($pratica_indicador_painel_composicao).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/composicao_p.gif','Selecionar Composi��o de Pain�is','Clique neste �cone '.imagem('icones/composicao_p.gif').' para selecionar uma composi��o de pain�is.').'</a></td></tr></table></td></tr>';
		echo '<tr '.($pratica_indicador_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tr']), 'Caso seja espec�fico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo dever� constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tr" value="'.$pratica_indicador_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($pratica_indicador_tr).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
		if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo '<tr '.($pratica_indicador_me ? '' : 'style="display:none"').' id="me" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['me']), 'Caso seja espec�fico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo dever� constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_me" value="'.$pratica_indicador_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($pratica_indicador_me).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
		else echo '<input type="hidden" name="pratica_indicador_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';
	
		}
	if ($Aplic->modulo_ativo('swot')) {
		echo '<tr '.(isset($pratica_indicador_mswot) && $pratica_indicador_mswot ? '' : 'style="display:none"').' id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT', 'Caso o indicador seja espec�fico de uma matriz SWOT neste campo dever� constar o nome da matriz SWOT').'Matriz SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_mswot" value="'.(isset($pratica_indicador_mswot) ? $pratica_indicador_mswot : '').'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot((isset($pratica_indicador_mswot) ? $pratica_indicador_mswot : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste �cone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr></table></td></tr>';
		echo '<tr '.(isset($pratica_indicador_swot) && $pratica_indicador_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" style="white-space: nowrap">'.dica('Campo SWOT', 'Caso o indicador seja espec�fico de um campo de matriz SWOT neste campo dever� constar o nome do campo de matriz SWOT').'Campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_swot" value="'.(isset($pratica_indicador_swot) ? $pratica_indicador_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($pratica_indicador_swot) ? $pratica_indicador_swot : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('icones/swot_p.png','Selecionar Campo SWOT','Clique neste �cone '.imagem('icones/swot_p.png').' para selecionar um campo de matriz SWOT.').'</a></td></tr></table></td></tr>';
		}
	else {
		echo '<input type="hidden" name="pratica_indicador_mswot" value="" id="mswot" /><input type="hidden" id="mswot_nome" name="mswot_nome" value="">';
		echo '<input type="hidden" name="pratica_indicador_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
		}
	if ($Aplic->modulo_ativo('atas')) echo '<tr '.(isset($pratica_indicador_ata) && $pratica_indicador_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" style="white-space: nowrap">'.dica('Ata de Reuni�o', 'Caso o indicador seja espec�fico de uma ata de reuni�o neste campo dever� constar o nome da ata').'Ata de Reuni�o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_ata" value="'.(isset($pratica_indicador_ata) ? $pratica_indicador_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($pratica_indicador_ata) ? $pratica_indicador_ata : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('icones/ata_p.png','Selecionar Ata de Reuni�o','Clique neste �cone '.imagem('icones/ata_p.png').' para selecionar uma ata de reuni�o.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="pratica_indicador_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
	if ($Aplic->modulo_ativo('operativo')) echo '<tr '.($pratica_indicador_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso o indicador seja espec�fico de um plano operativo, neste campo dever� constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_operativo" value="'.$pratica_indicador_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($pratica_indicador_operativo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste �cone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="pratica_indicador_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';
	echo '</table></td></tr>';
	}
elseif($tabela=='plano_acao_item'){
	$plano_acao_id=getParam($_REQUEST, 'plano_acao_id', null);
	$acao='<tr id="acao" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], ucfirst($config['genero_acao']).' '.$config['acao'].' do item a ser selecionado.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><input type="hidden" name="plano_acao_id" value="'.$plano_acao_id.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($plano_acao_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar '.ucfirst($config['acao']),'Clique neste �cone '.imagem('icones/plano_acao_p.gif').' para selecionar '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].'.').'</a></td></tr>';
	$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e demais campos.').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="textobusca" onChange="document.env.submit();" value="'.$textobusca.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	echo '<tr><td colspan=20 align=right><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procuraBuffer.$acao.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$botao_filtrar.'</table></td>';
	echo '</tr></table></td></tr>';
	}
elseif($tabela=='beneficio'){
	$programa_id=getParam($_REQUEST, 'programa_id', null);
	$programa='<tr id="programa" ><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['programa']).' Relacionad'.$config['genero_programa'], ucfirst($config['genero_programa']).' '.$config['programa'].' d'.$config['genero_beneficio'].' '.$config['beneficio'].' a ser selecionad'.$config['genero_beneficio'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><input type="hidden" name="programa_id" value="'.$programa_id.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($programa_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste �cone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr>';
	$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e demais campos.').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="textobusca" onChange="document.env.submit();" value="'.$textobusca.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	echo '<tr><td colspan=20 align=right><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procuraBuffer.$programa.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$botao_filtrar.'</table></td>';
	echo '</tr></table></td></tr>';
	}	
elseif($tabela=='swot'){
	$mswot_id=getParam($_REQUEST, 'mswot_id', null);
	$mswot='<tr id="mswot" ><td align="right" style="white-space: nowrap">'.dica('Matriz SWOT','A matriz SWOT do campo a ser selecionado.').'Matriz SWOT:'.dicaF().'</td><td><input type="hidden" name="mswot_id" value="'.$mswot_id.'" /><input type="text" id="mswot_nome" name="mswot_nome" value="'.nome_mswot($mswot_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMSWOT();">'.imagem('icones/mswot_p.png','Selecionar Matriz SWOT','Clique neste �cone '.imagem('icones/mswot_p.png').' para selecionar uma matriz SWOT.').'</a></td></tr>';
	$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e demais campos.').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="textobusca" onChange="document.env.submit();" value="'.$textobusca.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	echo '<tr><td colspan=20 align=right><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procuraBuffer.$mswot.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$botao_filtrar.'</table></td>';
	echo '</tr></table></td></tr>';
	}					
elseif($tabela=='tarefas'){
	$projeto='<tr id="projeto" align="right"><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']), ucfirst($config['genero_projeto']).' '.$config['projeto'].' d'.$config['genero_tarefa'].' '.$config['tarefa'].' a ser selecionad'.$config['genero_tarefa'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($projeto_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste �cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr>';
	$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e demais campos.').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="textobusca" onChange="document.env.submit();" value="'.$textobusca.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	echo '<tr><td colspan=20 align=right><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procuraBuffer.$projeto.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$botao_filtrar.'</table></td>';
	echo '</tr></table></td></tr>';
	}			
else{
	$procuraBuffer = '<tr><td align=right style="white-space: nowrap">'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descri��o').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="textobusca" onChange="document.env.submit();" value="'.$textobusca.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&textobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
	echo '<tr><td colspan=20 align=right><table cellspacing=0 cellpadding=0><tr>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$procurar_om.$procuraBuffer.'</table></td>';
	echo '<td><table cellspacing=0 cellpadding=0 >'.$botao_filtrar.'</table></td>';
	echo '</tr></table></td></tr>';
	$responsavel=0;	
	$supervisor=0;	
	$autoridade=0;
	$municipio_id=0;
	}



switch ($tabela) {
	case 'depts':
		$titulo = $config['departamento'];
		$nao_ha='N�o foi encontrad'.($config['genero_dept']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['departamento'];
		$nenhum='Nenhum'.($config['genero_dept']=='a' ? 'a' : '').' '.$config['departamento'];
		$esconder_cia=getParam($_REQUEST, 'esconder_cia', 0);
		$sql->esqUnir('cias', 'cias','cias.cia_id=dept_cia');
		$sql->adCampo('dept_id, dept_acesso');
		if ($esconder_cia == 1) $sql->adCampo('dept_nome');
		else $sql->adCampo('concatenar_tres(cia_nome, \': \', dept_nome) AS dept_nome');
		if ($cia_id && !$lista_cias) $sql->adOnde('dept_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('dept_cia IN ('.$lista_cias.')');
		$sql->adOnde('dept_ativo = 1');
		$sql->adOrdem('dept_ordem, dept_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarDept($linha['dept_acesso'], $linha['dept_id'])) $lista[$linha['dept_id']]=$linha['dept_nome']; 
				}
			}
		else {
			foreach($achados as $linha) {
				if (permiteAcessarDept($linha['dept_acesso'], $linha['dept_id'])) $lista[$linha['dept_id']]=$linha['dept_nome']; 
				}
			}
		break;
	
	
	case 'plano_acao_item':
	
		$titulo = 'Item de '.ucfirst($config['acao']);
		$nao_ha='N�o foi encontrado nenhum item de '.$config['acao'];
		$nenhum='Nenhum item de '.$config['acao'];
		
		$sql->adOnde('plano_acao_id='.(int)$plano_acao_id);
		
		if (trim($textobusca)) $sql->adOnde('plano_acao_item_nome LIKE \'%'.$textobusca.'%\' OR plano_acao_item_quando LIKE \'%'.$textobusca.'%\' OR plano_acao_item_oque LIKE \'%'.$textobusca.'%\' OR plano_acao_item_como LIKE \'%'.$textobusca.'%\' OR plano_acao_item_onde LIKE \'%'.$textobusca.'%\' OR plano_acao_item_quanto LIKE \'%'.$textobusca.'%\' OR plano_acao_item_porque LIKE \'%'.$textobusca.'%\' OR plano_acao_item_quem LIKE \'%'.$textobusca.'%\' OR plano_acao_item_observacao LIKE \'%'.$textobusca.'%\'');
		$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_item_acao=plano_acao_id');
		$sql->adCampo('plano_acao_item.plano_acao_item_id, plano_acao_item_nome, plano_acao_acesso, plano_acao_id');
		if ($dept_id) {
			$sql->esqUnir('plano_acao_dept','plano_acao_dept', 'plano_acao_dept_acao=plano_acao.plano_acao_id');
			$sql->adOnde('plano_acao_dept='.(int)$dept_id.' OR plano_acao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('plano_acao_cia', 'plano_acao_cia', 'plano_acao_cia_plano_acao=plano_acao.plano_acao_id');
			$sql->adOnde('plano_acao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_acao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('plano_acao_cia IN ('.$lista_cias.')');	
		$sql->adOnde('plano_acao_ativo = 1');
		$sql->adOrdem('plano_acao_item_nome');
		$sql->adGrupo('plano_acao_item_id');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarPlanoAcao($linha['plano_acao_acesso'], $linha['plano_acao_id'])) $lista[$linha['plano_acao_item_id']]=$linha['plano_acao_item_nome'];
				}
			}
		else {
			foreach($achados as $linha)	if (permiteAcessarPlanoAcao($linha['plano_acao_acesso'], $linha['plano_acao_id'])) $lista[$linha['plano_acao_item_id']]=$linha['plano_acao_item_nome'];
			}
		break;
	
	
	case 'beneficio':
		$titulo = ucfirst($config['beneficio']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].'';
		$nenhum='Nenh'.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].'';
		
		$sql->esqUnir('programa', 'programa', 'programa.programa_id=beneficio_programa');
		$sql->adOnde('programa_id='.(int)$programa_id);
		if (trim($textobusca)) $sql->adOnde('beneficio_nome LIKE \'%'.$textobusca.'%\' OR beneficio_descricao LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('beneficio.beneficio_id, beneficio_nome, programa_acesso, programa.programa_id');
		
		if ($dept_id) {
			$sql->esqUnir('beneficio_dept','beneficio_dept', 'beneficio_dept_beneficio=beneficio.beneficio_id');
			$sql->adOnde('beneficio_dept='.(int)$dept_id.' OR beneficio_dept_dept='.(int)$dept_id);
			}	
		elseif ($cia_id || $lista_cias) {
			$sql->esqUnir('beneficio_cia', 'beneficio_cia', 'beneficio_cia_beneficio=beneficio.beneficio_id');
			$sql->adOnde('beneficio_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR beneficio_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}		
		$sql->adOnde('beneficio_ativo = 1');
		$sql->adOrdem('beneficio_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPrograma($linha['programa_acesso'], $linha['programa_id'])) $lista[$linha['beneficio_id']]=converte_texto_grafico($linha['beneficio_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPrograma($linha['programa_acesso'], $linha['programa_id'])) $lista[$linha['beneficio_id']]=converte_texto_grafico($linha['beneficio_nome']); 
			}
		break;	
	
	case 'painel_slideshow':
		include_once BASE_DIR.'/modulos/praticas/painel_pro.class.php'; 
		$titulo = 'Slideshow de Composi��es de Pain�is';
		$nao_ha='N�o foi encontrado nenhum slideshow de composi��es de pain�is';
		$nenhum='Nenhum slideshow de composi��es de pain�is';
		if (trim($textobusca)) $sql->adOnde('painel_slideshow_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('painel_slideshow_id, painel_slideshow_nome, painel_slideshow_acesso');
		if ($dept_id) {
			$sql->esqUnir('painel_slideshow_dept','painel_slideshow_dept', 'painel_slideshow_dept.painel_slideshow_dept_painel_slideshow=painel_slideshow.painel_slideshow_id');
			$sql->adOnde('painel_slideshow_dept='.(int)$dept_id.' OR painel_slideshow_dept.painel_slideshow_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('painel_slideshow_cia', 'painel_slideshow_cia', 'painel_slideshow.painel_slideshow_id=painel_slideshow_cia_painel_slideshow');
			$sql->adOnde('painel_slideshow_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR painel_slideshow_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('painel_slideshow_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('painel_slideshow_cia IN ('.$lista_cias.')');
		$sql->adOnde('painel_slideshow_ativo = 1');
		$sql->adOrdem('painel_slideshow_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarPainelSlideShow($linha['painel_slideshow_acesso'], $linha['painel_slideshow_id'])) $lista[$linha['painel_slideshow_id']]=$linha['painel_slideshow_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPainelSlideShow($linha['painel_slideshow_acesso'], $linha['painel_slideshow_id'])) $lista[$linha['painel_slideshow_id']]=$linha['painel_slideshow_nome'];
			}
		break;	
		
	case 'projeto_viabilidade':
		$titulo = 'Estudo de Viabilidade';
		$nao_ha='N�o foi encontrado nenhum estudo de viabilidade';
		$nenhum='Nenhum estudo de viabilidade';
		if (trim($textobusca)) $sql->adOnde('projeto_viabilidade_nome LIKE \'%'.$textobusca.'%\' OR projeto_viabilidade_observacao LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('projeto_viabilidade_id, projeto_viabilidade_nome, projeto_viabilidade_acesso');
		if ($dept_id) {
			$sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
			$sql->adOnde('projeto_viabilidade_dept='.(int)$dept_id.' OR projeto_viabilidade_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('projeto_viabilidade_cia', 'projeto_viabilidade_cia', 'projeto_viabilidade.projeto_viabilidade_id=projeto_viabilidade_cia_projeto_viabilidade');
			$sql->adOnde('projeto_viabilidade_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_viabilidade_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_viabilidade_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('projeto_viabilidade_cia IN ('.$lista_cias.')');
		$sql->adOnde('projeto_viabilidade_ativo = 1');
		$sql->adOrdem('projeto_viabilidade_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarViabilidade($linha['projeto_viabilidade_acesso'], $linha['projeto_viabilidade_id'])) $lista[$linha['projeto_viabilidade_id']]=$linha['projeto_viabilidade_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarViabilidade($linha['projeto_viabilidade_acesso'], $linha['projeto_viabilidade_id'])) $lista[$linha['projeto_viabilidade_id']]=$linha['projeto_viabilidade_nome'];
			}
		break;	
	
	case 'projeto_abertura':
		$titulo = 'Termo de Abertura';
		$nao_ha='N�o foi encontrado nenhum termo de abertura';
		$nenhum='Nenhum termo de abertura';
		if (trim($textobusca)) $sql->adOnde('projeto_abertura_nome LIKE \'%'.$textobusca.'%\' OR projeto_abertura_observacao LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('projeto_abertura_id, projeto_abertura_nome, projeto_abertura_acesso');
		if ($dept_id) {
			$sql->esqUnir('projeto_abertura_dept','projeto_abertura_dept', 'projeto_abertura_dept_projeto_abertura=projeto_abertura.projeto_abertura_id');
			$sql->adOnde('projeto_abertura_dept='.(int)$dept_id.' OR projeto_abertura_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('projeto_abertura_cia', 'projeto_abertura_cia', 'projeto_abertura.projeto_abertura_id=projeto_abertura_cia_projeto_abertura');
			$sql->adOnde('projeto_abertura_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_abertura_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_abertura_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('projeto_abertura_cia IN ('.$lista_cias.')');
		$sql->adOnde('projeto_abertura_ativo = 1');
		$sql->adOrdem('projeto_abertura_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarTermoAbertura($linha['projeto_abertura_acesso'], $linha['projeto_abertura_id'])) $lista[$linha['projeto_abertura_id']]=$linha['projeto_abertura_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTermoAbertura($linha['projeto_abertura_acesso'], $linha['projeto_abertura_id'])) $lista[$linha['projeto_abertura_id']]=$linha['projeto_abertura_nome'];
			}
		break;	
	
	case 'plano_gestao':
		$titulo = 'Planejamento Estrat�gico';
		$nao_ha='N�o foi encontrado nenhum planejamento estrat�gico';
		$nenhum='Nenhum planejamento estrat�gico';
		if (trim($textobusca)) $sql->adOnde('pg_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('plano_gestao.pg_id, pg_nome, pg_acesso');
		if ($dept_id) {
			$sql->esqUnir('plano_gestao_dept', 'plano_gestao_dept', 'plano_gestao_dept_plano=plano_gestao.pg_id');
			$sql->adOnde('pg_dept='.(int)$dept_id.' OR plano_gestao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('plano_gestao_cia', 'plano_gestao_cia', 'plano_gestao_cia_plano=plano_gestao.pg_id');
			$sql->adOnde('pg_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_gestao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_cia IN ('.$lista_cias.')');	
		
		$sql->adOnde('pg_ativo = 1');
		$sql->adOrdem('pg_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarPlanoGestao($linha['pg_acesso'], $linha['pg_id'])) $lista[$linha['pg_id']]=$linha['pg_nome'];
				} 
			}
		else {
			foreach($achados as $linha) {
				if (permiteAcessarPlanoGestao($linha['pg_acesso'], $linha['pg_id'])) $lista[$linha['pg_id']]=$linha['pg_nome']; 
				}
			}
		break;
	
	case 'checklist':
		$titulo = 'Checklist';
		$nao_ha='N�o foi encontrado nenhum checklist';
		$nenhum='Nenhum checklist';
		if (trim($textobusca)) $sql->adOnde('checklist_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('checklist.checklist_id, checklist_nome, checklist_acesso');
		if ($dept_id) {
			$sql->esqUnir('checklist_depts','checklist_depts', 'checklist_depts.checklist_id=checklist.checklist_id');
			$sql->adOnde('checklist_dept='.(int)$dept_id.' OR checklist_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('checklist_cia', 'checklist_cia', 'checklist.checklist_id=checklist_cia_checklist');
			$sql->adOnde('checklist_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR checklist_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('checklist_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('checklist_cia IN ('.$lista_cias.')');
		$sql->adOnde('checklist_ativo = 1');		
		$sql->adOrdem('checklist_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarChecklist($linha['checklist_acesso'], $linha['checklist_id'])) $lista[$linha['checklist_id']]=$linha['checklist_nome'];
				}
			}
		else {
			foreach($achados as $linha)	if (permiteAcessarChecklist($linha['checklist_acesso'], $linha['checklist_id'])) $lista[$linha['checklist_id']]=$linha['checklist_nome'];
			}
		break;
	
	case 'painel_composicao':
		include_once BASE_DIR.'/modulos/praticas/painel_pro.class.php'; 
		$titulo = 'Composi��o de Pain�is';
		$nao_ha='N�o foi encontrada nenhuma composi��o de pain�is';
		$nenhum='Nenhuma composi��o de pain�is';
		if (trim($textobusca)) $sql->adOnde('painel_composicao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('painel_composicao_id, painel_composicao_nome, painel_composicao_acesso');
		if ($dept_id) {
			$sql->esqUnir('painel_composicao_dept','painel_composicao_dept', 'painel_composicao_dept.painel_composicao_dept_painel_composicao=painel_composicao.painel_composicao_id');
			$sql->adOnde('painel_composicao_dept='.(int)$dept_id.' OR painel_composicao_dept.painel_composicao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('painel_composicao_cia', 'painel_composicao_cia', 'painel_composicao.painel_composicao_id=painel_composicao_cia_painel_composicao');
			$sql->adOnde('painel_composicao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR painel_composicao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('painel_composicao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('painel_composicao_cia IN ('.$lista_cias.')');
		$sql->adOnde('painel_composicao_ativo = 1');
		$sql->adOrdem('painel_composicao_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarPainelSlideShow($linha['painel_composicao_acesso'], $linha['painel_composicao_id'])) $lista[$linha['painel_composicao_id']]=$linha['painel_composicao_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPainelSlideShow($linha['painel_composicao_acesso'], $linha['painel_composicao_id'])) $lista[$linha['painel_composicao_id']]=$linha['painel_composicao_nome'];
			}
		break;
		
	case 'painel':
		include_once BASE_DIR.'/modulos/praticas/painel_pro.class.php'; 
		$titulo = 'Painel';
		$nao_ha='N�o foi encontrado nenhum painel';
		$nenhum='Nenhum painel';
		if (trim($textobusca)) $sql->adOnde('painel_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('painel_id, painel_nome, painel_acesso');
		if ($dept_id) {
			$sql->esqUnir('painel_dept','painel_dept', 'painel_dept.painel_dept_painel=painel.painel_id');
			$sql->adOnde('painel_dept='.(int)$dept_id.' OR painel_dept.painel_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('painel_cia', 'painel_cia', 'painel.painel_id=painel_cia_painel');
			$sql->adOnde('painel_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR painel_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('painel_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('painel_cia IN ('.$lista_cias.')');
		$sql->adOnde('painel_ativo = 1');
		$sql->adOrdem('painel_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPainel($linha['painel_acesso'], $linha['painel_id'])) $lista[$linha['painel_id']]=$linha['painel_nome'];
			}
		else {
			foreach($achados as $linha)	if (permiteAcessarPainel($linha['painel_acesso'], $linha['painel_id'])) $lista[$linha['painel_id']]=$linha['painel_nome'];
			}
		break;	
		
	case 'painel_odometro':
		include_once BASE_DIR.'/modulos/praticas/painel_pro.class.php'; 
		$titulo = 'Od�metro';
		$nao_ha='N�o foi encontrado nenhum od�metro';
		$nenhum='Nenhum od�metro';
		if (trim($textobusca)) $sql->adOnde('painel_odometro_nome LIKE \'%'.$textobusca.'%\'');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_id=painel_odometro_indicador');
		$sql->adCampo('painel_odometro_id, painel_odometro_nome, painel_odometro_acesso, pratica_indicador_nome');
		if ($dept_id) {
			$sql->esqUnir('painel_odometro_dept','painel_odometro_dept', 'painel_odometro_dept.painel_odometro_dept_painel_odometro=painel_odometro.painel_odometro_id');
			$sql->adOnde('painel_odometro_dept='.(int)$dept_id.' OR painel_odometro_dept.painel_odometro_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('painel_odometro_cia', 'painel_odometro_cia', 'painel_odometro.painel_odometro_id=painel_odometro_cia_painel_odometro');
			$sql->adOnde('painel_odometro_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR painel_odometro_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('painel_odometro_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('painel_odometro_cia IN ('.$lista_cias.')');
		$sql->adOnde('painel_odometro_ativo = 1');
		$sql->adOrdem('painel_odometro_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha)	if (permiteEditarOdometro($linha['painel_odometro_acesso'], $linha['painel_odometro_id'])) $lista[$linha['painel_odometro_id']]=($linha['painel_odometro_nome']? $linha['painel_odometro_nome'] : $linha['pratica_indicador_nome']);
			}
		else {
			foreach($achados as $linha) if (permiteAcessarOdometro($linha['painel_odometro_acesso'], $linha['painel_odometro_id'])) $lista[$linha['painel_odometro_id']]=($linha['painel_odometro_nome']? $linha['painel_odometro_nome'] : $linha['pratica_indicador_nome']);
			}
		break;	
		
	case 'tr':
		$titulo = ucfirst($config['tr']);
		$nao_ha='N�o foi encontrad'.($config['genero_tr']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['tr'];
		$nenhum='Nenhum'.($config['genero_tr']=='a' ? 'a' : '').' '.$config['tr'];
		if (trim($textobusca)) $sql->adOnde('tr_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('tr_id, tr_nome, tr_acesso');
		if ($dept_id) {
			$sql->esqUnir('tr_dept','tr_dept', 'tr_dept.tr_dept_tr=tr.tr_id');
			$sql->adOnde('tr_dept='.(int)$dept_id.' OR tr_dept.tr_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('tr_cia', 'tr_cia', 'tr.tr_id=tr_cia_tr');
			$sql->adOnde('tr_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tr_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('tr_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('tr_cia IN ('.$lista_cias.')');
		$sql->adOnde('tr_ativo = 1');
		$sql->adOrdem('tr_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarTR($linha['tr_acesso'], $linha['tr_id'])) $lista[$linha['tr_id']]=$linha['tr_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTR($linha['tr_acesso'], $linha['tr_id'])) $lista[$linha['tr_id']]=$linha['tr_nome'];
			}
		break;	
		
	case 'agrupamento':
		include_once BASE_DIR.'/modulos/agrupamento/funcoes.php'; 
		$titulo = 'Agrupamento';
		$nao_ha='N�o foi encontrado nenhum agrupamento';
		$nenhum='Nenhum agrupamento';
		if (trim($textobusca)) $sql->adOnde('agrupamento_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('agrupamento.agrupamento_id, agrupamento_nome, agrupamento_acesso');
		if ($dept_id) {
			$sql->esqUnir('agrupamento_dept','agrupamento_dept', 'agrupamento_dept.agrupamento_dept_agrupamento=agrupamento.agrupamento_id');
			$sql->adOnde('agrupamento_dept='.(int)$dept_id.' OR agrupamento_dept.agrupamento_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('agrupamento_cia', 'agrupamento_cia', 'agrupamento.agrupamento_id=agrupamento_cia_agrupamento');
			$sql->adOnde('agrupamento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR agrupamento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('agrupamento_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('agrupamento_cia IN ('.$lista_cias.')');
		$sql->adOnde('agrupamento_ativo = 1');
		$sql->adOrdem('agrupamento_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarAgrupamento($linha['agrupamento_acesso'], $linha['agrupamento_id'])) $lista[$linha['agrupamento_id']]=$linha['agrupamento_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarAgrupamento($linha['agrupamento_acesso'], $linha['agrupamento_id'])) $lista[$linha['agrupamento_id']]=$linha['agrupamento_nome'];
			}
		break;	
		
		
		
	case 'mswot':
		$titulo = 'Matriz SWOT';
		$nao_ha='N�o foi encontrada nenhuma matriz SWOT';
		$nenhum='Nenhuma matriz SWOT';
		$sql->adCampo('mswot_id, mswot_nome, mswot_acesso');
		if ($cia_id && !$lista_cias) $sql->adOnde('mswot_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('mswot_cia IN ('.$lista_cias.')');
		$sql->adOnde('mswot_ativo=1');
		$sql->adOrdem('mswot_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarMSWOT($linha['mswot_acesso'], $linha['mswot_id'])) $lista[$linha['mswot_id']]=converte_texto_grafico($linha['mswot_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarMSWOT($linha['mswot_acesso'], $linha['mswot_id'])) $lista[$linha['mswot_id']]=converte_texto_grafico($linha['mswot_nome']); 
			}
		break;	

	case 'swot':
		$swot_tipo=getParam($_REQUEST, 'swot_tipo', null);
		if ($swot_tipo=='s') $tipo='for�a';
		elseif ($swot_tipo=='w') $tipo='fraqueza';
		elseif ($swot_tipo=='o') $tipo='oportunidade';
		elseif ($swot_tipo=='t') $tipo='amea�a';
		else $tipo='campo de matriz SWOT';
		$titulo = ucfirst($tipo);
		$nao_ha='N�o foi encontrado '.$tipo;
		
		$sql->adCampo('swot_id, swot_nome, swot_tipo, swot_acesso');
		
		if (trim($textobusca)) $sql->adOnde('swot_nome LIKE \'%'.$textobusca.'%\' OR swot_descricao LIKE \'%'.$textobusca.'%\'');
		if ($swot_tipo) $sql->adOnde('swot_tipo=\''.$swot_tipo.'\''); 
		if ($mswot_id) {
			$sql->esqUnir('mswot_swot', 'mswot_swot', 'swot.swot_id=mswot_swot_swot');
			$sql->adOnde('mswot_swot_mswot='.(int)$mswot_id);
			}
		if ($dept_id) {
			$sql->esqUnir('swot_dept','swot_dept', 'swot_dept_swot=swot.swot_id');
			$sql->adOnde('swot_dept='.(int)$dept_id.' OR swot_dept_dept='.(int)$dept_id);
			}	
		elseif ($cia_id || $lista_cias) {
			$sql->esqUnir('swot_cia', 'swot_cia', 'swot_cia_swot=swot.swot_id');
			$sql->adOnde('swot_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR swot_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}		
		$sql->adOnde('swot_ativo=1');
		$sql->adOrdem('swot_tipo, swot_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarSWOT($linha['swot_acesso'], $linha['swot_id'])) $lista[$linha['swot_id']]=converte_texto_grafico(($swot_tipo ? '' : strtoupper($linha['swot_tipo']).' - ').$linha['swot_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarSWOT($linha['swot_acesso'], $linha['swot_id'])) $lista[$linha['swot_id']]=converte_texto_grafico(($swot_tipo ? '' : strtoupper($linha['swot_tipo']).' - ').$linha['swot_nome']); 
			}
		break;	
		
	
	case 'template':
		include_once BASE_DIR.'/modulos/projetos/template_pro.class.php'; 
		$titulo = 'Modelo';
		$nao_ha='N�o foi encontrado nenhum modelo';
		$nenhum='Nenhum modelo';
		if (trim($textobusca)) $sql->adOnde('template_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('template.template_id, template_nome, template_acesso');
		if ($dept_id) {
			$sql->esqUnir('template_depts','template_depts', 'template_depts.template_id=template.template_id');
			$sql->adOnde('template_dept='.(int)$dept_id.' OR template_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('template_cia', 'template_cia', 'template.template_id=template_cia_template');
			$sql->adOnde('template_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR template_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('template_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('template_cia IN ('.$lista_cias.')');
		$sql->adOnde('template_ativo = 1');
		$sql->adOrdem('template_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarTemplate($linha['template_acesso'], $linha['template_id'])) $lista[$linha['template_id']]=$linha['template_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTemplate($linha['template_acesso'], $linha['template_id'])) $lista[$linha['template_id']]=$linha['template_nome'];
			}
		break;
		
	case 'arquivo':
		$titulo = 'Arquivo';
		$nao_ha='N�o foi encontrado nenhum arquivo';
		$nenhum='Nenhum arquivo';
		if (trim($textobusca)) $sql->adOnde('arquivo_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('arquivo.arquivo_id, arquivo_nome, arquivo_acesso');
		if ($dept_id) {
			$sql->esqUnir('arquivo_dept','arquivo_dept', 'arquivo_dept_arquivo=arquivo.arquivo_id');
			$sql->adOnde('arquivo_dept='.(int)$dept_id.' OR arquivo_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('arquivo_cia', 'arquivo_cia', 'arquivo.arquivo_id=arquivo_cia_arquivo');
			$sql->adOnde('arquivo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR arquivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('arquivo_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('arquivo_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('arquivo_ativo = 1');
		$sql->adOrdem('arquivo_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarArquivo($linha['arquivo_acesso'], $linha['arquivo_id'])) $lista[$linha['arquivo_id']]=$linha['arquivo_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarArquivo($linha['arquivo_acesso'], $linha['arquivo_id'])) $lista[$linha['arquivo_id']]=$linha['arquivo_nome']; 
			}
		break;
	
	
	
	case 'patrocinadores':
		$titulo = ucfirst($config['patrocinador']);
		$nao_ha='N�o foi encontrad'.$config['genero_patrocinador'].' nenhum'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'];
		$nenhum='Nenhum'.($config['genero_patrocinador']=='o' ? '' : 'a').' '.$config['patrocinador'];
		if (trim($textobusca)) $sql->adOnde('patrocinador_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('patrocinadores.patrocinador_id, patrocinador_nome, patrocinador_acesso');
		$sql->adOrdem('patrocinador_nome');
		
		if ($dept_id) {
			$sql->esqUnir('patrocinadores_depts', 'patrocinadores_depts', 'patrocinadores_depts.patrocinador_id=patrocinadores.patrocinador_id');
			$sql->adOnde('patrocinador_dept='.(int)$dept_id.' OR dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('patrocinador_cia', 'patrocinador_cia', 'patrocinador_cia_patrocinador=patrocinadores.patrocinador_id');
			$sql->adOnde('patrocinador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR patrocinador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('patrocinador_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('patrocinador_cia IN ('.$lista_cias.')');	
	
		$sql->adOrdem('patrocinador_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarPatrocinador($linha['patrocinador_acesso'], $linha['patrocinador_id'])) $lista[$linha['patrocinador_id']]=$linha['patrocinador_nome'];
				} 
			}
		else {
			foreach($achados as $linha) {
				if (permiteAcessarPatrocinador($linha['patrocinador_acesso'], $linha['patrocinador_id'])) $lista[$linha['patrocinador_id']]=$linha['patrocinador_nome']; 
				}
			}
		break;
	
	
	case 'eventos':
		$titulo = 'Evento';
		$nao_ha='N�o foi encontrado nenhum evento';
		$nenhum='Nenhum evento';
		if (trim($textobusca)) $sql->adOnde('evento_titulo LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('eventos.evento_id, concatenar_tres(evento_titulo, \': \', formatar_data(evento_inicio, \'%d/%m/%Y\')) AS evento_nome, evento_acesso, evento_projeto, evento_tarefa, evento_pratica, evento_acao, evento_indicador, evento_calendario');
		if ($dept_id) {
			$sql->esqUnir('evento_dept','evento_dept', 'evento_dept_evento=eventos.evento_id');
			$sql->adOnde('evento_dept='.(int)$dept_id.' OR evento_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('evento_cia', 'evento_cia', 'eventos.evento_id=evento_cia_evento');
			$sql->adOnde('evento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR evento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('evento_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('evento_cia IN ('.$lista_cias.')');
		$sql->adOnde('evento_ativo = 1');
		$sql->adOrdem('evento_inicio');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarEvento($linha['evento_acesso'], $linha['evento_indicador'])) $lista[$linha['evento_id']]=$linha['evento_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarEvento($linha['evento_acesso'], $linha['evento_projeto'], $linha['evento_tarefa'])) $lista[$linha['evento_id']]=$linha['evento_nome'];
			}
		break;	
	
	case 'foruns':
		$titulo = 'F�rum';
		$nao_ha='N�o foi encontrado nenhum f�rum';
		$nenhum='Nenhum f�rum';
		if (trim($textobusca)) $sql->adOnde('forum_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('foruns.forum_id, forum_nome, forum_acesso');

		if ($dept_id) {
			$sql->esqUnir('forum_dept','forum_dept', 'forum_dept_forum=foruns.forum_id');
			$sql->adOnde('forum_dept='.(int)$dept_id.' OR forum_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('forum_cia', 'forum_cia', 'foruns.forum_id=forum_cia_forum');
			$sql->adOnde('forum_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR forum_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('forum_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('forum_cia IN ('.$lista_cias.')');
		$sql->adOnde('forum_ativo = 1');
		$sql->adOrdem('forum_nome');
		
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarForum($linha['forum_acesso'],  $linha['forum_id'])) $lista[$linha['forum_id']]=$linha['forum_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarForum($linha['forum_acesso'],  $linha['forum_id'])) $lista[$linha['forum_id']]=$linha['forum_nome'];
			}
		break;
		
	case 'agenda_tipo':
		$titulo = 'Agenda';
		$nao_ha='N�o foi encontrado nenhuma agenda';
		$nenhum='Nenhuma agenda';
		if (trim($textobusca)) $sql->adOnde('nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('agenda_tipo_id, nome');
		$sql->adOnde('usuario_id='.(int)$Aplic->usuario_id);
		$sql->adOrdem('nome');
		$lista = $sql->ListaChave();
		$sql->limpar();
		break;	
	
	case 'calendario':
		$titulo = 'Agenda';
		$nao_ha='N�o foi encontrado nenhuma agenda';
		$nenhum='Nenhuma agenda';	
		if (trim($textobusca)) $sql->adOnde('calendario_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('calendario.calendario_id, calendario_nome, calendario_acesso');
		

		if ($dept_id) {
			$sql->esqUnir('calendario_dept','calendario_dept', 'calendario_dept_calendario=calendario.calendario_id');
			$sql->adOnde('calendario_dept='.(int)$dept_id.' OR calendario_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('calendario_cia', 'calendario_cia', 'calendario.calendario_id=calendario_cia_calendario');
			$sql->adOnde('calendario_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR calendario_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('calendario_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('calendario_cia IN ('.$lista_cias.')');
		$sql->adOnde('calendario_ativo = 1');
		$sql->adOrdem('calendario_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarCalendario($linha['calendario_acesso'], $linha['calendario_id'])) $lista[$linha['calendario_id']]=$linha['calendario_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarCalendario($linha['calendario_acesso'], $linha['calendario_id'])) $lista[$linha['calendario_id']]=$linha['calendario_nome'];
			}
		
		break;
	
	case 'ata':
		$titulo = 'Ata de Reuni�o';
		$nao_ha='N�o foi encontrado nenhuma ata de reuni�o';
		$nenhum='Nenhuma ata';
		if (trim($textobusca)) $sql->adOnde('ata_titulo LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('ata.ata_id, ata_titulo, ata_numero, ata_acesso');
		
		
		if ($dept_id) {
			$sql->esqUnir('ata_dept','ata_dept', 'ata_dept_ata=ata.ata_id');
			$sql->adOnde('ata_dept='.(int)$dept_id.' OR ata_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('ata_cia', 'ata_cia', 'ata.ata_id=ata_cia_ata');
			$sql->adOnde('ata_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR ata_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('ata_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('ata_cia IN ('.$lista_cias.')');
		$sql->adOnde('ata_ativo = 1');
		$sql->adOrdem('ata_titulo');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarAta($linha['ata_acesso'], $linha['ata_id'])) $lista[$linha['ata_id']]=($linha['ata_numero'] < 10 ? '00' : ($linha['ata_numero'] < 100 ? '0' : '')).$linha['ata_numero'].($linha['ata_titulo'] ? ' - '.$linha['ata_titulo'] : ''); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarAta($linha['ata_acesso'], $linha['ata_id'])) $lista[$linha['ata_id']]=($linha['ata_numero'] < 10 ? '00' : ($linha['ata_numero'] < 100 ? '0' : '')).$linha['ata_numero'].($linha['ata_titulo'] ? ' - '.$linha['ata_titulo'] : '');
			}
		break;
		
	case 'monitoramento':
		$titulo = 'Reuni�o de Monitoramento';
		$nao_ha='N�o foi encontrado nenhuma reuni�o de monitoramento';
		$nenhum='Nenhuma reuni�o de monitoramento';
		if (trim($textobusca)) $sql->adOnde('monitoramento_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('monitoramento.monitoramento_id, monitoramento_nome, monitoramento_acesso');
	
		
		if ($dept_id) {
			$sql->esqUnir('monitoramento_depts','monitoramento_depts', 'monitoramento_depts.monitoramento_id=monitoramento.monitoramento_id');
			$sql->adOnde('monitoramento_dept='.(int)$dept_id.' OR monitoramento_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('monitoramento_cia', 'monitoramento_cia', 'monitoramento.monitoramento_id=monitoramento_cia_monitoramento');
			$sql->adOnde('monitoramento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR monitoramento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('monitoramento_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('monitoramento_cia IN ('.$lista_cias.')');
		$sql->adOnde('monitoramento_ativo = 1');
		$sql->adOrdem('monitoramento_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarMonitoramento($linha['monitoramento_acesso'], $linha['monitoramento_id'])) $lista[$linha['monitoramento_id']]=$linha['monitoramento_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarMonitoramento($linha['monitoramento_acesso'], $linha['monitoramento_id'])) $lista[$linha['monitoramento_id']]=$linha['monitoramento_nome']; 
			}
		break;	
	
	case 'operativo':
		include_once BASE_DIR.'/modulos/operativo/funcoes.php'; 
		$titulo = 'Plano Operativo';
		$nao_ha='N�o foi encontrado nenhum plano de operativo';
		$nenhum='Nenhum  plano operativo';
		if (trim($textobusca)) $sql->adOnde('operativo_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('operativo.operativo_id, operativo_nome, operativo_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('operativo_depts','operativo_depts', 'operativo_depts.operativo_id=operativo.operativo_id');
			$sql->adOnde('operativo_dept='.(int)$dept_id.' OR operativo_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('operativo_cia', 'operativo_cia', 'operativo.operativo_id=operativo_cia_operativo');
			$sql->adOnde('operativo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR operativo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('operativo_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('operativo_cia IN ('.$lista_cias.')');
		$sql->adOnde('operativo_ativo = 1');
		$sql->adOrdem('operativo_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarOperativo($linha['operativo_acesso'], $linha['operativo_id'])) $lista[$linha['operativo_id']]=$linha['operativo_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarOperativo($linha['operativo_acesso'], $linha['operativo_id'])) $lista[$linha['operativo_id']]=$linha['operativo_nome']; 
			}
		break;	
	
	case 'recursos':
		$titulo = 'Recurso';
		$nao_ha='N�o foi encontrado nenhum recurso';
		$nenhum='Nenhum recurso';
		if (trim($textobusca)) $sql->adOnde('recurso_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('recursos.recurso_id, recurso_nome, recurso_nivel_acesso');
		if ($dept_id) {
			$sql->esqUnir('recurso_depts','recurso_depts', 'recurso_depts.recurso_id=recursos.recurso_id');
			$sql->adOnde('recurso_dept='.(int)$dept_id.' OR recurso_depts.departamento_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('recurso_cia', 'recurso_cia', 'recursos.recurso_id=recurso_cia_recurso');
			$sql->adOnde('recurso_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR recurso_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('recurso_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('recurso_cia IN ('.$lista_cias.')');
		$sql->adOnde('recurso_ativo = 1');
		$sql->adOrdem('recurso_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $lista[$linha['recurso_id']]=$linha['recurso_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $lista[$linha['recurso_id']]=$linha['recurso_nome']; 
			}
		break;
	
		case 'jornada':
		$titulo = 'Calend�rio';
		$nao_ha='N�o foi encontrado nenhum calend�rio';
		$nenhum='Nenhum calend�rio';
		if (trim($textobusca)) $sql->adOnde('jornada_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('jornada_id, jornada_nome');
		$sql->adOrdem('jornada_nome');
		$achados=$sql->Lista();
		$lista=array();
		foreach($achados as $linha) $lista[$linha['jornada_id']]=$linha['jornada_nome'];
		break;
	
	
	case 'praticas':
		$titulo = ucfirst($config['pratica']);
		$nao_ha='N�o foi encontrad'.($config['genero_pratica']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['pratica'];
		$nenhum='Nenhum'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'];
		if (trim($textobusca)) $sql->adOnde('pratica_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('praticas.pratica_id, pratica_nome, pratica_acesso');
		if ($dept_id) {
			$sql->esqUnir('pratica_depts','pratica_depts', 'pratica_depts.pratica_id=praticas.pratica_id');
			$sql->adOnde('pratica_dept='.(int)$dept_id.' OR pratica_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('pratica_cia', 'pratica_cia', 'praticas.pratica_id=pratica_cia_pratica');
			$sql->adOnde('pratica_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR pratica_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pratica_cia IN ('.$lista_cias.')');
		$sql->adOnde('pratica_ativa = 1');
		$sql->adOrdem('pratica_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPratica($linha['pratica_acesso'], $linha['pratica_id'])) $lista[$linha['pratica_id']]=$linha['pratica_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPratica($linha['pratica_acesso'], $linha['pratica_id'])) $lista[$linha['pratica_id']]=$linha['pratica_nome']; 
			}
		break;
	
	case 'problema':
		$titulo = ucfirst($config['problema']);
		$nao_ha='N�o foi encontrad'.($config['genero_problema']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['problema'];
		$nenhum='Nenhum'.($config['genero_problema']=='a' ? 'a' : '').' '.$config['problema'];
		if (trim($textobusca)) $sql->adOnde('problema_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('problema.problema_id, problema_nome, problema_acesso');
		if ($dept_id) {
			$sql->esqUnir('problema_depts','problema_depts', 'problema_depts.problema_id=problema.problema_id');
			$sql->adOnde('problema_dept='.(int)$dept_id.' OR problema_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('problema_cia', 'problema_cia', 'problema.problema_id=problema_cia_problema');
			$sql->adOnde('problema_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR problema_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('problema_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('problema_cia IN ('.$lista_cias.')');
		$sql->adOnde('problema_ativo = 1');
		$sql->adOrdem('problema_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarProblema($linha['problema_acesso'], $linha['problema_id'])) $lista[$linha['problema_id']]=$linha['problema_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarProblema($linha['problema_acesso'], $linha['problema_id'])) $lista[$linha['problema_id']]=$linha['problema_nome']; 
			}
		break;
	
	case 'instrumento':
		$titulo = ucfirst($config['instrumento']);
		$nao_ha='N�o foi encontrad'.($config['genero_instrumento']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['instrumento'];
		$nenhum='Nenhum'.($config['genero_instrumento']=='a' ? 'a' : '').' '.$config['instrumento'];
		if (trim($textobusca)) $sql->adOnde('instrumento_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('instrumento.instrumento_id, instrumento_nome, instrumento_acesso');
		if ($dept_id) {
			$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
			$sql->adOnde('instrumento_dept='.(int)$dept_id.' OR instrumento_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('instrumento_cia', 'instrumento_cia', 'instrumento.instrumento_id=instrumento_cia_instrumento');
			$sql->adOnde('instrumento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR instrumento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('instrumento_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('instrumento_cia IN ('.$lista_cias.')');
		$sql->adOnde('instrumento_ativo = 1');
		$sql->adOrdem('instrumento_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarInstrumento($linha['instrumento_acesso'], $linha['instrumento_id'])) $lista[$linha['instrumento_id']]=$linha['instrumento_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarInstrumento($linha['instrumento_acesso'], $linha['instrumento_id'])) $lista[$linha['instrumento_id']]=$linha['instrumento_nome']; 
			}
		break;
		
	case 'objetivo':
		if (isset($_REQUEST['pg_ano'])) $Aplic->setEstado('pg_ano', getParam($_REQUEST, 'pg_ano', null));
		$titulo = ucfirst($config['objetivo']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'';
		$nenhum='Nenh'.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'';
		if (trim($textobusca)) $sql->adOnde('objetivo_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('objetivo.objetivo_id, objetivo_nome, objetivo_acesso');
		if ($dept_id) {
			$sql->esqUnir('objetivo_dept','objetivo_dept', 'objetivo_dept_objetivo=objetivo.objetivo_id');
			$sql->adOnde('objetivo_dept='.(int)$dept_id.' OR objetivo_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('objetivo_cia', 'objetivo_cia', 'objetivo.objetivo_id=objetivo_cia_objetivo');
			$sql->adOnde('objetivo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR objetivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('objetivo_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('objetivo_cia IN ('.$lista_cias.')');
		$sql->adOnde('objetivo_ativo = 1');
		$sql->adOrdem('objetivo_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarObjetivo($linha['objetivo_acesso'], $linha['objetivo_id'])) $lista[$linha['objetivo_id']]=converte_texto_grafico($linha['objetivo_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarObjetivo($linha['objetivo_acesso'], $linha['objetivo_id'])) $lista[$linha['objetivo_id']]=converte_texto_grafico($linha['objetivo_nome']); 
			}
		break;	
	
	case 'beneficio':
		$titulo = ucfirst($config['beneficio']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].'';
		$nenhum='Nenh'.($config['genero_beneficio']=='o' ? 'um' : 'uma').' '.$config['beneficio'].'';
		if (trim($textobusca)) $sql->adOnde('beneficio_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('beneficio.beneficio_id, beneficio_nome, beneficio_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('beneficio_dept','beneficio_dept', 'beneficio_dept_beneficio=beneficio.beneficio_id');
			$sql->adOnde('beneficio_dept='.(int)$dept_id.' OR beneficio_dept_dept='.(int)$dept_id);
			}	
		elseif ($cia_id || $lista_cias) {
			$sql->esqUnir('beneficio_cia', 'beneficio_cia', 'beneficio_cia_beneficio=beneficio.beneficio_id');
			$sql->adOnde('beneficio_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR beneficio_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}		
		$sql->adOnde('beneficio_ativo = 1');
		$sql->adOrdem('beneficio_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarBeneficio($linha['beneficio_acesso'], $linha['beneficio_id'])) $lista[$linha['beneficio_id']]=converte_texto_grafico($linha['beneficio_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarBeneficio($linha['beneficio_acesso'], $linha['beneficio_id'])) $lista[$linha['beneficio_id']]=converte_texto_grafico($linha['beneficio_nome']); 
			}
		break;	
	
	case 'programa':
		$titulo = ucfirst($config['programa']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'';
		$nenhum='Nenh'.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'';
		if (trim($textobusca)) $sql->adOnde('programa_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('programa.programa_id, programa_nome, programa_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('programa_dept','programa_dept', 'programa_dept_programa=programa.programa_id');
			$sql->adOnde('programa_dept='.(int)$dept_id.' OR programa_dept_dept='.(int)$dept_id);
			}	
		elseif ($cia_id || $lista_cias) {
			$sql->esqUnir('programa_cia', 'programa_cia', 'programa_cia_programa=programa.programa_id');
			$sql->adOnde('programa_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR programa_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}		
		$sql->adOnde('programa_ativo = 1');
		$sql->adOrdem('programa_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPrograma($linha['programa_acesso'], $linha['programa_id'])) $lista[$linha['programa_id']]=converte_texto_grafico($linha['programa_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPrograma($linha['programa_acesso'], $linha['programa_id'])) $lista[$linha['programa_id']]=converte_texto_grafico($linha['programa_nome']); 
			}
		break;	
	
	case 'canvas':
		$titulo = ucfirst($config['canvas']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'';
		$nenhum='Nenh'.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'];
		if (trim($textobusca)) $sql->adOnde('canvas_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('canvas.canvas_id, canvas_nome, canvas_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('canvas_dept','canvas_dept', 'canvas_dept_canvas=canvas.canvas_id');
			$sql->adOnde('canvas_dept='.(int)$dept_id.' OR canvas_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('canvas_cia', 'canvas_cia', 'canvas.canvas_id=canvas_cia_canvas');
			$sql->adOnde('canvas_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR canvas_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('canvas_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('canvas_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('canvas_ativo = 1');
		$sql->adOrdem('canvas_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarCanvas($linha['canvas_acesso'], $linha['canvas_id'])) $lista[$linha['canvas_id']]=converte_texto_grafico($linha['canvas_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarCanvas($linha['canvas_acesso'], $linha['canvas_id'])) $lista[$linha['canvas_id']]=converte_texto_grafico($linha['canvas_nome']); 
			}
		break;		
	
	case 'risco':
		$titulo = ucfirst($config['risco']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'';
		$nenhum='Nenh'.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'';
		if (trim($textobusca)) $sql->adOnde('risco_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('risco.risco_id, risco_nome, risco_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('risco_depts','risco_depts', 'risco_depts.risco_id=risco.risco_id');
			$sql->adOnde('risco_dept='.(int)$dept_id.' OR risco_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('risco_cia', 'risco_cia', 'risco.risco_id=risco_cia_risco');
			$sql->adOnde('risco_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR risco_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('risco_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('risco_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('risco_ativo = 1');
		$sql->adOrdem('risco_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRisco($linha['risco_acesso'], $linha['risco_id'])) $lista[$linha['risco_id']]=converte_texto_grafico($linha['risco_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRisco($linha['risco_acesso'], $linha['risco_id'])) $lista[$linha['risco_id']]=converte_texto_grafico($linha['risco_nome']); 
			}
		break;	

	case 'risco_resposta':
		$titulo = ucfirst($config['risco_resposta']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'';
		$nenhum='Nenh'.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'';
		if (trim($textobusca)) $sql->adOnde('risco_resposta_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('risco_resposta.risco_resposta_id, risco_resposta_nome, risco_resposta_acesso');

		if ($dept_id) {
			$sql->esqUnir('risco_resposta_depts','risco_resposta_depts', 'risco_resposta_depts.risco_resposta_id=risco_resposta.risco_resposta_id');
			$sql->adOnde('risco_resposta_dept='.(int)$dept_id.' OR risco_resposta_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('risco_resposta_cia', 'risco_resposta_cia', 'risco_resposta.risco_resposta_id=risco_resposta_cia_risco_resposta');
			$sql->adOnde('risco_resposta_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR risco_resposta_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('risco_resposta_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('risco_resposta_cia IN ('.$lista_cias.')');
		$sql->adOnde('risco_resposta_ativo = 1');
		$sql->adOrdem('risco_resposta_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarRiscoResposta($linha['risco_resposta_acesso'], $linha['risco_resposta_id'])) $lista[$linha['risco_resposta_id']]=converte_texto_grafico($linha['risco_resposta_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarRiscoResposta($linha['risco_resposta_acesso'], $linha['risco_resposta_id'])) $lista[$linha['risco_resposta_id']]=converte_texto_grafico($linha['risco_resposta_nome']); 
			}
		break;	

	case 'tgn':
		$titulo = ucfirst($config['tgn']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'';
		$nenhum='Nenh'.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'';
		if (trim($textobusca)) $sql->adOnde('tgn_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('tgn.tgn_id, tgn_nome, tgn_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('tgn_dept','tgn_dept', 'tgn_dept_tgn=tgn.tgn_id');
			$sql->adOnde('tgn_dept='.(int)$dept_id.' OR tgn_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('tgn_cia', 'tgn_cia', 'tgn.tgn_id=tgn_cia_tgn');
			$sql->adOnde('tgn_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tgn_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('tgn_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('tgn_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('tgn_ativo = 1');
		$sql->adOrdem('tgn_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarTgn($linha['tgn_acesso'], $linha['tgn_id'])) $lista[$linha['tgn_id']]=converte_texto_grafico($linha['tgn_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTgn($linha['tgn_acesso'], $linha['tgn_id'])) $lista[$linha['tgn_id']]=converte_texto_grafico($linha['tgn_nome']); 
			}
		break;	

	case 'estrategias':
		$titulo = ucfirst($config['iniciativa']);
		$nao_ha='N�o foi encontrado '.($config['genero_iniciativa']=='o' ? 'nenhum' : 'nenhuma').' '.$config['iniciativa'];
		$nenhum=($config['genero_iniciativa']=='o' ? 'Nenhum' : 'Nenhuma').' '.$config['iniciativa'];
		if (trim($textobusca)) $sql->adOnde('pg_estrategia_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('estrategias.pg_estrategia_id, pg_estrategia_nome, pg_estrategia_acesso');
		if ($dept_id) {
			$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
			$sql->adOnde('pg_estrategia_dept='.(int)$dept_id.' OR estrategias_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('estrategia_cia', 'estrategia_cia', 'estrategias.pg_estrategia_id=estrategia_cia_estrategia');
			$sql->adOnde('pg_estrategia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR estrategia_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_estrategia_cia IN ('.$lista_cias.')');
		$sql->adOnde('pg_estrategia_ativo = 1');
		$sql->adOrdem('pg_estrategia_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao){
			foreach($achados as $linha) if (permiteEditarEstrategia($linha['pg_estrategia_acesso'], $linha['pg_estrategia_id'])) $lista[$linha['pg_estrategia_id']]=converte_texto_grafico($linha['pg_estrategia_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarEstrategia($linha['pg_estrategia_acesso'], $linha['pg_estrategia_id'])) $lista[$linha['pg_estrategia_id']]=converte_texto_grafico($linha['pg_estrategia_nome']); 
			}
		break;		
		
	case 'fator':
		$titulo = 'Fator cr�tico de sucesso';
		$nao_ha='N�o foi encontrado nenhum'.($config['genero_fator']=='a' ? 'a' : '').' '.$config['fator'];
		$nenhum='Nenhum'.($config['genero_fator']=='a' ? 'a' : '').' '.$config['fator'];
		if (trim($textobusca)) $sql->adOnde('fator_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('fator.fator_id, fator_nome, fator_acesso');
		if ($dept_id) {
			$sql->esqUnir('fator_dept','fator_dept', 'fator_dept_fator=fator.fator_id');
			$sql->adOnde('fator_dept='.(int)$dept_id.' OR fator_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('fator_cia', 'fator_cia', 'fator.fator_id=fator_cia_fator');
			$sql->adOnde('fator_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR fator_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('fator_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('fator_cia IN ('.$lista_cias.')');
		$sql->adOnde('fator_ativo = 1');
		$sql->adOrdem('fator_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarFator($linha['fator_acesso'], $linha['fator_id'])) $lista[$linha['fator_id']]=converte_texto_grafico($linha['fator_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarFator($linha['fator_acesso'], $linha['fator_id'])) $lista[$linha['fator_id']]=converte_texto_grafico($linha['fator_nome']); 
			}
		break;	
		
	case 'perspectivas':
		$titulo = ucfirst($config['perspectivas']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'];
		$nenhum='Nenh'.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'];
		if (trim($textobusca)) $sql->adOnde('pg_perspectiva_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('perspectivas.pg_perspectiva_id, pg_perspectiva_nome, pg_perspectiva_acesso');
		if ($dept_id) {
			$sql->esqUnir('perspectivas_depts','perspectivas_depts', 'perspectivas_depts.pg_perspectiva_id=perspectivas.pg_perspectiva_id');
			$sql->adOnde('pg_perspectiva_dept='.(int)$dept_id.' OR perspectivas_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('perspectiva_cia', 'perspectiva_cia', 'perspectivas.pg_perspectiva_id=perspectiva_cia_perspectiva');
			$sql->adOnde('pg_perspectiva_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR perspectiva_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_perspectiva_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_perspectiva_cia IN ('.$lista_cias.')');
		$sql->adOnde('pg_perspectiva_ativo = 1');
		$sql->adOrdem('pg_perspectiva_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) {
				if (permiteEditarPerspectiva($linha['pg_perspectiva_acesso'], $linha['pg_perspectiva_id'])) $lista[$linha['pg_perspectiva_id']]=converte_texto_grafico($linha['pg_perspectiva_nome']); 
				}
			}
		else {
			foreach($achados as $linha) {
				if (permiteAcessarPerspectiva($linha['pg_perspectiva_acesso'], $linha['pg_perspectiva_id'])) $lista[$linha['pg_perspectiva_id']]=converte_texto_grafico($linha['pg_perspectiva_nome']); 
				}
			}
		break;			
	
	case 'licao':
		$titulo = ucfirst($config['licoes']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_licao']=='a' ? 'uma' : 'um').' '.$config['licao'];
		$nenhum='Nenh'.($config['genero_licao']=='a' ? 'uma' : 'um').' '.$config['licao'];
		if (trim($textobusca)) $sql->adOnde('licao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('licao.licao_id, licao_nome, licao_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('licao_dept','licao_dept', 'licao_dept_licao=licao.licao_id');
			$sql->adOnde('licao_dept='.(int)$dept_id.' OR licao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('licao_cia', 'licao_cia', 'licao.licao_id=licao_cia_licao');
			$sql->adOnde('licao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR licao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('licao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('licao_cia IN ('.$lista_cias.')');
		$sql->adOnde('licao_ativa = 1');
		$sql->adOrdem('licao_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarLicao($linha['licao_acesso'], $linha['licao_id'])) $lista[$linha['licao_id']]=converte_texto_grafico($linha['licao_nome']); 
			}	
		else {
			foreach($achados as $linha) if (permiteAcessarLicao($linha['licao_acesso'], $linha['licao_id'])) $lista[$linha['licao_id']]=converte_texto_grafico($linha['licao_nome']); 
			}
		break;
	
	case 'tema':
		$titulo = ucfirst($config['tema']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'';
		$nenhum='Nenh'.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'';
		if (trim($textobusca)) $sql->adOnde('tema_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('tema.tema_id, tema_nome, tema_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('tema_depts','tema_depts', 'tema_depts.tema_id=tema.tema_id');
			$sql->adOnde('tema_dept='.(int)$dept_id.' OR tema_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('tema_cia', 'tema_cia', 'tema.tema_id=tema_cia_tema');
			$sql->adOnde('tema_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR tema_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('tema_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('tema_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('tema_ativo = 1');
		$sql->adOrdem('tema_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarTema($linha['tema_acesso'], $linha['tema_id'])) $lista[$linha['tema_id']]=converte_texto_grafico($linha['tema_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTema($linha['tema_acesso'], $linha['tema_id'])) $lista[$linha['tema_id']]=converte_texto_grafico($linha['tema_nome']); 
			}
		break;	
	
	case 'me':
		$titulo = ucfirst($config['me']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'';
		$nenhum='Nenh'.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'';
		if (trim($textobusca)) $sql->adOnde('me_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('me.me_id, me_nome, me_acesso');
		if ($dept_id) {
			$sql->esqUnir('me_dept','me_dept', 'me_dept_me=me.me_id');
			$sql->adOnde('me_dept='.(int)$dept_id.' OR me_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('me_cia', 'me_cia', 'me.me_id=me_cia_me');
			$sql->adOnde('me_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR me_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('me_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('me_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('me_ativo = 1');
		$sql->adOrdem('me_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarMe($linha['me_acesso'], $linha['me_id'])) $lista[$linha['me_id']]=converte_texto_grafico($linha['me_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarMe($linha['me_acesso'], $linha['me_id'])) $lista[$linha['me_id']]=converte_texto_grafico($linha['me_nome']); 
			}
		break;	
	
	case 'ssti':
		$titulo = ucfirst($config['ssti']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'';
		$nenhum='Nenh'.($config['genero_ssti']=='o' ? 'um' : 'uma').' '.$config['ssti'].'';
		if (trim($textobusca)) $sql->adOnde('ssti_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('ssti.ssti_id, ssti_nome, ssti_acesso');
		if ($dept_id) {
			$sql->esqUnir('ssti_dept','ssti_dept', 'ssti_dept_ssti=ssti.ssti_id');
			$sql->adOnde('ssti_dept='.(int)$dept_id.' OR ssti_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('ssti_cia', 'ssti_cia', 'ssti.ssti_id=ssti_cia_ssti');
			$sql->adOnde('ssti_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR ssti_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('ssti_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('ssti_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('ssti_ativo = 1');
		$sql->adOrdem('ssti_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarSSTI($linha['ssti_acesso'], $linha['ssti_id'])) $lista[$linha['ssti_id']]=converte_texto_grafico($linha['ssti_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarSSTI($linha['ssti_acesso'], $linha['ssti_id'])) $lista[$linha['ssti_id']]=converte_texto_grafico($linha['ssti_nome']); 
			}
		break;	
	
	case 'laudo':
		$titulo = ucfirst($config['laudo']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'';
		$nenhum='Nenh'.($config['genero_laudo']=='o' ? 'um' : 'uma').' '.$config['laudo'].'';
		if (trim($textobusca)) $sql->adOnde('laudo_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('laudo.laudo_id, laudo_nome, laudo_acesso');
		if ($dept_id) {
			$sql->esqUnir('laudo_dept','laudo_dept', 'laudo_dept_laudo=laudo.laudo_id');
			$sql->adOnde('laudo_dept='.(int)$dept_id.' OR laudo_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('laudo_cia', 'laudo_cia', 'laudo.laudo_id=laudo_cia_laudo');
			$sql->adOnde('laudo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR laudo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('laudo_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('laudo_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('laudo_ativo = 1');
		$sql->adOrdem('laudo_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarLaudo($linha['laudo_acesso'], $linha['laudo_id'])) $lista[$linha['laudo_id']]=converte_texto_grafico($linha['laudo_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarLaudo($linha['laudo_acesso'], $linha['laudo_id'])) $lista[$linha['laudo_id']]=converte_texto_grafico($linha['laudo_nome']); 
			}
		break;	
		
	case 'trelo':
		$titulo = ucfirst($config['trelo']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'';
		$nenhum='Nenh'.($config['genero_trelo']=='o' ? 'um' : 'uma').' '.$config['trelo'].'';
		if (trim($textobusca)) $sql->adOnde('trelo_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('trelo.trelo_id, trelo_nome, trelo_acesso');
		if ($dept_id) {
			$sql->esqUnir('trelo_dept','trelo_dept', 'trelo_dept_trelo=trelo.trelo_id');
			$sql->adOnde('trelo_dept='.(int)$dept_id.' OR trelo_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('trelo_cia', 'trelo_cia', 'trelo.trelo_id=trelo_cia_trelo');
			$sql->adOnde('trelo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR trelo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('trelo_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('trelo_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('trelo_ativo = 1');
		$sql->adOrdem('trelo_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarTrelo($linha['trelo_acesso'], $linha['trelo_id'])) $lista[$linha['trelo_id']]=converte_texto_grafico($linha['trelo_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTrelo($linha['trelo_acesso'], $linha['trelo_id'])) $lista[$linha['trelo_id']]=converte_texto_grafico($linha['trelo_nome']); 
			}
		break;	
		
	case 'trelo_cartao':
		$titulo = ucfirst($config['trelo_cartao']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'';
		$nenhum='Nenh'.($config['genero_trelo_cartao']=='o' ? 'um' : 'uma').' '.$config['trelo_cartao'].'';
		if (trim($textobusca)) $sql->adOnde('trelo_cartao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('trelo_cartao.trelo_cartao_id, trelo_cartao_nome, trelo_cartao_acesso');
		if ($dept_id) {
			$sql->esqUnir('trelo_cartao_dept','trelo_cartao_dept', 'trelo_cartao_dept_trelo_cartao=trelo_cartao.trelo_cartao_id');
			$sql->adOnde('trelo_cartao_dept='.(int)$dept_id.' OR trelo_cartao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('trelo_cartao_cia', 'trelo_cartao_cia', 'trelo_cartao.trelo_cartao_id=trelo_cartao_cia_trelo_cartao');
			$sql->adOnde('trelo_cartao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR trelo_cartao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('trelo_cartao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('trelo_cartao_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('trelo_cartao_ativo = 1');
		$sql->adOrdem('trelo_cartao_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarTreloCartao($linha['trelo_cartao_acesso'], $linha['trelo_cartao_id'])) $lista[$linha['trelo_cartao_id']]=converte_texto_grafico($linha['trelo_cartao_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarTreloCartao($linha['trelo_cartao_acesso'], $linha['trelo_cartao_id'])) $lista[$linha['trelo_cartao_id']]=converte_texto_grafico($linha['trelo_cartao_nome']); 
			}
		break;		
			
	case 'pdcl':
		$titulo = ucfirst($config['pdcl']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'';
		$nenhum='Nenh'.($config['genero_pdcl']=='o' ? 'um' : 'uma').' '.$config['pdcl'].'';
		if (trim($textobusca)) $sql->adOnde('pdcl_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('pdcl.pdcl_id, pdcl_nome, pdcl_acesso');
		if ($dept_id) {
			$sql->esqUnir('pdcl_dept','pdcl_dept', 'pdcl_dept_pdcl=pdcl.pdcl_id');
			$sql->adOnde('pdcl_dept='.(int)$dept_id.' OR pdcl_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('pdcl_cia', 'pdcl_cia', 'pdcl.pdcl_id=pdcl_cia_pdcl');
			$sql->adOnde('pdcl_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR pdcl_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pdcl_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pdcl_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('pdcl_ativo = 1');
		$sql->adOrdem('pdcl_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPDCL($linha['pdcl_acesso'], $linha['pdcl_id'])) $lista[$linha['pdcl_id']]=converte_texto_grafico($linha['pdcl_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPDCL($linha['pdcl_acesso'], $linha['pdcl_id'])) $lista[$linha['pdcl_id']]=converte_texto_grafico($linha['pdcl_nome']); 
			}
		break;	
		
	case 'pdcl_item':
		$titulo = ucfirst($config['pdcl_item']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'';
		$nenhum='Nenh'.($config['genero_pdcl_item']=='o' ? 'um' : 'uma').' '.$config['pdcl_item'].'';
		if (trim($textobusca)) $sql->adOnde('pdcl_item_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('pdcl_item.pdcl_item_id, pdcl_item_nome, pdcl_item_acesso');
		if ($dept_id) {
			$sql->esqUnir('pdcl_item_dept','pdcl_item_dept', 'pdcl_item_dept_pdcl_item=pdcl_item.pdcl_item_id');
			$sql->adOnde('pdcl_item_dept='.(int)$dept_id.' OR pdcl_item_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('pdcl_item_cia', 'pdcl_item_cia', 'pdcl_item.pdcl_item_id=pdcl_item_cia_pdcl_item');
			$sql->adOnde('pdcl_item_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR pdcl_item_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pdcl_item_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pdcl_item_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('pdcl_item_ativo = 1');
		$sql->adOrdem('pdcl_item_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPDCLItem($linha['pdcl_item_acesso'], $linha['pdcl_item_id'])) $lista[$linha['pdcl_item_id']]=converte_texto_grafico($linha['pdcl_item_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPDCLItem($linha['pdcl_item_acesso'], $linha['pdcl_item_id'])) $lista[$linha['pdcl_item_id']]=converte_texto_grafico($linha['pdcl_item_nome']); 
			}
		break;		
	
	case 'os':
		$titulo = ucfirst($config['os']);
		$nao_ha='N�o foi encontrado nenh'.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].'';
		$nenhum='Nenh'.($config['genero_os']=='o' ? 'um' : 'uma').' '.$config['os'].'';
		if (trim($textobusca)) $sql->adOnde('os_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('os.os_id, os_nome, os_acesso');
		if ($dept_id) {
			$sql->esqUnir('os_dept','os_dept', 'os_dept_os=os.os_id');
			$sql->adOnde('os_dept='.(int)$dept_id.' OR os_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('os_cia', 'os_cia', 'os.os_id=os_cia_os');
			$sql->adOnde('os_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR os_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('os_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('os_cia IN ('.$lista_cias.')');
		
		$sql->adOnde('os_ativo = 1');
		$sql->adOrdem('os_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarOS($linha['os_acesso'], $linha['os_id'])) $lista[$linha['os_id']]=converte_texto_grafico($linha['os_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarOS($linha['os_acesso'], $linha['os_id'])) $lista[$linha['os_id']]=converte_texto_grafico($linha['os_nome']); 
			}
		break;	
		
	case 'demandas':
		$titulo = 'Demanda';
		$nao_ha='N�o foi encontrado nenhuma demanda';
		$nenhum='Nenhuma demanda';
		$clausula=getParam($_REQUEST, 'clausula', '');
		if (trim($textobusca)) $sql->adOnde('demanda_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('demandas.demanda_id, demanda_nome, demanda_acesso');
		if ($dept_id) {
			$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
			$sql->adOnde('demanda_dept='.(int)$dept_id.' OR demanda_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('demanda_cia', 'demanda_cia', 'demandas.demanda_id=demanda_cia_demanda');
			$sql->adOnde('demanda_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR demanda_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('demanda_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('demanda_cia IN ('.$lista_cias.')');
		
		if ($clausula) $sql->adOnde($clausula);
		$sql->adOnde('demanda_ativa = 1');
		$sql->adOrdem('demanda_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarDemanda($linha['demanda_acesso'], $linha['demanda_id'])) $lista[$linha['demanda_id']]=converte_texto_grafico($linha['demanda_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarDemanda($linha['demanda_acesso'], $linha['demanda_id'])) $lista[$linha['demanda_id']]=converte_texto_grafico($linha['demanda_nome']); 
			}
		break;	
	
	case 'brainstorm':
		$titulo = 'Brainstorm';
		$nao_ha='N�o foi encontrado nenhum brainstorm';
		$nenhum='Nenhum brainstorm';
		$clausula=getParam($_REQUEST, 'clausula', '');
		if (trim($textobusca)) $sql->adOnde('brainstorm_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('brainstorm.brainstorm_id, brainstorm_nome, brainstorm_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('brainstorm_depts','brainstorm_depts', 'brainstorm_depts.brainstorm_id=brainstorm.brainstorm_id');
			$sql->adOnde('brainstorm_dept='.(int)$dept_id.' OR brainstorm_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('brainstorm_cia', 'brainstorm_cia', 'brainstorm.brainstorm_id=brainstorm_cia_brainstorm');
			$sql->adOnde('brainstorm_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR brainstorm_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('brainstorm_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('brainstorm_cia IN ('.$lista_cias.')');

		if ($clausula) $sql->adOnde($clausula);
		$sql->adOnde('brainstorm_ativo = 1');
		$sql->adOrdem('brainstorm_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarBrainstorm($linha['brainstorm_acesso'], $linha['brainstorm_id'])) $lista[$linha['brainstorm_id']]=converte_texto_grafico($linha['brainstorm_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarBrainstorm($linha['brainstorm_acesso'], $linha['brainstorm_id'])) $lista[$linha['brainstorm_id']]=converte_texto_grafico($linha['brainstorm_nome']); 
			}
		break;	
				
	case 'avaliacao':
		$titulo = 'Avalia��o';
		$nao_ha='N�o foi encontrado nenhuma avalia��o';
		$nenhum='Nenhuma avalia��o';
		$clausula=getParam($_REQUEST, 'clausula', '');
		if (trim($textobusca)) $sql->adOnde('avaliacao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('avaliacao_id, avaliacao_nome, avaliacao_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('avaliacao_dept','avaliacao_dept', 'avaliacao_dept_avaliacao=avaliacao.avaliacao_id');
			$sql->adOnde('avaliacao_dept='.(int)$dept_id.' OR avaliacao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('avaliacao_cia', 'avaliacao_cia', 'avaliacao.avaliacao_id=avaliacao_cia_avaliacao');
			$sql->adOnde('avaliacao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR avaliacao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('avaliacao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('avaliacao_cia IN ('.$lista_cias.')');
		
		if ($clausula) $sql->adOnde($clausula);
		$sql->adOnde('avaliacao_ativa = 1');
		$sql->adOrdem('avaliacao_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarAvaliacao($linha['avaliacao_acesso'], $linha['avaliacao_id'])) $lista[$linha['avaliacao_id']]=converte_texto_grafico($linha['avaliacao_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarAvaliacao($linha['avaliacao_acesso'], $linha['avaliacao_id'])) $lista[$linha['avaliacao_id']]=converte_texto_grafico($linha['avaliacao_nome']); 
			}
		break;		
		
	case 'agenda':
		$titulo = 'Compromisso';
		$nao_ha='N�o foi encontrado nenhum compromisso';
		$nenhum='Nenhum compromisso';
		$clausula=getParam($_REQUEST, 'clausula', '');
		if (trim($textobusca)) $sql->adOnde('agenda_titulo LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('agenda_id, agenda_titulo, agenda_acesso');
		$sql->adOnde('agenda_dono='.(int)$Aplic->usuario_id);
		if ($clausula) $sql->adOnde($clausula);
		$sql->adOrdem('agenda_titulo');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) $lista[$linha['agenda_id']]=converte_texto_grafico($linha['agenda_titulo']); 
			}
		else{ 
			foreach($achados as $linha) $lista[$linha['agenda_id']]=converte_texto_grafico($linha['agenda_titulo']); 
			}
		break;		
	
	case 'metas':
		$titulo = ucfirst($config['meta']);
		$nao_ha='N�o foi encontrado nenhum'.($config['genero_meta']=='a' ? 'a' : '').' '.$config['meta'];
		$nenhum='Nenhum'.($config['genero_meta']=='a' ? 'a' : '').' '.$config['meta'];
		if (trim($textobusca)) $sql->adOnde('pg_meta_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('metas.pg_meta_id, pg_meta_nome, pg_meta_acesso');
		if ($dept_id) {
			$sql->esqUnir('metas_depts','metas_depts', 'metas_depts.pg_meta_id=metas.pg_meta_id');
			$sql->adOnde('pg_meta_dept='.(int)$dept_id.' OR metas_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('meta_cia', 'meta_cia', 'metas.pg_meta_id=meta_cia_meta');
			$sql->adOnde('pg_meta_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR meta_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pg_meta_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pg_meta_cia IN ('.$lista_cias.')');
		$sql->adOnde('pg_meta_ativo = 1');
		$sql->adOrdem('pg_meta_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarMeta($linha['pg_meta_acesso'], $linha['pg_meta_id'])) $lista[$linha['pg_meta_id']]=converte_texto_grafico($linha['pg_meta_nome']); 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarMeta($linha['pg_meta_acesso'], $linha['pg_meta_id'])) $lista[$linha['pg_meta_id']]=converte_texto_grafico($linha['pg_meta_nome']); 
			}
		break;	
			
	case 'pratica_indicador':
		$titulo = 'Indicador';
		$nao_ha='N�o foi encontrado nenhum indicador';
		$nenhum='Nenhum indicador';
		if (trim($textobusca)) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_acesso');
		if ($Aplic->profissional){
			$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
			if ($pratica_indicador_tarefa) $sql->adOnde('pratica_indicador_gestao_tarefa='.$pratica_indicador_tarefa);
			elseif ($pratica_indicador_projeto) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$pratica_indicador_projeto);
			elseif ($pratica_indicador_perspectiva) $sql->adOnde('pratica_indicador_gestao_perspectiva='.$pratica_indicador_perspectiva);
			elseif ($pratica_indicador_tema) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$pratica_indicador_tema);
			elseif ($pratica_indicador_objetivo_estrategico) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pratica_indicador_objetivo_estrategico);
			elseif ($pratica_indicador_fator) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pratica_indicador_fator);
			elseif ($pratica_indicador_estrategia) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pratica_indicador_estrategia);
			elseif ($pratica_indicador_meta) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pratica_indicador_meta);
			elseif ($pratica_indicador_pratica) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_indicador_pratica);
			elseif ($pratica_indicador_acao) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$pratica_indicador_acao);
			elseif ($pratica_indicador_canvas) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$pratica_indicador_canvas);
			elseif ($pratica_indicador_risco) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$pratica_indicador_risco);
			elseif ($pratica_indicador_risco_resposta) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$pratica_indicador_risco_resposta);
			elseif ($pratica_indicador_calendario) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$pratica_indicador_calendario);
			elseif ($pratica_indicador_monitoramento) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$pratica_indicador_monitoramento);
			elseif ($pratica_indicador_ata) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$pratica_indicador_ata);
			elseif ($pratica_indicador_mswot) $sql->adOnde('pratica_indicador_gestao_mswot='.(int)$pratica_indicador_mswot);
			elseif ($pratica_indicador_swot) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$pratica_indicador_swot);
			elseif ($pratica_indicador_operativo) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$pratica_indicador_operativo);
			elseif ($pratica_indicador_instrumento) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$pratica_indicador_instrumento);
			elseif ($pratica_indicador_recurso) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$pratica_indicador_recurso);
			elseif ($pratica_indicador_problema) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$pratica_indicador_problema);
			elseif ($pratica_indicador_demanda) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$pratica_indicador_demanda);
			elseif ($pratica_indicador_programa) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$pratica_indicador_programa);
			elseif ($pratica_indicador_licao) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$pratica_indicador_licao);
			elseif ($pratica_indicador_evento) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$pratica_indicador_evento);
			elseif ($pratica_indicador_link) $sql->adOnde('pratica_indicador_gestao_link='.(int)$pratica_indicador_link);
			elseif ($pratica_indicador_avaliacao) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$pratica_indicador_avaliacao);
			elseif ($pratica_indicador_tgn) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$pratica_indicador_tgn);
			elseif ($pratica_indicador_brainstorm) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$pratica_indicador_brainstorm);
			elseif ($pratica_indicador_gut) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$pratica_indicador_gut);
			elseif ($pratica_indicador_causa_efeito) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$pratica_indicador_causa_efeito);
			elseif ($pratica_indicador_arquivo) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$pratica_indicador_arquivo);
			elseif ($pratica_indicador_forum) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$pratica_indicador_forum);
			elseif ($pratica_indicador_checklist) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$pratica_indicador_checklist);
			elseif ($pratica_indicador_agenda) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$pratica_indicador_agenda);
			elseif ($pratica_indicador_agrupamento) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$pratica_indicador_agrupamento);
			elseif ($pratica_indicador_patrocinador) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$pratica_indicador_patrocinador);
			elseif ($pratica_indicador_template) $sql->adOnde('pratica_indicador_gestao_template='.(int)$pratica_indicador_template);
			elseif ($pratica_indicador_painel) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$pratica_indicador_painel);
			elseif ($pratica_indicador_painel_odometro) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$pratica_indicador_painel_odometro);
			elseif ($pratica_indicador_painel_composicao) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$pratica_indicador_painel_composicao);
			elseif ($pratica_indicador_tr) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$pratica_indicador_tr);
			elseif ($pratica_indicador_me) $sql->adOnde('pratica_indicador_gestao_me='.(int)$pratica_indicador_me);
			}
		else {
			if ($pratica_indicador_projeto) $sql->adOnde('pratica_indicador_projeto='.(int)$pratica_indicador_projeto);
			elseif ($pratica_indicador_pratica) $sql->adOnde('pratica_indicador_pratica='.(int)$pratica_indicador_pratica);
			elseif ($pratica_indicador_acao) $sql->adOnde('pratica_indicador_acao='.(int)$pratica_indicador_acao);
			elseif ($pratica_indicador_objetivo_estrategico) $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$pratica_indicador_objetivo_estrategico);
			elseif ($pratica_indicador_estrategia) $sql->adOnde('pratica_indicador_estrategia='.(int)$pratica_indicador_estrategia);
			elseif ($pratica_indicador_perspectiva) $sql->adOnde('pratica_indicador_perspectiva='.(int)$pratica_indicador_perspectiva);
			elseif ($pratica_indicador_meta) $sql->adOnde('pratica_indicador_meta='.(int)$pratica_indicador_meta);
			elseif ($pratica_indicador_fator) $sql->adOnde('pratica_indicador_fator='.(int)$pratica_indicador_fator);
			elseif ($pratica_indicador_tema) $sql->adOnde('pratica_indicador_tema='.(int)$pratica_indicador_tema);
			}
		
		if ($dept_id) {
			$sql->esqUnir('pratica_indicador_depts','pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
			$sql->adOnde('pratica_indicador_dept='.(int)$dept_id.' OR pratica_indicador_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('indicador_cia', 'indicador_cia', 'pratica_indicador.pratica_indicador_id=indicador_cia_indicador');
			$sql->adOnde('pratica_indicador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('pratica_indicador_cia IN ('.$lista_cias.')');
		$sql->adOnde('pratica_indicador_ativo = 1');
		$sql->adOrdem('pratica_indicador_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarIndicador($linha['pratica_indicador_acesso'], $linha['pratica_indicador_id'])) $lista[$linha['pratica_indicador_id']]=$linha['pratica_indicador_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarIndicador($linha['pratica_indicador_acesso'], $linha['pratica_indicador_id'])) $lista[$linha['pratica_indicador_id']]=$linha['pratica_indicador_nome']; 
			}
		break;					

	case 'plano_acao':
		$plano_acao_id = intval(getParam($_REQUEST, 'plano_acao_id', 0));
		$titulo = ucfirst($config['acao']);
		$nao_ha='N�o foi encontrad'.($config['genero_acao']=='a' ? 'a nenhuma' : ' nenhum').' '.$config['acao'];
		$nenhum='Nenhum'.($config['genero_acao']=='a' ? 'a' : '').' '.$config['acao'];
		if (trim($textobusca)) $sql->adOnde('plano_acao_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('plano_acao.plano_acao_id, plano_acao_nome, plano_acao_acesso');
		
		if ($dept_id) {
			$sql->esqUnir('plano_acao_dept','plano_acao_dept', 'plano_acao_dept_acao=plano_acao.plano_acao_id');
			$sql->adOnde('plano_acao_dept='.(int)$dept_id.' OR plano_acao_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('plano_acao_cia', 'plano_acao_cia', 'plano_acao.plano_acao_id=plano_acao_cia_plano_acao');
			$sql->adOnde('plano_acao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_acao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('plano_acao_cia IN ('.$lista_cias.')');
		$sql->adOnde('plano_acao_ativo = 1');
		$sql->adOrdem('plano_acao_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarPlanoAcao($linha['plano_acao_acesso'], $linha['plano_acao_id'])) $lista[$linha['plano_acao_id']]=$linha['plano_acao_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarPlanoAcao($linha['plano_acao_acesso'], $linha['plano_acao_id'])) $lista[$linha['plano_acao_id']]=$linha['plano_acao_nome']; 
			}
		break;			

	case 'links':
		$titulo = 'Link';
		$nao_ha='N�o foi encontrado nenhum link';
		$nenhum='Nenhum link';
		if (trim($textobusca)) $sql->adOnde('link_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('links.link_id, link_nome, link_acesso');
		if ($dept_id) {
			$sql->esqUnir('link_dept','link_dept', 'link_dept_link=links.link_id');
			$sql->adOnde('link_dept='.(int)$dept_id.' OR link_dept_dept='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('link_cia', 'link_cia', 'links.link_id=link_cia_link');
			$sql->adOnde('link_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR link_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('link_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('link_cia IN ('.$lista_cias.')');
		$sql->adOnde('link_ativo = 1');
		$sql->adOrdem('link_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarLink($linha['link_acesso'], $linha['link_id'])) $lista[$linha['link_id']]=$linha['link_nome'];
			}
		else {
			foreach($achados as $linha) if (permiteAcessarLink($linha['link_acesso'], $linha['link_id'])) $lista[$linha['link_id']]=$linha['link_nome'];
			}
		break;	
	
	case 'gut':
		$titulo = 'GUT';
		$nao_ha='N�o foi encontrado nenhuma matriz GUT';
		$nenhum='Nenhuma matriz GUT';
		if (trim($textobusca)) $sql->adOnde('gut_nome LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('gut.gut_id, gut_nome, gut_acesso');
		if ($dept_id) {
			$sql->esqUnir('gut_depts','gut_depts', 'gut_depts.gut_id=gut.gut_id');
			$sql->adOnde('gut_dept='.(int)$dept_id.' OR gut_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('gut_cia', 'gut_cia', 'gut.gut_id=gut_cia_gut');
			$sql->adOnde('gut_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR gut_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('gut_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('gut_cia IN ('.$lista_cias.')');
		$sql->adOnde('gut_ativo = 1');
		$sql->adOrdem('gut_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarGUT($linha['gut_acesso'], $linha['gut_id'])) $lista[$linha['gut_id']]=$linha['gut_nome']; 
			}
		else {
			foreach($achados as $linha) if (permiteAcessarGUT($linha['gut_acesso'], $linha['gut_id'])) $lista[$linha['gut_id']]=$linha['gut_nome']; 
			}
		break;	
	
	case 'causa_efeito':
		$titulo = 'Diagrama de Causa-Efeito';
		$nao_ha='N�o foi encontrado nenhum diagrama de causa-efeito';
		$nenhum='Nenhum diagrama de causa-efeito';
		if (trim($textobusca)) $sql->adOnde('causa_efeito_nome LIKE \'%'.$textobusca.'%\' OR causa_efeito_objeto LIKE \'%'.$textobusca.'%\' OR causa_efeito_descricao LIKE \'%'.$textobusca.'%\'');
		$sql->adCampo('causa_efeito.causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
		if ($dept_id) {
			$sql->esqUnir('causa_efeito_depts','causa_efeito_depts', 'causa_efeito_depts.causa_efeito_id=causa_efeito.causa_efeito_id');
			$sql->adOnde('causa_efeito_dept='.(int)$dept_id.' OR causa_efeito_depts.dept_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('causa_efeito_cia', 'causa_efeito_cia', 'causa_efeito.causa_efeito_id=causa_efeito_cia_causa_efeito');
			$sql->adOnde('causa_efeito_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR causa_efeito_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias) $sql->adOnde('causa_efeito_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('causa_efeito_cia IN ('.$lista_cias.')');
		$sql->adOnde('causa_efeito_ativo = 1');
		$sql->adOrdem('causa_efeito_nome');
		$achados=$sql->Lista();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditarCausa_efeito($linha['causa_efeito_acesso'], $linha['causa_efeito_id'])) $lista[$linha['causa_efeito_id']]=$linha['causa_efeito_nome']; 
			}
		else{
			foreach($achados as $linha) if (permiteAcessarCausa_efeito($linha['causa_efeito_acesso'], $linha['causa_efeito_id'])) $lista[$linha['causa_efeito_id']]=$linha['causa_efeito_nome']; 
			}
		break;				
	
	case 'projetos':
		$titulo = ucfirst($config['projeto']).($aceita_portfolio ? ' / '.ucfirst($config['portfolio']) : '');
		$nenhum='Nenhum'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].($aceita_portfolio ? ' / portf�lio' : '');
		$sql->adTabela('projetos', 'pr');
		$sql->esqUnir('usuarios', 'u', 'pr.projeto_responsavel = u.usuario_id');
		$sql->esqUnir('cias', 'cias', 'pr.projeto_cia = cias.cia_id');
		$sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
		$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_projeto = pr.projeto_id');
		if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
		if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
		if (!$portfolio && !$portfolio_pai && !$aceita_portfolio) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
		elseif($portfolio && !$portfolio_pai)  $sql->adOnde('pr.projeto_portfolio=1 AND (pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL)');
		elseif ($portfolio_pai){
			$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
			$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
			}
		if ($favorito_id){
			$sql->internoUnir('favorito_lista', 'favorito_lista', 'pr.projeto_id=favorito_lista_campo');
			$sql->internoUnir('favorito', 'favorito', 'favorito.favorito_id=favorito_lista_favorito');
			$sql->adOnde('favorito.favorito_id IN ('.$favorito_id.')');
			}
		
		if (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');		
		if ($projetostatus){
			if ($projetostatus == -1) $sql->adOnde('projeto_ativo = 1');
			elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo = 0');
			elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
			}	
		
		if ($dept_id && !$favorito_id) {
			$sql->esqUnir('projeto_depts','projeto_depts', 'projeto_depts.projeto_id=pr.projeto_id');
			$sql->adOnde('projeto_dept='.(int)$dept_id.' OR projeto_depts.departamento_id='.(int)$dept_id);
			}
		elseif ($Aplic->profissional && ($cia_id || $lista_cias) && !$favorito_id) {
			$sql->esqUnir('projeto_cia', 'projeto_cia', 'pr.projeto_id=projeto_cia_projeto');
			$sql->adOnde('projeto_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}	
		elseif ($cia_id && !$lista_cias && !$favorito_id) $sql->adOnde('projeto_cia='.(int)$cia_id);
		elseif ($lista_cias && !$favorito_id) $sql->adOnde('projeto_cia IN ('.$lista_cias.')');
		
		if ($projeto_tipo > -1)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
		if ($projeto_setor) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
		if ($projeto_segmento) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
		if ($projeto_intervencao) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
		if ($projeto_tipo_intervencao) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
		if ($supervisor) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
		if ($autoridade) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
		if ($responsavel) $sql->adOnde('pr.projeto_responsavel IN ('.$responsavel.')');
		if (trim($projtextobusca)) $sql->adOnde('pr.projeto_nome LIKE \'%'.$projtextobusca.'%\' OR pr.projeto_descricao LIKE \'%'.$projtextobusca.'%\' OR pr.projeto_objetivos LIKE \'%'.$projtextobusca.'%\' OR pr.projeto_como LIKE \'%'.$projtextobusca.'%\' OR pr.projeto_codigo LIKE \'%'.$projtextobusca.'%\'');
		
		$sql->adCampo('pr.projeto_id, pr.projeto_nome, pr.projeto_acesso, projeto_portfolio');
		$sql->adOnde('pr.projeto_template='.($template ? '1' : '0 OR pr.projeto_template IS NULL'));
		if ($projeto_id)	$sql->adOnde('pr.projeto_id!='.(int)$projeto_id);
		$sql->adOnde('projeto_ativo = 1');
		$sql->adOrdem('pr.projeto_nome');
		
		$achados=$sql->Lista();
		$vetor_icone=array();
		$lista=array();
		if ($edicao) {
			foreach($achados as $linha) if (permiteEditar($linha['projeto_acesso'], $linha['projeto_id'])) {
				$lista[$linha['projeto_id']]=$linha['projeto_nome'];
				$vetor_icone[$linha['projeto_id']]=($linha['projeto_portfolio'] ? imagem('icones/portfolio_p.gif') : imagem('icones/vazio16.gif'));
				} 
			}
		else {
			foreach($achados as $linha) if (permiteAcessar($linha['projeto_acesso'], $linha['projeto_id'])) {
				$lista[$linha['projeto_id']]=$linha['projeto_nome']; 
				$vetor_icone[$linha['projeto_id']]=($linha['projeto_portfolio'] ? imagem('icones/portfolio_p.gif') : imagem('icones/vazio16.gif'));
				}
			}
		break;
	
	case 'tarefas':
		$tarefa_projeto=getParam($_REQUEST, 'tarefa_projeto', 0);
		if ($tarefa_projeto) $projeto_id=$tarefa_projeto;
		$titulo = ucfirst($config['tarefa']);
		$sql->adCampo('tarefa_id, tarefa_nome, tarefa_acesso');
		$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
		$sql->adOnde('tarefa_superior = tarefa_id OR tarefa_superior IS NULL');
		$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC, tarefa_nome ASC');
		$lista_tarefas = $sql->Lista();
		$sql->limpar();
		$saida='';
		$saida.= '<tr><td colspan=2 style="margin-bottom:0cm; margin-top:0cm;"><a href="javascript:setFechar(null, \'\');">Nenhuma</a></td></tr><tr><td>&nbsp;</td></tr>';
		foreach($lista_tarefas as $tarefa){
			if ($edicao) {
				if (permiteEditar($tarefa['tarefa_acesso'], $projeto_id, $tarefa['tarefa_id'])) $saida.='<tr><td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$tarefa['tarefa_id'].'" value="'.$tarefa['tarefa_id'].'" '.(isset($selecionado[$tarefa['tarefa_id']]) ? 'checked="checked"' : '').' /></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$tarefa['tarefa_nome'].'</td></tr>';
				else $saida.='<tr><td></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$tarefa['tarefa_nome'].'</td></tr>';
				}
			else{
				if (permiteAcessar($tarefa['tarefa_acesso'], $projeto_id, $tarefa['tarefa_id'])) $saida.='<tr><td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$tarefa['tarefa_id'].'" value="'.$tarefa['tarefa_id'].'" '.(isset($selecionado[$tarefa['tarefa_id']]) ? 'checked="checked"' : '').' /></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$tarefa['tarefa_nome'].'</td></tr>';
				else $saida.='<tr><td></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$tarefa['tarefa_nome'].'</td></tr>';
				}
			tarefa_subordinada($projeto_id, $tarefa['tarefa_id'], $saida, '');
			}
	
		break;
	
	case 'usuarios':
		$titulo = ucfirst($config['usuario']);
		$sql->adCampo('usuario_id,'.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
		$sql->adTabela('contatos', 'b');
		$sql->adOnde('usuario_contato = contato_id');
		if (trim($textobusca)) $sql->adOnde('contato_nome LIKE \'%'.$textobusca.'%\' OR contato_nomeguerra LIKE \'%'.$textobusca.'%\'');
		if ($dept_id) $sql->adOnde('contato_dept='.(int)$dept_id);
		elseif ($cia_id && !$lista_cias) $sql->adOnde('contato_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
		$nao_ha='N�o foi encontrado nenh'.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'];
		$nenhum='Nenh'.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'];
		$sql->adOnde('usuario_ativo = 1');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$lista = $sql->ListaChave();
		$sql->limpar();
		break;
	
	case 'contatos':
		$titulo = 'Contato';
		$sql->adCampo('contato_id,'.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
		if ($dept_id) $sql->adOnde('contato_dept='.(int)$dept_id);
		elseif ($cia_id && !$lista_cias) $sql->adOnde('contato_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('contato_cia IN ('.$lista_cias.')');
		if (trim($textobusca)) $sql->adOnde('contato_nome LIKE \'%'.$textobusca.'%\' OR contato_nomeguerra LIKE \'%'.$textobusca.'%\'');
		$sql->adOnde('contato_ativo = 1');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$nao_ha='N�o foi encontrado nenhum contato';
		$nenhum='Nenhum contato';
		$lista = $sql->ListaChave();
		$sql->limpar();
		break;
		
	default:
		$ok = false;
		break;
	}
	


	
if (!$ok) {
	echo '<tr><td colspan=20>Par�metros incorretos foram passados'."\n";
	} 
else {

	echo '<tr><td colspan=20 align=center ><b>Selecionar '.$titulo.'</b></td></tr>';
	
	if ($tabela!='tarefas'){
		if (count($lista) > 0) {
			echo '<tr><td style="width:16px;"><input type="checkbox" name="todos" id="todos" value="todos" onclick="marca_sel_todas();" /></td><td style="margin-bottom:0cm; margin-top:0cm;"></td></tr>';
			foreach ($lista as $chave => $val) echo (isset($extra[$chave]) ? $extra[$chave] : '').'<tr><td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$chave.'" value="'.$chave.'" '.(isset($selecionado[$chave]) ? 'checked="checked"' : '').' /></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$val.'</td></tr>';
			}
		else 	echo '<tr><td><a href="javascript:setFechar(0, \'\');">'.$nao_ha.'</a></td></tr>';
		}
	else echo $saida;
	echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr><td width=100%>'.botao('confirmar', '', '','','env.enviado.value=1; env.submit();').'</td>'.($Aplic->profissional ? '' : '<td>'.botao('cancelar', '', '','','javascript:cancelarSelecao()').'</td>').'</tr></table></td></tr>';
	} 
	
echo '</table>';	
echo '</form>';
echo estiloFundoCaixa();	

	
function tarefa_subordinada($projeto_id, $tarefa_id, &$saida, $espaco=''){
	global $sql, $edicao, $Aplic, $selecionado;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id, tarefa_nome, tarefa_acesso');
	$sql->adOrdem(($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio ASC, tarefa_nome ASC');
	$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
	$sql->adOnde('tarefa_id != '.(int)$tarefa_id);
	$lista_tarefas = $sql->Lista();
	$sql->limpar();
	foreach($lista_tarefas as $tarefa){
		if ($edicao) {
			if (permiteEditar($tarefa['tarefa_acesso'], $projeto_id, $tarefa['tarefa_id'])) $saida.='<tr><td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$tarefa['tarefa_id'].'" value="'.$tarefa['tarefa_id'].'" '.(isset($selecionado[$tarefa['tarefa_id']]) ? 'checked="checked"' : '').' /></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$espaco.imagem('icones/subnivel.gif').$tarefa['tarefa_nome'].'</td></tr>';
			else $saida.='<tr><td></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$espaco.imagem('icones/subnivel.gif').$tarefa['tarefa_nome'].'</td></tr>';
			}
		else{
			if (permiteAcessar($tarefa['tarefa_acesso'], $projeto_id, $tarefa['tarefa_id'])) $saida.='<tr><td style="width:16px;"><input type="checkbox" name="campos[]" id="campo_'.$tarefa['tarefa_id'].'" value="'.$tarefa['tarefa_id'].'" '.(isset($selecionado[$tarefa['tarefa_id']]) ? 'checked="checked"' : '').' /></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$espaco.imagem('icones/subnivel.gif').$tarefa['tarefa_nome'].'</td></tr>';
			else $saida.='<tr><td></td><td style="margin-bottom:0cm; margin-top:0cm;">'.$espaco.imagem('icones/subnivel.gif').$tarefa['tarefa_nome'].'</td></tr>';
			}
		tarefa_subordinada($projeto_id, $tarefa['tarefa_id'], $saida, '&nbsp;&nbsp;'.$espaco);
		}
	}	
	
?>
<script type="text/javascript">

function marca_sel_todas() {
  with(document.getElementById('env')) {
	  for(i=0; i < elements.length; i++) {
			thiselm = elements[i];
			thiselm.checked = !thiselm.checked
      }
    }
  }

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 
	}

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia, deptartamento){
	env.cia_dept.value=cia;
	env.dept_id.value=deptartamento;
	env.submit();
	}	
		

function enviar(){
	document.env.submit();
	}		
		

<?php if ($tabela=='plano_acao_item') { ?>
	
function popAcao() {
		var f = document.env;
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAcao(chave, valor){
		document.env.plano_acao_id.value = chave;
		document.env.acao_nome.value = valor;
		document.env.submit();
		}

<?php } ?>

<?php if ($tabela=='beneficio') { ?>
	
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	document.env.programa_id.value = chave;
	document.env.programa_nome.value = valor;
	document.env.submit();
	}

<?php } ?>



<?php if ($tabela=='tarefas') { ?>
	function popProjeto() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setProjeto(chave, valor){
		document.env.projeto_id.value = chave;
		document.env.projeto_nome.value = valor;
		document.env.submit();
		}

<?php } ?>	


		
<?php if ($tabela=='pratica_indicador') { ?>


	function mostrar(){
		limpar_tudo();
		esconder_tipo();
		if (document.getElementById('tipo_relacao').value){
			document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
			if (document.getElementById('tipo_relacao').value=='projeto') document.getElementById('tarefa').style.display='';
			}
		}
	
	function esconder_tipo(){
		document.getElementById('projeto').style.display='none';
		document.getElementById('tarefa').style.display='none';
		document.getElementById('pratica').style.display='none';
		document.getElementById('acao').style.display='none';
		document.getElementById('objetivo').style.display='none';
		document.getElementById('estrategia').style.display='none';
		document.getElementById('fator').style.display='none';
		document.getElementById('perspectiva').style.display='none';
		document.getElementById('canvas').style.display='none';
		document.getElementById('risco').style.display='none';
		document.getElementById('risco_resposta').style.display='none';
		document.getElementById('meta').style.display='none';
		document.getElementById('tema').style.display='none';
		document.getElementById('calendario').style.display='none';
		document.getElementById('monitoramento').style.display='none';
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
		document.getElementById('template').style.display='none';
		document.getElementById('painel').style.display='none';
		document.getElementById('painel_odometro').style.display='none';
		document.getElementById('painel_composicao').style.display='none';
	
		<?php
		if($Aplic->modulo_ativo('agrupamento')) echo 'document.getElementById(\'agrupamento\').style.display=\'none\';';
		if($Aplic->modulo_ativo('patrocinadores')) echo 'document.getElementById(\'patrocinador\').style.display=\'none\';';
		if($Aplic->modulo_ativo('swot')) {
			echo 'document.getElementById(\'mswot\').style.display=\'none\';';
			echo 'document.getElementById(\'swot\').style.display=\'none\';';
			}
		if($Aplic->modulo_ativo('atas')) echo 'document.getElementById(\'ata\').style.display=\'none\';';
		if($Aplic->modulo_ativo('operativo')) echo 'document.getElementById(\'operativo\').style.display=\'none\';';
		if($tr_ativo) echo 'document.getElementById(\'tr\').style.display=\'none\';';
		if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.getElementById(\'me\').style.display=\'none\';';
		?>
		}
	
	
	<?php  if ($Aplic->profissional) { ?>
	
		function popAgrupamento() {
			if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, window.setAgrupamento, window);
			else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
	
		function setAgrupamento(chave, valor){
			limpar_tudo();
			document.env.pratica_indicador_agrupamento.value = chave;
			document.env.agrupamento_nome.value = valor;
			document.env.submit();
			}
	
		function popPatrocinador() {
			if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, window.setPatrocinador, window);
			else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
	
		function setPatrocinador(chave, valor){
			limpar_tudo();
			document.env.pratica_indicador_patrocinador.value = chave;
			document.env.patrocinador_nome.value = valor;
			document.env.submit();
			}
	
		function popTemplate() {
			if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, window.setTemplate, window);
			else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
	
		function setTemplate(chave, valor){
			limpar_tudo();
			document.env.pratica_indicador_template.value = chave;
			document.env.template_nome.value = valor;
			document.env.submit();
			}
	
		function popPainel() {
			if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, window.setPainel, window);
			else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
	
		function setPainel(chave, valor){
			limpar_tudo();
			document.env.pratica_indicador_painel.value = chave;
			document.env.painel_nome.value = valor;
			document.env.submit();
			}
	
		function popOdometro() {
			if (window.parent.gpwebApp) parent.gpwebApp.popUp('Od�metro', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, window.setOdometro, window);
			else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, 'Od�metro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
	
		function setOdometro(chave, valor){
			limpar_tudo();
			document.env.pratica_indicador_painel_odometro.value = chave;
			document.env.painel_odometro_nome.value = valor;
			document.env.submit();
			}
	
		function popComposicaoPaineis() {
			if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composi��o de Pain�is', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, window.setComposicaoPaineis, window);
			else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, 'Composi��o de Pain�is','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
	
		function setComposicaoPaineis(chave, valor){
			limpar_tudo();
			document.env.pratica_indicador_painel_composicao.value = chave;
			document.env.painel_composicao_nome.value = valor;
			document.env.submit();
			}
	
		function popTR() {
			if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, window.setTR, window);
			else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
	
		function setTR(chave, valor){
			limpar_tudo();
			document.env.pratica_indicador_tr.value = chave;
			document.env.tr_nome.value = valor;
			document.env.submit();
			}
	
		function popMe() {
			if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&edicao=1&selecao=1&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, window.setMe, window);
			else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
			}
	
		function setMe(chave, valor){
			limpar_tudo();
			document.env.pratica_indicador_me.value = chave;
			document.env.me_nome.value = valor;
			document.env.submit();
			}
	
	<?php } ?>
	
	
	function popProjeto() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setProjeto(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_projeto.value = chave;
		document.env.projeto_nome.value = valor;
		}
	
	function popTarefa() {
		var f = document.env;
		if (f.pratica_indicador_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
		else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, window.setTarefa, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTarefa( chave, valor ) {
		limpar_tudo();
		document.env.pratica_indicador_tarefa.value = chave;
		document.env.tarefa_nome.value = valor;
		document.env.submit();
		}
	
	function popPerspectiva() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPerspectiva(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_perspectiva.value = chave;
		document.env.perspectiva_nome.value = valor;
		document.env.submit();
		}
	
	function popTema() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTema(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_tema.value = chave;
		document.env.tema_nome.value = valor;
		document.env.submit();
		}
	
	function popObjetivo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setObjetivo(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_objetivo_estrategico.value = chave;
		document.env.objetivo_nome.value = valor;
		document.env.submit();
		}
	
	function popFator() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setFator(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_fator.value = chave;
		document.env.fator_nome.value = valor;
		document.env.submit();
		}
	
	function popEstrategia() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setEstrategia(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_estrategia.value = chave;
		document.env.estrategia_nome.value = valor;
		document.env.submit();
		}
	
	function popMeta() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setMeta(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_meta.value = chave;
		document.env.meta_nome.value = valor;
		document.env.submit();
		}
	
	function popPratica() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPratica(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_pratica.value = chave;
		document.env.pratica_nome.value = valor;
		document.env.submit();
		}
	
	
	function popAcao() {
		var f = document.env;
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAcao(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_acao.value = chave;
		document.env.acao_nome.value = valor;
		document.env.submit();
		}
	
	<?php  if (isset($config['canvas'])) { ?>
	function popCanvas() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setCanvas(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_canvas.value = chave;
		document.env.canvas_nome.value = valor;
		document.env.submit();
		}
	<?php }?>
	
	<?php  if (isset($config['risco'])) { ?>
	function popRisco() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, window.setRisco, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setRisco(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_risco.value = chave;
		document.env.risco_nome.value = valor;
		document.env.submit();
		}
	<?php }?>
	
	<?php  if (isset($config['risco_respostas'])) { ?>
	function popRiscoResposta() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, window.setRiscoResposta, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setRiscoResposta(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_risco_resposta.value = chave;
		document.env.risco_resposta_nome.value = valor;
		document.env.submit();
		}
	<?php }?>
	
	
	function popCalendario() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, window.setCalendario, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setCalendario(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_calendario.value = chave;
		document.env.calendario_nome.value = valor;
		document.env.submit();
		}
	
	function popMonitoramento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setMonitoramento(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_monitoramento.value = chave;
		document.env.monitoramento_nome.value = valor;
		document.env.submit();
		}
	
	function popAta() {
		parent.gpwebApp.popUp('Ata de Reuni�o', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('cia_id').value, window.setAta, window);
		}
	
	function setAta(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_ata.value = chave;
		document.env.ata_nome.value = valor;
		document.env.submit();
		}
	
	function popMSWOT() {
		parent.gpwebApp.popUp('Matriz SWOT', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('cia_id').value, window.setMSWOT, window);
		}
	
	function setMSWOT(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_mswot.value = chave;
		document.env.mswot_nome.value = valor;
		document.env.submit();
		}
	
	function popSWOT() {
		parent.gpwebApp.popUp('Campo SWOT', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT, window);
		}
	
	function setSWOT(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_swot.value = chave;
		document.env.swot_nome.value = valor;
		document.env.submit();
		}
	
	function popOperativo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setOperativo(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_operativo.value = chave;
		document.env.operativo_nome.value = valor;
		}
	
	function popInstrumento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jur�dico', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, window.setInstrumento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, 'Instrumento Jur�dico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setInstrumento(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_instrumento.value = chave;
		document.env.instrumento_nome.value = valor;
		document.env.submit();
		}
	
	function popRecurso() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, window.setRecurso, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setRecurso(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_recurso.value = chave;
		document.env.recurso_nome.value = valor;
		document.env.submit();
		}
	
	<?php  if (isset($config['problema'])) { ?>
	function popProblema() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, window.setProblema, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setProblema(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_problema.value = chave;
		document.env.problema_nome.value = valor;
		document.env.submit();
		}
	<?php } ?>
	
	
	function popDemanda() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, window.setDemanda, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setDemanda(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_demanda.value = chave;
		document.env.demanda_nome.value = valor;
		document.env.submit();
		}
	
	<?php  if (isset($config['programa'])) { ?>
	function popPrograma() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPrograma(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_programa.value = chave;
		document.env.programa_nome.value = valor;
		document.env.submit();
		}
	<?php } ?>
	
	function popLicao() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, window.setLicao, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setLicao(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_licao.value = chave;
		document.env.licao_nome.value = valor;
		document.env.submit();
		}
	
	
	function popEvento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, window.setEvento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setEvento(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_evento.value = chave;
		document.env.evento_nome.value = valor;
		document.env.submit();
		}
	
	function popLink() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, window.setLink, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setLink(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_link.value = chave;
		document.env.link_nome.value = valor;
		document.env.submit();
		}
	
	function popAvaliacao() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avalia��o', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, window.setAvaliacao, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, 'Avalia��o','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAvaliacao(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_avaliacao.value = chave;
		document.env.avaliacao_nome.value = valor;
		document.env.submit();
		}
	<?php  if (isset($config['tgn'])) { ?>
	function popTgn() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, window.setTgn, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTgn(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_tgn.value = chave;
		document.env.tgn_nome.value = valor;
		document.env.submit();
		}
	<?php } ?>
	function popBrainstorm() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, window.setBrainstorm, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setBrainstorm(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_brainstorm.value = chave;
		document.env.brainstorm_nome.value = valor;
		document.env.submit();
		}
	
	function popGut() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, window.setGut, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setGut(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_gut.value = chave;
		document.env.gut_nome.value = valor;
		document.env.submit();
		}
	
	function popCausa_efeito() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, window.setCausa_efeito, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setCausa_efeito(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_causa_efeito.value = chave;
		document.env.causa_efeito_nome.value = valor;
		document.env.submit();
		}
	
	function popArquivo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, window.setArquivo, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setArquivo(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_arquivo.value = chave;
		document.env.arquivo_nome.value = valor;
		document.env.submit();
		}
	
	function popForum() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('F�rum', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, window.setForum, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, 'F�rum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setForum(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_forum.value = chave;
		document.env.forum_nome.value = valor;
		document.env.submit();
		}
	
	
	function popChecklist() {
		
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	
		
		
		
		//if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 630, 500, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setChecklist&tabela=checklist&valor='+document.getElementById('pratica_indicador_checklist').value+'&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
		//else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setChecklist&tabela=checklist&valor='+document.getElementById('pratica_indicador_checklist').value+'&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setChecklist(chave, valor){
		document.getElementById('pratica_indicador_checklist').value=(chave > 0 ? chave : null);
		document.getElementById('nome_checklist').value=valor;
		}
	
	
	
	function popChecklist2() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist2, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist2&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setChecklist2(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_checklist2.value = chave;
		document.env.checklist_nome2.value = valor;
		document.env.submit();
		}
	
	function popAgenda() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, window.setAgenda, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgenda(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_agenda.value = chave;
		document.env.agenda_nome.value = valor;
		document.env.submit();
		}
	
	
	
	function limpar_tudo(){
	
		document.env.projeto_nome.value = '';
		document.env.pratica_indicador_projeto.value = null;
	
		document.env.pratica_indicador_pratica.value = null;
		document.env.pratica_nome.value = '';
		document.env.pratica_indicador_tarefa.value = null;
		document.env.tarefa_nome.value = '';
		document.env.pratica_indicador_acao.value = null;
		document.env.acao_nome.value = '';
		document.env.pratica_indicador_objetivo_estrategico.value = null;
		document.env.objetivo_nome.value = '';
		document.env.pratica_indicador_estrategia.value = null;
		document.env.estrategia_nome.value = '';
		document.env.pratica_indicador_fator.value = null;
		document.env.fator_nome.value = '';
		document.env.pratica_indicador_perspectiva.value = null;
		document.env.perspectiva_nome.value = '';
		document.env.pratica_indicador_canvas.value = null;
		document.env.canvas_nome.value = '';
		document.env.pratica_indicador_risco.value = null;
		document.env.risco_nome.value = '';
		document.env.pratica_indicador_risco_resposta.value = null;
		document.env.risco_resposta_nome.value = '';
		document.env.pratica_indicador_meta.value = null;
		document.env.meta_nome.value = '';
		document.env.pratica_indicador_tema.value = null;
		document.env.tema_nome.value = '';
		document.env.pratica_indicador_monitoramento.value = null;
		document.env.monitoramento_nome.value = '';
		document.env.pratica_indicador_calendario.value = null;
		document.env.calendario_nome.value = '';
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
		document.env.pratica_indicador_checklist2.value = null;
		document.env.checklist_nome2.value = '';
		document.env.pratica_indicador_agenda.value = null;
		document.env.agenda_nome.value = '';
		document.env.pratica_indicador_template.value = null;
		document.env.template_nome.value = '';
		document.env.pratica_indicador_painel.value = null;
		document.env.painel_nome.value = '';
		document.env.pratica_indicador_painel_odometro.value = null;
		document.env.painel_odometro_nome.value = '';
		document.env.pratica_indicador_painel_composicao.value = null;
		document.env.painel_composicao_nome.value = '';
	
		<?php
		if($Aplic->modulo_ativo('swot')) {
			echo 'document.env.mswot_nome.value = \'\';	document.env.pratica_indicador_mswot.value = null;';
			echo 'document.env.swot_nome.value = \'\';	document.env.pratica_indicador_swot.value = null;';
			}
		if($Aplic->modulo_ativo('atas')) echo 'document.env.ata_nome.value = \'\';	document.env.pratica_indicador_ata.value = null;';
		if($Aplic->modulo_ativo('operativo')) echo 'document.env.operativo_nome.value = \'\';	document.env.pratica_indicador_operativo.value = null;';
		if($Aplic->modulo_ativo('agrupamento')) echo 'document.env.agrupamento_nome.value = \'\';	document.env.pratica_indicador_agrupamento.value = null;';
		if($Aplic->modulo_ativo('patrocinadores')) echo 'document.env.patrocinador_nome.value = \'\';	document.env.pratica_indicador_patrocinador.value = null;';
		if($tr_ativo) echo 'document.env.tr_nome.value = \'\';	document.env.pratica_indicador_tr.value = null;';
		if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.env.me_nome.value = \'\';	document.env.pratica_indicador_me.value = null;';
		?>
		}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

<?php } ?>	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
<?php if ($tabela=='projetos') { ?>

	function mudar_cidades(){
		xajax_selecionar_cidades_ajax(document.getElementById('estado_sigla').value,'municipio_id','combo_cidade', 'class="texto" size=1 style="width:250px;"', (document.getElementById('municipio_id').value ? document.getElementById('municipio_id').value : <?php echo ($municipio_id ? $municipio_id : 0) ?>)); 	
		}	

	
		
	function mudar_segmento(){
		<?php
		if($Aplic->profissional){
			echo '$jq.fn.multiSelect.clear("#projeto_tipo_intervencao");';
			echo '$jq.fn.multiSelect.clear("#projeto_intervencao");';
			}
		else{
			echo 'document.getElementById("projeto_intervencao").length=0;';
			echo 'document.getElementById("projeto_tipo_intervencao").length=0;';		
			}	
		?>
		xajax_mudar_ajax(document.getElementById('projeto_setor').value, 'Segmento', 'projeto_segmento','combo_segmento', 'style="width:250px;" class="texto" size=1 onchange="mudar_intervencao();"'); 	
		}
	
	function mudar_intervencao(){
		<?php
		if($Aplic->profissional) echo '$jq.fn.multiSelect.clear("#projeto_tipo_intervencao");';
		else echo 'document.getElementById("projeto_tipo_intervencao").length=0;';		
		?>
		xajax_mudar_ajax(document.getElementById('projeto_segmento').value, 'Intervencao', 'projeto_intervencao','combo_intervencao', 'style="width:250px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"'); 	
		}
	
	function mudar_tipo_intervencao(){
		xajax_mudar_ajax(document.getElementById('projeto_intervencao').value, 'TipoIntervencao', 'projeto_tipo_intervencao','combo_tipo_intervencao', 'style="width:250px;" class="texto" size=1'); 	
		}	
		
	var usuarios_gerente = '<?php echo $responsavel?>';	
	var usuarios_supervisor = '<?php echo $supervisor?>';	
	var usuarios_autoridade = '<?php echo $autoridade?>';		
		
		
	<?php if ($Aplic->profissional){ ?>
	
	
	function popResponsavel(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, window.setResponsavel, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}
		
	function setResponsavel(usuario_id_string){
		if(!usuario_id_string) usuarios_gerente = '';
		document.getElementById('responsavel').value = usuario_id_string;
		usuarios_gerente = usuario_id_string;
		xajax_lista_nome(usuario_id_string, 'nome_responsavel');
		}
	
	function popSupervisor(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_supervisor, window.setSupervisor, window);
		else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_supervisor, 'contatos','height=500,width=500,resizable,scrollbars=yes');
		}
		
	function setSupervisor(usuario_id_string){
		if(!usuario_id_string) usuarios_gerente = '';
		document.getElementById('supervisor').value = usuario_id_string;
		usuarios_gerente = usuario_id_string;
		xajax_lista_nome(usuario_id_string, 'nome_supervisor');
		}
	
	function popAutoridade(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_autoridade, window.setAutoridade, window);
		else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&usuarios_id_selecionados='+usuarios_autoridade, 'contatos','height=500,width=500,resizable,scrollbars=yes');
		}
	
	function setAutoridade(usuario_id_string){
		if(!usuario_id_string) usuarios_gerente = '';
		document.getElementById('autoridade').value = usuario_id_string;
		usuarios_gerente = usuario_id_string;
		xajax_lista_nome(usuario_id_string, 'nome_autoridade');
		}
	
	<?php } else { ?>
	function popResponsavel(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, window.setResponsavel, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}
		
	function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('responsavel').value=usuario_id;		
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}		
	
	function popSupervisor(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['supervisor']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('supervisor').value, window.setSupervisor, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setSupervisor&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('supervisor').value, 'Supervisor','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}
	
	
	function setSupervisor(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('supervisor').value=usuario_id;		
		document.getElementById('nome_supervisor').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}	
		
	
	function popAutoridade(campo) {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['autoridade']) ?>", 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('autoridade').value, window.setAutoridade, window);
		else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAutoridade&cia_id='+document.getElementById('cia_id').value+'&contato_id='+document.getElementById('autoridade').value, 'Autoridade','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
		}
	
	function setAutoridade(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('autoridade').value=usuario_id;		
		document.getElementById('nome_autoridade').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
		}	
	
	<?php } ?>	
	
	

<?php } ?>


		
			
</script>
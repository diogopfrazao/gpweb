<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';global $m, $a, $u;

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

require_once BASE_DIR.'/modulos/calendario/jornada_links.php';
require_once BASE_DIR.'/modulos/calendario/jornada.class.php';
$tamanho = intval(config('cal_tamanho_string'));
$cia_id=getParam($_REQUEST, 'cia_id', 0);
$departamento_id=getParam($_REQUEST, 'departamento_id', 0);
$usuario_id=getParam($_REQUEST, 'usuario_id', 0);
$suprimido=getParam($_REQUEST, 'sem_cabecalho', 0);
$sem_selecao=getParam($_REQUEST, 'sem_selecao', 0);
$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$recurso_id=getParam($_REQUEST, 'recurso_id', 0);
$jornada_id=getParam($_REQUEST, 'jornada_id', 0);
$data=getParam($_REQUEST, 'data', '');
$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;
if (!$dialogo) $Aplic->salvarPosicao();
if ($suprimido) echo '<LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css">';	
if (!$cia_id  && !$usuario_id && !$projeto_id && !$tarefa_id && !$recurso_id && !$jornada_id) $cia_id=$Aplic->usuario_cia;

if ($jornada_id) $cia_id=0;

$q = new BDConsulta; 

$jornada=new Cjornada($cia_id, $usuario_id, $projeto_id, $recurso_id, $tarefa_id, $jornada_id);
echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input type="hidden" name="a" value="jornada" />';

if (!$suprimido && !$sem_selecao){
	
	
	//if ($Aplic->profissional){
	
		
	
	
		$botoesTitulo = new CBlocoTitulo('Expediente', 'calendario.png', $m, "$m.$a");
		
		
		$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
	  $saida.=dica('Filtros e A??es','Clique nesta barra para esconder/mostrar os filtros e as a??es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/calendario_p.png').'&nbsp;Filtros e A??es</a></div>'.dicaF();
	  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
	  $saida.='<table cellspacing=0 cellpadding=0>';
			
	
		$procurar_om='<tr><td align=right>'.dica(ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table width="100%" cellspacing=0 cellpadding=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;').'</div></td><td><a href="javascript:void(0);" onclick="document.env.projeto_id.value=null; document.env.usuario_id.value=null; document.env.jornada_id.value=null; document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ?cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada ? esquerda.').'</a></td></tr></table></td></tr>';
		$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de sele??o ? direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td align=right><table width="100%" cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
		$procurar_projeto=($Aplic->profissional ? '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['projeto']), 'Filtar pel'.$config['genero_projeto'].' '.$config['projeto'].' escolhido ? direita.').ucfirst($config['projeto']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="projeto_id" name="projeto_id" value="'.$projeto_id.'" /><input type="text" name="nome_projeto_id" value="'.nome_projeto($projeto_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ?cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr></table></td></tr>' : '');
		$procurar_tarefa=($Aplic->profissional ? '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso o arquivo seja espec?fico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever? constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Tarefa:'.dicaF().'</td><td align="left" valign="top" style="white-space: nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($tarefa_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ?cone '.imagem('icones/tarefa_p.gif').' escolher ? qual '.$config['tarefa'].' o jornada ir? pertencer.<br><br>Caso n?o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o jornada ser? d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>' : '');
		$procurar_recurso=($Aplic->profissional ? '<tr><td align="right" style="white-space: nowrap">'.dica('Recurso', 'Filtar pelo recurso escolhido ? direita.').'Recurso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="recurso_id" name="recurso_id" value="'.$recurso_id.'" /><input type="text" name="nome_recurso_id" value="'.nome_recurso($recurso_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar Recurso','Clique neste ?cone '.imagem('icones/recursos_p.gif').' para selecionar um recurso.').'</a></td></tr></table></td></tr>' : '');
		$procurar_calendario='<tr><td align="right" style="white-space: nowrap">'.dica('Calend?rio', 'Filtar pelo calend?rio escolhido ? direita.').'Calend?rio:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="jornada_id" name="jornada_id" value="'.$jornada_id.'" /><input type="text" name="nome_jornada_id" value="'.nome_jornada($jornada_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popJornada();">'.imagem('icones/calendario.gif','Selecionar Calend?rio','Clique neste ?cone '.imagem('icones/calendario.gif').' para selecionar um calend?rio.').'</a></td></tr></table></td></tr>';
		
		$botao_editar='<tr><td align="right" style="white-space: nowrap"><a href="javascript: void(0);" onclick ="editar_jornada();">'.imagem('editar.gif', 'Editar', 'Clique neste ?cone '.imagem('editar.gif').' para editar o expediente.').'</a></td></tr>';
		
		
		$saida.='<tr><td valign=top><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$procurar_projeto.$procurar_tarefa.$procurar_recurso.$procurar_calendario.'</table></td><td valign=top><table cellspacing=0 cellpadding=0>'.$botao_editar.'</table></td></tr></table>';
		$saida.= '</div></div>';
		$botoesTitulo->adicionaCelula($saida);

		//}
		
		
		
	/*	
		
	else {
		$botoesTitulo = new CBlocoTitulo('Expediente', 'calendario.png', $m, "$m.$a");
		$procurar_om='<tr><td align=right>'.dica(ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table width="100%" cellspacing=0 cellpadding=0><tr><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;').'</div></td><td><a href="javascript:void(0);" onclick="document.env.projeto_id.value=null; document.env.usuario_id.value=null; document.env.jornada_id.value=null; document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ?cone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada ? esquerda.').'</a></td></tr></table></td></tr>';
		$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de sele??o ? direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td align=right><table width="100%" cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr></table></td></tr>';
		$procurar_projeto='<input type="hidden" name="projeto_id" value="" />';
		$procurar_tarefa='<input type="hidden" name="tarefa_id" value="" />';
		$procurar_recurso='<input type="hidden" name="recurso_id" value="" />';
		$procurar_calendario='<tr><td align="right" style="white-space: nowrap">'.dica('Calend?rio', 'Filtar pelo calend?rio escolhido ? direita.').'Calend?rio:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="jornada_id" name="jornada_id" value="'.$jornada_id.'" /><input type="text" name="nome_jornada_id" value="'.nome_jornada($jornada_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popJornada();">'.imagem('icones/calendario.gif','Selecionar Calend?rio','Clique neste ?cone '.imagem('icones/calendario.gif').' para selecionar um calend?rio.').'</a></td></tr></table></td></tr>';
		$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$procurar_projeto.$procurar_tarefa.$procurar_recurso.$procurar_calendario.'</table>');
		}
	
	*/
	
	
	
	}
else {
	if ($usuario_id) $botoesTitulo = new CBlocoTitulo('Expediente para '.nome_om($usuario_id,$Aplic->getPref('om_usuario')));
	elseif ($tarefa_id) $botoesTitulo = new CBlocoTitulo('Expediente para '.nome_tarefa($tarefa_id));
	elseif ($projeto_id) $botoesTitulo = new CBlocoTitulo('Expediente para '.nome_projeto($projeto_id));
	else $botoesTitulo = new CBlocoTitulo('Expediente para '.nome_cia($cia_id));
	}
	

if (!$dialogo && ($Aplic->usuario_admin || $Aplic->usuario_super_admin)) $botoesTitulo->adicionaBotao('m=sistema&a=index', 'sistema','','Administra??o do Sistema','Voltar ? tela de Administra??o do Sistema.');
if (!$suprimido && !$sem_selecao) {
	$botoesTitulo->adicionaBotao('', 'bases','','bases','Clique neste bot?o para ver a lista de bases de expedientes cadastradas e poder inserir ou edita-las.', 'url_passar(0, \'m=calendario&a=jornada_novo_lista\');');
	}

$botoesTitulo->mostrar();

if (!$data) $data = new CData();
else $data = new CData($data);

$data->setDay(1);
$data->setMonth(1);
$anoAnterior = $data->format('%Y%m%d');
$anoAnterior = (int)($anoAnterior - 10000);
$anoProximo = $data->format('%Y%m%d');
$anoProximo = (int)($anoProximo + 10000);

echo estiloTopoCaixa();
echo '<table class="std2" width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td><table width="100%" cellspacing=0 cellpadding=0><tr><td colspan="20" valign="top">';
echo '<table border=0 cellspacing=0 cellpadding="2" width="100%" class="motitulo">';
echo '<tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&data='.$anoAnterior.'&cia_id='.(int)$cia_id.'&usuario_id='.$usuario_id.'&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id.'&recurso_id='.$recurso_id.'&jornada_id='.$jornada_id.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif'), 'Ano Anterior', 'Clique neste ?cone '.imagem('icones/'.($estilo_interface=='metro' ? 'navAnterior_metro.png' :'anterior.gif')).' para exibir o ano anterior.').'</a></td>';
echo '<th width="100%" align="center">'.htmlentities($data->format('%Y')).'</th><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&u='.$u.'&data='.$anoProximo.'&cia_id='.(int)$cia_id.'&usuario_id='.$usuario_id.'&projeto_id='.$projeto_id.'&tarefa_id='.$tarefa_id.'&recurso_id='.$recurso_id.'&jornada_id='.$jornada_id.'\');">'.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif'), 'Pr?ximo Ano', 'Clique neste ?cone '.imagem('icones/'.($estilo_interface=='metro' ? 'navProximo_metro.png' :'proximo.gif')).' para exibir o pr?ximo ano.').'</a></td></tr></table></td></tr>';
$jornada->setData($data);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$jornada->calendarioMesAtual().'</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">'.$jornada->calendarioMesAtual().'</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

$jornada->adicionarMes(1);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '</tr></table>';

$jornada->adicionarMes(1);
echo '<table cellspacing=0 cellpadding=0 border=0 width="100%"><tr>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';

$jornada->adicionarMes(1);
echo '<td valign="top" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td valign="top" align="center" width="200">' .$jornada->calendarioMesAtual(). '</td>';
echo '<td valign="top" align="center" width="20%">&nbsp;</td>';

echo '</tr></table>';
echo '</td></tr>';
echo '<tr><td colspan="5" align="center">&nbsp;</td></tr><tr><td colspan="5" align="center"><table class="minical" align="center"><tr><td style="border-style:solid;border-width:1px" class="expediente_normal">&nbsp;&nbsp;</td><td style="white-space: nowrap">Expediente normal</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_meio">&nbsp;&nbsp;</td><td style="white-space: nowrap">Meio Expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_sem">&nbsp;&nbsp;</td><td style="white-space: nowrap">Sem Expediente</td><td>&nbsp;</td><td style="border-style:solid;border-width:1px" class="expediente_outros">&nbsp;&nbsp;</td><td style="white-space: nowrap">Expediente alterntivo</td><td>&nbsp;</td><td class="hoje">&nbsp;&nbsp;</td><td style="white-space: nowrap">Hoje</td><td>&nbsp;</td></tr></table></td></tr>';
echo '</table>';
echo '</form>';
echo estiloFundoCaixa();
?>
<script type="text/javascript">

function limpar(){
	document.env.tarefa_id.value=null;
	document.env.projeto_id.value=null;
	document.env.usuario_id.value=null;
	document.env.recurso_id.value=null;
	document.env.jornada_id.value=null;
	}

function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&edicao=1&selecao=1&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&edicao=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
	
function setProjeto(chave, valor){
	limpar();
	document.env.projeto_id.value=(chave > 0 ? chave : null);
	document.env.nome_projeto_id.value=valor;
	document.env.submit();
	}	

function popTarefa(){
	var f = document.env;
	if (f.projeto_id.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.projeto_id.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.projeto_id.value, 'tarefa','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}
	

function setTarefa(chave, valor){
	limpar();
	document.env.tarefa_id.value = (chave > 0 ? chave : null);
	document.env.tarefa_nome.value = valor;
	document.env.submit();
	}	



function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&recurso_id='+document.getElementById('recurso_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&recurso_id='+document.getElementById('recurso_id').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}
	
function setRecurso(chave, valor){
	limpar();
	document.env.recurso_id.value=(chave > 0 ? chave : null);
	document.env.nome_recurso_id.value=valor;
	document.env.submit();
	}	

function popJornada() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Jornada', 1000, 700, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setJornada&tabela=jornada&jornada_id='+document.getElementById('jornada_id').value, window.setJornada, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setJornada&tabela=jornada&jornada_id='+document.getElementById('jornada_id').value, 'Calend?rio','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}
	
function setJornada(chave, valor){
	limpar();
	document.env.jornada_id.value=(chave > 0 ? chave : null);
	document.env.nome_jornada_id.value=valor;
	document.env.submit();
	}		
function editar_jornada(){
	if (env.cia_id.value<1 && env.usuario_id.value<1 && env.projeto_id.value<1 && env.recurso_id.value<1 && env.jornada_id.value<1) alert('Selecione um objeto para inserir ou alterar o expediente.'); 
	else {
		env.a.value='jornada_editar';
		env.submit();
		}

	}	
	
function mudar_om(){	
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"','&nbsp;'); 	
	}
	

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Usu?rio','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	limpar();
	document.getElementById('usuario_id').value=usuario_id;		
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	document.env.submit();
	}	

	
</script>	
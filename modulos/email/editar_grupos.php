<?php  
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');



$botoesTitulo = new CBlocoTitulo('Grupos', 'membro.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$grupo_id=(int)getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=(int)getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);
if (!$grupo_id && !$grupo_id2) {
	$grupo_id=($Aplic->usuario_prefs['grupoid'] ? $Aplic->usuario_prefs['grupoid'] : null);
	$grupo_id2=($Aplic->usuario_prefs['grupoid2'] ? $Aplic->usuario_prefs['grupoid2'] : null);
	}

$cia_id = $Aplic->usuario_cia;

$sql = new BDConsulta;
$sql->adTabela('grupo');
$sql->esqUnir('grupo_permissao','gp1','gp1.grupo_permissao_grupo = grupo.grupo_id');
$sql->esqUnir('grupo_permissao','gp2','gp2.grupo_permissao_grupo=grupo.grupo_id AND gp2.grupo_permissao_usuario = '.$Aplic->usuario_id);
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia');
$sql->adCampo('COUNT(gp1.grupo_permissao_usuario) AS protegido');
$sql->adCampo('COUNT(gp2.grupo_permissao_usuario) AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_descricao ASC');
$sql->adGrupo('grupo.grupo_id, grupo_descricao, grupo_cia');
$achados=$sql->Lista();
$sql->limpar();


$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence'])) $grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se h� grupo privado da cia, se houver n�o haver� op��o de ver todos o usu�rios da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) {
	$grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
	if (!$grupo_id && !$grupo_id2) $grupo_id=-1;
	}
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;


echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="editar_grupos">';
echo '<input type=hidden id="m" name="m" value="email">';	

echo '<input type="hidden" id="grupo_id" name="grupo_id" value="" />';


echo estiloTopoCaixa();
echo '<table align="center" class="std" width="100%" cellpadding=0 cellspacing=0>';

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width=100%><tr>';
echo '<td><table cellspacing=0 cellpadding=0 width=100%>';
echo '<tr><td align="right" width=100>'.dica('Nome', 'O nome do grupo.').'Nome:'.dicaF().'</td><td><input type="text" id="grupo_descricao" name="grupo_descricao" value="" style="width:400px;" class="texto" /></td></tr>';



echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td align=right width=100>'.dica('Exibi��o', 'Forma de apresentar os ').'Exibi��o:'.dicaF().'</td><td><input type="radio" name="modo_exibicao" value="dept" id="dept" onChange="mudar_usuarios_designados()" checked>'.ucfirst($config['departamento']).'<input type="radio" name="modo_exibicao" value="simples" id="simples" onChange="mudar_usuarios_designados();">Lista simples</td></tr>';
$sql->adTabela('grupo');
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia, (SELECT COUNT(grupo_permissao_usuario) FROM grupo_permissao AS gp1 WHERE gp1.grupo_permissao_grupo=grupo.grupo_id) AS protegido, (SELECT COUNT(grupo_permissao_usuario) FROM grupo_permissao AS gp2 WHERE gp2.grupo_permissao_grupo=grupo.grupo_id AND gp2.grupo_permissao_usuario='.(int)$Aplic->usuario_id.') AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
$achados=$sql->Lista();
$sql->limpar();
$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se h� grupo privado da cia, se houver n�o haver� op��o de ver todos o usu�rios da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) {
	$grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
	if (!$grupo_id && !$grupo_id2) $grupo_id=-1;
	}
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;

if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align=right width=100>'.dica(ucfirst($config['organizacao']), 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia_designados">'.selecionar_om($Aplic->usuario_cia, 'cia_designados', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td></tr>';

if (!$grupo_id && !$grupo_id2) {
	$grupo_id=($Aplic->usuario_prefs['grupoid'] ? $Aplic->usuario_prefs['grupoid'] : null);
	$grupo_id2=($Aplic->usuario_prefs['grupoid2'] ? $Aplic->usuario_prefs['grupoid2'] : null);
	}
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo','Escolha '.$config['usuarios'].' inclu�d'.$config['genero_usuario'].'s em um dos grupos.').'Grupo:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:400px" class="texto" onchange="env.grupo_b.value=0; mudar_usuarios_designados()"',$grupo_id).'</td></tr>';
else echo '<input type="hidden" name="grupo_a" id="grupo_a" value="" />';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.(int)$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'')+$grupos;
if (count($grupos)>1) echo '<tr><td align=right>'.dica('Grupo Particular','Escolha '.$config['usuarios'].' inclu�d'.$config['genero_usuario'].'s em um dos seus grupos particulares.').'Grupo Particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_b', 'style="width:400px" size="1" class="texto" onchange="env.grupo_a.value=0; mudar_usuarios_designados()"',$grupo_id2).'</td></tr>';
else echo '<input type="hidden" name="grupo_b" id="grupo_b" value="" />';
echo '<tr><td align=right width=100>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descri��o').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:400px;" name="busca" id="busca" onchange="env.grupo_a.value=0; env.grupo_b.value=0; mudar_usuarios_designados();" value=""/></td><td><a href="javascript:void(0);" onclick="env.busca.value=\'\'; mudar_usuarios_designados()">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste �cone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';
echo '</table></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste �cone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';

echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0>';
echo '<tr><td style="text-align:left" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Sele��o de '.ucfirst($config['usuarios']),'D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para adiciona-lo � lista de destinat�rio.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o INCLUIR.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;'.ucfirst($config['usuarios']).'&nbsp</legend>';
echo '<div id="combo_de">';
if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
else {
	echo '<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';

	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('grupo_usuario','grupo_usuario','grupo_usuario_usuario=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
		$sql->adOnde('usuario_ativo=1');
		if ($grupo_id2) $sql->adOnde('grupo_usuario_grupo='.$grupo_id2);
		elseif ($grupo_id > 0) $sql->adOnde('grupo_usuario_grupo='.$grupo_id);
		elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
		$sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor');
		$usuarios = $sql->Lista();
		$sql->limpar();
   	foreach ($usuarios as $rs)	 echo '<option value="'.$rs['usuario_id'].'">'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').'</option>';
    }
	echo '</select>';
	}
echo '</div></fieldset>';
echo '</td>';

echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Integrantes','D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para remove-lo dos integrantes.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o Remover.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Integrantes</b>&nbsp;</legend><div id="combo_para"><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;"></select></div></fieldset></td></tr>';
echo '<tr><td class=CampoJanela style="text-align:center"><table cellpadding=0 cellspacing=0><tr><td width="150">'.dica('Incluir','Clique neste bot�o para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionad'.$config['genero_usuario'].'s na lista de integrantes.').'<a class="botao" href="javascript:Mover(env.ListaDE, env.ListaPARA)"><span><b>incluir >></b></span></a></td><td>'.dica('Incluir Todos','Clique neste bot�o para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a class="botao" href="javascript:btSelecionarTodos_onclick()"><span><b>incluir todos</b></span></a>'.dicaF().'</td></tr></table></td><td style="text-align:center"><table cellpadding=0 cellspacing=0><tr><td>'.dica('Remover','Clique neste bot�o para remover '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionad'.$config['genero_usuario'].'s da caixa de integrantes.').'<a class="botao" href="javascript:Mover2(env.ListaPARA, env.ListaDE)"><span><b><<&nbsp;remover</b></span></a></td><td width=230>&nbsp;</td></tr></table></td></tr>';

echo '</table></td></tr>';




echo '</table></td>';

echo '<td id="adicionar_grupo" style="display:"><a href="javascript: void(0);" onclick="incluir_grupo();">'.imagem('icones/adicionar_g.png','Incluir','Clique neste �cone '.imagem('icones/adicionar.png').' para incluir o grupo.').'</a></td>';
echo '<td id="confirmar_grupo" style="display:none"><a href="javascript: void(0);" onclick="document.getElementById(\'grupo_id\').value=0;document.getElementById(\'grupo_descricao\').value=\'\'; document.getElementById(\'adicionar_grupo\').style.display=\'\';	document.getElementById(\'confirmar_grupo\').style.display=\'none\';">'.imagem('icones/cancelar_g.png','Cancelar','Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar a edi��o do grupo .').'</a><a href="javascript: void(0);" onclick="incluir_grupo();">'.imagem('icones/ok_g.png','Confirmar','Clique neste �cone '.imagem('icones/ok.png').' para confirmar a edi��o do grupo.').'</a></td>';

echo '</tr></table></td></tr>';





echo '<tr><td colspan=20 align=left>&nbsp;<td></tr>';

$sql->adTabela('grupo');
$sql->adOnde('grupo_usuario = '.(int)$Aplic->usuario_id);
$sql->adCampo('grupo.*');
$sql->adOrdem('grupo_ordem');
$grupos_cadastrados=$sql->ListaChave('grupo_id');
$sql->limpar();

echo '<tr><td colspan=20 align=left><div id="grupos">';
if (count($grupos_cadastrados)) {
	echo '<table cellpadding=0 cellspacing=0 class="tbl1" align=left width=250><tr><th></th><th>Nome</th><th></th></tr>';
	foreach ($grupos_cadastrados as $grupo_id => $linha) {
		echo '<tr align="center">';
		echo '<td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td align="left">'.$linha['grupo_descricao'].'</td>';
		echo '<td width=32><a href="javascript: void(0);" onclick="editar_grupo('.$linha['grupo_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o grupo.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esto grupo?\')) {excluir_grupo('.$linha['grupo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir esto grupo.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}
echo '</div></td></tr>';	 












echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td align="right">'.botao('retornar', 'Retornar', 'Retornar a tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();

echo '</form>';


?>

<script LANGUAGE="javascript">


function mudar_posicao_grupo(grupo_ordem, grupo_id, direcao){
	xajax_mudar_posicao_grupo_ajax(grupo_ordem, grupo_id, direcao, <?php echo (int)$Aplic->usuario_id ?>); 	
	}	

function editar_grupo(grupo_id){
	xajax_editar_grupo(grupo_id);
	document.getElementById('adicionar_grupo').style.display="none";
	document.getElementById('confirmar_grupo').style.display="";
	
	}
	
function incluir_grupo(){
	if (env.ListaPARA.length==0) {
			alert("Selecione ao menos um destinat�rio!");
			return 0;
			}
	if (document.getElementById('grupo_descricao').value!=''){
	var qnt=0;
	var usuarios='';
	for (var i=0; i < env.ListaPARA.length ; i++) {
		usuarios=usuarios+(qnt++ ? ',' : '')+env.ListaPARA.options[i].value;
		}
		
		xajax_incluir_grupo_ajax(<?php echo (int)$Aplic->usuario_id ?>, document.getElementById('grupo_id').value, document.getElementById('grupo_descricao').value, usuarios);
		document.getElementById('grupo_id').value=null;
		document.getElementById('grupo_descricao').value='';
		document.getElementById('adicionar_grupo').style.display='';	
		document.getElementById('confirmar_grupo').style.display='none';

		
		}
	else alert('Escolha um grupo_descricao para o grupo.');	
	}	
	
function excluir_grupo(grupo_id){
	xajax_excluir_grupo_ajax(grupo_id, <?php echo (int)$Aplic->usuario_id ?>);
	}
	



function mudar_om_designados(){
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1);
	}


function mudar_usuarios_designados(){
	var tipo=document.env.modo_exibicao.value;
	grupo=document.getElementById('grupo_b').value;
	if (!grupo|| grupo==0) grupo=document.getElementById('grupo_a').value;
	if (grupo==-1) grupo=null;
	if (tipo=='dept')	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'ListaDE', 'combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"', document.getElementById('busca').value, grupo);
	else xajax_mudar_usuario_grupo_ajax(grupo, document.getElementById('busca').value);
	}
	
function Mover(ListaDE,ListaPARA) {
	//checar se j� existe
	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value > 0) {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text.replace(/(^[\s]+|[\s]+$)/g, '');
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) {
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				ListaPARA.options[ListaPARA.options.length] = no;
				}
			}
		}
	}

function Mover2(ListaPARA,ListaDE) {
	var oculto;
	var aviso;
	var externo;
	var tarefa=0;

	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value > 0) {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}

// Seleciona todos os campos da lista de destinat�rios e efetua o submit
function selecionar() {
	if (env.ListaPARA.length== 0 && env.outros_emails.value.length==0) {
		alert("Selecione ao menos um destinat�rio!");
		return 0;
		}
	for (var i=0; i < env.ListaPARA.length ; i++) {
		env.ListaPARA.options[i].selected = true;
		}
	return 1;
	}
	
// Seleciona todos os campos da lista de usu�rios
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
		}
	Mover(env.ListaDE, env.ListaPARA);
	}

</script>	


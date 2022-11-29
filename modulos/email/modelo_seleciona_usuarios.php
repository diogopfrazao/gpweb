<?php  
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario=getParam($_REQUEST, 'vetor_modelo_msg_usuario', null); 
else if (isset($_REQUEST['modelo_usuario_id']) && $_REQUEST['modelo_usuario_id']) $vetor_modelo_msg_usuario[]=getParam($_REQUEST, 'modelo_usuario_id', null);

$recebido_enviado=(isset($vetor_modelo_msg_usuario) && count($vetor_modelo_msg_usuario));

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID=getParam($_REQUEST, 'modeloID', null); 
	else if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']) $modeloID[]=getParam($_REQUEST, 'modelo_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}


$destino=getParam($_REQUEST, 'destino_cabecalho', getParam($_REQUEST, 'destino', ''));
$grupo_id=getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);
/*tipo: 0 = anotacao; 1 = despacho; 2=resposta*/ 
$tipo=getParam($_REQUEST, 'tipo', 0);
$status=getParam($_REQUEST, 'status', 0);
$ListaPARA=getParam($_REQUEST, 'ListaPARA', array());
$ListaPARAoculto=getParam($_REQUEST, 'ListaPARAoculto', array());
$ListaPARAaviso=getParam($_REQUEST, 'ListaPARAaviso', array());
$ListaPARAexterno=getParam($_REQUEST, 'ListaPARAexterno', array());
$outros_emails=getParam($_REQUEST, 'outros_emails','');
$msg_id_cripto=getParam($_REQUEST, 'msg_id_cripto', 0);
$tem_cripto=getParam($_REQUEST, 'cripto', 0);
$retornar=getParam($_REQUEST, 'retornar', 'lista_msg');

$sql = new BDConsulta;
$legendas=array('modelo_grava_encaminha'=>'Encaminhamento','modelo_envia_msg'=>'', 'modelo_envia_email'=>'Encaminhamento por e-mail', 'modelo_envia_anot'=>'Despacho');


if (!$grupo_id && !$grupo_id2) {
	$grupo_id=($Aplic->usuario_prefs['grupoid'] ? $Aplic->usuario_prefs['grupoid'] : null);
	$grupo_id2=($Aplic->usuario_prefs['grupoid2'] ? $Aplic->usuario_prefs['grupoid2'] : null);
	}

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="a" name="a" value="'.$destino.'">';
echo '<input type=hidden id="m" name="m" value="email">';	
echo '<input type=hidden id="destino" name="destino" value="'.$destino.'">';		
echo '<input type=hidden id="status" name="status" value="'.$status.'">';	
echo '<input type=hidden id="status_original" name="status_original" value="'.$status.'">';	
echo '<input type=hidden id="tipo" name="tipo" value="'.$tipo.'">';	
echo '<input type=hidden id="grupo_id" name="grupo_id" value="">';		
echo '<input type=hidden id="grupo_id2" name="grupo_id2" value="">';		
echo '<input type=hidden id="arquivar" name="arquivar" value="">';		

echo '<input type=hidden id="modelo_usuario_id" name="modelo_usuario_id" value="'.getParam($_REQUEST, 'modelo_usuario_id', 0).'">';	
echo '<input type=hidden id="modelo_id" name="modelo_id" value="'.getParam($_REQUEST, 'modelo_id', 0).'">';	

if (isset($vetor_modelo_msg_usuario)) foreach ($vetor_modelo_msg_usuario as $chave => $valor) echo '<input type=hidden id="vetor_modelo_msg_usuario" name=vetor_modelo_msg_usuario[] value="'.$valor.'">';
else foreach ($modeloID as $chave => $valor) echo '<input type=hidden id="modeloID" name=modeloID[] value="'.$valor.'">';

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
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se h� grupo privado da cia, se houver n�o haver� op��o de ver todos o usu�rios da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) {
	$grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
	if (!$grupo_id && !$grupo_id2) $grupo_id=-1;
	}
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;



$botoesTitulo = new CBlocoTitulo('Selecionar Destinat�rios', 'documento.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();




echo estiloTopoCaixa();
echo '<table align="center" class="std" width=100% cellspacing=0 cellpadding=1>';
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
if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $Aplic->usuario_cia, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
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

	echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Destinat�rios','D� um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de sele��o para remove-lo dos destinat�rios.<BR><BR>Outra op��o � selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no bot�o Remover.<BR><BR>Para selecionar m�ltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'<b>Destinat�rios</b>&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;">';
	foreach($ListaPARA as $chave => $valor){ 	
		echo "<option value=".$valor.">";
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuario_id, cia_nome');
		$sql->adOnde('usuario_id='.$valor);	
		$rs =	$sql->Linha();
		$sql->limpar();
		echo nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '');
		if (in_array($valor, $ListaPARAoculto, true)) echo ' - oculo';
		if (in_array($valor, $ListaPARAaviso, true)) echo ' - aviso';
		if (in_array($valor, $ListaPARAaviso, true)) echo ' - externo';
		echo '</option>';
		}
	echo '</select></fieldset></td></tr>';
echo '<tr><td style="text-align:center">';
echo '<select name="ListaPARAoculto[]" multiple id="ListaPARAoculto" size=4 style="width:100%; display:none">';
foreach($ListaPARAoculto as $chave => $valor) echo "<option value=".$valor."></option>";
echo '</select>';

echo '<select name="ListaPARAaviso[]" multiple id="ListaPARAaviso" size=4 style="width:100%; display:none">';
foreach($ListaPARAaviso as $chave => $valor  ) echo "<option value=".$valor."></option>";
echo '</select>';

echo '<select name="ListaPARAexterno[]" multiple id="ListaPARAexterno" size=4 style="width:100%; display:none">';
foreach($ListaPARAexterno as $chave => $valor  ) echo "<option value=".$valor."></option>";
echo '</select>';

echo '</td></tr>';

echo '<tr><td class=CampoJanela style="text-align:center"><table cellspacing=0 cellpadding=0><tr><td width="150">'.dica('Incluir','Clique neste bot�o para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinat�rios.').'<a class="botao" href="javascript:Mover(env.ListaDE, env.ListaPARA)"><span><b>incluir >></b></span></a></td><td>'.dica('Incluir Todos','Clique neste bot�o para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.').'<a class="botao" href="javascript:btSelecionarTodos_onclick()"><span><b>incluir todos</b></span></a>'.dicaF().'</td></tr></table></td><td style="text-align:center"><table cellspacing=0 cellpadding=0><tr><td>'.dica("REMOVER","Clique neste bot�o para remover os destinat�rios selecionados da caixa de destinat�rios.").'<a class="botao" href="javascript:Mover2(env.ListaPARA, env.ListaDE)"><span><b><< Remover</b></span></a></td></tr></table></td></tr>';

echo '<tr><td colspan=2 '.($destino=='envia_email' ? 'style="display:none"' :'').' ><b>&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Aviso de Recebimento','Selecione esta caixa caso deseje receber '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' de notifica��o assim que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados lerem '.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Aviso de Leitura'.dicaF().'<input type="checkbox" name="aviso" id="aviso" value="1">'.($Aplic->usuario_pode_oculta ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Destinat�rio Oculto','Selecione esta caixa caso deseje que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados n�o apare�am na lista de encaminhados dos outros destinat�rios d'.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Destinat�rio Oculto'.dicaF().' <input type="checkbox" name="oculto" id="oculto" value="1" >' : '<input type="checkbox" name="oculto" id="oculto" value="0" style="display:none">').'</b>'.($config['email_ativo'] ? '<b>&nbsp;&nbsp;&nbsp;&nbsp;'.dica('E-Mail Externo','Selecione esta caixa caso deseje que '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados que tenham e-mails externos cadastrados recebam uma c�pia d'.$config['genero_mensagem'].' '.$config['mensagem'].' em suas contas de e-mail.').'E-mail externo'.dicaF().'<input type="checkbox" name="externo" id="externo" value="1">' : '<input type="hidden" name="externo" id="externo" value="0">').'</td></tr>';
echo '<tr><td colspan=2>'.dica('E-mail de Outros Destinat�rios', 'Insira os e-mails de outros destinat�rios que n�o constam nas listas dos grupos acima.<br>Separe os e-mails por ponto-v�rgula<br>ex: reinert@hotmail.com;sergio@oi.com.br').'<b>&nbsp;Outros destinat�rios:</b>&nbsp;'.dicaF().'<input type="text" name="outros_emails" value="'.$outros_emails.'" size="116" maxlength="255" class="texto" /></td></tr>';
if ((isset($vetor_modelo_msg_usuario) && count($vetor_modelo_msg_usuario))||(isset($modeloID) && count($modeloID))) echo '<tr><td colspan=2 align="center">'.(isset($legendas[$destino]) ? $legendas[$destino].' para ' : '').relacao_documentos().'</td></tr><tr><td colspan=2 align="center">&nbsp</td></tr>';







echo '<tr><td colspan=20><table width=100% cellspacing=0 cellpadding=0><tr>';


$titulo='';
if ($destino=='modelo_grava_encaminha') echo '<td width=99%>'.dica('Encaminhar','Clique neste bot�o para encaminhar.').'<a class="botao" href="javascript:btRemeter()"><span><b>encaminhar</b></span></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td><td>'.($recebido_enviado ? dica('Encaminhar e Arquivar','Clique nesta op��o para encaminhar e arquivar.<BR><BR>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' ser� enviad'.$config['genero_mensagem'].' para a caixa d'.$config['genero_mensagem'].'s arquivad'.$config['genero_mensagem'].'s').'<a class="botao" href="javascript:btRemeter_arquivar()"><span><b>enc. e arquivar</b></span></a>'.dicaF() : '').'</td></tr><tr><td>&nbsp;</td><td>'.($recebido_enviado ? dica('Encaminhar e Pender','Clique nesta op��o para encaminhar e pender.<BR><BR>'.ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' ser� enviad'.$config['genero_mensagem'].' para a caixa d'.$config['genero_mensagem'].'s pendentes').'<a class="botao" href="javascript:btRemeter_pender()"><span><b>enc. e pender</b></span></a>'.dicaF() : '').'</td>';				
else if ($destino=='modelo_envia_email')	echo '<td width=99%>'.dica('Encaminhar por e-mail','Clique neste bot�o para encaminhar por e-mail.').'<a class="botao" href="javascript:btRemeter()"><span><b>encaminhar por e-mail</b></span></a>'.dicaF().'</td>';
else if ($destino=='modelo_envia_msg')	echo '<td width=99%>'.dica('Avan�ar','Clique neste bot�o para escrever '.$config['genero_mensagem'].' '.$config['mensagem'].'.').'<a class="botao" href="javascript:btRemeter()"><span><b>avan�ar</b></span></a>'.dicaF().'</td>';
else echo '<td width=99%>'.dica('Despacho','Clique nesta bot�o para escrever o despacho.').'<a class="botao" href="javascript:btRemeter()"><span><b>despacho</b></span></a></td>';


echo '<td align="right">'.dica('Voltar','Clique neste bot�o para voltar � tela anterior.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.a.value=\''.$retornar.'\'; env.submit();"><span><b>voltar</b></span></a></td>';

echo '</tr></table></td></tr>';


echo '</table></td></tr>';


echo '</table>';




echo estiloFundoCaixa();
echo '</form></body></html>';
?>
<script LANGUAGE="javascript">
	

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
		if (ListaDE.options[i].selected && ListaDE.options[i].value  > 0) {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text;
			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) { 
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {
				if (env.oculto.checked) {
					var no2 = new Option();
					no2.value = ListaDE.options[i].value;
					no2.text = ListaDE.options[i].text;
					env.ListaPARAoculto.options[env.ListaPARAoculto.options.length] = no2;
					no.text = no.text+' - oculto';
					}		
				if (env.aviso.checked) {
					var no3 = new Option();
					no3.value = ListaDE.options[i].value;
					no3.text = ListaDE.options[i].text;
					env.ListaPARAaviso.options[env.ListaPARAaviso.options.length] = no3;
					no.text = no.text+' - aviso';
					}	
				if (env.externo.checked) {
					var no4 = new Option();
					no4.value = ListaDE.options[i].value;
					no4.text = ListaDE.options[i].text;
					env.ListaPARAexterno.options[env.ListaPARAexterno.options.length] = no4;
					no.text = no.text+' - externo';
					}			
				ListaPARA.options[ListaPARA.options.length] = no;		
				//ListaDE.options[i].value = "";
				//ListaDE.options[i].text = "";
				}
			}
		}
	//LimpaVazios(ListaDE, ListaDE.options.length);
	}

function Mover2(ListaPARA,ListaDE) {
	var oculto;
	var aviso;
	var externo;
	
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value != "-1") {
			
			var existe=0;
			for(var j=0; j < ListaDE.options.length; j++) { 
				if (ListaDE.options[j].value==ListaPARA.options[i].value) {
					existe=1;
					break;
					}
				}
			oculto=0;
			aviso=0;
			externo=0;
			for(var j=0; j < env.ListaPARAoculto.options.length; j++){	
				if (env.ListaPARAoculto.options[j].value == env.ListaPARA.options[i].value) {
					oculto=1;
					break;
					}
				}							
			for(var k=0; k < env.ListaPARAaviso.options.length; k++){	
				if (env.ListaPARAaviso.options[k].value == env.ListaPARA.options[i].value) {
					aviso=1;
					break;
					}
				}
			for(var e=0; e < env.ListaPARAexterno.options.length; e++){	
				if (env.ListaPARAexterno.options[e].value == env.ListaPARA.options[i].value) {
					externo=1;
					break;
					}
				}	
			var no = new Option();	
			if (oculto==1){
					env.ListaPARAoculto.options[j].value = "";
					env.ListaPARAoculto.options[j].text = "";	
					}
			if (aviso==1){
				env.ListaPARAaviso.options[k].value = "";
				env.ListaPARAaviso.options[k].text = "";					
				}		
			if (externo==1){
				env.ListaPARAexterno.options[e].value = "";
				env.ListaPARAexterno.options[e].text = "";					
				}		
			no.value = ListaPARA.options[i].value
			no.text = ListaPARA.options[i].text
			//if (!existe) ListaDE.options[ListaDE.options.length] = no;
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""	
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	LimpaVazios(env.ListaPARAoculto, env.ListaPARAoculto.options.length);
  LimpaVazios(env.ListaPARAaviso, env.ListaPARAaviso.options.length);
  LimpaVazios(env.ListaPARAexterno, env.ListaPARAexterno.options.length);
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
	if (env.ListaPARA.length <= 0) { alert("Selecione ao menos um destinat�rio!");exit; }
	
	for (var i=0; i < env.ListaPARA.length ; i++) {
		env.ListaPARA.options[i].selected = true;
		}
	for (var i=0; i < env.ListaPARAoculto.length ; i++) {
		env.ListaPARAoculto.options[i].selected = true;
	  }
	for (var i=0; i < env.ListaPARAaviso.length ; i++) {
		env.ListaPARAaviso.options[i].selected = true;
		}
	for (var i=0; i < env.ListaPARAexterno.length ; i++) {
		env.ListaPARAexterno.options[i].selected = true;
		}	
	}

function btRemeter() {
	selecionar();
	env.submit();
	}

function btRemeter_arquivar() {
	selecionar();
	env.status.value=4;
	env.arquivar.value=1;
	env.submit();
	}

function btRemeter_pender() {
	selecionar();
	env.status.value=3;
	env.arquivar.value=2;
	env.submit();
	}



// Seleciona todos os campos da lista de usu�rios
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
	}
	Mover(env.ListaDE, env.ListaPARA);
}

function campos(){
	for (var i=0; i < env.ListaPARA.length ; i++) env.ListaPARA.options[i].selected = true;
	for (var i=0; i < env.ListaPARAoculto.length ; i++) env.ListaPARAoculto.options[i].selected = true;
	for (var i=0; i < env.ListaPARAaviso.length ; i++) env.ListaPARAaviso.options[i].selected = true;
	for (var i=0; i < env.ListaPARAexterno.length ; i++) env.ListaPARAexterno.options[i].selected = true;
	env.a.value = "editar_grupos";	
	env.submit();
}
</script>
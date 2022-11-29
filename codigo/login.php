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



if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente');
$celular=getParam($_REQUEST, 'celular', 0);
global $usuario_externo_endereco;

echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<meta http-equiv="Content-Type" content="text/html;charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'" />';

echo '<meta http-equiv="Pragma" content="no-cache" />';
echo '<meta name="Version" content="'.$Aplic->getVersao().'" />';
echo '<link rel="stylesheet" type="text/css" href="./estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "./estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="./estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="'.str_replace('/codigo', "", BASE_URL).'/lib/jquery/jquery-1.8.3.min.js"></script>';
echo '<script type="text/javascript" src="'.str_replace('/codigo', "", BASE_URL).'/lib/mootools/mootools.js"></script>';
echo '<script type="text/javascript" src="'.str_replace('/codigo', "", BASE_URL).'/js/gpweb.js?dc=3"></script>';
echo '</head>';
echo '<body onload="document.frmlogin.usuarioNome.focus();" '.(isset($config['papel_parede_login']) && $config['papel_parede_login'] ? 'background="'.$config['papel_parede_login'].'"' : '').'>';
echo '<script>$jq = jQuery.noConflict();</script>';

if (!$celular) {
	echo '<br><center>'.dica('Site do '.$config['gpweb'], 'Clique para entrar no site oficial do '.$config['gpweb'].'.').'<a href="'.((isset($config['endereco_site']) && $config['endereco_site']) ? $config['endereco_site'] : get_protocol().'www.sistemagpweb.com.br').'" target="_blank"><img src="'.$Aplic->gpweb_logo.'" border=0 /></a>'.dicaF().'<center>';
 	echo '<br><br>';
 	}
else echo '<table width="300" cellspacing=0 cellpadding=0 align=center><tr><td></td></tr><tr><td><hr noshade size=5 style="color: #a6a6a6"></td></tr><td align=center style="font-size:35pt; padding-left: 5px; padding-right: 5px;color: #009900"><i><b>gp</b>web</td></i></tr><tr><td><hr noshade size=5 style="color: #a6a6a6"></td></tr><tr><td>&nbsp;</td></tr></table>';




include ('./estilo/rondon/sobrecarga.php');
echo '<form method="post" action="index.php" name="frmlogin" autocomplete="off">';
echo '<input type="hidden" name="login" value="'.time().'" />';
echo '<input type="hidden" name="login" value="entrar" />';
echo '<input type="hidden" name="celular" value="'.$celular.'" />';
echo '<input type="hidden" name="usuario_externo_endereco" value="'.$usuario_externo_endereco.'" />';
echo '<input type="hidden" name="gpweb_url_protocol" value="" />';
echo '<input type="hidden" name="full_url" value="" />';

$expirado=false;
if (isset($config['data_limite']) && $config['data_limite']){
	$hoje_unix = mktime (0, 0, 0, date("m"), date("d"),  date("Y"));
	$campos_data=explode('/', $config['data_limite']);
	$limite_unix = mktime (0, 0, 0, $campos_data[1], $campos_data[0],  $campos_data[2]);
	$expirado=($hoje_unix > $limite_unix);
	}


if (!$expirado){
	echo '<table align="center" border=0 width="250" cellpadding=0 cellspacing=0 style="background: #f2f0ec">';
	if (!$celular) echo '<tr><td colspan="2">'.estiloTopoCaixa().'</td></tr>';
	else echo '<tr><td colspan=2 width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr>';
	echo '<tr><th colspan="2">&nbsp;</th></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica(ucfirst($config['login']), 'Escreva o '.$config['login'].' com o qual acessa o '.$config['gpweb'].' , com no m�nimo '.config('tam_min_login').' caracteres.').'&nbsp;'.ucfirst($config['login']).':&nbsp;'.dicaF().'</td><td align="left" style="white-space: nowrap"><input type="text" size="25" maxlength="255" name="usuarioNome" class="texto" /></td></tr>';
	echo '<tr><td align="right" style="white-space: nowrap">'.dica('Senha', 'Escreva a senha com a qual acessa o '.$config['gpweb'].', com no m�nimo '.config('tam_min_senha').' caracteres.').'&nbsp;Senha:&nbsp;'.dicaF().'</td><td align="left" style="white-space: nowrap"><input type="password" size="25" maxlength="32" name="senha" class="texto" onkeypress="return submitenter(this, event)" onunfocus="document.frmlogin.submit();" onblur="document.frmlogin.submit();" />&nbsp;</td></tr>';
	echo '<tr><td colspan="2" align="center" style="background: #f2f0ec;">'.botao('entrar', 'Entrar','pressione este bot�o para acessar o '.$config['gpweb'].', ap�s inserir o nome e a senha cadastrados.','','frmlogin.submit()').'&nbsp;</td></tr>';

	if ($config['ativar_criacao_externa_usuario']) echo '<tr><td style="padding:2px" colspan="2" align="center" style="white-space: nowrap">'.dica('Criar Conta','Clique neste link caso n�o fa�a parte ainda d'.$config['genero_usuario'].'s '.$config['usuarios'].' registrados no '.$config['gpweb'].'.<br><br>Lembre-se de que o acesso depender� da aprova��o do cadastro.').'<a href="javascript: void(0);" onclick="javascript:window.location=\'./codigo/novo_usuario.php'.($celular ? '?celular=1' :'').'\'">Criar uma conta</a></td></tr>';
	
	if ($config['esqueceu_senha'] && $Aplic->profissional && $config['email_ativo']) echo '<tr><td style="padding:2px" colspan="2" align="center" style="white-space: nowrap">'.dica('Esqueceu a Senha','Clique neste link caso n�o lembre da senha ou do login d'.$config['genero_usuario'].' '.$config['usuarios'].' registrado no '.$config['gpweb'].'.').'<a href="javascript: void(0);" onclick="javascript:window.location=\'./codigo/perdeu_senha_pro.php'.($celular ? '?celular=1' :'').'\'">Esqueceu a senha?</a></td></tr>';
	
	
	
	
	if (!$celular) echo '<tr><td colspan="2">'.estiloFundoCaixa().'</td></tr>';
	else echo '<tr><td colspan=2 width="100%" style="background-color: #a6a6a6">&nbsp;</td></tr>';
	echo '</table>';
	if ($Aplic->getVersao()) echo '<div align="center"><span style="font-size:6pt">Vers�o '.($Aplic->profissional ? 'Pro ' : '').$Aplic->getVersao().'</span></div>';
	if (isset($config['exemplo']) && $config['exemplo']){
		echo '<div align="center"><h1>Demonstra��o</h1></div>';
		echo '<div align="center"><table cellspacing=0 cellpadding="3" class="tbl1">';


		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_login');
		$sql->adOrdem('usuario_id');
		$sql->adOnde('usuario_senha=\'e10adc3949ba59abbe56e057f20f883e\'');
		if (isset($config['treino'])) $sql->adOnde('usuario_login LIKE \'%aluno%\'');
		$usuarios = $sql->carregarColuna();
		$sql->limpar();


	  echo '<tr><td colspan=5 align=center><b>Login</b></td></tr>';

	  $col=0;
	  foreach($usuarios as $usuario) {
	  	$col++;
	  	if ($col==1) echo '<tr>';

	  	echo '<td align=center>'.$usuario.'</td>';


	  	if ($col==5) {
	  		echo '</tr>';
	  		 $col=0;
	  		}
	  	}

    if ($col==4) echo '<td>&nbsp;</td></tr>';
	  elseif ($col==3) echo '<td colspan=2>&nbsp;</td></tr>';
	  elseif ($col==2) echo '<td colspan=3>&nbsp;</td></tr>';
	  elseif ($col==1) echo '<td colspan=4>&nbsp;</td></tr>';
	  echo '<tr><td colspan=5 align=center><b>Senha: 123456</b></td></tr>';

	  echo '</table></div>';
		}

	if (isset($config['data_limite']) && $config['data_limite']){
		echo '<h2>Limite do demonstrativo: '.$config['data_limite'].'</h2>';
		}


	}
else {
	echo '<table width=100% cellpadding=0 cellspacing=0>';
	echo '<tr><td align=center><h1>O prazo de uso deste demonstrativo expirou em '.$config['data_limite'].'.</h1></td></tr>';
	echo '<tr><td align=center><h1>&nbsp;</h1></td></tr>';
	echo '<tr><td align=center><h2>Contate a Sistema GP-Web Ltda. atrav�s dos telefones: 0800 606 6003 e (51)3026-7509.</h2></td></tr>';
	echo '</table>';
	}

echo '</form>';
echo '<div align="center">';
echo '<span class="error">'.$Aplic->getMsg().'</span>';

$msg = phpversion() < '5.3' ? '<br /><span class="warning">AVISO:O software n�o � suportado por esta vers�o do PHP('.phpversion().')</span>' : '';
//$msg .= (!extension_loaded('mysqli') && !extension_loaded('mysql') ) ? '' : '<br /><span class="warning">AVISO: PHP poder� n�o rodar com suporte ao MySQL. Verifique as configura��o do arquivo config.php.</span>';
echo $msg;
$Aplic->carregarRodapeJS();
echo '</div></body></html>';
?>
<SCRIPT TYPE="text/javascript">
if(window.parent && window.parent.gpwebApp){
	var gpwebApp = parent.gpwebApp;
	gpwebApp.onLogout();
    frmlogin.gpweb_url_protocol.value = window.parent.location.protocol;
    frmlogin.full_url.value = window.parent.getAbsolutePath()+'server';
}
else if(window.parent){
    frmlogin.gpweb_url_protocol.value = window.parent.location.protocol;
    frmlogin.full_url.value = getAbsolutePathFree();
}

function submitenter(campo,e){
	var codigo;
	if (window.event) codigo = window.event.keyCode;
	else if (e) codigo = e.which;
	else return true;

	if (codigo == 13) {
	   campo.form.submit();
	   return false;
	   }
	else return true;
	}

function getAbsolutePathFree() {
    var loc = window.parent.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}

</SCRIPT>


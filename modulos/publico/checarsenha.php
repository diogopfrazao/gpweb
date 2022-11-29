<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (!($usuario_id=getParam($_REQUEST, 'usuario_id', 0))) $usuario_id = $Aplic->usuario_id;
global $config;

$podeEditar=($usuario_id == $Aplic->usuario_id || $Aplic->usuario_admin || $Aplic->usuario_super_admin);
if (!$podeEditar)	$Aplic->redirecionar('m=publico&a=acesso_negado');


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';

$sql = new BDConsulta;

if ($usuario_id && $podeEditar) {
	//$senhaAntiga = db_escape(trim(getParam($_REQUEST, 'senhaAntiga', null)));
	$senhaNova1 = db_escape(trim(getParam($_REQUEST, 'senhaNova1', null)));
	$senhaNova2 = db_escape(trim(getParam($_REQUEST, 'senhaNova2', null)));

	if ($senhaNova1 && $senhaNova2 && ($senhaNova1 == $senhaNova2)) {
		//$antigo_md5 = md5($senhaAntiga);


		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_id, usuario_login');
		//if (!$Aplic->usuario_admin) $sql->adOnde('usuario_senha = \''.$antigo_md5.'\'');
		$sql->adOnde('usuario_id = '.(int)$usuario_id);
		$resultado=$sql->Linha();
		$sql->limpar();
		if ($Aplic->usuario_admin || $resultado['usuario_id'] == $usuario_id) {
			require_once ($Aplic->getClasseModulo('admin'));
			$sql->adTabela('usuarios');
			$sql->adAtualizar('usuario_senha', md5($senhaNova1));
			$sql->adOnde('usuario_id = '.$usuario_id);
			if (!$sql->exec()) die('N�o foi poss�vel alterar a senha.');
			$sql->limpar();
			echo '<h1>Mudar Senha de '.nome_usuario($usuario_id).'</h1>';
			echo estiloTopoCaixa();
			echo '<table width="100%" cellspacing=0 cellpadding=0 class="std"><tr><td>A sua senha foi alterada</td></tr></table>';
			
			if ($Aplic->profissional){

				$sql->adTabela('usuarios');
				$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id=usuarios.usuario_contato');
				$sql->adCampo('contato_email');
				$sql->adOnde('usuario_id = '.(int)$usuario_id);
				$email=$sql->resultado();
				$sql->limpar();
				
				

				if($config['email_ativo'] && $email) msg_email_externo($email, 'Senha Alterada', 'Sua senha foi alterada em '.date('d/m/Y H:i:s').' por '.$Aplic->usuario_nome);
				msg_email_interno('' , 'Senha Alterada', 'Sua senha foi alterada em '.date('d/m/Y H:i:s').' por '.$Aplic->usuario_nome, '', $usuario_id);
				
				}
			
			}
		else {
			echo '<h1>Mudar Senha de '.nome_usuario($usuario_id).'</h1>';
			echo estiloTopoCaixa();
			echo '<table width="100%" cellspacing=0 cellpadding=0 class="std"><tr><td>A sua senha n�o est� correta</td></tr></table>';
			}
		}
	else {
		echo '<h1>Mudar Senha de '.nome_usuario($usuario_id).'</h1>';
		echo estiloTopoCaixa();
		
		echo '<table width="100%" cellspacing=0 cellpadding=0 class="std">';
		//if (!$Aplic->usuario_admin) echo '<tr><td align="right" style="white-space: nowrap">Senha Atual</td><td><input type="password" name="senhaAntiga" class="texto" /></td></tr>';
		echo '<tr><td align="right" style="white-space: nowrap">Nova Senha:</td><td><input type="password" name="senhaNova1" class="texto" size="25" /></td></tr>';
		echo '<tr><td align="right" style="white-space: nowrap">Repita Nova Senha:</td><td><input type="password" name="senhaNova2" class="texto" size="25" onkeypress="return submitenter(this, event)" /></td></tr>';
		echo '<tr><td>&nbsp;</td><td align="right" style="white-space: nowrap">'.botao('confirmar', '', '','','enviarDados()').'</td></tr>';
		echo '</table>';
		}
	}
else {
	echo '<h1>Mudar Senha de '.nome_usuario($usuario_id).'</h1>';
	echo estiloTopoCaixa();
	echo '<table width="100%" cellspacing=0 cellpadding=0 class="std"><tr><td>'.ucfirst($config['usuario']).' n�o existe</td></tr></table>';
	}
echo estiloFundoCaixa();

echo '<form>';
?>
<script type="text/javascript">
function enviarDados() {
	var f = document.env;
	var msg = '';
	if (f.senhaNova1.value.length < 3) {
    msg += "Por favor insira uma nova senha com ao menos 3 caracteres ";
		f.senhaNova1.focus();
		}
	if (f.senhaNova1.value != f.senhaNova2.value) {
		msg += "\nSenha diferente nos dois campos";
		f.senhaNova2.focus();
		}
	if (msg.length < 1)	f.submit();
	else alert(msg);
	}


function submitenter(campo,e){
	var codigo;
	if (window.event) codigo = window.event.keyCode;
	else if (e) codigo = e.which;
	else return true;

	if (codigo == 13) {
	   enviarDados();
	   return false;
	   }
	else return true;
	}

</script>

<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
$sql = new BDConsulta;
$sql->adTabela('social_comite_usuarios');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=social_comite_usuarios.usuario_id');
$sql->esqUnir('contatos','contatos','usuario_contato=contatos.contato_id');
$sql->adCampo('social_comite_id, contatos.contato_id');
$lista=$sql->Lista();
$sql->limpar();

foreach ($lista as $linha){
	$sql->adTabela('social_comite_membros');
	$sql->adInserir('social_comite_id', $linha['social_comite_id']);
	$sql->adInserir('contato_id', $linha['contato_id']);
	$sql->exec();
	$sql->limpar();
	}


?>

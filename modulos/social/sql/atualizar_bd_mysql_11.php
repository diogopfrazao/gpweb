<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
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

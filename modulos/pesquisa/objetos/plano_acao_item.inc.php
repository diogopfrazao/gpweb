<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class plano_acao_item extends pesquisa {
	public $tabela = 'plano_acao_item';
	public $tabela_modulo = 'plano_acao_item';
	public $tabela_chave = 'plano_acao_item_id';
	public $tabela_link = 'index.php?m=praticas&a=plano_acao_item_ver&plano_acao_item_id=';
	public $tabela_titulo = 'A��es de Plano de A��o';
	public $tabela_ordem_por = 'plano_acao_item_nome';
	public $buscar_campos = array('plano_acao_item_nome', 'plano_acao_item_quando', 'plano_acao_item_oque', 'plano_acao_item_como', 'plano_acao_item_onde', 'plano_acao_item_quanto', 'plano_acao_item_porque', 'plano_acao_item_quem');
	public $mostrar_campos = array('plano_acao_item_nome', 'plano_acao_item_quando', 'plano_acao_item_oque', 'plano_acao_item_como', 'plano_acao_item_onde', 'plano_acao_item_quanto', 'plano_acao_item_porque', 'plano_acao_item_quem');
	public $funcao='plano_acao_item';
	}
	
?>
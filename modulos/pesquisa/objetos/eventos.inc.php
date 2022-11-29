<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class eventos extends pesquisa {
	public $tabela = 'eventos';
	public $tabela_modulo = 'calendario';
	public $tabela_chave = 'evento_id';
	public $tabela_extra = '';
	public $tabela_link = 'index.php?m=calendario&a=ver&evento_id=';
	public $tabela_titulo = 'Eventos';
	public $tabela_ordem_por = 'evento_inicio';
	public $buscar_campos = array('evento_titulo', 'evento_descricao', 'evento_oque', 'evento_onde', 'evento_quando', 'evento_como', 'evento_porque', 'evento_quanto', 'evento_quem');
	public $mostrar_campos = array('evento_titulo', 'evento_descricao', 'evento_oque', 'evento_onde', 'evento_quando', 'evento_como', 'evento_porque', 'evento_quanto', 'evento_quem');
	public $funcao='evento';
	}
?>
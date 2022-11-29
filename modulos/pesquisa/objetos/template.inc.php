<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class template extends pesquisa {
	public $tabela = 'template';
	public $tabela_apelido = 'template';
	public $tabela_modulo = 'template';
	public $tabela_chave = 'template.template_id';
	public $tabela_link = 'index.php?m=template&a=template_ver&template_id=';
	public $tabela_titulo = 'Modelos';
	public $tabela_ordem_por = 'template_nome';
	public $buscar_campos = array('template_nome', 'template_descricao', 'template_tipo');
	public $mostrar_campos = array('template_nome', 'template_descricao', 'template_tipo');
	public $tabela_agruparPor = 'template.template_id';
	public $funcao='template';
	}
?>
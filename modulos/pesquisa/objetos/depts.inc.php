<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class depts extends pesquisa {
	public $tabela = 'depts';
	public $tabela_modulo = 'depts';
	public $tabela_chave = 'dept_id';
	public $tabela_link = 'index.php?m=depts&a=ver&dept_id=';
	public $tabela_titulo='departamentos';
	public $ordem_por = 'dept_nome';
	public $buscar_campos = array('dept_nome', 'dept_endereco1', 'dept_endereco2', 'dept_cidade', 'dept_estado', 'dept_cep', 'dept_url', 'dept_descricao');
	public $mostrar_campos = array('dept_nome', 'dept_endereco1', 'dept_endereco2', 'dept_cidade', 'dept_estado', 'dept_cep', 'dept_url', 'dept_descricao');
  public $funcao='departamento';
	}
?>
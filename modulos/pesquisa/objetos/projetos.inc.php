<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
	
class projetos extends pesquisa {
	public $tabela = 'projetos';
	public $tabela_apelido = 'projetos';
	public $tabela_modulo = 'projetos';
	public $tabela_chave = 'projeto_id';
	public $tabela_link = 'index.php?m=projetos&a=ver&projeto_id=';
	public $tabela_titulo = 'projetos';
	public $tabela_ordem_por = 'projeto_nome';
	public $buscar_campos = array('projeto_nome', 'projeto_descricao', 'projeto_url', 'projeto_url_externa', 'projeto_codigo', 'projeto_objetivos', 'projeto_observacao', 'projeto_como', 'projeto_localizacao', 'projeto_beneficiario', 'projeto_justificativa', 'projeto_objetivo', 'projeto_objetivo_especifico', 'projeto_escopo', 'projeto_nao_escopo', 'projeto_premissas', 'projeto_restricoes', 'projeto_orcamento', 'projeto_beneficio', 'projeto_produto', 'projeto_requisito');
	public $mostrar_campos = array('projeto_nome', 'projeto_descricao', 'projeto_url', 'projeto_url_externa', 'projeto_codigo', 'projeto_objetivos', 'projeto_observacao', 'projeto_como', 'projeto_localizacao', 'projeto_beneficiario', 'projeto_justificativa', 'projeto_objetivo', 'projeto_objetivo_especifico', 'projeto_escopo', 'projeto_nao_escopo', 'projeto_premissas', 'projeto_restricoes', 'projeto_orcamento', 'projeto_beneficio', 'projeto_produto', 'projeto_requisito');
	public $funcao='projeto';

	}

?>
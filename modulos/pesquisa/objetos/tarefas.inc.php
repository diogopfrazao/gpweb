<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
class tarefas extends pesquisa {
	public $tabela = 'tarefas';
	public $tabela_modulo = 'tarefas';
	public $tabela_chave = 'tarefa_id';
	public $tabela_link = 'index.php?m=tarefas&a=ver&tarefa_id=';
	public $tabela_titulo = 'tarefas';
	public $tabela_ordem_por = 'tarefa_nome';
	public $buscar_campos = array('tarefa_nome', 'tarefa_descricao', 'tarefa_onde', 'tarefa_porque', 'tarefa_como', 'tarefa_customizado', 'tarefa_situacao_atual', 'tarefa_url_relacionada', 'tarefa_tipo', 'tarefa_codigo');
	public $mostrar_campos = array('tarefa_nome', 'tarefa_descricao', 'tarefa_onde', 'tarefa_porque', 'tarefa_como', 'tarefa_customizado', 'tarefa_situacao_atual', 'tarefa_url_relacionada', 'tarefa_tipo', 'tarefa_codigo');
  public $funcao='tarefa';
	public function ctarefas() {
		return new tarefas();
		}
	}
?>
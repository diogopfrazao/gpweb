<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este avaliacao ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este avaliacao, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

class avaliacoes extends pesquisa {
	public $tabela = 'avaliacao';
	public $tabela_apelido = 'avaliacao';
	public $tabela_modulo = 'projetos';
	public $tabela_chave = 'avaliacao.avaliacao_id';
	public $tabela_link = 'index.php?m=praticas&a=avaliacao_ver&avaliacao_id=';
	public $tabela_titulo = 'Avalia??es';
	public $tabela_ordem_por = 'avaliacao_nome';
	public $buscar_campos = array('avaliacao_nome', 'avaliacao_descricao');
	public $mostrar_campos = array('avaliacao_nome', 'avaliacao_descricao');
	public $tabela_agruparPor = 'avaliacao.avaliacao_id';
	public $funcao='avaliacao';
	}
?>
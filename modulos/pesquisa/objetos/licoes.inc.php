<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este licao � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este licao, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class licoes extends pesquisa {
	public $tabela = 'licao';
	public $tabela_apelido = 'licao';
	public $tabela_modulo = 'projetos';
	public $tabela_chave = 'licao.licao_id';
	public $tabela_link = 'index.php?m=projetos&a=licao_ver&licao_id=';
	public $tabela_titulo = 'licoes';
	public $tabela_ordem_por = 'licao_nome';
	public $buscar_campos = array('licao_nome', 'licao_ocorrencia', 'licao_categoria', 'licao_consequencia', 'licao_acao_tomada', 'licao_aprendizado');
	public $mostrar_campos = array('licao_nome', 'licao_ocorrencia', 'licao_categoria', 'licao_consequencia', 'licao_acao_tomada', 'licao_aprendizado');
	public $tabela_agruparPor = 'licao.licao_id';
	public $funcao='licao';
	}
?>
<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class demandas extends pesquisa {
	public $tabela = 'demandas';
	public $tabela_apelido = 'demandas';
	public $tabela_modulo = 'projetos';
	public $tabela_chave = 'demandas.demanda_id';
	public $tabela_link = 'index.php?m=projetos&a=demanda_ver&demanda_id=';
	public $tabela_titulo = 'Demandas';
	public $tabela_ordem_por = 'demanda_nome';
	public $buscar_campos = array('demanda_nome', 'demanda_identificacao', 'demanda_justificativa', 'demanda_resultados', 'demanda_alinhamento', 'demanda_fonte_recurso', 'demanda_observacao', 'demanda_prazo', 'demanda_custos', 'demanda_codigo', 'demanda_cliente_obs', 'demanda_supervisor_obs', 'demanda_autoridade_obs', 'demanda_descricao', 'demanda_objetivos', 'demanda_como', 'demanda_localizacao', 'demanda_beneficiario', 'demanda_objetivo', 'demanda_objetivo_especifico', 'demanda_escopo', 'demanda_nao_escopo', 'demanda_premissas', 'demanda_restricoes', 'demanda_orcamento', 'demanda_beneficio', 'demanda_produto', 'demanda_requisito');
	public $mostrar_campos = array('demanda_nome', 'demanda_identificacao', 'demanda_justificativa', 'demanda_resultados', 'demanda_alinhamento', 'demanda_fonte_recurso', 'demanda_observacao', 'demanda_prazo', 'demanda_custos', 'demanda_codigo', 'demanda_cliente_obs', 'demanda_supervisor_obs', 'demanda_autoridade_obs', 'demanda_descricao', 'demanda_objetivos', 'demanda_como', 'demanda_localizacao', 'demanda_beneficiario', 'demanda_objetivo', 'demanda_objetivo_especifico', 'demanda_escopo', 'demanda_nao_escopo', 'demanda_premissas', 'demanda_restricoes', 'demanda_orcamento', 'demanda_beneficio', 'demanda_produto', 'demanda_requisito');
	public $tabela_agruparPor = 'demandas.demanda_id';
	public $funcao='demanda';
	}
?>
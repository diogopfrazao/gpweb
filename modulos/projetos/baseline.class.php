<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


class CBaseline {
	
	public $projeto_id = null;
	public $baseline_nome = null;
	public $baseline_descricao = null;
	
	public function __construct($projeto_id=null, $baseline_nome = null, $baseline_descricao = null) {
		$this->projeto_id=(int)$projeto_id;
		$this->baseline_nome=previnirXSS($baseline_nome);
		$this->baseline_descricao=previnirXSS($baseline_descricao);
		}
		
		
	public function setProjeto($projeto_id=null, $baseline_nome = null, $baseline_descricao = null){
		$this->projeto_id=(int)$projeto_id;
		$this->baseline_nome=previnirXSS($baseline_nome);
		$this->baseline_descricao=previnirXSS($baseline_descricao);
		}	
		

	
	public function gerar() {
		global $bd, $Aplic, $config;
		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_projeto='.(int)$this->projeto_id);
		$lista_tarefas = $sql->carregarColuna();
		$sql->limpar();
		
		$lista_tarefas=implode(',',$lista_tarefas);
		if (!$lista_tarefas) $lista_tarefas='0';
	
		$sql->adTabela('eventos');
		$sql->adCampo('evento_id');
		$sql->adOnde('evento_projeto='.(int)$this->projeto_id.' OR evento_tarefa IN ('.$lista_tarefas.')');
		$lista_eventos = $sql->carregarColuna();
		$sql->limpar();
		$lista_eventos=implode(',',$lista_eventos);
		if (!$lista_eventos) $lista_eventos='0';
	
		$sql->adTabela('baseline');
		$sql->adInserir('baseline_projeto_id', $this->projeto_id);
		$sql->adInserir('baseline_nome', $this->baseline_nome);
		$sql->adInserir('baseline_descricao', $this->baseline_descricao);
		$sql->adInserir('baseline_data', date('Y-m-d H:i:s'));
		$sql->exec();
		$baseline_id=$bd->Insert_ID('baseline','baseline_id');
		$sql->limpar();
	
		if ($Aplic->profissional && $config['anexo_eb']){
															copiar('eb_arquivo', 'eb_arquivo_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_encerramento', 'eb_encerramento_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_situacao', 'eb_situacao_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_aceite', 'eb_aceite_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_mudanca_controle', 'eb_mudanca_controle_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_mudanca', 'eb_mudanca_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_mudanca_item', 'eb_mudanca_item_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_qualidade', 'eb_qualidade_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_qualidade_item', 'eb_qualidade_item_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_risco_item', 'eb_risco_item_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_risco', 'eb_risco_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_interessado', 'eb_interessado_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_comunicacao', 'eb_comunicacao_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_humano', 'eb_humano_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_humano_matriz', 'eb_humano_matriz_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_plano_item', 'eb_plano_item_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_plano', 'eb_plano_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_escopo', 'eb_escopo_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_iniciacao', 'eb_iniciacao_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_iniciacao_envolvido', 'projeto_id='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_implantacao', 'eb_implantacao_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_viabilidade', 'eb_viabilidade_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('eb_viabilidade_envolvido', 'projeto_id='.(int)$this->projeto_id, $baseline_id);
	
															copiar('eb_encerramento_campo', 'eb_encerramento_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_encerramento', 'eb_encerramento_campo_encerramento=eb_encerramento.eb_encerramento_id');
															copiar('eb_situacao_campo', 'eb_situacao_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_situacao', 'eb_situacao_campo_situacao=eb_situacao.eb_situacao_id');
															copiar('eb_mudanca_controle_campo', 'eb_mudanca_controle_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_mudanca_controle', 'eb_mudanca_controle_campo_mudanca=eb_mudanca_controle.eb_mudanca_controle_id');
															copiar('eb_mudanca_controle_item', 'eb_mudanca_controle_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_mudanca_controle', 'eb_mudanca_controle_item_mudanca_controle=eb_mudanca_controle.eb_mudanca_controle_id');
															copiar('eb_mudanca_controle_escopo', 'eb_mudanca_controle_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_mudanca_controle', 'eb_mudanca_controle_escopo_mudanca_controle=eb_mudanca_controle.eb_mudanca_controle_id');
															copiar('eb_mudanca_controle_custo', 'eb_mudanca_controle_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_mudanca_controle', 'eb_mudanca_controle_custo_mudanca_controle=eb_mudanca_controle.eb_mudanca_controle_id');
															copiar('eb_mudanca_campo', 'eb_mudanca_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_mudanca', 'eb_mudanca_campo_mudanca=eb_mudanca.eb_mudanca_id');
															copiar('eb_interessado_campo', 'eb_interessado_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_interessado', 'eb_interessado_campo_interessado=eb_interessado.eb_interessado_id');
															copiar('eb_comunicacao_campo', 'eb_comunicacao_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_comunicacao', 'eb_comunicacao_campo_comunicacao=eb_comunicacao.eb_comunicacao_id');
															copiar('eb_plano_item_depts', 'eb_plano_item_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_plano_item', 'eb_plano_item_depts.eb_plano_item_id=eb_plano_item.eb_plano_item_id');
															copiar('eb_plano_item_custos', 'eb_plano_item_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_plano_item', 'eb_plano_item_custos_eb_plano_item=eb_plano_item.eb_plano_item_id');
															copiar('eb_plano_item_designados', 'eb_plano_item_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_plano_item', 'eb_plano_item_designados.eb_plano_item_id=eb_plano_item.eb_plano_item_id');
															copiar('eb_plano_item_gastos', 'eb_plano_item_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_plano_item', 'eb_plano_item_gastos_eb_plano_item=eb_plano_item.eb_plano_item_id');
															copiar('eb_plano_item_h_custos', 'eb_plano_item_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_plano_item', 'h_custos_eb_plano_item=eb_plano_item.eb_plano_item_id');
															copiar('eb_plano_item_h_gastos', 'eb_plano_item_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_plano_item', 'h_gastos_eb_plano_item=eb_plano_item.eb_plano_item_id');
															copiar('eb_escopo_campo', 'eb_escopo_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_escopo', 'eb_escopo_campo_escopo=eb_escopo.eb_escopo_id');
															copiar('eb_iniciacao_campo', 'eb_iniciacao_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_iniciacao', 'eb_iniciacao_campo_iniciacao=eb_iniciacao.eb_iniciacao_id');
															copiar('eb_implantacao_campo', 'eb_implantacao_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_implantacao', 'eb_implantacao_campo_implantacao=eb_implantacao.eb_implantacao_id');
															copiar('eb_viabilidade_campo', 'eb_viabilidade_projeto='.(int)$this->projeto_id, $baseline_id, 'eb_viabilidade', 'eb_viabilidade_campo_viabilidade=eb_viabilidade.eb_viabilidade_id');
															}
	
		if ($Aplic->profissional) copiar('evento_gestao', 'evento_gestao_evento IN ('.$lista_eventos.')', $baseline_id);
															copiar('eventos', 'evento_tarefa IN ('.$lista_tarefas.') OR evento_projeto='.(int)$this->projeto_id, $baseline_id);
		if ($Aplic->profissional) copiar('folha_ponto', 'folha_ponto_tarefa IN ('.$lista_tarefas.') OR folha_ponto_evento IN ('.$lista_eventos.')', $baseline_id);
		if ($Aplic->profissional) copiar('folha_ponto_arquivo', 'folha_ponto_tarefa IN ('.$lista_tarefas.') OR folha_ponto_evento IN ('.$lista_eventos.')', $baseline_id, 'folha_ponto', 'folha_ponto_id=folha_ponto_arquivo_ponto');
		if ($Aplic->profissional) copiar('folha_ponto_gasto', 'folha_ponto_tarefa IN ('.$lista_tarefas.') OR folha_ponto_evento IN ('.$lista_eventos.')', $baseline_id, 'folha_ponto', 'folha_ponto_id=folha_ponto_gasto_folha');
															copiar('municipio_lista', 'municipio_lista_tarefa IN ('.$lista_tarefas.') OR municipio_lista_projeto='.(int)$this->projeto_id, $baseline_id);
		if ($Aplic->profissional) copiar('pagamento', 'pagamento_tarefa IN ('.$lista_tarefas.') OR pagamento_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('projeto_area', 'projeto_area_tarefa IN ('.$lista_tarefas.') OR projeto_area_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('projeto_cia', 'projeto_cia_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('projeto_contatos', 'projeto_id='.(int)$this->projeto_id, $baseline_id);
															copiar('projeto_depts', 'projeto_id='.(int)$this->projeto_id, $baseline_id);
		if ($Aplic->profissional) copiar('projeto_gestao', 'projeto_gestao_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('projeto_integrantes', 'projeto_id='.(int)$this->projeto_id, $baseline_id);
															copiar('projeto_ponto', 'projeto_area_tarefa IN ('.$lista_tarefas.') OR projeto_area_projeto='.(int)$this->projeto_id, $baseline_id, 'projeto_area', 'projeto_ponto.projeto_area_id=projeto_area.projeto_area_id');
		if ($Aplic->profissional) copiar('projeto_portfolio', 'projeto_portfolio_pai='.(int)$this->projeto_id, $baseline_id);
		if ($Aplic->profissional) copiar('priorizacao', 'priorizacao_projeto='.(int)$this->projeto_id, $baseline_id);
		if ($Aplic->profissional) copiar('projeto_stakeholder', 'projeto_stakeholder_projeto='.(int)$this->projeto_id, $baseline_id);
															copiar('projetos', 'projeto_id='.(int)$this->projeto_id, $baseline_id);
		if ($Aplic->profissional) copiar('recurso_ponto', 'recurso_ponto_tarefa IN ('.$lista_tarefas.')', $baseline_id);
		if ($Aplic->profissional) copiar('recurso_ponto_arquivo', 'recurso_ponto_tarefa IN ('.$lista_tarefas.')', $baseline_id, 'recurso_ponto', 'recurso_ponto_id=recurso_ponto_arquivo_ponto');
		if ($Aplic->profissional) copiar('recurso_ponto_gasto', 'recurso_ponto_tarefa IN ('.$lista_tarefas.')', $baseline_id, 'recurso_ponto', 'recurso_ponto_id=recurso_ponto_gasto_ponto');
															copiar('recurso_tarefa', 'recurso_tarefa_tarefa IN ('.$lista_tarefas.')', $baseline_id);
															copiar('tarefa_contatos', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
															copiar('tarefa_custos', 'tarefa_custos_tarefa IN ('.$lista_tarefas.')', $baseline_id);
															copiar('tarefa_dependencias', 'dependencias_tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
															copiar('tarefa_depts', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
		if ($Aplic->profissional) copiar('tarefa_designado_periodos', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
															copiar('tarefa_designados', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
		if ($Aplic->profissional) copiar('tarefa_entrega', 'tarefa_entrega_tarefa IN ('.$lista_tarefas.')', $baseline_id);
															copiar('tarefa_gastos', 'tarefa_gastos_tarefa IN ('.$lista_tarefas.')', $baseline_id);
															copiar('log', 'log_tarefa IN ('.$lista_tarefas.')', $baseline_id);
		if ($Aplic->profissional) copiar('log_arquivo', 'log_tarefa IN ('.$lista_tarefas.')', $baseline_id, 'log', 'log_id=log_arquivo_log');
															copiar('tarefas', 'tarefa_id IN ('.$lista_tarefas.')', $baseline_id);
		if ($Aplic->profissional) copiar('tarefa_cia', 'tarefa_cia_tarefa IN ('.$lista_tarefas.')', $baseline_id);
		
		if ($Aplic->profissional) copiar('projeto_atividade', 'projeto_atividade_projeto='.(int)$this->projeto_id, $baseline_id);
		if ($Aplic->profissional) copiar('projeto_regiao', 'projeto_regiao_projeto='.(int)$this->projeto_id, $baseline_id);
		
	
		if($Aplic->profissional){
	    $sql->adTabela('tarefas');
	    $sql->adCampo('tarefa_id, tarefa_inicio');
	    $sql->adOnde('tarefa_projeto='.(int)$this->projeto_id);
	    $sql->adOnde('tarefa_data_atualizada IS NULL');
	    $sql->exec();
	    $lista_tarefas = $sql->Lista();
	    $sql->limpar();
	
	    foreach($lista_tarefas as $tarefa){
	      $sql->adTabela('tarefas');
	      $sql->adAtualizar('tarefa_data_atualizada',$tarefa['tarefa_inicio']);
	      $sql->adOnde('tarefa_id='.(int)$tarefa['tarefa_id']);
	      $sql->exec();
	      $sql->limpar();
	      }
	    }
		
		}
	
	}


function copiar($tabela, $onde='', $baseline_id=0, $esqUnir='', $esqParametro=''){
	  $sql = new BDConsulta;
	  $sql->adTabela($tabela);
	  if ($esqUnir) $sql->esqUnir($esqUnir, $esqUnir, $esqParametro);
	  $sql->adCampo($tabela.'.*');
	  if ($onde) $sql->adOnde($onde);
	  $lista = $sql->lista();
	  $sql->limpar();
	 	if (count($lista)){
	 		foreach ($lista as $linha){
	 			$sql->adTabela('baseline_'.$tabela);
				$sql->adInserir('baseline_id', $baseline_id);
				foreach ($linha as $chave => $valor) $sql->adInserir($chave, $valor);
		   	$sql->exec();
				$sql->limpar();
				}
			}
		}


?>
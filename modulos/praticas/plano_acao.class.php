<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


class CPlanoAcao extends CAplicObjeto {

  public $plano_acao_id = null;
  public $plano_acao_cia = null;
  public $plano_acao_dept = null;
  public $plano_acao_responsavel = null;
  public $plano_acao_projeto = null;
  public $plano_acao_tarefa = null;
  public $plano_acao_pratica = null;
  public $plano_acao_indicador = null;
  public $plano_acao_perspectiva = null;
  public $plano_acao_tema = null;
  public $plano_acao_objetivo = null;
  public $plano_acao_estrategia = null;
  public $plano_acao_meta = null;
  public $plano_acao_fator = null;
  public $plano_acao_canvas = null;
  public $plano_acao_usuario = null;
  public $plano_acao_nome = null;
  public $plano_acao_descricao = null;
  public $plano_acao_cor = null;
  public $plano_acao_acesso = null;
  public $plano_acao_inicio = null;
  public $plano_acao_fim = null;
  public $plano_acao_duracao = null;
  public $plano_acao_percentagem = null;
  public $plano_acao_calculo_porcentagem = null;
  public $plano_acao_data_obrigatorio = null;
  public $plano_acao_data_apenas = null;
  public $plano_acao_ano = null;
  public $plano_acao_codigo = null;
  public $plano_acao_setor = null;
	public $plano_acao_segmento = null;
	public $plano_acao_intervencao = null;
	public $plano_acao_tipo_intervencao = null;
	public $plano_acao_sequencial = null;
 	public $plano_acao_principal_indicador = null;
	public $plano_acao_aprovado = null;
	public $plano_acao_moeda = null;
	public $plano_acao_ativo = null;
	public $plano_acao_uuid = null;

	public function __construct() {
		parent::__construct('plano_acao', 'plano_acao_id');
		}


	public function arrumarTodos() {
		parent::arrumarTodos();
		}

	public function check() {
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessarPlanoAcao($this->plano_acao_acesso, $this->plano_acao_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarPlanoAcao($this->plano_acao_acesso, $this->plano_acao_id);
		return $valor;
		}

	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->plano_acao_id) {
			$ret = $sql->atualizarObjeto('plano_acao', $this, 'plano_acao_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('plano_acao', $this, 'plano_acao_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('plano_acao', $this->plano_acao_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->plano_acao_id);



		$plano_acao_contatos=getParam($_REQUEST, 'plano_acao_contatos', array());
		$plano_acao_contatos=explode(',', $plano_acao_contatos);
		$sql->setExcluir('plano_acao_contato');
		$sql->adOnde('plano_acao_contato_acao = '.$this->plano_acao_id);
		$sql->exec();
		$sql->limpar();
		foreach($plano_acao_contatos as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('plano_acao_contato');
				$sql->adInserir('plano_acao_contato_acao', $this->plano_acao_id);
				$sql->adInserir('plano_acao_contato_contato', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}



		$plano_acao_usuarios=getParam($_REQUEST, 'plano_acao_usuarios', null);
		$plano_acao_usuarios=explode(',', $plano_acao_usuarios);
		$sql->setExcluir('plano_acao_usuario');
		$sql->adOnde('plano_acao_usuario_acao = '.$this->plano_acao_id);
		$sql->exec();
		$sql->limpar();
		foreach($plano_acao_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('plano_acao_usuario');
				$sql->adInserir('plano_acao_usuario_acao', $this->plano_acao_id);
				$sql->adInserir('plano_acao_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'plano_acao_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('plano_acao_dept');
		$sql->adOnde('plano_acao_dept_acao = '.$this->plano_acao_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('plano_acao_dept');
				$sql->adInserir('plano_acao_dept_acao', $this->plano_acao_id);
				$sql->adInserir('plano_acao_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$sql->setExcluir('plano_acao_cia');
		$sql->adOnde('plano_acao_cia_plano_acao='.(int)$this->plano_acao_id);
		$sql->exec();
		$sql->limpar();
		$cias=getParam($_REQUEST, 'plano_acao_cias', '');
		$cias=explode(',', $cias);
		if (count($cias)) {
			foreach ($cias as $cia_id) {
				if ($cia_id){
					$sql->adTabela('plano_acao_cia');
					$sql->adInserir('plano_acao_cia_plano_acao', $this->plano_acao_id);
					$sql->adInserir('plano_acao_cia_cia', $cia_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}


		$uuid=getParam($_REQUEST, 'uuid', null);
		
		$sql->adTabela('plano_acao_item');
		$sql->adAtualizar('plano_acao_item_acao', (int)$this->plano_acao_id);
		$sql->adAtualizar('plano_acao_item_uuid', null);
		$sql->adOnde('plano_acao_item_uuid=\''.$uuid.'\'');
		$sql->exec();
		$sql->limpar();
		
		if ($uuid){
			$sql->adTabela('plano_acao_gestao');
			$sql->adAtualizar('plano_acao_gestao_acao', (int)$this->plano_acao_id);
			$sql->adAtualizar('plano_acao_gestao_uuid', null);
			$sql->adOnde('plano_acao_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
			
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_acao', (int)$this->plano_acao_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_acao', (int)$this->plano_acao_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			}

		//calculo de porcentagem
		$this->mudanca_fisico();
		

		//verificar aprovacao
		if ($Aplic->profissional) {
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_acao='.(int)$this->plano_acao_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_acao='.(int)$this->plano_acao_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_acao='.(int)$this->plano_acao_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('plano_acao');
			$sql->adAtualizar('plano_acao_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('plano_acao_id='.(int)$this->plano_acao_id);
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}



	public function mudanca_fisico(){
		global $Aplic;
		if ($Aplic->profissional) {
			$sql = new BDConsulta;
			if ($this->plano_acao_calculo_porcentagem){
				$sql->adTabela('plano_acao_item');
				$sql->adOnde('plano_acao_item_acao = '.(int)$this->plano_acao_id);
				$sql->adCampo('plano_acao_item_percentagem, plano_acao_item_peso');
				$lista=$sql->Lista();
				$sql->limpar();

				$numerador=0;
				$denominador=0;
				foreach($lista as $linha) {
					$numerador+=($linha['plano_acao_item_percentagem']*$linha['plano_acao_item_peso']);
					$denominador+=$linha['plano_acao_item_peso'];
					}
					
				$porcentagem=($denominador ? $numerador/$denominador : 0);
					
				$sql->adTabela('plano_acao');
				$sql->adAtualizar('plano_acao_percentagem', $porcentagem);
				$sql->adOnde('plano_acao_id='.(int)$this->plano_acao_id);
				$sql->exec();
				$sql->limpar();
				if ($this->plano_acao_percentagem!=$porcentagem) $this->disparo_observador('fisico');	
				}
			}
		}






	public function disparo_observador( $acao='fisico'){
		//Quem faz uso deste tema em c�lculos de percentagem
		$sql = new BDConsulta;
		
		$sql->adTabela('plano_acao_observador');
		$sql->adCampo('plano_acao_observador.*');
		$sql->adOnde('plano_acao_observador_plano_acao ='.(int)$this->plano_acao_id);
		$sql->adOnde('plano_acao_observador_acao =\''.$acao.'\'');
			
		$lista = $sql->lista();
		$sql->limpar();

		$qnt_projeto=0;
		$qnt_programa=0;
		$qnt_plano_gestao=0;
		$qnt_perspectiva=0;
		$qnt_tema=0;
		$qnt_objetivo=0;
		$qnt_me=0;
		$qnt_fator=0;
		$qnt_estrategia=0;
		$qnt_meta=0;
		$qnt_acao=0;
		$qnt_risco=0;
		$qnt_risco_resposta=0;
		$qnt_pdcl_item=0;
		foreach($lista as $linha){
			if ($linha['plano_acao_observador_projeto']){
				if (!($qnt_projeto++)) require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
				$obj= new CProjeto();
				$obj->load($linha['plano_acao_observador_projeto']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}
				
			elseif ($linha['plano_acao_observador_risco']){
				if (!($qnt_risco++)) require_once BASE_DIR.'/modulos/praticas/risco_pro.class.php';
				$obj= new CRisco();
				$obj->load($linha['plano_acao_observador_risco']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}	
				
			elseif ($linha['plano_acao_observador_risco_resposta']){
				if (!($qnt_risco_resposta++)) require_once BASE_DIR.'/modulos/praticas/risco_resposta_pro.class.php';
				$obj= new CRiscoResposta();
				$obj->load($linha['plano_acao_observador_risco_resposta']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}	
				
			elseif ($linha['plano_acao_observador_pdcl_item']){
				if (!($qnt_pdcl_item++)) require_once BASE_DIR.'/modulos/pdcl/pdcl_item.class.php';
				$obj= new CPDCLItem();
				$obj->load($linha['plano_acao_observador_pdcl_item']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}		
				
			elseif ($linha['plano_acao_observador_programa']){
				if (!($qnt_programa++)) require_once BASE_DIR.'/modulos/projetos/programa_pro.class.php';
				$obj= new CPrograma();
				$obj->load($linha['plano_acao_observador_programa']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}
			elseif ($linha['plano_acao_observador_plano_gestao']){
				if (!($qnt_plano_gestao++)) require_once BASE_DIR.'/modulos/praticas/gestao/gestao.class.php';
				$obj= new CGestao();
				$obj->load($linha['plano_acao_observador_plano_gestao']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}	
			elseif ($linha['plano_acao_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['plano_acao_observador_perspectiva']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}
			elseif ($linha['plano_acao_observador_tema']){
				if (!($qnt_tema++)) require_once BASE_DIR.'/modulos/praticas/tema.class.php';
				$obj= new CTema();
				$obj->load($linha['plano_acao_observador_tema']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}
			elseif ($linha['plano_acao_observador_objetivo']){
				if (!($qnt_objetivo++)) require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
				$obj= new CObjetivo();
				$obj->load($linha['plano_acao_observador_objetivo']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}
			elseif ($linha['plano_acao_observador_me']){
				if (!($qnt_me++)) require_once BASE_DIR.'/modulos/praticas/me_pro.class.php';
				$obj= new CMe();
				$obj->load($linha['plano_acao_observador_me']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}
			elseif ($linha['plano_acao_observador_fator']){
				if (!($qnt_fator++)) require_once BASE_DIR.'/modulos/praticas/fator.class.php';
				$obj= new CFator();
				$obj->load($linha['plano_acao_observador_fator']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}
			elseif ($linha['plano_acao_observador_estrategia']){
				if (!($qnt_estrategia++)) require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
				$obj= new CEstrategia();
				$obj->load($linha['plano_acao_observador_estrategia']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}
			elseif ($linha['plano_acao_observador_meta']){
				if (!($qnt_meta++)) require_once BASE_DIR.'/modulos/praticas/meta.class.php';
				$obj= new CMeta();
				$obj->load($linha['plano_acao_observador_meta']);
				if (method_exists($obj, $linha['plano_acao_observador_metodo'])){
					$obj->{$linha['plano_acao_observador_metodo']}();
					}
				}

			}
		}


	public function custo(){
		global $Aplic;
		$lista='';
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_item_custos','plano_acao_item_custos');
		$sql->esqUnir('plano_acao_item','plano_acao_item', 'plano_acao_item_id=plano_acao_item_custos_plano_acao_item');
		$sql->adCampo('SUM(plano_acao_item_custos_quantidade*plano_acao_item_custos_custo) AS total');
		$sql->adOnde('plano_acao_item_acao='.(int)$this->plano_acao_id);
		$total=$sql->Resultado();
		$sql->limpar();
		return $total;
		}


	public function gasto(){
		global $Aplic;
		$lista='';
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_item_gastos','plano_acao_item_gastos');
		$sql->esqUnir('plano_acao_item','plano_acao_item', 'plano_acao_item_id=plano_acao_item_gastos_plano_acao_item');
		$sql->adCampo('SUM(plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo) AS total');
		$sql->adOnde('plano_acao_item_acao='.(int)$this->plano_acao_id);
		$total=$sql->Resultado();
		$sql->limpar();
		return $total;
		}


	public function getCodigo( $completo=true){
		if (!$this->plano_acao_sequencial) $this->setSequencial();
		if ($this->plano_acao_setor && $this->plano_acao_sequencial){
			if ($this->plano_acao_sequencial<10) $sequencial='000'.$this->plano_acao_sequencial;
			elseif ($this->plano_acao_sequencial<100) $sequencial='00'.$this->plano_acao_sequencial;
			elseif ($this->plano_acao_sequencial<1000) $sequencial='0'.$this->plano_acao_sequencial;
			else $sequencial=$this->plano_acao_sequencial;
			return $this->plano_acao_setor.($completo && $this->plano_acao_segmento ? '.' : '').substr($this->plano_acao_segmento, 2).($completo && $this->plano_acao_intervencao ? '.' : '').substr($this->plano_acao_intervencao, 4).($completo && $this->plano_acao_tipo_intervencao ? '.' : '').substr($this->plano_acao_tipo_intervencao, 6).($completo ? '.' : '').$sequencial.($completo  && $this->plano_acao_ano? '/' : '').$this->plano_acao_ano;
			}
		else return $this->plano_acao_codigo;
		}


	public function setSequencial(){
		if (!$this->plano_acao_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('plano_acao');
			$sql->adCampo('max(plano_acao_sequencial)');
			$sql->adOnde('plano_acao_cia='.(int)$this->plano_acao_cia);
			if ($this->plano_acao_ano) $sql->adOnde('plano_acao_ano=\''.$this->plano_acao_ano.'\'');
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao');
			$sql->adAtualizar('plano_acao_sequencial', ($maior_sequencial+1));
			$sql->adOnde('plano_acao_id ='.(int)$this->plano_acao_id);
			$retorno=$sql->exec();
			$sql->limpar();
			$this->plano_acao_sequencial=($maior_sequencial+1);
			return $retorno;
			}
		}

	public function getSetor(){
		if ($this->plano_acao_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'AcaoSetor\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->plano_acao_setor.'\'');
			$plano_acao_setor= $sql->Resultado();
			$sql->limpar();
			return $plano_acao_setor;
			}
		else return '';
		}

	public function getSegmento(){
		if ($this->plano_acao_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'AcaoSegmento\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->plano_acao_segmento.'\'');
			$plano_acao_segmento= $sql->Resultado();
			$sql->limpar();
			return $plano_acao_segmento;
			}
		else return '';
		}

	public function getIntervencao(){
		if ($this->plano_acao_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'AcaoIntervencao\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->plano_acao_intervencao.'\'');
			$plano_acao_intervencao= $sql->Resultado();
			$sql->limpar();
			return $plano_acao_intervencao;
			}
		else return '';
		}

	public function getTipoIntervencao(){
		if ($this->plano_acao_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'AcaoTipoIntervencao\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->plano_acao_tipo_intervencao.'\'');
			$plano_acao_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $plano_acao_tipo_intervencao;
			}
		else return '';
		}




	public function notificarResponsavel( $comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$q = new BDConsulta;
		$q->adTabela('plano_acao');
		$q->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = plano_acao_responsavel');
		$q->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$q->adCampo('usuarios.usuario_id, contato_email');
		$q->adOnde('plano_acao_id = '.(int)$this->plano_acao_id);
		$linha = $q->linha();
		$q->limpar();
		$corpo_email='';
		if ($linha['usuario_id'] && $linha['usuario_id']!=$Aplic->usuario_id) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['acao']).' Excluid'.$config['genero_acao'].': '.$this->plano_acao_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['acao']).' Atualizad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			else $titulo=ucfirst($config['acao']).' Criad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi atualizad'.$config['genero_acao'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi criad'.$config['genero_acao'].'.</b><br>';
			$corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser o respons�vel pel'.$config['genero_acao'].' '.$config['acao'].')<br><br>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;
			$corpo_externo=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id.'\');"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
			$validos=0;

			if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
			if ($email->EmailValido($linha['contato_email']) && $config['email_ativo']) {
				if ($Aplic->profissional){
					require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
					$email = new Mail;
                    $email->De($config['email'], $Aplic->usuario_nome);

                    if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                        $email->ResponderPara($Aplic->usuario_email);
                        }
                    else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                        $email->ResponderPara($Aplic->usuario_email2);
                        }

					if ($email->EmailValido($linha['contato_email'])) {
						if ($Aplic->profissional){
							require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
							$endereco=link_email_externo($linha['usuario_id'], 'm=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
							}
						$email->Assunto($titulo, $localidade_tipo_caract);
						$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
						$email->Para($linha['contato_email'], true);
						$email->Enviar();
						}
					}
				else {
					$validos++;
					$email->Para($linha['contato_email'], true);
					}
				}

			if ($validos) $email->Enviar();
			}
		}


	public function notificarContatos( $comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_contato');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = plano_acao_contato_contato');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('usuarios.usuario_id != '.(int)$Aplic->usuario_id);
		$sql->adOnde('plano_acao_contato_acao = '.(int)$this->plano_acao_id);
		$usuarios = $sql->Lista();
		$sql->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['acao']).' Excluid'.$config['genero_acao'].': '.$this->plano_acao_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['acao']).' Atualizad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			else $titulo=ucfirst($config['acao']).' Criad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi atualizad'.$config['genero_acao'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi criad'.$config['genero_acao'].'.</b><br>';
			$corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser um dos contatos d'.$config['genero_acao'].' '.$config['acao'].')<br><br>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id.'\');"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
			$validos=0;

			foreach ($usuarios as $linha) {
				$corpo_externo=$corpo;
				if ($linha['usuario_id'] && $linha['usuario_id']!=$Aplic->usuario_id) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo']) {
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;
                        $email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						if ($email->EmailValido($linha['contato_email'])) {
							if ($linha['usuario_id']){
								if ($Aplic->profissional){
									require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
									$endereco=link_email_externo($linha['usuario_id'], 'm=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id);
									$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
									}
								}

							$email->Assunto($titulo, $localidade_tipo_caract);
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($linha['contato_email'], true);
							$email->Enviar();
							}
						}
					else {
						$validos++;
						$email->Para($linha['contato_email'], true);
						}
					}
				}
			if ($validos) $email->Enviar();
			}
		}


	public function notificarDesignados( $comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$email = new Mail;
		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_usuario');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = plano_acao_usuario_usuario');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('plano_acao_usuario_acao = '.(int)$this->plano_acao_id);
		$sql->adOnde('usuarios.usuario_id != '.(int)$Aplic->usuario_id);
		$usuarios = $sql->Lista();
		$sql->limpar();

		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['acao']).' Excluid'.$config['genero_acao'].': '.$this->plano_acao_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['acao']).' Atualizad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			else $titulo=ucfirst($config['acao']).' Criad'.$config['genero_acao'].': '.$this->plano_acao_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi atualizad'.$config['genero_acao'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_acao']).' '.ucfirst($config['acao']).' '.$this->plano_acao_nome.' foi criad'.$config['genero_acao'].'.</b><br>';
			$corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser um dos designados d'.$config['genero_acao'].' '.$config['acao'].')<br><br>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_acao'].' '.$config['acao'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id.'\');"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
			$validos=0;

			foreach ($usuarios as $linha) {
				$corpo_externo=$corpo;
				if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);
				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo']) {
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$email = new Mail;
                        $email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						if ($email->EmailValido($linha['contato_email'])) {

							if ($Aplic->profissional){
									require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
									$endereco=link_email_externo($linha['usuario_id'], 'm=praticas&a=plano_acao_ver&plano_acao_id='.$this->plano_acao_id);
									$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_acao'].' '.$config['acao'].'</b></a>';
									}
							$email->Assunto($titulo, $localidade_tipo_caract);
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($linha['contato_email'], true);
							$email->Enviar();
							}
						}
					else {
						$validos++;
						$email->Para($linha['contato_email'], true);
						}
					}
				}
			if ($validos) $email->Enviar();
			}
		}





	public function fisico_previsto( $data='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return fisico_previsto_acao($this->plano_acao_id, $data, $ate_data_atual, null, null, $indicador_id, null);
		}


	public function fisico_velocidade( $data='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return fisico_velocidade_acao($this->plano_acao_id, $data, $ate_data_atual, null, null, $indicador_id, null);
		}






	}


function fisico_previsto_acao($plano_acao_id, $data_final='', $ate_data_atual=true, $baseline_id=0, $plano_acao_item_id=0, $indicador_id=false, $portfolio_externo=null){
	/*
	if (!$plano_acao_item_id){
		$vetor=($plano_acao_id ? array($plano_acao_id => $plano_acao_id) : array());
		portfolio_plano_acao($plano_acao_id, $vetor, $baseline_id, $portfolio_externo);
		$plano_acao_id=implode(',',$vetor);
		}
	*/	
		
	if (!$data_final) $data_final=date('Y-m-d H:i:s');
	$horas =0;

	$sql = new BDConsulta;

	$filtro_extra=($indicador_id ? filtro_indicador_projeto($indicador_id) : '');
	if ($ate_data_atual){
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_acao_item', 'plano_acao_item', ($baseline_id ? 'plano_acao_item.baseline_id='.(int)$baseline_id : ''));
		$sql->adCampo('SUM(plano_acao_item.plano_acao_item_duracao)');
		if ($plano_acao_item_id) $sql->adOnde('plano_acao_item_id IN ('.$plano_acao_item_id.')');
		elseif ($plano_acao_id) $sql->adOnde('plano_acao_item_acao IN ('.$plano_acao_id.')');
		$sql->adOnde('plano_acao_item_fim<= \''.$data_final.'\'');
		if ($filtro_extra) $sql->adOnde($filtro_extra);
		$horas+= $sql->Resultado();
		$sql->limpar();

		//No meio da execu��o
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_acao_item', 'plano_acao_item', ($baseline_id ? 'plano_acao_item.baseline_id='.(int)$baseline_id : ''));
		$sql->adCampo('plano_acao_item_id, plano_acao_item_inicio, plano_acao_item_cia, plano_acao_item_acao');
		if ($plano_acao_item_id) $sql->adOnde('plano_acao_item_id IN ('.$plano_acao_item_id.')');
		elseif ($plano_acao_id) $sql->adOnde('plano_acao_item_acao IN ('.$plano_acao_id.')');
		$sql->adOnde('plano_acao_item_inicio<= \''.$data_final.'\'');
		$sql->adOnde('plano_acao_item_fim > \''.$data_final.'\'');
		if ($filtro_extra) $sql->adOnde($filtro_extra);
		$em_execucao = $sql->Lista();
		$sql->limpar();
		foreach($em_execucao as $linha) $horas+=horas_periodo($linha['plano_acao_item_inicio'], $data_final, $linha['plano_acao_item_cia'], null, $linha['plano_acao_item_acao']);
		}
	else {
		$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_acao_item', 'plano_acao_item', ($baseline_id ? 'plano_acao_item.baseline_id='.(int)$baseline_id : ''));
		$sql->adCampo('SUM(plano_acao_item.plano_acao_item_duracao*(plano_acao_item.plano_acao_item_percentagem/100))');
		if ($plano_acao_item_id) $sql->adOnde('plano_acao_item_id IN ('.$plano_acao_item_id.')');
		elseif ($plano_acao_id) $sql->adOnde('plano_acao_item_acao IN ('.$plano_acao_id.')');
		if ($filtro_extra) $sql->adOnde($filtro_extra);
		$horas+= $sql->Resultado();
		$sql->limpar();
		}
	
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'plano_acao_item', 'plano_acao_item', ($baseline_id ? 'plano_acao_item.baseline_id='.(int)$baseline_id : ''));
	$sql->adCampo('SUM(plano_acao_item.plano_acao_item_duracao)');
	if ($plano_acao_item_id) $sql->adOnde('plano_acao_item_id IN ('.$plano_acao_item_id.')');
	elseif ($plano_acao_id) $sql->adOnde('plano_acao_item_acao IN ('.$plano_acao_id.')');
	if ($filtro_extra) $sql->adOnde($filtro_extra);
	$total=$sql->Resultado();

	$sql->limpar();
	$porcentagem=($total > 0 ? ($horas/$total)*100 : 0);
	return ($porcentagem > 100 ? 100 : $porcentagem);
	}
	
	
function fisico_velocidade_acao($plano_acao_id, $data_final='', $ate_data_atual=true, $baseline_id=0, $plano_acao_item_id=0, $indicador_id=false, $portfolio_externo=null){
	$feito=fisico_previsto_acao($plano_acao_id, $data_final, false,           $baseline_id, $plano_acao_item_id, $indicador_id, $portfolio_externo);
	$previsto=fisico_previsto_acao($plano_acao_id, $data_final, $ate_data_atual, $baseline_id, $plano_acao_item_id, $indicador_id, $portfolio_externo);
	$porcentagem=($previsto > 0 ? ($feito/$previsto) : 0);
	return ($porcentagem > 100 ? 100 : $porcentagem);
	}	
	
?>
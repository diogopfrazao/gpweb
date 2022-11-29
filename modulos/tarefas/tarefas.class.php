<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $Aplic, $config;
require_once BASE_DIR.'/classes/libmail.class.php';
require_once BASE_DIR.'/classes/aplic.class.php';
require_once BASE_DIR.'/classes/evento_recorrencia.class.php';
require_once BASE_DIR.'/classes/data.class.php';
require_once BASE_DIR.'/modulos/projetos/projetos.class.php';

$percentual = getSisValor('TarefaPorcentagem','','','sisvalor_id');


$filtros = array(
'meu' => ($config['genero_tarefa']=='o' ? 'Meus ' :'Minhas ').$config['tarefa'], 
'minhasIncompletas' => ($config['genero_tarefa']=='o' ? 'Meus ' :'Minhas ').$config['tarefas'].' incomplet'.$config['genero_tarefa'].'s', 
'todasIncompletas' => ($config['genero_tarefa']=='o' ? 'Todos ' :'Todas ').' incomplet'.$config['genero_tarefa'].'s',  
'semDesignado' => 'Tod'.$config['genero_tarefa'].'s '.$config['tarefas'].' sem '.$config['usuario'].' designado',  
'todos' => 'Tod'.$config['genero_tarefa'].'s '.$config['tarefas']); 

$status = getSisValor('StatusTarefa');
$prioridade = getSisValor('PrioridadeTarefa');
$tarefa_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$dinamicas_seguidas = array('0' => '0', '1' => '1');

class CTarefa extends CAplicObjeto {
	public $tarefa_id = null;
	public $tarefa_nome = null;
	public $tarefa_cia = null;
	public $tarefa_dept = null;
	public $tarefa_superior = null;
	public $tarefa_marco = null;
	public $tarefa_projeto = null;
	public $tarefa_comunidade = null;
	public $tarefa_social = null;
	public $tarefa_acao = null;
	public $tarefa_principal_indicador = null;
	public $tarefa_dono = null;
	public $tarefa_inicio = null;
	public $tarefa_inicio_manual = null;
	public $tarefa_inicio_calculado = null;
	public $tarefa_duracao = null;
	public $tarefa_duracao_manual = null;
	public $tarefa_duracao_tipo = null;
	public $tarefa_horas_trabalhadas = null;
	public $tarefa_fim = null;
	public $tarefa_fim_manual = null;
	public $tarefa_status = null;
	public $tarefa_prioridade = null;
	public $tarefa_percentagem = null;
	public $tarefa_percentagem_data = null;
	public $tarefa_descricao = null;
	public $tarefa_custo_almejado = null;
	public $tarefa_url_relacionada = null;
	public $tarefa_criador = null;
	public $tarefa_cliente_publicada = null;
	public $tarefa_dinamica = null;
	public $tarefa_acesso = null;
	public $tarefa_notificar = null;
	public $tarefa_customizado = null;
	public $tarefa_tipo = null;
	public $tarefa_adquirido = null;
	public $tarefa_previsto = null;
	public $tarefa_realizado = null;
	public $tarefa_onde = null;
	public $tarefa_porque = null;
	public $tarefa_como = null;
	public $tarefa_custo = null;
	public $tarefa_gasto = null;
	public $tarefa_endereco1 ='';
	public $tarefa_endereco2 ='';
	public $tarefa_cidade ='';
	public $tarefa_estado ='';
	public $tarefa_cep ='';
	public $tarefa_pais ='';
	public $tarefa_latitude ='';
	public $tarefa_longitude ='';
	public $tarefa_emprego_obra = null;
	public $tarefa_emprego_direto = null;
	public $tarefa_emprego_indireto = null;
	public $tarefa_populacao_atendida = null;
	public $tarefa_forma_implantacao = null;
	public $tarefa_codigo = null;
	public $tarefa_sequencial = null;
	public $tarefa_setor = null;
	public $tarefa_segmento = null;
	public $tarefa_intervencao = null;
	public $tarefa_tipo_intervencao = null;
	public $tarefa_ano = null;
	public $tarefa_unidade = null;
	public $tarefa_numeracao = null;
	public $tarefa_gerenciamento = null;
	public $tarefa_situacao_atual = null;
	public $tarefas_subordinadas = null;
	public $tarefa_alerta = null;
	public $tarefa_projetoex_id = null;
	public $tarefa_tarefaex_id = null;
	public $incluir_subordinadas = false;
 	public $baseline_id = null;

	public function __construct( $baseline_id=null, $incluir_subordinadas=false) {
		$this->incluir_subordinadas=$incluir_subordinadas;
		if ($baseline_id) {
			$this->baseline_id=$baseline_id;
			parent::__construct('baseline_tarefas', 'tarefa_id','baseline_id');
			}
		else {
			parent::__construct('tarefas', 'tarefa_id');
			}
		}

	public function load( $oid = null, $tira = false, $id2 = null) {
		$carregado = parent::load($oid, $tira);

		if ($this->incluir_subordinadas) {
			$this->subordinadas(null, $this->baseline_id);
			$this->tarefas_subordinadas=implode(',', $this->tarefas_subordinadas);
			}
		else $this->tarefas_subordinadas=$this->tarefa_id;
		return $carregado;
		}

	public function armazenar( $atualizarNulos = false, $sem_chave_estrangeira=false) {
		global $Aplic;
		$sql = new BDConsulta;
		$this->arrumarTodos();
		$importando_tarefas = false;
		$msg = $this->check();
		if ($msg) {
			$msg_retorno = array(get_class($this).':: checagem de armazenamento', 'falhou', '-');
			if (is_array($msg)) return array_merge($msg_retorno, $msg);
			else {
				array_push($msg_retorno, $msg);
				return $msg_retorno;
				}
			}

    $this->tarefa_inicio_manual = $this->tarefa_inicio;
    $this->tarefa_fim_manual = $this->tarefa_fim;
    $this->tarefa_duracao_manual = $this->tarefa_duracao;
    
		if ($this->tarefa_id) {
			//atualizar

			$sql = new BDConsulta;
			$sql->adTabela('tarefas');
			$sql->adCampo('diferenca_data("'.$this->tarefa_fim.'", tarefa_fim)');
			$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
			$diferenca=$sql->Resultado();
			$sql->limpar();
			if ($this->tarefa_inicio == '') $this->tarefa_inicio = null;
			if ($this->tarefa_fim == '')	$this->tarefa_fim = null;


			$this->_acao = 'atualizada';
			global $oTar;
			$oTar = new CTarefa();
			$oTar->olhar((int)$this->tarefa_id);
			if ($this->tarefa_status != $oTar->tarefa_status) $this->atualizarStatusSubTarefas($this->tarefa_status);
			if ($this->tarefa_projeto != $oTar->tarefa_projeto) $this->atualizarSubTarefasProjeto($this->tarefa_projeto);
			$this->check();
			$ret = $sql->atualizarObjeto('tarefas', $this, 'tarefa_id', array('baseline_id', 'tarefas_subordinadas', 'incluir_subordinadas'));
			$sql->limpar();
			}
		else {
			$this->_acao = 'adicionada';
			if ($this->tarefa_inicio == '') $this->tarefa_inicio = null;
			if ($this->tarefa_fim == '') $this->tarefa_fim = null;
			$ret = $sql->inserirObjeto('tarefas', $this, 'tarefa_id');
			$sql->limpar();
			if (!$this->tarefa_superior) {
				$sql->adTabela('tarefas');
				$sql->adAtualizar('tarefa_superior', (int)$this->tarefa_id);
				$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
				$sql->exec();
				$sql->limpar();
				}
			else $importando_tarefas = true;
			$sql->adTabela('tarefa_designados');
			$sql->adInserir('usuario_id', $Aplic->usuario_id);
			$sql->adInserir('tarefa_id', (int)$this->tarefa_id);
			$sql->adInserir('usuario_admin', '0');
			$sql->exec();
			$sql->limpar();
			}

		$sql->setExcluir('tarefa_depts');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();

		$depts=getParam($_REQUEST, 'tarefa_depts', '');
		$depts=explode(',', $depts);
		if (count($depts)) {
			foreach ($depts as $secao) {
				if($secao){
					$sql->adTabela('tarefa_depts');
					$sql->adInserir('tarefa_id', (int)$this->tarefa_id);
					$sql->adInserir('departamento_id', $secao);
					if ($sem_chave_estrangeira) $sql->sem_chave_estrangeira();
					$sql->exec();
					$sql->limpar();
					}
				}
			}

		$sql->setExcluir('municipio_lista');
		$sql->adOnde('municipio_lista_tarefa='.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();

		$municipios=getParam($_REQUEST, 'tarefa_municipios', '');
		$municipios=explode(',', $municipios);
		if (count($municipios)) {
			foreach ($municipios as $municipio_id) {
				if ($municipio_id){
					$sql->adTabela('municipio_lista');
					$sql->adInserir('municipio_lista_tarefa', (int)$this->tarefa_id);
					$sql->adInserir('municipio_lista_projeto', (int)$this->tarefa_projeto);
					$sql->adInserir('municipio_lista_municipio', $municipio_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}

		$sql->setExcluir('tarefa_contatos');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();

		$contatos=getParam($_REQUEST, 'tarefa_contatos', '');
		$contatos=explode(',', $contatos);
		if (count($contatos)) {
			foreach ($contatos as $contato) {
				if($contato){
					$sql->adTabela('tarefa_contatos');
					$sql->adInserir('tarefa_id', (int)$this->tarefa_id);
					$sql->adInserir('contato_id', $contato);
					$sql->exec();
					$sql->limpar();
					}
				}
			}


		if ($Aplic->profissional){
		$sql->setExcluir('tarefa_cia');
		$sql->adOnde('tarefa_cia_tarefa='.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();
		$cias=getParam($_REQUEST, 'tarefa_cias', '');
		$cias=explode(',', $cias);
		if (count($cias)) {
			foreach ($cias as $cia_id) {
				if ($cia_id){
					$sql->adTabela('tarefa_cia');
					$sql->adInserir('tarefa_cia_tarefa', $this->tarefa_id);
					$sql->adInserir('tarefa_cia_cia', $cia_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		}


		if (isset($_REQUEST['uuid']) && $_REQUEST['uuid'] && $Aplic->profissional){
			$sql->adTabela('tarefa_entrega');
			$sql->adAtualizar('tarefa_entrega_tarefa', (int)$this->tarefa_id);
			$sql->adAtualizar('tarefa_entrega_uuid', null);
			$sql->adOnde('tarefa_entrega_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			
			
			
			
			$sql->adTabela('tarefa_bioma');
			$sql->adAtualizar('tarefa_bioma_tarefa', (int)$this->tarefa_id);
			$sql->adAtualizar('tarefa_bioma_uuid', null);
			$sql->adOnde('tarefa_bioma_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			
			
			
			$sql->adTabela('tarefa_comunidade');
			$sql->adAtualizar('tarefa_comunidade_tarefa', (int)$this->tarefa_id);
			$sql->adAtualizar('tarefa_comunidade_uuid', null);
			$sql->adOnde('tarefa_comunidade_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			
			
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('tarefas', $this->tarefa_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->tarefa_id);

		if (!$ret) return get_class($this).':: armazenamento falhou <br />'.db_error();
		else return null;
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('tarefa_id', null)==$this->tarefa_id) $Aplic->setEstado('tarefa_id', null);
		parent::excluir();
		$this->_acao = 'exclu�da';
		return null;
		}

	public function verificar_dependencia_circular( $tarefa_id=0, $possivel_dependencia=0){
		if (!$this->tarefa_id) $this->tarefa_id=$tarefa_id;
		$achou_circular=0;
		$this->dependentes((int)$this->tarefa_id, $achou_circular, $possivel_dependencia);
		return $achou_circular;
		}


	public function dependentes( $tarefa_id=0, &$achou_circular, $possivel_dependencia=0){
		if ($achou_circular) return true;
		$sql = new BDConsulta;
		$sql->adTabela('tarefa_dependencias');
		$sql->adCampo('dependencias_req_tarefa_id');
		$sql->adOnde('dependencias_tarefa_id='.(int)$tarefa_id);
		$dependencias=$sql->carregarColuna();
		if ($possivel_dependencia) $dependencias[]=$possivel_dependencia;
		$sql->limpar();
		foreach($dependencias as $chave => $dependente_id) {
			if ($this->tarefa_id==$dependente_id) {
				$achou_circular=1;
				break;
				}
			$this->dependentes($dependente_id, $achou_circular);
			}
		}



	public function __toString() {
		return $this->link.'/'.$this->type.'/'.$this->length;
		}

	public function check() {
		global $Aplic, $config;
		$this->tarefa_marco = null;
		$this->tarefa_dinamica = intval($this->tarefa_dinamica);
		$this->tarefa_percentagem = intval($this->tarefa_percentagem);
		$this->tarefa_custo_almejado = $this->tarefa_custo_almejado ? $this->tarefa_custo_almejado : 0.00;
		if (!$this->tarefa_criador) $this->tarefa_criador = $Aplic->usuario_id;
		if (!$this->tarefa_duracao_tipo) $this->tarefa_duracao_tipo = 1;
		static $editar;
		if (!isset($editar)) $editar=getParam($_REQUEST, 'fazerSQL', '') == 'fazer_tarefa_aed' ? true : false;
		$esta_dependencias = array();
		return null;
		}



	public function mudar_dependencia( $tarefas='', $dependencias=''){
		$tarefas=explode(',',$tarefas);
		$dependencias=explode(',',$dependencias);

		$subordinada_inicio=false;
		$subordinada_fim=false;

		$sql = new BDConsulta;
		$sql->setExcluir('tarefa_dependencias');
		$sql->adOnde('dependencias_tarefa_id = '.(int)$this->tarefa_id);
		$sql->exec();
		$sql->limpar();

		if ($this->tarefa_dinamica){

			$sql->adTabela('tarefas');
			$sql->adCampo('min(tarefa_inicio)');
			$sql->adOnde('tarefa_superior = '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id);
			$inicio=$sql->comando_sql();
			$sql->limpar();

			$sql->adTabela('tarefas');
			$sql->adCampo('max(tarefa_fim)');
			$sql->adOnde('tarefa_superior = '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id);
			$fim=$sql->comando_sql();
			$sql->limpar();


			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_id');
			$sql->adOnde('tarefa_superior = '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_inicio =('.$inicio.')');
			$subordinada_inicio=$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_id');
			$sql->adOnde('tarefa_superior = '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id);
			$sql->adOnde('tarefa_fim =('.$fim.')');
			$subordinada_fim=$sql->Resultado();
			$sql->limpar();
			}

		foreach($dependencias AS $chave => $valor){
			$qnt_latencia='';
			$tipo_latencia='';
			$dependencia=0;
			//verifico se tem latencia
			$valor=explode(':',$valor);
			if (isset($valor[1]) && $valor[1]) {
				$tipo_latencia=substr($valor[1],0,1);
				$qnt_latencia=substr($valor[1],1);
				}

			$dependencia_original=$tarefas[$chave];
			$tipo=$valor[0];

			//caso seja tarefa dinamica colocar dependencia filho
			if ($subordinada_inicio || $subordinada_fim){
				if(!$this->verificar_dependencia_circular(($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim), $dependencia_original)){

					//verificarse j� existe esta dependencia
					$sql->adTabela('tarefa_dependencias');
					$sql->adCampo('count(dependencias_tarefa_id)');
					$sql->adOnde('dependencias_tarefa_id = '. ($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim));
					$sql->adOnde('dependencias_req_tarefa_id = '.$dependencia_original);
					$existe=$sql->Resultado();
					$sql->limpar();

					if (!$existe && $dependencia_original && ($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim)){
						$sql->adTabela('tarefa_dependencias');
						$sql->adInserir('dependencias_tarefa_id', ($tipo=='TI' || $tipo=='II' ? $subordinada_inicio : $subordinada_fim));
						$sql->adInserir('dependencias_req_tarefa_id', $dependencia_original);
						$sql->adInserir('tipo_dependencia', $tipo);
						if ($qnt_latencia) $sql->adInserir('latencia', $qnt_latencia);
						if ($tipo_latencia) $sql->adInserir('tipo_latencia', $tipo_latencia);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			else if(!$this->verificar_dependencia_circular((int)$this->tarefa_id, $dependencia_original)){
				if ($dependencia_original){
					$sql->adTabela('tarefa_dependencias');
					$sql->adInserir('dependencias_tarefa_id', (int)$this->tarefa_id);
					$sql->adInserir('dependencias_req_tarefa_id', $dependencia_original);
					$sql->adInserir('tipo_dependencia', $tipo);
					if ($qnt_latencia) $sql->adInserir('latencia', $qnt_latencia);
					if ($tipo_latencia) $sql->adInserir('tipo_latencia', $tipo_latencia);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		}



	public function getQuantidadeAdquirida( $baseline_id=0, $pratica_indicador_id=null){
		return quantidadeAdquirida($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	public function getQuantidadePrevista( $baseline_id=0, $pratica_indicador_id=null){
		return quantidadePrevista($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	public function getQuantidadeRealizada( $baseline_id=0, $pratica_indicador_id=null){
		return quantidadeRealizada($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	public function getRealizadaPrevista( $baseline_id=0, $pratica_indicador_id=null){
		return realizadaPrevista($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	public function getAdquiridaPrevista( $baseline_id=0, $pratica_indicador_id=null){
		return adquiridaPrevista($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $pratica_indicador_id);
		}

	public function getEmpregosObra( $baseline_id=false, $indicador_id=false){
		global $Aplic;
		$lista='';
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('tarefa_emprego_obra');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}

	public function getEmpregosDiretos( $baseline_id=false, $indicador_id=false){
		global $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('tarefa_emprego_direto');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}

	public function getEmpregosIndiretos( $baseline_id=false, $indicador_id=false){
		global $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas');
		$sql->adCampo('tarefa_emprego_indireto');
		$sql->adOnde('tarefa_id='.(int)$this->tarefa_id);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$quantidade=$sql->Resultado();
		$sql->limpar();
		return $quantidade;
		}

	public function getTotalRecursosFinanceiros( $baseline_id=false, $indicador_id=false) {
		global $Aplic;

		$sql = new BDConsulta();
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'recurso_tarefa','recurso_tarefa');
		$sql->esqUnir('recursos','recursos','recurso_tarefa_recurso=recursos.recurso_id');
		$sql->esqUnir((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas','tarefas','tarefas.tarefa_id=recurso_tarefa_tarefa');
		$sql->adCampo('SUM(recurso_tarefa_quantidade)');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)){
			$sql->adOnde('recurso_tarefa.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			$sql->adOnde('tarefas.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
			}
		$sql->adOnde('recurso_tarefa_tarefa= '.(int)$this->tarefa_id);
		$sql->adOnde('recursos.recurso_tipo=5');
		$sql->adOnde('recurso_tarefa_aprovado=1');
		$total=$sql->Resultado();
		$sql->limpar();
		return $total;
		}





	public function recurso_previsto( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=false, $indicador_id=false){
		return recurso_previsto($this->tarefa_projeto, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function mao_obra_previsto( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return mao_obra_previsto($this->tarefa_projeto, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function mao_obra_gasto( $baseline_id=0){
		return mao_obra_gasto($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id));
		}

	public function homem_hora( $baseline_id=0, $indicador_id=false){
		return homem_hora($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function custo_previsto( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return custo_previsto($this->tarefa_projeto, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function financeiro_velocidade( $data_final='', $data_inicial='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return financeiro_velocidade($this->tarefa_projeto, $data_final, $data_inicial, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}


	public function pagamento( $baseline_id=0, $tipo=null, $no_ano=true, $inicio='', $fim=''){
		return pagamento($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $tipo, $no_ano, $inicio, $fim);
		}

	public function custo_gasto( $baseline_id=0){
		return custo_gasto($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id));
		}

	public function fisico_previsto( $data='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return fisico_previsto($this->tarefa_projeto, $data, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}


	public function fisico_velocidade( $data='', $ate_data_atual=true, $baseline_id=0, $indicador_id=false){
		return fisico_velocidade($this->tarefa_projeto, $data, $ate_data_atual, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}


	public function recurso_gasto( $baseline_id=false, $indicador_id=false){
		return recurso_gasto($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), $this->tarefa_id, $this->tarefas_subordinadas, $indicador_id);
		}

	public function recurso_valor_agregado( $baseline_id=false, $indicador_id=false){
		return recurso_valor_agregado($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function recurso_EPT( $baseline_id=false, $indicador_id=false){
		return recurso_EPT($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

 public function ata_acao( $baseline_id=false, $indicador_id=false){
		return ata_acao($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

 	public function mao_obra_valor_agregado( $baseline_id=0, $indicador_id=false){
		return mao_obra_valor_agregado($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function mao_obra_EPT( $baseline_id=0, $indicador_id=false){
		return mao_obra_EPT($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function custo_valor_agregado( $baseline_id=false, $indicador_id=false){
		return custo_valor_agregado($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function custo_EPT( $baseline_id=false, $indicador_id=false){
		return custo_EPT($this->tarefa_projeto, ($this->baseline_id ? $this->baseline_id : $baseline_id), ($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id), $indicador_id);
		}

	public function getCodigo( $completo=true){
		/*
		if ($this->tarefa_sequencial) $this->setSequencial();

		$sql = new BDConsulta;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_ano');
		$sql->adOnde('projeto_id='.$this->tarefa_projeto);
		$ano=$sql->Resultado();
		$sql->limpar();

		if ($this->tarefa_sequencial<10) $sequencial='000'.$this->tarefa_sequencial;
		elseif ($this->tarefa_sequencial<100) $sequencial='00'.$this->tarefa_sequencial;
		elseif ($this->tarefa_sequencial<1000) $sequencial='0'.$this->tarefa_sequencial;
		else $sequencial=$this->tarefa_sequencial;


		if ($this->tarefa_projeto<10) $id='000'.$this->tarefa_projeto;
		elseif ($this->tarefa_projeto<100) $id='00'.$this->tarefa_projeto;
		elseif ($this->tarefa_projeto<1000) $id='0'.$this->tarefa_projeto;
		else $id=$this->tarefa_projeto;

		if ($this->tarefa_setor && $sequencial){
			return $this->tarefa_setor.($completo && $this->tarefa_segmento ? '.' : '').substr($this->tarefa_segmento, 2).($completo && $this->tarefa_intervencao ? '.' : '').substr($this->tarefa_intervencao, 4).($completo && $this->tarefa_tipo_intervencao ? '.' : '').substr($this->tarefa_tipo_intervencao, 6).($completo ? '.' : '').$sequencial.($completo ? '/' : '').$id;
			}
		elseif ($this->tarefa_tipo && $this->tarefa_sequencial){
			return $this->tarefa_tipo.($completo ? '.' : '').$sequencial.($completo  ? '/'.$id : '');
			}
		else return '';
		*/
		return $this->tarefa_codigo;
		}


	public function setSequencial(){
		if (!$this->tarefa_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('tarefas');
			$sql->adCampo('max(tarefa_sequencial)');
			$sql->adOnde('tarefa_projeto='.(int)$this->tarefa_projeto);
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tarefas');
			$sql->adAtualizar('tarefa_sequencial', ($maior_sequencial+1));
			$sql->adOnde('tarefa_id ='.(int)$this->tarefa_id);
			$retorno=$sql->exec();
			$sql->limpar();
			$this->tarefa_sequencial=($maior_sequencial+1);
			return $retorno;
			}
		}

	public function getSetor(){
		if ($this->tarefa_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TarefaSetor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->tarefa_setor.'"');
			$tarefa_setor= $sql->Resultado();
			$sql->limpar();
			return $tarefa_setor;
			}
		else return '';
		}

	public function getSegmento(){
		if ($this->tarefa_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'TarefaSegmento\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->tarefa_segmento.'\'');
			$tarefa_segmento= $sql->Resultado();
			$sql->limpar();
			return $tarefa_segmento;
			}
		else return '';
		}

	public function getIntervencao(){
		if ($this->tarefa_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'TarefaIntervencao\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->tarefa_intervencao.'\'');
			$tarefa_intervencao= $sql->Resultado();
			$sql->limpar();
			return $tarefa_intervencao;
			}
		else return '';
		}

	public function getTipoIntervencao(){
		if ($this->tarefa_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo=\'TarefaTipoIntervencao\'');
			$sql->adOnde('sisvalor_valor_id=\''.$this->tarefa_tipo_intervencao.'\'');
			$tarefa_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $tarefa_tipo_intervencao;
			}
		else return '';
		}

	public function subordinadas( $tarefa_pai=0, $baseline_id=false){
		if (!$tarefa_pai) $tarefa_pai=(int)$this->tarefa_id;

		$this->tarefas_subordinadas[$tarefa_pai]=(int)$tarefa_pai;

		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefas', 'tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_superior ='.(int)$tarefa_pai.' AND tarefa_id!='.(int)$tarefa_pai);
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$lista=$sql->carregarColuna();
		$sql->limpar();
		foreach($lista as $chsve => $valor){
      if(!isset($this->tarefas_subordinadas[$valor])){
			  $this->tarefas_subordinadas[$valor]=(int)$valor;
			  $this->subordinadas($valor, ($this->baseline_id ? $this->baseline_id : $baseline_id));
        }
			}
		}

	public function custo_estimado( $baseline_id=false, $indicador_id=false){
		global $Aplic, $config;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_custos','tarefa_custos');
		$sql->adCampo('SUM((tarefa_custos_quantidade*tarefa_custos_custo*tarefa_custos_cotacao)*((100+tarefa_custos_bdi)/100)) AS total');
		$sql->adOnde('tarefa_custos_tarefa IN ('.($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id).')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
		$total=$sql->Resultado();
		$sql->limpar();
		return $total;

		}

	public function gasto_efetuado( $baseline_id=false, $indicador_id=false){
		global $Aplic, $config;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'tarefa_gastos', 'tarefa_gastos');
		$sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo*tarefa_gastos_cotacao)*((100+tarefa_gastos_bdi)/100)) AS total');
		$sql->adOnde('tarefa_gastos_tarefa IN ('.($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id).')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('tarefa_gastos.baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
		$total=$sql->Resultado();
		$sql->limpar();
		return $total;
		}

	public function gasto_registro( $baseline_id=false, $indicador_id=false){
		global $config, $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela((($this->baseline_id ? $this->baseline_id : $baseline_id) ? 'baseline_' : '').'log', 'log');
		$sql->esqUnir('custo','custo', 'custo_log=log.log_id');
		$sql->adCampo('SUM((custo_quantidade*custo_custo*custo_cotacao)*((100+custo_bdi)/100)) AS total');
		$sql->adOnde('custo_gasto=1');
		if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('log_aprovado = 1');
		$sql->adOnde('log_tarefa IN ('.($this->tarefas_subordinadas ? $this->tarefas_subordinadas : $this->tarefa_id).')');
		if (($this->baseline_id ? $this->baseline_id : $baseline_id)) $sql->adOnde('baseline_id='.(int)($this->baseline_id ? $this->baseline_id : $baseline_id));
		$total=$sql->Resultado();
		$sql->limpar();
		return $total;
		}

	public function olhar( $oid = null, $tira = false) {
		$meCarregue = $this->load($oid, $tira, true);
		return $meCarregue;
		}

	public function copiar( $destProjeto_id = 0, $destTarefa_id = -1) {
		$novoObj = $this->duplicar();
		if ($destProjeto_id != 0) $novoObj->tarefa_projeto = (int)$destProjeto_id;
		if ($destTarefa_id == 0) $novoObj->tarefa_superior = (int)$novoObj->tarefa_id;
		elseif ($destTarefa_id > 0) $novoObj->tarefa_superior = (int)$destTarefa_id;
		$novoObj->armazenar(false, true);
		return $novoObj;
		}

	public function copiaProfunda( $destProjeto_id = 0, $destTarefa_id = 0) {
		$subordinada = $this->getSubordinada();
		$novoObj = $this->copiar($destProjeto_id, $destTarefa_id);
		$novo_id = (int)$novoObj->tarefa_id;
		if (!empty($subordinada)) {
			$tempTarefa = new CTarefa();
			foreach ($subordinada as $sub) {
				$tempTarefa->olhar($sub);
				$tempTarefa->htmlDecodificar($sub);
				$novaSubordinada = $tempTarefa->copiaProfunda($destProjeto_id, $novo_id);
				$novaSubordinada->armazenar();
				}
			}
		return $novoObj;
		}

	public function mover( $destProjeto_id = 0, $destTarefa_id = -1) {
		if ($destProjeto_id != 0) $this->tarefa_projeto = $destProjeto_id;
		if ($destTarefa_id == 0) $this->tarefa_superior = (int)$this->tarefa_id;
		elseif ($destTarefa_id > 0) $this->tarefa_superior = (int)$destTarefa_id;
		}

	public function moverProfundo( $destProjeto_id = 0, $destTarefa_id = 0) {
		$this->mover($destProjeto_id, $destTarefa_id);
		$subordinada = $this->getSubordinadaProfunda();
		if (!empty($subordinada)) {
			$tempSubordinada = new CTarefa();
			foreach ($subordinada as $sub) {
				$tempSubordinada->olhar($sub);
				$tempSubordinada->htmlDecodificar($sub);
				$tempSubordinada->moverProfundo($destProjeto_id, (int)$this->tarefa_id);
				$tempSubordinada->armazenar();
				}
			}
		}





	public function notificarResponsavel( $comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;

		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_dono');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
		$linha = $sql->linha();
		$sql->limpar();
		$corpo_email='';
		if ($linha['usuario_id']) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser o respons�vel pel'.$config['genero_tarefa'].' '.$config['tarefa'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'',true, '', true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;
			$corpo_externo=$corpo;

			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
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
						$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
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
		$email = new Mail;

		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('tarefa_contatos');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = tarefa_contatos.contato_id');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
		$usuarios = $sql->Lista();
		$sql->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser um dos contatos para '.$config['genero_tarefa'].' '.$config['tarefa'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'',true, '', true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
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
							if ($linha['usuario_id']){
								$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
								$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
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


	public function notificar($comentario='', $nao_eh_novo=false){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;

		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('tarefa_designados');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_designados.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
		$usuarios = $sql->Lista();
		$sql->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser um dos designados para '.$config['genero_tarefa'].' '.$config['tarefa'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'',true, '', true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
			$validos=0;
			$email->Corpo($corpo_email, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
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
							$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
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



	public function notificar_novos( $comentario='', $nao_eh_novo=false, $lista_designados_antigo=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;

		$email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$sql->adTabela('tarefa_designados');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_designados.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->adCampo('usuarios.usuario_id, contato_email');
		if (count($lista_designados_antigo)) $sql->adOnde('tarefa_designados.usuario_id NOT IN ('.implode(',', $lista_designados_antigo).')');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
		$usuarios = $sql->Lista();
		$sql->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser um dos designados para '.$config['genero_tarefa'].' '.$config['tarefa'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'',true, '', true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
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
							$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
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




















	public function notificarResponsavelProjeto($nao_eh_novo, $tipo='gerente', $email_texto='') {
		global $Aplic, $config, $localidade_tipo_caract;

		if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
		elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
		else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;

		$sql = new BDConsulta;
		if ($tipo=='gerente'){
			$sql->adTabela('projetos', 'p');
			$sql->esqUnir('usuarios', 'o', 'o.usuario_id = p.projeto_responsavel');
			$sql->esqUnir('contatos', 'oc', 'oc.contato_id = o.usuario_contato');
			$sql->adCampo('p.projeto_id, oc.contato_email, o.usuario_id');
			$sql->adOnde('p.projeto_id = '.(int)$this->tarefa_projeto);
			$sql->adOnde('o.usuario_id != '.(int)$Aplic->usuario_id);
			$usuario = $sql->Linha();
			$sql->limpar();
			}
		elseif ($tipo=='supervisor'){
			$sql->adTabela('projetos', 'p');
			$sql->esqUnir('usuarios', 'o', 'o.usuario_id = p.projeto_supervisor');
			$sql->esqUnir('contatos', 'oc', 'oc.contato_id = o.usuario_contato');
			$sql->adCampo('p.projeto_id, oc.contato_email, o.usuario_id');
			$sql->adOnde('p.projeto_id = '.(int)$this->tarefa_projeto);
			$sql->adOnde('o.usuario_id != '.(int)$Aplic->usuario_id);
			$usuario = $sql->Linha();
			$sql->limpar();
			}
		elseif ($tipo=='autoridade'){
			$sql->adTabela('projetos', 'p');
			$sql->esqUnir('usuarios', 'o', 'o.usuario_id = p.projeto_autoridade');
			$sql->esqUnir('contatos', 'oc', 'oc.contato_id = o.usuario_contato');
			$sql->adCampo('p.projeto_id, oc.contato_email, o.usuario_id');
			$sql->adOnde('p.projeto_id = '.(int)$this->tarefa_projeto);
			$sql->adOnde('o.usuario_id != '.(int)$Aplic->usuario_id);
			$usuario = $sql->Linha();
			$sql->limpar();
			}
		elseif ($tipo=='cliente'){
			$sql->adTabela('projetos', 'p');
			$sql->esqUnir('usuarios', 'o', 'o.usuario_id = p.projeto_cliente');
			$sql->esqUnir('contatos', 'oc', 'oc.contato_id = o.usuario_contato');
			$sql->adCampo('p.projeto_id, oc.contato_email, o.usuario_id');
			$sql->adOnde('p.projeto_id = '.(int)$this->tarefa_projeto);
			$sql->adOnde('o.usuario_id != '.(int)$Aplic->usuario_id);
			$usuario = $sql->Linha();
			$sql->limpar();
			}

		$corpo='';
		$corpo_email='';
		$corpo_interno='';
		$corpo_externo='';
		if ($usuario['usuario_id']) {
			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';
			$corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser '.$config[$tipo].' d'.$config['genero_projeto'].' '.$config['projeto'].')<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

			if ($email_texto) $corpo .= '<br><br>'.$email_texto;

			$corpo_interno=$corpo;
			$corpo_externo=$corpo;
			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
			}

		if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno, '', $usuario['usuario_id']);

		if ($config['email_ativo']) {
			if ($Aplic->profissional){
				//texto diferente para gerente e supervisor
				require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
				$email = new Mail;
                $email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				if ($email->EmailValido($usuario['contato_email'])) {
					$email->Assunto($titulo, $localidade_tipo_caract);
					$endereco=link_email_externo($usuario['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
					$link='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
					$email->Corpo($corpo_externo.$link, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
					$email->Para($usuario['contato_email'], true);
					$email->Enviar();
					}
				}
			else{
				$email = new Mail;
                $email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				$email->Assunto($titulo, $localidade_tipo_caract);
				//texto igual para gerente e supervisor
				$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
				if ($email->EmailValido($usuario['contato_email'])) {
					$email->Para($usuario['contato_email'], true);
					$email->Enviar();
					}
				}
			}
		}





	public function notificarProjeto( $nao_eh_novo, $tipo='designados', $email_texto='', $contatos=''){
		global $Aplic, $config, $localidade_tipo_caract;
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$sql = new BDConsulta;
		$usuarios = array();
		if ($tipo=='designados'){
			$sql->adTabela('projeto_integrantes','pi');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = pi.contato_id');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = c.contato_id');
			$sql->adCampo('pi.contato_id, usuario_id, contato_email');
			$sql->adOnde('usuarios.usuario_id != '.(int)$Aplic->usuario_id);
			$sql->adOnde('pi.projeto_id = '.(int)$this->tarefa_projeto);
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		elseif ($tipo=='contatos'){
			$sql->adTabela('projeto_contatos', 'pc');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = c.contato_id');
			$sql->adCampo('c.contato_id, usuario_id, contato_email');
			$sql->adOnde('pc.projeto_id = '.(int)$this->tarefa_projeto);
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		elseif ($Aplic->profissional && $tipo=='stakeholders'){
			$sql->adTabela('projeto_stakeholder');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = projeto_stakeholder_contato');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = c.contato_id');
			$sql->adCampo('c.contato_id, usuario_id, contato_email');
			$sql->adOnde('projeto_stakeholder_projeto = '.(int)$this->tarefa_projeto);
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		elseif ($tipo=='outros'){
			$sql->adTabela('contatos', 'c');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = c.contato_id');
			$sql->adCampo('c.contato_id, usuario_id, contato_email');
			$sql->adOnde('c.contato_id IN ('.$contatos.')');
			$usuarios = $sql->Lista();
			$sql->limpar();
			}
		elseif ($tipo=='extras' && $contatos){
			$extras=explode(',',$contatos);
			foreach($extras as $chave => $valor) $usuarios[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}

		$corpo_email='';

		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo=ucfirst($config['tarefa']).' Excluid'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			elseif (intval($nao_eh_novo)) $titulo=ucfirst($config['tarefa']).' Atualizad'.$config['genero_tarefa'].': '.$this->tarefa_nome;
			else $titulo=ucfirst($config['tarefa']).' Criad'.$config['genero_tarefa'].': '.$this->tarefa_nome;

			if (intval($nao_eh_novo)) $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi atualizad'.$config['genero_tarefa'].'.</b><br>';
			else $corpo = '<b>'.ucfirst($config['genero_tarefa']).' '.ucfirst($config['tarefa']).' '.$this->tarefa_nome.' foi criad'.$config['genero_tarefa'].'.</b><br>';

			if ($tipo=='designados') $corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser um dos designados d'.$config['genero_projeto'].' '.$config['projeto'].')<br><br>';
			elseif ($tipo=='contatos') $corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser um dos contatos d'.$config['genero_projeto'].' '.$config['projeto'].')<br><br>';
			elseif ($tipo=='stakeholders') $corpo .= '<br><br>(Voc� est� recebendo este e-mail por ser um dos stakeholders d'.$config['genero_projeto'].' '.$config['projeto'].')<br><br>';

			if ($email_texto) $corpo .= '<br><br>'.$email_texto;

			$corpo .='<table border="1"><tr><td>'.link_tarefa($this->tarefa_id,'','','',true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons�vel pela exclus�o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador d'.$config['genero_tarefa'].' '.$config['tarefa'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


			$corpo_externo=$corpo;
			$corpo_interno=$corpo;
			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$this->tarefa_id.'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';

			$validos=0;
			$email->Corpo($corpo_email, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			foreach ($usuarios as $linha) {
				$corpo_interno=$corpo;
				if ($linha['usuario_id']) msg_email_interno ('', $titulo, $corpo_interno,'',$linha['usuario_id']);

				if ($email->EmailValido($linha['contato_email']) && $config['email_ativo']) {
					if ($Aplic->profissional){
						//texto diferente para gerente e supervisor
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
							$endereco=link_email_externo($linha['usuario_id'], 'm=tarefas&a=ver&tarefa_id='.$this->tarefa_id);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
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






















	public function email_log( &$log, $designados, $tarefa_contatos, $projeto_contatos, $outros, $extras) {
		global $Aplic, $localidade_tipo_caract, $config;
	  $sem_email_interno=0;
		$email_recipientes = array();
		$sql = new BDConsulta;
		if (isset($designados) && $designados) {
			$sql->adTabela('tarefa_designados', 'ut');
			$sql->esqUnir('usuarios', 'ua', 'ua.usuario_id = ut.usuario_id');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = ua.usuario_contato');
			$sql->adCampo('c.contato_email, c.contato_posto, c.contato_nomeguerra');
			$sql->adOnde('ut.tarefa_id = '.(int)$this->tarefa_id);
			if (!$Aplic->getPref('emailtodos')) $sql->adOnde('ua.usuario_id !='.(int)$Aplic->usuario_id);
			$req = $sql->Lista();
			$sql->limpar();
			foreach($req as $linha)	if ($linha['contato_email'] && !isset($email_recipientes[$linha['contato_email']])) $email_recipientes[$linha['contato_email']] = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']);
			}
		if (isset($tarefa_contatos) && $tarefa_contatos) {
			$sql->adTabela('tarefa_contatos', 'tc');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = tc.contato_id');
			$sql->adCampo('c.contato_email, c.contato_posto, c.contato_nomeguerra');
			$sql->adOnde('tc.tarefa_id = '.(int)$this->tarefa_id);
			$req = $sql->Lista();
			$sql->limpar();
			foreach($req as $linha)	if ($linha['contato_email'] && !isset($email_recipientes[$linha['contato_email']])) $email_recipientes[$linha['contato_email']] = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']);
			}
		if (isset($projeto_contatos) && $projeto_contatos) {
			$sql->adTabela('projeto_contatos', 'pc');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
			$sql->adCampo('c.contato_email, c.contato_posto, c.contato_nomeguerra');
			$sql->adOnde('pc.projeto_id = '.(int)$this->tarefa_projeto);
			$req = $sql->Lista();
			$sql->limpar();
			foreach($req as $linha)	if ($linha['contato_email'] && !isset($email_recipientes[$linha['contato_email']])) $email_recipientes[$linha['contato_email']] = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']);
			}
		if (isset($outros) && $outros) {
			$outros = trim($outros, " \r\n\t,");
			if (strlen($outros) > 0) {
				$sql->adTabela('contatos', 'c');
				$sql->adCampo('c.contato_email, c.contato_posto, c.contato_nomeguerra');
				$sql->adOnde('c.contato_id IN ('.$outros.')');
				$req = $sql->Lista();
				$sql->limpar();
				foreach($req as $linha)	if ($linha['contato_email'] && !isset($email_recipientes[$linha['contato_email']])) $email_recipientes[$linha['contato_email']] = ($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']);
				}
			}
		if (isset($extras) && $extras) {
			$extra_lista = preg_split('/[\s,;]+/', $extras);
			foreach ($extra_lista as $email_extra) {
				if ($email_extra && !isset($email_recipientes[$email_extra])) $email_recipientes[$email_extra] = $email_extra;
				}
			}
		if (count($email_recipientes) == 0) return false;
		$char_set = isset($localidade_tipo_caract) ? $localidade_tipo_caract : '';
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$email->Assunto($log->log_nome, $char_set);
		$titulo=$prefixo.' '.$log->log_nome;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_nome');
		$sql->adOnde('projeto_id='.(int)$this->tarefa_projeto);
		$nomeProjeto = htmlspecialchars_decode($sql->Resultado());
		$sql->limpar();
		$corpo = '<b>'.ucfirst($config['projeto']).':</b> '.$nomeProjeto.'<br>';
		if ($this->tarefa_superior != (int)$this->tarefa_id) {
			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_nome');
			$sql->adOnde('tarefa_id = '.(int)$this->tarefa_superior);
			$req = $sql->Resultado();
			$sql->limpar();
			if ($req) $corpo .= '<b>'.ucfirst($config['tarefa']).' Superior:</b> '.htmlspecialchars_decode($req).'<br>';

			}
		$corpo .= '<b>Tarefa:</b> '.$this->tarefa_nome.'<br>';
		$corpo .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$this->tarefa_id.'\');">Clique aqui para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</a><br><br>';
		$corpo .= '<b>Registro d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).'</b><br>';
		$corpo .= '<b>Sum�rio:</b> '.$log->log_nome.'<br>';
		$corpo .= '<b>Descri��o:</b> '.$log->log_descricao;
		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_rodape');
		$sql->adOnde('usuario_id = '.(int)$Aplic->usuario_id);
		$req = $sql->Resultado();
		$sql->limpar();
		if ($req)$corpo .= '<br><br>'.$req;
		$sql->limpar();
		$email->Corpo($corpo, $char_set);
		$lista_recipientes = '';
		foreach ($email_recipientes as $chave => $nome) {
			msg_email_interno($chave, $titulo, $corpo);
			if ($email->EmailValido($chave)) {
				$email->Para($chave, true);
				$lista_recipientes.= $chave.' ('.$nome.')<br>';
				}
			else $lista_recipientes .= 'e-mail \''.$chave.'\' para '.$nome.' inv�lido, n�o foi enviado.<br>';
			}
		if ($config['email_ativo']) $email->Enviar();

		return false;
		}

	public static function getTarefasParaPeriodo( $data_inicio, $data_fim, $usuario_id=0, $envolvimento=null, $cia_id = null, $dept_id=null,
    $projeto_id=null,
    $tarefa_id=null,
    $pg_perspectiva_id=null,
    $tema_id=null,
    $objetivo_id=null,
    $fator_id=null,
    $pg_estrategia_id=null,
    $pg_meta_id=null,
    $pratica_id=null,
    $pratica_indicador_id=null,
    $plano_acao_id=null,
    $canvas_id=null,
    $risco_id=null,
    $risco_resposta_id=null,
    $calendario_id=null,
    $monitoramento_id=null,
    $ata_id=null,
    $mswot_id=null,
    $swot_id=null,
    $operativo_id=null,
    $instrumento_id=null,
    $recurso_id=null,
    $problema_id=null,
    $demanda_id=null,
    $programa_id=null,
    $licao_id=null,
    $evento_id=null,
    $link_id=null,
    $avaliacao_id=null,
    $tgn_id=null,
    $brainstorm_id=null,
    $gut_id=null,
    $causa_efeito_id=null,
    $arquivo_id=null,
    $forum_id=null,
    $checklist_id=null,
    $agenda_id=null,
    $agrupamento_id=null,
    $patrocinador_id=null,
    $template_id=null,
    $painel_id=null,
    $painel_odometro_id=null,
    $painel_composicao_id=null,
    $tr_id=null,
    $me_id=null,
    $plano_acao_item_id=null,
    $beneficio_id=null,
    $painel_slideshow_id=null,
    $projeto_viabilidade_id=null,
    $projeto_abertura_id=null,
    $pg_id=null,
		$ssti_id=null,
		$laudo_id=null,
		$trelo_id=null,
		$trelo_cartao_id=null,
		$pdcl_id=null,
		$pdcl_item_id=null,
		$os_id=null) {
			
		global $Aplic;
		$sql = new BDConsulta;
		$db_inicio= $data_inicio->format('%Y-%m-%d %H:%M:%S');
		$db_fim = $data_fim->format('%Y-%m-%d %H:%M:%S');
		$tarefas_filtro = '';
		$sql->adTabela('tarefas', 't');
		if ($usuario_id) $sql->esqUnir('tarefa_designados', 'td', 't.tarefa_id=td.tarefa_id');
		$sql->esqUnir('projetos', 'projetos', 't.tarefa_projeto = projetos.projeto_id');

		if ($tarefa_id) $sql->adOnde('tarefas.tarefas_i='.(int)$tarefa_id);
		elseif ($projeto_id) $sql->adOnde('t.tarefa_projeto='.(int)$projeto_id);
		$sql->esqUnir('projeto_gestao','projeto_gestao','projeto_gestao_projeto = projetos.projeto_id');
		
		
		if ($pg_perspectiva_id) $sql->adOnde('projeto_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('projeto_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('projeto_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('projeto_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('projeto_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('projeto_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('projeto_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('projeto_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('projeto_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('projeto_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('projeto_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('projeto_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('projeto_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('projeto_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('projeto_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('projeto_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('projeto_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('projeto_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('projeto_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('projeto_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('projeto_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('projeto_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('projeto_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('projeto_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('projeto_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('projeto_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('projeto_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('projeto_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('projeto_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('projeto_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('projeto_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('projeto_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('projeto_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('projeto_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('projeto_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('projeto_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('projeto_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('projeto_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('projeto_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('projeto_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('projeto_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('projeto_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('projeto_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('projeto_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('projeto_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('projeto_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('projeto_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('projeto_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('projeto_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('projeto_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('projeto_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('projeto_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('projeto_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('projeto_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
		elseif ($os_id) $sql->adOnde('projeto_gestao_os IN ('.$os_id.')');	

		if ($Aplic->profissional) $sql->esqUnir('projeto_cia', 'projeto_cia', 'projeto_cia_projeto = projetos.projeto_id');
		$sql->esqUnir('tarefa_depts', '', 't.tarefa_id = tarefa_depts.tarefa_id');
		$sql->esqUnir('depts', '', 'depts.dept_id = tarefa_depts.departamento_id');
		$sql->adCampo('DISTINCT t.tarefa_id, t.tarefa_nome, t.tarefa_acesso, t.tarefa_inicio, t.tarefa_fim, t.tarefa_duracao'.', t.tarefa_duracao_tipo, projetos.projeto_id, projetos.projeto_cor AS cor, projetos.projeto_nome, t.tarefa_marco, tarefa_acao');
		$sql->adOnde('tarefa_status > -1 AND (tarefa_inicio <= \''.$db_fim.'\' AND (tarefa_fim >= \''.$db_inicio. '\' OR tarefa_fim = NULL))');
		if ($usuario_id) $sql->adOnde('(td.usuario_id='.(int)$usuario_id.' OR tarefa_dono='.(int)$usuario_id.')');
		
		
		if (!$envolvimento && $cia_id) $sql->adOnde('projetos.projeto_cia IN ('.$cia_id.')'.($Aplic->profissional ? ' OR projeto_cia_cia  IN ('.$cia_id.')' : ''));
		elseif ($cia_id) $sql->adOnde('projetos.projeto_cia IN ('.$cia_id.')');
		
		if ($dept_id) $sql->adOnde('tarefa_depts.departamento_id IN ('.$dept_id.')');
		
		if ($projeto_id) $sql->adOnde('projetos.projeto_id = '.(int)$projeto_id);
		
		$sql->adOrdem('t.tarefa_inicio');
		$resultado = $sql->Lista();
		$sql->limpar();
		return $resultado;
		}

	public function podeAcessar() {
		$valor=permiteAcessar($this->tarefa_acesso, $this->tarefa_projeto, (int)$this->tarefa_id);
		return $valor;
		}

	public function podeEditar() {
		return permiteEditar($this->tarefa_acesso, (int)$this->tarefa_projeto, (int)$this->tarefa_id);
		}


	public function cia_nome() {
		global $Aplic;
		$sql = new BDConsulta;
		$sql->adTabela('tarefas', 't');
		$sql->esqUnir('projetos', 'projetos', 't.tarefa_projeto = projetos.projeto_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = projetos.projeto_cia');
		$sql->adCampo('cia_nome');
		$resultado=$sql->Resultado();
		$sql->limpar();
		return $resultado;
		}




	public function getTarefaDuracaoPorDia( $usar_percentagem_designado = false) {
		$duracao = $this->tarefa_duracao * ($this->tarefa_duracao_tipo == 24 ? config('horas_trab_diario') : $this->tarefa_duracao_tipo);
		$tarefa_inicio = new CData($this->tarefa_inicio);
		$tarefa_data_termino = new CData($this->tarefa_fim);
		$usuarios_designados = $this->getUsuariosDesignados_Linha();
		if ($usar_percentagem_designado) {
			$numero_usuarios_designados = 0;
			foreach ($usuarios_designados as $u) $numero_usuarios_designados += ($u['perc_designado'] / 100);
			}
		else $numero_usuarios_designados = count($usuarios_designados);
		$dia_diferenca = $tarefa_data_termino->dataDiferenca($tarefa_inicio);
		$numero_dias_trabalhados = 0;
		$data_atual = $tarefa_inicio;
		for ($i = 0; $i <= $dia_diferenca; $i++) {
			if ($data_atual->serDiaUtil())	$numero_dias_trabalhados++;
			$data_atual->adDias(1);
			}
		if ($numero_dias_trabalhados == 0) $numero_dias_trabalhados = 1;
		if ($numero_usuarios_designados == 0) $numero_usuarios_designados = 1;
		return ($duracao / $numero_usuarios_designados) / $numero_dias_trabalhados;
		}

	public function getTarefaDuracaoPorSemana( $usar_percentagem_designado = false) {
		$duracao = $this->tarefa_duracao * ($this->tarefa_duracao_tipo == 24 ? config('horas_trab_diario') : $this->tarefa_duracao_tipo);
		$tarefa_inicio = new CData($this->tarefa_inicio);
		$tarefa_data_termino = new CData($this->tarefa_fim);
		$usuarios_designados = $this->getUsuariosDesignados_Linha();
		if ($usar_percentagem_designado) {
			$numero_usuarios_designados = 0;
			foreach ($usuarios_designados as $u) $numero_usuarios_designados += ($u['perc_designado'] / 100);
			}
		else $numero_usuarios_designados = count($usuarios_designados);
		$numero_semanas_trabalhadas = $tarefa_data_termino->nrDiasUteisNoEspaco($tarefa_inicio) / count(explode(',', config('cal_dias_uteis')));
		$numero_semanas_trabalhadas = (($numero_semanas_trabalhadas < 1) ? ceil($numero_semanas_trabalhadas) : $numero_semanas_trabalhadas);
		if ($numero_semanas_trabalhadas == 0) $numero_semanas_trabalhadas = 1;
		if ($numero_usuarios_designados == 0) $numero_usuarios_designados = 1;
		return ($duracao / $numero_usuarios_designados) / $numero_semanas_trabalhadas;
		}

	public function removerDesignado( $usuario_id) {
		$sql = new BDConsulta;
		$sql->setExcluir('tarefa_designados');
		$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id.' AND usuario_id = '.(int)$usuario_id);
		$sql->exec();
		$sql->limpar();
		}

	public function atualizarDesignados( $cslista, $perc_designado, $del = true, $rmUsuarios = false) {
		$sql = new BDConsulta;
		$tarr = explode(',', $cslista);
		if ($del == true && $rmUsuarios == true) {
			foreach ($tarr as $usuario_id) {
				$usuario_id = (int)$usuario_id;
				if (!empty($usuario_id))	$this->removerDesignado($usuario_id);
				}
			return false;
			}
		elseif ($del == true) {
			$sql->setExcluir('tarefa_designados');
			$sql->adOnde('tarefa_id = '.(int)$this->tarefa_id);
			$sql->exec();
			$sql->limpar();
			}
		$alocado = $this->getDesignacao('usuario_id');
		$sobrecarregado = false;
		foreach ($tarr as $usuario_id) {
			if (intval($usuario_id) > 0) {
				$perc = $perc_designado[$usuario_id];
				$sql->adTabela('tarefa_designados');
				$sql->adSubstituir('usuario_id', $usuario_id);
				$sql->adSubstituir('tarefa_id', (int)$this->tarefa_id);
				$sql->adSubstituir('perc_designado', $perc);
				$sql->exec();
				$sql->limpar();
				}
			}
		return $sobrecarregado;
		}

	public function getUsuariosDesignados_Linha() {
		$sql = new BDConsulta;
		$sql->adTabela('usuarios', 'u');
		$sql->esqUnir('tarefa_designados', 'ut', 'ut.usuario_id = u.usuario_id');
		$sql->esqUnir('contatos', 'co', ' co.contato_id = u.usuario_contato');
		$sql->adCampo('u.*, ut.perc_designado, ut.usuario_tarefa_prioridade, co.contato_nomeguerra, co.contato_posto');
		$sql->adOnde('ut.tarefa_id = '.(int)$this->tarefa_id);
		$resultado = $sql->ListaChave('usuario_id');
		$sql->limpar();
		return $resultado;
		}


	public function getResponsavel() {
		$sql = new BDConsulta;
		$sql->adTabela('usuarios', 'u');
		$sql->esqUnir('tarefas', 't', 't.tarefa_dono = u.usuario_id');
		$sql->esqUnir('contatos', 'co', ' co.contato_id = u.usuario_contato');
		$sql->adCampo('u.*, 100 AS perc_designado, t.tarefa_prioridade AS usuario_tarefa_prioridade, co.contato_nomeguerra, co.contato_posto');
		$sql->adOnde('t.tarefa_id = '.(int)$this->tarefa_id);
		$resultado = $sql->ListaChave('usuario_id');
		$sql->limpar();
		return $resultado;
		}

	public function getDesignacao( $hash = null, $usuarios = null, $get_lista_usuario = false, $cia_id=0) {
		global $Aplic;
		if ($get_lista_usuario) {
			$usuarios_lista = getListaUsuariosaLinha(null,null,'contato_posto_valor, contato_nomeguerra', $cia_id);
			foreach ($usuarios_lista as $chave => $usuario) $usuarios_lista[$chave]['usuarioFC'] = $usuario['contato_nome'];
			$hash = $usuarios_lista;
			}
		else $hash = array();
		return $hash;
		}

	public function getPrioridadeTarefaUsuarioEspecifico( $usuario_id = 0, $tarefa_id = null) {
		$sql = new BDConsulta;
		$tarefa_id = empty($tarefa_id) ? (int)$this->tarefa_id : $tarefa_id;
		$sql->adTabela('tarefa_designados');
		$sql->adCampo('usuario_tarefa_prioridade');
		$sql->adOnde('usuario_id = '.(int)$usuario_id.' AND tarefa_id = '.(int)$tarefa_id);
		$prioridade = $sql->Linha();
		$sql->limpar();
		return ($prioridade['usuario_tarefa_prioridade'] ? $prioridade['usuario_tarefa_prioridade'] : null);
		}

	public function atualizarUsuarioPrioridadeTarefa( $usuario_tarefa_prioridade = 0, $usuario_id = 0, $tarefa_id = null) {
		$sql = new BDConsulta;
		$tarefa_id = empty($tarefa_id) ? (int)$this->tarefa_id : $tarefa_id;
		$sql->adTabela('tarefa_designados');
		$sql->adSubstituir('usuario_id', $usuario_id);
		$sql->adSubstituir('tarefa_id', $tarefa_id);
		$sql->adSubstituir('usuario_tarefa_prioridade', $usuario_tarefa_prioridade);
		$sql->exec();
		$sql->limpar();
		}

	public function getProjeto() {
		$sql = new BDConsulta;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_nome, projeto_nome_curto, projeto_cor, projeto_descricao, projeto_id, projeto_cia, projeto_moeda');
		$sql->adOnde('projeto_id = '.(int)$this->tarefa_projeto);
		$projeto = $sql->Linha();
		$sql->limpar();
		return $projeto;
		}

	public function getSubordinada() {
		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_id != '.(int)$this->tarefa_id.' AND tarefa_superior = '.(int)$this->tarefa_id);
		$resultado = $sql->carregarColuna();
		$sql->limpar();
		return $resultado;
		}

	public function getSubordinadaProfunda() {
		$subordinada = $this->getSubordinada();
		if ($subordinada) {
			$subordinada_profunda = array();
			$tempTarefa = new CTarefa();
			foreach ($subordinada as $sub) {
				$tempTarefa->olhar($sub);
				$subordinada_profunda = array_merge($subordinada_profunda, $tempTarefa->getSubordinadaProfunda());
				}
			return array_merge($subordinada, $subordinada_profunda);
			}
		return array();
		}

	public function atualizarStatusSubTarefas( $novo_status, $tarefa_id = null) {
		$sql = new BDConsulta;
		if (is_null($tarefa_id)) $tarefa_id = (int)$this->tarefa_id;
		$sql->adTabela('tarefas');

		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$tarefas_id = $sql->carregarColuna();
		$sql->limpar();
		if (count($tarefas_id) == 0) 	return true;
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_status', $novo_status);
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$sql->exec();
		$sql->limpar();
		foreach ($tarefas_id as $id) {
			if ($id != $tarefa_id) $this->atualizarStatusSubTarefas($novo_status, $id);
			}
		}

	public function atualizarSubTarefasProjeto( $novo_projeto, $tarefa_id = null) {
		$sql = new BDConsulta;
		if (is_null($tarefa_id)) $tarefa_id = (int)$this->tarefa_id;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_id');
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$tarefas_id = $sql->carregarColuna();
		$sql->limpar();
		if (count($tarefas_id) == 0) return true;
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_projeto', $novo_projeto);
		$sql->adOnde('tarefa_superior = '.(int)$tarefa_id);
		$sql->exec();
		$sql->limpar();
		foreach ($tarefas_id as $id) {
			if ($id != $tarefa_id) 	$this->atualizarSubTarefasProjeto($novo_projeto, $id);
			}
		}

	public function adLembrete() {
		global $Aplic;

		$dia = 86400;
		if ($Apli->profissional || config('tarefa_controle_aviso')) return;
		if (!$this->tarefa_fim) {
			return $this->limparLembrete(true);
			}
		if ($this->tarefa_percentagem >= 100) return $this->limparLembrete(true);

		$eq = new EventoFila;
		$dias_antes = config('tarefa_aviso_dias_antes', 1);
		$repetir = config('tarefa_aviso_repetir', 0);
		$args = null;
		$lembretes_antigos = $eq->procurar('tarefas', 'lembrar', (int)$this->tarefa_id);
		if (count($lembretes_antigos)) {
			foreach ($lembretes_antigos as $antigo_id => $data_antiga) $eq->remover($antigo_id);
			}
		$data = new CData($this->tarefa_fim);
		$hoje = new CData(date('Y-m-d'));
		if ($data->compare($data, $hoje) < 0) $inicio_dia = time();
		else {
			$inicio_dia = $data->getData(DATE_FORMAT_UNIXTIME);
			$inicio_dia -= ($dia * $dias_antes);
			}
		$eq->adicionar(array($this, 'lembrar'), $args, 'tarefas', false, (int)$this->tarefa_id, 'lembrar', $inicio_dia, $dia, $repetir);
		}

	public function lembrar( $modulo, $tipo, $id, $responsavel, $args) {
		global $Aplic, $localidade_tipo_caract, $config;
		$sql = new BDConsulta;
	  $sem_email_interno=0;
		
		
		if (!$this->load($id)) return - 1;
		$this->htmlDecodificar();
		$hoje = new CData();
		if (!$hoje->serDiaUtil()) return true;
		if ($this->tarefa_percentagem == 100) return - 1;

		$sql->adTabela('tarefa_designados', 'ut');
		$sql->esqUnir('usuarios', 'u', 'u.usuario_id = ut.usuario_id');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email, u.usuario_id, cia_nome');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$sql->adOnde('ut.tarefa_id = '.(int)$id);
		$contatos = $sql->ListaChaveSimples('contato_id');
		$sql->limpar();

		$sql->adTabela('usuarios', 'u');
		$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('c.contato_id, contato_posto, contato_nomeguerra, contato_email, usuario_id, cia_nome');
		$sql->adOnde('u.usuario_id = '.(int)$this->tarefa_dono);
		$responsavel=$sql->linha();
		$sql->limpar();
		if (!isset($contatos[$responsavel['contato_id']])) {
			$contatos[$responsavel['contato_id']]=$responsavel;
			}


		$inicia = new CData($this->tarefa_inicio);
		$expira = new CData($this->tarefa_fim);
		$agora = new CData();
		$dif = $expira->dataDiferenca($agora);
		$dif *= $agora->compare($expira, $agora);
		$prefixo = ucfirst($config['tarefa']).' para';

		if ($dif == 0) $msg = 'hoje';
		elseif ($dif == 1) $msg = 'amanh�';
		elseif ($dif < 0) {
			$msg = 'atrasadas '.abs($dif).' dias';
			$prefixo = ucfirst($config['tarefa']);
			}
		else $msg = $dif.' dias';

		$projeto_nome = htmlspecialchars_decode(nome_projeto($this->tarefa_projeto));

		$assunto = ($prefixo ? $prefixo.' ' : '').$msg.' '.$this->tarefa_nome.' - '.$projeto_nome;
		$corpo='<b>Tarefas para:</b> '.$msg.'<br><b>'.ucfirst($config['projeto']).':</b> '.$projeto_nome.'<br><b>'.ucfirst($config['tarefa']).':</b> '.$this->tarefa_nome.'<br>';
		if ($this->tarefa_inicio) $corpo.='<b>Data de In�cio:</b> '.retorna_data($this->tarefa_inicio, true).'<br>';
		if ($this->tarefa_fim) $corpo.='<b>Data de T�rmino:</b> '.retorna_data($this->tarefa_fim, true).'<br>';
		$corpo.='<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.(int)$this->tarefa_id.'&lembrar=1\');">Clique aqui para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</a><br><br>';
		$designados='';
		foreach ($contatos as $contato) $designados.= $contato['contato_posto'].' '.$contato['contato_nomeguerra'].($contato['cia_nome'] ? ' - '.$contato['cia_nome'] : '').($contato['contato_email'] ? ' <'.$contato['contato_email'].'>' : '').'<br>';
		if ($designados) $corpo.='<b>Designados:</b><br>'.$designados;
		if ($this->tarefa_descricao) $corpo .= '<br><b>Descri��o:</b><br>'.$this->tarefa_descricao.'<br>';
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		$interno_enviado=0;
		foreach ($contatos as $contato) {
			$retorno_interno=msg_email_interno($contato['contato_email'], $assunto, $corpo, '', $contato['usuario_id']);
			if (!$retorno_interno) $interno_enviado++;
			if ($email->EmailValido($contato['contato_email'])) {
				$email->Para($contato['contato_email'], true);
				}
			}
		$email->Assunto($assunto, $localidade_tipo_caract);
		$email->Corpo($corpo, $localidade_tipo_caract);
		if ($config['email_ativo']) $retorno_externo=$email->Enviar();
		if ($interno_enviado || $retorno_externo) return true;
		}

	public function limparLembrete( $nao_checar = false) {
		$ev = new EventoFila;
		$evento_lista = $ev->procurar('tarefas', 'lembrar', (int)$this->tarefa_id);
		if (count($evento_lista)) {
			foreach ($evento_lista as $id => $data) {
				if ($nao_checar || $this->tarefa_percentagem >= 100) $ev->remover($id);
				}
			}
		}

	public function &getDesignado() {
		$sql = new BDConsulta;
		$sql->adTabela('usuarios', 'u');
		$sql->adTabela('tarefa_designados', 'ut');
		$sql->adTabela('contatos', 'con');
		$sql->adCampo('u.usuario_id, concatenar_quatro(contato_posto, \' \', contato_nomeguerra, concatenar_dois(perc_designado, \'%\'))');
		$sql->adOnde('ut.tarefa_id = '.(int)$this->tarefa_id);
		$sql->adOnde('usuario_contato = contato_id');
		$sql->adOnde('ut.usuario_id = u.usuario_id');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$designado = $sql->ListaChave();
		return $designado;
		}
	}

/********************************************************************************************

Classe CTarefaLog para manipula��o dos registros das tarefa

gpweb\modulos\tarefas\CTarefa.class.php

********************************************************************************************/
class CTarefaLog extends CAplicObjeto {
	public $log_id = null;
  public $log_tarefa = null;
  public $log_correcao = null;
  public $log_nome = null;
  public $log_descricao = null;
  public $log_criador = null;
  public $log_horas = null;
  public $log_data = null;
  public $log_custo = null;
  public $log_nd = null;
  public $log_categoria_economica = null;
  public $log_grupo_despesa = null;
  public $log_modalidade_aplicacao = null;
  public $log_corrigir = null;
  public $log_tipo_problema = null;
  public $log_referencia = null;
  public $log_url_relacionada = null;
  public $log_reg_mudanca_inicio = null;
  public $log_reg_mudanca_fim = null;
  public $log_reg_mudanca_duracao = null;
  public $log_reg_mudanca_percentagem = null;
  public $log_reg_mudanca_realizado = null;
	public $log_reg_mudanca_status = null;
	public $log_acesso = null;
	public $log_aprovou = null;
	public $log_aprovado = null;
	public $log_data_aprovado = null;
	public $log_tipo_oorrencia = null;

	public function __construct(){
		parent::__construct('log', 'log_id');
		$this->log_corrigir = intval($this->log_corrigir);
		}

	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->log_id) {
			$ret = $sql->atualizarObjeto('log', $this, 'log_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('log', $this, 'log_id');
			$sql->limpar();
			}


		if ($Aplic->profissional && isset($_REQUEST['uuid']) && $_REQUEST['uuid']){
			$sql->adTabela('custo');
			$sql->adAtualizar('custo_log', (int)$this->log_id);
			$sql->adAtualizar('custo_uuid', null);
			$sql->adOnde('custo_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			
			
			$sql->adTabela('log_entrega');
			$sql->adAtualizar('log_entrega_log', (int)$this->log_id);
			$sql->adAtualizar('log_entrega_uuid', null);
			$sql->adOnde('log_entrega_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			
			
			$sql->adTabela('log_bioma');
			$sql->adAtualizar('log_bioma_log', (int)$this->log_id);
			$sql->adAtualizar('log_bioma_uuid', null);
			$sql->adOnde('log_bioma_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('log_comunidade');
			$sql->adAtualizar('log_comunidade_log', (int)$this->log_id);
			$sql->adAtualizar('log_comunidade_uuid', null);
			$sql->adOnde('log_comunidade_uuid=\''.getParam($_REQUEST, 'uuid', null).'\'');
			$sql->exec();
			$sql->limpar();
			
			}


		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	public function getProjeto(){
		$sql = new BDConsulta;
		$sql->adTabela('tarefas', 't');
		$sql->adCampo('t.tarefa_projeto');
		$sql->adOnde('t.tarefa_id = '.$this->log_tarefa);
		$resultado = $sql->Resultado();
		return $resultado;
		}

	public function arrumarTodos(){
		$descricaoComEspacos = $this->log_descricao;
		parent::arrumarTodos();
		$this->log_descricao = $descricaoComEspacos;
		}

	public function check(){
		$this->log_horas = (float)$this->log_horas;
		return null;
		}


	public function notificar($post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_nome');
		$sql->adOnde('tarefa_id ='.$post['log_tarefa']);
		$tarefa_nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();
		$usuarios5=array();
		$usuarios6=array();

		if (isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('tarefa_designados');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = tarefa_designados.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tarefa_id='.$post['log_tarefa']);
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_tarefa_contatos']) && $post['email_tarefa_contatos']){
			$sql->adTabela('tarefa_contatos');
			$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = tarefa_contatos.contato_id');
			$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = contatos.contato_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tarefa_id='.$post['log_tarefa']);
			$usuarios2 = $sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_outro']) && $post['email_outro']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_outro'].')');
			$usuarios3=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['log_notificar_responsavel']) && $post['log_notificar_responsavel']){
			$sql->adTabela('tarefas');
			$sql->esqUnir('usuarios', 'usuarios', 'tarefas.tarefa_dono = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tarefa_id='.$post['log_tarefa']);
			$usuarios4=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_projeto_responsavel']) && $post['email_projeto_responsavel']){
			$sql->adTabela('projetos');
			$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_projeto = projetos.projeto_id');
			$sql->esqUnir('usuarios', 'usuarios', 'projetos.projeto_responsavel = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tarefa_id='.$post['log_tarefa']);
			$usuarios5=$sql->Lista();
			$sql->limpar();
			}


		if (isset($post['email_extras']) && $post['email_extras']){
			$extras=explode(',',$post['email_extras']);
			foreach($extras as $chave => $valor) $usuarios6[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}

		$usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios3);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios4);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios5);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios6);

		$usado_usuario=array();
		$usado_email=array();

		if (isset($post['del']) && $post['del'])$tipo='excluido';
		elseif (isset($post['log_id']) && $post['log_id']) $tipo='atualizado';
		else $tipo='incluido';

		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				if ($usuario['usuario_id']) $usado[$usuario['usuario_id']]=1;
				if ($usuario['contato_email']) $usado[$usuario['contato_email']]=1;
				$email = new Mail;
                $email->De($config['email'], $Aplic->usuario_nome);

                if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                    $email->ResponderPara($Aplic->usuario_email);
                    }
                else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                    $email->ResponderPara($Aplic->usuario_email2);
                    }

				if ($tipo == 'excluido') {
					$email->Assunto('Exclu�do registro de ocorr�ncia de '.$config['tarefa'], $localidade_tipo_caract);
					$titulo='Exclu�do registro de ocorr�ncia de '.$config['tarefa'];
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado registro de ocorr�ncia de '.$config['tarefa'], $localidade_tipo_caract);
					$titulo='Atualizado registro de ocorr�ncia de '.$config['tarefa'];
					}
				else {
					$email->Assunto('Inserido registro de ocorr�ncia de '.$config['tarefa'], $localidade_tipo_caract);
					$titulo='Inserido registro de ocorr�ncia de '.$config['tarefa'];
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado registro de ocorr�ncia d'.$config['genero_tarefa'].' '.$config['tarefa'].': '.$tarefa_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Exclu�do registro de ocorr�ncia d'.$config['genero_tarefa'].' '.$config['tarefa'].': '.$tarefa_nome.'<br>';
				else $corpo = 'Inserido registro de ocorr�ncia d'.$config['genero_tarefa'].' '.$config['tarefa'].': '.$tarefa_nome.'<br>';


				if ($tipo=='excluido') $corpo .= '<br><br><b>Respons�vel pela exclus�o do registro de ocorr�ncia:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Respons�vel pela edi��o do registro de ocorr�ncia:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do registro de ocorr�ncia:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				if ($Aplic->profissional){

						$corpo.='<br><br><b>Informa��es sobre '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'</b><br><br><table cellspacing=0 cellpadding=0>';

						//detalhes da tarefa
						$obj = new CTarefa();
						$obj->load($post['log_tarefa']);

						$custo_estimado=$obj->custo_estimado();
						$gasto_efetuado=$obj->gasto_efetuado();
						$gasto_registro=$obj->gasto_registro();
						$mao_obra_gasto=$obj->mao_obra_gasto();
						$mao_obra_previsto=$obj->mao_obra_previsto(date('Y-m-d H:i:s'),'', true);
						$mao_obra_previsto_total=$obj->mao_obra_previsto('','', false);
						$recurso_previsto=$obj->recurso_previsto(date('Y-m-d H:i:s'),'', true);
						$recurso_previsto_total=$obj->recurso_previsto('','', false);
						$custo_previsto=$obj->custo_previsto(date('Y-m-d H:i:s'),'', true);
						if ($obj->tarefa_cia) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">'.ucfirst($config['organizacao']).':</td><td>'.nome_cia($obj->tarefa_cia).'</td></tr>';
						if ($obj->tarefa_dono) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Respons�vel:</td><td>'.nome_usuario($obj->tarefa_dono,'','','esquerda').'</td></tr>';
						
						
						$data_inicio = intval($obj->tarefa_inicio) ? new CData($obj->tarefa_inicio) : null;
						$data_fim = intval($obj->tarefa_fim) ? new CData($obj->tarefa_fim) : null;
						if ($data_inicio) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">In�cio:</td><td width="300">'.$data_inicio->format('%d/%m/%Y %H:%M').'</td></tr>';
						if ($data_fim) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">T�rmino:</td><td width="300">'.$data_fim->format('%d/%m/%Y %H:%M').'</td></tr>';
						if ($data_inicio && $data_fim && !$obj->tarefa_marco && $obj->tarefa_percentagem > 0 && $obj->tarefa_percentagem < 100){
							//Quantas horas desde  a data de in�cio da tarefa
							$horas_faltando=((100-$obj->tarefa_percentagem)/100)*$obj->tarefa_duracao;
							$data=calculo_data_final_periodo(date('Y-m-d H:i:s'), $horas_faltando, $obj->tarefa_cia, null, $obj->tarefa_projeto, null, $post['log_tarefa']);
							$corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Previs�o:</td><td width="300">'.retorna_data($data).'</td></tr>';
							}
						if ($obj->tarefa_duracao) $corpo.='<tr><td align="right" style="white-space: nowrap" valign="top" style="width:110px;">Dura��o:</td><td width="300">'.number_format((float)$obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 2, ',', '.').' dia'.((float)$obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8) >= 2 ? 's' : '').'</td></tr>';
						if ($obj->tarefa_tipo) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Tipo:</td><td width="300">'.getSisValorCampo('TipoTarefa',$obj->tarefa_tipo).'</td></tr>';
						if ($obj->tarefa_emprego_obra) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Empregos durante a execu��o:</td><td width="300">'.$obj->tarefa_emprego_obra.'</td></tr>';
						if ($obj->tarefa_emprego_direto) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Empregos diretos ap�s conclus�o:</td><td width="300">'.$obj->tarefa_emprego_direto.'</td></tr>';
						if ($obj->tarefa_emprego_indireto) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Empregos indiretos ap�s conclus�o:</td><td width="300">'.$obj->tarefa_emprego_indireto.'</td></tr>';
						if ($obj->tarefa_forma_implantacao) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Forma de implanta��o:</td><td width="300">'.$obj->tarefa_forma_implantacao.'</td></tr>';
						if ($obj->tarefa_populacao_atendida) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Popula��o atendida:</td><td width="300">'.$obj->tarefa_populacao_atendida.'</td></tr>';
						$unidade= getSisValor('TipoUnidade');
						if ($obj->tarefa_adquirido!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Quantidade adquirida:</td><td width="300">'.number_format($obj->tarefa_adquirido, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
						if ($obj->tarefa_previsto!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Quantidade prevista:</td><td width="300">'.number_format($obj->tarefa_previsto, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
						if ($obj->tarefa_realizado!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Quantidade realizada:</td><td width="300">'.number_format($obj->tarefa_realizado, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
						if ($obj->tarefa_url_relacionada) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Endere�o URL:</td><td width="300"><a href="'.$obj->tarefa_url_relacionada.'" target="tarefa'.$post['log_tarefa'].'">'.$obj->tarefa_url_relacionada.'</a></td></tr>';
						if ($custo_estimado!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Custo estimado:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($custo_estimado, 2, ',', '.').'</td></tr>';
						if ($gasto_efetuado!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Gasto:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($gasto_efetuado, 2, ',', '.').'</td></tr>';
						if ($mao_obra_previsto!=0) $corpo.='<tr><td align="right" style="white-space: nowrap">M.O. estimada:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto, 2, ',', '.').'</td></tr>';
						if ($mao_obra_previsto_total!=0) $corpo.='<tr><td align="right" style="white-space: nowrap">Total M.O. estimada:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_previsto_total, 2, ',', '.').'</td></tr>';
						if ($mao_obra_gasto!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">M.O. gasta:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($mao_obra_gasto, 2, ',', '.').'</td></tr>';
						if ($recurso_previsto!=0) $corpo.='<tr><td align="right" style="white-space: nowrap">Recursos estimados:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto, 2, ',', '.').'</td></tr>';
						if ($recurso_previsto_total!=0) $corpo.='<tr><td align="right" style="white-space: nowrap">Recursos total estimado:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($recurso_previsto_total, 2, ',', '.').'</td></tr>';
						if ($gasto_registro!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Gastos extras:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($gasto_registro, 2, ',', '.').'</td></tr>';
						if ($custo_previsto != 0) $corpo.='<tr><td align="right" style="white-space: nowrap">Custo Previsto:</td><td width="100%">'.$config['simbolo_moeda'].' '.number_format($custo_previsto, 2, ',', '.').'</td></tr>';
						$financeiro_velocidade=$obj->financeiro_velocidade(date('Y-m-d H:i:s'),'', true);
						if ($financeiro_velocidade != 0) $corpo.='<tr><td align="right" style="white-space: nowrap">Vel. do financeiro:</td><td width="100%">'.number_format($financeiro_velocidade, 2, ',', '.').'</td></tr>';
						$total_estimado=$custo_estimado;
						$total_gasto=($gasto_efetuado+$gasto_registro+$mao_obra_gasto);
						if ($total_estimado!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Total estimado:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($total_estimado, 2, ',', '.').'</td></tr>';
						if ($total_gasto!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Total efetivo:</td><td width="300">'.$config['simbolo_moeda'].' '.number_format($total_gasto, 2, ',', '.').'</td></tr>';
						$provavel=($obj->tarefa_percentagem > 0 ? ($gasto_efetuado+$gasto_registro)/($obj->tarefa_percentagem/100) : ($gasto_efetuado+$gasto_registro)/0.01);
						if ($obj->tarefa_percentagem!=100 && $provavel!=0) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Custo prov�vel:</td><td width="300" '.($provavel > $custo_estimado ? 'style="color:#FF0000"' : '').'>'.$config['simbolo_moeda'].' '.number_format($provavel, 2, ',', '.').'</td></tr>';
						if (!$obj->tarefa_marco) $corpo.='<tr><td align="right" style="white-space: nowrap;width:110px;">Progresso:</td><td width="300">'.number_format($obj->tarefa_percentagem, 2, ',', '.').'%</td></tr>';
						if (!$obj->tarefa_marco){
							$corpo.='<tr><td align="right" style="white-space: nowrap">F�sico Previsto:</td><td width="100%">'.number_format($obj->fisico_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'%</td></tr>';
							$corpo.='<tr><td align="right" style="white-space: nowrap">Velocidade do f�sico:</td><td width="100%">'.number_format($obj->fisico_velocidade(date('Y-m-d H:i:s')), 2, ',', '.').'</td></tr>';
							}
						if ($obj->tarefa_descricao)	$corpo.='<tr><td align="right" style="white-space: nowrap" align="left" width="80">O Que:</td><td>'.$obj->tarefa_descricao.'</td></tr>';
						if ($obj->tarefa_porque)	$corpo.='<tr><td align="right" style="white-space: nowrap">Por Que:</td><td>'.$obj->tarefa_porque.'</td></tr>';
						if ($obj->tarefa_como)	$corpo.='<tr><td align="right" style="white-space: nowrap">Como:</td><td>'.$obj->tarefa_como.'</td></tr>';
						if ($obj->tarefa_onde)	$corpo.='<tr><td align="right" style="white-space: nowrap">Onde:</td><td>'.$obj->tarefa_onde.'</td></tr>';
						$corpo.='</table>';
						}

				$link_interno=($tipo!='excluido' ? '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tab=0&tarefa_id='.$post['log_tarefa'].'\');"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>' : '');

				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) msg_email_interno('', $titulo, $corpo.$link_interno,'',$usuario['usuario_id']);
				if ($config['email_ativo'] && $tipo!='excluido' && $usuario['usuario_id']!=$Aplic->usuario_id) {

					$corpo_externo=$corpo;

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

						if ($email->EmailValido($usuario['contato_email'])) {
							$email->Assunto($titulo, $localidade_tipo_caract);
							$endereco=link_email_externo($usuario['usuario_id'], 'm=tarefas&a=ver&tab=0&tarefa_id='.$post['log_tarefa']);
							$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tarefa'].' '.$config['tarefa'].'</b></a>';
							$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
							$email->Para($usuario['contato_email'], true);
							$email->Enviar();
							}
						}
					else{
						$email = new Mail;
						$email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }

						$email->Assunto($titulo, $localidade_tipo_caract);
						$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
						if ($email->EmailValido($usuario['contato_email'])) $email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}


	public function excluir( $oid = null){
		global $Aplic;
		if ($Aplic->getEstado('log_id', null)==$this->log_id) $Aplic->setEstado('log_id', null);
		parent::excluir();
		return null;
		}

	public function podeExcluir( &$msg='', $oid = null, $unioes = null) {
		global $Aplic;
		if (!$Aplic->checarModulo('log', 'excluir')) {
			$msg = 'Sem permiss�o para excluir';
			return false;
			}
		return true;
		}

	public function podeAcessar() {
		$valor=permiteAcessar($this->log_acesso, $this->getProjeto(), $this->log_tarefa);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditar($this->log_acesso, $this->getProjeto(), $this->log_tarefa);
		return $valor;
		}

	}









//funcoes gerais





function mostrarTarefaGrande($arr=array(), $projeto_id=0, $editar=true, $tarefa_superior=0, $baseline_id=false){
		global $Aplic, $config;
		$saida='';
		$agora = new CData();
		
		

		$AdicionarRegistro = $Aplic->checarModulo('log', 'adicionar');
		if ($Aplic->usuario_super_admin || permiteAcessar($arr['tarefa_acesso'], (int)$projeto_id, $arr['tarefa_id'])){
		$permiteEditar=($Aplic->usuario_super_admin || permiteEditar($arr['tarefa_acesso'],(int)$projeto_id, $arr['tarefa_id']));

		$data_inicio = intval($arr['tarefa_inicio']) ? new CData($arr['tarefa_inicio']) : null;
		$data_fim = intval($arr['tarefa_fim']) ? new CData($arr['tarefa_fim']) : null;
		$sinal = 1;
		$estilo = '';
		if ($data_inicio) {
			if (!$data_fim)	$data_fim = new CData();
			if ($agora->after($data_inicio) && $arr['tarefa_percentagem'] == 0 && $agora->before($data_fim)) $estilo = 'background-color:#ffeebb';
			else if ($agora->after($data_inicio) && $arr['tarefa_percentagem'] < 100 && $agora->before($data_fim)) $estilo = 'background-color:#e6eedd';
			else if ($arr['tarefa_percentagem'] == 100) $estilo = 'background-color:#aaddaa; color:#00000';
			else if ($agora->after($data_fim) && $arr['tarefa_percentagem'] < 100 ) $estilo = 'background-color:#cc6666;color:#ffffff';
			if ($agora->after($data_fim)) $sinal = -1;
			$dias = $agora->dataDiferenca($data_fim) * $sinal;
			}
		$saida.='<td>';
		$saida.=($editar ? dica('Editar '.ucfirst($config['tarefa']), 'Clique neste �cone '.imagem('icones/editar.gif').' para editar est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=editar&tarefa_id='.$arr['tarefa_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;');
		$saida.='</td>';
		$prefixo_marcada= $arr['tarefa_marcada'] ? '' : 'des';
		//$saida.='<td align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=tarefas&marcada='.($arr['tarefa_marcada'] ? 0 : 1).'&tarefa_id='.$arr['tarefa_id'].'\');">'.dica('Marcar', 'Clique neste �cone '.imagem('icones/'.$prefixo_marcada.'marcada.gif').' para '.($arr['tarefa_marcada'] ? 'des' : '').'marcar '.$config['genero_tarefa'].' '.$config['tarefa'].'.<br><br>'.ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' marcadas '.imagem('icones/marcada.gif').' s�o uma forma de chamar a aten��o.',True).'<img src="'.acharImagem('icones/'.$prefixo_marcada. 'marcada.gif').'" border=0 />'.dicaF().'</a></td>';
		if (isset($arr['log_corrigir']) && $arr['log_corrigir'] > 0) $saida.='<td align="center" valign="middle"><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$arr['tarefa_id'].'&tab=0&problem=1\');">'.imagem('icones/aviso.gif', imagem('icones/aviso.gif').' Problema', 'Foi registrado um problema nest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'<br>Clique neste �cone '.imagem('icones/aviso.gif').' para visualizar o registro.'). dicaF().'</a></td>';
		elseif ($arr['tarefa_dinamica'] != 1 && $permiteEditar && $AdicionarRegistro) $saida.='<td align="center" width="11">'.dica('Adicionar Registro', 'Clique neste �cone '.imagem('icones/adicionar.png').' para criar um novo registro nest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa']).'<a href="javascript:void(0);" onclick="popLog('.$arr['tarefa_id'].');">'.imagem('icones/adicionar.png').'</a>'.dicaF().'</td>';
		else $saida.='<td align="center">&nbsp;</td>';
		$saida.='<td align="center">'.dica('Percentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Neste campo � mostrado quantos por cento d'.$config['genero_tarefa'].' '.$config['tarefa'].' j� foi realizado.'). intval($arr['tarefa_percentagem']).'%'.dicaF().'</td>';
		$saida.='<td align="center" width="10px" style="white-space: nowrap">'.prioridade($arr['tarefa_prioridade']).'</td>';
		$saida.='<td>';

		if ($arr['tarefa_nr_subordinadas'] &&  $arr['tarefa_id']!=$tarefa_superior) $icone=($tarefa_superior && $arr['tarefa_id']!=$tarefa_superior ? '&nbsp;&nbsp;&nbsp;' : '').($arr['tarefa_superior']!=$arr['tarefa_id'] ? $icone=imagem('icones/subnivel.gif') : '').dica('Expandir '.ucfirst($config['tarefa']),'Clique no �cone '.imagem('icones/expandir.gif').' para expandir as subtarefas.').'<a href="javascript: void(0);" onclick="frm.tarefa_superior.value='.$arr['tarefa_id'].'; frm.submit();">'.imagem('icones/expandir.gif').'</a>'.dicaF();
		elseif ($arr['tarefa_nr_subordinadas'] &&  $arr['tarefa_id']==$tarefa_superior) $icone=($tarefa_superior && $arr['tarefa_id']!=$tarefa_superior ? '&nbsp;&nbsp;&nbsp;' : '').($arr['tarefa_superior']!=$arr['tarefa_id'] ? $icone=imagem('icones/subnivel.gif') : '').dica('Colapsar '.ucfirst($config['tarefa']),'Clique no �cone '.imagem('icones/colapsar.gif').' para colapsar as subtarefas.').'<a href="javascript: void(0);" onclick="frm.tarefa_superior.value='.($arr['tarefa_superior']!=$arr['tarefa_id'] ? $arr['tarefa_superior'] : 0).'; frm.submit();">'.imagem('icones/colapsar.gif').'</a>'.dicaF();
		else $icone=($tarefa_superior && $arr['tarefa_id']!=$tarefa_superior ? '&nbsp;&nbsp;&nbsp;' : '').($arr['tarefa_superior']!=$arr['tarefa_id'] ? $icone=imagem('icones/subnivel.gif') : '');
		$saida.=$icone;

		if ($arr['tarefa_marco'] > 0) $saida.='&nbsp;<b>'.link_tarefa($arr['tarefa_id']).'</b>'.dica('Marco de '.ucfirst($config['projeto']), '<ul><li>O marco pode ser vislumbrado como uma data chave de t�rmino de um grupo de  '.$config['tarefas'].'.</li><li>No gr�fico Gantt ser� visualizado como um los�ngulo <font color="#FF0000">&loz;</font> vermelho.</li></ul>'). '<img src="'.acharImagem('icones/marco.gif').'" border=0 />'.dicaF();
		elseif ($arr['tarefa_dinamica'] == '1') $saida.='&nbsp;<b><i>'.link_tarefa($arr['tarefa_id']).'</i></b>';
		else $saida.='&nbsp;'.link_tarefa($arr['tarefa_id']);

		$saida.=((isset($arr['nr_arquivos']) && $arr['nr_arquivos'] > 0) ? dica('Tem Anexo', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem '.$arr['nr_arquivos'].' anexo'.($arr['nr_arquivos'] > 1 ? 's.':'.')).imagem('icones/clip.png').dicaF() : '');
		$saida.=(isset($arr['tarefa_acao']) && $arr['tarefa_acao'] ? dica('A��o Social', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' � referente a uma a��o social.').imagem('../../../modulos/social/imagens/social_p.gif').dicaF() : '');
		$v = new BDConsulta;

		$obj = new CTarefa();
		$obj->tarefa_id=$arr['tarefa_id'];
		$custo=$obj->custo_estimado($baseline_id);
		if ($custo){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de custos estimados.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Custos</b></td><td>'.$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&tarefa_id='.$arr['tarefa_id'].'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no �cone '.imagem('icones/planilha_estimado.gif').' ver a planilha de custos estimados</td></tr>';
			$saida.=' '.dica('Valores', $dentro).$link.imagem('icones/planilha_estimado.gif').'</a>'.dicaF();
			}

		$gasto=$obj->gasto_efetuado($baseline_id);
		if ($gasto){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de gastos efetuados.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>gastos</b></td><td>'.$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&baseline_id='.$baseline_id.'&tarefa_id='.$arr['tarefa_id'].'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no �cone '.imagem('icones/planilha_gasto.gif').' ver a planilha de gastos efetuados</td></tr>';
			$saida.=' '.dica('Valores', $dentro).$link.imagem('icones/planilha_gasto.gif').'</a>'.dicaF();
			}

		$mao_obra_gasto=($Aplic->profissional ? $obj->mao_obra_gasto($baseline_id) : null);
		if ($mao_obra_gasto){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de gasto com m�o de obra.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>M�o de obra</b></td><td>'.$config['simbolo_moeda'].' '.number_format($mao_obra_gasto, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha_mao_obra&dialogo=1&tarefa_id='.$arr['tarefa_id'].'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no �cone '.imagem('icones/mo_estimado.gif').' ver a planilha de gastos com m�o de obra</td></tr>';
			$saida.=' '.dica('Valores', $dentro).$link.imagem('icones/mo_estimado.gif').'</a>'.dicaF();
			}
		$v->adTabela(($baseline_id ? 'baseline_' : '').'recurso_tarefa','recurso_tarefa', ($baseline_id ? 'recurso_tarefa.baseline_id='.(int)$baseline_id : ''));
		$v->adCampo('count(recurso_tarefa_recurso)');
		$v->adOnde('recurso_tarefa_tarefa = '.$arr['tarefa_id']);
		$qnt = $v->Resultado();
		$v->limpar();
		if ($qnt > 0) $saida.='<a href="javascript:void(0);" onclick="javascript:window.open(\'?m=tarefas&a=lista_recursos&dialogo=1&tarefa_id='.$arr['tarefa_id'].'\', \'Recursos\', \'width=790, height=470, left=0, top=0, scrollbars=yes, resizable=no\')">&nbsp;'.imagem('icones/recurso_estimado.gif','Recursos Alocados', 'H� aloca��o de '.$qnt.' recurso'.($qnt>1 ? 's' : '').' para '.($config['genero_tarefa']=='a' ? 'esta': 'este').' '.$config['tarefa'].'.<br><br>Clique no �cone '.imagem('icones/recurso_estimado.gif').' para visualizar').'</a>';
		$saida.='</td>';
		$saida.='<td style="white-space: nowrap" align="left" >&nbsp;'.link_usuario($arr['tarefa_dono'],'','','esquerda').'</td>';
		$v->adTabela('tarefa_designados');
		$v->adCampo('usuario_id, perc_designado');
		$v->adOnde('tarefa_id = '.$arr['tarefa_id']);
		$participantes = $v->lista();
		$v->limpar();
		$saida_quem='';
		if ($participantes && count($participantes)) {
				$saida_quem.= link_usuario($participantes[0]['usuario_id'], '','','esquerda');
				$qnt_participantes=count($participantes);
				if ($qnt_participantes > 1) {
						$lista='';
						for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').'<br>';
						$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar_grande(\'participantes_'.$arr['tarefa_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$arr['tarefa_id'].'"><br>'.$lista.'</span>';
						}
				}
		$saida.= '<td align="left" style="white-space: nowrap">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
		$saida.='<td id="ignore_td_'.$arr['tarefa_id'].'" style="white-space: nowrap" width="120px" align="center" style="'.$estilo.'">&nbsp;'.($data_inicio ? $data_inicio->format('%d/%m/%Y %H:%M') : '&nbsp;').'&nbsp;</td><td id="ignore_td_'.$arr['tarefa_id'].'" align="right" style="white-space: nowrap;'.$estilo.'">&nbsp;'.number_format((float)$arr['tarefa_duracao']/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 0, ',', '.').'&nbsp;</td><td width="120px" id="ignore_td_'.$arr['tarefa_id'].'" style="white-space: nowrap" align="center" style="'.$estilo.'">&nbsp;'.($data_fim ? $data_fim->format('%d/%m/%Y %H:%M') : '&nbsp;').'&nbsp;</td>';
		$saida.='<td id="ignore_td_'.$arr['tarefa_id'].'" style="white-space: nowrap" align="center" style="'.$estilo.'">'.$arr['dias'].'</td>';
		if ($config['editar_designado_diretamente']){
		 	$saida.= '<td align="center" width="10">'.($editar ? dica('Selecionar '.ucfirst($config['tarefa']), 'Marque esta caixa caso deseje deslocar as datas de in�cio e t�rmino d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.<ul><li>Ap�s ter terminado de marcar '.$config['genero_tarefa'].'s '.$config['tarefas'].' selecione a op��o de tempo na caixa de sele��o <b>deslocar</b> no canto inferior.').'<input type="checkbox" name="selecionado_tarefa['.$arr['tarefa_id'].']" value="'.$arr['tarefa_id'].'" onfocus="estah_marcado=true;" onblur="estah_marcado=false;" id="selecionado_tarefa_'.$arr['tarefa_id'].'" />'.dicaF() : '&nbsp;').'</td>';
			}
		$saida.='</tr>';
		}
	return $saida;
	}


function mostrarTarefa($arr, $nivel = 0, $aberta = true, $visao_hoje = false, $esconderAbrirFecharLink = false, $permitirRepitir = false, $baseline_id=false) {
	global $Aplic, $texto_consulta, $tipoDuracao, $usuarioDesig, $config ;
	global $m, $a, $historico_ativo;
	$AdicionarRegistro = $Aplic->checarModulo('log', 'adicionar');
	if ($Aplic->usuario_super_admin || permiteAcessar($arr['tarefa_acesso'], $arr['tarefa_projeto'], $arr['tarefa_id'])){
		$expandido = $Aplic->getPref('tarefasexpandidas');
		$agora = new CData();
		
		
		$podeEditar = ($Aplic->usuario_super_admin || $Aplic->checarModulo('tarefas','editar'));
		$permiteEditar=($Aplic->usuario_super_admin || permiteEditar($arr['tarefa_acesso'], $arr['tarefa_projeto'], $arr['tarefa_id']));
		$editar=($podeEditar&&$permiteEditar);
		if ($editar){
			if (($m=='projetos' && $a=='ver') ||($m=='tarefas' && $a=='index'))	$clique='selecionar_caixa(\'selecionado_tarefa\', \''.$arr['tarefa_id'].'\', \'projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_\',\'frmDesignar'.$arr['tarefa_projeto'].'\')';
			else $clique='selecionar_caixa(\'selecionado_tarefa\', \''.$arr['tarefa_id'].'\', \'projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_\',\'frm_tarefas\')';
			}
		else $clique='';
		$data_inicio = intval($arr['tarefa_inicio']) ? new CData($arr['tarefa_inicio']) : null;
		$data_fim = intval($arr['tarefa_fim']) ? new CData($arr['tarefa_fim']) : null;
		$sinal = 1;
		$estilo = '';
		if ($data_inicio) {
			if (!$data_fim)	$data_fim = new CData();
			if ($agora->after($data_inicio) && $arr['tarefa_percentagem'] == 0 && $agora->before($data_fim)) $estilo = 'background-color:#ffeebb';
			else if ($agora->after($data_inicio) && $arr['tarefa_percentagem'] < 100 && $agora->before($data_fim)) $estilo = 'background-color:#e6eedd';
			else if ($arr['tarefa_percentagem'] == 100) $estilo = 'background-color:#aaddaa; color:#00000';
			else if ($agora->after($data_fim) && $arr['tarefa_percentagem'] < 100 ) $estilo = 'background-color:#cc6666;color:#ffffff';
			if ($agora->after($data_fim)) $sinal = -1;
			$dias = $agora->dataDiferenca($data_fim) * $sinal;
			}
		if ($m=='projetos' && $a=='ver')	echo '<tr id="projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_" onmouseover="iluminar_tds(this, true, '.$arr['tarefa_id'].', '.$arr['tarefa_projeto'].')" onmouseout="iluminar_tds(this, false, '.$arr['tarefa_id'].', '.$arr['tarefa_projeto'].')" '.(($nivel > 0 && !$expandido) ? 'style="display:none"' : '').($clique ? ' onclick="'.$clique.'"' : '').'>';
		elseif ($m!='tarefas') echo '<tr id="projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_" onmouseover="iluminar_tds(this, true, '.$arr['tarefa_id'].')" onmouseout="iluminar_tds(this, false, '.$arr['tarefa_id'].')" '.(($nivel > 0 && !$expandido) ? 'style="display:none"' : '').($clique ? ' onclick="'.$clique.'"' : '').'>';
		else echo '<tr>';
		echo '<td align="center">';

		echo ($editar ? dica('Editar '.ucfirst($config['tarefa']), 'Clique neste �cone '.imagem('icones/editar.gif').' para editar est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa']).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=editar&tarefa_id='.$arr['tarefa_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;');
		echo '</td>';
		if (isset($arr['log_corrigir']) && $arr['log_corrigir'] > 0) echo '<td align="center" valign="middle"><a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$arr['tarefa_id'].'&tab=0&problem=1\');">'.imagem('icones/aviso.gif', imagem('icones/aviso.gif').' Problema', 'Foi registrado um problema nest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'<br>Clique neste �cone '.imagem('icones/aviso.gif').' para visualizar o registro.'). dicaF().'</a></td>';
		elseif ($AdicionarRegistro && $arr['tarefa_dinamica'] != 1 && $permiteEditar) echo '<td align="center" width="11">'.dica('Adicionar Registro', 'Clique neste �cone '.imagem('icones/adicionar.png').' para criar um novo registro dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa']).'<a href="javascript:void(0);" onclick="popLog('.$arr['tarefa_id'].');">'.imagem('icones/adicionar.png').'</a>'.dicaF().'</td>';
		else echo '<td align="center">&nbsp;</td>';
		echo'<td align="center">'.dica('Percentual d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Realizada', 'Neste campo � mostrado quantos por cento d'.$config['genero_tarefa'].' '.$config['tarefa'].' j� foi realizado.'). intval($arr['tarefa_percentagem']).'%'.dicaF().'</td>';
		echo '<td align="center" width="10px" style="white-space: nowrap">'.prioridade($arr['tarefa_prioridade']).'</td>';
		echo '<td>';
		if ($nivel == -1)	echo '...';
		for ($y = 0; $y < $nivel; $y++) {
			if ($y + 1 == $nivel)	echo '<img src="'.acharImagem('subnivel.gif').'" width="16" height="12" border=0>';
			else echo '<img src="'.acharImagem('shim.gif').'" width="16" height="12"  border=0>';
			}
		$abrir_link=($m!='tarefas' ? '<a href="javascript: void(0);">'.dica('Colapsar '.ucfirst($config['tarefa']),'Clique no �cone '.imagem('icones/colapsar.gif').' para colapsar as subtarefas.').'<img onclick="expandir_colapsar(\'projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_\', \'tblProjetos\',\'\','.($nivel+1).');'.$clique.'" id="projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'__colapsar" src="'.acharImagem('icones/colapsar.gif').'" border=0 align="center" '.(!$expandido?'style="display:none"':'').' />'.dicaF().dica('Expandir '.ucfirst($config['tarefa']),'Clique no �cone '.imagem('icones/expandir.gif').' para expandir as subtarefas.').'<img onclick="expandir_colapsar(\'projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'_\', \'tblProjetos\',\'\','.($nivel+1).'); '.$clique.'"  id="projeto_'.$arr['tarefa_projeto'].'_nivel>'.$nivel.'<tarefa_'.$arr['tarefa_id'].'__expandir" src="'.acharImagem('icones/expandir.gif').'" border=0 align="center" '.($expandido?'style="display:none"':'').' /></a>'.dicaF() : '');
		if (isset($arr['tarefa_nr_subordinadas']) && $arr['tarefa_nr_subordinadas']) $superior = true;
		else $superior = false;
		if ($arr['tarefa_marco'] > 0) echo '&nbsp;<b>'.link_tarefa($arr['tarefa_id']).'</b>'.dica('Marco de '.ucfirst($config['projeto']), '<ul><li>O marco pode ser vislumbrado como uma data chave de t�rmino de um grupo de  '.$config['tarefas'].'.</li><li>No gr�fico Gantt ser� visualizado como um los�ngulo <font color="#FF0000">&loz;</font> vermelho.</li></ul>'). '<img src="'.acharImagem('icones/marco.gif').'" border=0 />'.dicaF();
		elseif ($arr['tarefa_dinamica'] == '1' || $superior) {
				if (!$visao_hoje) echo $abrir_link;
				if ($arr['tarefa_dinamica'] == '1') echo '&nbsp;<b><i>'.link_tarefa($arr['tarefa_id']).'</i></b>';
				else echo '&nbsp;'.link_tarefa($arr['tarefa_id']);
				}
		else echo '&nbsp;'.link_tarefa($arr['tarefa_id']);
		echo ((isset($arr['nr_arquivos']) && $arr['nr_arquivos'] > 0) ? dica('Tem Anexo', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem '.$arr['nr_arquivos'].' anexo'.($arr['nr_arquivos'] > 1 ? 's.':'.')).imagem('icones/clip.png').dicaF() : '');

		echo (isset($arr['tarefa_acao']) && $arr['tarefa_acao'] ? dica('A��o Social', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' � referente a uma a��o social.').imagem('../../../modulos/social/imagens/social_p.gif').dicaF() : '');


		$obj = new CTarefa();
		$obj->tarefa_id=$arr['tarefa_id'];
		$custo=$obj->custo_estimado($baseline_id);
		if ($custo){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de custos estimados.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Custos</b></td><td>'.$config['simbolo_moeda'].' '.number_format($custo, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&baseline_id='.$baseline_id.'&tarefa_id='.$arr['tarefa_id'].'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no �cone '.imagem('icones/planilha_estimado.gif').' ver a planilha de custos estimados</td></tr>';
			echo ' '.dica('Valores', $dentro).$link.imagem('icones/planilha_estimado.gif').'</a>'.dicaF();
			}

		$gasto=$obj->gasto_efetuado($baseline_id);
		if ($gasto){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de gastos efetuados.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>gastos</b></td><td>'.$config['simbolo_moeda'].' '.number_format($gasto, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha&dialogo=1&baseline_id='.$baseline_id.'&tarefa_id='.$arr['tarefa_id'].'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no �cone '.imagem('icones/planilha_gasto.gif').' ver a planilha de gastos efetuados</td></tr>';
			echo ' '.dica('Valores', $dentro).$link.imagem('icones/planilha_gasto.gif').'</a>'.dicaF();
			}

		$mao_obra_gasto=($Aplic->profissional ? $obj->mao_obra_gasto($baseline_id) : null);

		if ($mao_obra_gasto){
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td colspan="2">Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' tem valores na planilha de gasto com m�o de obra.</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>M�o de obra</b></td><td>'.$config['simbolo_moeda'].' '.number_format($mao_obra_gasto, 2, ',', '.').'</td></tr>';
			$link='<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=planilha_mao_obra&baseline_id='.$baseline_id.'&dialogo=1&tarefa_id='.$arr['tarefa_id'].'\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">';
			$dentro .= '<tr><td colspan="2">Clique para no �cone '.imagem('icones/mo_estimado.gif').' ver a planilha de gastos com m�o de obra</td></tr>';
			echo ' '.dica('Valores', $dentro).$link.imagem('icones/mo_estimado.gif').'</a>'.dicaF();
			}


		$v = new BDConsulta;
		$v->adTabela(($baseline_id ? 'baseline_' : '').'recurso_tarefa','recurso_tarefa', ($baseline_id ? 'recurso_tarefa.baseline_id='.(int)$baseline_id : ''));
		$v->adCampo('count(recurso_tarefa_recurso)');
		$v->adOnde('recurso_tarefa_tarefa = '.$arr['tarefa_id']);
		$qnt = $v->Resultado();
		$v->limpar();

		if ($qnt > 0) echo'<a href="javascript:void(0);" onclick="javascript:window.open(\'?m=tarefas&a=lista_recursos&baseline_id='.$baseline_id.'&dialogo=1&tarefa_id='.$arr['tarefa_id'].'\', \'Recursos\', \'width=790, height=470, left=0, top=0, scrollbars=yes, resizable=no\')">&nbsp;'.imagem('icones/recurso_estimado.gif','Recursos Alocados', 'H� aloca��o de '.$qnt.' recurso'.($qnt>1 ? 's' : '').' para '.($config['genero_tarefa']=='a' ? 'esta': 'este').' '.$config['tarefa'].'.<br><br>Clique no �cone '.imagem('icones/recurso_estimado.gif').' para visualizar').'</a>';

		echo '</td>';
		if ($visao_hoje)  echo '<td align="left">'.link_projeto($arr['tarefa_projeto'], 'cor').'</td>';
		else echo '<td style="white-space: nowrap" align="left" >&nbsp;'.link_usuario($arr['tarefa_dono'],'','','esquerda').'</td>';
		if (isset($arr['tarefa_designado_usuarios']) && ($usuarios_designados = $arr['tarefa_designado_usuarios'])) {
			$a_u_vetor_tmp = array();

			echo '<td align="left" style="white-space: nowrap">&nbsp;'.link_usuario($usuarios_designados[0]['usuario_id'],'','','esquerda').' ('.$usuarios_designados[0]['perc_designado'].'%)&nbsp;';
			if (count($usuarios_designados) > 1) {
				$lista='';
				echo dica('Outros '.ucfirst($config['usuarios']).' Designados', 'Clique para ver os demais designados.').' <a href="javascript: void(0);" onclick="ativar_usuarios('."'usuarios_".$arr['tarefa_id']."'".'); '.$clique.'">(+'.(count($usuarios_designados) - 1).')</a>'.dicaF(). '<span style="display: none" id="usuarios_'.$arr['tarefa_id'].'">';
				$a_u_vetor_tmp[] = $usuarios_designados[0]['usuario_id'];
				for ($i = 1, $i_cmp = count($usuarios_designados); $i < $i_cmp; $i++) {
					$a_u_vetor_tmp[] = $usuarios_designados[$i]['usuario_id'];
					echo '<br />&nbsp;'.link_usuario($usuarios_designados[$i]['usuario_id'], '','','esquerda').' ('.$usuarios_designados[$i]['perc_designado'].'%)';
					}
				echo '</span>';
				}
			echo '</td>';

			}
		elseif (!$visao_hoje) echo '<td align="center">&nbsp;</td>';
		echo '<td id="ignore_td_'.$arr['tarefa_id'].'" style="white-space: nowrap" width="120px" align="center" style="'.$estilo.'">&nbsp;'.($data_inicio ? $data_inicio->format('%d/%m/%Y %H:%M') : '&nbsp;').'&nbsp;</td><td id="ignore_td_'.$arr['tarefa_id'].'" align="right" style="white-space: nowrap;'.$estilo.'">&nbsp;'.number_format((float)$arr['tarefa_duracao']/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 0, ',', '.').'&nbsp;</td><td width="120px" id="ignore_td_'.$arr['tarefa_id'].'" style="white-space: nowrap" align="center" style="'.$estilo.'">&nbsp;'.($data_fim ? $data_fim->format('%d/%m/%Y %H:%M') : '&nbsp;').'&nbsp;</td>';
		if ($visao_hoje) echo '<td id="ignore_td_'.$arr['tarefa_id'].'" style="white-space: nowrap" align="center" style="'.$estilo.'">'.($arr['tarefa_fazer_em'] > 0 ? $arr['tarefa_fazer_em'] : dica('Prazo Expirou', 'Est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' deveria ter sido completada h� '.($arr['tarefa_fazer_em']*-1). ' dias atr�s.').'expirou'.dicaF()).'</td>';
		echo '<td id="ignore_td_'.$arr['tarefa_id'].'" style="white-space: nowrap" align="center" style="'.$estilo.'">'.$arr['dias'].'</td>';
		if ($config['editar_designado_diretamente']){
			if ($editar && $m=='projetos') echo '<td align="center" width="10">'.dica('Selecionar '.ucfirst($config['tarefa']), 'Marque esta caixa caso deseje deslocar as datas de in�cio e t�rmino d'.$config['genero_tarefa'].'s '.$config['tarefas'].'.<ul><li>Ap�s ter terminado de marcar '.$config['genero_tarefa'].'s '.$config['tarefas'].' selecione a op��o de tempo na caixa de sele��o <b>deslocar</b> no canto inferior.').'<input type="checkbox" name="selecionado_tarefa['.$arr['tarefa_id'].']" value="'.$arr['tarefa_id'].'"  onclick="'.$clique.'" onfocus="estah_marcado=true;" onblur="estah_marcado=false;" id="selecionado_tarefa_'.$arr['tarefa_id'].'" />'.dicaF().'</td>';
			elseif ($m!='tarefas') echo '<td align="center">&nbsp;</td>';
			}
		echo '</tr>';
		}
	}

function acharSubordinada($tarr, $superior, $nivel = 0, $baseline_id=false, $filhos=array()) {
	global $tarefas_mostradas, $expandido;
	$nivel = $nivel + 1;
	if (isset($filhos[$superior])){
		foreach ($filhos[$superior] as $tarefa_id) {
			mostrarTarefa($tarr[$tarefa_id], $nivel, true, false, false, false, $baseline_id);
			$tarefas_mostradas[] = $tarefa_id;
			acharSubordinada($tarr, $tarefa_id, $nivel, $baseline_id, $filhos);
			}
		}
	}

function vetor_ordenar() {
	$args = func_get_args();
	$mvetor = array_shift($args);
	if (empty($mvetor)) return array();
	$i = 0;
	$mLinhaOrdenada = 'return(array_multisort(';
	$vetorOrdenado = array();
	foreach ($args as $arg) {
		$i++;
		if (is_string($arg)) {
			for ($j = 0, $j_cmp = count($mvetor); $j < $j_cmp; $j++) {
				if (!$mvetor[$j]['tarefa_fim']) $mvetor[$j]['tarefa_fim'] = calcFimPorInicioEDuracao($mvetor[$j]);
				if (isset($mvetor[$j][$arg])) $vetorOrdenado[$i][] = $mvetor[$j][$arg];
				else  $vetorOrdenado[$i][] ='';
				}
			}
		else $vetorOrdenado[$i] = $arg;
		$mLinhaOrdenada .= '$vetorOrdenado['.$i.'],';
		}
	$mLinhaOrdenada .= '$mvetor));';
	eval($mLinhaOrdenada);
	return $mvetor;
	}

function calcFimPorInicioEDuracao($tarefa) {
	$data_fim = new CData($tarefa['tarefa_inicio']);
	$data_fim->adSegundos($tarefa['tarefa_duracao'] * $tarefa['tarefa_duracao_tipo'] * 3600);
	return $data_fim->format('%Y-%m-%d %H:%M:%S');
	}

function ordenar_por_item_titulo($titulo, $item_nome, $item_tipo, $a = '') {
	global $Aplic, $projeto_id, $tarefa_id, $ver_min, $m;
	global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
	global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;
	if ($tarefa_ordenar_item2 == $item_nome) $item_ordem = $tarefa_ordenar_ordem2;
	if ($tarefa_ordenar_item1 == $item_nome) $item_ordem = $tarefa_ordenar_ordem1;
	$s = '';
	if (isset($item_ordem)) $mostrar_icone = true;
 	else {
		$mostrar_icone = false;
		$item_ordem = SORT_DESC;
		}
	$item_ordem = ($item_ordem == SORT_ASC) ? SORT_DESC : SORT_ASC;
	if ($m == 'tarefas') $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas'.(($tarefa_id > 0) ? ('&a=ver&tarefa_id='.$tarefa_id) : $a);
	elseif ($m == 'calendario') $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=ver_dia';
	else $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&bypass=1'.($projeto_id > 0 ? '&a=ver&projeto_id='.$projeto_id : '');
	$s .= '&tarefa_ordenar_item1='.$item_nome;
	$s .= '&tarefa_ordenar_tipo1='.$item_tipo;
	$s .= '&tarefa_ordenar_ordem1='.$item_ordem;
	if ($tarefa_ordenar_item1 == $item_nome) {
		$s .= '&tarefa_ordenar_item2='.$tarefa_ordenar_item2;
		$s .= '&tarefa_ordenar_tipo2='.$tarefa_ordenar_tipo2;
		$s .= '&tarefa_ordenar_ordem2='.$tarefa_ordenar_ordem2;
		}
	else {
		$s .= '&tarefa_ordenar_item2='.$tarefa_ordenar_item1;
		$s .= '&tarefa_ordenar_tipo2='.$tarefa_ordenar_tipo1;
		$s .= '&tarefa_ordenar_ordem2='.$tarefa_ordenar_ordem1;
		}
	$s .= '\');" class="hdr">'.$titulo;
	if ($mostrar_icone) $s .= '&nbsp;<img src="'.acharImagem('icones/seta-'.(($item_ordem == SORT_ASC) ? 'cima' : 'baixo').'.gif').'" border=0 /></a>';
	return $s;
	}


function ordenar_por_titulo($titulo, $item_nome, $item_tipo, $m= '', $a = '', $usuario_id='',$dept_id='') {
	global $Aplic, $projeto_id, $tarefa_id, $ver_min, $m;
	global $tarefa_ordenar_item1, $tarefa_ordenar_tipo1, $tarefa_ordenar_ordem1;
	global $tarefa_ordenar_item2, $tarefa_ordenar_tipo2, $tarefa_ordenar_ordem2;
	if ($tarefa_ordenar_item2 == $item_nome) $item_ordem = $tarefa_ordenar_ordem2;
	if ($tarefa_ordenar_item1 == $item_nome) $item_ordem = $tarefa_ordenar_ordem1;
	$s = '';
		if (isset($item_ordem)) $mostrar_icone = true;
 	else {
		$mostrar_icone = false;
		$item_ordem = SORT_DESC;
		}
	$item_ordem = ($item_ordem == SORT_ASC) ? SORT_DESC : SORT_ASC;
	$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&bypass=1&'.$a.($usuario_id ? '&usuario_id='.$usuario_id : '').($dept_id ? '&dept_id='.$dept_id : '');
	$s .= '&tarefa_ordenar_item1='.$item_nome;
	$s .= '&tarefa_ordenar_tipo1='.$item_tipo;
	$s .= '&tarefa_ordenar_ordem1='.$item_ordem;
	if ($tarefa_ordenar_item1 == $item_nome) {
		$s .= '&tarefa_ordenar_item2='.$tarefa_ordenar_item2;
		$s .= '&tarefa_ordenar_tipo2='.$tarefa_ordenar_tipo2;
		$s .= '&tarefa_ordenar_ordem2='.$tarefa_ordenar_ordem2;
		}
	else {
		$s .= '&tarefa_ordenar_item2='.$tarefa_ordenar_item1;
		$s .= '&tarefa_ordenar_tipo2='.$tarefa_ordenar_tipo1;
		$s .= '&tarefa_ordenar_ordem2='.$tarefa_ordenar_ordem1;
		}
	$s .= '\');" class="hdr">'.$titulo;
	if ($mostrar_icone) $s .= '&nbsp;<img src="'.acharImagem('icones/seta-'.(($item_ordem == SORT_ASC) ? 'cima' : 'baixo').'.gif').'" border=0 /></a>';
	echo $s;
	}
?>
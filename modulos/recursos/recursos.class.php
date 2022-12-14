<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

require_once $Aplic->getClasseSistema('aplic');
include_once BASE_DIR.'/modulos/tarefas/funcoes.php';

class CRecurso extends CAplicObjeto {
	public $recurso_id = null;
	public $recurso_nome = null;
	public $recurso_chave = null;
	public $recurso_tipo = null;
	public $recurso_nota = null;
	public $recurso_max_alocacao = null;
	public $recurso_cia = null;
	public $recurso_dept = null;
	public $recurso_nivel_acesso = null;
	public $recurso_responsavel = null;
	public $recurso_unidade = null;
  public $recurso_quantidade = null;
  public $recurso_custo = null;
  public $recurso_nd = null;
  public $recurso_categoria_economica = null;	
	public $recurso_grupo_despesa = null;	
	public $recurso_modalidade_aplicacao = null;	
	public $recurso_ev = null;
	public $recurso_esf = null;
	public $recurso_ptres = null;
	public $recurso_fonte = null;
	public $recurso_sb = null;
	public $recurso_ugr = null;
	public $recurso_pi = null;
	public $recurso_ano = null;
	public $recurso_resultado_primario = null;
	public $recurso_origem = null;
	public $recurso_contato = null;
	public $recurso_credito_adicional = null;
	public $recurso_movimentacao_orcamentaria = null;
	public $recurso_identificador_uso = null;
	public $recurso_liberado = null;
	public $recurso_hora_custo = null;
	public $recurso_ativo = null;	
	public $recurso_principal_indicador = null;
	public $recurso_moeda = null;
	public $recurso_aprovado = null;
	public $recurso_cor=null;
	
	public function __construct( $recurso_id=0) {
    global $config;
		parent::__construct('recursos', 'recurso_id');
		$this->recurso_id=$recurso_id;
    $this->recurso_nivel_acesso = $config['nivel_acesso_padrao'];
		}
	
	public function getNomeTipo() {
		$sql = new BDConsulta;
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor');
		$sql->adOnde( 'sisvalor_titulo = \'tipoRecurso\' AND sisvalor_valor_id = \''.$this->recurso_tipo. '\'' );
		$resultado = $sql->Resultado();
		$sql->limpar();
		if (!$resultado) $resultado = 'Todos os Recursos';
		return $resultado;
		}
		
	
	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta;
		$msg = $this->check();
		if ($msg) {
			$msg_retorno = array(get_class($this).':: checagem de armazenamento', 'falhou', '-');
			if (is_array($msg)) return array_merge($msg_retorno, $msg);
			else {
				array_push($msg_retorno, $msg);
				return $msg_retorno;
				}
			}
		if ($this->recurso_id) {
			$ret = $sql->atualizarObjeto('recursos', $this, 'recurso_id');
			$sql->limpar();
			$this->_acao = 'atualizada';
			} 
		else {
			$this->_acao = 'adicionada';
			$ret = $sql->inserirObjeto('recursos', $this, 'recurso_id');
			$sql->limpar();
			}
	
		$sql->setExcluir('recurso_depts');
		$sql->adOnde('recurso_id='.(int)$this->recurso_id);
		$sql->exec();
		$sql->limpar();
		
		$depts=getParam($_REQUEST, 'recurso_depts', '');
		$depts=explode(',', $depts);
		if (count($depts)) {
			foreach ($depts as $secao) {
				if ($secao){
					$sql->adTabela('recurso_depts');
					$sql->adInserir('recurso_id', $this->recurso_id);
					$sql->adInserir('departamento_id', $secao);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		$sql->setExcluir('recurso_usuarios');
		$sql->adOnde('recurso_id='.(int)$this->recurso_id);
		$sql->exec();
		$sql->limpar();
		
		$usuarios=getParam($_REQUEST, 'recurso_usuarios', '');
		$usuarios=explode(',', $usuarios);
		if (count($usuarios)) {
			foreach ($usuarios as $usuario) {
				if ($usuario){
					$sql->adTabela('recurso_usuarios');
					$sql->adInserir('recurso_id', $this->recurso_id);
					$sql->adInserir('usuario_id', $usuario);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		
		if ($Aplic->profissional){
			$sql->setExcluir('recurso_cia');
			$sql->adOnde('recurso_cia_recurso='.(int)$this->recurso_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'recurso_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('recurso_cia');
						$sql->adInserir('recurso_cia_recurso', $this->recurso_id);
						$sql->adInserir('recurso_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}	
			}	
			
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){	
			$sql->adTabela('recurso_gestao');
			$sql->adAtualizar('recurso_gestao_recurso', $this->recurso_id);
			$sql->adAtualizar('recurso_gestao_uuid', null);
			$sql->adOnde('recurso_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_recurso', (int)$this->recurso_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_recurso', (int)$this->recurso_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();		
			}


		//verificar aprovacao
		if ($Aplic->profissional) {
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_recurso='.(int)$this->recurso_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_recurso='.(int)$this->recurso_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_recurso='.(int)$this->recurso_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('recursos');
			$sql->adAtualizar('recurso_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('recurso_id='.(int)$this->recurso_id);
			$sql->exec();
			$sql->limpar();
			}
			
			
			
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('recursos', $this->recurso_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->recurso_id);	
			
		}
	
	public function qntDisponivel( $tarefa_id=0, $data_inicio=null, $data_fim=null){
		if ($this->recurso_tipo < 4) return (float)$this->recurso_quantidade;
		$sql = new BDConsulta;
		$sql->adTabela('recurso_tarefa');
		$sql->adCampo('SUM(recurso_tarefa_quantidade)');
		$sql->adOnde('recurso_tarefa_recurso='.$this->recurso_id);
		if ($tarefa_id) $sql->adOnde('recurso_tarefa_tarefa!='.(int)$tarefa_id);
		if ($data_inicio && $data_fim) $sql->adOnde('recurso_tarefa_inicio<=\''.$data_fim.'\' AND recurso_tarefa_fim>=\''.$data_inicio.'\'');
		$sql->adOnde('recurso_tarefa_aprovado=1');
		$resultado = $sql->Resultado();
		$sql->limpar();
		return ($this->recurso_quantidade-$resultado);
		}
	
	
	public function podeAcessar( $usuario_id) {
		global $Aplic;
		$sql = new BDConsulta;
		if ($Aplic->usuario_super_admin) return true;
		switch ($this->recurso_nivel_acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$valorRetorno = true;			
				break;
			case 2:
				// participante
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('COUNT(recurso_id)');
				$sql->adOnde('usuario_id='.(int)$usuario_id.' AND recurso_id='.(int)$this->recurso_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = (($quantidade > 0) || $this->recurso_responsavel == $usuario_id);
				break;
			case 3:
				// privado
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('COUNT(recurso_id)');
				$sql->adOnde('usuario_id='.(int)$usuario_id.' AND recurso_id='.(int)$this->recurso_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = (($quantidade > 0) || $this->recurso_responsavel == $usuario_id);
				break;
			}
		return $valorRetorno;
		}

	public function podeEditar( $usuario_id) {
		global $Aplic;
		$sql = new BDConsulta;
		if ($Aplic->usuario_super_admin) return true;
		switch ($this->recurso_nivel_acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('COUNT(recurso_id)');
				$sql->adOnde('usuario_id='.(int)$usuario_id.' AND recurso_id='.(int)$this->recurso_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = ($quantidade > 0 || $this->recurso_responsavel == $usuario_id);
				break;		
			case 2:
				// participante
				$sql->adTabela('recurso_usuarios');
				$sql->adCampo('COUNT(recurso_id)');
				$sql->adOnde('usuario_id='.(int)$usuario_id.' AND recurso_id='.(int)$this->recurso_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = ($quantidade > 0 || $this->recurso_responsavel == $usuario_id);
				break;
			case 3:
				// privado
				$valorRetorno = ($this->recurso_responsavel == $usuario_id);
				break;
			default:
				$valorRetorno = false;
				break;
			}
		return $valorRetorno;
		}

	
	
	public function setData( $data){
		$this->data=$data;
		$this->minical = new CCalendarioMes($data);
		$this->minical->setEstilo('minititulo', 'minical');
		$this->minical->mostrarSetas = false;
		$this->minical->mostrarSemana = false;
		$this->minical->clicarMes = false;
		$this->minical->setAlocacao('sim');
		}


	public function adicionarMes( $qnt){
		$this->data->adMeses($qnt);
		}

	public function calendarioMesAtual($sobrecarga=false, $aprovado=false, $gasto=false){
		$primeira_data = new CData($this->data);
		$primeira_data->setDay(1);
		$primeira_data->setTime(0, 0, 0);
		$ultima_data = new CData($this->data);
		$ultima_data->setDay($this->data->getDaysInMonth());
		$ultima_data->setTime(23, 59, 59);
		$links = array();
		$this->getDisponibilidadeLinks($links, $aprovado, $gasto);
		$this->minical->setEventos($links);
		$this->minical->setData($this->data);
		return $this->minical->mostrar();
		}
		
		
	public function getDisponibilidadeLinks(&$links, $aprovado=false, $gasto=false) {
		global $a, $Aplic, $config, $usuario_id;
		$primeira_data = new CData($this->data);
		$primeira_data->setDay(1);
		$primeira_data->setTime(0, 0, 0);
		$ultima_data = new CData($this->data);
		$ultima_data->setDay($this->data->getDaysInMonth());
		$ultima_data->setTime(23, 59, 59);
		$d="%Y-%m-%d";
		$horas_diasuteis=array();
		$sql = new BDConsulta;
		if ($gasto){
			$sql->adTabela('recurso_ponto');
			$sql->esqUnir('tarefas', 'tarefas', 'recurso_ponto_tarefa = tarefas.tarefa_id');
			$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
			$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id=recurso_ponto_recurso');
			$sql->adCampo('projetos.projeto_id, projeto_cor, projeto_nome, projeto_ativo');
			$sql->adCampo('recursos.recurso_quantidade AS total, recurso_ponto_inicio AS recurso_tarefa_inicio, recurso_ponto_fim AS recurso_tarefa_fim, recurso_ponto_quantidade AS recurso_tarefa_quantidade, recurso_ponto_duracao AS duracao, recurso_ponto_percentual AS percentual');
			$sql->adOnde('recurso_ponto_recurso = '.(int)$this->recurso_id);
			$sql->adOnde('date(recurso_ponto_inicio) <= \''.$ultima_data->format($d).'\' AND date(recurso_ponto_fim)>= \''.$primeira_data->format($d).'\'');
			$sql->adOnde('recurso_ponto_aprovado=1');
			$recursos = $sql->Lista();
			$sql->limpar();
			}
		else{
			$sql->adTabela('recurso_tarefa');
			$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id=recurso_tarefa_tarefa');
			$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id=recurso_tarefa_recurso');
			$sql->adCampo('recursos.recurso_quantidade AS total, recurso_tarefa_inicio, recurso_tarefa_fim, recurso_tarefa_quantidade, recurso_tarefa_duracao AS duracao, recurso_tarefa_percentual AS percentual');
			$sql->adOnde('date(recurso_tarefa_inicio) <= \''.$ultima_data->format($d).'\' AND date(recurso_tarefa_fim)>= \''.$primeira_data->format($d).'\'');
			$sql->adOnde('recurso_tarefa_recurso='.(int)$this->recurso_id);
			if ($aprovado) $sql->adOnde('recurso_tarefa_aprovado=1');
			$recursos=$sql->Lista();
			}
		
		$percentual_recursos_dias=array();
		$quantidade_recursos_dias=array();
		
		foreach ($recursos as $recurso) {
			$data_inicial=new CData($recurso['recurso_tarefa_inicio']);
			$data_final=new CData($recurso['recurso_tarefa_fim']);
			$soma_hora_uteis=0;
			$data=$data_inicial;
			for ($i = 0, $i_cmp = $data_inicial->dataDiferenca($data_final); $i <= $i_cmp; $i++) {
				if (isset($recurso['recurso_tarefa_quantidade']) && $recurso['recurso_tarefa_quantidade']) {
					if (isset($percentual_recursos_dias[$data->format('%Y%m%d')])){
						
						$percentual_recursos_dias[$data->format('%Y%m%d')]+=(int)(($recurso['recurso_tarefa_quantidade']*($recurso['duracao']<=$config['horas_trab_diario'] ? $recurso['duracao'] : $config['horas_trab_diario'])*($recurso['percentual']/100))/($recurso['total']*$config['horas_trab_diario'])*100);
						$quantidade_recursos_dias[$data->format('%Y%m%d')]+=$recurso['recurso_tarefa_quantidade'];
						}
					else {
						$percentual_recursos_dias[$data->format('%Y%m%d')]=(int)(($recurso['recurso_tarefa_quantidade']*($recurso['duracao']<=$config['horas_trab_diario'] ? $recurso['duracao'] : $config['horas_trab_diario'])*($recurso['percentual']/100))/($recurso['total']*$config['horas_trab_diario'])*100);
						
						$quantidade_recursos_dias[$data->format('%Y%m%d')]=$recurso['recurso_tarefa_quantidade'];
						}
					}
				$data = $data->getNextDay();
				}
			}
		
		$data=$primeira_data;
		for ($i = 0, $i_cmp = $primeira_data->dataDiferenca($ultima_data); $i <= $i_cmp; $i++) {
			$indice=$data->format('%Y%m%d');
			$percentual=(isset($percentual_recursos_dias[$indice]) ? (int)$percentual_recursos_dias[$indice] : '');
			$quantidade=(isset($quantidade_recursos_dias[$indice]) ? $quantidade_recursos_dias[$indice] : '');
			if ($percentual){
				$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Percentagem</b></td><td>'.$percentual.'%</td></tr>';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Quantidade</b></td><td>'.number_format((float)$quantidade, 2, ',', '.').'</td></tr>';
				$dentro .= '</table>';
				$temp =  array('alocacao' => true, 'texto_mini' => '<tr><td>'.$dentro.'</td></tr>', 'percentagem' => $percentual);
				$links[$indice][] = $temp;
				
				}
			$data = $data->getNextDay();
			}
		}	
		
	}
?>
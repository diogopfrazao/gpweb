<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


class CDemanda extends CAplicObjeto {

	public $demanda_id = null;
	public $demanda_cia = null;
	public $demanda_dept = null;
	public $demanda_usuario = null;
	public $demanda_supervisor = null;
	public $demanda_autoridade = null;
	public $demanda_cliente = null;
	public $demanda_mensurador = null;
	public $demanda_viabilidade = null;
	public $demanda_termo_abertura = null;
	public $demanda_projeto = null;
	public $demanda_nome = null;
	public $demanda_identificacao = null;
	public $demanda_justificativa = null;
	public $demanda_resultados = null;
	public $demanda_alinhamento = null;
	public $demanda_fonte_recurso = null;
	public $demanda_observacao = null;
	public $demanda_prazo = null;
	public $demanda_custos = null;
	public $demanda_descricao = null;
	public $demanda_objetivos = null;
	public $demanda_como = null;
	public $demanda_localizacao = null;
	public $demanda_beneficiario = null;
	public $demanda_objetivo = null;
	public $demanda_objetivo_especifico = null;
	public $demanda_escopo = null;
	public $demanda_nao_escopo = null;
	public $demanda_premissas = null;
	public $demanda_restricoes = null;
	public $demanda_orcamento = null;
	public $demanda_beneficio = null;
	public $demanda_produto = null;
	public $demanda_requisito = null;
	public $demanda_acesso = null;
	public $demanda_cor = null;
	public $demanda_caracteristica_projeto = null;
	public $demanda_data = null;
	public $demanda_mensuracao_data = null;
	public $demanda_complexidade = null;
	public $demanda_custo = null;
	public $demanda_tempo = null;
	public $demanda_servidores = null;
	public $demanda_recurso_externo = null;
	public $demanda_interligacao = null;
	public $demanda_tamanho = null;
	public $demanda_codigo = null;
	public $demanda_setor = null;
	public $demanda_segmento = null;
	public $demanda_intervencao = null;
	public $demanda_tipo_intervencao = null;
	public $demanda_ano = null;
	public $demanda_sequencial = null;
	public $demanda_cliente_data = null;
	public $demanda_cliente_aprovado = null;
	public $demanda_cliente_obs = null;
	public $demanda_cliente_ativo = null;
	public $demanda_supervisor_data = null;
	public $demanda_supervisor_aprovado = null;
	public $demanda_supervisor_obs = null;
	public $demanda_supervisor_ativo = null;
	public $demanda_autoridade_data = null;
	public $demanda_autoridade_aprovado = null;
	public $demanda_autoridade_obs = null;
	public $demanda_autoridade_ativo = null;
	public $demanda_aprovado = null;
	public $demanda_ativa = null;
	public $demanda_principal_indicador = null;
	public $demanda_moeda = null;
	
	public function __construct( $incluir_subordinadas=false) {
		$this->incluir_subordinadas=$incluir_subordinadas;
		parent::__construct('demandas', 'demanda_id');
		}

	public function load( $oid = null, $tira = false, $pularAtualizacao = false) {
		$carregado = parent::load($oid, $tira);

		if (isset($this->incluir_subordinadas) && $this->incluir_subordinadas) {
			$this->subordinadas(null);
			$this->demandas_subordinadas=implode(',', $this->demandas_subordinadas);
			}
		else $this->demandas_subordinadas=$this->demanda_id;
		return $carregado;
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('demanda_id', null)==$this->demanda_id) $Aplic->setEstado('demanda_id', null);
		parent::excluir();
		return null;
		}

	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->demanda_id) {
			$ret = $sql->atualizarObjeto('demandas', $this, 'demanda_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('demandas', $this, 'demanda_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('demandas', $this->demanda_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->demanda_id);


		$demanda_usuarios=getParam($_REQUEST, 'demanda_usuarios', null);
		$demanda_usuarios=explode(',', $demanda_usuarios);
		$sql->setExcluir('demanda_usuarios');
		$sql->adOnde('demanda_id = '.$this->demanda_id);
		$sql->exec();
		$sql->limpar();
		foreach($demanda_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('demanda_usuarios');
				$sql->adInserir('demanda_id', $this->demanda_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$demanda_contatos=getParam($_REQUEST, 'demanda_contatos', array());
		$demanda_contatos=explode(',', $demanda_contatos);
		$sql->setExcluir('demanda_contatos');
		$sql->adOnde('demanda_id = '.$this->demanda_id);
		$sql->exec();
		$sql->limpar();
		foreach($demanda_contatos as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('demanda_contatos');
				$sql->adInserir('demanda_id', $this->demanda_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}


		$depts_selecionados=getParam($_REQUEST, 'demanda_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('demanda_depts');
		$sql->adOnde('demanda_id = '.$this->demanda_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('demanda_depts');
				$sql->adInserir('demanda_id', $this->demanda_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('demanda_cia');
			$sql->adOnde('demanda_cia_demanda='.(int)$this->demanda_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'demanda_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('demanda_cia');
						$sql->adInserir('demanda_cia_demanda', $this->demanda_id);
						$sql->adInserir('demanda_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}


		$uuid=getParam($_REQUEST, 'uuid', null);
		
		if ($uuid){
			$sql->adTabela('demanda_gestao');
			$sql->adAtualizar('demanda_gestao_demanda', (int)$this->demanda_id);
			$sql->adAtualizar('demanda_gestao_uuid', null);
			$sql->adOnde('demanda_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
		
		if ($uuid && $Aplic->profissional){
			$sql->adTabela('demanda_portfolio');
			$sql->adAtualizar('demanda_portfolio_pai', (int)$this->demanda_id);
			$sql->adAtualizar('uuid', null);
			$sql->adOnde('uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_demanda', (int)$this->demanda_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_demanda', (int)$this->demanda_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
		//verificar aprovacao
		if ($Aplic->profissional) {
			//escolheu despacho negativo
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_demanda='.(int)$this->demanda_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que nao tem despacho mas foi negativo ou nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_demanda='.(int)$this->demanda_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_demanda='.(int)$this->demanda_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('demandas');
			$sql->adAtualizar('demanda_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('demanda_id='.(int)$this->demanda_id);
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	public function subordinadas( $demanda_pai=0){
		global $Aplic;
		if (!$demanda_pai) $demanda_pai=(int)$this->demanda_id;

		$this->demandas_subordinadas[$demanda_pai]=(int)$demanda_pai;
		if ($Aplic->profissional){
			$sql = new BDConsulta;
			$sql->adTabela('demanda_portfolio');
			$sql->adCampo('demanda_portfolio_filho');
			$sql->adOnde('demanda_portfolio_pai ='.(int)$demanda_pai);
			$lista=$sql->carregarColuna();
			$sql->limpar();
			foreach($lista as $chsve => $valor){
	      if(!isset($this->demandas_subordinadas[$valor])){
				  $this->demandas_subordinadas[$valor]=(int)$valor;
				  $this->subordinadas($valor);
	        }
				}
			}
		}


	public function custo_estimado(){
		global $Aplic, $config;
		$sql = new BDConsulta;
		$sql->adTabela('demanda_custo');
		$sql->adCampo('SUM((demanda_custo_quantidade*demanda_custo_custo*demanda_custo_cotacao)*((100+demanda_custo_bdi)/100)) AS total');
		$sql->adOnde('demanda_custo_demanda IN ('.($this->demandas_subordinadas ? $this->demandas_subordinadas : $this->demanda_id).')');
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('demanda_custo_aprovado = 1');
		$total=$sql->Resultado();
		$sql->limpar();
		
		if ($this->demanda_moeda!=1) $total=$total/cotacao($this->demanda_moeda, date('Y-m-d'));

		return $total;
		}

	public function check() {
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessarDemanda($this->demanda_acesso, $this->demanda_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarDemanda($this->demanda_acesso, $this->demanda_id);
		return $valor;
		}

	public function getCodigo( $completo=true){
		if ($this->demanda_tipo_intervencao && $this->demanda_ano && $this->demanda_sequencial){
			if ($this->demanda_sequencial<10) $sequencial='000'.$this->demanda_sequencial;
			elseif ($this->demanda_sequencial<100) $sequencial='00'.$this->demanda_sequencial;
			elseif ($this->demanda_sequencial<1000) $sequencial='0'.$this->demanda_sequencial;
			else $sequencial=$this->demanda_sequencial;
			return substr($this->demanda_tipo_intervencao, 0, 2).($completo ? '.' : '').substr($this->demanda_tipo_intervencao, 2, 2).($completo ? '.' : '').substr($this->demanda_tipo_intervencao, 4, 2).($completo ? '.' : '').substr($this->demanda_tipo_intervencao, 6, 3).($completo ? '.' : '').$sequencial.($completo ? '/' : '').$this->demanda_ano;
			}
		else return '';
		}


	public function setSequencial(){
		if (!$this->demanda_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('demandas');
			$sql->adCampo('max(demanda_sequencial)');
			$sql->adOnde('demanda_cia='.(int)$this->demanda_cia);
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('demandas');
			$sql->adAtualizar('demanda_sequencial', ($maior_sequencial+1));
			$sql->adOnde('demanda_id = '.$this->demanda_id);
			$retorno=$sql->exec();
			$sql->limpar();
			return $retorno;
			}
		}

	public function getSetor(){
		if ($this->demanda_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Setor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->demanda_setor.'"');
			$demanda_setor= $sql->Resultado();
			$sql->limpar();
			return $demanda_setor;
			}
		else return '';
		}

	public function getSegmento(){
		if ($this->demanda_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_valor_id="'.$this->demanda_segmento.'"');
			$demanda_segmento= $sql->Resultado();
			$sql->limpar();
			return $demanda_segmento;
			}
		else return '';
		}

	public function getIntervencao(){
		if ($this->demanda_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->demanda_intervencao.'"');
			$demanda_intervencao= $sql->Resultado();
			$sql->limpar();
			return $demanda_intervencao;
			}
		else return '';
		}

	public function getTipoIntervencao(){
		if ($this->demanda_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->demanda_tipo_intervencao.'"');
			$demanda_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $demanda_tipo_intervencao;
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
		$q->adTabela('demandas');
		$q->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = demanda_usuario');
		$q->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$q->adCampo('usuarios.usuario_id, contato_email');
		$q->adOnde('demanda_id = '.(int)$this->demanda_id);
		$linha = $q->linha();
		$q->limpar();
		$corpo_email='';
		if ($linha['usuario_id']) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo='Demanda Excluida: '.$this->demanda_nome;
			elseif (intval($nao_eh_novo)) $titulo='Demanda Atualizada: '.$this->demanda_nome;
			else $titulo='Demanda Criada: '.$this->demanda_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>A Demanda '.$this->demanda_nome.' foi atualizada.</b><br>';
			else $corpo = '<b>A Demanda '.$this->demanda_nome.' foi criada.</b><br>';
			$corpo .= '<br><br>(Voc? est? recebendo este e-mail por ser o respons?vel pela demanda)<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_demanda($this->demanda_id,'','','','',true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons?vel pela exclus?o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador da demanda:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador da demanda:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;


			$validos=0;


			$corpo_interno=$corpo;
			$corpo_externo=$corpo;

			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=demanda_ver&demanda_id='.$this->demanda_id.'\');"><b>Clique para acessar a demanda</b></a>';

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
								$endereco=link_email_externo($linha['usuario_id'], 'm=projetos&a=demanda_ver&demanda_id='.$this->demanda_id);
								$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar a demanda</b></a>';
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

		$q = new BDConsulta;
		$q->adTabela('demanda_contatos');
		$q->esqUnir('contatos', 'contatos', 'contatos.contato_id = demanda_contatos.contato_id');
		$q->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = contatos.contato_id');
		$q->adCampo('usuarios.usuario_id, contato_email');
		$q->adOnde('demanda_id = '.(int)$this->demanda_id);
		$usuarios = $q->Lista();
		$q->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo='Demanda Excluida: '.$this->demanda_nome;
			elseif (intval($nao_eh_novo)) $titulo='Demanda Atualizada: '.$this->demanda_nome;
			else $titulo='Demanda Criada: '.$this->demanda_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>A Demanda '.$this->demanda_nome.' foi atualizada.</b><br>';
			else $corpo = '<b>A Demanda '.$this->demanda_nome.' foi criada.</b><br>';
			$corpo .= '<br><br>(Voc? est? recebendo este e-mail por ser um dos contatos da demanda)<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_acao($this->demanda_id,'','','','',true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons?vel pela exclus?o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador da demanda:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador da demanda:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=demanda_ver&demanda_id='.$this->demanda_id.'\');"><b>Clique para acessar a demanda</b></a>';
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

							if ($Aplic->profissional){
								require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
								$endereco=link_email_externo($linha['usuario_id'], 'm=projetos&a=demanda_ver&demanda_id='.$this->demanda_id);
								$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar a demanda</b></a>';
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

		$q = new BDConsulta;
		$q->adTabela('demanda_usuarios');
		$q->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = demanda_usuarios.usuario_id');
		$q->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$q->adCampo('usuarios.usuario_id, contato_email');
		$q->adOnde('demanda_id = '.(int)$this->demanda_id);
		$usuarios = $q->Lista();
		$q->limpar();
		$corpo_email='';
		if (count($usuarios)) {
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $titulo='Demanda Excluida: '.$this->demanda_nome;
			elseif (intval($nao_eh_novo)) $titulo='Demanda Atualizada: '.$this->demanda_nome;
			else $titulo='Demanda Criada: '.$this->demanda_nome;
			if (intval($nao_eh_novo)) $corpo = '<b>A Demanda '.$this->demanda_nome.' foi atualizada.</b><br>';
			else $corpo = '<b>A Demanda '.$this->demanda_nome.' foi criada.</b><br>';
			$corpo .= '<br><br>(Voc? est? recebendo este e-mail por ser um dos designados da demanda)<br><br>';
			$corpo .='<table border="1"><tr><td>'.link_demanda($this->demanda_id,'','','','',true).'</td></tr></table>';
			if (isset($this->_mensagem) && $this->_mensagem == 'excluido') $corpo .= "<br><br><b>Respons?vel pela exclus?o:</b> ".$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if (intval($nao_eh_novo)) $corpo .= '<br><br><b>Atualizador da demanda:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			else $corpo .= '<br><br><b>Criador da demanda:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
			if ($comentario) $corpo .='<br><br>'.$comentario;

			$corpo_interno=$corpo;


			if (!isset($this->_mensagem) || (isset($this->_mensagem) && $this->_mensagem != 'excluido')) $corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=demanda_ver&demanda_id='.$this->demanda_id.'\');"><b>Clique para acessar a demanda</b></a>';
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
							if ($Aplic->profissional){
								require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
								$endereco=link_email_externo($linha['usuario_id'], 'm=projetos&a=demanda_ver&demanda_id='.$this->demanda_id);
								$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar a demanda</b></a>';
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

	}

?>
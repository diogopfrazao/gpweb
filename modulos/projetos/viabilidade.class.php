<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


class CViabilidade extends CAplicObjeto {
	public $projeto_viabilidade_id = null;
	public $projeto_viabilidade_cia = null;
	public $projeto_viabilidade_dept = null;
  public $projeto_viabilidade_projeto = null;
  public $projeto_viabilidade_demanda = null;
  public $projeto_viabilidade_nome = null;
  public $projeto_viabilidade_codigo = null;
  public $projeto_viabilidade_setor = null;
	public $projeto_viabilidade_segmento = null;
	public $projeto_viabilidade_intervencao = null;
	public $projeto_viabilidade_tipo_intervencao = null;
	public $projeto_viabilidade_ano = null;
	public $projeto_viabilidade_sequencial = null;
  public $projeto_viabilidade_necessidade = null;
  public $projeto_viabilidade_alinhamento = null;
  public $projeto_viabilidade_requisitos = null;
  public $projeto_viabilidade_solucoes = null;
  public $projeto_viabilidade_viabilidade_tecnica = null;
  public $projeto_viabilidade_financeira = null;
  public $projeto_viabilidade_institucional = null;
  public $projeto_viabilidade_solucao = null;
  public $projeto_viabilidade_continuidade = null;
  public $projeto_viabilidade_responsavel = null;
  public $projeto_viabilidade_acesso = null;
  public $projeto_viabilidade_cor = null;
  public $projeto_viabilidade_data = null;
  public $projeto_viabilidade_ativo = null;
 	public $projeto_viabilidade_viavel = null;
 	public $projeto_viabilidade_aprovado = null;
 	public $projeto_viabilidade_tempo = null;
 	public $projeto_viabilidade_custo = null;
 	public $projeto_viabilidade_observacao = null;
 	public $projeto_viabilidade_moeda = null;

	public function __construct() {
		parent::__construct('projeto_viabilidade', 'projeto_viabilidade_id');
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('projeto_viabilidade_id', null)==$this->projeto_viabilidade_id) $Aplic->setEstado('projeto_viabilidade_id', null);
		parent::excluir();
		return null;
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->projeto_viabilidade_id) {
			$ret = $sql->atualizarObjeto('projeto_viabilidade', $this, 'projeto_viabilidade_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('projeto_viabilidade', $this, 'projeto_viabilidade_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('viabilidade', $this->projeto_viabilidade_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->projeto_viabilidade_id);


		if ($Aplic->profissional){
			$sql->setExcluir('projeto_viabilidade_cia');
			$sql->adOnde('projeto_viabilidade_cia_projeto_viabilidade='.(int)$this->projeto_viabilidade_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'projeto_viabilidade_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('projeto_viabilidade_cia');
						$sql->adInserir('projeto_viabilidade_cia_projeto_viabilidade', $this->projeto_viabilidade_id);
						$sql->adInserir('projeto_viabilidade_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}



		$projeto_viabilidade_depts=getParam($_REQUEST, 'projeto_viabilidade_depts', null);
		$projeto_viabilidade_depts=explode(',', $projeto_viabilidade_depts);
		$sql->setExcluir('projeto_viabilidade_dept');
		$sql->adOnde('projeto_viabilidade_dept_projeto_viabilidade = '.$this->projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_viabilidade_depts as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('projeto_viabilidade_dept');
				$sql->adInserir('projeto_viabilidade_dept_projeto_viabilidade', $this->projeto_viabilidade_id);
				$sql->adInserir('projeto_viabilidade_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$projeto_viabilidade_usuarios=getParam($_REQUEST, 'projeto_viabilidade_usuarios', null);
		$projeto_viabilidade_usuarios=explode(',', $projeto_viabilidade_usuarios);
		$sql->setExcluir('projeto_viabilidade_usuarios');
		$sql->adOnde('projeto_viabilidade_id = '.$this->projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($projeto_viabilidade_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('projeto_viabilidade_usuarios');
				$sql->adInserir('projeto_viabilidade_id', $this->projeto_viabilidade_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$patrocinadores_selecionados=getParam($_REQUEST, 'projeto_viabilidade_patrocinadores', array());
		$patrocinadores_selecionados=explode(',', $patrocinadores_selecionados);
		$sql->setExcluir('projeto_viabilidade_patrocinadores');
		$sql->adOnde('projeto_viabilidade_id = '.$this->projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($patrocinadores_selecionados as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('projeto_viabilidade_patrocinadores');
				$sql->adInserir('projeto_viabilidade_id', $this->projeto_viabilidade_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}


		$interessados_selecionados=getParam($_REQUEST, 'projeto_viabilidade_interessados', array());
		$interessados_selecionados=explode(',', $interessados_selecionados);
		$sql->setExcluir('projeto_viabilidade_interessados');
		$sql->adOnde('projeto_viabilidade_id = '.$this->projeto_viabilidade_id);
		$sql->exec();
		$sql->limpar();
		foreach($interessados_selecionados as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('projeto_viabilidade_interessados');
				$sql->adInserir('projeto_viabilidade_id', $this->projeto_viabilidade_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$sql->adTabela('demandas');
		$sql->adAtualizar('demanda_viabilidade', (int)$this->projeto_viabilidade_id);
		$sql->adOnde('demanda_id='.(int)$this->projeto_viabilidade_demanda);
		$sql->exec();
		$sql->limpar();

		$uuid=getParam($_REQUEST, 'uuid', null);
		
		
		if ($uuid){
			$sql->adTabela('projeto_viabilidade_gestao');
			$sql->adAtualizar('projeto_viabilidade_gestao_projeto_viabilidade', (int)$this->projeto_viabilidade_id);
			$sql->adAtualizar('projeto_viabilidade_gestao_uuid', null);
			$sql->adOnde('projeto_viabilidade_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
		
		
		if ($uuid && $Aplic->profissional){
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_viabilidade', (int)$this->projeto_viabilidade_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}

		//verificar aprovacao
		if ($Aplic->profissional) {
		
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_viabilidade='.(int)$this->projeto_viabilidade_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_viabilidade='.(int)$this->projeto_viabilidade_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_viabilidade='.(int)$this->projeto_viabilidade_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('projeto_viabilidade');
			$sql->adAtualizar('projeto_viabilidade_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('projeto_viabilidade_id='.(int)$this->projeto_viabilidade_id);
			$sql->exec();
			$sql->limpar();
			}

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	public function custo_estimado(){
		global $Aplic, $config;
		$sql = new BDConsulta;
		$sql->adTabela('projeto_viabilidade_custo');
		$sql->adCampo('SUM((projeto_viabilidade_custo_quantidade*projeto_viabilidade_custo_custo*projeto_viabilidade_custo_cotacao)*((100+projeto_viabilidade_custo_bdi)/100)) AS total');
		$sql->adOnde('projeto_viabilidade_custo_projeto_viabilidade ='.(int)$this->projeto_viabilidade_id);
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('projeto_viabilidade_custo_aprovado = 1');
		$total=$sql->Resultado();
		$sql->limpar();
		
		if ($this->projeto_viabilidade_moeda!=1) $total=$total/cotacao($this->projeto_viabilidade_moeda, date('Y-m-d'));

		return $total;
		}
		
	public function check() {
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessarViabilidade($this->projeto_viabilidade_acesso, $this->projeto_viabilidade_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarViabilidade($this->projeto_viabilidade_acesso, $this->projeto_viabilidade_id);
		return $valor;
		}


	public function getCodigo( $completo=true){
		if ($this->projeto_viabilidade_tipo_intervencao && $this->projeto_viabilidade_ano && $this->projeto_viabilidade_sequencial){
			if ($this->projeto_viabilidade_sequencial<10) $sequencial='000'.$this->projeto_viabilidade_sequencial;
			elseif ($this->projeto_viabilidade_sequencial<100) $sequencial='00'.$this->projeto_viabilidade_sequencial;
			elseif ($this->projeto_viabilidade_sequencial<1000) $sequencial='0'.$this->projeto_viabilidade_sequencial;
			else $sequencial=$this->projeto_viabilidade_sequencial;
			return substr($this->projeto_viabilidade_tipo_intervencao, 0, 2).($completo ? '.' : '').substr($this->projeto_viabilidade_tipo_intervencao, 2, 2).($completo ? '.' : '').substr($this->projeto_viabilidade_tipo_intervencao, 4, 2).($completo ? '.' : '').substr($this->projeto_viabilidade_tipo_intervencao, 6, 3).($completo ? '.' : '').$sequencial.($completo ? '/' : '').$this->projeto_viabilidade_ano;
			}
		else return '';
		}


	public function setSequencial(){
		if (!$this->projeto_viabilidade_sequencial){
			$sql = new BDConsulta;
			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('max(projeto_viabilidade_sequencial)');
			$sql->adOnde('projeto_viabilidade_cia='.(int)$this->projeto_viabilidade_cia);
			$maior_sequencial= (int)$sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_viabilidade');
			$sql->adAtualizar('projeto_viabilidade_sequencial', ($maior_sequencial+1));
			$sql->adOnde('projeto_viabilidade_id = '.$this->projeto_viabilidade_id);
			$retorno=$sql->exec();
			$sql->limpar();
			return $retorno;
			}
		}

	public function getSetor(){
		if ($this->projeto_viabilidade_setor){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Setor"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_viabilidade_setor.'"');
			$projeto_viabilidade_setor= $sql->Resultado();
			$sql->limpar();
			return $projeto_viabilidade_setor;
			}
		else return '';
		}

	public function getSegmento(){
		if ($this->projeto_viabilidade_segmento){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Segmento"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_viabilidade_segmento.'"');
			$projeto_viabilidade_segmento= $sql->Resultado();
			$sql->limpar();
			return $projeto_viabilidade_segmento;
			}
		else return '';
		}

	public function getIntervencao(){
		if ($this->projeto_viabilidade_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="Intervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_viabilidade_intervencao.'"');
			$projeto_viabilidade_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_viabilidade_intervencao;
			}
		else return '';
		}

	public function getTipoIntervencao(){
		if ($this->projeto_viabilidade_tipo_intervencao){
			$sql = new BDConsulta;
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor');
			$sql->adOnde('sisvalor_titulo="TipoIntervencao"');
			$sql->adOnde('sisvalor_valor_id="'.$this->projeto_viabilidade_tipo_intervencao.'"');
			$projeto_viabilidade_tipo_intervencao= $sql->Resultado();
			$sql->limpar();
			return $projeto_viabilidade_tipo_intervencao;
			}
		else return '';
		}


	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('projeto_viabilidade');
		$sql->adCampo('projeto_viabilidade_nome');
		$sql->adOnde('projeto_viabilidade_id ='.$this->projeto_viabilidade_id);
		$fator_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['projeto_viabilidade_usuarios']) && $post['projeto_viabilidade_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['projeto_viabilidade_usuarios'].')');
			$usuarios1 = $sql->Lista();
			$sql->limpar();
			}
		if (isset($post['email_outro']) && $post['email_outro']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('contato_id IN ('.$post['email_outro'].')');
			$usuarios2=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_responsavel']) && $post['email_responsavel']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('projeto_viabilidade', 'projeto_viabilidade', 'projeto_viabilidade.projeto_viabilidade_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('projeto_viabilidade_id='.$this->projeto_viabilidade_id);
			$usuarios3=$sql->Lista();
			$sql->limpar();
			}

		if (isset($post['email_extras']) && $post['email_extras']){
			$extras=explode(',',$post['email_extras']);
			foreach($extras as $chave => $valor) $usuarios4[]=array('usuario_id' => 0, 'nome_usuario' =>'', 'contato_email'=> $valor);
			}



		$usuarios = array_merge((array)$usuarios1, (array)$usuarios2);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios3);
		$usuarios = array_merge((array)$usuarios, (array)$usuarios4);


		$usado_usuario=array();
		$usado_email=array();

		if (isset($post['excluir']) && $post['excluir'])$tipo='excluido';
		elseif (isset($post['projeto_viabilidade_id']) && $post['projeto_viabilidade_id']) $tipo='atualizado';
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
					$email->Assunto('Exclu?do estudo de viabilidade', $localidade_tipo_caract);
					$titulo='Exclu?do estudo de viabilidade';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado estudo de viabilidade', $localidade_tipo_caract);
					$titulo='Atualizado estudo de viabilidade';
					}
				else {
					$email->Assunto('Inserido estudo de viabilidade', $localidade_tipo_caract);
					$titulo='Inserido estudo de viabilidade';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado estudo de viabilidade: '.$fator_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Exclu?do estudo de viabilidade: '.$fator_nome.'<br>';
				else $corpo = 'Inserido estudo de viabilidade: '.$fator_nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Respons?vel pela exclus?o do estudo de viabilidade:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Respons?vel pela edi??o do estudo de viabilidade:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do estudo de viabilidade:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$this->projeto_viabilidade_id.'\');"><b>Clique para acessar o estudo de viabilidade</b></a>';
					
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$this->projeto_viabilidade_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o estudo de viabilidade</b></a>';
						}
					}	

				$email->Corpo($corpo_externo, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno,'',$usuario['usuario_id']);
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo']) {
						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}

	}



function viabilidades_quantidade(
	$a=null,
	$tab=null, 
	$envolvimento=null, 
	$cia_id=null, 
	$lista_cias=null, 
	$dept_id=null, 
	$lista_depts=null, 
	$pesquisar_texto=null, 
	$responsavel=null, 
	$viabilidade_setor=null, 
	$viabilidade_segmento=null, 
	$viabilidade_intervencao=null, 
	$viabilidade_tipo_intervencao=null,
	$tarefa_id=null,
	$projeto_id=null,
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
	$os_id=null
	){
	global $Aplic, $m, $u;

	$sql = new BDConsulta;

    $from_lista = (isset($m) && is_string($m) && strtolower($m)==='projetos')
                  && (!isset($u) || $u === '')
                  && (isset($a) && is_string($a) && strtolower($a)==='viabilidade_lista');

    $from_para_fazer = (isset($m) && is_string($m) && strtolower($m)==='tarefas')
                       && (!isset($u) || $u === '')
                       && (isset($a) && is_string($a) && strtolower($a)==='parafazer');


	if ($tab!=0 || !$from_lista){
		$sql->adTabela('projeto_viabilidade');
		$sql->esqUnir('demandas','demandas','demandas.demanda_id=projeto_viabilidade.projeto_viabilidade_demanda');

		$sql->adCampo('count(DISTINCT projeto_viabilidade.projeto_viabilidade_id)');

        if($from_lista){
            if (trim($pesquisar_texto)) $sql->adOnde('projeto_viabilidade_nome LIKE \'%'.$pesquisar_texto.'%\' OR projeto_viabilidade_observacao LIKE \'%'.$pesquisar_texto.'%\'');

            if ($dept_id && !$lista_depts) {
                $sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
                $sql->adOnde('projeto_viabilidade_dept='.(int)$dept_id.' OR projeto_viabilidade_dept_dept='.(int)$dept_id);
                }
            elseif ($lista_depts) {
                $sql->esqUnir('projeto_viabilidade_dept','projeto_viabilidade_dept', 'projeto_viabilidade_dept_projeto_viabilidade=projeto_viabilidade.projeto_viabilidade_id');
                $sql->adOnde('projeto_viabilidade_dept IN ('.$lista_depts.') OR projeto_viabilidade_dept_dept IN ('.$lista_depts.')');
                }
            elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
                $sql->esqUnir('projeto_viabilidade_cia', 'projeto_viabilidade_cia', 'projeto_viabilidade.projeto_viabilidade_id=projeto_viabilidade_cia_projeto_viabilidade');
                $sql->adOnde('projeto_viabilidade_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR projeto_viabilidade_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
                }
            elseif ($cia_id && !$lista_cias) $sql->adOnde('projeto_viabilidade_cia='.(int)$cia_id);
            elseif ($lista_cias) $sql->adOnde('projeto_viabilidade_cia IN ('.$lista_cias.')');

            if ($viabilidade_setor) $sql->adOnde('projeto_viabilidade_setor IN ('.$viabilidade_setor.')');
            if ($viabilidade_segmento) $sql->adOnde('projeto_viabilidade_segmento IN ('.$viabilidade_segmento.')');
            if ($viabilidade_intervencao) $sql->adOnde('projeto_viabilidade_intervencao IN ('.$viabilidade_intervencao.')');
            if ($viabilidade_tipo_intervencao) $sql->adOnde('projeto_viabilidade_tipo_intervencao IN ('.$viabilidade_tipo_intervencao.')');

            if($responsavel) {
                $sql->esqUnir('projeto_viabilidade_usuarios', 'projeto_viabilidade_usuarios', 'projeto_viabilidade_usuarios.projeto_viabilidade_id = projeto_viabilidade.projeto_viabilidade_id');
                $sql->adOnde('projeto_viabilidade_responsavel IN ('.$responsavel.') OR projeto_viabilidade_usuarios.usuario_id IN ('.$responsavel.')');
                }

            if ($tab==1) $sql->adOnde('projeto_viabilidade_viavel=1');
            elseif ($tab==2) $sql->adOnde('projeto_viabilidade_viavel=-1');
            elseif ($tab==3) $sql->adOnde('demanda_projeto IS NOT NULL');

            $sql->adOnde('projeto_viabilidade_ativo=1');
            }

		$sql->esqUnir('projeto_viabilidade_gestao','projeto_viabilidade_gestao','projeto_viabilidade_gestao_projeto_viabilidade = projeto_viabilidade.projeto_viabilidade_id');
		if ($tarefa_id) $sql->adOnde('projeto_viabilidade_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=projeto_viabilidade_gestao_tarefa');
			$sql->adOnde('projeto_viabilidade_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('projeto_viabilidade_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('projeto_viabilidade_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('projeto_viabilidade_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('projeto_viabilidade_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('projeto_viabilidade_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('projeto_viabilidade_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('projeto_viabilidade_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('projeto_viabilidade_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('projeto_viabilidade_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('projeto_viabilidade_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('projeto_viabilidade_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('projeto_viabilidade_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('projeto_viabilidade_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('projeto_viabilidade_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('projeto_viabilidade_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('projeto_viabilidade_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('projeto_viabilidade_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('projeto_viabilidade_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('projeto_viabilidade_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('projeto_viabilidade_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('projeto_viabilidade_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('projeto_viabilidade_gestao_demanda IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('projeto_viabilidade_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('projeto_viabilidade_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('projeto_viabilidade_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('projeto_viabilidade_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('projeto_viabilidade_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('projeto_viabilidade_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('projeto_viabilidade_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('projeto_viabilidade_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('projeto_viabilidade_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('projeto_viabilidade_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('projeto_viabilidade_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('projeto_viabilidade_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('projeto_viabilidade_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('projeto_viabilidade_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('projeto_viabilidade_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('projeto_viabilidade_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('projeto_viabilidade_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('projeto_viabilidade_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('projeto_viabilidade_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('projeto_viabilidade_gestao_tr IN ('.$tr_id.')');
		elseif ($me_id) $sql->adOnde('projeto_viabilidade_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('projeto_viabilidade_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('projeto_viabilidade_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('projeto_viabilidade_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('projeto_viabilidade_gestao_semelhante IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('projeto_viabilidade_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('projeto_viabilidade_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('projeto_viabilidade_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('projeto_viabilidade_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('projeto_viabilidade_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('projeto_viabilidade_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('projeto_viabilidade_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('projeto_viabilidade_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
		elseif ($os_id) $sql->adOnde('projeto_viabilidade_gestao_os IN ('.$os_id.')');
		}
	else {
		$sql->adTabela('demandas');
		
		
		$sql->adCampo('count(DISTINCT demandas.demanda_id)');
		
		if (trim($pesquisar_texto)) $sql->adOnde('demanda_nome LIKE \'%'.$pesquisar_texto.'%\' OR demanda_observacao LIKE \'%'.$pesquisar_texto.'%\'');
			
		if ($dept_id && !$lista_depts) {
			$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
			$sql->adOnde('demanda_dept='.(int)$dept_id.' OR demanda_depts.dept_id='.(int)$dept_id);
			}
		elseif ($lista_depts) {
			$sql->esqUnir('demanda_depts','demanda_depts', 'demanda_depts.demanda_id=demandas.demanda_id');
			$sql->adOnde('demanda_dept IN ('.$lista_depts.') OR demanda_depts.dept_id IN ('.$lista_depts.')');
			}	
		elseif (!$envolvimento && $Aplic->profissional && ($cia_id || $lista_cias)) {
			$sql->esqUnir('demanda_cia', 'demanda_cia', 'demandas.demanda_id=demanda_cia_demanda');
			$sql->adOnde('demanda_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR demanda_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
			}
		elseif ($cia_id && !$lista_cias) $sql->adOnde('demanda_cia='.(int)$cia_id);
		elseif ($lista_cias) $sql->adOnde('demanda_cia IN ('.$lista_cias.')');
		
		if ($viabilidade_setor) $sql->adOnde('demanda_setor IN ('.$viabilidade_setor.')');
		if ($viabilidade_segmento) $sql->adOnde('demanda_segmento IN ('.$viabilidade_segmento.')');
		if ($viabilidade_intervencao) $sql->adOnde('demanda_intervencao IN ('.$viabilidade_intervencao.')');
		if ($viabilidade_tipo_intervencao) $sql->adOnde('demanda_tipo_intervencao IN ('.$viabilidade_tipo_intervencao.')');
		if($responsavel) {
			$sql->esqUnir('demanda_usuarios', 'demanda_usuarios', 'demanda_usuarios.demanda_id = demandas.demanda_id');
			$sql->adOnde('(demanda_usuarios.usuario_id IN ('.$responsavel.') OR demanda_usuario IN ('.$responsavel.'))');
			}
			

		$sql->esqUnir('demanda_gestao','demanda_gestao','demanda_gestao_demanda = demandas.demanda_id');
		if ($tarefa_id) $sql->adOnde('demanda_gestao_tarefa IN ('.$tarefa_id.')');
		elseif ($projeto_id){
			$sql->esqUnir('tarefas','tarefas', 'tarefa_id=demanda_gestao_tarefa');
			$sql->adOnde('demanda_gestao_projeto IN ('.$projeto_id.') OR tarefa_projeto IN ('.$projeto_id.')');
			}
		elseif ($pg_perspectiva_id) $sql->adOnde('demanda_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
		elseif ($tema_id) $sql->adOnde('demanda_gestao_tema IN ('.$tema_id.')');
		elseif ($objetivo_id) $sql->adOnde('demanda_gestao_objetivo IN ('.$objetivo_id.')');
		elseif ($fator_id) $sql->adOnde('demanda_gestao_fator IN ('.$fator_id.')');
		elseif ($pg_estrategia_id) $sql->adOnde('demanda_gestao_estrategia IN ('.$pg_estrategia_id.')');
		elseif ($pg_meta_id) $sql->adOnde('demanda_gestao_meta IN ('.$pg_meta_id.')');
		elseif ($pratica_id) $sql->adOnde('demanda_gestao_pratica IN ('.$pratica_id.')');
		elseif ($pratica_indicador_id) $sql->adOnde('demanda_gestao_indicador IN ('.$pratica_indicador_id.')');
		elseif ($plano_acao_id) $sql->adOnde('demanda_gestao_acao IN ('.$plano_acao_id.')');
		elseif ($canvas_id) $sql->adOnde('demanda_gestao_canvas IN ('.$canvas_id.')');
		elseif ($risco_id) $sql->adOnde('demanda_gestao_risco IN ('.$risco_id.')');
		elseif ($risco_resposta_id) $sql->adOnde('demanda_gestao_risco_resposta IN ('.$risco_resposta_id.')');
		elseif ($calendario_id) $sql->adOnde('demanda_gestao_calendario IN ('.$calendario_id.')');
		elseif ($monitoramento_id) $sql->adOnde('demanda_gestao_monitoramento IN ('.$monitoramento_id.')');
		elseif ($ata_id) $sql->adOnde('demanda_gestao_ata IN ('.$ata_id.')');
		elseif ($mswot_id) $sql->adOnde('demanda_gestao_mswot IN ('.$mswot_id.')');
		elseif ($swot_id) $sql->adOnde('demanda_gestao_swot IN ('.$swot_id.')');
		elseif ($operativo_id) $sql->adOnde('demanda_gestao_operativo IN ('.$operativo_id.')');
		elseif ($instrumento_id) $sql->adOnde('demanda_gestao_instrumento IN ('.$instrumento_id.')');
		elseif ($recurso_id) $sql->adOnde('demanda_gestao_recurso IN ('.$recurso_id.')');
		elseif ($problema_id) $sql->adOnde('demanda_gestao_problema IN ('.$problema_id.')');
		elseif ($demanda_id) $sql->adOnde('demanda_gestao_semelhante IN ('.$demanda_id.')');
		elseif ($programa_id) $sql->adOnde('demanda_gestao_programa IN ('.$programa_id.')');
		elseif ($licao_id) $sql->adOnde('demanda_gestao_licao IN ('.$licao_id.')');
		elseif ($evento_id) $sql->adOnde('demanda_gestao_evento IN ('.$evento_id.')');
		elseif ($link_id) $sql->adOnde('demanda_gestao_link IN ('.$link_id.')');
		elseif ($avaliacao_id) $sql->adOnde('demanda_gestao_avaliacao IN ('.$avaliacao_id.')');
		elseif ($tgn_id) $sql->adOnde('demanda_gestao_tgn IN ('.$tgn_id.')');
		elseif ($brainstorm_id) $sql->adOnde('demanda_gestao_brainstorm IN ('.$brainstorm_id.')');
		elseif ($gut_id) $sql->adOnde('demanda_gestao_gut IN ('.$gut_id.')');
		elseif ($causa_efeito_id) $sql->adOnde('demanda_gestao_causa_efeito IN ('.$causa_efeito_id.')');
		elseif ($arquivo_id) $sql->adOnde('demanda_gestao_arquivo IN ('.$arquivo_id.')');
		elseif ($forum_id) $sql->adOnde('demanda_gestao_forum IN ('.$forum_id.')');
		elseif ($checklist_id) $sql->adOnde('demanda_gestao_checklist IN ('.$checklist_id.')');
		elseif ($agenda_id) $sql->adOnde('demanda_gestao_agenda IN ('.$agenda_id.')');
		elseif ($agrupamento_id) $sql->adOnde('demanda_gestao_agrupamento IN ('.$agrupamento_id.')');
		elseif ($patrocinador_id) $sql->adOnde('demanda_gestao_patrocinador IN ('.$patrocinador_id.')');
		elseif ($template_id) $sql->adOnde('demanda_gestao_template IN ('.$template_id.')');
		elseif ($painel_id) $sql->adOnde('demanda_gestao_painel IN ('.$painel_id.')');
		elseif ($painel_odometro_id) $sql->adOnde('demanda_gestao_painel_odometro IN ('.$painel_odometro_id.')');
		elseif ($painel_composicao_id) $sql->adOnde('demanda_gestao_painel_composicao IN ('.$painel_composicao_id.')');
		elseif ($tr_id) $sql->adOnde('demanda_gestao_tr IN ('.$tr_id.')');
		elseif ($me_id) $sql->adOnde('demanda_gestao_me IN ('.$me_id.')');
		elseif ($plano_acao_item_id) $sql->adOnde('demanda_gestao_acao_item IN ('.$plano_acao_item_id.')');
		elseif ($beneficio_id) $sql->adOnde('demanda_gestao_beneficio IN ('.$beneficio_id.')');
		elseif ($painel_slideshow_id) $sql->adOnde('demanda_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
		elseif ($projeto_viabilidade_id) $sql->adOnde('demanda_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
		elseif ($projeto_abertura_id) $sql->adOnde('demanda_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
		elseif ($pg_id) $sql->adOnde('demanda_gestao_plano_gestao IN ('.$pg_id.')');
		elseif ($ssti_id) $sql->adOnde('demanda_gestao_ssti IN ('.$ssti_id.')');
		elseif ($laudo_id) $sql->adOnde('demanda_gestao_laudo IN ('.$laudo_id.')');
		elseif ($trelo_id) $sql->adOnde('demanda_gestao_trelo IN ('.$trelo_id.')');
		elseif ($trelo_cartao_id) $sql->adOnde('demanda_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
		elseif ($pdcl_id) $sql->adOnde('demanda_gestao_pdcl IN ('.$pdcl_id.')');
		elseif ($pdcl_item_id) $sql->adOnde('demanda_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
		elseif ($os_id) $sql->adOnde('demanda_gestao_os IN ('.$os_id.')');	

		$sql->adOnde('demanda_viabilidade IS NULL');	
		$sql->adOnde('demanda_ativa=1');	
		}
	$qnt=$sql->Resultado();
	$sql->limpar();
	return $qnt;	
	}
	
?>
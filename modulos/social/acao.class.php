<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


class CAcao extends CAplicObjeto {
	
	public $social_acao_id = null;
  public $social_acao_social = null;
  public $social_acao_nome = null;
  public $social_acao_responsavel = null;
  public $social_acao_descricao = null;
	public $social_acao_inicial = null;
	public $social_acao_adquirido = null;
	public $social_acao_final = null;
	public $social_acao_instalado = null;
	public $social_acao_instalar = null;
	public $social_acao_produto = null;
	public $social_acao_orgao = null;
	public $social_acao_financiador = null;
	public $social_acao_codigo = null;
	public $social_acao_declaracao = null;
	public $social_acao_cor = null;
	public $social_acao_logo = null;

	
	public function __construct() {
		parent::__construct('social_acao', 'social_acao_id');
		}

	
	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->social_acao_id) {
			$ret = $sql->atualizarObjeto('social_acao', $this, 'social_acao_id');
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('social_acao', $this, 'social_acao_id');
			$sql->limpar();
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		
		$campos_customizados = new CampoCustomizados('social_acao', $this->social_acao_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->social_acao_id);
			
		$social_usuarios=getParam($_REQUEST, 'social_acao_usuarios', null);
		$social_usuarios=explode(',', $social_usuarios);
		$sql->setExcluir('social_acao_usuarios');
		$sql->adOnde('social_acao_id = '.$this->social_acao_id);
		$sql->exec();
		$sql->limpar();
		foreach($social_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('social_acao_usuarios');
				$sql->adInserir('social_acao_id', $this->social_acao_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		
		$depts_selecionados=getParam($_REQUEST, 'social_acao_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('social_acao_depts');
		$sql->adOnde('social_acao_id = '.$this->social_acao_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('social_acao_depts');
				$sql->adInserir('social_acao_id', $this->social_acao_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		
		

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function check() {
		return null;
		}

	
	public function podeAcessar() {
		$valor = $Aplic->checarModulo('social', 'acesso');
		return $valor;
		}
	
	public function podeEditar() {
		$valor = $Aplic->checarModulo('social', 'editar');
		return $valor;
		}
		

	public function notificar( $post=array()){

		}
	
	}

class CSocialAcaoLog extends CAplicObjeto {
	public $social_acao_log_id = null;
	public $social_acao_log_social = null;
	public $social_acao_log_nome = null;
	public $social_acao_log_descricao = null;
	public $social_acao_log_criador = null;
	public $social_acao_log_horas = null;
	public $social_acao_log_data = null;
	public $social_acao_log_nd = null;
	public $social_acao_log_categoria_economica = null;
	public $social_acao_log_grupo_despesa = null;
	public $social_acao_log_modalidade_aplicacao = null;
	public $social_acao_log_problema = null;
	public $social_acao_log_referencia = null;
	public $social_acao_log_url_relacionada = null;
	public $social_acao_log_custo = null;
	public $social_acao_log_acesso = null;
		
	public function __construct() {
		parent::__construct('social_acao_log', 'social_acao_log_id');
		$this->social_acao_log_problema = intval($this->social_acao_log_problema);
		}

	
	public function arrumarTodos() {
		$descricaoComEspacos = $this->social_acao_log_descricao;
		parent::arrumarTodos();
		$this->social_acao_log_descricao = $descricaoComEspacos;
		}

	public function check() {
		$this->social_acao_log_horas = (float)$this->social_acao_log_horas;
		return null;
		}

	
	public function podeAcessar() {
		$valor = $Aplic->checarModulo('social', 'acesso');
		return $valor;
		}
	
	public function podeEditar() {
		$valor = $Aplic->checarModulo('social', 'editar');
		return $valor;
		}
	
	public function notificar( $post=array()){
		}
	
	
	}
	

	
?>
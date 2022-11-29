<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


class CSuperintendencia extends CAplicObjeto {

	public $social_superintendencia_id = null;
	public $social_superintendencia_responsavel = null;
  public $social_superintendencia_nome = null;
  public $social_superintendencia_cia = null;
  public $social_superintendencia_estado = null;
  public $social_superintendencia_municipio = null;
  public $social_superintendencia_endereco1 = null;
  public $social_superintendencia_endereco2 = null;
  public $social_superintendencia_cep = null;
  public $social_superintendencia_email = null;
  public $social_superintendencia_tel = null;
  public $social_superintendencia_tel2 = null;
  public $social_superintendencia_observacao = null;
  public $social_superintendencia_cel = null;
  public $social_superintendencia_cor = null;
  public $social_superintendencia_ativo = null;
	
		
	public function __construct() {
		parent::__construct('social_superintendencia', 'social_superintendencia_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->social_superintendencia_id) {
			$ret = $sql->atualizarObjeto('social_superintendencia', $this, 'social_superintendencia_id');
			$sql->limpar();
			} 
		else {
			$ret = $sql->inserirObjeto('social_superintendencia', $this, 'social_superintendencia_id');
			$sql->limpar();
			}
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		
		$campos_customizados = new CampoCustomizados('social_superintendencia', $this->social_superintendencia_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->social_superintendencia_id);

		$sql->setExcluir('social_superintendencia_membros');
		$sql->adOnde('social_superintendencia_id = '.$this->social_superintendencia_id);
		$sql->exec();
		$sql->limpar();
		$vetor=getParam($_REQUEST, 'social_superintendencia_membros', '');
		$vetor=explode(',', $vetor);
		foreach ($vetor as $chave => $contato_id){
			if ($contato_id){
				$sql->adTabela('social_superintendencia_membros');
				$sql->adInserir('social_superintendencia_id', $this->social_superintendencia_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		
		
		$sql->setExcluir('social_superintendencia_municipios');
		$sql->adOnde('social_superintendencia_id='.(int)$this->social_superintendencia_id);
		$sql->exec();
		$sql->limpar();
		
		$vetor=getParam($_REQUEST, 'superintendencia_municipios', '');
		if ($vetor) {
			$municipios = explode(',', $vetor);
			foreach ($municipios as $municipio_id) {
				if ($municipio_id){
					$sql->adTabela('social_superintendencia_municipios');
					$sql->adInserir('social_superintendencia_id', $this->social_superintendencia_id);
					$sql->adInserir('municipio_id', $municipio_id);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		
		
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	
	public function excluir( $oid = NULL){
		global $Aplic;
		if ($Aplic->getEstado('social_superintendencia_id', null)==$this->social_superintendencia_id) $Aplic->setEstado('social_superintendencia_id', null);
		parent::excluir();
		return null;
		}
	
	
	public function check() {
		return null;
		}

	
	public function podeAcessar() {
		global $perms;
		//$valor=permiteAcessarSocial($this->social_acesso, $this->social_id);
		$valor = $Aplic->checarModulo('social', 'acesso');
		return $valor;
		}
	
	public function podeEditar() {
		//$valor=permiteEditarSocial($this->social_acesso, $this->social_id);
		$valor = $Aplic->checarModulo('social', 'editar');
		return $valor;
		}
		

	public function notificar( $post=array()){

		}
	
	}

class CSuperintendenciaLog extends CAplicObjeto {
	public $social_superintendencia_log_id = null;
	public $social_superintendencia_log_social = null;
	public $social_superintendencia_log_nome = null;
	public $social_superintendencia_log_descricao = null;
	public $social_superintendencia_log_criador = null;
	public $social_superintendencia_log_horas = null;
	public $social_superintendencia_log_data = null;
	public $social_superintendencia_log_nd = null;
	public $social_superintendencia_log_categoria_economica = null;
	public $social_superintendencia_log_grupo_despesa = null;
	public $social_superintendencia_log_modalidade_aplicacao = null;
	public $social_superintendencia_log_problema = null;
	public $social_superintendencia_log_referencia = null;
	public $social_superintendencia_log_url_relacionada = null;
	public $social_superintendencia_log_custo = null;
	public $social_superintendencia_log_acesso = null;
		
	public function __construct() {
		parent::__construct('social_superintendencia_log', 'social_superintendencia_log_id');
		$this->social_superintendencia_log_problema = intval($this->social_superintendencia_log_problema);
		}

	
	public function arrumarTodos() {
		$descricaoComEspacos = $this->social_superintendencia_log_descricao;
		parent::arrumarTodos();
		$this->social_superintendencia_log_descricao = $descricaoComEspacos;
		}

	public function check() {
		$this->social_superintendencia_log_horas = (float)$this->social_superintendencia_log_horas;
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
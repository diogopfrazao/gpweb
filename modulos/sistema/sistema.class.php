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
$valoresFixosSistema = array('Arma1', 'Arma2', 'Arma3', 'Arma4', 'CategoriaEconomica', 'certificado', 'certificado_senha', 'class_sigilosa', 'CorPrioridadeProjeto', 'cor_precedencia', 'CreditoAdicional', 'EntregaCM', 'EsferaOrcamentaria', 'Estado', 'estilo', 'Fecho', 'FormaImplantacao', 'GrupoND', 'IdentificadorUso', 'Intervencao', 'ModalidadeAplicacao', 'modelo_msg', 'MovimentacaoOrcamentaria', 'ND', 'operadora_tel', 'OrigemRecurso', 'Paises', 'PopulacaoAtendida', 'Posto1', 'Posto2', 'Posto3', 'Posto4', 'precedencia', 'PrioridadeProjeto', 'PrioridadeTarefa', 'PronomeTratamento', 'RefRegistroTarefa', 'RefRegistroTarefaImagem', 'ResultadoPrimario', 'Segmento', 'Setor', 'SimNaoGlobal', 'status', 'StatusProjeto', 'StatusTarefa', 'TipoArquivo', 'TipoDepartamento', 'TipoDuracaoTarefa', 'TipoEvento', 'TipoIntervencao', 'TipoLink', 'TipoOrganizacao', 'TipoProjeto', 'TipoRecurso', 'TipoTarefa', 'TipoUnidade', 'tipo_anexo', 'Vocativo', 'VocativoEnd');

class CPreferencias extends CAplicObjeto {
	public $preferencia_id = null;
  public $usuario = null;
  public $favorito = null;
  public $emailtodos = null;
  public $encaminhar = null;
  public $exibenomefuncao = null;
  public $filtroevento = null;
  public $grupoid = null;
  public $grupoid2 = null;
  public $localidade = null;
  public $modelo_msg = null;
  public $nomefuncao = null;
  public $selecionarpordpto = null;
  public $tarefaemailreg = null;
  public $tarefasexpandidas = null;
  public $msg_extra = null;
  public $msg_entrada = null;
  public $om_usuario = null;
  public $agrupar_msg = null;
  public $padrao_ver_m = null;
  public $padrao_ver_a = null;
  public $padrao_ver_tab = null;
  public $ui_estilo = null;
	public $ver_subordinadas = null;
	public $ver_dept_subordinados = null;
	public $informa_responsavel = null;
	public $informa_designados = null;
	public $informa_contatos = null;
	public $informa_interessados = null;
	public $informa_aberto = null;
	
	public function __construct() {
		parent::__construct('preferencia', 'preferencia_id');
		}
		
	public function join( $hash) {
		if (!is_array($hash)) return 'CPreferencias::unir falhou';
		else {
			$q = new BDConsulta;
			$q->unirLinhaAoObjeto($hash, $this);
			$q->limpar();
			return null;
			}
		}
	public function check() {
		return null; 
		}
	public function armazenar( $atualizarNulos = false) {

		$q = new BDConsulta;
		if ($this->preferencia_id) {
			$ret = $q->atualizarObjeto('preferencia', $this, 'preferencia_id');
			$q->limpar();
			} 
		else {
			$ret = $q->inserirObjeto('preferencia', $this, 'preferencia_id');
			$q->limpar();
			}
		
	
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;	
		}
	public function excluir( $oid = NULL) {
		$q = new BDConsulta;
		$q->setExcluir('preferencia');
		$q->adOnde('usuario = '.(int)$this->usuario);
		if (!$q->exec()) {
			$q->limpar();
			return db_error();
			} 
		else {
			$q->limpar();
			return null;
			}
		}
	}
/********************************************************************************************

Classe CModulo para manipular os m?dulos do sistema
		
gpweb\modulos\sistema\sistema.class.php																																		
																																												
********************************************************************************************/
class CModulo extends CAplicObjeto {
	public $mod_id = null;
  public $mod_nome = null;
  public $mod_diretorio = null;
  public $mod_versao = null;
  public $mod_classe_configurar = null;
  public $mod_tipo = null;
  public $mod_ativo = null;
  public $mod_ui_nome = null;
  public $mod_ui_icone = null;
  public $mod_ui_ordem = null;
  public $mod_ui_ativo = null;
  public $mod_descricao = null;
  public $permissoes_item_tabela = null;
  public $permissoes_item_campo = null;
  public $permissoes_item_legenda = null;
  public $mod_classe_principal = null;
  public $mod_texto_botao = null;
  public $sempre_ativo = null;
  public $mod_menu = null;
	
	public function __construct() {
		parent::__construct('modulos', 'mod_id');
		}
	public function instalar() {
		$q = new BDConsulta;
		$q->adTabela('modulos');
		$q->adCampo('mod_diretorio');
		$q->adOnde('mod_diretorio = \''.$this->mod_diretorio.'\'');
		if ($temp = $q->Linha()) {
			return false;
			}
		$q = new BDConsulta;
		$q->adTabela('modulos');
		$q->adCampo('MAX(mod_ui_ordem)');
		$q->adOnde('mod_nome NOT LIKE \'Public\'');
		$this->mod_ui_ordem = $q->Resultado() + 1;
		$this->armazenar();
		if (!isset($this->mod_admin)) $this->mod_admin = 0;
		return true;
		}
	public function remover() {
		$q = new BDConsulta;
		$q->setExcluir('modulos');
		$q->adOnde('mod_id = '.(int)$this->mod_id);
		if (!$q->exec()) {
			$q->limpar();
			return db_error();
			} 
		else {
			if (!isset($this->mod_admin))	$this->mod_admin = 0;
			return null;
			}
		}
	public function mover( $direcao) {
		$novo_ui_ordem = $this->mod_ui_ordem;
		$q = new BDConsulta;
		$q->adTabela('modulos');
		$q->adOnde('mod_id != '.(int)$this->mod_id);
		$q->adOrdem('mod_ui_ordem');
		$modulos = $q->Lista();
		$q->limpar();
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($modulos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($modulos) + 1)) {
			$q = new BDConsulta;
			$q->adTabela('modulos');
			$q->adAtualizar('mod_ui_ordem', $novo_ui_ordem);
			$q->adOnde('mod_id = '.(int)$this->mod_id);
			$q->exec();
			$q->limpar();
			$idx = 1;
			foreach ($modulos as $modulo) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$q->adTabela('modulos');
					$q->adAtualizar('mod_ui_ordem', $idx);
					$q->adOnde('mod_id = '.(int)$modulo['mod_id']);
					$q->exec();
					$q->limpar();
					$idx++;
					} 
				else {
					$q->adTabela('modulos');
					$q->adAtualizar('mod_ui_ordem', $idx + 1);
					$q->adOnde('mod_id = '.(int)$modulo['mod_id']);
					$q->exec();
					$q->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	public function moduloInstalar() {
		return null;
		}
	public function moduloRemover() {
		return null;
		}
	public function moduloAtualizar() {
		return null;
		}
	}
/********************************************************************************************

Classe CConfig para manipular as configura??es gerais do sistema
		
gpweb\modulos\sistema\sistema.class.php																																		
																																												
********************************************************************************************/
class CConfig extends CAplicObjeto {

	public function __construct() {
		parent::__construct('config', 'config_id');
		}
	public function getSubordinada( $id) {
		$this->_consulta->limpar();
		$this->_consulta->adTabela('config_lista');
		$this->_consulta->adOrdem('config_lista_id');
		$this->_consulta->adOnde('config_nome = \''.$id.'\'');
		$resultado = $this->_consulta->ListaChave('config_lista_id');
		$this->_consulta->limpar();
		return $resultado;
		}
	}
?>
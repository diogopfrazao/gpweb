<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
class CChecklist extends CAplicObjeto {

  public $checklist_id = null;
  public $checklist_superior = null;
  public $checklist_modelo = null;
  public $checklist_cia = null;
  public $checklist_dept = null;
  public $checklist_responsavel = null;
  public $checklist_principal_indicador = null;
  public $checklist_nome = null;
  public $checklist_descricao = null;
  public $checklist_moeda = null;
  public $checklist_cor = null;
  public $checklist_acesso = null;
  public $checklist_tipo = null;
  public $checklist_ativo = null;
	public $checklist_aprovado = null;

	public function __construct() {
		parent::__construct('checklist', 'checklist_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->checklist_id) {
			$ret = $sql->atualizarObjeto('checklist', $this, 'checklist_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('checklist', $this, 'checklist_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('checklist', $this->checklist_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->checklist_id);



		$checklist_usuarios=getParam($_REQUEST, 'checklist_usuarios', null);
		$checklist_usuarios=explode(',', $checklist_usuarios);
		$sql->setExcluir('checklist_usuarios');
		$sql->adOnde('checklist_id = '.$this->checklist_id);
		$sql->exec();
		$sql->limpar();
		foreach($checklist_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('checklist_usuarios');
				$sql->adInserir('checklist_id', $this->checklist_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'checklist_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('checklist_depts');
		$sql->adOnde('checklist_id = '.$this->checklist_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('checklist_depts');
				$sql->adInserir('checklist_id', $this->checklist_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('checklist_cia');
			$sql->adOnde('checklist_cia_checklist='.(int)$this->checklist_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'checklist_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('checklist_cia');
						$sql->adInserir('checklist_cia_checklist', $this->checklist_id);
						$sql->adInserir('checklist_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
					
			$sql->adTabela('checklist_gestao');
			$sql->adAtualizar('checklist_gestao_checklist', (int)$this->checklist_id);
			$sql->adAtualizar('checklist_gestao_uuid', null);
			$sql->adOnde('checklist_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('checklist_lista');
			$sql->adAtualizar('checklist_lista_checklist_id', (int)$this->checklist_id);
			$sql->adAtualizar('checklist_lista_uuid', null);
			$sql->adOnde('checklist_lista_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_checklist', (int)$this->checklist_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_checklist', (int)$this->checklist_id);
			$sql->adAtualizar('priorizacao_uuid', null);
			$sql->adOnde('priorizacao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();		
			}
		
		if ($Aplic->profissional) {
			$sql->adTabela('assinatura');
			$sql->esqUnir('assinatura_atesta_opcao', 'assinatura_atesta_opcao', 'assinatura_atesta_opcao_id=assinatura_atesta_opcao');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_checklist='.(int)$this->checklist_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_checklist='.(int)$this->checklist_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_checklist='.(int)$this->checklist_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('checklist');
			$sql->adAtualizar('checklist_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('checklist_id='.(int)$this->checklist_id);
			$sql->exec();
			$sql->limpar();
			}
		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}

	public function check() {
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessarChecklist($this->checklist_acesso, $this->checklist_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarChecklist($this->checklist_acesso, $this->checklist_id);
		return $valor;
		}

	
	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('checklist');
		$sql->adCampo('checklist_nome');
		$sql->adOnde('checklist_id ='.$this->checklist_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['checklist_usuarios']) && $post['checklist_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['checklist_usuarios'].')');
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
			$sql->esqUnir('checklist', 'checklist', 'checklist.checklist_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('checklist_id='.$this->checklist_id);
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

		if (isset($post['del']) && $post['del'])$tipo='excluido';
		elseif (isset($post['checklist_id']) && $post['checklist_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo=''.ucfirst($config['genero_checklist']).' exclu?do';
				elseif ($tipo=='atualizado') $titulo=''.ucfirst($config['genero_checklist']).' atualizado';
				else $titulo=''.ucfirst($config['genero_checklist']).' inserido';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado '.$config['genero_checklist'].' '.$config['checklist'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Exclu?do '.$config['genero_checklist'].' '.$config['checklist'].': '.$nome.'<br>';
				else $corpo = 'Inserido '.$config['genero_checklist'].' '.$config['checklist'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Respons?vel pela exclus?o d'.$config['genero_checklist'].' '.$config['checklist'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Respons?vel pela edi??o d'.$config['genero_checklist'].' '.$config['checklist'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_checklist'].' '.$config['checklist'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=checklist_ver&checklist_id='.$this->checklist_id.'\');"><b>Clique para acessar '.$config['genero_checklist'].' '.$config['checklist'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=checklist_ver&checklist_id='.$this->checklist_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_checklist'].' '.$config['checklist'].'</b></a>';
						}
					}

				$email->Corpo($corpo_externo, (isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : $localidade_tipo_caract));
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


?>
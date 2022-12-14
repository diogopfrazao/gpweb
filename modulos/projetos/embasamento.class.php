<?php
/*
Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa GP-Web
O GP-Web ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');



class CEmbasamento extends CAplicObjeto {
  public $projeto_embasamento_projeto = null;
  public $projeto_embasamento_responsavel = null;
  public $projeto_embasamento_justificativa = null;
  public $projeto_embasamento_objetivo = null;
  public $projeto_embasamento_escopo = null;
  public $projeto_embasamento_nao_escopo = null;
  public $projeto_embasamento_premissas = null;
  public $projeto_embasamento_restricoes = null;
  public $projeto_embasamento_orcamento = null;
  public $projeto_embasamento_data = null;

	public function __construct() {
		parent::__construct('projeto_embasamento', 'projeto_embasamento_projeto');
		}

	public function excluir( $oid = NULL) {
		global $Aplic;
		if ($Aplic->getEstado('projeto_embasamento_projeto', null)==$this->projeto_embasamento_projeto) $Aplic->setEstado('projeto_embasamento_projeto', null);
		parent::excluir();
		return null;
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic, $_REQUEST;
		$sql = new BDConsulta();
		if ($_REQUEST['antigo']) {
			$ret = $sql->atualizarObjeto('projeto_embasamento', $this, 'projeto_embasamento_projeto');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('projeto_embasamento', $this, '');
			$sql->limpar();
			}
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('projeto_embasamento', $this->projeto_embasamento_projeto, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->projeto_embasamento_projeto);

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}


	public function check() {
		return null;
		}



	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$sql = new BDConsulta;

		$sql->adTabela('projetos');
		$sql->adCampo('projeto_nome');
		$sql->adOnde('projeto_id ='.$this->projeto_embasamento_projeto);
		$projeto_nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['projeto_embasamento_usuarios']) && $post['projeto_embasamento_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['projeto_embasamento_usuarios'].')');
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
			$sql->esqUnir('projeto_embasamento', 'projeto_embasamento', 'projeto_embasamento.projeto_embasamento_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('projeto_embasamento_projeto='.$this->projeto_embasamento_projeto);
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
		elseif (isset($post['projeto_embasamento_projeto']) && $post['projeto_embasamento_projeto']) $tipo='atualizado';
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
					$email->Assunto('Exclu?do embasamento do projeto', $localidade_tipo_caract);
					$titulo='Exclu?do embasamento do projeto';
					}
				elseif ($tipo=='atualizado') {
					$email->Assunto('Atualizado embasamento do projeto', $localidade_tipo_caract);
					$titulo='Atualizado embasamento do projeto';
					}
				else {
					$email->Assunto('Inserido embasamento do projeto', $localidade_tipo_caract);
					$titulo='Inserido embasamento do projeto';
					}
				if ($tipo=='atualizado') $corpo = 'Atualizado embasamento do projeto: '.$projeto_nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Exclu?do embasamento do projeto: '.$projeto_nome.'<br>';
				else $corpo = 'Inserido embasamento do projeto: '.$projeto_nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Respons?vel pela exclus?o do embasamento do projeto:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizada') $corpo .= '<br><br><b>Respons?vel pela edi??o do embasamento do projeto:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador do embasamento do projeto:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				$corpo_interno=$corpo;
				$corpo_externo=$corpo;
				
				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=viabilidade_ver&projeto_embasamento_projeto='.$this->projeto_embasamento_projeto.'\');"><b>Clique para acessar o embasamento do projeto</b></a>';
					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=projetos&a=viabilidade_ver&projeto_embasamento_projeto='.$this->projeto_embasamento_projeto);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar o embasamento do projeto</b></a>';
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

?>
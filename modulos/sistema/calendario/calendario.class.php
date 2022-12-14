<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


class CCalendario extends CAplicObjeto {

	public $calendario_id = null;
  public $calendario_ativo = null;
	public $calendario_cia = null;
	public $calendario_dept = null;
  public $calendario_nome = null;
  public $calendario_usuario = null;
  public $calendario_cor = null;
	public $calendario_acesso = null;
	public $calendario_descricao = null;

	public function __construct() {
		parent::__construct('calendario', 'calendario_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->calendario_id) {
			$ret = $sql->atualizarObjeto('calendario', $this, 'calendario_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('calendario', $this, 'calendario_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));



		$calendario_usuario=getParam($_REQUEST, 'calendario_usuarios', null);
		$calendario_usuario=explode(',', $calendario_usuario);
		$sql->setExcluir('calendario_usuario');
		$sql->adOnde('calendario_usuario_calendario = '.$this->calendario_id);
		$sql->exec();
		$sql->limpar();
		foreach($calendario_usuario as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('calendario_usuario');
				$sql->adInserir('calendario_usuario_calendario', $this->calendario_id);
				$sql->adInserir('calendario_usuario_usuario', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'calendario_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('calendario_dept');
		$sql->adOnde('calendario_dept_calendario = '.$this->calendario_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('calendario_dept');
				$sql->adInserir('calendario_dept_calendario', $this->calendario_id);
				$sql->adInserir('calendario_dept_dept', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}


		if ($Aplic->profissional){
			$sql->setExcluir('calendario_cia');
			$sql->adOnde('calendario_cia_calendario='.(int)$this->calendario_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'calendario_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('calendario_cia');
						$sql->adInserir('calendario_cia_calendario', $this->calendario_id);
						$sql->adInserir('calendario_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			$uuid=getParam($_REQUEST, 'uuid', null);
			if ($uuid){
				$sql->adTabela('calendario_gestao');
				$sql->adAtualizar('calendario_gestao_calendario', (int)$this->calendario_id);
				$sql->adAtualizar('calendario_gestao_uuid', null);
				$sql->adOnde('calendario_gestao_uuid=\''.$uuid.'\'');
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
		$valor=permiteAcessarCalendario($this->calendario_acesso, $this->calendario_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarCalendario($this->calendario_acesso, $this->calendario_id);
		return $valor;
		}


	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('calendario');
		$sql->adCampo('calendario_nome');
		$sql->adOnde('calendario_id ='.$this->calendario_id);
		$nome = $sql->Resultado();
		$sql->limpar();


		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();


		if (isset($post['calendario_usuario']) && $post['calendario_usuario'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['calendario_usuario'].')');
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
			$sql->esqUnir('calendario', 'calendario', 'calendario.calendario_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('calendario_id='.$this->calendario_id);
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
		elseif (isset($post['calendario_id']) && $post['calendario_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo='Agenda Coletiva exclu?da';
				elseif ($tipo=='atualizado') $titulo='Agenda Coletiva atualizada';
				else $titulo='Calendario inserida';

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizada a agenda coletiva: '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Exclu?da a agenda coletiva: '.$nome.'<br>';
				else $corpo = 'Inserida a agenda coletiva: '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Respons?vel pela exclus?o da calendario:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Respons?vel pela edi??o da calendario:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador da calendario:</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;

				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$this->calendario_id.'\');"><b>Clique para acessar a perpectiva</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=sistema&u=calendario&a=calendario_ver&calendario_id='.$this->calendario_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar a perpectiva</b></a>';
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
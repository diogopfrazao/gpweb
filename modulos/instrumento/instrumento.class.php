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
require BASE_DIR.'/incluir/validar_autorizado.php';


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

//require_once ($Aplic->getClasseSistema('aplic'));



class CInstrumento extends CAplicObjeto {
	public $instrumento_id = null;
	public $instrumento_campo = null;
	public $instrumento_numero = null;
	public $instrumento_nome = null;
	public $instrumento_ano = null;
	public $instrumento_licitacao = null;
	public $instrumento_edital_nr = null;
	public $instrumento_processo = null;
	public $instrumento_objeto = null;
	public $instrumento_justificativa = null;
	public $instrumento_entidade = null;
	public $instrumento_entidade_cnpj = null;
	public $instrumento_entidade_codigo = null;
	public $instrumento_data_celebracao = null;
	public $instrumento_data_publicacao = null;
	public $instrumento_data_inicio = null;
	public $instrumento_data_termino = null;
	public $instrumento_garantia_contratual_modalidade = null;
	public $instrumento_garantia_contratual_percentual = null;
	public $instrumento_garantia_contratual_vencimento = null;
	public $instrumento_valor = null;
	public $instrumento_valor_atual = null;
	public $instrumento_valor_contrapartida = null;
	public $instrumento_valor_repasse = null;
	public $instrumento_fim_contrato = null;
	public $instrumento_situacao = null;
	public $instrumento_porcentagem = null;
	public $instrumento_responsavel = null;
	public $instrumento_supervisor = null;
	public $instrumento_autoridade = null;
	public $instrumento_cliente = null;
	public $instrumento_acesso = null;
	public $instrumento_cor = null;
	public $instrumento_cia = null;
	public $instrumento_dept = null;
	public $instrumento_principal_indicador = null;
	public $instrumento_aprovado  = null;
	public $instrumento_ativo = null;
	public $instrumento_moeda = null;
	public $instrumento_fiscal = null;
	public $instrumento_fiscal_substituto = null;
	public $instrumento_prorrogavel = null;
	public $instrumento_prazo_prorrogacao = null;
	public $instrumento_prazo_prorrogacao_tipo = null;
	public $instrumento_acrescimo = null;
	public $instrumento_supressao = null;
	public $instrumento_local_entrega = null;
	public $instrumento_resultado_esperado = null;
	public $instrumento_situacao_atual = null;
	public $instrumento_vantagem_economica = null;
	public $instrumento_casa_significativa = null;
	
	public function __construct() {
		parent::__construct('instrumento', 'instrumento_id');
		}
	public function check() {
		return null;
		}
	public function excluir( $oid = NULL) {
		global $Aplic;
		$this->_mensagem = "excluido";
		if ($Aplic->getEstado('instrumento_id', null)==$this->instrumento_id) $Aplic->setEstado('instrumento_id', null);
		parent::excluir();
		return null;
		}


	public function podeAcessar() {
		$valor=permiteAcessarInstrumento($this->instrumento_acesso, $this->instrumento_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarInstrumento($this->instrumento_acesso, $this->instrumento_id);
		return $valor;
		}



	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta;
		if ($this->instrumento_id) {
			$ret = $sql->atualizarObjeto('instrumento', $this, 'instrumento_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('instrumento', $this, 'instrumento_id');
			$sql->limpar();
			}



		$instrumento_usuarios=getParam($_REQUEST, 'instrumento_usuarios', null);
		$instrumento_usuarios=explode(',', $instrumento_usuarios);
		$sql->setExcluir('instrumento_designados');
		$sql->adOnde('instrumento_id = '.$this->instrumento_id);
		$sql->exec();
		$sql->limpar();
		foreach($instrumento_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('instrumento_designados');
				$sql->adInserir('instrumento_id', $this->instrumento_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'instrumento_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('instrumento_depts');
		$sql->adOnde('instrumento_id = '.$this->instrumento_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('instrumento_depts');
				$sql->adInserir('instrumento_id', $this->instrumento_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$instrumento_contatos=getParam($_REQUEST, 'instrumento_contatos', array());
		$instrumento_contatos=explode(',', $instrumento_contatos);
		$sql->setExcluir('instrumento_contatos');
		$sql->adOnde('instrumento_id = '.$this->instrumento_id);
		$sql->exec();
		$sql->limpar();
		foreach($instrumento_contatos as $chave => $contato_id){
			if($contato_id){
				$sql->adTabela('instrumento_contatos');
				$sql->adInserir('instrumento_id', $this->instrumento_id);
				$sql->adInserir('contato_id', $contato_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$instrumento_recursos=getParam($_REQUEST, 'instrumento_recursos', array());
		$instrumento_recursos=explode(',', $instrumento_recursos);
		$sql->setExcluir('instrumento_recursos');
		$sql->adOnde('instrumento_id = '.$this->instrumento_id);
		$sql->exec();
		$sql->limpar();
		foreach($instrumento_recursos as $chave => $recurso_id){
			if($recurso_id){
				$sql->adTabela('instrumento_recursos');
				$sql->adInserir('instrumento_id', $this->instrumento_id);
				$sql->adInserir('recurso_id', $recurso_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('instrumento_cia');
			$sql->adOnde('instrumento_cia_instrumento='.(int)$this->instrumento_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'instrumento_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('instrumento_cia');
						$sql->adInserir('instrumento_cia_instrumento', $this->instrumento_id);
						$sql->adInserir('instrumento_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}
			
		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid && $Aplic->profissional){
			$sql->adTabela('instrumento_gestao');
			$sql->adAtualizar('instrumento_gestao_instrumento', (int)$this->instrumento_id);
			$sql->adAtualizar('instrumento_gestao_uuid', null);
			$sql->adOnde('instrumento_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

            $sql->adTabela('instrumento_avulso_custo');
            $sql->adAtualizar('instrumento_avulso_custo_instrumento', (int)$this->instrumento_id);
            $sql->adAtualizar('instrumento_avulso_custo_uuid', null);
            $sql->adOnde('instrumento_avulso_custo_uuid=\''.$uuid.'\'');
            $sql->exec();
            $sql->limpar();

            $sql->adTabela('instrumento_custo');
            $sql->adAtualizar('instrumento_custo_instrumento', (int)$this->instrumento_id);
            $sql->adAtualizar('instrumento_custo_uuid', null);
            $sql->adOnde('instrumento_custo_uuid=\''.$uuid.'\'');
            $sql->exec();
            $sql->limpar();
		
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_instrumento', (int)$this->instrumento_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
			
		if ($uuid){	
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_instrumento', (int)$this->instrumento_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_instrumento', (int)$this->instrumento_id);
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
			$sql->adOnde('assinatura_instrumento='.(int)$this->instrumento_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_instrumento='.(int)$this->instrumento_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_instrumento='.(int)$this->instrumento_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('instrumento');
			$sql->adAtualizar('instrumento_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('instrumento_id='.(int)$this->instrumento_id);
			$sql->exec();
			$sql->limpar();
			}
			
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('instrumento', $this->instrumento_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->instrumento_id);

		if (!$ret) return get_class($this).'::armazenar falhou '.db_error();
		else return null;
		}



	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;

		require_once ($Aplic->getClasseSistema('libmail'));

		$sql = new BDConsulta;

		$sql->adTabela('instrumento');
		$sql->adCampo('instrumento_nome');
		$sql->adOnde('instrumento_id ='.$this->instrumento_id);
		$nome = $sql->Resultado();
		$sql->limpar();



		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['instrumento_usuarios']) && $post['instrumento_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['instrumento_usuarios'].')');
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

		if (isset($post['email_responsavel']) && isset($post['email_responsavel'])){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->esqUnir('instrumento', 'instrumento', 'instrumento.instrumento_responsavel = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('instrumento_id='.$this->instrumento_id);
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
		elseif (isset($post['instrumento_id']) && $post['instrumento_id']) $tipo='atualizado';
		else $tipo='incluido';

		foreach($usuarios as $usuario){
			if (!isset($usado[$usuario['usuario_id']]) && !isset($usado[$usuario['contato_email']])){

				if ($usuario['usuario_id']) $usado[$usuario['usuario_id']]=1;
				if ($usuario['contato_email']) $usado[$usuario['contato_email']]=1;




				if ($tipo == 'excluido') $titulo=''.ucfirst($config['genero_instrumento']).' exclu�do';
				elseif ($tipo=='atualizado') $titulo=''.ucfirst($config['genero_instrumento']).' atualizado';
				else $titulo=''.ucfirst($config['genero_instrumento']).' inserido';



				if ($tipo=='atualizado') $corpo = 'Atualizado '.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Exclu�do '.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';
				else $corpo = 'Inserido '.$config['genero_instrumento'].' '.$config['instrumento'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Respons�vel pela exclus�o d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Respons�vel pela edi��o d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_instrumento'].' '.$config['instrumento'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=instrumento&a=instrumento_ver&instrumento_id='.$this->instrumento_id.'\');"><b>Clique para acessar '.$config['genero_instrumento'].' '.$config['instrumento'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=instrumento&a=instrumento_ver&instrumento_id='.$this->instrumento_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_instrumento'].' '.$config['instrumento'].'</b></a>';
						}
					}


				if ($usuario['usuario_id']!=$Aplic->usuario_id && $usuario['usuario_id']) {
					if ($usuario['usuario_id']) msg_email_interno('', $titulo, $corpo_interno,'',$usuario['usuario_id']);
					$email = new Mail;
					if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo']) {
						$email->De($config['email'], $Aplic->usuario_nome);

                        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                            $email->ResponderPara($Aplic->usuario_email);
                            }
                        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                            $email->ResponderPara($Aplic->usuario_email2);
                            }
						$email->Assunto($titulo, $localidade_tipo_caract);
						$email->Corpo($corpo_externo, (isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : $localidade_tipo_caract));

						$email->Para($usuario['contato_email'], true);
						$email->Enviar();
						}
					}
				}
			}
		}


	}



?>
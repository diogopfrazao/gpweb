<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


class CTema extends CAplicObjeto {

	public $tema_id = null;
  public $tema_cia = null;
  public $tema_dept = null;
  public $tema_principal_indicador = null;
  public $tema_nome = null;
  public $tema_data = null;
  public $tema_usuario = null;
  public $tema_ordem = null;
  public $tema_acesso = null;
  public $tema_perspectiva = null;
  public $tema_cor = null;
  public $tema_oque = null;
  public $tema_descricao = null;
  public $tema_onde = null;
  public $tema_quando = null;
  public $tema_como = null;
  public $tema_porque = null;
  public $tema_quanto = null;
  public $tema_quem = null;
  public $tema_controle = null;
  public $tema_melhorias = null;
  public $tema_metodo_aprendizado = null;
  public $tema_desde_quando = null;
  public $tema_ativo = null;
  public $tema_tipo = null;
	public $tema_tipo_pontuacao = null;
	public $tema_percentagem = null;
  public $tema_ponto_alvo = null;
	public $tema_moeda = null;
	public $tema_aprovado = null;
	
	public function __construct() {
		parent::__construct('tema', 'tema_id');
		}


	public function armazenar( $atualizarNulos = false) {
		global $Aplic;
		$sql = new BDConsulta();
		if ($this->tema_id) {
			$ret = $sql->atualizarObjeto('tema', $this, 'tema_id');
			$sql->limpar();
			}
		else {
			$ret = $sql->inserirObjeto('tema', $this, 'tema_id');
			$sql->limpar();
			}

		require_once ($Aplic->getClasseSistema('CampoCustomizados'));

		$campos_customizados = new CampoCustomizados('tema', $this->tema_id, 'editar');
		$campos_customizados->join($_REQUEST);
		$campos_customizados->armazenar($this->tema_id);



		$tema_usuarios=getParam($_REQUEST, 'tema_usuarios', null);
		$tema_usuarios=explode(',', $tema_usuarios);
		$sql->setExcluir('tema_usuarios');
		$sql->adOnde('tema_id = '.$this->tema_id);
		$sql->exec();
		$sql->limpar();
		foreach($tema_usuarios as $chave => $usuario_id){
			if($usuario_id){
				$sql->adTabela('tema_usuarios');
				$sql->adInserir('tema_id', $this->tema_id);
				$sql->adInserir('usuario_id', $usuario_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		$depts_selecionados=getParam($_REQUEST, 'tema_depts', null);
		$depts_selecionados=explode(',', $depts_selecionados);
		$sql->setExcluir('tema_depts');
		$sql->adOnde('tema_id = '.$this->tema_id);
		$sql->exec();
		$sql->limpar();
		foreach($depts_selecionados as $chave => $dept_id){
			if($dept_id){
				$sql->adTabela('tema_depts');
				$sql->adInserir('tema_id', $this->tema_id);
				$sql->adInserir('dept_id', $dept_id);
				$sql->exec();
				$sql->limpar();
				}
			}

		if ($Aplic->profissional){
			$sql->setExcluir('tema_cia');
			$sql->adOnde('tema_cia_tema='.(int)$this->tema_id);
			$sql->exec();
			$sql->limpar();
			$cias=getParam($_REQUEST, 'tema_cias', '');
			$cias=explode(',', $cias);
			if (count($cias)) {
				foreach ($cias as $cia_id) {
					if ($cia_id){
						$sql->adTabela('tema_cia');
						$sql->adInserir('tema_cia_tema', $this->tema_id);
						$sql->adInserir('tema_cia_cia', $cia_id);
						$sql->exec();
						$sql->limpar();
						}
					}
				}
			}

		$uuid=getParam($_REQUEST, 'uuid', null);
		if ($uuid){
			$sql->adTabela('tema_gestao');
			$sql->adAtualizar('tema_gestao_tema', (int)$this->tema_id);
			$sql->adAtualizar('tema_gestao_uuid', null);
			$sql->adOnde('tema_gestao_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			}
			
		if ($Aplic->profissional && $uuid){
			$sql->adTabela('tema_media');
			$sql->adAtualizar('tema_media_tema', (int)$this->tema_id);
			$sql->adAtualizar('tema_media_uuid', null);
			$sql->adOnde('tema_media_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('plano_acao_observador');
			$sql->adAtualizar('plano_acao_observador_tema', (int)$this->tema_id);
			$sql->adAtualizar('plano_acao_observador_uuid', null);
			$sql->adOnde('plano_acao_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('projeto_observador');
			$sql->adAtualizar('projeto_observador_tema', (int)$this->tema_id);
			$sql->adAtualizar('projeto_observador_uuid', null);
			$sql->adOnde('projeto_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('objetivo_observador');
			$sql->adAtualizar('objetivo_observador_tema', (int)$this->tema_id);
			$sql->adAtualizar('objetivo_observador_uuid', null);
			$sql->adOnde('objetivo_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('estrategia_observador');
			$sql->adAtualizar('estrategia_observador_tema', (int)$this->tema_id);
			$sql->adAtualizar('estrategia_observador_uuid', null);
			$sql->adOnde('estrategia_observador_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();
			
			
			
			$sql->adTabela('assinatura');
			$sql->adAtualizar('assinatura_tema', (int)$this->tema_id);
			$sql->adAtualizar('assinatura_uuid', null);
			$sql->adOnde('assinatura_uuid=\''.$uuid.'\'');
			$sql->exec();
			$sql->limpar();

			$sql->adTabela('priorizacao');
			$sql->adAtualizar('priorizacao_tema', (int)$this->tema_id);
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
			$sql->adOnde('assinatura_tema='.(int)$this->tema_id);
			$sql->adOnde('assinatura_atesta_opcao_aprova!=1 OR assinatura_atesta_opcao_aprova IS NULL');
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta_opcao > 0');
			$nao_aprovado1 = $sql->resultado();
			$sql->limpar();
			
			
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_tema='.(int)$this->tema_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NULL');
			$sql->adOnde('assinatura_data IS NULL OR (assinatura_data IS NOT NULL AND assinatura_aprovou=0)');
			$nao_aprovado2 = $sql->resultado();
			$sql->limpar();
			
			//assinatura que tem despacho mas nem assinou
			$sql->adTabela('assinatura');
			$sql->adCampo('count(assinatura_id)');
			$sql->adOnde('assinatura_tema='.(int)$this->tema_id);
			$sql->adOnde('assinatura_aprova=1');
			$sql->adOnde('assinatura_atesta IS NOT NULL');
			$sql->adOnde('assinatura_atesta_opcao IS NULL');
			$nao_aprovado3 = $sql->resultado();
			$sql->limpar();
			
			$nao_aprovado=($nao_aprovado1 || $nao_aprovado2 || $nao_aprovado3);
			
			$sql->adTabela('tema');
			$sql->adAtualizar('tema_aprovado', ($nao_aprovado ? 0 : 1));
			$sql->adOnde('tema_id='.(int)$this->tema_id);
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
		$valor=permiteAcessarTema($this->tema_acesso, $this->tema_id);
		return $valor;
		}

	public function podeEditar() {
		$valor=permiteEditarTema($this->tema_acesso, $this->tema_id);
		return $valor;
		}

	public function calculo_percentagem(){
		$tipo=$this->tema_tipo_pontuacao;

		$sql = new BDConsulta;


		$porcentagem=null;
		if (!$tipo) $porcentagem=$this->tema_percentagem;
		elseif($tipo=='media_ponderada'){
			$sql->adTabela('tema_media');
			$sql->esqUnir('objetivo', 'objetivo', 'objetivo_id=tema_media_objetivo');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=tema_media_estrategia');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=tema_media_projeto');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=tema_media_acao');
			$sql->adCampo('objetivo_percentagem, projeto_percentagem, pg_estrategia_percentagem, plano_acao_percentagem, tema_media_peso, tema_media_objetivo, tema_media_estrategia, tema_media_projeto, tema_media_acao');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'media_ponderada\'');
			$lista = $sql->lista();
			$sql->limpar();
			$numerador=0;
			$denominador=0;



			foreach($lista as $linha){
				if ($linha['tema_media_objetivo']) $numerador+=($linha['objetivo_percentagem']*$linha['tema_media_peso']);
				elseif ($linha['tema_media_estrategia']) $numerador+=($linha['pg_estrategia_percentagem']*$linha['tema_media_peso']);
				elseif ($linha['tema_media_projeto']) $numerador+=($linha['projeto_percentagem']*$linha['tema_media_peso']);
				elseif ($linha['tema_media_acao']) $numerador+=($linha['plano_acao_percentagem']*$linha['tema_media_peso']);
				$denominador+=$linha['tema_media_peso'];
				}
			$porcentagem=($denominador ? $numerador/$denominador : 0);
			}
		elseif($tipo=='pontos_completos'){
			$sql->adTabela('tema_media');
			$sql->esqUnir('objetivo', 'objetivo', 'objetivo_id=tema_media_objetivo');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('objetivo_percentagem = 100');
			$sql->adOnde('tema_media_objetivo > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=tema_media_estrategia');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('pg_estrategia_percentagem = 100');
			$sql->adOnde('tema_media_estrategia > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=tema_media_projeto');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('projeto_percentagem = 100');
			$sql->adOnde('tema_media_projeto > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=tema_media_acao');
			$sql->adCampo('SUM(tema_media_ponto)');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_completos\'');
			$sql->adOnde('plano_acao_percentagem = 100');
			$sql->adOnde('tema_media_acao > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();


			$porcentagem=($this->tema_ponto_alvo != 0 ? (($pontos1+$pontos2+$pontos3+$pontos4)/$this->tema_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='pontos_parcial'){
			$sql->adTabela('tema_media');
			$sql->esqUnir('objetivo', 'objetivo', 'objetivo_id=tema_media_objetivo');
			$sql->adCampo('SUM(tema_media_ponto*(objetivo_percentagem/100))');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('tema_media_objetivo > 0');
			$pontos1 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('estrategias', 'estrategias', 'pg_estrategia_id=tema_media_estrategia');
			$sql->adCampo('SUM(tema_media_ponto*(pg_estrategia_percentagem/100))');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('tema_media_estrategia > 0');
			$pontos2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('projetos', 'projetos', 'projeto_id=tema_media_projeto');
			$sql->adCampo('SUM(tema_media_ponto*(projeto_percentagem/100))');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('tema_media_projeto > 0');
			$pontos3 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema_media');
			$sql->esqUnir('plano_acao', 'plano_acao', 'plano_acao_id=tema_media_acao');
			$sql->adCampo('SUM(tema_media_ponto*(plano_acao_percentagem/100))');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\'pontos_parcial\'');
			$sql->adOnde('tema_media_acao > 0');
			$pontos4 = $sql->Resultado();
			$sql->limpar();

			$porcentagem=($this->tema_ponto_alvo != 0 ? (($pontos1+$pontos2+$pontos3+$pontos4)/$this->tema_ponto_alvo)*100 : 0);
			}
		elseif($tipo=='indicador'){
			if ($this->tema_principal_indicador) {
				include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
				$obj_indicador = new Indicador($this->tema_principal_indicador);
				$porcentagem=$obj_indicador->Pontuacao();
				}
			else $porcentagem=0;
			}

		else $porcentagem=0; //caso nao previsto

		if ($porcentagem > 100) $porcentagem=100;
		if ($porcentagem!=$this->tema_percentagem){
			$sql->adTabela('tema');
			$sql->adAtualizar('tema_percentagem', $porcentagem);
			$sql->adOnde('tema_id ='.(int)$this->tema_id);
			$sql->exec();
			$sql->limpar();
			$this->disparo_observador('fisico');
			}
		return $porcentagem;
		}





	public function disparo_observador( $acao='fisico'){
		//Quem faz uso deste tema em c?lculos de percentagem
		$sql = new BDConsulta;

		$sql->adTabela('tema_observador');
		$sql->adCampo('tema_observador.*');
		$sql->adOnde('tema_observador_tema ='.(int)$this->tema_id);
		if ($acao) $sql->adOnde('tema_observador_acao =\''.$acao.'\'');
		$lista = $sql->lista();
		$sql->limpar();

		$qnt_perspectiva=0;

		foreach($lista as $linha){

			if ($linha['tema_observador_perspectiva']){
				if (!($qnt_perspectiva++)) require_once BASE_DIR.'/modulos/praticas/perspectiva.class.php';
				$obj= new CPerspectiva();
				$obj->load($linha['tema_observador_perspectiva']);
				if (method_exists($obj, $linha['tema_observador_metodo'])){
					$obj->{$linha['tema_observador_metodo']}();
					}
				}
			}

		}

	public function notificar( $post=array()){
		global $Aplic, $config, $localidade_tipo_caract;
		require_once ($Aplic->getClasseSistema('libmail'));
		$sql = new BDConsulta;

		$sql->adTabela('tema');
		$sql->adCampo('tema_nome');
		$sql->adOnde('tema_id ='.$this->tema_id);
		$nome = $sql->Resultado();
		$sql->limpar();

		$usuarios =array();
		$usuarios1=array();
		$usuarios2=array();
		$usuarios3=array();
		$usuarios4=array();

		if (isset($post['tema_usuarios']) && $post['tema_usuarios'] && isset($post['email_designados']) && $post['email_designados']){
			$sql->adTabela('usuarios');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('usuario_id IN ('.$post['tema_usuarios'].')');
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
			$sql->esqUnir('tema', 'tema', 'tema.tema_usuario = usuarios.usuario_id');
			$sql->adCampo('DISTINCT usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_email');
			$sql->adOnde('tema_id='.$this->tema_id);
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
		elseif (isset($post['tema_id']) && $post['tema_id']) $tipo='atualizado';
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

				if ($tipo == 'excluido') $titulo=ucfirst($config['tema']).' exclu?d'.$config['genero_tema'];
				elseif ($tipo=='atualizado') $titulo=ucfirst($config['tema']).' atualizad'.$config['genero_tema'];
				else $titulo=ucfirst($config['tema']).' inserid'.$config['genero_tema'];

				$email->Assunto($titulo, $localidade_tipo_caract);

				if ($tipo=='atualizado') $corpo = 'Atualizado '.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';
				elseif ($tipo=='excluido') $corpo = 'Exclu?do '.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';
				else $corpo = 'Inserido '.$config['genero_tema'].' '.$config['tema'].': '.$nome.'<br>';

				if ($tipo=='excluido') $corpo .= '<br><br><b>Respons?vel pela exclus?o d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				elseif ($tipo=='atualizado') $corpo .= '<br><br><b>Respons?vel pela edi??o d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;
				else $corpo .= '<br><br><b>Criador d'.$config['genero_tema'].' '.$config['tema'].':</b> '.$Aplic->usuario_posto.' '.$Aplic->usuario_nomeguerra;


				$corpo_interno=$corpo;
				$corpo_externo=$corpo;

				if ($tipo!='excluido') {
					$corpo_interno .= '<br><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=tema_ver&tema_id='.$this->tema_id.'\');"><b>Clique para acessar '.$config['genero_tema'].' '.$config['tema'].'</b></a>';

					if ($Aplic->profissional){
						require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
						$endereco=link_email_externo($usuario['usuario_id'], 'm=praticas&a=tema_ver&tema_id='.$this->tema_id);
						$corpo_externo.='<br><a href="'.$endereco.'"><b>Clique para acessar '.$config['genero_tema'].' '.$config['tema'].'</b></a>';
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




	public function fisico_previsto($data=null){
		if ($this->tema_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
			require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('tema_media');
			$sql->adCampo('tema_media_projeto, tema_media_acao, tema_media_estrategia, tema_media_objetivo, tema_media_peso');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\''.$this->tema_tipo_pontuacao.'\'');
			
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['tema_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['tema_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['tema_media_peso'];
					$denominador+=$linha['tema_media_peso'];
					}
				elseif ($linha['tema_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['tema_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['tema_media_peso'];
					$denominador+=$linha['tema_media_peso'];
					}
				elseif ($linha['tema_media_estrategia']){
					$obj = new CEstrategia();
					$obj->load($linha['tema_media_estrategia']);
					$numerador+=$obj->fisico_previsto($data)*$linha['tema_media_peso'];
					$denominador+=$linha['tema_media_peso'];
					}	
					
				elseif ($linha['tema_media_objetivo']){
					$obj = new CObjetivo();
					$obj->load($linha['tema_media_objetivo']);
					$numerador+=$obj->fisico_previsto($data)*$linha['tema_media_peso'];
					$denominador+=$linha['tema_media_peso'];
					}			
									
					
						
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->tema_tipo_pontuacao=='pontos_parcial' || $this->tema_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
			require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('tema_media');
			$sql->adCampo('tema_media_projeto, tema_media_acao, tema_media_estrategia, tema_media_objetivo, tema_media_ponto');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\''.$this->tema_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['tema_media_projeto']){
					$obj = new CProjeto();
					$obj->load($linha['tema_media_projeto']);
					$numerador+=$obj->fisico_previsto($data)*$linha['tema_media_ponto'];
					$denominador+=$linha['tema_media_ponto'];
					}
				elseif ($linha['tema_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['tema_media_acao']);
					$numerador+=$obj->fisico_previsto($data)*$linha['tema_media_ponto'];
					$denominador+=$linha['tema_media_ponto'];
					}
				elseif ($linha['tema_media_estrategia']){
					$obj = new CEstrategia();
					$obj->load($linha['tema_media_estrategia']);
					$numerador+=$obj->fisico_previsto($data)*$linha['tema_media_ponto'];
					$denominador+=$linha['tema_media_ponto'];
					}	
				elseif ($linha['tema_media_objetivo']){
					$obj = new CObjetivo();
					$obj->load($linha['tema_media_objetivo']);
					$numerador+=$obj->fisico_previsto($data)*$linha['tema_media_ponto'];
					$denominador+=$linha['tema_media_ponto'];
					}			
				
				}
			return ($denominador ? $numerador/$denominador : 0);
			}	
		else return $this->tema_percentagem;	
		}
		
		
		
		
		
		
		
	public function fisico_executado($data=null){
		if ($this->tema_tipo_pontuacao=='media_ponderada'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
			require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('tema_media');
			$sql->adCampo('tema_media_projeto, tema_media_acao, tema_media_estrategia, tema_media_objetivo, tema_media_peso');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\''.$this->tema_tipo_pontuacao.'\'');
			
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['tema_media_projeto']){
					$obj = new CProjeto(null, null, $data, $linha['tema_media_projeto']);
					$obj->load($linha['tema_media_projeto']);
					$numerador+=$obj->projeto_percentagem*$linha['tema_media_peso'];
					$denominador+=$linha['tema_media_peso'];
					}
				elseif ($linha['tema_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['tema_media_acao']);
					$numerador+=$obj->plano_acao_percentagem*$linha['tema_media_peso'];
					$denominador+=$linha['tema_media_peso'];
					}
				elseif ($linha['tema_media_estrategia']){
					$obj = new CEstrategia();
					$obj->load($linha['tema_media_estrategia']);
					$numerador+=$obj->fisico_executado($data)*$linha['tema_media_peso'];
					$denominador+=$linha['tema_media_peso'];
					}	
					
				elseif ($linha['tema_media_objetivo']){
					$obj = new CObjetivo();
					$obj->load($linha['tema_media_objetivo']);
					$numerador+=$obj->fisico_executado($data)*$linha['tema_media_peso'];
					$denominador+=$linha['tema_media_peso'];
					}			
									
					
						
				}
			return ($denominador ? $numerador/$denominador : 0);
			}
		elseif ($this->tema_tipo_pontuacao=='pontos_parcial' || $this->tema_tipo_pontuacao=='pontos_completos'){
			require_once BASE_DIR.'/modulos/projetos/projetos.class.php';
			require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';
			require_once BASE_DIR.'/modulos/praticas/estrategia.class.php';
			require_once BASE_DIR.'/modulos/praticas/obj_estrategico.class.php';
			$sql = new BDConsulta;
			$sql->adTabela('tema_media');
			$sql->adCampo('tema_media_projeto, tema_media_acao, tema_media_estrategia, tema_media_objetivo, tema_media_ponto');
			$sql->adOnde('tema_media_tema ='.(int)$this->tema_id);
			$sql->adOnde('tema_media_tipo =\''.$this->tema_tipo_pontuacao.'\'');
			$lista = $sql->lista();
			$sql->limpar();
			
			$numerador=0;
			$denominador=0;
			
			foreach($lista as $linha){
				if ($linha['tema_media_projeto']){
					$obj = new CProjeto(null, null, $data, $linha['tema_media_projeto']);
					$obj->load($linha['tema_media_projeto']);
					$numerador+=$obj->projeto_percentagem*$linha['tema_media_ponto'];
					$denominador+=$linha['tema_media_ponto'];
					}
				elseif ($linha['tema_media_acao']){
					$obj = new CPlanoAcao();
					$obj->load($linha['tema_media_acao']);
					$numerador+=$obj->plano_acao_percentagem*$linha['tema_media_ponto'];
					$denominador+=$linha['tema_media_ponto'];
					}
				elseif ($linha['tema_media_estrategia']){
					$obj = new CEstrategia();
					$obj->load($linha['tema_media_estrategia']);
					$numerador+=$obj->fisico_executado($data)*$linha['tema_media_ponto'];
					$denominador+=$linha['tema_media_ponto'];
					}	
				elseif ($linha['tema_media_objetivo']){
					$obj = new CObjetivo();
					$obj->load($linha['tema_media_objetivo']);
					$numerador+=$obj->fisico_executado($data)*$linha['tema_media_ponto'];
					$denominador+=$linha['tema_media_ponto'];
					}			
				
				}
			return ($denominador ? $numerador/$denominador : 0);
			}	
		else return $this->tema_percentagem;	
		}	
		
		
		
		
		
		
		
		
		

	public function fisico_velocidade($data=null){
		$fisico_previsto=$this->fisico_previsto($data);
		$fisico_executado=$this->fisico_executado($data);
		return ($fisico_previsto ? $fisico_executado/$fisico_previsto : 0);
		}





	}


?>
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

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

if(!defined('UI_MSG_OK')) define('UI_MSG_OK', 1);
if(!defined('UI_MSG_ALERTA')) define('UI_MSG_ALERTA', 2);
if(!defined('UI_MSG_AVISO')) define('UI_MSG_AVISO', 3);
if(!defined('UI_MSG_ERRO')) define('UI_MSG_ERRO', 4);
$GLOBALS['traduzir'] = array();
if(!defined('UI_CAIXA_MASCARA')) define('UI_CAIXA_MASCARA', 0x0F);
if(!defined('UI_CAIXA_ALTA')) define('UI_CAIXA_ALTA', 1);
if(!defined('UI_CAIXA_BAIXA')) define('UI_CAIXA_BAIXA', 2);
if(!defined('UI_CAIXA_PRIMEIRA_ALTA')) define('UI_CAIXA_PRIMEIRA_ALTA', 3);
if(!defined('UI_MASCARA_SAIDA')) define('UI_MASCARA_SAIDA', 0xF0);
if(!defined('UI_SAIDA_HTML')) define('UI_SAIDA_HTML', 0);
if(!defined('UI_SAIDA_JS')) define('UI_SAIDA_JS', 0x10);
if(!defined('UI_SAIDA_CRUA')) define('UI_SAIDA_CRUA', 0x20);
require_once BASE_DIR.'/classes/permissoes.class.php';

class CAplic {
	public $estado = null;
	public $usuario_id = null;
  public $usuario_posto = null;
	public $usuario_nomeguerra = null;
	public $usuario_funcao = null;
	public $usuario_nome = null;
	public $usuario_nome_completo = null;
	public $usuario_cia = null;
	public $usuario_dept = null;
	public $usuario_grupo_dept = null;
	public $usuario_lista_grupo = null;
	public $usuario_lista_grupo_vetor = null;
	public $usuario_tem_lista_grupo = null;
	public $usuario_grupo_id = null;
	public $usuario_email = null;
	public $usuario_email2 = null;
	public $usuario_rodape = null;
	public $usuario_admin = null;
	public $usuario_ver_tudo = null;
	public $usuario_observador = null;
	public $profissional = null;
	public $usuario_super_admin = null;
	public $usuario_acesso_email = null;
	public $usuario_pode_oculta = null;
	public $conta_conjunta = null;
	public $chave_privada = null;
	public $chave_criada = null;
	public $chave_publica_id = null;
	public $senha_msg = null;
	public $usuario_ativo = null;
	public $usuario_pode_outra_cia = null;
	public $usuario_pode_lateral = null;
	public $usuario_pode_superior = null;
	public $usuario_pode_todas_cias = null;
	public $usuario_pode_todos_depts = null;
	public $usuario_pode_dept_subordinado = null;
	public $usuario_pode_dept_lateral = null;
	public $usuario_pode_dept_superior = null;
	public $usuario_prefs = null;
	public $dia_selecionado = null;
	public $usuario_localidade = null;
	public $usuario_linguagem = null;
	public $base_localidade = 'pt';
	public $msg = '';
	public $msgNo = '';
	public $redirecionarPadrao = '';
	public $cfg = null;
	public $versao_maior = null;
	public $versao_menor = null;
	public $versao_revisao = null;
	public $versao_string = null;
	public $versao_js_string = null;
	public $ultimo_acesso = 0;
	public $beta = null;
	public $ultimo_id_inserido = null;
	public $usuario_estilo = null;
	public $cor_msg_nao_lida = null;
	public $cor_msg_realce = null;
	public $celular = null;
	public $pdf_print = 0;
	public $gpweb_logo = '';
	public $gpweb_brasao = '';
    public $ckeditorjs_carregado = false;
    public $extjs_carregado = false;
    public $calendariojs_carregado = false;
    public $calendariodatahorajs_carregado = false;
    public $multiselecaojs_carregado = false;
    public $login_externo = false;

	public function __construct() {
		global $config;
		$this->estado = array();
		$this->usuario_id = -1;
		$this->usuario_posto = '';
		$this->usuario_nomeguerra = '';
		$this->usuario_funcao = '';
		$this->usuario_nome = '';
		$this->usuario_nome_completo = '';
		$this->usuario_cia = null;
		$this->usuario_dept = null;
		$this->usuario_grupo_dept = null;
		$this->usuario_admin = 0;
		$this->usuario_ver_tudo = 0;
		$this->usuario_observador = 0;
		$this->profissional = file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php');
		$this->usuario_super_admin = 0;
		$this->usuario_acesso_email = 0;
		$this->usuario_pode_oculta = 0;
		$this->chave_privada= '';
		$this->chave_criada= '';
		$this->chave_publica_id= null;
		$this->senha_msg= '';
		$this->conta_conjunta = 0;
		$this->usuario_ativo = 0;
		$this->usuario_pode_outra_cia = 0;
		$this->usuario_pode_lateral = 0;
		$this->usuario_pode_superior = 0;
		$this->usuario_pode_todas_cias = 0;
		$this->usuario_pode_todos_depts = 0;
		$this->usuario_pode_dept_subordinado = 0;
		$this->usuario_pode_dept_lateral = 0;
		$this->usuario_pode_dept_superior = 0;
		$this->usuario_acesso=array();
		$this->modulo_ativo=array();
		$this->projeto_id = null;
		$this->redirecionarPadrao = '';
    $this->ckeditorjs_carregado = false;
    $this->extjs_carregado = false;
    $this->calendariojs_carregado = false;
    $this->calendariodatahorajs_carregado = false;
    $this->multiselecaojs_carregado = false;
		$this->setUsuarioLocalidade($this->base_localidade);
		$this->usuario_prefs = array();
		$this->gpweb_logo = get_base_url().'/'.(isset($config['logotipo']) ? $config['logotipo'] : 'estilo/rondon/imagens/organizacao/'.(isset($config['militar']) ? $config['militar'] : '10').'/gpweb_logo.png');
		$this->gpweb_brasao = get_base_url().'/'.(isset($config['brasao']) ? $config['brasao'] : 'estilo/rondon/imagens/brasao.gif');
		}

	public function &acl() {
		if (!isset($GLOBALS['acl'])) $GLOBALS['acl'] = new meu_acl;
		return $GLOBALS['acl'];
		}

	public function getClasseSistema( $nome = null) {
		if ($nome) return BASE_DIR.'/classes/'.$nome.'.class.php';
		}

	public function getClasseBiblioteca( $nome = null) {
		if ($nome) return BASE_DIR.'/lib/'.$nome.'.php';
		}


	public function getClasseModulo( $nome = null) {
		if ($nome) return BASE_DIR.'/modulos/'.$nome.'/'.$nome.'.class.php';
		}

	public function getModuloAjax( $nome=null ) {
		if ($nome) return BASE_DIR.'/modulos/'.$nome.'/'.$nome.'.ajax.php';
		}

	public function getVersao() {
		global $config;

		$sql = new BDConsulta;
		$sql->adTabela('versao');
		$sql->adCampo('versao_bd');
		$versao_bd_atual = $sql->resultado();
		$sql->limpar();


		if (!isset($this->versao_maior)) {
			include_once BASE_DIR.'/incluir/versao.php';
			$this->versao_maior = $_versao_maior;
			$this->versao_menor = $_versao_menor;
			$this->_versao_revisao = $_versao_revisao;
			$this->versao_bd = $versao_bd;
			$this->beta = $beta;
			$this->versao_string = $this->versao_maior.'.'.$this->versao_menor.'.'.$this->_versao_revisao.($this->beta ? ' Beta '.$this->beta :'').' ('.($versao_bd_atual != $this->versao_bd ? '<font style=\'color: red;\'>'.$versao_bd_atual.'</font> - '.$this->versao_bd : $this->versao_bd).')<br>'.$data_versao;
			$this->versao_js_string = $_versao_js_maior.'.'.$_versao_js_menor.'.'.$_versao_js_revisao;
			}
		return $this->versao_string;
		}

	public function getVersaoJs() {
		global $config;
		if(!$this->versao_js_string) {
			include_once BASE_DIR.'/incluir/versao.php';
			$this->versao_js_string = $_versao_js_maior.'.'.$_versao_js_menor.'.'.$_versao_js_revisao;
			}
		return $this->versao_js_string;
		}

	public function checarEstilo() {
		$estilo_ui = 'rondon';
		$this->setPref('ui_estilo', 'rondon');
		}

	public function lerDirs( $caminho) {
		$dirs = array();
		if (is_dir(BASE_DIR.'/'.$caminho)){
			$d = dir(BASE_DIR.'/'.$caminho);
			if ($d){
				while (false !== ($nome = $d->read())) {
					if (is_dir(BASE_DIR.'/'.$caminho.'/'.$nome) && $nome != '.' && $nome != '..' && $nome != 'CVS' && $nome != '.svn') $dirs[$nome] = $nome;
					}
				$d->close();
				}
			}
		return $dirs;
		}

	public function lerArquivos( $caminho, $filtro = '.') {
		$arquivos = array();
		if (is_dir($caminho) && ($handle = opendir($caminho))) {
			while (false !== ($arquivo = readdir($handle))) {
				if ($arquivo != '.' && $arquivo != '..' && preg_match('/'.$filtro.'/', $arquivo)) $arquivos[$arquivo] = $arquivo;
				}
			closedir($handle);
			}
		return $arquivos;
		}

	public function checarNomeArquivo( $arquivo) {
		global $Aplic;
		$carc_ruins = ";/\\";
		$substituicao_ruim = '....';
		if (strpos(strtr($arquivo, $carc_ruins, $substituicao_ruim), '.') !== false) $Aplic->redirecionar('m=publico&a=acesso_negado');
		else return $arquivo;
		}

	public function fazerNomeArquivoSeguro( $arquivo) {
		$arquivo = str_replace('../', '', $arquivo);
		$arquivo = str_replace('..\\', '', $arquivo);
		return $arquivo;
		}

	public function setUsuarioLocalidade( $loc = '', $set = true) {
		global $localidade_tipo_caract;
		$IDIOMA = $this->carregarIdioma();
		if (!$loc) $loc = (isset($this->usuario_prefs['localidade']) && $this->usuario_prefs['localidade'] ? $this->usuario_prefs['localidade'] : config('idioma'));
		if (isset($IDIOMA[$loc])) $lingua = $IDIOMA[$loc];
		else {
			if (strlen($loc)> 2) {
				list($l, $c) = explode('_', $loc);
				$loc = $this->acharIdioma($l, $c);
				}
			else {
				$loc = $this->acharIdioma($loc);
				}
			$lingua = $IDIOMA[$loc];
			}

		list($base_localidade, $texto_inglesa, $texto_nativa, $idioma_padrao, $lcs) = $lingua;
		if (!isset($lcs))	$lcs = (isset($localidade_tipo_caract)) ? $localidade_tipo_caract : 'utf-8';

		if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') $usuario_linguagem = $idioma_padrao;
		else $usuario_linguagem = $loc.'.'.$lcs;

		if ($set) {
			$this->usuario_localidade = $base_localidade;
			$this->usuario_linguagem = $usuario_linguagem;
			$localidade_tipo_caract = $lcs;
			}
		else return $usuario_linguagem;
		}

	public function acharIdioma( $idioma, $pais = false) {
		$IDIOMA = $this->carregarIdioma();
		$idioma = strtolower($idioma);
		if ($pais) {
			$pais = strtoupper($pais);
			$code = $idioma.'_'.$pais;
			if (isset($IDIOMA[$code])) return $code;
			}
		$primeira_entrada = null;
		foreach ($IDIOMA as $lingua => $info) {
			list($l, $c) = explode('_', $lingua);
			if ($l == $idioma) {
				if (!$primeira_entrada) $primeira_entrada = $lingua;
				if ($pais && $c == $pais) return $lingua;
				}
			}
		return $primeira_entrada;
		}

	public function carregarIdioma() {
		$IDIOMA = array();
		$langs = $this->lerDirs('localidades');
		foreach ($langs as $lingua) if (file_exists(BASE_DIR.'/localidades/'.$lingua.'/idioma.php')) include BASE_DIR.'/localidades/'.$lingua.'/idioma.php';
		$_SESSION['IDIOMAS'] = &$IDIOMA;
		return $IDIOMA;
		}

	public function _( $str, $estados = 0) {
		if (is_array($str)) {
			$traduzido = array();
			foreach ($str as $s) $traduzido[] = $this->__($s, $estados);
			return implode(' ', $traduzido);
			}
		else return $this->__($str, $estados);
		}

	public function __( $str, $estados = 0) {
		$str = trim($str);
		if (empty($str)) return '';
		$x = $GLOBALS['traduzir'][$str];
		$x = $str;
		if ($x)	$str = $x;

		switch ($estados & UI_CAIXA_MASCARA) {
			case UI_CAIXA_ALTA:
				$str = strtoupper($str);
				break;
			case UI_CAIXA_BAIXA:
				$str = strtolower($str);
				break;
			case UI_CAIXA_PRIMEIRA_ALTA:
				$str = ucwords($str);
				break;
			}
		global $localidade_tipo_caract;
		if (!$localidade_tipo_caract) $localidade_tipo_caract = 'iso-8859-1';
		switch ($estados & UI_MASCARA_SAIDA) {
			case UI_SAIDA_HTML:
				$str = htmlentities(stripslashes($str), ENT_COMPAT, $localidade_tipo_caract);
				break;
			case UI_SAIDA_JS:
				$str = addslashes(stripslashes($str));
				break;
			case UI_SAIDA_CRUA:
				$str = stripslashes($str);
				break;
			}
		return $str;
		}

	public function salvarPosicao(){
		$qnt=0;
		$saida='';
		foreach($_REQUEST as $chave => $valor) {
			if (is_array($valor)){
				foreach($valor as $chave1 => $valor1) $saida.=($qnt++ ? '&' : '').$chave.'[]='.$valor1;
				}
			else $saida.=($qnt++ ? '&' : '').$chave.'='.$valor;
			}
					
		if (!isset($this->estado['POSICAOSALVA']) || !is_array($this->estado['POSICAOSALVA'])) $this->estado['POSICAOSALVA']=array();
		$indices=count($this->estado['POSICAOSALVA']);
		
		
		if ($indices && $this->estado['POSICAOSALVA'][$indices-1]!=$saida) {
			$this->estado['POSICAOSALVA'][]=$saida;
			}
		elseif (!$indices) {
		$this->estado['POSICAOSALVA'][]=$saida;
		}
		}

	public function getPosicao() {
		
		if (isset($this->estado['POSICAOSALVA'])) {
			$indice=count($this->estado['POSICAOSALVA'])-1;
			return $this->estado['POSICAOSALVA'][$indice];
			}
		else return '';
		}

	public function redirecionar( $toms = '', $hist = '', $caminho='') {
		if (!$toms) $toms = isset($this->estado['POSICAOSALVA'.$hist]) && count($this->estado['POSICAOSALVA'.$hist]) ? $this->estado['POSICAOSALVA'.$hist][count($this->estado['POSICAOSALVA'.$hist])-1] : $this->redirecionarPadrao;
		echo '<script>url_passar(0, \''.$toms.'\');</script>';
		exit();
		}

	public function tela_anterior($m_atual=null, $a_atual=null, $u_atual=null) {
		if (isset($this->estado['POSICAOSALVA'])) {
			$atual=array_pop($this->estado['POSICAOSALVA']);
			$i=count($this->estado['POSICAOSALVA'])-1;
			$ultimo=($i!=0 ? $this->estado['POSICAOSALVA'][$i] : array());
			$vetor=array();

			$vetor_filtro=explode('&', $atual);
			foreach($vetor_filtro as $valor){
				$resultado=explode('=', $valor);
				if (isset($resultado[0]) && isset($resultado[1])) $vetor[$resultado[0]]=$resultado[1];
				}
				
			$igual=((!isset($vetor['m']) || (isset($vetor['m']) && ($vetor['m']==$m_atual))) && (!isset($vetor['a']) || (isset($vetor['a']) && ($vetor['a']==$a_atual))) && (!isset($vetor['u']) || (isset($vetor['u']) && ($vetor['u']==$u_atual))));	
				
			if (!$igual) {
				$this->redirecionar($atual);
				}
			else {
				array_pop($this->estado['POSICAOSALVA']);
				$this->redirecionar($ultimo);
				}
			}
		else return '';
		}

	public function setChavePrivada( $chave) {
		return $this->chave_privada=$chave;
		}

	public function setChaveCriada( $chave) {
		return $this->chave_criada=$chave;
		}

	public function setChavePublicaId( $chave) {
		return $this->chave_publica_id=$chave;
		}

	public function setSenhaMsg( $senha) {
		return $this->senha_msg=$senha;
		}



	public function setMsg( $msg, $msgNo = 0, $anexar = false) {
		$this->msg = $anexar ? $this->msg.' '.$msg : $msg;
		$this->msgNo = $msgNo;
		}

	public function getMsg( $reset = true) {
		$img = '';
		$classe = '';
		$msg = $this->msg;
		switch ($this->msgNo) {
			case UI_MSG_OK:
				$img = imagem('icones/ok.png');
				$classe = 'mensagem';
				break;
			case UI_MSG_ALERTA:
				$img = imagem('icones/alerta.png');
				$classe = 'mensagem';
				break;
			case UI_MSG_AVISO:
				$img = imagem('icones/informacao.gif');
				$classe = 'aviso';
				break;
			case UI_MSG_ERRO:
				$img = imagem('icones/cancelar.png');
				$classe = 'erro';
				break;
			default:
				$classe = 'mensagem';
				break;
			}
		if ($reset) {
			$this->msg = '';
			$this->msgNo = 0;
			}
		return $msg ? '<table cellspacing=0 cellpadding=0><tr><td>'.$img.'</td><td class="'.$classe.'">'.$msg.'</td></tr></table>' : '';
		}

	public function setEstado( $legenda, $valor = null, $m=null, $a=null, $u=null) {
		$this->estado[$legenda.$m.$u.$a] = $valor;
		}

	public function getEstado($legenda, $valor_padrao = null, $m=null, $a=null, $u=null) {
		if (array_key_exists($legenda.$m.$u.$a, $this->estado))	return $this->estado[$legenda.$m.$u.$a];
		elseif (isset($valor_padrao)) {
			$this->setEstado($legenda.$m.$u.$a, $valor_padrao);
			return $valor_padrao;
			}
		else return null;
		}

  public function variavel_estado($buffer, $nome_variavel, $nome_estado, $valor_padrao){
    if(array_key_exists($nome_variavel, $buffer)){
      $this->setEstado($nome_estado, getParam($buffer, $nome_variavel, $valor_padrao));
    }

    $estado = $this->getEstado($nome_estado, $valor_padrao);
    return $estado !== null ? $estado : $valor_padrao;
  }

	public function login( $usuarioNome, $senha) {
		global $config;
		require_once BASE_DIR.'/classes/autenticacao.class.php';
		$metodo_autenticacao = config('metodo_autenticacao', 'sql');

		if ($_REQUEST['login'] != 'entrar' && $_REQUEST['login'] != $this->_('entrar', UI_SAIDA_CRUA) && $_REQUEST['login'] != $metodo_autenticacao) 	die('Voc? escolheu logar utilizando um m?todo n?o suportado ou desabilitado.');

		$usuarioNome = trim(db_escape($usuarioNome));
		$senha = trim($senha);

		$conectou=false;

		//para o programa gaucho usar o webservice em 1o lugar
		if ($config['militar']==11 && file_exists(BASE_DIR.'/modulos/sagri/autenticacao.class.php')) {
			require_once BASE_DIR.'/modulos/sagri/autenticacao.class.php';
			$auth = new PGQPAutenticador();
			$conectou=$auth->autenticar($usuarioNome, $senha);
			}


		if ($config['militar']!=11 || !$conectou)	{
			$auth = &getauth($metodo_autenticacao);
			$conectou=$auth->autenticar($usuarioNome, $senha);
			}


		if (!$conectou && $metodo_autenticacao=='sql') {
			//tentar LDAP
			$auth = &getauth('ldap');
			$conectou=$auth->autenticar($usuarioNome, $senha);
			}

		if (!$conectou && ($metodo_autenticacao=='ldap' || $metodo_autenticacao=='dgp')) {
			//tentar SQL
			$auth = &getauth('sql');
			$conectou=$auth->autenticar($usuarioNome, $senha);
			}

		if (!$conectou) return false;

		$usuario_id = $auth->usuarioId($usuarioNome);
		$usuarioNome = $auth->usuarioNome;

		$sql = new BDConsulta;

		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo('usuario_id, contato_posto AS usuario_posto, contato_nomeguerra AS usuario_nomeguerra, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS usuario_nome, contato_nomecompleto AS usuario_nome_completo, contato_funcao AS usuario_funcao, contato_cia AS usuario_cia, contato_dept AS usuario_dept, usuario_grupo_dept, contato_email AS usuario_email, contato_email2 AS usuario_email2, usuario_admin, usuario_observador, usuario_rodape, usuario_pode_oculta, usuario_ativo');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$preparar = $sql->prepare();
		$sql->carregarObjeto($this);
		$sql->limpar();

		if ($this->usuario_observador){
			$achado=array();
			$achado[]=array('modulo'=>'admin', 'objeto'=>null, 'acesso'=>1, 'editar'=>1, 'adicionar'=>1,'excluir'=>1,'aprovar'=>1);
			$achado[]=array('modulo'=>'nao_admin', 'objeto'=>null, 'acesso'=>0, 'editar'=>1, 'adicionar'=>1,'excluir'=>1,'aprovar'=>1);
			$this->usuario_acesso['negar']=$achado;

			$achado=array();
			$achado[]=array('modulo'=>'nao_admin', 'objeto'=>null, 'acesso'=>1, 'editar'=>0, 'adicionar'=>0,'excluir'=>0,'aprovar'=>0);
			$this->usuario_acesso['permitir']=$achado;
			}
		
		else {
			$sql->adTabela('perfil_acesso');
			$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
			$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
			$sql->adCampo('perfil_acesso_modulo AS modulo, perfil_acesso_objeto AS objeto, perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
			$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
			$sql->adOnde('perfil_acesso_negar = 1');
			$achado=$sql->lista();
			$sql->limpar();

			$this->usuario_acesso['negar']=$achado;
	
			$sql->adTabela('perfil_acesso');
			$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
			$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
			$sql->adCampo('perfil_acesso_modulo AS modulo, perfil_acesso_objeto AS objeto, perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
			$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
			$sql->adOnde('perfil_acesso_negar = 0');
			$achado=$sql->lista();
			$sql->limpar();
	
			$this->usuario_acesso['permitir']=$achado;
			}
		
		$sql->adTabela('modulos');
		$sql->adCampo('mod_diretorio, mod_ativo');
		$this->modulo_ativo = $sql->listaVetorChave('mod_diretorio', 'mod_ativo');
		$sql->limpar();
		
		if (checarModulo('email', 'acesso', $usuario_id, 'acesso_4')) $this->usuario_acesso_email=4;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_3')) $this->usuario_acesso_email=3;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_2')) $this->usuario_acesso_email=2;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_1')) $this->usuario_acesso_email=1;
		else $this->usuario_acesso_email=0;

		$this->usuario_pode_todas_cias = checarModulo('cias', 'acesso', $usuario_id, 'pode_todas');
		$this->usuario_pode_outra_cia = checarModulo('cias', 'acesso', $usuario_id, 'pode_subordinada');
		$this->usuario_pode_lateral = checarModulo('cias', 'acesso', $usuario_id, 'pode_lateral');
		$this->usuario_pode_superior = checarModulo('cias', 'acesso', $usuario_id, 'pode_superior');

		//Permiss?o de navegar nos departamento
		if($this->profissional){
			$this->usuario_pode_todos_depts = checarModulo('depts', 'acesso', $usuario_id, 'pode_todos');
			$this->usuario_pode_dept_subordinado = checarModulo('depts', 'acesso', $usuario_id, 'pode_subordinado');
			$this->usuario_pode_dept_lateral = checarModulo('depts', 'acesso', $usuario_id, 'pode_lateral');
			$this->usuario_pode_dept_superior = checarModulo('depts', 'acesso', $usuario_id, 'pode_superior');

			}
		else{
			$this->usuario_pode_todos_depts = 1;
			$this->usuario_pode_dept_subordinado = 1;
			$this->usuario_pode_dept_lateral = 1;
			$this->usuario_pode_dept_superior = 1;
			}

		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_pai');
		$sql->adOnde('usuario_grupo_usuario ='.(int)$usuario_id.($this->usuario_dept ? ' OR usuario_grupo_dept='.$this->usuario_dept : ''));
		$sql->adOnde('usuario_grupo_pai !='.(int)$usuario_id);
		$lista=$sql->carregarColuna();
		$sql->limpar();

		if (count($lista)){
			$lista[]=$usuario_id;
			$this->usuario_tem_lista_grupo=true;
			$this->usuario_lista_grupo_vetor=$lista;
			$this->usuario_lista_grupo=implode(',',$lista);
			}
		else {
			$this->usuario_tem_lista_grupo=false;
			$this->usuario_lista_grupo=$usuario_id;
			$this->usuario_lista_grupo_vetor=array(0 => $usuario_id);
			}

		if ($config['filtrar_usuario_dept'] && $this->usuario_dept) $this->setEstado('dept_id', $this->usuario_dept);
		elseif ($this->usuario_dept && !$this->usuario_pode_todos_depts && !$this->usuario_pode_dept_subordinado && !$this->usuario_pode_dept_lateral && !$this->usuario_pode_dept_superior) $this->setEstado('dept_id', $this->usuario_dept);


		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_login2, usuario_senha2');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$outra_conta=$sql->Linha();
		$sql->limpar();

		if ($outra_conta['usuario_login2'] && $outra_conta['usuario_senha2']) $this->conta_conjunta=1;

		$sql->adTabela('preferencia_cor');
		$sql->adCampo('cor_msg_nao_lida, cor_msg_realce');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$cores=$sql->Linha();
		$sql->limpar();


		if ($cores==null){
			$sql->adTabela('preferencia_cor');
			$sql->adCampo('cor_msg_nao_lida, cor_msg_realce');
			$sql->adOnde('usuario_id IS NULL');
			$cores=$sql->Linha();
			$sql->limpar();
			}


		//delete as sessoes abertas do usuario_id
		if (!isset($config['exemplo']) || (isset($config['exemplo']) && !$config['exemplo'])){
			$sql->setExcluir('sessoes');
			$sql->adOnde('sessao_usuario='.(int)$usuario_id);
			$sql->exec();
			$sql->limpar();
			}

		$this->cor_msg_nao_lida=($cores['cor_msg_nao_lida']? $cores['cor_msg_nao_lida'] :'fbfbda');
		$this->cor_msg_realce=($cores['cor_msg_realce']? $cores['cor_msg_realce'] :'ffffff');

		if (isset($_REQUEST['celular']) && $_REQUEST['celular']) $this->celular=1;

		$this->carregarPrefs($this->usuario_id);
		$this->setUsuarioLocalidade();
		$this->checarEstilo();
		$this->usuario_super_admin=verificaAdministrador($this->usuario_id);
		$this->usuario_ver_tudo=verificaVerTudo($this->usuario_id);
		if ($this->usuario_super_admin) $this->usuario_admin=1;
   	gpwCriarCodigoSeguranca();
		return true;
		}


	public function mudar_conta($usuario_id) {
		global $config;
		$this->registrarLogout($this->usuario_id);
		$sql = new BDConsulta;


		if ($usuario_id==-1){
			//caso de login autom?tico gen?rico

			$this->usuario_id=1;
			$this->usuario_cia=1;
			$this->usuario_ativo=1;
			$this->usuario_posto=null;
			$this->usuario_nomeguerra='Visitante';
			$this->usuario_nome=null;
			$this->usuario_nome_completo=null;
			$this->usuario_funcao=null;
			$this->usuario_dept=null;
			$this->usuario_grupo_dept=null;
			$this->usuario_email=null;
			$this->usuario_email2=null;
			$this->usuario_admin=null;
			$this->usuario_observador=null;
			$this->usuario_pode_oculta=null;

			$achado=array();
			$achado[]=array('modulo'=>'admin', 'objeto'=>null, 'acesso'=>1, 'editar'=>1, 'adicionar'=>1,'excluir'=>1,'aprovar'=>1);
			$achado[]=array('modulo'=>'nao_admin', 'objeto'=>null, 'acesso'=>0, 'editar'=>1, 'adicionar'=>1,'excluir'=>1,'aprovar'=>1);
			$this->usuario_acesso['negar']=$achado;

			$achado=array();
			$achado[]=array('modulo'=>'nao_admin', 'objeto'=>null, 'acesso'=>1, 'editar'=>0, 'adicionar'=>0,'excluir'=>0,'aprovar'=>0);
			$this->usuario_acesso['permitir']=$achado;

			$sql->adTabela('modulos');
			$sql->adCampo('mod_diretorio, mod_ativo');
			$this->modulo_ativo = $sql->listaVetorChave('mod_diretorio', 'mod_ativo');
			$sql->limpar();

			$this->usuario_pode_todas_cias = false;
			$this->usuario_pode_outra_cia = false;
			$this->usuario_pode_lateral = false;
			$this->usuario_pode_superior = false;
			$this->usuario_pode_todos_depts = 0;
			$this->usuario_pode_dept_subordinado = 0;
			$this->usuario_pode_dept_lateral = 0;
			$this->usuario_pode_dept_superior = 0;
			$this->usuario_tem_lista_grupo=false;
			$this->usuario_lista_grupo=1;
			$this->usuario_lista_grupo_vetor=array(0 => 1);
			$this->cor_msg_nao_lida='fbfbda';
			$this->cor_msg_realce='ffffff';
			$this->carregarPrefs(1);
			$this->setUsuarioLocalidade();
			$this->checarEstilo();
			$this->usuario_super_admin=false;
			$this->usuario_admin=false;
			$this->usuario_observador=false;
			return true;
			}


		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo('
		usuario_id,
		contato_posto AS usuario_posto,
		contato_nomeguerra AS usuario_nomeguerra,
		'.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS usuario_nome,
		contato_nomecompleto AS usuario_nome_completo,
		contato_funcao AS usuario_funcao,
		contato_cia AS usuario_cia,
		contato_dept AS usuario_dept,
		usuario_grupo_dept,
		contato_email AS usuario_email,
		contato_email2 AS usuario_email2,
		usuario_admin,
		usuario_observador,
		usuario_pode_oculta,
		usuario_ativo');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$preparar = $sql->prepare();
		$sql->carregarObjeto($this);
		$sql->limpar();

		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_pai');
		$sql->adOnde('usuario_grupo_usuario ='.(int)$usuario_id);
		$sql->adOnde('usuario_grupo_pai !='.(int)$usuario_id);
		$lista=$sql->listaVetorChave('usuario_grupo_pai', 'usuario_grupo_pai');
		$sql->limpar();
		if (count($lista)){
			$permitido=array();
			$this->usuario_tem_lista_grupo=true;
			$sql->adTabela('usuarios');
			$sql->adCampo('usuario_contas');
			$sql->adOnde('usuario_id ='.(int)$usuario_id);
			$contas=$sql->resultado();
			$sql->limpar();
			$contas=explode(',',$contas);
			foreach($contas as $linha) if (isset($lista[$linha]) || $linha==$usuario_id) $permitido[]=$linha;
			if (!in_array($usuario_id, $permitido)) $permitido[]=$usuario_id;
			$this->usuario_lista_grupo_vetor=$permitido;
			$permitido=implode(',',$permitido);
			$this->usuario_lista_grupo=$permitido;
			}
		else {
			$this->usuario_tem_lista_grupo=false;
			$this->usuario_lista_grupo=$usuario_id;
			$this->usuario_lista_grupo_vetor=array(0 => $usuario_id);
			}

		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_login2, usuario_senha2');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$outra_conta=$sql->Linha();
		$sql->limpar();

		if ($outra_conta['usuario_login2'] && $outra_conta['usuario_senha2']) $this->conta_conjunta=1;

		$sql->adTabela('preferencia_cor');
		$sql->adCampo('cor_msg_nao_lida, cor_msg_realce');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$cores=$sql->Linha();
		$sql->limpar();

		if (!isset($cores['cor_msg_nao_lida'])){
			$sql->adTabela('preferencia_cor');
			$sql->adCampo('cor_msg_nao_lida, cor_msg_realce');
			$sql->adOnde('usuario_id=0');
			$cores=$sql->Linha();
			$sql->limpar();
			}


		if ($this->usuario_observador){
			$achado=array();
			$achado[]=array('modulo'=>'admin', 'objeto'=>null, 'acesso'=>1, 'editar'=>1, 'adicionar'=>1,'excluir'=>1,'aprovar'=>1);
			$achado[]=array('modulo'=>'nao_admin', 'objeto'=>null, 'acesso'=>0, 'editar'=>1, 'adicionar'=>1,'excluir'=>1,'aprovar'=>1);
			$this->usuario_acesso['negar']=$achado;

			$achado=array();
			$achado[]=array('modulo'=>'nao_admin', 'objeto'=>null, 'acesso'=>1, 'editar'=>0, 'adicionar'=>0,'excluir'=>0,'aprovar'=>0);
			$this->usuario_acesso['permitir']=$achado;
			}
		else {
			$sql->adTabela('perfil_acesso');
			$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
			$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
			$sql->adCampo('perfil_acesso_modulo AS modulo, perfil_acesso_objeto AS objeto, perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
			$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
			$sql->adOnde('perfil_acesso_negar = 1');
			$achado=$sql->lista();
			$sql->limpar();
	
			$this->usuario_acesso['negar']=$achado;
	
	
			$sql->adTabela('perfil_acesso');
			$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
			$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
			$sql->adCampo('perfil_acesso_modulo AS modulo, perfil_acesso_objeto AS objeto, perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
			$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
			$sql->adOnde('perfil_acesso_negar = 0');
			$achado=$sql->lista();
			$sql->limpar();
	
			$this->usuario_acesso['permitir']=$achado;
			}
		$sql->adTabela('modulos');
		$sql->adCampo('mod_diretorio, mod_ativo');
		$this->modulo_ativo = $sql->listaVetorChave('mod_diretorio', 'mod_ativo');
		$sql->limpar();


		if (checarModulo('email', 'acesso', $usuario_id, 'acesso_4')) $this->usuario_acesso_email=4;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_3')) $this->usuario_acesso_email=3;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_2')) $this->usuario_acesso_email=2;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_1')) $this->usuario_acesso_email=1;
		else $this->usuario_acesso_email=0;

		$this->usuario_pode_todas_cias = checarModulo('cias', 'acesso', $usuario_id, 'pode_todas');
		$this->usuario_pode_outra_cia = checarModulo('cias', 'acesso', $usuario_id, 'pode_subordinada');
		$this->usuario_pode_lateral = checarModulo('cias', 'acesso', $usuario_id, 'pode_lateral');
		$this->usuario_pode_superior = checarModulo('cias', 'acesso', $usuario_id, 'pode_superior');

		if (!$this->usuario_pode_superior) $this->usuario_pode_todas_cias=false;
		if (!$this->usuario_pode_lateral) $this->usuario_pode_todas_cias=false;
		if (!$this->usuario_pode_outra_cia) $this->usuario_pode_todas_cias=false;

		//Permiss?o de navegar nos departamento
		if($this->profissional){
			$this->usuario_pode_todos_depts = checarModulo('depts', 'acesso', $usuario_id, 'pode_todos');
			$this->usuario_pode_dept_subordinado = checarModulo('depts', 'acesso', $usuario_id, 'pode_subordinado');
			$this->usuario_pode_dept_lateral = checarModulo('depts', 'acesso', $usuario_id, 'pode_lateral');
			$this->usuario_pode_dept_superior = checarModulo('depts', 'acesso', $usuario_id, 'pode_superior');

			if (!$this->usuario_pode_dept_subordinado) $this->usuario_pode_todos_depts=null;
			if (!$this->usuario_pode_dept_lateral) $this->usuario_pode_todos_depts=null;
			if (!$this->usuario_pode_dept_superior) $this->usuario_pode_todos_depts=null;
			}
		else{
			$this->usuario_pode_todos_depts = 1;
			$this->usuario_pode_dept_subordinado = 1;
			$this->usuario_pode_dept_lateral = 1;
			$this->usuario_pode_dept_superior = 1;
			}

		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_pai');
		$sql->adOnde('usuario_grupo_usuario ='.(int)$usuario_id);
		$sql->adOnde('usuario_grupo_pai !='.(int)$usuario_id);
		$lista=$sql->listaVetorChave('usuario_grupo_pai', 'usuario_grupo_pai');
		$sql->limpar();

		if (count($lista)){
			$permitido=array();
			$this->usuario_tem_lista_grupo=true;
			$sql->adTabela('usuarios');
			$sql->adCampo('usuario_contas');
			$sql->adOnde('usuario_id ='.(int)$usuario_id);
			$contas=$sql->resultado();
			$sql->limpar();
			$contas=explode(',',$contas);
			foreach($contas as $linha) if (isset($lista[$linha]) || $linha==$usuario_id) $permitido[]=$linha;
			if (!in_array($usuario_id, $permitido)) $permitido[]=$usuario_id;
			$this->usuario_lista_grupo_vetor=$permitido;
			$permitido=implode(',',$permitido);
			$this->usuario_lista_grupo=$permitido;
			}
		else {
			$this->usuario_tem_lista_grupo=false;
			$this->usuario_lista_grupo=$usuario_id;
			$this->usuario_lista_grupo_vetor=array(0 => $usuario_id);
			}

		if (!$this->profissional || !$config['filtrar_usuario_dept']) $this->usuario_dept=null;
		elseif ($config['filtrar_usuario_dept'] && $this->usuario_dept) $this->setEstado('dept_id', $this->usuario_dept);
		elseif ($this->usuario_dept && !$this->usuario_pode_dept_subordinado && !$this->usuario_pode_dept_lateral && !$this->usuario_pode_dept_superior) $this->setEstado('dept_id', $this->usuario_dept);
		
		$this->cor_msg_nao_lida=(isset($cores['cor_msg_nao_lida']) ? $cores['cor_msg_nao_lida'] :'fbfbda');
		$this->cor_msg_realce=(isset($cores['cor_msg_realce']) ? $cores['cor_msg_realce'] :'ffffff');
		$this->carregarPrefs($this->usuario_id);
		$this->setUsuarioLocalidade();
		$this->checarEstilo();
		$this->usuario_super_admin=verificaAdministrador($this->usuario_id);
		$this->usuario_ver_tudo=verificaVerTudo($this->usuario_id);
		if ($this->usuario_super_admin) $this->usuario_admin=1;
		return true;
		}


	public function carregar_usuario($usuario_id) {
		global $config;
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo('usuario_id, contato_posto AS usuario_posto, contato_nomeguerra AS usuario_nomeguerra, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS usuario_nome, contato_nomecompleto AS usuario_nome_completo, contato_funcao AS usuario_funcao, contato_cia AS usuario_cia, contato_dept AS usuario_dept, usuario_grupo_dept, contato_email AS usuario_email, contato_email2 AS usuario_email2, usuario_admin, usuario_observador, usuario_pode_oculta, usuario_ativo');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$preparar = $sql->prepare();
		$sql->carregarObjeto($this);
		$sql->limpar();
	
		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_pai');
		$sql->adOnde('usuario_grupo_usuario ='.(int)$usuario_id);
		$sql->adOnde('usuario_grupo_pai !='.(int)$usuario_id);
		$lista=$sql->listaVetorChave('usuario_grupo_pai', 'usuario_grupo_pai');
		$sql->limpar();
		if (count($lista)){
			$permitido=array();
			$this->usuario_tem_lista_grupo=true;
			$sql->adTabela('usuarios');
			$sql->adCampo('usuario_contas');
			$sql->adOnde('usuario_id ='.(int)$usuario_id);
			$contas=$sql->resultado();
			$sql->limpar();
			$contas=explode(',',$contas);
			foreach($contas as $linha) if (isset($lista[$linha]) || $linha==$usuario_id) $permitido[]=$linha;
			if (!in_array($usuario_id, $permitido)) $permitido[]=$usuario_id;
			$this->usuario_lista_grupo_vetor=$permitido;
			$permitido=implode(',',$permitido);
			$this->usuario_lista_grupo=$permitido;
			}
		else {
			$this->usuario_tem_lista_grupo=false;
			$this->usuario_lista_grupo=$usuario_id;
			$this->usuario_lista_grupo_vetor=array(0 => $usuario_id);
			}

		$sql->adTabela('usuarios');
		$sql->adCampo('usuario_login2, usuario_senha2');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$outra_conta=$sql->Linha();
		$sql->limpar();

		if ($outra_conta['usuario_login2'] && $outra_conta['usuario_senha2']) $this->conta_conjunta=1;

		$sql->adTabela('preferencia_cor');
		$sql->adCampo('cor_msg_nao_lida, cor_msg_realce');
		$sql->adOnde('usuario_id ='.(int)$usuario_id);
		$cores=$sql->Linha();
		$sql->limpar();

		if (!$cores['cor_msg_nao_lida']){
			$sql->adTabela('preferencia_cor');
			$sql->adCampo('cor_msg_nao_lida, cor_msg_realce');
			$sql->adOnde('usuario_id =0');
			$cores=$sql->Linha();
			$sql->limpar();
			}

		if ($this->usuario_observador){
			$achado=array();
			$achado[]=array('modulo'=>'admin', 'objeto'=>null, 'acesso'=>1, 'editar'=>1, 'adicionar'=>1,'excluir'=>1,'aprovar'=>1);
			$achado[]=array('modulo'=>'nao_admin', 'objeto'=>null, 'acesso'=>0, 'editar'=>1, 'adicionar'=>1,'excluir'=>1,'aprovar'=>1);
			$this->usuario_acesso['negar']=$achado;

			$achado=array();
			$achado[]=array('modulo'=>'nao_admin', 'objeto'=>null, 'acesso'=>1, 'editar'=>0, 'adicionar'=>0,'excluir'=>0,'aprovar'=>0);
			$this->usuario_acesso['permitir']=$achado;
			}
		else {
			$sql->adTabela('perfil_acesso');
			$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
			$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
			$sql->adCampo('perfil_acesso_modulo AS modulo, perfil_acesso_objeto AS objeto, perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
			$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
			$sql->adOnde('perfil_acesso_negar = 1');
			$achado=$sql->lista();
			$sql->limpar();
	
			$this->usuario_acesso['negar']=$achado;
	
			$sql->adTabela('perfil_acesso');
			$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
			$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
			$sql->adCampo('perfil_acesso_modulo AS modulo, perfil_acesso_objeto AS objeto, perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
			$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
			$sql->adOnde('perfil_acesso_negar = 0');
			$achado=$sql->lista();
			$sql->limpar();
	
			$this->usuario_acesso['permitir']=$achado;
			}
			
		$sql->adTabela('modulos');
		$sql->adCampo('mod_diretorio, mod_ativo');
		$this->modulo_ativo = $sql->listaVetorChave('mod_diretorio', 'mod_ativo');
		$sql->limpar();


		if (checarModulo('email', 'acesso', $usuario_id, 'acesso_4')) $this->usuario_acesso_email=4;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_3')) $this->usuario_acesso_email=3;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_2')) $this->usuario_acesso_email=2;
		elseif (checarModulo('email', 'acesso', $usuario_id, 'acesso_1')) $this->usuario_acesso_email=1;
		else $this->usuario_acesso_email=0;

		$this->usuario_pode_todas_cias = checarModulo('cias', 'acesso', $usuario_id, 'pode_todas');
		$this->usuario_pode_outra_cia = checarModulo('cias', 'acesso', $usuario_id, 'pode_subordinada');
		$this->usuario_pode_lateral = checarModulo('cias', 'acesso', $usuario_id, 'pode_lateral');
		$this->usuario_pode_superior = checarModulo('cias', 'acesso', $usuario_id, 'pode_superior');

		if (!$this->usuario_pode_superior) $this->usuario_pode_todas_cias=false;
		if (!$this->usuario_pode_lateral) $this->usuario_pode_todas_cias=false;
		if (!$this->usuario_pode_outra_cia) $this->usuario_pode_todas_cias=false;

		//Permiss?o de navegar nos departamento
		if($this->profissional){
			$this->usuario_pode_todos_depts = checarModulo('depts', 'acesso', $usuario_id, 'pode_todos');
			$this->usuario_pode_dept_subordinado = checarModulo('depts', 'acesso', $usuario_id, 'pode_subordinado');
			$this->usuario_pode_dept_lateral = checarModulo('depts', 'acesso', $usuario_id, 'pode_lateral');
			$this->usuario_pode_dept_superior = checarModulo('depts', 'acesso', $usuario_id, 'pode_superior');

			if (!$this->usuario_pode_dept_subordinado) $this->usuario_pode_todos_depts=null;
			if (!$this->usuario_pode_dept_lateral) $this->usuario_pode_todos_depts=null;
			if (!$this->usuario_pode_dept_superior) $this->usuario_pode_todos_depts=null;
			}
		else{
			$this->usuario_pode_todos_depts = 1;
			$this->usuario_pode_dept_subordinado = 1;
			$this->usuario_pode_dept_lateral = 1;
			$this->usuario_pode_dept_superior = 1;
			}

		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_pai');
		$sql->adOnde('usuario_grupo_usuario ='.(int)$usuario_id);
		$sql->adOnde('usuario_grupo_pai !='.(int)$usuario_id);
		$lista=$sql->listaVetorChave('usuario_grupo_pai', 'usuario_grupo_pai');
		$sql->limpar();

		if (count($lista)){
			$permitido=array();
			$this->usuario_tem_lista_grupo=true;
			$sql->adTabela('usuarios');
			$sql->adCampo('usuario_contas');
			$sql->adOnde('usuario_id ='.(int)$usuario_id);
			$contas=$sql->resultado();
			$sql->limpar();
			$contas=explode(',',$contas);
			foreach($contas as $linha) if (isset($lista[$linha]) || $linha==$usuario_id) $permitido[]=$linha;
			if (!in_array($usuario_id, $permitido)) $permitido[]=$usuario_id;
			$this->usuario_lista_grupo_vetor=$permitido;
			$permitido=implode(',',$permitido);
			$this->usuario_lista_grupo=$permitido;
			}
		else {
			$this->usuario_tem_lista_grupo=false;
			$this->usuario_lista_grupo=$usuario_id;
			$this->usuario_lista_grupo_vetor=array(0 => $usuario_id);
			}

		if (!$this->profissional || !$config['filtrar_usuario_dept']) $this->usuario_dept=null;
		else if ($config['filtrar_usuario_dept'] && $this->usuario_dept) $this->setEstado('dept_id', $this->usuario_dept);

		$this->cor_msg_nao_lida=($cores['cor_msg_nao_lida']? $cores['cor_msg_nao_lida'] :'fbfbda');
		$this->cor_msg_realce=($cores['cor_msg_realce']? $cores['cor_msg_realce'] :'ffffff');

		$this->carregarPrefs($this->usuario_id);
		$this->checarEstilo();

		$this->usuario_super_admin=verificaAdministrador($this->usuario_id);
		$this->usuario_ver_tudo=verificaVerTudo($this->usuario_id);
		if ($this->usuario_super_admin) $this->usuario_admin=1;
		return true;
		}

	public function registrarLogin() {
		if ($this->usuario_id){
		    $this->registrarLogout($this->usuario_id);

			$sql = new BDConsulta;
			$sql->adTabela('usuario_reg_acesso');
			$sql->adInserir('usuario_id', $this->usuario_id);
			$sql->adInserir('entrou', 'now()', false, true);
			$sql->adInserir('usuario_ip', previnirXSS($_SERVER['REMOTE_ADDR']));
			$sql->exec();
			$this->ultimo_id_inserido = db_insert_id('usuario_reg_acesso','usuario_reg_acesso_id');
			$sql->limpar();
			}
		}

	public function registrarLogout( $usuario_id) {

		$sql = new BDConsulta;
		$sql->adTabela('usuario_reg_acesso');
		$sql->adAtualizar('saiu', date('Y-m-d H:i:s'));
		$sql->adOnde('usuario_id = '.(int)$usuario_id.' AND saiu IS NULL');

		if ($usuario_id > 0) {
			$sql->exec();
			$sql->limpar();
			}
		}

	public function atualizarUltimaAcao( ) {
	    global $config;

        $ultimo_id_inserido = $this->ultimo_id_inserido;

		if ($ultimo_id_inserido > 0) {
			$sql = new BDConsulta;
            $sql->adTabela('usuario_reg_acesso');
            $sql->adCampo('usuario_id, saiu, usuario_ip');
            $sql->adOnde('usuario_reg_acesso_id = '.$ultimo_id_inserido);
            $reg = $sql->Linha();
            $sql->limpar();

            if((!$reg || $reg['saiu'] || $reg['usuario_id'] != $this->usuario_id || $reg['usuario_ip'] != previnirXSS($_SERVER['REMOTE_ADDR'])) && (!array_key_exists('validar_registro_acesso', $config) || !$config['validar_registro_acesso'])){
                $this->ultimo_id_inserido = -1;
                $this->usuario_id = -1;
                return 0;
            }

			$sql->adTabela('usuario_reg_acesso');
			$sql->adAtualizar('ultima_atividade', date('Y-m-d H:i:s'));
			$sql->adOnde('usuario_reg_acesso_id = '.$ultimo_id_inserido);
			$sql->exec();
			$sql->limpar();
			}

        return $ultimo_id_inserido;
		}

	public function logout() {
	    if($this->usuario_id > 0) {
            $this->registrarLogout( $this->usuario_id );
            }
		}

	public function fazerLogin() {
		return ($this->usuario_id <= 0) ? true : false;
		}

	public function getPref( $nome) {
		return (isset($this->usuario_prefs[$nome]) ? $this->usuario_prefs[$nome] : null);
		}

	public function setPref( $nome, $val) {
		$this->usuario_prefs[$nome] = $val;
		}

	public function carregarPrefs( $uid = 0) {
		$sql = new BDConsulta;
		$sql->adTabela('preferencia');
		$sql->adCampo('preferencia.*');
		if ($uid) $sql->adOnde('usuario = '.(int)$uid);
		else $sql->adOnde('usuario IS NULL OR usuario=0');
		$prefs = $sql->Linha();
		$sql->limpar();
		if (is_array($prefs) && !count($prefs)){
			$sql->adTabela('preferencia');
			$sql->adCampo('preferencia.*');
			$sql->adOnde('usuario IS NULL OR usuario=0');
			$prefs = $sql->Linha();
			$sql->limpar();
			}
		$this->usuario_prefs = array_merge($this->usuario_prefs, (array)$prefs);
		}

	public function getModulosInstalados() {
		$sql = new BDConsulta;
		$sql->adTabela('modulos');
		$sql->adCampo('mod_diretorio, mod_ui_nome');
		$sql->adOrdem('mod_diretorio');
		return ($sql->ListaChave());
		}

	public function getModulosAtivos() {
		$sql = new BDConsulta;
		$sql->adTabela('modulos');
		$sql->adCampo('mod_diretorio, mod_ui_nome');
		$sql->adOnde('mod_ativo = 1');
		$sql->adOrdem('mod_diretorio');
		return ($sql->ListaChave());
		}

	public function getMenuModulos() {
		$sql = new BDConsulta;
		$sql->adTabela('modulos');
		$sql->adCampo('mod_diretorio, mod_ui_nome, mod_ui_icone, mod_texto_botao');
		$sql->adOnde('mod_ativo > 0 AND mod_ui_ativo > 0 AND mod_diretorio !=\'publico\'');
		$sql->adOnde('mod_tipo != \'utilitario\'');
		$sql->adOrdem('mod_ui_ordem');
		return ($sql->Lista());
		}

	public function ModuloAtivo( $modulo) {
		$sql = new BDConsulta;
		$sql->adTabela('modulos');
		$sql->adCampo('mod_ativo');
		$sql->adOnde('mod_diretorio = \''.$modulo.'\'');
		$resultado = $sql->Resultado();
		$sql->limpar();
		return $resultado;
		}

  public function resetJS(){
    $this->ckeditorjs_carregado = false;
    $this->extjs_carregado = false;
    $this->calendariojs_carregado = false;
    $this->calendariodatahorajs_carregado = false;
    $this->multiselecaojs_carregado = false;
    }

	public function carregarCabecalhoJS() {
		global $m, $a, $config;

		echo '<script type="text/javascript" src="'.get_base_url().'/lib/jquery/jquery-1.8.3.min.js"></script>';
		echo '<script type="text/javascript" src="'.get_base_url().'/lib/mootools/mootools.js"></script>';

    if (isset($config['estilo_css'])&& $config['estilo_css']=='metro') echo '<script type="text/javascript" src="'.get_base_url().'/js/metro.js"></script>';
   	else echo '<script type="text/javascript" src="'.get_base_url().'/js/classico.js"></script>';

    echo '<script type="text/javascript" src="'.get_base_url().'/js/gpweb.js?dc=3"></script>';

		if (!isset($m))	return;

		$this->getModuloJS($m, $a, true);
		}

	public function getModuloJS( $modulo, $arquivo = null, $carregar_todos = false) {
		$raiz = BASE_DIR;
		if (substr($raiz, -1) != '/') $raiz .= '/';
		$base = get_base_url();
		if (substr($base, -1) != '/')	$base .= '/';
		if ($carregar_todos || !$arquivo) {
			if (file_exists($raiz.'modulos/'.$modulo.'/'.$modulo.'.modulo.js')) echo '<script type="text/javascript" src="'.$base.'modulos/'.$modulo.'/'.$modulo.'.modulo.js"></script>';
			}
		if (isset($arquivo) && file_exists($raiz.'modulos/'.$modulo.'/'.$arquivo.'.js')) echo '<script type="text/javascript" src="'.$base.'modulos/'.$modulo.'/'.$arquivo.'.js"></script>';
		}

    /**
    * Inicializa os campos autom?ticos e tooltip
    */
	public function carregarRodapeJS() {
    global $Aplic, $m, $a, $u;
		echo '<script>';

		echo 'function tela_anterior(){url_passar(0, \'m=admin&a=tela_anterior_pro&m_ant='.$m.'&a_ant='.$a.'&u_ant='.$u.'\');}';

    echo 'function lista_todo(convites){listaTodo(convites,'.$Aplic->usuario_id.');}';
    echo '$jq(function(){';
    echo 'var as = [];';
    echo 'as = $jq("textarea[data-gpweb-cmp=\'ckeditor\']");';
    if($Aplic->profissional){
        $botoes_ckeditor=botoesCKEditor();
        echo "as.each(function(){CKEDITOR.inline(this,".$botoes_ckeditor.")});";
        }
    else{
        echo "as.each(function(){ CKEDITOR.replace(this, {toolbar :[['Source','NewPage','PasteText','PasteFromWord','SpellChecker','Scayt','Find', 'Undo','Redo','RemoveFormat','NumberedList','BulletedList','Outdent','Indent','Blockquote', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','Link','Unlink','HorizontalRule','PageBreak'],['Bold','Italic','Underline','Strike','Subscript','Superscript','TextColor','BGColor','Image','Table','SpecialChar','Styles','Format','Font','FontSize']]});});";
        }

    echo 'as = $jq("input[data-gpweb-cmp=\'calendario\']");';
    echo 'as.each(function(){criarCampoCalendario(this);});';

    echo '$$(\'span\').each(function(span){if (span.getAttribute(\'title\')) as.push(span);}); new Tips(as);';

    echo '});</script>';
		}

    /**
    * Insere o script do combo de multisele??o.
    */
    public function carregarComboMultiSelecaoJS(){
        if($this->multiselecaojs_carregado) return;
        $this->multiselecaojs_carregado = true;

        echo '<script src="'.get_base_url().'/lib/jqmultiselect/jquery.multiselect.js" type="text/javascript"></script>';
        }

    /**
    * Insere o script de editor (CKEditor)
    */
    public function carregarCKEditorJS( $altura='100'){
        if($this->ckeditorjs_carregado) return;
        $this->ckeditorjs_carregado= true;
        echo '<script type="text/javascript" src="'.get_base_url().'/lib/ckeditor4/ckeditor.js"></script>';
        echo '<style>.cke_textarea_inline {padding: 10px; height: '.$altura.'px; background-color:#fff; overflow: auto; -webkit-border-radius:4px; border-radius:4px; -moz-border-radius:4px; border:1px #a6a6a6 solid; -webkit-appearance: textfield;}</style>';
        }

    public function carregarExtJS( $carregarChart = false){
        global $config;

        if($this->extjs_carregado) return;
        $this->extjs_carregado= true;

        $estilo_interface=(isset($config['estilo_css']) ? $config['estilo_css'] : 'classico');

        if($estilo_interface === 'metro'){
            echo '<link href="'.get_base_url().'/lib/extjs/resources/css/ext-all.css" rel="stylesheet" type="text/css" />';
            }
        else{
            echo '<link href="'.get_base_url().'/lib/extjs/resources/css/ext-all-gray.css" rel="stylesheet" type="text/css" />';
            }

        echo '<link href="'.get_base_url().'/lib/extjs/resources/css/gpweb.css" rel="stylesheet" type="text/css" />';

        echo '<script src="'.get_base_url().'/lib/extjs/ext-all.js" type="text/javascript"></script>';
        echo '<script src="'.get_base_url().'/lib/extjs/ux/RowExpander.js" type="text/javascript"></script>';
        echo '<script src="'.get_base_url().'/lib/extjs/ux/UXDateTimeField.js" type="text/javascript"></script>';
        echo '<script src="'.get_base_url().'/lib/extjs/locale/ext-lang-pt_BR-min.js" type="text/javascript"></script>';

        if($carregarChart){
            echo '<link href="'.get_base_url().'/lib/extjs/ux/gpwchart/gpwhchart.css" rel="stylesheet" type="text/css" />';
            echo '<link href="'.get_base_url().'/lib/extjs/ux/gpwchart/gpwvchart.css" rel="stylesheet" type="text/css" />';
            echo '<script src="'.get_base_url().'/lib/extjs/ux/gpwchart/GPWChart.js" type="text/javascript"></script>';
            }

        echo '<script src="'.get_base_url().'/lib/extjs/locale/ext-lang-pt_BR-min.js" type="text/javascript"></script>';
        }

    /**
    * Insere os scripts de calend?rio simples (somente data)
    */
    public function carregarCalendarioJS() {
        if($this->calendariojs_carregado) return;
        $this->calendariojs_carregado = true;
        echo '<script type="text/javascript" src="'.get_base_url().'/js/calendario.js"></script>';
        echo '<script type="text/javascript" src="'.get_base_url().'/lib/calendario/src/js/jscal2.js"></script>';
        echo '<script type="text/javascript" src="'.get_base_url().'/lib/calendario/src/js/pt.js"></script>';
        include BASE_DIR.'/js/calendario.php';
        }

    /**
    * Insere os scripts de calend?rio com data e hora
    */
    public function carregarCalendarioDataHoraJS() {
        if($this->calendariodatahorajs_carregado) return;
        $this->calendariodatahorajs_carregado = true;

        echo '<style type="text/css">@import url('.get_base_url().'/lib/jscalendar/skins/aqua/theme.css);</style>';
        echo '<script type="text/javascript" src="'.get_base_url().'/js/calendar.js"></script>';
        echo '<script type="text/javascript" src="'.get_base_url().'/lib/jscalendar/calendar.js"></script>';
        if (file_exists (BASE_DIR.'/lib/jscalendar/lang/calendar-pt.js')) echo '<script type="text/javascript" src="'.get_base_url().'/lib/jscalendar/lang/calendar-pt.js"></script>';
        else echo '<script type="text/javascript" src="'.get_base_url().'/lib/jscalendar/lang/calendar-en.js"></script>';
        echo '<script type="text/javascript" src="'.get_base_url().'/lib/jscalendar/calendar-setup.js"></script>';
        include_once(BASE_DIR.'/js/calendar.php');
        }

	public function carregarCalendario() {
		echo '<!DOCTYPE html PUBLIC
          "-//W3C//DTD XHTML 1.0 Transitional//EN"
          "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
		echo '<script type="text/javascript" src="'.get_base_url().'/js/calendario.js"></script>';
		echo '<script type="text/javascript" src="'.get_base_url().'/lib/calendario/src/js/jscal2.js"></script>';
		echo '<script type="text/javascript" src="'.get_base_url().'/lib/calendario/src/js/pt.js"></script>';
		include BASE_DIR.'/js/calendario.php';
		}

	public function carregarCalendarJS() {
		echo '<style type="text/css">@import url('.get_base_url().'/lib/jscalendar/skins/aqua/theme.css);</style>';
		echo '<script type="text/javascript" src="'.get_base_url().'/js/calendar.js"></script>';
		echo '<script type="text/javascript" src="'.get_base_url().'/lib/jscalendar/calendar.js"></script>';
		if (file_exists (BASE_DIR.'/lib/jscalendar/lang/calendar-pt.js')) echo '<script type="text/javascript" src="'.get_base_url().'/lib/jscalendar/lang/calendar-pt.js"></script>';
		else echo '<script type="text/javascript" src="'.get_base_url().'/lib/jscalendar/lang/calendar-en.js"></script>';
		echo '<script type="text/javascript" src="'.get_base_url().'/lib/jscalendar/calendar-setup.js"></script>';
		include BASE_DIR.'/js/calendar.php';
		}

    public function calendarioInfoDataProjeto( $projeto_id=0){
        $saida="\n".'<script type="text/javascript">'."\n";

        $saida.='CALENDARIO_INFO_DATA = {';
        if ($projeto_id){
            $sql = new BDConsulta;
            $sql->adTabela('tarefas', 't');
            $sql->adCampo('tarefa_nome, tarefa_inicio, tarefa_fim');
            $sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
            $sql->setLimite(0,1000);
            $tarefas = $sql->Lista();
            $sql->limpar();
            $qnt_t=count($tarefas);
            $qnt=0;
            $vetor=array();
            foreach ($tarefas as $valor) {
                $qnt++;
                $data_tarefa = new CData($valor['tarefa_inicio']);
                $indice1=$data_tarefa->format("%Y%m%d");
                $data_tarefa = new CData($valor['tarefa_fim']);
                $indice2=$data_tarefa->format("%Y%m%d");
                $tarefa_nome = htmlspecialchars($valor['tarefa_nome']);
                if ($indice1==$indice2){
                    if (isset($vetor[$indice1]) && $vetor[$indice1]) {
                        $vetor[$indice1].='<br><img src=\'./estilo/rondon/imagens/icones/inicio.gif\' /><img src=\'./estilo/rondon/imagens/icones/fim.gif\' /> '.$tarefa_nome;
                        $cor='calen_misto';
                        }
                    else{
                        $vetor[$indice1]='<img src=\'./estilo/rondon/imagens/icones/inicio.gif\' /><img src=\'./estilo/rondon/imagens/icones/fim.gif\' /> '.$tarefa_nome;
                        $cor='calen_mesmodia';
                        }
                    $saida.=$indice1.': { klass: "'.$cor.'", tooltip: "'.$vetor[$indice1].'"}, ';
                    }
                else{
                    if (isset($vetor[$indice1]) && $vetor[$indice1]) {
                        $vetor[$indice1].='<br><img src=\'./estilo/rondon/imagens/icones/inicio.gif\' /><img src=\'./estilo/rondon/imagens/icones/vazio.gif\' /> '.$tarefa_nome;
                        $cor='calen_misto';
                        }
                    else{
                        $vetor[$indice1]='<img src=\'./estilo/rondon/imagens/icones/inicio.gif\' /><img src=\'./estilo/rondon/imagens/icones/vazio.gif\' /> '.$tarefa_nome;
                        $cor='calen_tarefa_ini';
                        }
                    $saida.=$indice1.': { klass: "'.$cor.'", tooltip: "'.$vetor[$indice1].'"}, ';
                    if(isset($vetor[$indice2]) && $vetor[$indice2]) {
                        $vetor[$indice2].='<br><img src=\'./estilo/rondon/imagens/icones/vazio.gif\' /><img src=\'./estilo/rondon/imagens/icones/fim.gif\' /> '.$tarefa_nome;
                        $cor='calen_misto';
                        }
                    else{
                        $vetor[$indice2]='<img src=\'./estilo/rondon/imagens/icones/vazio.gif\' /><img src=\'./estilo/rondon/imagens/icones/fim.gif\' /> '.$tarefa_nome;
                        $cor='calen_tarefa_fim';
                        }
                    $saida.=$indice2.': { klass: "'.$cor.'", tooltip: "'.$vetor[$indice2].'"}'.($qnt_t !=1 && $qnt !=$qnt_t ? ', ' : '');
                    }
                }
            }
        $saida.='};</script>';

        return $saida;
        }

  	public function mensagensNaoLidas( $usuario_id = -1){
	   	if($usuario_id == -1) $usuario_id = $this->usuario_id;
	    $sql = new BDConsulta();
			$sql->adTabela('msg_usuario');
			if($this->getPref('agrupar_msg')) $sql->adCampo('count(DISTINCT msg_id)');
			else $sql->adCampo('count(msg_usuario_id)');
			$sql->adOnde('para_id '.($this->usuario_lista_grupo && $this->usuario_lista_grupo!=$usuario_id ? 'IN ('.$this->usuario_lista_grupo.')' : '='.$usuario_id));
			$sql->adOnde('status=0');
			$total = (int)$sql->Resultado();
			$sql->limpar();
			return $total;
    	}

    public function mensagensTotalCaixaEntrada( $usuario_id = -1){
			if($usuario_id == -1) $usuario_id = $this->usuario_id;
			$sql = new BDConsulta();
			$sql->adTabela('msg_usuario');
			if($this->getPref('agrupar_msg')) $sql->adCampo('count(DISTINCT msg_id)');
			else $sql->adCampo('count(msg_usuario_id)');
			$sql->adOnde('para_id '.($this->usuario_lista_grupo && $this->usuario_lista_grupo!=$usuario_id ? 'IN ('.$this->usuario_lista_grupo.')' : '='.$usuario_id));
			$sql->adOnde('status<2');
			$total = (int)$sql->Resultado();
			$sql->limpar();
			return $total;
			}

	public function mensagensTotalPendentes( $usuario_id = -1){
    if($usuario_id == -1) $usuario_id = $this->usuario_id;
    $sql = new BDConsulta();
		$sql->adTabela('msg_usuario');
		if($this->getPref('agrupar_msg')) $sql->adCampo('count(DISTINCT msg_id)');
		else $sql->adCampo('count(msg_usuario_id)');
		$sql->adOnde('para_id '.($this->usuario_lista_grupo && $this->usuario_lista_grupo!=$usuario_id ? 'IN ('.$this->usuario_lista_grupo.')' : '='.$usuario_id));
		$sql->adOnde('status=3');
		$total = (int)$sql->Resultado();
		$sql->limpar();
		return $total;
		}


	public function checarModulo( $m=null, $acesso='acesso', $usuario_id=null, $submodulo=null){
	  if($this->usuario_super_admin) return true;
		//checar se ? negado
		$negado=$this->usuario_acesso['negar'];
		foreach($negado as $linha) {
			if ((
			($linha['modulo']==$m && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
			($linha['modulo']=='todos' && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
			(($m!='admin' && $m!='sistema') && $linha['modulo']=='nao_admin' && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
			(($m=='admin' || $m=='sistema') && $linha['modulo']=='admin' && ($linha['objeto']==$submodulo || !$linha['objeto']))
			) && $linha[$acesso]=='1') return false;
			}
		$permitir=$this->usuario_acesso['permitir'];
		foreach($permitir as $linha) {
			if ((
			($linha['modulo']==$m && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
			($linha['modulo']=='todos' && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
			(($m!='admin' && $m!='sistema') && $linha['modulo']=='nao_admin' && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
			(($m=='admin' || $m=='sistema') && $linha['modulo']=='admin' && ($linha['objeto']==$submodulo || !$linha['objeto']))
			) && $linha[$acesso]=='1') return true;
			}
		return false;
		}

    public function checarModuloNegado( $m=null, $acesso='acesso', $usuario_id=null, $submodulo=null){
        if($this->usuario_super_admin) return false;

        //checar se ? negado
        $negado=$this->usuario_acesso['negar'];
        foreach($negado as $linha) {
            if ((
                    ($linha['modulo']==$m && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
                    ($linha['modulo']=='todos' && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
                    (($m!='admin' && $m!='sistema') && $linha['modulo']=='nao_admin' && ($linha['objeto']==$submodulo || !$linha['objeto'])) ||
                    (($m=='admin' || $m=='sistema') && $linha['modulo']=='admin' && ($linha['objeto']==$submodulo || !$linha['objeto']))
                ) && $linha[$acesso]=='1') return true;
        }

        return false;
    }

	public function modulo_ativo( $nome){
		if (!$nome) return '';
		return (isset($this->modulo_ativo[$nome]) ? $this->modulo_ativo[$nome] : false);
		}
	}

/********************************************************************************************

Classe CCaixaTab_nucleo define o esqueleto do sistema de visualiza??o em abas

********************************************************************************************/
class CCaixaTab_nucleo {
	public $tabs = null;
	public $ativo = null;
	public $baseHRef = null;
	public $baseInc;
	public $javascript = null;

	public function __construct( $baseHRef = '', $baseInc = '', $ativo = 0, $javascript = null) {
		$this->tabs = array();
		$this->ativo= $ativo;
		$this->baseHRef = ($baseHRef ? $baseHRef.'&' : '?');
		$this->javascript = $javascript;
		$this->baseInc = $baseInc;
		}

	public function getNomeTab( $idx) {
		if (isset($this->tabs[$idx][1])) return $this->tabs[$idx][1];
		else return '';
		}

	public function adicionar( $arquivo, $titulo, $traduzido = false, $chave = null, $titulo2=null, $texto=null) {
		$t = array($arquivo, $titulo, $traduzido, $titulo2, $texto);
		if (isset($chave)) $this->tabs[$chave] = $t;
		else $this->tabs[] = $t;
		}

	public function mostrar( $extra = '', $js_tabs = false) {
		global $Aplic, $tabAtualId, $tabNomeAtual;
		$this->carregarExtras($m, $a);
		reset($this->tabs);
		$s = '';

		$s .= '<table cellpadding=0 cellspacing=0 width="100%"><tr>';
		if (!$somente_tab) $s .= '<td style="white-space: nowrap">'.dica("Aba", "Visualizar as op??es na forma de abas. </P>M?todo prefer?ncial caso n?o deseja rolar a tela do navegador Web.").'<a class="botao" href="'.$this->baseHRef.'tab=0"><span>abas</span></a>'.dicaF().'<a class="botao" href="'.$this->baseHRef.'tab=-1"><span>lista</span></a></td>';
		$s .= $extra.'</tr></table>';
		echo $s;

		$s = '<table width="100%" cellpadding=0 cellspacing=0><tr>';
		if (count($this->tabs) - 1 < $this->ativo) $this->ativo= 0;
		foreach ($this->tabs as $k => $v) {
			$classe = ($k == $this->ativo) ? 'tabativo' : 'tabinativo';
			$s .= '<td width="1%" style="white-space: nowrap" class="tabsp"><img src="./estilo/rondon/imagens/shim.gif" height="1" width="1" alt="" /></td>';
			$s .= '<td id="tab_s_'.$k.'" width="1%" style="white-space: nowrap"';
			if ($js_tabs) $s .= ' class="'.$classe.'"';
			$s .= '><a href="';
			if ($this->javascript)	$s .= 'javascript:'.$this->javascript.'('.$this->ativo. ', '.$k.')';
			elseif ($js_tabs) $s .= 'javascript:mostrar_tab('.$k.')';
			else $s .= $this->baseHRef."tab=$k";
			$s .= '">'.$v[1].'</a></td>';
			}
		$s .= '<td style="white-space: nowrap" class="tabsp">&nbsp;</td></tr>';
		$s .= '<tr><td width="100%" colspan="'.(count($this->tabs) * 2 + 1).'" class="tabox">';
		echo $s;
		if ($this->baseInc.$this->tabs[$this->ativo][0] != '') {
			$tabAtualId = $this->ativo;
			$tabNomeAtual = $this->tabs[$this->ativo][1];
			if (!$js_tabs) require $this->baseInc .$this->tabs[$this->ativo][0].'.php';
			}
		if ($js_tabs) {
			foreach ($this->tabs as $k => $v) {
				echo '<div class="tab" id="tab_'.$k.'">';
				require $this->baseInc.$v[0].'.php';
				echo '</div>';
				}
			}
		echo '</td></tr></table>';

		}

	public function carregarExtras( $modulo, $arquivo = null) {
		global $Aplic;
		if (!isset($_SESSION['todas_tabs']) || !isset($_SESSION['todas_tabs'][$modulo])) return false;
		if ($arquivo) {
			if (isset($_SESSION['todas_tabs'][$modulo][$arquivo]) && is_array($_SESSION['todas_tabs'][$modulo][$arquivo])) $vetor_tab = &$_SESSION['todas_tabs'][$modulo][$arquivo];
			else return false;
			}
		else $vetor_tab = &$_SESSION['todas_tabs'][$modulo];
		$tab_contagem = 0;
		foreach ($vetor_tab as $elem_tab) {
			if (isset($elem_tab['modulo']) && $Aplic->ModuloAtivo($elem_tab['modulo'])) {
				$tab_contagem++;
				$this->adicionar($elem_tab['arquivo'], $elem_tab['nome']);
				}
			}
		return $tab_contagem;
		}

	public function acharTabModulo( $tab) {
		global $Aplic, $m, $a;
		if (!isset($_SESSION['todas_tabs']) || !isset($_SESSION['todas_tabs'][$m]))	return false;
		if (isset($a)) {
			if (isset($_SESSION['todas_tabs'][$m][$a]) && is_array($_SESSION['todas_tabs'][$m][$a])) $vetor_tab = &$_SESSION['todas_tabs'][$m][$a];
			else $vetor_tab = &$_SESSION['todas_tabs'][$m];
			}
		else $vetor_tab = &$_SESSION['todas_tabs'][$m];
		list($arquivo, $nome) = $this->tabs[$tab];
		foreach ($vetor_tab as $elem_tab) {
			if (isset($elem_tab['nome']) && $elem_tab['nome'] == $nome && $elem_tab['arquivo'] == $arquivo) return $elem_tab['modulo'];
			}
		return false;
		}

	}
/********************************************************************************************

Classe CBlocoTitulo_core define o esqueleto do sistema de bot?es do t?tulo de cada p?gina

********************************************************************************************/
class CBlocoTitulo_core {
	public $titulo = '';
	public $icone = '';
	public $modulo = '';
	public $celulas = null;
	public $ajudaref = '';

	public function __construct( $titulo, $icone = '', $modulo = '', $ajudaref = '') {
		$this->titulo = $titulo;
		$this->icon = $icone;
		$this->modulo = $modulo;
		$this->ajudaref = $ajudaref;
		$this->celulas1 = array();
		$this->celulas2 = array();
		$this->blocos = array();
		$this->mostrarajuda = checarModulo('ajuda', 'acesso');
		}

	public function adicionaBotaoCelula( $href='', $clicando='', $legenda, $icone = '', $titulo = '', $texto='', $prefixo = '', $sufixo = '') {
		$data='<table cellspacing=0 cellpadding=0><tr><td>'. dica($titulo, $texto). '<a class="botao" href="'.($href ? $href : 'javascript: void(0);').'" '.($clicando ? ' onclick="javascript:'.$clicando.'" ':'').'><span>'.($icone ? imagem($icone):'').str_ireplace(' ','&nbsp;', $legenda).'</span></a>'.dicaF().'</td></tr></table>';
		$this->celulas1[] = array('', $data, $prefixo, $sufixo);
		}


	public function adicionaCelula( $data = '', $atributos = '', $prefixo = '', $sufixo = '') {
		$this->celulas1[] = array($atributos, $data, $prefixo, $sufixo);
		}

	public function adicionaBotao( $link, $legenda, $icone = '', $titulo = '', $texto = '', $javascript='') {
		$this->blocos[] = array($legenda, $icone, $titulo, $texto, $javascript, $link);
		}

	public function adicionaBotaoDireita( $data = '', $atributos = '', $prefixo = '', $sufixo = '') {
		$this->celulas2[] = array($atributos, $data, $prefixo, $sufixo);
		}

	public function adicionaBotaoExcluir( $nome, $podeExcluir = '', $msg = '', $titulo='', $texto='') {
		global $Aplic;
		$this->adicionaBotaoDireita('<table cellspacing=0 cellpadding=0><tr><td style="white-space: nowrap">'.dica($titulo, $texto).'<a class="excluir" href="javascript:excluir()" ><span>'.$nome.'</span></a>'.dicaF().'</td></tr></table>');
		}


	public function mostrar() {
		global $Aplic, $a, $m, $tab, $infotab;
		$estilo_ui = 'rondon';
		$s = '<table width="100%" cellpadding=0 cellspacing=0><tr>';
		if ($this->icon && !$Aplic->celular) $s .= '<td width="42">'.imagem($this->icon).'</td>';
		$s .= '<td align="left" width="100%" style="white-space: nowrap"><h1>'.$this->titulo.'</h1></td>';
		foreach ($this->celulas1 as $c) {
			$s .= ($c[2] ? $c[2] : '');
			$s .= '<td align="right" style="white-space: nowrap"'.($c[0] ? (' '.$c[0]) : '').'>';
			$s .= ($c[1] ? $c[1] : '&nbsp;');
			$s .= '</td>';
			$s .= ($c[3] ? $c[3] : '');
			}
		$s .= '</tr></table>';
		if (count($this->blocos) || count($this->celulas2)) {
			$blocos = array();
			$s .= '<table cellpadding=0 cellspacing=0 width="100%"><tr><td height="20" style="white-space: nowrap"><table cellpadding=1 cellspacing=0><tr>';
			foreach ($this->blocos as $v) {
				$t = $v[1] ? '<img src="'.acharImagem($v[1], $this->modulo).'" border="" alt="" />&nbsp;' : '';
				$t .= $v[0];
				if ($v[5]) $s .= '<td>'.dica($v[2], $v[3]).'<a class="botao" href="javascript:void(0);" onclick="url_passar(0, \''.$v[5].'\');"><span>'.$t.'</span></a>'.dicaF().'</td>';
				else $s .= '<td>'.dica($v[2], $v[3]).'<a class="botao" href="javascript:void(0);" onclick="'.$v[4].'"><span>'.$t.'</span></a>'.dicaF().'</td>';
				}
			$s .= '</tr></table></td>';
			foreach ($this->celulas2 as $c) {
				$s .= $c[2] ? $c[2] : '';
				$s .= '<td align="right" style="white-space: nowrap" '.($c[0] ? ' '.$c[0] : '').'>';
				$s .= $c[1] ? $c[1] : '&nbsp;';
				$s .= '</td>';
				$s .= $c[3] ? $c[3] : '';
				}
			$s .= '</tr></table>';
			}
		echo $s;
		}

	}
?>
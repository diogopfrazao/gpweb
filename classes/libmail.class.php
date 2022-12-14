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

//require_once($Aplic->getClasseBiblioteca('PHPMailer/class.phpmailer'));
//require_once($Aplic->getClasseBiblioteca('PHPMailer/PHPMailerAutoload'));

require_once BASE_DIR.'/lib/PHPMailer/PHPMailerAutoload.php';


class Mail extends PHPMailer {
	public $ato = array();
	public $acc = array();
	public $abcc = array();
	public $recibo = false;
	public $usarEnderecoBruto = true;
	public $adiar;

	public function __construct() {
		global $config;
		$this->ContentType='text/html';
		$this->autoCheck(true);
		$this->adiar = config('email_adiar');
		$this->canEncode = function_exists('imap_8bit') && 'us-ascii' != $this->CharSet;
		$this->hasMbStr = function_exists('mb_substr');
		$this->Mailer = (config('email_transporte', 'php') == 'smtp' ? 'smtp' : 'mail');
		$this->Port = config('email_porta', '25');
		$this->Host = config('email_hospedagem', 'localhost');
		$this->Hostname = config('email_hospedagem', 'localhost');
		$this->SMTPAuth = config('email_autenticacao', false);
		$this->SMTPSecure = config('email_seguro', '');
		$this->SMTPDebug = config('email_debug', false);
		$this->Username = config('email_usuario');
		$this->Password = config('email_senha');
		$this->Timeout = config('email_tempo', 0);
		$this->CharSet = isset($GLOBALS['locale_char_set']) ? checarMapaCaract(strtolower($GLOBALS['locale_char_set'])) : 'ISO-8859-1';
		$this->Encoding = $this->CharSet != 'us-ascii' ? '8bit' : '7bit';
		$this->De(config('nome_administrador').'@'.config('email_hospedagem'),$config['nome_om']);

        //resolve problema certificado inv?lido quando PHP >= 5.6
        $this->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );
		}

	public function checarMapaCaract( $tipoCaract) {
		if (!(strpos($tipoCaract, 'iso') === false)) {
			if (strpos($tipoCaract, 'iso-') === false)	return str_replace('iso', 'iso-', $tipoCaract);
			}
		return $tipoCaract;
		}

	public function autoCheck( $bool) {
		$this->checarEndereco = (bool)$bool;
		return true;
		}

	public function Assunto( $assunto, $tipoCaract = '') {
		$this->Subject = config('prefixo_email').' '.$assunto;
		return true;
		}

	public function De( $de, $de_nome = '') {
		if (!is_string($de)) return false;
		$this->From = $de;
		$this->FromName = $de_nome;
		if ($this->recibo) $this->ConfirmReadingTo($de);
		return true;
		}

	public function ResponderPara( $endereco) {
		if (!is_string($endereco)) return false;
		$this->AddReplyTo($endereco);
		if ($this->recibo)	$this->ConfirmReadingTo($endereco);
		return true;
		}

	public function Recibo() {
		$this->recibo = true;
		return true;
		}

	public function Para( $para, $reset = false) {
		if (is_array($para)) $this->ato = $para;
		else {
			if ($this->usarEnderecoBruto) {
				if (preg_match("/^(.*)\<(.+)\>$/D", $para, $regs)) $para = $regs[2];
				}
			if ($reset) {
				unset($this->ato);
				$this->ato = array();
				}
			$this->ato[] = $para;
			}
		if ($this->checarEndereco == true) $this->ChecarEndereco($this->ato);
		foreach ($this->ato as $endereco_para) {
			if (strpos($endereco_para, '<') !== false) {
				preg_match('/^.*<([^@]+\@[a-z0-9\._-]+)>/i', $endereco_para, $comparados);
				if (isset($comparados[1])) $endereco_para = $comparados[1];
				}
			$this->AddAddress($endereco_para);
			}
		return true;
		}

	public function Cc( $cc) {
		if (is_array($cc)) $this->acc = $cc;
		else $this->acc = explode(',', $cc);
		if ($this->checarEndereco == true) $this->ChecarEndereco($this->acc);
		foreach ($this->acc as $endereco_cc) {
			if (strpos($endereco_cc, '<') !== false) {
				preg_match('/^.*<([^@]+\@[a-z0-9\._-]+)>/i', $endereco_cc, $comparados);
				if (isset($comparados[1])) $endereco_cc = $comparados[1];
				}
			$this->AddCC($endereco_cc);
			}
		return true;
		}

	public function Bcc( $bcc) {
		if (is_array($bcc)) $this->abcc = $bcc;
		else $this->abcc = explode(',', $bcc);
		if ($this->checarEndereco == true) $this->ChecarEndereco($this->abcc);
		foreach ($this->abcc as $endereco_bcc) {
			if (strpos($endereco_bcc, '<') !== false) {
				preg_match('/^.*<([^@]+\@[a-z0-9\._-]+)>/i', $endereco_bcc, $comparados);
				if (isset($comparados[1])) $endereco_bcc = $comparados[1];
				}
			$this->AddCC($endereco_bcc);
			}
		return true;
		}

	public function Corpo( $corpo, $tipoCaract = '') {
		$this->Body = $corpo;
		if (!empty($tipoCaract)) {
			@($this->charset = strtolower($tipoCaract));
			if ($this->charset != 'us-ascii') $this->Encoding = '8bit';
			}
		}

	public function Prioridade( $prioridade) {
		if ((!intval($prioridade)) || (intval($prioridade) < 1) || (intval($prioridade) > 5))	return false;
		$this->Priority = $prioridade;
		return true;
		}

	public function Enviar() {
		if ($this->adiar) return $this->AdiarEmail();
		else return PHPMailer::Send();
		}

	public function getNomeServidor() {
		if ($servidor = gethostbyaddr(previnirXSS($_SERVER['SERVER_ADDR'])))	return $servidor;
		else return '['.previnirXSS($_SERVER['SERVER_ADDR']).']';
		}

	public function AdiarEmail() {
		global $Aplic;
		require_once $Aplic->getClasseSistema('evento_recorrencia');
		$ec = new EventoFila;
		$vars = get_object_vars($this);
		return $ec->adicionar(array('Mail', 'EnviarEmailEmEspera'), $vars, 'libmail', true);
		}

	public function EnviarEmailEmEspera( $mod, $tipo, $origem, $responsavel, &$args) {
	    global $config;
		if (isset($args['ato'])){
            $this->De($config['email'], $args['FromName']);
		    $this->ResponderPara($args['From']);
		    $this->Para($args['ato'], true);
		    $this->Assunto($args['Subject']);
            $this->Corpo($args['Body']);
            $this->adiar=false;

            /*if($args['attachment']){
                $this->attachAll($args['attachment']);
                }*/

            if ($this->Mailer == 'smtp') {
                $this->IsSMTP();
                return $this->Enviar();
                }
            else {
                $this->IsMail();
                return $this->Enviar();
                }
            }
		}

	public function Get() {
		$email = $this->CreateHeader();
		$email .= $this->CreateBody();
		return $email;
		}

	public function EmailValido( $endereco) {
		if (preg_match('/^(.*)\<(.+)\>$/D', $endereco, $regs)) $endereco = $regs[2];
		return (bool)preg_match('/^[^@ ]+@([-a-zA-Z0-9..]+)$/D', $endereco);
		}

	public function ChecarEndereco( $aad) {
		foreach ($aad as $ad) {
			if (!$this->EmailValido($ad)) {
				echo 'Class Mail, method Mail : invalid address '.$ad;
				exit;
				}
			}
		return true;
		}

	public function ChecarEnderecos( $aad) {
		return $this->ChecarEndereco($aad);
		}
	}
?>
<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

require_once '../base.php';
require_once BASE_DIR.'/estilo/rondon/funcao_grafica.php';
require_once BASE_DIR.'/codigo/instalacao.inc.php';
require_once BASE_DIR.'/lib/adodb/adodb.inc.php';

function checarAtualizacao(&$config) {
	$modo = 0;
	if (is_file('../config.php')) {
		if (isset($config['hospedadoBd'])) {
			if ($resultado=checarDBMultiplo($config)) $modo = $resultado;
			}
		}
	return $modo;
	}

function checarDBMultiplo(&$config){
    $_GPWEB_MULTIPLO_INSTANCIA = false;

    $tokenCookie = isset($_COOKIE['gpweb_token']) ? getParam($_COOKIE,'gpweb_token', '') : false;
    $tokenSession = isset($_REQUEST['gpw']) ? getParam($_REQUEST,'gpw', '') : false;

    if(isset($config['multiplo']) && $config['multiplo'] ){
        if(!$tokenSession && !$tokenCookie){
            db_multiplo_install_exit('erro 1');
            }
        else{
            $_GPWEB_MULTIPLO_INSTANCIA = $tokenSession ? strip_tags($tokenSession) : strip_tags($tokenCookie);
            }
        }

    if($_GPWEB_MULTIPLO_INSTANCIA !== false){
        if(!isset($_SESSION['gpweb_multiplo']) || !isset($_SESSION['gpweb_token']) || $_SESSION['gpweb_token'] !== $_GPWEB_MULTIPLO_INSTANCIA){
            $ado = NewADOConnection( get_nome_driver_database($config['tipoBd']) );
            //conectar_bd($config['hospedadoBd'], $config['nomeBd'], $config['usuarioBd'], $config['senhaBd'], $config['persistenteBd']);
            if (empty($ado)) return false;
            $bd = @$ado->Connect($config['hospedadoBd'], $config['usuarioBd'], $config['senhaBd']);
            if(!$bd) return false;
            $existe = @$ado->SelectDB($config['nomeBd']);
            if (!$existe) return false;

            $sql = "select srv.* FROM ".$config['prefixoBd']."servidores AS srv,".$config['prefixoBd']."clientes AS cl WHERE srv.servidor_id = cl.cliente_servidor_id AND cl.cliente_chave = '".$_GPWEB_MULTIPLO_INSTANCIA."' AND cl.cliente_ativo > 0";
            $rs = $ado->Execute($sql);
            if ($rs) {
                $rsArr = $rs->GetArray();
                if(!empty($rsArr)){
                    $rsArr = $rsArr[0];
                    $config['hospedadoBd'] = $rsArr['servidor_database_url'];
                    $config['nomeBd'] = $rsArr['servidor_prefixo'].$_GPWEB_MULTIPLO_INSTANCIA;
                    $_SESSION['gpweb_token'] = $_GPWEB_MULTIPLO_INSTANCIA;
                    $_SESSION['gpweb_multiplo'] = array('hospedadoBd' => $config['hospedadoBd'], 'nomeBd' => $config['nomeBd']);
                    }
                else{
                    db_multiplo_install_exit('erro 2');
                    }
                $rs->Close();
                $ado->Close();
                }
            else{
                db_multiplo_install_exit('erro 3');
                }
            }
        else{
            $config['hospedadoBd'] = $_SESSION['gpweb_multiplo']['hospedadoBd'];
            $config['nomeBd'] = $_SESSION['gpweb_multiplo']['nomeBd'];
            }

            setcookie('gpweb_token',$_GPWEB_MULTIPLO_INSTANCIA);
        }

    return checarBDexistente($config);
    }

function checarBDexistente($config) {
	global $Aplic, $ADODB_FETCH_MODE;
	$Aplic = new UI_instalacao;
	$ado = @NewADOConnection( get_nome_driver_database($config['tipoBd']) );
	if (empty($ado)) return false;
	$bd = @$ado->Connect($config['hospedadoBd'], $config['usuarioBd'], $config['senhaBd']);

	if (!$bd) return 0;

	$existe = @$ado->SelectDB($config['nomeBd']);
	if (! $existe) return 0;

	$ADODB_FETCH_MODE = ADODB_FETCH_NUM;
	$qid = $ado->Execute('select versao_bd from versao');
	if(!$qid) return 0;

	$versao_bd = $qid->fields;
	return $versao_bd[0];
	}

function db_multiplo_install_exit($erro=''){
    global $config;
    $m = isset($_REQUEST['m']) ? getParam($_REQUEST, 'm', null) : '';
    $u = isset($_REQUEST['u']) ? getParam($_REQUEST, 'u', null) : '';
    if($m == 'sistema' && $u == 'menu'){
        echo json_encode(array('sucess' => true, 'menu' => array()));
        exit();
        }
    else{
        ini_set('default_charset', 'ISO-8859-1');
        require_once BASE_DIR.'/estilo/rondon/funcao_grafica.php';
        echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico">';
        echo '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">';
        echo '<link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css"></head><body>';
        echo $erro;
        echo '<br><br><br>';
        echo '<table width="600" cellspacing=0 cellpadding=0 border=0 align="center"><tr><td>'.estiloTopoCaixa().'</td></tr><tr><td>';
        echo '<table cellspacing=0 cellpadding="6" border=0 class="std" width="100%" align="center">';
        echo '<tr><td align="center"><h1>Desculpe, mas os dados informados n�o s�o v�lidos.</h1></td><tr>';
        echo '<tr><td align="center">Caso considere isto um erro entre em contato com a Sistema GP-Web LTDA-ME pelo e-mail: <a href="mailto:suporte@sistemagpweb.com.br?subject=Problema de acesso ao sistema">suporte@sistemagpweb.com.br</a></br>Informe o ocorrido juntamente com os dados do cliente ao qual esta registrada a assinatura do sistema.<br/>Obrigado!</td></tr></table></td></tr>';
        echo '<tr><td>'.estiloFundoCaixa().'</td></tr></table>';
        echo '</body></html>';
        exit();
        }
    }
?>
<?php
require_once '../base.php';
require_once BASE_DIR.'/config.php';
if (!isset($GLOBALS['OS_WIN'])) $GLOBALS['OS_WIN'] = (stristr(PHP_OS, 'WIN') !== false);

global $Aplic;

if(!isset($Aplic)){
    if(session_status() == PHP_SESSION_NONE ){
        require_once BASE_DIR.'/incluir/sessao.php';
        sessaoIniciar(array('Aplic'));
    }

    if(array_key_exists('Aplic', $_SESSION)){
        $Aplic = &$_SESSION['Aplic'];
    }
}

if(!isset($Aplic) || $Aplic->fazerLogin()){
    die('Você não deveria acessar este arquivo diretamente.');
}

define('BASE_URL', get_base_url());

require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';


$url=grava_ckeditor('upload');
$message = '';
echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(1, '$url', '$message');</script>";


?>
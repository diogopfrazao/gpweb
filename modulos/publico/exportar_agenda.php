<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
/*if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');*/

require_once '../../base.php';
require_once BASE_DIR.'/config.php';
require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/ui.class.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once BASE_DIR.'/lib/icalcreator/iCalcreator.class.php';
$Aplic = new CAplic;
$Aplic->carregarPrefs();


set_time_limit(0);
ignore_user_abort(true);

$chave = strtoupper(getParam($_REQUEST, 'chave', ''));
$usuario_id = (int)getParam($_REQUEST, 'u', 0);

if(empty($chave) || !$usuario_id) die();

$sql = new BDConsulta();

//TODO: verificar se o usu?rio esta ativo
$sql->adTabela('evento_compartilhamento');
$sql->adCampo('*');
$sql->adOnde('usuario_id = '.(int)$usuario_id.' AND comp_chave = \''.$chave.'\' AND comp_ativo != 0');
$comp = $sql->Linha();
$sql->limpar();

if(empty($comp)) die();

$usuarios = array();

$usuario = usuario($usuario_id);

$ical = new vcalendar(array('unique_id' => get_base_url().'//Sistema',
    //'language' => 'PT-BR',
    //'NewlineChar' => '<br/>',
    'TZID' => 'America/Sao_Paulo'));

$ical->createVersion();
$ical->setMethod('PUBLISH');
$ical->setProperty( 'X-WR-CALNAME', $comp['comp_nome'] );
$ical->setProperty( 'X-WR-CALDESC', $comp['comp_descricao'] );
$ical->setProperty( 'X-WR-RELCALID', '{'.$chave.'}');
$ical->setProperty( 'X-WR-TIMEZONE', 'America/Sao_Paulo' );
//$ical->setProperty('X-PRIMARY-CALENDAR','TRUE');
$ical->setProperty( 'X-OWNER','CN="'.$usuario['nome'].'":mailto:'.$usuario['email']);


//TODO: ajustar o limite entre solicita??es
//$ical->setProperty('X-PUBLISHED-TTL','PT2M');

$tz = iCalUtilityFunctions::createTimezone($ical, 'America/Sao_Paulo');

$start = 0;
$inc = 1000;

//exportar eventos
if((int)$comp['comp_evento_acessos']){
    do{
        $sql->adTabela('eventos', 'ev');
        $sql->esqUnir('evento_participante','evento_participante','ev.evento_id = evento_participante_evento');
        $sql->adOnde('evento_participante_usuario = '.(int)$usuario_id);
        $sql->adOnde('ev.evento_recorrencia_pai IS NULL OR ev.evento_recorrencia_pai = 0');
        $sql->adCampo('ev.*, evento_participante_aceito');

        //se n?o for todos os tipos de acesso
        if($comp['comp_evento_acessos'] != 0xf){
            $ac = (int) $comp['comp_evento_acessos'];
            $r = array();
            if($ac & 0x1) $r[] = 'ev.evento_acesso = 0';
            if($ac & 0x2) $r[] = 'ev.evento_acesso = 1';
            if($ac & 0x4) $r[] = 'ev.evento_acesso = 5';
            if($ac & 0x8) $r[] = 'ev.evento_acesso = 4';
            if($ac & 0x10) $r[] = 'ev.evento_acesso = 2';
            if($ac & 0x20) $r[] = 'ev.evento_acesso = 6';
            if($ac & 0x40) $r[] = 'ev.evento_acesso = 3';

            $sql->adOnde('('.implode(' OR ', $r).')');
            }


        $sql->setLimite($start, $inc);
        $eventos = $sql->Lista();
        $start += $inc;
        $sql->limpar();

        foreach($eventos as $evento){
            criarEvento($evento);
            }

    }while(!empty($eventos));
}

//$str = $ical->createCalendar();
//echo $str;
$ical->returnCalendar(true);


function usuario($usuario_id){
    global $usuarios, $config;

    if(array_key_exists($usuario_id, $usuarios)) return $usuarios[$usuario_id];

    $sql = new BDConsulta();
    $sql->adTabela('usuarios','u');
    $sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
    $sql->adCampo('u.usuario_login as login, c.contato_email as email1, c.contato_email2 as email2');
    $sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
    $sql->adOnde('u.usuario_id = '.(int)$usuario_id);
    $usuario = $sql->linha();
    $sql->limpar();
    if(empty($usuario)) return false;
    $email = ($usuario['email1'] ? $usuario['email1'] : ($usuario['email2'] ? $usuario['email2'] : $usuario['login'].'@gpweb.org'));

    $usu = array('nome' => $usuario['nome'], 'email' => $email);

    $usuarios[$usuario_id] = $usu;

    return $usu;
}

function criarEvento($evento){
    global $ical, $usuario_id;
    $ev = new vevent(array('TZID' => 'America/Sao_Paulo'));

    /*$ev->setProperty('X-ALT-DESC' , '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//E
    N">\n<HTML>\n<HEAD>\n<META NAME="Generator" CONTENT="MS Exchange Server ve
    rsion rmj.rmm.rup.rpr">\n<TITLE></TITLE>\n</HEAD>\n<BODY>\n<!-- Converted
    from text/rtf format -->\n\n<P DIR=LTR><SPAN LANG="pt-br"><FONT COLOR="#FF
    0000" FACE="Calibri">Fdgdfgsdf sf sdfg sdfgsd fdf gsdf sdfgs dfsdf gdf dfg
    </FONT></SPAN><SPAN LANG="pt-br"></SPAN></P>\n\n</BODY>\n</HTML>', array('FMTTYPE' => 'text/html'));*/

    $uid = $evento['evento_uid'] ? $evento['evento_uid'] : strtoupper(uuid());
    $ev->setUid( $uid );

    $ev->setSummary($evento['evento_titulo']);
    $ev->setDescription(strip_tags($evento['evento_descricao']));
    $ev->setDtstart($evento['evento_inicio']);
    $ev->setDtend($evento['evento_fim']);
    $ev->setLastModified(date('Y-m-d H:i:s',time()));

    processaOrganizador($ev, $evento);

    //o usuario ? participante porem recusou
    if(!processaParticipantes($ev, $evento)) return;

    processaRecorrencia($ev, $evento);

    $categoria = array('GPWEB');

    /*if($evento['evento_gestao_acao']) $categoria[] = 'PLANO DE A??O';
    if($evento['evento_gestao_agenda']) $categoria[] = 'AGENDA';
    if($evento['evento_gestao_agrupamento']) $categoria[] = 'AGRUPAMENTO';
    if($evento['evento_gestao_arquivo']) $categoria[] = 'ARQUIVO';
    if($evento['evento_gestao_ata']) $categoria[] = 'ATA REUNI?O';
    if($evento['evento_gestao_avaliacao']) $categoria[] = 'AVALIA??O';
    if($evento['evento_gestao_brainstorm']) $categoria[] = 'BRAINSTORM';
    if($evento['evento_gestao_calendario']) $categoria[] = 'CALEND?RIO';
    if($evento['evento_gestao_canvas']) $categoria[] = 'PROJECT MODEL CANVAS';
    if($evento['evento_gestao_causa_efeito']) $categoria[] = 'CAUSA E EFEITO';
    if($evento['evento_gestao_checklist']) $categoria[] = 'CHECKLIST';
    if($evento['evento_gestao_demanda']) $categoria[] = 'DEMANDA';
    if($evento['evento_gestao_estrategia']) $categoria[] = 'INICIATIVA ESTRAT?GICA';
    if($evento['evento_gestao_fator']) $categoria[] = 'FATOR CR?TICO';
    if($evento['evento_gestao_forum']) $categoria[] = 'FORUM';
    if($evento['evento_gestao_gut']) $categoria[] = 'GUT';
    if($evento['evento_gestao_indicador']) $categoria[] = 'INDICADOR';
    if($evento['evento_gestao_instrumento']) $categoria[] = 'INSTRUMENTO';
    if($evento['evento_gestao_licao']) $categoria[] = 'LI??O';
    if($evento['evento_gestao_link']) $categoria[] = 'LINK';
    if($evento['evento_gestao_me']) $categoria[] = 'ME';
    if($evento['evento_gestao_meta']) $categoria[] = 'META';
    if($evento['evento_gestao_monitoramento']) $categoria[] = 'MONITORAMENTO';
    if($evento['evento_gestao_objetivo']) $categoria[] = 'OBJETIVO ESTRAT?GICO';
    if($evento['evento_gestao_operativo']) $categoria[] = 'PLANO OPERAT?VO';
    if($evento['evento_gestao_painel']) $categoria[] = 'PAINEL';
    if($evento['evento_gestao_painel_composicao']) $categoria[] = 'PAINEL COMPOSI??O';
    if($evento['evento_gestao_painel_odometro']) $categoria[] = 'OD?METRO';
    if($evento['evento_gestao_patrocinador']) $categoria[] = 'PATROCINADOR';
    if($evento['evento_gestao_perspectiva']) $categoria[] = 'PERSPECTIVA';
    if($evento['evento_gestao_pratica']) $categoria[] = 'PR?TICA';
    if($evento['evento_gestao_problema']) $categoria[] = 'PEND?NCIA';
    if($evento['evento_gestao_programa']) $categoria[] = 'PROGRAMA';
    if($evento['evento_gestao_projeto']) $categoria[] = 'PROJETO';
    if($evento['evento_gestao_recurso']) $categoria[] = 'RECURSO';
    if($evento['evento_gestao_risco']) $categoria[] = 'RISCO';
    if($evento['evento_gestao_risco_resposta']) $categoria[] = 'RESPOSTA A RISCO';
    if($evento['evento_gestao_mswot']) $categoria[] = 'MATRIZ SWOT';
    if($evento['evento_gestao_swot']) $categoria[] = 'CAMPO SWOT';
    if($evento['evento_gestao_tarefa']) $categoria[] = 'TAREFA';
    if($evento['evento_gestao_tema']) $categoria[] = 'TEMA';
    if($evento['evento_gestao_template']) $categoria[] = 'TEMPLATE';
    if($evento['evento_gestao_tgn']) $categoria[] = 'TGN';
    if($evento['evento_gestao_tr']) $categoria[] = 'TERMO REFER?NCIA';*/

    $ev->setCategories($categoria);

    switch((int)$evento['evento_acesso']){
        case 0:
            $ev->setClass('PUBLIC');
            break;
        case 1:
        case 5:
        case 4:
            $ev->setClass('CONFIDENTIAL');
            break;
        case 2:
        case 3:
        case 6:
            $ev->setClass('MEMBER');
    }

    processaAlerta($ev, $evento);

    $ical->addComponent($ev);

    //atualiza eventos antigos que n?o existia o uuid
    if(!$evento['evento_uid']){
        $sql = new BDConsulta();
        $sql->adTabela('eventos');
        $sql->adAtualizar('evento_uid', $uid);
        $sql->adOnde('evento_id = '.$evento['evento_id']);
        $sql->exec();
        $sql->limpar();
    }
}

function processaOrganizador(calendarComponent $component, $evento){
    $usuario = usuario($evento['evento_dono']);
    if(!$usuario) return;
    $component->setOrganizer($usuario['email'], array('CN' => $usuario['nome']));
}

function processaParticipantes(calendarComponent $component, $evento){
    global $usuario_id;

    $sql = new BDConsulta();
    $sql->adTabela('evento_participante');
    $sql->adCampo('*');
    $sql->adOnde('evento_participante_evento = '.($evento['evento_id']));
    $lista = $sql->Lista();
    $sql->limpar();

    foreach($lista as $ev_usu){
        $usuario = usuario($ev_usu['evento_participante_usuario']);
        if(!$usuario) continue;
        $props =  array('CN' => $usuario['nome']);

        if($ev_usu['evento_participante_aceito'] == -1){
            //o usuario exportando recusou este evento?
            //se sim este evento n?o ? exportado para o mesmo
            if($ev_usu['evento_participante_usuario'] == $usuario_id) return false;

            $props['PARTSTAT'] = 'DECLINED';
            $date = iCalUtilityFunctions::_date_time_string($ev_usu['evento_participante_data']);
            $date = iCalUtilityFunctions::_date2strdate($date);
            $props['X-MS-OLK-RESPTIME'] = $date;
        }
        else if($ev_usu['evento_participante_aceito'] > 0){
            $props['PARTSTAT'] = 'ACCEPTED';
            $date = iCalUtilityFunctions::_date_time_string($ev_usu['evento_participante_data']);
            $date = iCalUtilityFunctions::_date2strdate($date);
            $props['X-MS-OLK-RESPTIME'] = $date;
        }
        else{
            if($ev_usu['evento_participante_usuario'] != $usuario_id) $props['RSVP']='TRUE';
        }
        $component->setAttendee($usuario['email'], $props);
    }
    return true;
}

function processaRecorrencia(calendarComponent $component, $evento){
    static $weekdays = array('SU', "MO" , "TU" , "WE" , "TH" , "FR" , "SA");

    if(!((int)$evento['evento_recorrencias'])) return;

    $req = array();
    $nr = (int)$evento['evento_nr_recorrencias'];
    switch((int)$evento['evento_recorrencias']){
        case 1:
            //TODO: aparentemente o outlook n?o tem de hora em hora, verificar
            $req['FREQ'] = 'HOURLY';
            if($nr) $req['COUNT'] = $nr;
            //$component->setProperty('RRULE',$req);
            break;
        case 2:
            $req['FREQ'] = 'DAILY';
            if($nr) $req['COUNT'] = $nr;
            $component->setProperty('RRULE',$req);
            break;
        case 3:
            $req['FREQ'] = 'WEEKLY';
            if($nr) $req['COUNT'] = $nr;
            $data = strtotime($evento['evento_inicio']);
            $diaSemana = date('w', $data);
            $req['BYDAY'] = array($weekdays[$diaSemana]);
            $component->setProperty('RRULE',$req);
            break;
        case 4:
            $req['FREQ'] = 'DAILY';
            if($nr) $req['COUNT'] = $nr;
            $req['INTERVAL'] = 15;
            $component->setProperty('RRULE',$req);
            break;
        case 5:
            $req['FREQ'] = 'MONTHLY';
            $date = iCalUtilityFunctions::_date_time_string($evento['evento_inicio']);
            if($nr) $req['COUNT'] = $nr;
            $req['BYMONTHDAY'] = $date['day'];
            $component->setProperty('RRULE',$req);
            break;
        case 6:
            $req['FREQ'] = 'MONTHLY';
            $date = iCalUtilityFunctions::_date_time_string($evento['evento_inicio']);
            if($nr) $req['COUNT'] = $nr;
            $req['BYMONTHDAY'] = $date['day'];
            $req['INTERVAL'] = 4;
            $component->setProperty('RRULE',$req);
            break;
        case 7:
            $req['FREQ'] = 'MONTHLY';
            $date = iCalUtilityFunctions::_date_time_string($evento['evento_inicio']);
            if($nr) $req['COUNT'] = $nr;
            $req['BYMONTHDAY'] = $date['day'];
            $req['INTERVAL'] = 6;
            $component->setProperty('RRULE',$req);
            break;
        case 8:
            $req['FREQ'] = 'YEARLY';
            $date = iCalUtilityFunctions::_date_time_string($evento['evento_inicio']);
            if($nr) $req['COUNT'] = $nr;
            $req['BYMONTHDAY'] = $date['day'];
            $req['BYMONTH'] = $date['month'];
            $component->setProperty('RRULE',$req);
            break;
    }
}

function processaAlerta(calendarComponent $component, $evento){
    //TODO: revisar alarmes
    $alert = (int)$evento['evento_lembrar'];
    if(!$alert) return;
    $al = $component->newComponent('valarm');

    $horas = 0;
    $minutos = 0;

    $dias = floor($alert / 86400); //quantos dias?
    $alert -= $dias * 86400;
    if(!$dias && $alert>0){ //n?o esta em dias
        $horas = floor($alert / 3600); //quantas horas?
        $alert -= $horas * 3600;
        //se n?o estiver em horas esta em quantos minutos?
        if(!$horas && $alert>0) $minutos = floor($alert / 60);
    }

    $al->setTrigger(null,null,$dias,null,$horas, $minutos);

    $al->setAction('DISPLAY');
    $al->setDescription('Reminder');
}
?>

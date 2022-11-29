<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario=getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[]=getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();
$msg_usuario_id=reset($vetor_msg_usuario); 
$status=getParam($_REQUEST, 'status', 0);
$senha=getParam($_REQUEST, 'senha', '');
$usuario_id=getParam($_REQUEST, 'usuario_id', $Aplic->usuario_id);
$outro_usuario=getParam($_REQUEST, 'outro_usuario', 0);
$tipos_status=array('' => 'indefinido') + getSisValor('status');
$precedencia=getSisValor('precedencia');
$class_sigilosa=getSisValor('class_sigilosa');

//cm impede ver mensagens de outro usuario se n�o for CM ou administrador
if (!$Aplic->usuario_admin && $Aplic->usuario_acesso_email!=1) $usuario_id = $Aplic->usuario_id;

$sql = new BDConsulta;
$sql->adTabela('msg');
$sql->adUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
$sql->adCampo('msg_usuario_id');
$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
$sql->adOnde('msg.class_sigilosa <= '.$Aplic->usuario_acesso_email);
$permitido = $sql->Resultado();
$sql->limpar();

if (!$permitido) {
	echo '<script language=Javascript>alert("N�o tem permiss�o de acesso a esta Msg"); url_passar(0, \'m=email&a=lista_msg&status=1\');</script>';
	exit();
	}			

//dados b�sicos da mensagem	
$sql->adTabela('msg_usuario');
$sql->adUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
$sql->esqUnir('msg_cripto', 'msg_cripto', 'msg_usuario.msg_cripto_id=msg_cripto.msg_cripto_id');
$sql->esqUnir('chaves_publicas', 'chaves_publicas', 'msg.chave_publica = chave_publica_id');
$sql->adCampo('msg_usuario.msg_cripto_id, chave_publica_chave AS publica');
$sql->adCampo('msg_usuario.anotacao_id, msg_usuario.tipo, msg_usuario.aviso_leitura, msg_usuario.datahora_leitura, msg.data_envio, msg.msg_id, data_retorno, data_limite, resposta_despacho, msg.cm, assinatura, msg_usuario_id, msg.precedencia, msg.class_sigilosa, msg.referencia, msg.texto, msg.cripto, datahora, msg_usuario.de_id');
$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
$rs = $sql->Linha();
$sql->limpar();

$msg_id=$rs['msg_id'];
if ($rs['cripto']) $msg_id_cripto=$msg_id;
else $msg_id_cripto=0;
if ($rs['cripto']==1){
	$sql->adTabela('msg_cripto');
	$sql->adCampo('texto, chave_envelope');
	$sql->adOnde('msg_cripto_msg = '.$msg_id);
	$sql->adOnde('msg_cripto_para = '.$usuario_id);
	$linha_cripto = $sql->Linha();
	$sql->limpar();
	openssl_open(base64_decode($linha_cripto['texto']), $em_claro, base64_decode($linha_cripto['chave_envelope']), $Aplic->chave_privada);
	$rs['texto']=$em_claro;
	}
elseif ($rs['cripto']==2){
	$sql->adTabela('msg_cripto');
	$sql->adCampo('texto');
	$sql->adOnde('msg_cripto_id = '.$rs['msg_cripto_id']);
	$linha_cripto = $sql->Resultado();
	$sql->limpar();
	require_once BASE_DIR.'/classes/cifra.class.php';
	$cifra = new cifra; 
	$cifra->set_key($senha);
	$rs['texto']=$cifra->decriptar($linha_cripto);
	}

$assinado='';

//verificar dados originais da 1a mensagem
$sql->adTabela('msg');
$sql->esqUnir('chaves_publicas', 'chaves_publicas', 'msg.chave_publica = chave_publica_id');
$sql->adCampo('precedencia, class_sigilosa, referencia, de_id, texto, cripto, data_envio, assinatura, chave_publica_chave');
$sql->adOnde('msg_id = '.$msg_id);
$original = $sql->Linha();
$sql->limpar();

if (function_exists('openssl_sign') && $rs['assinatura']){	
	$identificador=$original['precedencia'].$original['class_sigilosa'].$original['referencia'].$original['de_id'].$rs['texto'].$original['cripto'].$original['data_envio'];
	$ok = openssl_verify($identificador, base64_decode($original['assinatura']), $original['chave_publica_chave'], OPENSSL_ALGO_SHA1);
	if (!$ok) $assinado='&nbsp;<img src="'.acharImagem('icones/assinatura_erro.gif').'" style="vertical-align:top" width="15" height="13" />';
	else $assinado='&nbsp;<img src="'.acharImagem('icones/assinatura.gif').'" style="vertical-align:top" width="15" height="13" />';
	}
echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';	
echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
echo '<tr width="100%"><td align="right" style="font-size:10pt;  padding-left: 5px; padding-right: 5px;">Msg '.$msg_id.':</td><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;">'.$assinado.$rs['referencia'].'</td></tr>';
echo '<tr><td align="right" style="font-size:10pt; padding-left: 5px; padding-right: 5px;">De:</td>';
echo '<td colspan="2" style="font-size:10pt; padding-left: 5px; padding-right: 5px;">';

//todos os remetentes
$sql->adTabela('msg_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=msg_usuario.de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
//EUZ
$sql->adGrupo('msg_usuario_id, usuarios.usuario_id, contatos.contato_posto, contato_nomeguerra, contato_funcao, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
//EUD
$remetentes = $sql->lista();
$sql->limpar();
$i=0;


if (isset($remetentes[0])) echo nome_funcao($remetentes[0]['nome_de'],$remetentes[0]['nome_usuario'], $remetentes[0]['funcao_de'], $remetentes[0]['contato_funcao']);

$sql->adTabela('anotacao');
$sql->adCampo('usuario_id, nome_de, funcao_de');
$sql->adOnde('msg_id = '.$msg_id);
$sql->adOnde('tipo = 3');
$rs_para=$sql->Linha();
$sql->limpar();
if (isset($rs_para['usuario_id'])) echo ' - (CM: enviada por '.nome_funcao($rs_para['nome_de'], '', $rs_para['funcao_de'], '', $rs_para['usuario_id']);

echo '</td></tr>';

echo '<tr width="100%"><td align="right" style="font-size:10pt;  padding-left: 5px; padding-right: 5px;">Enviada:</td><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;">'.retorna_data($rs['datahora']).'</td></tr>';

echo '<tr><td align="right" style="font-size:10pt; padding-left: 5px; padding-right: 5px;" >Para:</td>';
echo '<td colspan="2" style="font-size:9pt; padding-left: 5px; padding-right: 5px;">';


//todos os destinat�rios
$sql->adTabela('msg_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=msg_usuario.para_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('msg_usuario_id, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, msg_usuario.status, msg_usuario.datahora_leitura, msg_usuario.cm, msg_usuario.meio, usuarios.usuario_id, contato_funcao');
$sql->adCampo('para_id');
$sql->adOnde('msg_usuario.msg_id ='.(int)$msg_id);
$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
$sql->adOnde('msg_usuario.para_id>0');
//EUZ
//$sql->adGrupo('usuarios.usuario_id');
$sql->adGrupo('msg_usuario_id, usuarios.usuario_id, contatos.contato_posto, contato_nomeguerra, contato_funcao, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
//EUD

$destinatarios = $sql->Lista();
$sql->limpar();

foreach($destinatarios as $chave => $destinatario){
	if ($destinatario['para_id']==$usuario_id) {
		$apoio=$destinatarios[0];
		$destinatarios[0]=$destinatarios[$chave];
		$destinatarios[$chave]=$apoio;
		}
	}
//todos os destinat�rios extras
$sql->adTabela('msg_usuario_ext');
$sql->adCampo('para');
$sql->adOnde('msg_id ='.(int)$msg_id);
$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
$sql->adGrupo('para');
$destinatarios_extras = $sql->Lista();
$sql->limpar();
if (isset($destinatarios[0]) && $destinatarios[0]) echo formata_destinatario($destinatarios[0]);
elseif(isset($destinatarios_extras[0]) && $destinatarios_extras[0]) echo $destinatarios_extras[0]['para']; 
$qnt_destinatario=count($destinatarios)+count($destinatarios_extras);
if ($qnt_destinatario > 1) {		
		$lista='';
		for ($i = 1, $i_cmp = count($destinatarios); $i < $i_cmp; $i++) $lista.= formata_destinatario($destinatarios[$i]).'<br>';	
		for ($i = 1, $i_cmp = count($destinatarios_extras); $i < $i_cmp; $i++) $lista.= $destinatarios_extras[$i]['para'].'<br>';	
		echo ' <a href="javascript: void(0);" onclick="ver_destinatario();">(+'.($qnt_destinatario - 1).')</a><span style="display: none" id="destinatario"><br>'.$lista.'</span>';
		}


echo '</td></tr>';
echo '</table></td></tr></table>';   
echo '<br>'; 
echo '<table align="center" cellspacing=0 width="770" cellpadding=0><tr><td width="100%" style="font-size:10pt; padding-left: 5px; padding-right: 5px;">'.$rs['texto'].'</td></tr></table>';
echo '<br>'; 


//referencias
$sql->adTabela('referencia');
$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_pai');
$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_pai');
$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
$sql->adOnde('referencia_msg_filho = '.$msg_id);
$lista_referencia_pai = $sql->Lista();
$sql->limpar();
$referencia_pai='';
if ($lista_referencia_pai && count($lista_referencia_pai)) {
		$qnt_msg=0;
		$qnt_doc=0;
		$referencia_pai.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		if ($lista_referencia_pai[0]['referencia_msg_pai']) {
			$referencia_pai.= '<tr><td>Msg. '.$lista_referencia_pai[0]['referencia_msg_pai'].($lista_referencia_pai[0]['referencia']? ' - '.$lista_referencia_pai[0]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[0]['nome_de'], '', $lista_referencia_pai[0]['funcao_de'], '', $lista_referencia_pai[0]['de_id']).' - '.retorna_data($lista_referencia_pai[0]['data_envio'], false);
			$qnt_msg++;
			}
		else {
			if ($lista_referencia_pai[0]['modelo_autoridade_assinou']) {
				$nome=nome_funcao($lista_referencia_pai[0]['modelo_assinatura_nome'], '', $lista_referencia_pai[0]['modelo_assinatura_funcao'], '', $lista_referencia_pai[0]['modelo_autoridade_assinou']);
				$data=retorna_data($lista_referencia_pai[0]['modelo_data_assinado'], false);
				}
			elseif ($lista_referencia_pai[0]['modelo_autoridade_aprovou']) {
				$nome=nome_funcao($lista_referencia_pai[0]['modelo_aprovou_nome'], '', $lista_referencia_pai[0]['modelo_aprovou_funcao'], '', $lista_referencia_pai[0]['modelo_autoridade_aprovou']);
				$data=retorna_data($lista_referencia_pai[0]['modelo_data_aprovado'], false);
				}
			else {
				$nome=nome_funcao($lista_referencia_pai[0]['modelo_criador_nome'], '', $lista_referencia_pai[0]['modelo_criador_funcao'], '', $lista_referencia_pai[0]['modelo_criador_original']);
				$data=retorna_data($lista_referencia_pai[0]['modelo_data'], false);
				}
			$referencia_pai.= '<tr><td>Doc. '.$lista_referencia_pai[0]['referencia_doc_pai'].($lista_referencia_pai[0]['modelo_assunto']? ' - '.$lista_referencia_pai[0]['modelo_assunto'] : '').' - '.$nome.' - '.$data;
			$qnt_doc++;
			}
		$qnt_lista_referencia_pai=count($lista_referencia_pai);
		if ($qnt_lista_referencia_pai > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_referencia_pai; $i < $i_cmp; $i++) {
					if ($lista_referencia_pai[$i]['referencia_msg_pai']) {
						$lista.= 'Msg. '.$lista_referencia_pai[$i]['referencia_msg_pai'].($lista_referencia_pai[$i]['referencia']? ' - '.$lista_referencia_pai[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_pai[$i]['nome_de'], '', $lista_referencia_pai[$i]['funcao_de'], '', $lista_referencia_pai[$i]['de_id']).' - '.retorna_data($lista_referencia_pai[$i]['data_envio'], false).'<br>';
						$qnt_msg++;
						}
					else {
						if ($lista_referencia_pai[$i]['modelo_autoridade_assinou']) {
							$nome=nome_funcao($lista_referencia_pai[$i]['modelo_assinatura_nome'], '', $lista_referencia_pai[$i]['modelo_assinatura_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_assinou']);
							$data=retorna_data($lista_referencia_pai[$i]['modelo_data_assinado'], false);
							}
						elseif ($lista_referencia_pai[$i]['modelo_autoridade_aprovou']) {
							$nome=nome_funcao($lista_referencia_pai[$i]['modelo_aprovou_nome'], '', $lista_referencia_pai[$i]['modelo_aprovou_funcao'], '', $lista_referencia_pai[$i]['modelo_autoridade_aprovou']);
							$data=retorna_data($lista_referencia_pai[$i]['modelo_data_aprovado'], false);
							}
						else {
							$nome=nome_funcao($lista_referencia_pai[$i]['modelo_criador_nome'], '', $lista_referencia_pai[$i]['modelo_criador_funcao'], '', $lista_referencia_pai[$i]['modelo_criador_original']);
							$data=retorna_data($lista_referencia_pai[$i]['modelo_data'], false);
							}
						$lista.= 'Doc. '.$lista_referencia_pai[$i]['referencia_doc_pai'].($lista_referencia_pai[$i]['modelo_assunto']? ' - '.$lista_referencia_pai[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'<br>';
						$qnt_doc++;
						}
					}
				$referencia_pai.= ' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_referencia_pai\');">(+'.($qnt_lista_referencia_pai - 1).')</a><span style="display:none" id="lista_referencia_pai"><br>'.$lista.'</span>';
				}
		$referencia_pai.= '</td></tr></table>';
		} 
if ($referencia_pai) {
	echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
	echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
	echo '<tr><td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;"><b>'.($qnt_lista_referencia_pai > 1 ? 'Referencias' : 'Referencia').'</b></td></tr>';	
	echo '<tr><td>'.$referencia_pai.'</td></tr>';
	echo '</table></td></tr></table>';
	echo '<br>'; 
	}


//referencias filho
$sql->adTabela('referencia');
$sql->esqUnir('msg', 'msg', 'msg.msg_id=referencia.referencia_msg_filho');
$sql->esqUnir('modelos', 'modelos', 'modelos.modelo_id=referencia.referencia_doc_filho');
$sql->adCampo('referencia.*, msg.de_id, modelos.*, msg.referencia, msg.data_envio, nome_de, funcao_de');
$sql->adOnde('referencia_msg_pai = '.$msg_id);
$lista_referencia_filho = $sql->Lista();
$sql->limpar();
$referencia_filho='';
if ($lista_referencia_filho && count($lista_referencia_filho)) {
		$qnt_msg=0;
		$qnt_doc=0;
		$referencia_filho.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		if ($lista_referencia_filho[0]['referencia_msg_filho']) {
			$referencia_filho.= '<tr><td>Msg. '.$lista_referencia_filho[0]['referencia_msg_filho'].($lista_referencia_filho[0]['referencia']? ' - '.$lista_referencia_filho[0]['referencia'] : '').' - '.nome_funcao($lista_referencia_filho[0]['nome_de'], '', $lista_referencia_filho[0]['funcao_de'], '', $lista_referencia_filho[0]['de_id']).' - '.retorna_data($lista_referencia_filho[0]['data_envio'], false);
			$qnt_msg++;
			}
		else {
			if ($lista_referencia_filho[0]['modelo_autoridade_assinou']) {
				$nome=nome_funcao($lista_referencia_filho[0]['modelo_assinatura_nome'], '', $lista_referencia_filho[0]['modelo_assinatura_funcao'], '', $lista_referencia_filho[0]['modelo_autoridade_assinou']);
				$data=retorna_data($lista_referencia_filho[0]['modelo_data_assinado'], false);
				}
			elseif ($lista_referencia_filho[0]['modelo_autoridade_aprovou']) {
				$nome=nome_funcao($lista_referencia_filho[0]['modelo_aprovou_nome'], '', $lista_referencia_filho[0]['modelo_aprovou_funcao'], '', $lista_referencia_filho[0]['modelo_autoridade_aprovou']);
				$data=retorna_data($lista_referencia_filho[0]['modelo_data_aprovado'], false);
				}
			else {
				$nome=nome_funcao($lista_referencia_filho[0]['modelo_criador_nome'], '', $lista_referencia_filho[0]['modelo_criador_funcao'], '', $lista_referencia_filho[0]['modelo_criador_original']);
				$data=retorna_data($lista_referencia_filho[0]['modelo_data'], false);
				}
			$referencia_filho.= '<tr><td>Doc. '.$lista_referencia_filho[0]['referencia_doc_filho'].($lista_referencia_filho[0]['modelo_assunto']? ' - '.$lista_referencia_filho[0]['modelo_assunto'] : '').' - '.$nome.' - '.$data;
			$qnt_doc++;
			}
		$qnt_lista_referencia_filho=count($lista_referencia_filho);
		if ($qnt_lista_referencia_filho > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_referencia_filho; $i < $i_cmp; $i++) {
					if ($lista_referencia_filho[$i]['referencia_msg_filho']) {
						$lista.= 'Msg. '.$lista_referencia_filho[$i]['referencia_msg_filho'].($lista_referencia_filho[$i]['referencia']? ' - '.$lista_referencia_filho[$i]['referencia'] : '').' - '.nome_funcao($lista_referencia_filho[$i]['nome_de'], '', $lista_referencia_filho[$i]['funcao_de'], '', $lista_referencia_filho[$i]['de_id']).' - '.retorna_data($lista_referencia_filho[$i]['data_envio'], false).'<br>';
						$qnt_msg++;
						}
					else {
						if ($lista_referencia_filho[$i]['modelo_autoridade_assinou']) {
							$nome=nome_funcao($lista_referencia_filho[$i]['modelo_assinatura_nome'], '', $lista_referencia_filho[$i]['modelo_assinatura_funcao'], '', $lista_referencia_filho[$i]['modelo_autoridade_assinou']);
							$data=retorna_data($lista_referencia_filho[$i]['modelo_data_assinado'], false);
							}
						elseif ($lista_referencia_filho[$i]['modelo_autoridade_aprovou']) {
							$nome=nome_funcao($lista_referencia_filho[$i]['modelo_aprovou_nome'], '', $lista_referencia_filho[$i]['modelo_aprovou_funcao'], '', $lista_referencia_filho[$i]['modelo_autoridade_aprovou']);
							$data=retorna_data($lista_referencia_filho[$i]['modelo_data_aprovado'], false);
							}
						else {
							$nome=nome_funcao($lista_referencia_filho[$i]['modelo_criador_nome'], '', $lista_referencia_filho[$i]['modelo_criador_funcao'], '', $lista_referencia_filho[$i]['modelo_criador_original']);
							$data=retorna_data($lista_referencia_filho[$i]['modelo_data'], false);
							}
						$lista.= 'Doc. '.$lista_referencia_filho[$i]['referencia_doc_filho'].($lista_referencia_filho[$i]['modelo_assunto']? ' - '.$lista_referencia_filho[$i]['modelo_assunto'] : '').' - '.$nome.' - '.$data.'<br>';
						$qnt_doc++;
						}
					}
				$referencia_filho.= ' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_referencia_filho\');">(+'.($qnt_lista_referencia_filho - 1).')</a><span style="display:none" id="lista_referencia_filho"><br>'.$lista.'</span>';
				}
		$referencia_filho.= '</td></tr></table>';
		} 
if ($referencia_filho) {
	echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
	echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
	echo '<tr><td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;"><b>'.($qnt_lista_referencia_filho > 1 ? 'Fazem Referencia a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'] : 'Faz referencia a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem']).'</b></td></tr>';	
	echo '<tr><td>'.$referencia_filho.'</td></tr>';
	echo '</table></td></tr></table>';
	echo '<br>'; 
	}


//anexos
$sql->adTabela('anexo');
$sql->adUnir('usuarios','usuarios', 'anexo_usuario=usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->esqUnir('modelos', 'modelos', 'modelo_id = anexo_modelo');
$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo_id = modelo_tipo');
$sql->adCampo('anexo_nome_fantasia, anexo_nome, modelo_tipo_nome, modelo_data, modelo_numero, modelo_autoridade_aprovou, modelo_autoridade_assinou, modelo_protocolo, modelo_assunto');
$sql->adCampo('anexo_msg, anexo_assinatura, anexo_id, anexo_caminho, anexo_usuario, anexo_nome_de, anexo_funcao_de, anexo_tipo_doc, anexo_doc_nr, anexo_data_envio, contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, anexo_modelo');
$sql->esqUnir('chaves_publicas', 'chaves_publicas', 'anexo_chave_publica = chave_publica_id');
$sql->adCampo('chave_publica_chave AS publica');
$sql->adOnde('anexo_msg = '.(int)$msg_id);
$sql->adOrdem('anexo_id DESC');
$anexos = $sql->Lista();
$sql->limpar();
$quantidade_anexos=0;	
foreach ($anexos as $rs_anexo){ 
	$quantidade_anexos++;
	if ($quantidade_anexos==1) {
		echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
		echo	'<tr><td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;"><b>'.(count($anexos) > 1 ? 'Anexos' : 'Anexo').'</b></td></tr>';	
		}
	$assinado='';
	if (function_exists('openssl_sign') && $rs_anexo['anexo_assinatura']){	
		$identificador=$rs_anexo['anexo_msg'].$rs_anexo['anexo_nome'].$rs_anexo['anexo_caminho'].$rs_anexo['anexo_usuario'].$rs_anexo['anexo_tipo_doc'].$rs_anexo['anexo_doc_nr'].$rs_anexo['anexo_data_envio'].$rs_anexo['anexo_modelo'];
		if ($rs_anexo['publica'])	$ok = openssl_verify($identificador, base64_decode($rs_anexo['anexo_assinatura']), $rs_anexo['publica']);
		else $ok=0;
		if (!$ok) $assinado='<img src="'.acharImagem('icones/assinatura_erro.gif').'" style="vertical-align:top" width="15" height="13" />';
		else $assinado='<img src="'.acharImagem('icones/assinatura.gif').'" style="vertical-align:top" width="15" height="13" />';
		}
	if (!$assinado) $assinado='<img src="'.acharImagem('icones/assinatura_sem.gif').'" style="vertical-align:top" width="15" height="13" />';
	if ($rs_anexo['anexo_modelo']) echo '<tr align="left" style="font-size:10pt; padding-left: 5px; padding-right: 5px;" ><td>'.$assinado.($rs_anexo['anexo_nome_fantasia'] ? $rs_anexo['anexo_nome_fantasia'] : $rs_anexo['anexo_nome']).' - '.nome_funcao($rs_anexo['anexo_nome_de'], '', $rs_anexo['anexo_funcao_de'], '', $rs_anexo['anexo_usuario']).($rs_anexo['anexo_data_envio'] ? ' - '.retorna_data($rs_anexo['anexo_data_envio']) : '').'</td></tr>';
	else echo '<tr align="left" style="font-size:10pt; padding-left: 5px; padding-right: 5px;" ><td>'.$assinado.($rs_anexo['anexo_nome_fantasia'] ? $rs_anexo['anexo_nome_fantasia'] : $rs_anexo['anexo_nome']).' - '.nome_funcao($rs_anexo['anexo_nome_de'], '', $rs_anexo['anexo_funcao_de'], '', $rs_anexo['anexo_usuario']).($rs_anexo['anexo_data_envio'] ? ' - '.retorna_data($rs_anexo['anexo_data_envio']) : '').'</td></tr>';
	}
if ($quantidade_anexos)  echo '</table></td></tr></table>';



$sql->adTabela('msg_gestao');
$sql->adCampo('msg_gestao.*');
$sql->adOnde('msg_gestao_msg ='.(int)$msg_id);	
$sql->adOrdem('msg_gestao_ordem');
$lista = $sql->Lista();
$sql->limpar();
$qnt_gestao=0;

if (count($lista)) {
	echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center" width="770"><tr><td width=110 style="font-size:10pt; padding-left: 5px; padding-right: 5px ;">Relacionamento:</td><td>';
	foreach($lista as $gestao_data){
		if ($gestao_data['msg_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['msg_gestao_tarefa']);
		elseif ($gestao_data['msg_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['msg_gestao_projeto']);
		elseif ($gestao_data['msg_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['msg_gestao_pratica']);
		elseif ($gestao_data['msg_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['msg_gestao_acao']);
		elseif ($gestao_data['msg_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['msg_gestao_perspectiva']);
		elseif ($gestao_data['msg_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['msg_gestao_tema']);
		elseif ($gestao_data['msg_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['msg_gestao_objetivo']);
		elseif ($gestao_data['msg_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['msg_gestao_fator']);
		elseif ($gestao_data['msg_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['msg_gestao_estrategia']);
		elseif ($gestao_data['msg_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['msg_gestao_meta']);
		elseif ($gestao_data['msg_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['msg_gestao_canvas']);
		elseif ($gestao_data['msg_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['msg_gestao_risco']);
		elseif ($gestao_data['msg_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['msg_gestao_risco_resposta']);
		elseif ($gestao_data['msg_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['msg_gestao_indicador']);
		elseif ($gestao_data['msg_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['msg_gestao_calendario']);
		elseif ($gestao_data['msg_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['msg_gestao_monitoramento']);
		elseif ($gestao_data['msg_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['msg_gestao_ata']);
		elseif ($gestao_data['msg_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['msg_gestao_mswot']);
		elseif ($gestao_data['msg_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['msg_gestao_swot']);
		elseif ($gestao_data['msg_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['msg_gestao_operativo']);
		elseif ($gestao_data['msg_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['msg_gestao_instrumento']);
		elseif ($gestao_data['msg_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['msg_gestao_recurso']);
		elseif ($gestao_data['msg_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['msg_gestao_problema']);
		elseif ($gestao_data['msg_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['msg_gestao_demanda']);	
		elseif ($gestao_data['msg_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['msg_gestao_programa']);
		elseif ($gestao_data['msg_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['msg_gestao_licao']);
		elseif ($gestao_data['msg_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['msg_gestao_evento']);
		elseif ($gestao_data['msg_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['msg_gestao_link']);
		elseif ($gestao_data['msg_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['msg_gestao_avaliacao']);
		elseif ($gestao_data['msg_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['msg_gestao_tgn']);
		elseif ($gestao_data['msg_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['msg_gestao_brainstorm']);
		elseif ($gestao_data['msg_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['msg_gestao_gut']);
		elseif ($gestao_data['msg_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['msg_gestao_causa_efeito']);
		elseif ($gestao_data['msg_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['msg_gestao_arquivo']);
		elseif ($gestao_data['msg_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['msg_gestao_forum']);
		elseif ($gestao_data['msg_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['msg_gestao_checklist']);
		elseif ($gestao_data['msg_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['msg_gestao_agenda']);
		elseif ($gestao_data['msg_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['msg_gestao_agrupamento']);
		elseif ($gestao_data['msg_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['msg_gestao_patrocinador']);
		elseif ($gestao_data['msg_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['msg_gestao_template']);
		elseif ($gestao_data['msg_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['msg_gestao_painel']);
		elseif ($gestao_data['msg_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['msg_gestao_painel_odometro']);
		elseif ($gestao_data['msg_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['msg_gestao_painel_composicao']);		
		elseif ($gestao_data['msg_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['msg_gestao_tr']);	
		elseif ($gestao_data['msg_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['msg_gestao_me']);	
		elseif ($gestao_data['msg_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['msg_gestao_acao_item']);	
		elseif ($gestao_data['msg_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['msg_gestao_beneficio']);	
		elseif ($gestao_data['msg_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['msg_gestao_painel_slideshow']);	
		elseif ($gestao_data['msg_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['msg_gestao_projeto_viabilidade']);	
		elseif ($gestao_data['msg_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['msg_gestao_projeto_abertura']);	
		elseif ($gestao_data['msg_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['msg_gestao_plano_gestao']);	
		elseif ($gestao_data['msg_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['msg_gestao_ssti']);	
		elseif ($gestao_data['msg_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['msg_gestao_laudo']);	
		elseif ($gestao_data['msg_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['msg_gestao_trelo']);	
		elseif ($gestao_data['msg_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['msg_gestao_trelo_cartao']);	
		elseif ($gestao_data['msg_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['msg_gestao_pdcl']);	
		elseif ($gestao_data['msg_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['msg_gestao_pdcl_item']);	
		elseif ($gestao_data['msg_gestao_os']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/os_p.png').link_os($gestao_data['msg_gestao_os']);	
		}
	echo '</td></tr></table>';	
	}	


if ($Aplic->getPref('msg_extra')) include_once(BASE_DIR.'/modulos/email/exibe_extra.php');


echo '</body></html>';
if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script language=Javascript>self.print();</script>';

function formata_destinatario($rs_para=array()){
	global $Aplic,$tipos_status;
	$saida='';
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
	if ($rs_para['copia_oculta'] !=1|| $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3) $saida.= nome_funcao($rs_para['nome_para'], $rs_para['nome_usuario'], $rs_para['funcao_para'], $rs_para['contato_funcao'], $rs_para['para_id']);
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3)) $saida.= '</i>';
	if ($rs_para['copia_oculta'] !=1 || $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3){
		if (!$rs_para['datahora_leitura']){
			$saida.= ' - n�o lida'; 
			}
		else{
			$saida.= ' - '.$tipos_status[$rs_para['status']].' em '.retorna_data($rs_para['datahora_leitura']);
			}
		}	
	return $saida;	
	}

function formata_despacho ($rs_anotf=array()){
	global $Aplic;
	$saida='';
	if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '<b>';
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
  if ($rs_anotf['copia_oculta'] !=1 || ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= nome_funcao($rs_anotf['nome_para'], $rs_anotf['nome_usuario'], $rs_anotf['funcao_para'], $rs_anotf['contato_funcao'])."&nbsp;&nbsp;";
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3 )) $saida.= '</i>'; 
  if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '</b>';
  return $saida;
	}

?>
<script language=Javascript>
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
		
function ver_destinatario(){
	if (document.getElementById('destinatario').style.display=='none') document.getElementById('destinatario').style.display='';
	else document.getElementById('destinatario').style.display='none';
	}

</script>

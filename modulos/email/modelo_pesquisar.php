<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
global $dialogo;

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (!$dialogo) $Aplic->salvarPosicao();


if (isset($_REQUEST['vetor_modelo_msg_usuario'])) $vetor_modelo_msg_usuario=getParam($_REQUEST, 'vetor_modelo_msg_usuario', null); 
else if (isset($_REQUEST['modelo_usuario_id']) && $_REQUEST['modelo_usuario_id']) $vetor_modelo_msg_usuario[]=getParam($_REQUEST, 'modelo_usuario_id', null);

if (!isset($vetor_modelo_msg_usuario)){
	if (isset($_REQUEST['modeloID']) && $_REQUEST['modeloID']) $modeloID=getParam($_REQUEST, 'modeloID', null); 
	else if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']) $modeloID[]=getParam($_REQUEST, 'modelo_id', null);
	else if (!isset($modeloID)) $modeloID = array();
	}

if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario=getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[]=getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();

$coletivo=($Aplic->usuario_lista_grupo && $Aplic->usuario_lista_grupo!=$Aplic->usuario_id);

$pesquisar_modelo_irrestrito=$Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'pesquisar_modelo');
$pesquisar_cia_irrestrita=$Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'pesquisar_cia');

$msg_usuario_id=reset($vetor_msg_usuario); 
$msg_id=getParam($_REQUEST, 'msg_id', null);
$status=getParam($_REQUEST, 'status', 1);
$senha=getParam($_REQUEST, 'senha', '');
$campo_ordenar=getParam($_REQUEST, 'campo_ordenar', 'data');
$pesquisar=getParam($_REQUEST, 'pesquisar', 0);
$Aplic->carregarCalendarioJS();
$pesquisa_inicio=getParam($_REQUEST, 'pesquisa_inicio', null);
$pesquisa_fim=getParam($_REQUEST, 'pesquisa_fim', null);
$data_inicio = intval($pesquisa_inicio) ? new CData($pesquisa_inicio) : new CData();
$data_fim = intval($pesquisa_fim) ? new CData($pesquisa_fim) : new CData();
$pagina=getParam($_REQUEST, 'pagina', 1);
$sentido=getParam($_REQUEST, 'sentido', 1);
$numero=getParam($_REQUEST, 'numero', '');
$modelo_protocolo=getParam($_REQUEST, 'modelo_protocolo', '');
$assunto=getParam($_REQUEST, 'assunto', '');

$tipo_tempo=getParam($_REQUEST, 'tipo_tempo', '');
$criador=getParam($_REQUEST, 'criador', 0);
$aprovou=getParam($_REQUEST, 'aprovou', 0);
$estado_documento=getParam($_REQUEST, 'estado_documento', '');
$acao_documento=getParam($_REQUEST, 'acao_documento', '');

$modelo_tipo_id=getParam($_REQUEST, 'modelo_tipo_id', '');
$tipo_documento=getParam($_REQUEST, 'tipo_documento', 0);

if($tipo_documento > 0)$modelo_tipo_id=$tipo_documento;
elseif($tipo_documento < 0)$modelo_tipo_id=0;

$caixa_entrada=getParam($_REQUEST, 'caixa_entrada', null);
$caixa_pendente=getParam($_REQUEST, 'caixa_pendente', null);
$caixa_arquivado=getParam($_REQUEST, 'caixa_arquivado', null);
$caixa_elaborar=getParam($_REQUEST, 'caixa_elaborar', null);
$caixa_para_protocolar=getParam($_REQUEST, 'caixa_para_protocolar', null);
$caixa_protocolado=getParam($_REQUEST, 'caixa_protocolado', null);
$caixa_assinado=getParam($_REQUEST, 'caixa_assinado', null);
$anexar_documento=getParam($_REQUEST, 'anexar_documento', null);
$referenciar_documento=getParam($_REQUEST, 'referenciar_documento', null);
$anexar_msg=getParam($_REQUEST, 'anexar_msg', null);
$retornar=getParam($_REQUEST, 'retornar', '');
$campo=getParam($_REQUEST, 'campo', 0);
$modelo_id=getParam($_REQUEST, 'modelo_id', null);
$pesquisar_tudo=getParam($_REQUEST, 'pesquisar_tudo', 0);
$item_menu=getParam($_REQUEST, 'item_menu', ($anexar_documento || $referenciar_documento ? '' : 'entrada'));
$pasta=getParam($_REQUEST, 'pasta', null);
$mover=getParam($_REQUEST, 'mover', array());
$tipo=array(''=> '', '0'=>'', '1'=>'Despacho', '2'=>'Resposta', '3'=>'Encaminhamento', '4'=>'Nota');


if (isset($_REQUEST['tarefa_id'])) $Aplic->setEstado('tarefa_id', getParam($_REQUEST,'tarefa_id', null));
$tarefa_id  = $Aplic->getEstado('tarefa_id', null);

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('projeto_id', getParam($_REQUEST,'projeto_id', null));
$projeto_id  = $Aplic->getEstado('projeto_id', null);

if (isset($_REQUEST['pg_perspectiva_id'])) $Aplic->setEstado('pg_perspectiva_id', getParam($_REQUEST,'pg_perspectiva_id', null));
$pg_perspectiva_id  = $Aplic->getEstado('pg_perspectiva_id', null);

if (isset($_REQUEST['tema_id'])) $Aplic->setEstado('tema_id', getParam($_REQUEST,'tema_id', null));
$tema_id  = $Aplic->getEstado('tema_id', null);

if (isset($_REQUEST['objetivo_id'])) $Aplic->setEstado('objetivo_id', getParam($_REQUEST,'objetivo_id', null));
$objetivo_id  = $Aplic->getEstado('objetivo_id', null);

if (isset($_REQUEST['fator_id'])) $Aplic->setEstado('fator_id', getParam($_REQUEST,'fator_id', null));
$fator_id  = $Aplic->getEstado('fator_id', null);

if (isset($_REQUEST['pg_estrategia_id'])) $Aplic->setEstado('pg_estrategia_id', getParam($_REQUEST,'pg_estrategia_id', null));
$pg_estrategia_id = $Aplic->getEstado('pg_estrategia_id', null);

if (isset($_REQUEST['pg_meta_id'])) $Aplic->setEstado('pg_meta_id', getParam($_REQUEST,'pg_meta_id', null));
$pg_meta_id  = $Aplic->getEstado('pg_meta_id', null);

if (isset($_REQUEST['pratica_id'])) $Aplic->setEstado('pratica_id', getParam($_REQUEST,'pratica_id', null));
$pratica_id  = $Aplic->getEstado('pratica_id', null);

if (isset($_REQUEST['pratica_indicador_id'])) $Aplic->setEstado('pratica_indicador_id', getParam($_REQUEST,'pratica_indicador_id', null));
$pratica_indicador_id  = $Aplic->getEstado('pratica_indicador_id', null);

if (isset($_REQUEST['plano_acao_id'])) $Aplic->setEstado('plano_acao_id', getParam($_REQUEST,'plano_acao_id', null));
$plano_acao_id  = $Aplic->getEstado('plano_acao_id', null);

if (isset($_REQUEST['canvas_id'])) $Aplic->setEstado('canvas_id', getParam($_REQUEST,'canvas_id', null));
$canvas_id  = $Aplic->getEstado('canvas_id', null);

if (isset($_REQUEST['risco_id'])) $Aplic->setEstado('risco_id', getParam($_REQUEST,'risco_id', null));
$risco_id = $Aplic->getEstado('risco_id', null);

if (isset($_REQUEST['risco_resposta_id'])) $Aplic->setEstado('risco_resposta_id', getParam($_REQUEST,'risco_resposta_id', null));
$risco_resposta_id = $Aplic->getEstado('risco_resposta_id', null);

if (isset($_REQUEST['calendario_id'])) $Aplic->setEstado('calendario_id', getParam($_REQUEST,'calendario_id', null));
$calendario_id  = $Aplic->getEstado('calendario_id', null);

if (isset($_REQUEST['monitoramento_id'])) $Aplic->setEstado('monitoramento_id', getParam($_REQUEST,'monitoramento_id', null));
$monitoramento_id  = $Aplic->getEstado('monitoramento_id', null);

if (isset($_REQUEST['ata_id'])) $Aplic->setEstado('ata_id', getParam($_REQUEST,'ata_id', null));
$ata_id  = $Aplic->getEstado('ata_id', null);

if (isset($_REQUEST['mswot_id'])) $Aplic->setEstado('mswot_id', getParam($_REQUEST,'mswot_id', null));
$mswot_id  = $Aplic->getEstado('mswot_id', null);

if (isset($_REQUEST['swot_id'])) $Aplic->setEstado('swot_id', getParam($_REQUEST,'swot_id', null));
$swot_id  = $Aplic->getEstado('swot_id', null);

if (isset($_REQUEST['operativo_id'])) $Aplic->setEstado('operativo_id', getParam($_REQUEST,'operativo_id', null));
$operativo_id = $Aplic->getEstado('operativo_id', null);

if (isset($_REQUEST['instrumento_id'])) $Aplic->setEstado('instrumento_id', getParam($_REQUEST,'instrumento_id', null));
$instrumento_id = $Aplic->getEstado('instrumento_id', null);

if (isset($_REQUEST['recurso_id'])) $Aplic->setEstado('recurso_id', getParam($_REQUEST,'recurso_id', null));
$recurso_id = $Aplic->getEstado('recurso_id', null);

if (isset($_REQUEST['problema_id'])) $Aplic->setEstado('problema_id', getParam($_REQUEST,'problema_id', null));
$problema_id = $Aplic->getEstado('problema_id', null);

if (isset($_REQUEST['demanda_id'])) $Aplic->setEstado('demanda_id', getParam($_REQUEST,'demanda_id', null));
$demanda_id = $Aplic->getEstado('demanda_id', null);

if (isset($_REQUEST['programa_id'])) $Aplic->setEstado('programa_id', getParam($_REQUEST,'programa_id', null));
$programa_id = $Aplic->getEstado('programa_id', null);

if (isset($_REQUEST['licao_id'])) $Aplic->setEstado('licao_id', getParam($_REQUEST,'licao_id', null));
$licao_id = $Aplic->getEstado('licao_id', null);

if (isset($_REQUEST['evento_id'])) $Aplic->setEstado('evento_id', getParam($_REQUEST,'evento_id', null));
$evento_id = $Aplic->getEstado('evento_id', null);

if (isset($_REQUEST['link_id'])) $Aplic->setEstado('link_id', getParam($_REQUEST,'link_id', null));
$link_id = $Aplic->getEstado('link_id', null);

if (isset($_REQUEST['avaliacao_id'])) $Aplic->setEstado('avaliacao_id', getParam($_REQUEST,'avaliacao_id', null));
$avaliacao_id = $Aplic->getEstado('avaliacao_id', null);

if (isset($_REQUEST['tgn_id'])) $Aplic->setEstado('tgn_id', getParam($_REQUEST,'tgn_id', null));
$tgn_id = $Aplic->getEstado('tgn_id', null);

if (isset($_REQUEST['brainstorm_id'])) $Aplic->setEstado('brainstorm_id', getParam($_REQUEST,'brainstorm_id', null));
$brainstorm_id = $Aplic->getEstado('brainstorm_id', null);

if (isset($_REQUEST['gut_id'])) $Aplic->setEstado('gut_id', getParam($_REQUEST,'gut_id', null));
$gut_id = $Aplic->getEstado('gut_id', null);

if (isset($_REQUEST['causa_efeito_id'])) $Aplic->setEstado('causa_efeito_id', getParam($_REQUEST,'causa_efeito_id', null));
$causa_efeito_id = $Aplic->getEstado('causa_efeito_id', null);

if (isset($_REQUEST['arquivo_id'])) $Aplic->setEstado('arquivo_id', getParam($_REQUEST,'arquivo_id', null));
$arquivo_id = $Aplic->getEstado('arquivo_id', null);

if (isset($_REQUEST['forum_id'])) $Aplic->setEstado('forum_id', getParam($_REQUEST,'forum_id', null));
$forum_id = $Aplic->getEstado('forum_id', null);

if (isset($_REQUEST['checklist_id'])) $Aplic->setEstado('checklist_id', getParam($_REQUEST,'checklist_id', null));
$checklist_id = $Aplic->getEstado('checklist_id', null);

if (isset($_REQUEST['agenda_id'])) $Aplic->setEstado('agenda_id', getParam($_REQUEST,'agenda_id', null));
$agenda_id = $Aplic->getEstado('agenda_id', null);

if (isset($_REQUEST['agrupamento_id'])) $Aplic->setEstado('agrupamento_id', getParam($_REQUEST,'agrupamento_id', null));
$agrupamento_id = $Aplic->getEstado('agrupamento_id', null);

if (isset($_REQUEST['patrocinador_id'])) $Aplic->setEstado('patrocinador_id', getParam($_REQUEST,'patrocinador_id', null));
$patrocinador_id = $Aplic->getEstado('patrocinador_id', null);

if (isset($_REQUEST['template_id'])) $Aplic->setEstado('template_id', getParam($_REQUEST,'template_id', null));
$template_id = $Aplic->getEstado('template_id', null);

if (isset($_REQUEST['painel_id'])) $Aplic->setEstado('painel_id', getParam($_REQUEST,'painel_id', null));
$painel_id = $Aplic->getEstado('painel_id', null);

if (isset($_REQUEST['painel_odometro_id'])) $Aplic->setEstado('painel_odometro_id', getParam($_REQUEST,'painel_odometro_id', null));
$painel_odometro_id = $Aplic->getEstado('painel_odometro_id', null);

if (isset($_REQUEST['painel_composicao_id'])) $Aplic->setEstado('painel_composicao_id', getParam($_REQUEST,'painel_composicao_id', null));
$painel_composicao_id = $Aplic->getEstado('painel_composicao_id', null);

if (isset($_REQUEST['tr_id'])) $Aplic->setEstado('tr_id', getParam($_REQUEST,'tr_id', null));
$tr_id = $Aplic->getEstado('tr_id', null);

if (isset($_REQUEST['me_id'])) $Aplic->setEstado('me_id', getParam($_REQUEST,'me_id', null));
$me_id = $Aplic->getEstado('me_id', null);

if (isset($_REQUEST['plano_acao_item_id'])) $Aplic->setEstado('plano_acao_item_id', getParam($_REQUEST,'plano_acao_item_id', null));
$plano_acao_item_id = $Aplic->getEstado('plano_acao_item_id', null);

if (isset($_REQUEST['beneficio_id'])) $Aplic->setEstado('beneficio_id', getParam($_REQUEST,'beneficio_id', null));
$beneficio_id = $Aplic->getEstado('beneficio_id', null);

if (isset($_REQUEST['painel_slideshow_id'])) $Aplic->setEstado('painel_slideshow_id', getParam($_REQUEST,'painel_slideshow_id', null));
$painel_slideshow_id = $Aplic->getEstado('painel_slideshow_id', null);

if (isset($_REQUEST['projeto_viabilidade_id'])) $Aplic->setEstado('projeto_viabilidade_id', getParam($_REQUEST,'projeto_viabilidade_id', null));
$projeto_viabilidade_id = $Aplic->getEstado('projeto_viabilidade_id', null);

if (isset($_REQUEST['projeto_abertura_id'])) $Aplic->setEstado('projeto_abertura_id', getParam($_REQUEST,'projeto_abertura_id', null));
$projeto_abertura_id = $Aplic->getEstado('projeto_abertura_id', null);

if (isset($_REQUEST['pg_id'])) $Aplic->setEstado('pg_id', getParam($_REQUEST,'pg_id', null));
$pg_id = $Aplic->getEstado('pg_id', null);

if (isset($_REQUEST['ssti_id'])) $Aplic->setEstado('ssti_id', getParam($_REQUEST,'ssti_id', null));
$ssti_id = $Aplic->getEstado('ssti_id', null);

if (isset($_REQUEST['laudo_id'])) $Aplic->setEstado('laudo_id', getParam($_REQUEST,'laudo_id', null));
$laudo_id = $Aplic->getEstado('laudo_id', null);

if (isset($_REQUEST['trelo_id'])) $Aplic->setEstado('trelo_id', getParam($_REQUEST,'trelo_id', null));
$trelo_id = $Aplic->getEstado('trelo_id', null);

if (isset($_REQUEST['trelo_cartao_id'])) $Aplic->setEstado('trelo_cartao_id', getParam($_REQUEST,'trelo_cartao_id', null));
$trelo_cartao_id = $Aplic->getEstado('trelo_cartao_id', null);

if (isset($_REQUEST['pdcl_id'])) $Aplic->setEstado('pdcl_id', getParam($_REQUEST,'pdcl_id', null));
$pdcl_id = $Aplic->getEstado('pdcl_id', null);

if (isset($_REQUEST['pdcl_item_id'])) $Aplic->setEstado('pdcl_item_id', getParam($_REQUEST,'pdcl_item_id', null));
$pdcl_item_id = $Aplic->getEstado('pdcl_item_id', null);

if (isset($_REQUEST['os_id'])) $Aplic->setEstado('os_id', getParam($_REQUEST,'os_id', null));
$os_id = $Aplic->getEstado('os_id', null);

//muda a ordena??o ao clicar nos titulos
if ($sentido) $ordem='DESC'; 
else $ordem='ASC' ; 

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden id="a" name="a" value="modelo_pesquisar">';
echo '<input type=hidden id="msg_id" name="msg_id" value="'.$msg_id.'">';
echo '<input type=hidden id="msg_usuario_id" name="msg_usuario_id" value="'.$msg_usuario_id.'">';	
echo '<input type=hidden id="pesquisar" name="pesquisar" value="'.$pesquisar.'">';
echo '<input type=hidden id="pagina" name="pagina" value="'.$pagina.'">';
echo '<input type=hidden id="sentido" name="sentido" value="'.$sentido.'">';	
echo '<input type=hidden id="campo_ordenar" name="campo_ordenar" value="'.$campo_ordenar.'">';
echo '<input type=hidden id="editar" name="editar" value="">';
echo '<input type=hidden id="item_menu" name="item_menu" value="'.$item_menu.'">';
echo '<input type=hidden id="caixa_entrada" name="caixa_entrada" value="">';
echo '<input type=hidden id="caixa_pendente" name="caixa_pendente" value="">';
echo '<input type=hidden id="caixa_arquivado" name="caixa_arquivado" value="">';
echo '<input type=hidden id="caixa_elaborar" name="caixa_elaborar" value="'.$caixa_elaborar.'">';
echo '<input type=hidden id="caixa_para_protocolar" name="caixa_para_protocolar" value="">';
echo '<input type=hidden id="caixa_protocolado" name="caixa_protocolado" value="">';
echo '<input type=hidden id="caixa_assinado" name="caixa_assinado" value="">';
echo '<input type=hidden id="anexar_documento" name="anexar_documento" value="'.$anexar_documento.'">';
echo '<input type=hidden id="referenciar_documento" name="referenciar_documento" value="'.$referenciar_documento.'">';
echo '<input type=hidden id="dialogo" name="dialogo" value="'.$dialogo.'">';
echo '<input type=hidden id="anexar_msg" name="anexar_msg" value="">';
echo '<input type=hidden id="retornar" name="retornar" value="'.$retornar.'">';
echo '<input type=hidden id="campo" name="campo" value="'.$campo.'">';
echo '<input type=hidden id="modelo_id" name="modelo_id" value="'.$modelo_id.'">';
echo '<input type=hidden id="modelo_tipo_id" name="modelo_tipo_id" value="'.$modelo_tipo_id.'">';
echo '<input type=hidden id="status" name="status" value="'.$status.'">';
echo '<input type=hidden name="mover" id="mover" value="">';
echo '<input type=hidden name="pasta" id="pasta" value="'.$pasta.'">';
echo '<input type=hidden id="tipo" name="tipo" value="">';
echo '<input type=hidden name="destino" id="destino" value="">';	
echo '<input type=hidden name="arquivar" id="arquivar" value="">';
echo '<input type=hidden name="novo" id="novo" value="">';
echo '<input type=hidden name="tab" id="tab" value="">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="">';
echo '<input type=hidden name="modelo_usuario_id" id="modelo_usuario_id" value="">';
echo '<input type="hidden" name="cia_id" id="cia_id" value="'.$Aplic->usuario_cia.'" />';

echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="'.$pg_perspectiva_id.'" />';
echo '<input type="hidden" name="tema_id" id="tema_id" value="'.$tema_id.'" />';
echo '<input type="hidden" name="objetivo_id" id="objetivo_id" value="'.$objetivo_id.'" />';
echo '<input type="hidden" name="fator_id" id="fator_id" value="'.$fator_id.'" />';
echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="'.$pg_meta_id.'" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="plano_acao_id" id="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="canvas_id" id="canvas_id" value="'.$canvas_id.'" />';
echo '<input type="hidden" name="risco_id" id="risco_id" value="'.$risco_id.'" />';
echo '<input type="hidden" name="risco_resposta_id" id="risco_resposta_id" value="'.$risco_resposta_id.'" />';
echo '<input type="hidden" name="calendario_id" id="calendario_id" value="'.$calendario_id.'" />';
echo '<input type="hidden" name="monitoramento_id" id="monitoramento_id" value="'.$monitoramento_id.'" />';
echo '<input type="hidden" name="ata_id" id="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="mswot_id" id="mswot_id" value="'.$mswot_id.'" />';
echo '<input type="hidden" name="swot_id" id="swot_id" value="'.$swot_id.'" />';
echo '<input type="hidden" name="operativo_id" id="operativo_id" value="'.$operativo_id.'" />';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input type="hidden" name="recurso_id" id="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="problema_id" id="problema_id" value="'.$problema_id.'" />';
echo '<input type="hidden" name="demanda_id" id="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="programa_id" id="programa_id" value="'.$programa_id.'" />';
echo '<input type="hidden" name="licao_id" id="licao_id" value="'.$licao_id.'" />';
echo '<input type="hidden" name="evento_id" id="evento_id" value="'.$evento_id.'" />';
echo '<input type="hidden" name="link_id" id="link_id" value="'.$link_id.'" />';
echo '<input type="hidden" name="avaliacao_id" id="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="tgn_id" id="tgn_id" value="'.$tgn_id.'" />';
echo '<input type="hidden" name="brainstorm_id" id="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="gut_id" id="gut_id" value="'.$gut_id.'" />';
echo '<input type="hidden" name="causa_efeito_id" id="causa_efeito_id" value="'.$causa_efeito_id.'" />';
echo '<input type="hidden" name="arquivo_id" id="arquivo_id" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="forum_id" id="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="checklist_id" id="checklist_id" value="'.$checklist_id.'" />';
echo '<input type="hidden" name="agenda_id" id="agenda_id" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="agrupamento_id" id="agrupamento_id" value="'.$agrupamento_id.'" />';
echo '<input type="hidden" name="patrocinador_id" id="patrocinador_id" value="'.$patrocinador_id.'" />';
echo '<input type="hidden" name="template_id" id="template_id" value="'.$template_id.'" />';
echo '<input type="hidden" name="painel_id" id="painel_id" value="'.$painel_id.'" />';
echo '<input type="hidden" name="painel_odometro_id" id="painel_odometro_id" value="'.$painel_odometro_id.'" />';
echo '<input type="hidden" name="painel_composicao_id" id="painel_composicao_id" value="'.$painel_composicao_id.'" />';
echo '<input type="hidden" name="tr_id" id="tr_id" value="'.$tr_id.'" />';
echo '<input type="hidden" name="me_id" id="me_id" value="'.$me_id.'" />';
echo '<input type="hidden" name="plano_acao_item_id" id="plano_acao_item_id" value="'.$plano_acao_item_id.'" />';
echo '<input type="hidden" name="beneficio_id" id="beneficio_id" value="'.$beneficio_id.'" />';
echo '<input type="hidden" name="painel_slideshow_id" id="painel_slideshow_id" value="'.$painel_slideshow_id.'" />';
echo '<input type="hidden" name="projeto_viabilidade_id" id="projeto_viabilidade_id" value="'.$projeto_viabilidade_id.'" />';
echo '<input type="hidden" name="projeto_abertura_id" id="projeto_abertura_id" value="'.$projeto_abertura_id.'" />';
echo '<input type="hidden" name="pg_id" id="pg_id" value="'.$pg_id.'" />';
echo '<input type="hidden" name="ssti_id" id="ssti_id" value="'.$ssti_id.'" />';
echo '<input type="hidden" name="laudo_id" id="laudo_id" value="'.$laudo_id.'" />';
echo '<input type="hidden" name="trelo_id" id="trelo_id" value="'.$trelo_id.'" />';
echo '<input type="hidden" name="trelo_cartao_id" id="trelo_cartao_id" value="'.$trelo_cartao_id.'" />';
echo '<input type="hidden" name="pdcl_id" id="pdcl_id" value="'.$pdcl_id.'" />';
echo '<input type="hidden" name="pdcl_item_id" id="pdcl_item_id" value="'.$pdcl_item_id.'" />';
echo '<input type="hidden" name="os_id" id="os_id" value="'.$os_id.'" />';


$sql = new BDConsulta;

if ($anexar_msg){
	$data=date('Y-m-d H:i:s');
	$modelo_id=$anexar_msg;
	$sql->adTabela('anexo');
	$sql->adCampo('anexo_id');
	$sql->adOnde('anexo_msg = '.(int)$msg_id);
	$sql->adOnde('anexo_modelo = '.(int)$modelo_id);
	$existente=$sql->Resultado();
	
	if (!$existente){
		$sql->adTabela('modelos');
		$sql->esqUnir('usuarios','usuarios','modelo_criador_original=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo_id = modelo_tipo');
		$sql->esqUnir('modelos_dados','modelos_dados','modelo_dados_modelo=modelos.modelo_id');
		//$sql->esqUnir('modelo_anexo', 'modelo_anexo', 'modelo_anexo_modelo=modelos.modelo_id');
		$sql->adCampo('modelos.modelo_id, modelo_tipo_nome, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, modelo_assunto,  modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada');
		$sql->adOnde('modelos.modelo_id = '.(int)$modelo_id);
		$linha=$sql->Linha();
		$sql->limpar();
		$assinatura='';
		if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
			$identificador=$msg_id.$linha['modelo_assunto'].$Aplic->usuario_id.$linha['modelo_tipo_nome'].$data.$modelo_id;
			openssl_sign($identificador, $assinatura, $Aplic->chave_privada);
			}
		$sql->adTabela('anexo');
		$sql->adInserir('anexo_msg', $msg_id);
		$sql->adInserir('anexo_nome', $linha['modelo_assunto']);
		$sql->adInserir('anexo_usuario', $Aplic->usuario_id);
		$sql->adInserir('anexo_tipo_doc', $linha['modelo_tipo_nome']);
		$sql->adInserir('anexo_nome_de', $Aplic->usuario_nome);
		$sql->adInserir('anexo_funcao_de', $Aplic->usuario_funcao);
		$sql->adInserir('anexo_data_envio', $data);
		$sql->adInserir('anexo_modelo', $modelo_id);
		$sql->adInserir('anexo_assinatura',  base64_encode($assinatura));
		if ($Aplic->chave_publica_id) $sql->adInserir('anexo_chave_publica', $Aplic->chave_publica_id);
		if (!$sql->exec()) echo ('N?o foi poss?vel inserir os anexos na tabela anexos!');
		$sql->limpar();
		echo '<script type="text/javascript">alert("Documento foi anexado.");</script>';
		}
	else 	echo '<script type="text/javascript">alert("Documento j? foi anexado anteriormente.");</script>';
	echo '<script type="text/javascript">env.a.value="'.$retornar.'";env.submit();</script>';
	exit();
	}

if (!$dialogo){
		
	
	$tipos=array(''=>'');

	if ($Aplic->checarModulo('projetos', 'acesso', null, 'projetos_lista')) $tipos['popProjeto']=ucfirst($config['projeto']);
	if ($Aplic->checarModulo('tarefas', 'acesso', null, null)) $tipos['popTarefa']=ucfirst($config['tarefa']);
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'perspectiva')) $tipos['popPerspectiva']=ucfirst($config['perspectiva']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'tema')) $tipos['popTema']=ucfirst($config['tema']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'objetivo')) $tipos['popObjetivo']=ucfirst($config['objetivo']); 
	if ($config['exibe_fator'] && $Aplic->checarModulo('praticas', 'acesso', null, 'fator')) $tipos['popFator']=ucfirst($config['fator']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'iniciativa')) $tipos['popEstrategia']=ucfirst($config['iniciativa']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'meta')) $tipos['popMeta']=ucfirst($config['meta']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $tipos['popAcao']=ucfirst($config['acao']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'pratica')) $tipos['popPratica']=ucfirst($config['pratica']); 
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $tipos['popIndicador']='Indicador'; 
	if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso', null, null)) $tipos['popAta']='Ata de reuni?o';	
	if ($Aplic->modulo_ativo('swot') && $Aplic->checarModulo('swot', 'acesso', null, null)) {
		$tipos['popMSWOT']='Matriz SWOT';
		$tipos['popSWOT']='Campo SWOT';
		}
	if ($Aplic->modulo_ativo('operativo') && $Aplic->checarModulo('operativo', 'acesso', null, null)) $tipos['popOperativo']='Plano operativo';
	if ($Aplic->checarModulo('agenda', 'acesso', null, null)) $tipos['popCalendario']='Agenda';
	if ($Aplic->modulo_ativo('instrumento') && $Aplic->checarModulo('instrumento', 'acesso', null, null)) $tipos['popInstrumento']=ucfirst($config['instrumento']);
	if ($Aplic->checarModulo('recursos', 'acesso', null, null)) $tipos['popRecurso']=ucfirst($config['recurso']);
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'demanda')) $tipos['popDemanda']='Demanda';
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'licao')) $tipos['popLicao']=ucfirst($config['licao']);
	if ($Aplic->checarModulo('eventos', 'acesso', null, null)) $tipos['popEvento']='Evento';
	if ($Aplic->checarModulo('links', 'acesso', null, null)) $tipos['popLink']='Link';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'avaliacao_indicador')) $tipos['popAvaliacao']='Avalia??o';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'brainstorm')) $tipos['popBrainstorm']='Brainstorm';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'gut')) $tipos['popGut']='Matriz GUT';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'causa_efeito')) $tipos['popCausa_efeito']='Diagrama de causa-efeito';
	if ($Aplic->checarModulo('arquivos', 'acesso', null,  null)) $tipos['popArquivo']='Arquivo';
	if ($Aplic->checarModulo('foruns', 'acesso', null, null)) $tipos['popForum']='F?rum';	
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'checklist')) $tipos['popChecklist']='Checklist';
	if ($Aplic->modulo_ativo('patrocinadores') && $Aplic->checarModulo('patrocinadores', 'acesso', null, null)) $tipos['popPatrocinador']=ucfirst($config['patrocinador']);
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao_item')) $tipos['popAcaoItem']='Item de '.$config['acao'];
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'viabilidade')) $tipos['popViabilidade']='Estudo de viabilidade';
	if ($Aplic->checarModulo('projetos', 'acesso', null, 'abertura')) $tipos['popAbertura']='Termo de abertura';
	if ($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento')) $tipos['popPlanejamento']='Planejamento estrat?gico';					
	if ($Aplic->profissional)  {
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'canvas')) $tipos['popCanvas']=ucfirst($config['canvas']);
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $tipos['popRisco']=ucfirst($config['risco']);
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'resposta_risco')) $tipos['popRiscoResposta']=ucfirst($config['risco_resposta']);
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'monitoramento')) $tipos['popMonitoramento']='Monitoramento';
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso', null, null)) $tipos['popProblema']=ucfirst($config['problema']);
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'programa')) $tipos['popPrograma']=ucfirst($config['programa']);
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'tgn')) $tipos['popTgn']=ucfirst($config['tgn']);
		$tipos['popAgenda']='Compromisso';
		if ($Aplic->modulo_ativo('agrupamento') && $Aplic->checarModulo('agrupamento', 'acesso', null, null)) $tipos['popAgrupamento']='Agrupamento';
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'modelo')) $tipos['popTemplate']='Modelo';
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'painel_indicador')) $tipos['popPainel']='Painel de indicador';
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'odometro_indicador')) $tipos['popOdometro']='Od?metro de indicador';
		if ($Aplic->checarModulo('praticas', 'acesso', null, 'composicao_painel')) $tipos['popComposicaoPaineis']='Composi??o de pain?is';
		if ($Aplic->modulo_ativo('tr') && $Aplic->checarModulo('tr', 'acesso', null, null)) $tipos['popTR']=ucfirst($config['tr']);
		if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'acesso', null, 'me')) $tipos['popMe']=ucfirst($config['me']);	
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'beneficio')) $tipos['popBeneficio']=ucfirst($config['beneficio']).' de '.$config['programa'];
		if ($Aplic->checarModulo('projetos', 'acesso', null, 'slideshow_painel')) $tipos['popSlideshow']='Slideshow de pain?is';
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso', null, 'ssti')) $tipos['popSSTI']=ucfirst($config['ssti']);
		if ($Aplic->modulo_ativo('ssti') && $Aplic->checarModulo('ssti', 'acesso', null, 'laudo')) $tipos['popLaudo']=ucfirst($config['laudo']);
		if ($Aplic->modulo_ativo('trelo') && $Aplic->checarModulo('trelo', 'acesso', null, null)) {
			$tipos['popTrelo']=ucfirst($config['trelo']);
			$tipos['popTreloCartao']=ucfirst($config['trelo_cartao']);
			}
		if ($Aplic->modulo_ativo('pdcl') && $Aplic->checarModulo('pdcl', 'acesso', null, null)) {
			$tipos['popPDCL']=ucfirst($config['pdcl']);
			$tipos['pop_pdcl_item']=ucfirst($config['pdcl_item']);
			}
			
		if ($Aplic->modulo_ativo('os') && $Aplic->checarModulo('os', 'acesso', null, null))	$tipos['pop_os']=ucfirst($config['os']);
			
			
		}
	asort($tipos);

	
	if($plano_acao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Filtrar pel'.$config['genero_acao'].' '.$config['acao'].' que se relacionam.').ucfirst($config['acao']).':'.dicaF();
		$nome=nome_acao($plano_acao_id);
		}
	elseif($pratica_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Filtrar pel'.$config['genero_pratica'].' '.$config['pratica'].' que se relacionam.').ucfirst($config['pratica']).':'.dicaF();
		$nome=nome_pratica($pratica_id);
		}
	elseif($calendario_id){
		$legenda_filtro=dica('Filtrar pela Agenda', 'Filtrar pela agenda que se relacionam.').'Agenda:'.dicaF();
		$nome=nome_calendario($calendario_id);
		}
	elseif($projeto_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Filtrar pel'.$config['genero_projeto'].' '.$config['projeto'].' que se relacionam.').ucfirst($config['projeto']).':'.dicaF();
		$nome=nome_projeto($projeto_id);
		}
	elseif($tarefa_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Filtrar pel'.$config['genero_tarefa'].' '.$config['tarefa'].' que se relacionam.').ucfirst($config['tarefa']).':'.dicaF();
		$nome=nome_tarefa($tarefa_id);
		}	
	elseif($pratica_indicador_id){
		$legenda_filtro=dica('Filtrar pelo Indicador', 'Filtrar pelo indicador que se relacionam.').'Indicador:'.dicaF();
		$nome=nome_indicador($pratica_indicador_id);
		}
	elseif($objetivo_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Filtrar pel'.$config['genero_objetivo'].' '.$config['objetivo'].' que se relacionam.').''.ucfirst($config['objetivo']).':'.dicaF();
		$nome=nome_objetivo($objetivo_id);
		}
	elseif($tema_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tema'].' '.ucfirst($config['tema']).'', 'Filtrar pel'.$config['genero_tema'].' '.$config['tema'].' que se relacionam.').ucfirst($config['tema']).':'.dicaF();
		$nome=nome_tema($tema_id);
		}
	elseif($pg_estrategia_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Filtrar pel'.$config['genero_iniciativa'].' '.$config['iniciativa'].' que se relacionam.').ucfirst($config['iniciativa']).':'.dicaF();
		$nome=nome_estrategia($pg_estrategia_id);
		}
	elseif($pg_perspectiva_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Filtrar pel'.$config['genero_perspectiva'].' '.$config['perspectiva'].' que se relacionam.').ucfirst($config['perspectiva']).':'.dicaF();
		$nome=nome_perspectiva($pg_perspectiva_id);
		}
	elseif($canvas_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Filtrar pel'.$config['genero_canvas'].' '.$config['canvas'].' que se relacionam.').ucfirst($config['canvas']).':'.dicaF();
		$nome=nome_canvas($canvas_id);
		}
	elseif($fator_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Filtrar pel'.$config['genero_fator'].' '.$config['fator'].' que se relacionam.').ucfirst($config['fator']).':'.dicaF();
		$nome=nome_fator($fator_id);
		}
	elseif($pg_meta_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Filtrar pel'.$config['genero_meta'].' '.$config['meta'].' que se relacionam.').ucfirst($config['meta']).':'.dicaF();
		$nome=nome_meta($pg_meta_id);
		}	
	elseif($risco_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Filtrar pel'.$config['genero_risco'].' '.$config['risco'].' que se relacionam.').ucfirst($config['risco']).':'.dicaF();
		$nome=nome_risco($risco_id);
		}
	elseif($risco_resposta_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Filtrar pel'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' que se relacionam.').ucfirst($config['risco_resposta']).':'.dicaF();
		$nome=nome_risco_resposta($risco_resposta_id);
		}	
	elseif($monitoramento_id){
		$legenda_filtro=dica('Filtrar pelo Monitoramento', 'Filtrar pelo monitoramento que se relacionam.').'Monitoramento:'.dicaF();
		$nome=nome_monitoramento($monitoramento_id);
		}		
	elseif($ata_id){
		$legenda_filtro=dica('Filtrar pela Ata de Reuni?o', 'Filtrar pela ata de reuni?o a qual est?o relacionados.').'Ata:'.dicaF();
		$nome=nome_ata($ata_id);
		}		
	elseif($mswot_id){
		$legenda_filtro=dica('Filtrar pela Matriz SWOT', 'Filtrar pela matriz SWOT que se relacionam.').'Matriz SWOT:'.dicaF();
		$nome=nome_mswot($mswot_id);
		}	
	elseif($swot_id){
		$legenda_filtro=dica('Filtrar pelo Campo de Matriz SWOT', 'Filtrar pelo campo de matriz SWOT que se relacionam.').'Campo SWOT:'.dicaF();
		$nome=nome_swot($swot_id);
		}		
	elseif($operativo_id){
		$legenda_filtro=dica('Filtrar pelo Plano Operativo', 'Filtrar pelo plano operativo que se relacionam.').'Plano Operativo:'.dicaF();
		$nome=nome_operativo($operativo_id);
		}			
	elseif($instrumento_id){
		$legenda_filtro=dica('Filtrar pelo Instrumento Jur?dico', 'Filtrar pelo instrumento jur?dico que se relacionam.').'Instrumento Jur?dico:'.dicaF();
		$nome=nome_instrumento($instrumento_id);
		}	
	elseif($recurso_id){
		$legenda_filtro=dica('Filtrar pelo Recurso', 'Filtrar pelo recurso que se relacionam.').'Recurso:'.dicaF();
		$nome=nome_recurso($recurso_id);
		}	
	elseif($problema_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Filtrar pel'.$config['genero_problema'].' '.$config['problema'].' que se relacionam.').ucfirst($config['problema']).':'.dicaF();
		$nome=nome_problema($problema_id);
		}	
	elseif($demanda_id){
		$legenda_filtro=dica('Filtrar pela Demanda', 'Filtrar pela demanda que se relacionam.').'Demanda:'.dicaF();
		$nome=nome_demanda($demanda_id);
		}		
	elseif($programa_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Filtrar pel'.$config['genero_programa'].' '.$config['programa'].' que se relacionam.').ucfirst($config['programa']).':'.dicaF();
		$nome=nome_programa($programa_id);
		}	
	elseif($licao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Filtrar pel'.$config['genero_licao'].' '.$config['licao'].' que se relacionam.').ucfirst($config['licao']).':'.dicaF();
		$nome=nome_licao($licao_id);
		}	
	elseif($evento_id){
		$legenda_filtro=dica('Filtrar pelo Evento', 'Filtrar pelo evento que se relacionam.').'Evento:'.dicaF();
		$nome=nome_evento($evento_id);
		}		
	elseif($link_id){
		$legenda_filtro=dica('Filtrar pelo Link', 'Filtrar pelo link que se relacionam.').'Link:'.dicaF();
		$nome=nome_link($link_id);
		}
	elseif($avaliacao_id){
		$legenda_filtro=dica('Filtrar pela Avalia??o', 'Filtrar pela avalia??o que se relacionam.').'Avalia??o:'.dicaF();
		$nome=nome_avaliacao($avaliacao_id);
		}
	elseif($tgn_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Filtrar pel'.$config['genero_tgn'].' '.$config['tgn'].' que se relacionam.').ucfirst($config['tgn']).':'.dicaF();
		$nome=nome_tgn($tgn_id);
		}	
	elseif($brainstorm_id){
		$legenda_filtro=dica('Filtrar pelo Brainstorm', 'Filtrar pelo brainstorm que se relacionam.').'Brainstorm:'.dicaF();
		$nome=nome_brainstorm($brainstorm_id);
		}	
	elseif($gut_id){
		$legenda_filtro=dica('Filtrar pela Matriz GUT', 'Filtrar pela matriz GUT que se relacionam.').'Matriz GUT:'.dicaF();
		$nome=nome_gut($gut_id);
		}		
	elseif($causa_efeito_id){
		$legenda_filtro=dica('Filtrar pelo Diagrama de Causa-Efeito', 'Filtrar pelo diagrama de causa-efeito que se relacionam.').'Diagrama de Causa-Efeito:'.dicaF();
		$nome=nome_causa_efeito($causa_efeito_id);
		}		
	elseif($arquivo_id){
		$legenda_filtro=dica('Filtrar pelo Arquivo', 'Filtrar pelo arquivo que se relacionam.').'Arquivo:'.dicaF();
		$nome=nome_arquivo($arquivo_id);
		}	
	elseif($forum_id){
		$legenda_filtro=dica('Filtrar pelo F?rum', 'Filtrar pelo f?rum que se relacionam.').'F?rum:'.dicaF();
		$nome=nome_forum($forum_id);
		}	
	elseif($checklist_id){
		$legenda_filtro=dica('Filtrar pelo Checklist', 'Filtrar pelo checklist que se relacionam.').'Checklist:'.dicaF();
		$nome=nome_checklist($checklist_id);
		}	
	elseif($agenda_id){
		$legenda_filtro=dica('Filtrar pelo Compromisso', 'Filtrar pelo compromisso que se relacionam.').'Compromisso:'.dicaF();
		$nome=nome_compromisso($agenda_id);
		}	
	elseif($agrupamento_id){
		$legenda_filtro=dica('Filtrar pelo Agrupamento', 'Filtrar pelo agrupamento que se relacionam.').'Agrupamento:'.dicaF();
		$nome=nome_agrupamento($agrupamento_id);
		}
	elseif($patrocinador_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_patrocinador'].' '.ucfirst($config['patrocinador']), 'Filtrar pel'.$config['genero_patrocinador'].' '.$config['patrocinador'].' que se relacionam.').ucfirst($config['patrocinador']).':'.dicaF();
		$nome=nome_patrocinador($patrocinador_id);
		}
	elseif($template_id){
		$legenda_filtro=dica('Filtrar pelo Modelo', 'Filtrar pelo modelo que se relacionam.').'Modelo:'.dicaF();
		$nome=nome_template($template_id);
		}	
	elseif($painel_id){
		$legenda_filtro=dica('Filtrar pelo Painel', 'Filtrar pelo painel de indicador relacionado.').'Painel:'.dicaF();
		$nome=nome_painel($painel_id);
		}		
	elseif($painel_odometro_id){
		$legenda_filtro=dica('Filtrar pelo Od?metro', 'Filtrar pelo od?metro de indicador relacionado.').'Od?metro:'.dicaF();
		$nome=nome_painel_odometro($painel_odometro_id);
		}		
	elseif($painel_composicao_id){
		$legenda_filtro=dica('Filtrar pela Composi??o de Pain?is', 'Filtrar pela composi??o de pain?is relacionada.').'Composi??o de Pain?is:'.dicaF();
		$nome=nome_painel_composicao($painel_composicao_id);
		}	
	elseif($tr_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Filtrar pel'.$config['genero_tr'].' '.$config['tr'].' relacionad'.$config['genero_tr'].'.').ucfirst($config['tr']).':'.dicaF();
		$nome=nome_tr($tr_id);
		}	
	elseif($me_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_me'].' '.ucfirst($config['me']), 'Filtrar pel'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).':'.dicaF();
		$nome=nome_me($me_id);
		}	
	elseif($plano_acao_item_id){
		$legenda_filtro=dica('Filtrar pelo Item d'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Filtrar pelo item d'.$config['genero_acao'].' '.$config['acao'].' relacionado.').'Item d'.$config['genero_acao'].' '.ucfirst($config['acao']).':'.dicaF();
		$nome=nome_acao_item($plano_acao_item_id);
		}	
	elseif($beneficio_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_beneficio'].' '.ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Filtrar pel'.$config['genero_beneficio'].' '.$config['beneficio'].' d'.$config['genero_programa'].' '.$config['programa'].' relacionad'.$config['genero_beneficio'].'.').ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.ucfirst($config['programa']).':'.dicaF();
		$nome=nome_beneficio($beneficio_id);
		}	
	elseif($painel_slideshow_id){
		$legenda_filtro=dica('Filtrar pelo Slideshow de Composi??es', 'Filtrar pelo slideshow de composi??es relacionado.').'Slideshow de Composi??es:'.dicaF();
		$nome=nome_painel_slideshow($painel_slideshow_id);
		}	
	elseif($projeto_viabilidade_id){
		$legenda_filtro=dica('Filtrar pelo Estudo de Viabilidade', 'Filtrar pelo estudo de viabilidade relacionado.').'Estudo de Viabilidade:'.dicaF();
		$nome=nome_viabilidade($projeto_viabilidade_id);
		}	
	elseif($projeto_abertura_id){
		$legenda_filtro=dica('Filtrar pelo Termo de Abertura', 'Filtrar pelo termo de abertura relacionado.').'Termo de Abertura:'.dicaF();
		$nome=nome_termo_abertura($projeto_abertura_id);
		}	
	elseif($pg_id){
		$legenda_filtro=dica('Filtrar pelo Planejamento Estrat?gico', 'Filtrar pelo planejamento estrat?gico relacionado.').'Planejamento Estrat?gico:'.dicaF();
		$nome=nome_plano_gestao($pg_id);
		}	
	elseif($ssti_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_ssti'].' '.ucfirst($config['ssti']), 'Filtrar pel'.$config['genero_ssti'].' '.$config['ssti'].' relacionad'.$config['genero_ssti'].'.').ucfirst($config['ssti']).':'.dicaF();
		$nome=nome_ssti($ssti_id);
		}		
	elseif($laudo_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_laudo'].' '.ucfirst($config['laudo']), 'Filtrar pel'.$config['genero_laudo'].' '.$config['laudo'].' relacionad'.$config['genero_laudo'].'.').ucfirst($config['laudo']).':'.dicaF();
		$nome=nome_laudo($laudo_id);
		}	
	elseif($trelo_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_trelo'].' '.ucfirst($config['trelo']), 'Filtrar pel'.$config['genero_trelo'].' '.$config['trelo'].' relacionad'.$config['genero_trelo'].'.').ucfirst($config['trelo']).':'.dicaF();
		$nome=nome_trelo($trelo_id);
		}	
	elseif($trelo_cartao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_trelo_cartao'].' '.ucfirst($config['trelo_cartao']), 'Filtrar pel'.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' relacionad'.$config['genero_trelo_cartao'].'.').ucfirst($config['trelo_cartao']).':'.dicaF();
		$nome=nome_trelo_cartao($trelo_cartao_id);
		}		
	elseif($pdcl_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pdcl'].' '.ucfirst($config['pdcl']), 'Filtrar pel'.$config['genero_pdcl'].' '.$config['pdcl'].' relacionad'.$config['genero_pdcl'].'.').ucfirst($config['pdcl']).':'.dicaF();
		$nome=nome_pdcl($pdcl_id);
		}		
	elseif($pdcl_item_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pdcl_item'].' '.ucfirst($config['pdcl_item']), 'Filtrar pel'.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' relacionad'.$config['genero_pdcl_item'].'.').ucfirst($config['pdcl_item']).':'.dicaF();
		$nome=nome_pdcl_item($pdcl_item_id);
		}	
	elseif($os_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_os'].' '.ucfirst($config['os']), 'Filtrar pel'.$config['genero_os'].' '.$config['os'].' relacionad'.$config['genero_os'].'.').ucfirst($config['os']).':'.dicaF();
		$nome=nome_os($os_id);
		}								
	else{
		$nome='';
		$legenda_filtro=dica('Filtrar', 'Selecione um campo para filtrar.').'Filtro:'.dicaF();
		}

	$popFiltro='<tr><td align="right" style="white-space: nowrap">'.dica('Relacionado','A qual parte do sistema os documentos est?o relacionados.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:250px;" class="texto" onchange="popRelacao(this.value)"').'</td></tr>';
	$icone_limpar='<td><a href="javascript:void(0);" onclick="limpar_tudo(); env.submit();">'.imagem('icones/limpar_p.gif','Cancelar Filtro', 'Clique neste ?cone '.imagem('icones/limpar_p.gif').' para cancelar o filtro aplicado.').'</a></td>';
	$filtros=($nome ? '<tr><td align="right" style="white-space: nowrap">'.$legenda_filtro.'</td><td><input type="text" id="nome" name="nome" value="'.$nome.'" style="width:250px;" class="texto" READONLY /></td>'.$icone_limpar.'</tr>' : '');
	
	$botoesTitulo = new CBlocoTitulo('Documentos', 'documento.png', $m, $m.'.'.$a);
	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
	$saida.=dica('Filtros e A??es','Clique nesta barra para esconder/mostrar os filtros e as a??es permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/documento_p.png').'&nbsp;Filtros e A??es</a></div>'.dicaF();
	$saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
	$saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';
	
	$icone_pasta='<tr><td align="right" style="white-space: nowrap"><a href="javascript: void(0);" onclick ="url_passar(0, \'m=email&a=editar_pastas\');">'.imagem('pasta_p.png', 'Pastas','Clique neste ?cone '.imagem('pasta_p.png').' para editar as pastas particulares.').'</a></td></tr>';
	
	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$popFiltro.$filtros.'</table></td><td><table cellspacing=0 cellpadding=0>'.$icone_pasta.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	}


echo estiloTopoCaixa(); 



$tipos_tempo=array('data_criacao'=>'data de cria??o', 'data_aprovado'=>'data de aprova??o', 'data_protocolo'=>'data do protocolo', 'data_assinatura'=>'data da assinatura'); 
$estado_documentos=array(''=>'', 'elaboracao'=>'Em elabora??o', 'aprovado'=>'Aprovado', 'protocolado'=>'Protocolado', 'assinado'=>'Assinado','nao_assinado'=>'N?o assinado', 'nao_protocolado'=>'Aguardando protocolo'); 
$acoes_documentos=array(''=>'', 'encaminhado'=>'Encaminhado', 'envio_aprovacao'=>'Enviado para aprova??o', 'envio_despachado'=>'Despachado', 'arquivado'=>'Arquivado','ciencia_registrada'=>'Ci?ncia j? registrada', 'ciencia_nao_registrada'=>'Ci?ncia n?o registrada', 'gravado_midia'=>'Gravado em m?dia externa'); 

$sql->adTabela('modelos_tipo');
$sql->esqUnir('modelo_cia', 'modelo_cia', 'modelo_cia_tipo=modelo_tipo_id');
$sql->adCampo('modelo_tipo_id, modelo_tipo_nome');
$sql->adOnde('organizacao='.(int)$config['militar']);
$sql->adOnde('modelo_cia_cia='.(int)$Aplic->usuario_cia);
$modelos = $sql->listaVetorChave('modelo_tipo_id', 'modelo_tipo_nome');
$modelos = array('0'=>'')+$modelos;
$sql->limpar();

echo '<table class="std2" width="100%" align="center" cellpadding=0 cellspacing=0 >';
echo '<tr><td align="left" colspan="20" width="100%" id="barra" style="background-color: #e6e6e6">';
require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
$km = new CoolMenu("km");
$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
$km->styleFolder="default";

if ($Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'protocolar_modelo') && !($anexar_documento || $referenciar_documento)) $km->Add("root","protocolar","Protocolar", "javascript: void(0);' onclick='limpar_pesquisa(); env.a.value=\"modelo_protocolar\"; env.item_menu.value=\"a_protocolar\"; env.pagina.value=1; env.submit();");

//caixa entrada
$sql->adTabela('modelo_usuario');
$sql->adCampo('count(DISTINCT modelo_id)');
$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
$sql->adOnde('status<2');
$total_entrada = $sql->Resultado();
$sql->limpar();

$sql->adTabela('modelo_usuario');
$sql->adCampo('count(DISTINCT modelo_id)');
$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
$sql->adOnde('status=0');
$nao_lidas = $sql->Resultado();
$sql->limpar();
$km->Add("root","entrada","Entrada".($nao_lidas ? ' ('.$nao_lidas.'/'.$total_entrada.')' : ($total_entrada ? ' ('.$total_entrada.')' : '')), "javascript: void(0);' onclick='env.item_menu.value=\"entrada\"; env.pesquisar.value=0; env.pagina.value=1; env.submit();");

//caixa pendentes
$sql->adTabela('modelo_usuario');
$sql->adCampo('count(DISTINCT modelo_id)');
$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
$sql->adOnde('status=3');
$pendentes = $sql->Resultado();
$sql->limpar();
$km->Add("root","pendentes","Pendentes".($pendentes ? ' ('.$pendentes.')': ''), "javascript: void(0);' onclick='limpar_pesquisa(); env.caixa_elaborar.value=0; env.modelo_tipo_id.value=0; env.item_menu.value=\"pendente\"; env.pesquisar.value=0; env.pagina.value=1; env.submit();");
$km->Add("root","arquivados","Arquivados", "javascript: void(0);' onclick='env.item_menu.value=\"arquivado\"; env.pesquisar.value=0; env.pagina.value=1; env.submit();");
$km->Add("root","enviados","Enviados", "javascript: void(0);' onclick='env.item_menu.value=\"enviado\"; env.pesquisar.value=0; env.pagina.value=1; env.submit();");

if (!$anexar_documento && !$referenciar_documento) {
	if ($Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'criar_modelo')) $km->Add("root","criar_documentos","Criar Documento");
	$km->Add("root","pesquisa","Pesquisar", "javascript: void(0);' onclick='limpar_pesquisa(); mostrarEsconder(); return false;");
	}

$sql->adTabela('modelos');
$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo=modelo_tipo_id');
$sql->esqUnir('modelos_dados', 'modelos_dados', 'modelo_dados_modelo=modelos.modelo_id');
$sql->adCampo('count(DISTINCT modelos.modelo_id)');
$sql->adOnde('modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
$sql->adOnde('modelo_versao_aprovada IS NULL');
$mod = $sql->Resultado();
$sql->limpar();
$km->Add("root","elaboracao","Elabora??o".($mod ? ' ('.$mod.')' : ''), "javascript: void(0);' onclick='limpar_pesquisa(); env.modelo_tipo_id.value=0; env.item_menu.value=\"elaboracao\"; env.pesquisar.value=0; env.caixa_elaborar.value=1; env.pagina.value=1; env.submit();");

//? protocolar
$sql->adTabela('modelos');
$sql->esqUnir('modelos_dados', 'modelos_dados', 'modelo_dados_modelo=modelos.modelo_id');
$sql->adCampo('count(DISTINCT modelos.modelo_id) as quantidade');
$sql->adOnde('modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
$sql->adOnde('modelo_versao_aprovada > 0');
$sql->adOnde('modelo_protocolo IS NULL OR modelo_protocolo=\'\'');
$mod = $sql->Resultado();

$sql->limpar();
$km->Add("root","a_protocolar","? Protocolar".($mod ? ' ('.$mod.')' : ''), "javascript: void(0);' onclick='limpar_pesquisa(); env.item_menu.value=\"a_protocolar\"; env.modelo_tipo_id.value=0; env.pesquisar.value=0; env.caixa_para_protocolar.value=1; env.pagina.value=1; env.submit();");
$km->Add("root","protocolados","Protocolados", "javascript: void(0);' onclick='limpar_pesquisa(); env.item_menu.value=\"protocolado\"; env.modelo_tipo_id.value=0; env.pesquisar.value=0; env.caixa_protocolado.value=1; env.pagina.value=1; env.submit();");
$km->Add("root","assinados","Assinados", "javascript: void(0);' onclick='limpar_pesquisa(); env.item_menu.value=\"assinado\"; env.modelo_tipo_id.value=0; env.pesquisar.value=0; env.caixa_assinado.value=1; env.pagina.value=1; env.submit();");

$sql->adTabela('modelos_tipo');
$sql->esqUnir('modelo_cia', 'modelo_cia', 'modelo_cia_tipo=modelo_tipo_id');
$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, descricao, imagem');
$sql->adOnde('organizacao='.(int)$config['militar']);
$sql->adOnde('modelo_cia_cia='.(int)$Aplic->usuario_cia);
$resultados = $sql->Lista();
$sql->limpar();

$qnt_entrada=0;	
$qnt_elaboracao=0;
$qnt_protocolar=0;
$qnt_pendente=0;
foreach((array)$resultados as $rs)	{
	//criar
	if (!$anexar_documento && !$referenciar_documento && $Aplic->checarModulo('email', 'acesso', $Aplic->usuario_id, 'criar_modelo')) $km->Add("criar_documentos","novodocumento",dica($rs['modelo_tipo_nome'], $rs['descricao']).$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;'.dicaf(),"javascript: void(0);' onclick='javascript:limpar_pesquisa(); env.retornar.value=\"modelo_pesquisar\"; env.m.value=\"email\"; env.a.value=\"modelo_editar\"; env.editar.value=1; env.novo.value=1; env.modelo_id.value=0; env.modelo_tipo_id.value=".$rs['modelo_tipo_id']."; env.submit();", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
	
	//elaboracao
	$sql->adTabela('modelos');
	$sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo=modelo_tipo_id');
	$sql->esqUnir('modelos_dados', 'modelos_dados', 'modelo_dados_modelo=modelos.modelo_id');
	$sql->adCampo('count(DISTINCT modelos.modelo_id) as quantidade');
	$sql->adOnde('modelos_dados_criador'.($coletivo ? ' IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$sql->adOnde('modelo_versao_aprovada IS NULL');
	$sql->adOnde('modelo_tipo='.(int)$rs['modelo_tipo_id']);
	$mod = $sql->resultado();

	$sql->limpar();
	if ($mod) {
		$km->Add("elaboracao","elaboraca_doc",$rs['modelo_tipo_nome'].'('.$mod.')',"javascript: void(0);' onclick='javascript:limpar_pesquisa(); env.item_menu.value=\"elaboracao\"; env.pesquisar.value=0; env.caixa_elaborar.value=1; env.modelo_tipo_id.value=".$rs['modelo_tipo_id']."; env.submit();");
		$qnt_elaboracao++;
		}
	}
if (!$resultados || !count($resultados)) $km->Add('criar_documentos','ciar_doc', 'N?o h? modelos dispon?veis');
else $km->Add("criar_documentos",'vazio8', '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
	
if (!$qnt_elaboracao) $km->Add('elaboracao','elaboracao_doc', 'N?o h? documentos em elabora??o');


echo $km->Render();
echo '</td></tr>';
	
echo '<tr id="pesquisa_completa" '.(!$anexar_documento && !$referenciar_documento && !$pesquisar ? 'style="display:none;"' : '').'><td><table width="100%">';
echo '<tr><td colspan=20>&nbsp;</td></tr>';
echo '<tr><td colspan=20>&nbsp;</td></tr>';
echo '<tr><td colspan=20>&nbsp;</td></tr>';
echo '<tr><td width="50%" valign="top"><table width="100%" cellpadding=0 cellspacing=0>';
echo '<tr><td align="right" width="130">'.dica('Texto ? Pesquisar','Escreva a palavra chave a ser pesquisa nos documentos.').'Texto:'.dicaF().'</td><td><input type="text" class="texto" name="assunto" id="assunto" size="60" value="'.$assunto.'"></td></tr>';
echo '<tr><td align="center" colspan="2"></td></tr>';
echo'<tr><td align="right" style="white-space: nowrap">'.dica('Tipo de Documento', 'Escolha qual o modelo de documento utilizado.').'Tipo de documento:'.dicaF().'</td><td style="white-space: nowrap">'.selecionaVetor($modelos, 'tipo_documento', 'size="1" class="texto"', $tipo_documento).'</td></tr>';
echo'<tr><td align="right" style="white-space: nowrap">'.dica('Estado de Documento', 'Escolha em qual estado o documento se encontra.').'Estado do documento:'.dicaF().'</td><td style="white-space: nowrap">'.selecionaVetor($estado_documentos, 'estado_documento', 'size="1" class="texto"', $estado_documento).'</td></tr>';
echo'<tr><td align="right" style="white-space: nowrap">'.dica('A??es Sofridas', 'Escolha qual a??o sofrida pelo documento.').'A??o sofrida:'.dicaF().'</td><td style="white-space: nowrap">'.selecionaVetor($acoes_documentos, 'acao_documento', 'size="1" class="texto"', $acao_documento).'</td></tr>';
echo '<tr><td align="right">'.dica('Nr Documento','Escolha o n?mero do documento que deseja encontrar').'Nr Documento:'.dicaF().'</td><td><input type="text" class="texto" name="numero" id="numero" size="10" value="'.$numero.'"></td></tr>';
echo '<tr><td align="right" style="white-space: nowrap">'.dica('Data de In?cio', 'Digite ou escolha no calend?rio a data de in?cio da pesquisa.').'De:'.dicaF().'</td><td style="white-space: nowrap"><input type="hidden" name="pesquisa_inicio" id="pesquisa_inicio" value="'.$pesquisa_inicio.'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'pesquisa_inicio\');" value="'.($pesquisa_inicio ? retorna_data($pesquisa_inicio, false): '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ?cone '.imagem('icones/calendario.gif').' para abrir um calend?rio onde poder? selecionar a data inicial da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="Agenda"" border=0 /></a>'.dicaF().dica('Data de T?rmino', 'Digite ou escolha no calend?rio a data de t?rmino da pesquisa.').'&nbsp;&nbsp;a&nbsp;&nbsp;'.dicaF().'<input type="hidden" name="pesquisa_fim" id="pesquisa_fim" value="'.$pesquisa_fim.'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\', \'pesquisa_fim\');" value="'.($pesquisa_fim ? retorna_data($pesquisa_fim, false): '').'" class="texto" />'.dica('Data de T?rmino', 'Clique neste ?cone '.imagem('icones/calendario.gif').'  para abrir um calend?rio onde poder? selecionar a data limite da pesquisa.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="Agenda"" border=0 /></a>'.dicaF().'&nbsp;&nbsp;&nbsp;&nbsp;'.selecionaVetor($tipos_tempo, 'tipo_tempo', 'size="1" class="texto"', $tipo_tempo).'</td></tr>';
echo '</table></td><td width="50%" valign="top"><table width="100%" cellpadding=0 cellspacing=0>';
echo '<tr><td align="right">'.dica('Protocolo','Escolha o n?mero de protocolo do documento que deseja encontrar').'Protocolo:'.dicaF().'</td><td><input type="text" class="texto" name="modelo_protocolo" id="modelo_protocolo" size="40" value="'.$modelo_protocolo.'"></td></tr>';
echo '<tr><td align="right">'.dica('Criador','Escolha os documentos que tenham sido criados pel'.$config['genero_usuario'].' '.$config['usuario'].' selecionado.').'Criador:'.dicaF().'</td><td><input type="hidden" id="criador" name="criador" value="'.$criador.'" /><input type="text" id="nome_criador" name="nome_criador" value="'.nome_om($criador,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCriador();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';	
echo '<tr><td align="right">'.dica('Aprovou','Escolha os documentos que tenham sido aprovados pel'.$config['genero_usuario'].' '.$config['usuario'].' selecionado.').'Aprovou:'.dicaF().'</td><td><input type="hidden" id="aprovou" name="aprovou" value="'.$aprovou.'" /><input type="text" id="nome_aprovou" name="nome_aprovou" value="'.nome_om($aprovou,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popAprovou();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ?cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';	
if ($Aplic->usuario_super_admin) echo '<tr><td align="right">Todos '.$config['genero_usuario'].'s '.$config['usuarios'].':</td><td><input type="checkbox"" class="texto" name="pesquisar_tudo" id="pesquisar_tudo"  value="1" '.($pesquisar_tudo ? 'checked' : '').'></td></tr>';
else echo '<input type="hidden"" name="pesquisar_tudo" id="pesquisar_tudo"  value="0">';

echo '</table></td></tr>';
echo '<tr><td colspan=2 align="center"><table width="100%" cellpadding=0 cellspacing=0><tr><td width="130">&nbsp;</td><td>'.botao('pesquisar documentos', 'Pesquisar Documentos','Clique neste bot?o para efetuar a pesquisa nos documentos criados no '.$config['gpweb'].'.','','env.item_menu.value=\'pesquisar\'; env.pesquisar.value=1; env.submit();').'</td><td>'.($retornar ? botao('retornar', 'Retornar','Ao se pressionar este bot?o ir? retornar a tela anterior.','','env.a.value=\''.$retornar.'\'; env.submit();') : '').'</td></tr></table></td></tr>';
echo '</table></td></tr>';


$cor_prioridade=getSisValor('cor_precedencia');
$precedencia=getSisValor('precedencia','','','sisvalor_valor_id ASC');
$class_sigilosa=getSisValor('class_sigilosa','','','sisvalor_valor_id ASC');
$tipos_status=getSisValor('status');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

//pega status do cm (ver msg de outros)
if (isset($_REQUEST['status_cabecalho'])) $status=getParam($_REQUEST, 'status_cabecalho', null);
$numero_status=getParam($_REQUEST, 'numero_status', 0);


//checar se tem pasta particular
$tem_pasta=0;

//entrada
$sql->adTabela('pasta');
$sql->adCampo('count(DISTINCT pasta_id) as soma');
$sql->adOnde('usuario_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
$tem_pasta = $sql->Resultado();
$sql->limpar();



//pesquisa

$sel_usuario_de=getParam($_REQUEST, 'sel_usuario_de', 0);
$sel_usuario_para=getParam($_REQUEST, 'sel_usuario_para', 0);
$assunto=getParam($_REQUEST, 'assunto', '');
$ordem_inicio=getParam($_REQUEST, 'pesquisa_inicio', '');
$ordem_fim=getParam($_REQUEST, 'pesquisa_fim', '');
//para msg vindas do exibir msg para arquivar em pasta
$arquivar=getParam($_REQUEST, 'arquivar', 0);


$lista_msg = 1;

	
if ($mover){
	
	
	$sql->adTabela('modelo_usuario');
	$sql->adAtualizar('pasta_id', $pasta);
	$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$sql->adOnde('modelo_usuario_id IN ('.$mover.')');
	$sql->exec();
	$sql->limpar();
	//Verificar msgs novas
	$sql->adTabela('modelo_usuario');
	$sql->adCampo('datahora_leitura, de_id, modelo_usuario_id, aviso_leitura');
	$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$sql->adOnde('modelo_usuario_id IN ('.$mover.')');
	$sql->adOnde('status<2');
	$lista=$sql->lista();
	$sql->limpar();
	foreach($lista as $rs_leitura){
		if (!$rs_leitura['datahora_leitura']) {
			//msg n?o lida na caixa de entrada
			$data = date('Y-m-d H:i:s');
			if ($rs_leitura['aviso_leitura']==1) aviso_leitura_moldelo($rs_leitura['de_id'], $rs_leitura['modelo_usuario_id'], $data);
			$sql->adTabela('modelo_usuario');
			$sql->adAtualizar('status', '4');
			$sql->adAtualizar('datahora_leitura', $data);
			$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
			$sql->adOnde('modelo_usuario_id = '.$rs_leitura['modelo_usuario_id']);
			$sql->adOnde('datahora_leitura IS NULL');
			$sql->exec();
			$sql->limpar();
			}
		else {
			//msg j? lida ainda na caixa de entrada
			$sql->adTabela('modelo_usuario');
			$sql->adAtualizar('status', '4');
			$sql->adOnde('para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
			$sql->adOnde('modelo_usuario_id = '.$rs_leitura['modelo_usuario_id']);
			$sql->exec();
			$sql->limpar();
			}	
		$status=4;
		}
	echo '<script>url_passar(0, "m=email&a=modelo_pesquisar&pesquisar=0&'.(!$Aplic->getPref('msg_entrada') ? '&status='.$status.'&pasta='.$pasta : '&status=1').'");</script>';	
	}

if ($item_menu=='elaboracao') $titulo = 'Documentos em elabora??o';
elseif($item_menu=='a_protocolar') $titulo = 'Documentos aguardando protocolo';
elseif($item_menu=='protocolado') $titulo = 'Documentos protocolados';
elseif($item_menu=='assinado') $titulo = 'Documentos assinados';
else $titulo = 'Pesquisa de documentos';

if($pesquisar){
	$sql->adTabela('modelos');
  $sql->esqUnir('modelo_usuario','modelo_usuario','modelo_usuario.modelo_id = modelos.modelo_id');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('modelos_dados','modelos_dados','modelo_dados_modelo = modelos.modelo_id');
  $sql->esqUnir('usuarios','usuarios', 'modelo_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
  $sql->adCampo('modelos.modelo_id, cia_nome, dept_nome, modelos.class_sigilosa, modelo_criador_original, modelo_dados_data, modelo_tipo_nome, modelo_usuario_id, modelo_usuario.datahora, modelo_assunto, modelo_usuario.datahora, modelo_usuario.cor, modelo_usuario.nota, contatos.contato_funcao,modelo_assunto, modelo_usuario.status, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	
	if ($caixa_entrada){
		$sql->adOnde('modelo_usuario.status<3');
		if (!$pesquisar_modelo_irrestrito) $sql->adOnde('modelo_usuario.para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
		if ($pesquisar_modelo_irrestrito && !$pesquisar_cia_irrestrita) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		}
	elseif ($caixa_pendente){
		$sql->adOnde('modelo_usuario.status=3');
		if (!$pesquisar_modelo_irrestrito) $sql->adOnde('modelo_usuario.para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
		if ($pesquisar_modelo_irrestrito && !$pesquisar_cia_irrestrita) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		}
	elseif ($caixa_arquivado){
		$sql->adOnde('modelo_usuario.status=4');
		if (!$pesquisar_modelo_irrestrito) $sql->adOnde('modelo_usuario.para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
		if ($pesquisar_modelo_irrestrito && !$pesquisar_cia_irrestrita) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		}
	elseif ($caixa_para_protocolar) {
		$sql->adOnde('modelo_versao_aprovada IS NOT NULL AND modelo_data_protocolo IS NULL');
		if (!$pesquisar_modelo_irrestrito) $sql->adOnde('modelos_dados.modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
		if ($pesquisar_modelo_irrestrito && !$pesquisar_cia_irrestrita) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		}
	elseif ($caixa_protocolado) {
		$sql->adOnde('modelo_data_protocolo IS NOT NULL');
		if (!$pesquisar_modelo_irrestrito) $sql->adOnde('modelos_dados.modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
		if ($pesquisar_modelo_irrestrito && !$pesquisar_cia_irrestrita) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		}
	elseif ($caixa_elaborar) {
		$sql->adOnde('modelo_versao_aprovada IS NULL');
		if (!$pesquisar_modelo_irrestrito) $sql->adOnde('modelos_dados.modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
		if ($pesquisar_modelo_irrestrito && !$pesquisar_cia_irrestrita) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		}
	elseif ($caixa_assinado) {
		$sql->adOnde('modelo_autoridade_assinou IS NOT NULL');
		if (!$pesquisar_modelo_irrestrito) $sql->adOnde('modelos_dados.modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
		if ($pesquisar_modelo_irrestrito && !$pesquisar_cia_irrestrita) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		}
	elseif (!$pesquisar_tudo){
		if (!$pesquisar_modelo_irrestrito) $sql->adOnde('modelo_usuario.para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id).' OR modelo_usuario.de_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id).' OR modelos_dados.modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
		if ($pesquisar_modelo_irrestrito && !$pesquisar_cia_irrestrita) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		}	
	if ($tipo_documento) $sql->adOnde('modelo_tipo='.(int)$tipo_documento);
	if ($modelo_protocolo) $sql->adOnde('modelo_protocolo LIKE \'%'.$modelo_protocolo.'%\'');
	if ($aprovou) $sql->adOnde('modelo_autoridade_aprovou='.(int)$aprovou);
	if ($numero) $sql->adOnde('modelos.modelo_id LIKE \'%'.$numero.'%\'');
	if ($estado_documento) {
		if ($estado_documento=='elaboracao') $sql->adOnde('modelo_versao_aprovada IS NULL');
		elseif ($estado_documento=='aprovado') $sql->adOnde('modelo_versao_aprovada>0');
		elseif ($estado_documento=='protocolado') $sql->adOnde('modelo_data_protocolo IS NOT NULL');
		elseif ($estado_documento=='nao_protocolado') $sql->adOnde('modelo_versao_aprovada IS NOT NULL AND modelo_data_protocolo IS NULL');
		elseif ($estado_documento=='assinado') $sql->adOnde('modelo_autoridade_assinou>0');
		elseif ($estado_documento=='nao_assinado') $sql->adOnde('modelo_versao_aprovada>0 AND modelo_autoridade_assinou IS NULL');
		}
		
	/**********************************************
	
	
	bizzaro . Faz referenfia a anexos.modelo  e nem tem esta tabela no left join
	
	
	
	***********************************************/	
		
		
	if ($acao_documento) {
		if ($estado_documento=='encaminhado') {
			$sql->adOnde('modelo_versao_aprovada IS NULL');
			$sql->adOnde('anexos.anexos>0');
			$sql->adOnde('aprovar_modelo IS NULL');
			}
		elseif ($estado_documento=='envio_aprovacao') {
			$sql->adOnde('modelo_versao_aprovada IS NULL');
			$sql->adOnde('anexos.modelo>0');
			$sql->adOnde('aprovar_modelo=1');
			}	
		elseif ($estado_documento=='envio_despachado') {
			$sql->adOnde('anexos.modelo>0');
			$sql->adOnde('anotacao.tipo=1');
			}	
		elseif ($estado_documento=='arquivado') {
			$sql->adOnde('anexos.modelo>0');
			$sql->adOnde('modelo_usuario.status=4');
			}	
		elseif ($estado_documento=='ciencia_registrada') {
			$sql->adOnde('ciencia_modelo=1');
			}	
		elseif ($estado_documento=='gravado_midia') {
			$sql->adOnde('arquivo_externo !=\'\'');
			}		
		}
	if ($assunto) $sql->adOnde('modelo_assunto LIKE \'%'.$assunto.'%\' OR modelos_dados_campos LIKE \'%'.$assunto.'%\'');
	
	if ($pesquisa_inicio){
		if ($tipo_tempo=='data_criacao')  $sql->adOnde('modelo_data >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
		elseif ($tipo_tempo=='data_aprovado')  $sql->adOnde('modelo_data_aprovado >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
		elseif ($tipo_tempo=='data_protocolo')  $sql->adOnde('modelo_data_protocolo >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
		elseif ($tipo_tempo=='data_assinatura')  $sql->adOnde('modelo_data_assinado >= \''.$data_inicio->format('%Y-%m-%d %H:%M:%S').'\'');
		}
	if ($pesquisa_fim){
		if ($tipo_tempo=='data_criacao')  $sql->adOnde('modelo_data <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'');
		elseif ($tipo_tempo=='data_aprovado')  $sql->adOnde('modelo_data_aprovado <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'');
		elseif ($tipo_tempo=='data_protocolo')  $sql->adOnde('modelo_data_protocolo <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'');
		elseif ($tipo_tempo=='data_assinatura')  $sql->adOnde('modelo_data_assinado <= \''.$data_fim->format('%Y-%m-%d %H:%M:%S').'\'');
		}
	if ($criador) $sql->adOnde('modelo_criador_original='.(int)$criador);	
	if (!$pesquisar_tudo && ($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado')) $sql->adOnde('modelo_usuario.de_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id).' OR modelo_usuario.para_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	
	$sql->adGrupo('modelos.modelo_id');
	}
elseif ($item_menu=='entrada'){
	$titulo = "Caixa de Entrada de Documentos";
	if (empty($numero_status)) $numero_status = 1;
	$sql->adTabela('modelos');
  $sql->esqUnir('modelo_usuario','modelo_usuario','modelo_usuario.modelo_id = modelos.modelo_id');
  $sql->esqUnir('modelos_dados','modelos_dados','modelos_dados.modelo_dados_modelo = modelos.modelo_id');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('usuarios','usuarios', 'modelo_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
	$sql->esqUnir('modelo_anotacao', 'modelo_anotacao', 'modelo_anotacao.modelo_anotacao_id = modelo_usuario.modelo_anotacao_id');
	$sql->adCampo('modelos.modelo_id, modelo_usuario.modelo_usuario_id, modelo_usuario.tipo, modelos.class_sigilosa, modelo_dados_data, modelo_tipo_nome, modelo_usuario.datahora, modelo_assunto, modelo_usuario.datahora, modelo_usuario.cor, modelo_usuario.nota, contatos.contato_funcao,modelo_assunto, modelo_usuario.status, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de');
	$sql->adCampo('modelo_anotacao.texto AS texto_nota');
  $sql->adCampo('cia_nome, dept_nome');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('para_id = '.$Aplic->usuario_id);
	$sql->adOnde('modelo_usuario.status <= 2');
	$sql->adGrupo('modelos.modelo_id');
  }
//pendentes
elseif ($item_menu=='pendente'){
	$titulo = "Documentos Pendentes";
	$sql->adTabela('modelos');
  $sql->esqUnir('modelo_usuario','modelo_usuario','modelo_usuario.modelo_id = modelos.modelo_id');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('usuarios','usuarios', 'modelo_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
	$sql->esqUnir('modelo_anotacao', 'modelo_anotacao', 'modelo_anotacao.modelo_anotacao_id = modelo_usuario.modelo_anotacao_id');
  $sql->adCampo('modelos.modelo_id, modelo_usuario.tipo, modelos.class_sigilosa, modelo_tipo_nome, modelo_usuario.modelo_usuario_id, modelo_usuario.datahora, modelo_assunto, modelo_usuario.datahora, modelo_usuario.cor, modelo_usuario.nota, contatos.contato_funcao,modelo_assunto, modelo_usuario.status, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de');
	$sql->adCampo('modelo_anotacao.texto AS texto_nota');
  $sql->adCampo('cia_nome, dept_nome');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('modelo_usuario.para_id= '.$Aplic->usuario_id);
	$sql->adOnde('modelo_usuario.status = 3');
  if ($pasta > 0) $sql->adOnde('modelo_usuario.pasta_id ='.(int)$pasta);
  else if ($pasta == 0) $sql->adOnde('modelo_usuario.pasta_id < 1 OR modelo_usuario.pasta_id IS NULL'); 
	$sql->adGrupo('modelos.modelo_id');
	}

//arquivadas
elseif ($item_menu=='arquivado') {
	$titulo = 'Documentos Arquivadas';
	$sql->adTabela('modelos');
  $sql->esqUnir('modelo_usuario','modelo_usuario','modelo_usuario.modelo_id = modelos.modelo_id');
	$sql->adUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->adUnir('usuarios','usuarios', 'modelo_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
	$sql->esqUnir('modelo_anotacao', 'modelo_anotacao', 'modelo_anotacao.modelo_anotacao_id = modelo_usuario.modelo_anotacao_id');
  $sql->adCampo('modelos.modelo_id, modelo_usuario.tipo, modelos.class_sigilosa, modelo_tipo_nome, modelo_usuario.modelo_usuario_id, modelo_usuario.datahora, modelo_assunto, modelo_usuario.datahora, modelo_usuario.cor, modelo_usuario.nota, contatos.contato_funcao,modelo_assunto, modelo_usuario.status, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de');
	$sql->adCampo('modelo_anotacao.texto AS texto_nota');
  $sql->adCampo('cia_nome, dept_nome');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('para_id= '.$Aplic->usuario_id);
	$sql->adOnde('modelo_usuario.status = 4');
  if ($pasta > 0) $sql->adOnde('modelo_usuario.pasta_id ='.(int)$pasta);
  else if ($pasta == 0) $sql->adOnde('modelo_usuario.pasta_id < 1 OR modelo_usuario.pasta_id IS NULL');
	$sql->adGrupo('modelos.modelo_id');
	}
	
//enviados
elseif ($item_menu=='enviado'){
	$titulo = 'Documentos Enviados';
	$sql->adTabela('modelos');
  $sql->esqUnir('modelo_usuario','modelo_usuario','modelo_usuario.modelo_id = modelos.modelo_id');
 	$sql->adUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->adUnir('usuarios','usuarios', 'modelo_usuario.de_id=usuarios.usuario_id');
  $sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
  $sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
	$sql->esqUnir('modelo_anotacao', 'modelo_anotacao', 'modelo_anotacao.modelo_anotacao_id = modelo_usuario.modelo_anotacao_id');
  $sql->adCampo('modelos.modelo_id, modelo_usuario.tipo, modelos.class_sigilosa, modelo_tipo_nome, modelo_usuario.modelo_usuario_id, modelo_usuario.datahora, modelo_assunto, modelo_usuario.datahora, modelo_usuario.cor, modelo_usuario.nota, contatos.contato_funcao,modelo_assunto, modelo_usuario.status, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de');
	$sql->adCampo('modelo_anotacao.texto AS texto_nota');
  $sql->adCampo('cia_nome, dept_nome');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('de_id = '.$Aplic->usuario_id);
  if ($pasta > 0) $sql->adOnde('modelo_usuario.pasta_id ='.(int)$pasta);
  else if ($pasta == 0) $sql->adOnde('modelo_usuario.pasta_id < 1 OR modelo_usuario.pasta_id IS NULL');
	$sql->adGrupo('modelos.modelo_id');
	}

//? protocolar
elseif ($item_menu=='a_protocolar'){
	$titulo = "Documentos ? Protocolar";
  $sql->adTabela('modelos');
  $sql->esqUnir('modelos_tipo','','modelos.modelo_tipo = modelos_tipo.modelo_tipo_id');
  $sql->esqUnir('modelos_dados', '', 'modelos_dados.modelo_dados_modelo = modelos.modelo_id');
  $sql->adCampo('modelos.modelo_id, MAX(modelo_dados_data), modelos.class_sigilosa, modelos.modelo_data_assinado, modelos.modelo_criador_original, modelos.modelo_assunto, modelos_tipo.modelo_tipo_nome, modelo_data_aprovado');
	$sql->adOnde('modelos_dados.modelos_dados_criador'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$sql->adOnde('modelos.modelo_versao_aprovada > 0');
	$sql->adOnde('modelos.modelo_protocolo IS NULL');
	if ($modelo_tipo_id) $sql->adOnde('modelos.modelo_tipo = '.$modelo_tipo_id);
	$sql->adGrupo('modelo_id');
	}

//protocolado
elseif ($item_menu=='protocolado'){
	$titulo = "Documentos Protocolados";
  $sql->adTabela('modelos');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('modelos_dados', 'modelos_dados', 'modelo_dados_modelo=modelos.modelo_id');
  $sql->adCampo('modelos.modelo_id, modelo_tipo_nome, modelo_assunto, modelo_assunto, modelo_data_protocolo, modelos.class_sigilosa');
	$sql->adOnde('modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$sql->adOnde('modelo_protocolo IS NOT NULL');
	if ($modelo_tipo_id) $sql->adOnde('modelo_tipo='.(int)$modelo_tipo_id);
	$sql->adGrupo('modelos.modelo_id');
	}

//assinado
elseif ($item_menu=='assinado'){
	$titulo = "Documentos Assinados";
  $sql->adTabela('modelos');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('modelos_dados', 'modelos_dados', 'modelo_dados_modelo=modelos.modelo_id');
  $sql->adCampo('modelos.modelo_id, modelo_tipo_nome, modelo_assunto, modelo_assunto, modelo_data_assinado, modelos.class_sigilosa');
	$sql->adOnde('modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$sql->adOnde('modelo_autoridade_assinou IS NOT NULL');
	if ($modelo_tipo_id) $sql->adOnde('modelo_tipo='.(int)$modelo_tipo_id);
	$sql->adGrupo('modelos.modelo_id');
	}

//elaboracao
elseif($item_menu=='elaboracao'){
	$titulo = "Documentos em Elabora??o";
  $sql->adTabela('modelos');
  $sql->esqUnir('modelos_tipo','modelos_tipo','modelo_tipo = modelo_tipo_id');
  $sql->esqUnir('modelos_dados', 'modelos_dados', 'modelo_dados_modelo=modelos.modelo_id');
	$sql->adCampo('modelos.modelo_id, MAX(modelos_dados.modelo_dados_data) AS modelo_dados_data, modelo_tipo_nome, modelo_assunto, modelo_assunto, modelo_data_assinado, modelos.class_sigilosa, modelo_criador_original');
	$sql->adOnde('modelos_dados_criador	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$sql->adOnde('modelo_versao_aprovada IS NULL');
	if ($modelo_tipo_id) $sql->adOnde('modelo_tipo='.(int)$modelo_tipo_id);
	$sql->adGrupo('modelos.modelo_id');
	}



$sql->esqUnir('modelo_gestao','modelo_gestao','modelo_gestao_modelo = modelos.modelo_id');
if ($tarefa_id) $sql->adOnde('modelo_gestao_tarefa IN ('.$tarefa_id.')');
elseif ($projeto_id){
	$sql->esqUnir('tarefas','tarefas2', 'tarefas2.tarefa_id=modelo_gestao_tarefa');
	$sql->adOnde('modelo_gestao_projeto IN ('.$projeto_id.') OR tarefas2.tarefa_projeto IN ('.$projeto_id.')');
	}
elseif ($pg_perspectiva_id) $sql->adOnde('modelo_gestao_perspectiva IN ('.$pg_perspectiva_id.')');
elseif ($tema_id) $sql->adOnde('modelo_gestao_tema IN ('.$tema_id.')');
elseif ($objetivo_id) $sql->adOnde('modelo_gestao_objetivo IN ('.$objetivo_id.')');
elseif ($fator_id) $sql->adOnde('modelo_gestao_fator IN ('.$fator_id.')');
elseif ($pg_estrategia_id) $sql->adOnde('modelo_gestao_estrategia IN ('.$pg_estrategia_id.')');
elseif ($pg_meta_id) $sql->adOnde('modelo_gestao_meta IN ('.$pg_meta_id.')');
elseif ($pratica_id) $sql->adOnde('modelo_gestao_pratica IN ('.$pratica_id.')');
elseif ($pratica_indicador_id) $sql->adOnde('modelo_gestao_indicador IN ('.$pratica_indicador_id.')');
elseif ($plano_acao_id) $sql->adOnde('modelo_gestao_acao IN ('.$plano_acao_id.')');
elseif ($canvas_id) $sql->adOnde('modelo_gestao_canvas IN ('.$canvas_id.')');
elseif ($risco_id) $sql->adOnde('modelo_gestao_risco IN ('.$risco_id.')');
elseif ($risco_resposta_id) $sql->adOnde('modelo_gestao_risco_resposta IN ('.$risco_resposta_id.')');
elseif ($calendario_id) $sql->adOnde('modelo_gestao_calendario IN ('.$calendario_id.')');
elseif ($monitoramento_id) $sql->adOnde('modelo_gestao_monitoramento IN ('.$monitoramento_id.')');
elseif ($ata_id) $sql->adOnde('modelo_gestao_ata IN ('.$ata_id.')');
elseif ($mswot_id) $sql->adOnde('modelo_gestao_mswot IN ('.$mswot_id.')');
elseif ($swot_id) $sql->adOnde('modelo_gestao_swot IN ('.$swot_id.')');
elseif ($operativo_id) $sql->adOnde('modelo_gestao_operativo IN ('.$operativo_id.')');
elseif ($instrumento_id) $sql->adOnde('modelo_gestao_instrumento IN ('.$instrumento_id.')');
elseif ($recurso_id) $sql->adOnde('modelo_gestao_recurso IN ('.$recurso_id.')');
elseif ($problema_id) $sql->adOnde('modelo_gestao_problema IN ('.$problema_id.')');
elseif ($demanda_id) $sql->adOnde('modelo_gestao_demanda IN ('.$demanda_id.')');
elseif ($programa_id) $sql->adOnde('modelo_gestao_programa IN ('.$programa_id.')');
elseif ($licao_id) $sql->adOnde('modelo_gestao_licao IN ('.$licao_id.')');
elseif ($evento_id) $sql->adOnde('modelo_gestao_evento IN ('.$evento_id.')');
elseif ($link_id) $sql->adOnde('modelo_gestao_link IN ('.$link_id.')');
elseif ($avaliacao_id) $sql->adOnde('modelo_gestao_avaliacao IN ('.$avaliacao_id.')');
elseif ($tgn_id) $sql->adOnde('modelo_gestao_tgn IN ('.$tgn_id.')');
elseif ($brainstorm_id) $sql->adOnde('modelo_gestao_brainstorm IN ('.$brainstorm_id.')');
elseif ($gut_id) $sql->adOnde('modelo_gestao_gut IN ('.$gut_id.')');
elseif ($causa_efeito_id) $sql->adOnde('modelo_gestao_causa_efeito IN ('.$causa_efeito_id.')');
elseif ($arquivo_id) $sql->adOnde('modelo_gestao_arquivo IN ('.$arquivo_id.')');
elseif ($forum_id) $sql->adOnde('modelo_gestao_forum IN ('.$forum_id.')');
elseif ($checklist_id) $sql->adOnde('modelo_gestao_checklist IN ('.$checklist_id.')');
elseif ($agenda_id) $sql->adOnde('modelo_gestao_agenda IN ('.$agenda_id.')');
elseif ($agrupamento_id) $sql->adOnde('modelo_gestao_agrupamento IN ('.$agrupamento_id.')');
elseif ($patrocinador_id) $sql->adOnde('modelo_gestao_patrocinador IN ('.$patrocinador_id.')');
elseif ($template_id) $sql->adOnde('modelo_gestao_template IN ('.$template_id.')');
elseif ($painel_id) $sql->adOnde('modelo_gestao_painel IN ('.$painel_id.')');
elseif ($painel_odometro_id) $sql->adOnde('modelo_gestao_painel_odometro IN ('.$painel_odometro_id.')');
elseif ($painel_composicao_id) $sql->adOnde('modelo_gestao_painel_composicao IN ('.$painel_composicao_id.')');
elseif ($tr_id) $sql->adOnde('modelo_gestao_tr IN ('.$tr_id.')');
elseif ($me_id) $sql->adOnde('modelo_gestao_me IN ('.$me_id.')');
elseif ($plano_acao_item_id) $sql->adOnde('modelo_gestao_acao_item IN ('.$plano_acao_item_id.')');
elseif ($beneficio_id) $sql->adOnde('modelo_gestao_beneficio IN ('.$beneficio_id.')');
elseif ($painel_slideshow_id) $sql->adOnde('modelo_gestao_painel_slideshow IN ('.$painel_slideshow_id.')');
elseif ($projeto_viabilidade_id) $sql->adOnde('modelo_gestao_projeto_viabilidade IN ('.$projeto_viabilidade_id.')');
elseif ($projeto_abertura_id) $sql->adOnde('modelo_gestao_projeto_abertura IN ('.$projeto_abertura_id.')');
elseif ($pg_id) $sql->adOnde('modelo_gestao_plano_gestao IN ('.$pg_id.')');
elseif ($ssti_id) $sql->adOnde('modelo_gestao_ssti IN ('.$ssti_id.')');
elseif ($laudo_id) $sql->adOnde('modelo_gestao_laudo IN ('.$laudo_id.')');
elseif ($trelo_id) $sql->adOnde('modelo_gestao_trelo IN ('.$trelo_id.')');
elseif ($trelo_cartao_id) $sql->adOnde('modelo_gestao_trelo_cartao IN ('.$trelo_cartao_id.')');
elseif ($pdcl_id) $sql->adOnde('modelo_gestao_pdcl IN ('.$pdcl_id.')');
elseif ($pdcl_item_id) $sql->adOnde('modelo_gestao_pdcl_item IN ('.$pdcl_item_id.')');	
elseif ($os_id) $sql->adOnde('modelo_gestao_os IN ('.$os_id.')');	

$xpg_totalregistros=0;
$xpg_total_paginas=0;	
$xpg_tamanhoPagina = $config['qnt_emails'];


if ($item_menu=='entrada'|| $item_menu=='pendente'||$item_menu=='arquivado' || $item_menu=='enviado'){
	if ($campo_ordenar=='msg') $sql->adOrdem('modelo_usuario.modelo_id '.$ordem.', modelo_usuario.status ASC');
	else if ($campo_ordenar=='de') $sql->adOrdem('nome_usuario '.$ordem.', modelo_usuario.modelo_id DESC, modelo_usuario.status ASC');
	else if ($campo_ordenar=='referencia')$sql->adOrdem('modelo_assunto '.$ordem.', modelos.modelo_id DESC, modelo_usuario.status ASC');
	else if ($campo_ordenar=='data')$sql->adOrdem('modelo_usuario.datahora '.$ordem.', modelos.modelo_id DESC, modelo_usuario.status ASC');
	else if ($campo_ordenar=='status')$sql->adOrdem('modelo_usuario.status '.$ordem.', modelos.modelo_id DESC, modelo_usuario.status ASC');
	else if ($campo_ordenar=='cor')$sql->adOrdem('modelo_usuario.cor '.$ordem.', modelos.modelo_id DESC, modelo_usuario.status ASC');
	else if ($campo_ordenar=='tipo')$sql->adOrdem('modelo_tipo_nome '.$ordem.', modelos.modelo_id DESC, modelo_usuario.status ASC');
	else $sql->adOrdem('modelos.modelo_id DESC, modelo_usuario.status ASC');

	if ($tipo_tempo=='data_aprovado' || $item_menu=='a_protocolar') $campo_data='modelo_data_aprovado';
	elseif ($tipo_tempo=='data_protocolo' || $item_menu=='protocolado') $campo_data='modelo_data_protocolo';
	elseif ($tipo_tempo=='data_assinatura' || $item_menu=='assinado') $campo_data='modelo_data_assinado';
	else $campo_data='modelo_dados_data';
			
	//caso esteja visualizando conta de outra pessoa
	echo '<tr><td height=30  colspan="20"><font size=2><center><b>'.$titulo.'</b></center></td></tr>';
	echo '</table>';	
	echo '<table width="100%" class="std" align="center" rules="ALL" cellpadding=0 cellspacing=0>';
	echo '<tr align=center>';
	
	if (!$anexar_documento && !$referenciar_documento) echo '<td><input type="checkbox" name="sel_todas" value="1" onclick="marca_sel_todas();"></td>';
	echo '<td>'.dica('Ordenar pelo Assunto','Clique para ordenar pelo assunto dos documentos.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'referencia\');">'.($campo_ordenar=='referencia' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Assunto</b></a>'.dicaF().'</td>';
	echo '<td>'.dica('Ordenar pelo Remetente','Clique para ordenar pelos remetentes dos documentos.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'de\');">'.($campo_ordenar=='de' ? imagem('icones/'.$seta[$sentido]) : '').'<b>De</b></a>'.dicaF().'</td>';
	echo '<td>'.dica('Ordenar pelo Tipo de Documento','Clique para ordenar pelo tipo de documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'tipo\');">'.($campo_ordenar=='tipo' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Tipo</b></a>'.dicaF().'</td>';
	if ($item_menu=='enviado') echo '<td><b>N?o Leram</b></td>';
	echo '<td>'.dica('Ordenar pela Data de Envio','Clique para ordenar pela data de envio dos documentos.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'data\');">'.($campo_ordenar=='data' ? imagem('icones/'.$seta[$sentido]) : '').'<b><b>Data de Envio</b></a>'.dicaF().'</td>';
	if ($item_menu=='entrada') echo '<td align="center">'.dica('Ordenar pelo Status dos Documentos','Clique para ordenar pelo status dos documentos.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'status\');">'.($campo_ordenar=='status' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Status</b></a>'.dicaF().'</td>';
	echo '<td>'.dica('Ordenar pelo N?mero','Clique para ordenar pelos n?meros dos documentos.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'msg\');">'.($campo_ordenar=='msg' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Nr</b></a>'.dicaF().'</td>';
	if ($item_menu=='entrada' || $item_menu=='pendente' || $item_menu=='arquivado') echo '<td align="center">'.dica('Ordenar pela Cor do Documento','Clique para ordenar ordenar pela cor do documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'cor\');">'.($campo_ordenar=='cor' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Cor</b></a>'.dicaF().'</td>';
	echo '</tr>';

	$resultados=$sql->Lista();
	$sql->limpar();
	
	
	$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 
	$xpg_totalregistros = ($resultados ? count($resultados) : 0);
	$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
	$tipo_linha=0;
	for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
		$rs_linha=$resultados[$i];
		//ignorar dados inconsistentes (reportado por Cap Bertani)? 
		if (isset($rs_linha['modelo_id']) && $rs_linha['modelo_id']){
		  $tipo_linha =($tipo_linha == 1 ? 0 : 1);
		  if (($anexar_documento || $referenciar_documento)&& !$msg_id) $icone_anexar='<a href="javascript:void(0);" onclick="javascript: anexar('.$rs_linha['modelo_id'].', \''.$rs_linha['modelo_tipo_nome'].' - '.$rs_linha['modelo_assunto'].($rs_linha['modelo_dados_data'] && !$campo ? ' - '.retorna_data($rs_linha['modelo_dados_data']) : '' ).($rs_linha['nome_usuario']  && !$campo ? ' ('.$rs_linha['nome_usuario'].')' : '' ).'\', '.$campo.');">'.imagem('icones/adicionar.png','Adicionar Documento', 'Clique neste ?cone '.imagem('icones/adicionar.png').'para adicionar este documento.').'</a>';
			elseif (($anexar_documento || $referenciar_documento) && $msg_id) $icone_anexar='<a href="javascript:void(0);" onclick="javascript: env.anexar_msg.value='.$rs_linha['modelo_id'].'; env.submit();">'.imagem('icones/adicionar.png','Adicionar Documento', 'Clique neste ?cone '.imagem('icones/adicionar.png').' para adicionar este documento n'.$config['genero_mensagem'].' '.$config['mensagem'].' '.$msg_id).'</a>';
			else $icone_anexar='';
		  //verifica se tem anexo
		  $sql->adTabela('modelo_anexo');
		  $sql->adUnir('usuarios','usuarios','modelo_anexo_usuario=usuarios.usuario_id');
		  $sql->adCampo('modelo_anexo.*, contatos.contato_funcao');
		  $sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adOnde('modelo_anexo_modelo ='.(int)$rs_linha['modelo_id']);
			$sql->adOrdem('modelo_anexo_id');
		  $sql_resultadosc = $sql->Lista();
		  $sql->limpar();
		  $texto_anexo='';
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Nome</b></td><td>'.$rs_linha['nome_usuario'].'</td></tr>';
			if ($rs_linha['contato_funcao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Fun??o</b></td><td>'.$rs_linha['contato_funcao'].'</td></tr>';
			if ($rs_linha['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['organizacao'].'</b></td><td>'.$rs_linha['cia_nome'].'</td></tr>';
			if ($rs_linha['dept_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['departamento'].'</b></td><td>'.$rs_linha['dept_nome'].'</td></tr>';
			$dentro .= '</table>';
			$dentro .= 'Clique para ver os detalhes deste '.$config['usuario'].'.';
		  $qnt_anexo=0;
		  $modelo=0;
			$qnt_arquivo=0;
		  foreach((array)$sql_resultadosc as $rs_anexo){
				++$qnt_anexo;
				if (isset($rs_anexo['modelo_id']) && $rs_anexo['modelo_id']) $modelo++; 
				else $qnt_arquivo++;
				if ($qnt_anexo==1) $texto_anexo='<BR><BR><b>Documentos em Anexo:</b><BR>';
				$texto_anexo.='&nbsp;&nbsp;'.($rs_anexo['modelo_anexo_nome_fantasia'] ? $rs_anexo['modelo_anexo_nome_fantasia'] : $rs_anexo['modelo_anexo_nome']).' - '.($Aplic->usuario_prefs['nomefuncao'] ? ($rs_anexo['modelo_anexo_nome_de'] ? $rs_anexo['modelo_anexo_nome_de'] : $rs_anexo['nome_usuario']) : ($rs_anexo['modelo_anexo_funcao_de'] ? $rs_anexo['modelo_anexo_funcao_de'] : $rs_anexo['contato_funcao']) ).($rs_anexo['modelo_anexo_data_envio']? ' - '.retorna_data($rs_anexo['modelo_anexo_data_envio']) : '').'<br>';
				}
				
				
			$icone='';	

			$sql->adTabela('modelo_gestao');
			$sql->adCampo('modelo_gestao.*');
			$sql->adOnde('modelo_gestao_modelo='.(int)$rs_linha['modelo_id']);
			$lista = $sql->lista();
			$sql->limpar();
				
			foreach($lista as $linha) {	
				if ($linha['modelo_gestao_tarefa']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=tarefas&a=ver&tarefa_id='.$linha['modelo_gestao_tarefa'].'\');">'.imagem('icones/tarefa_p.gif',ucfirst($config['tarefa']),'Clique neste ?cone '.imagem('icones/tarefa_p.gif').' para ver '.$config['genero_tarefa'].' '.$config['tarefa'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_projeto']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=ver&projeto_id='.$linha['modelo_gestao_projeto'].'\');">'.imagem('icones/projeto_p.gif',ucfirst($config['projeto']),'Clique neste ?cone '.imagem('icones/projeto_p.gif').' para ver '.$config['genero_projeto'].' '.$config['projeto'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_perspectiva']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$linha['modelo_gestao_perspectiva'].'\');">'.imagem('icones/perspectiva_p.png',ucfirst($config['perspectiva']),'Clique neste ?cone '.imagem('icones/perspectiva_p.png').' para ver'.$config['genero_perspectiva'].' '.$config['perspectiva'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_tema']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=tema_ver&tema_id='.$linha['modelo_gestao_tema'].'\');">'.imagem('icones/tema_p.png',ucfirst($config['tema']),'Clique neste ?cone '.imagem('icones/tema_p.png').' para ver '.$config['genero_tema'].' '.$config['tema'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_objetivo']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=obj_estrategico_ver&objetivo_id='.$linha['modelo_gestao_objetivo'].'\');">'.imagem('icones/obj_estrategicos_p.gif',ucfirst($config['objetivo']),'Clique neste ?cone '.imagem('icones/obj_estrategicos_p.gif').' para ver '.$config['genero_objetivo'].' '.$config['objetivo'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_fator']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=fator_ver&fator_id='.$linha['modelo_gestao_fator'].'\');">'.imagem('icones/fator_p.gif',ucfirst($config['fator']),'Clique neste ?cone '.imagem('icones/fator_p.gif').' para ver '.$config['genero_fator'].' '.$config['fator'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_estrategia']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=estrategia_ver&pg_estrategia_id='.$linha['modelo_gestao_estrategia'].'\');">'.imagem('icones/estrategia_p.gif',ucfirst($config['iniciativa']),'Clique neste ?cone '.imagem('icones/estrategia_p.gif').' para ver '.$config['genero_iniciativa'].' '.$config['iniciativa'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_meta']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=meta_ver&pg_meta_id='.$linha['modelo_gestao_meta'].'\');">'.imagem('icones/meta_p.gif',ucfirst($config['meta']),'Clique neste ?cone '.imagem('icones/meta_p.gif').' para ver '.$config['genero_meta'].' '.$config['meta'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_pratica']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=pratica_ver&pratica_id='.$linha['modelo_gestao_pratica'].'\');">'.imagem('icones/pratica_p.gif',ucfirst($config['pratica']),'Clique neste ?cone '.imagem('icones/pratica_p.gif').' para ver '.$config['genero_pratica'].' '.$config['pratica'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_indicador']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=indicador_ver&pratica_indicador_id='.$linha['modelo_gestao_indicador'].'\');">'.imagem('icones/indicador_p.gif','Indicador','Clique neste ?cone '.imagem('icones/indicador_p.gif').' para ver o indicador ao qual este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_acao']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['modelo_gestao_acao'].'\');">'.imagem('icones/acao_p.gif',ucfirst($config['acao']),'Clique neste ?cone '.imagem('icones/acao_p.gif').' para ver '.$config['genero_acao'].' '.$config['acao'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_canvas']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=canvas_pro_ver&canvas_id='.$linha['modelo_gestao_canvas'].'\');">'.imagem('icones/canvas_p.png',ucfirst($config['canvas']),'Clique neste ?cone '.imagem('icones/canvas_p.png').' para ver'.$config['genero_canvas'].' '.$config['canvas'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_risco']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=operativo&a=operativo_ver&operativo_id='.$linha['modelo_gestao_risco'].'\');">'.imagem('icones/risco_p.png',ucfirst($config['risco']),'Clique neste ?cone '.imagem('icones/risco_p.png').' para ver '.$config['genero_risco'].' '.$config['risco'].' que este documento est? vinculado.').'</a>';	
				elseif ($linha['modelo_gestao_risco_resposta']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=operativo&a=operativo_ver&operativo_id='.$linha['modelo_gestao_risco_resposta'].'\');">'.imagem('icones/operativo_p.png',ucfirst($config['risco_resposta']),'Clique neste ?cone '.imagem('icones/risco_p.png').' para ver '.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' que este documento est? vinculado.').'</a>';	
				elseif ($linha['modelo_gestao_calendario']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$linha['modelo_gestao_calendario'].'\');">'.imagem('icones/calendario_p.png','Agenda','Clique neste ?cone '.imagem('icones/calendario_p.png').' para ver a agenda que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_monitoramento']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$linha['modelo_gestao_monitoramento'].'\');">'.imagem('icones/monitoramento_p.gif','Monitoramento','Clique neste ?cone '.imagem('icones/monitoramento_p.gif').' para ver o monitoramento que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_ata']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=atas&a=ata_ver&ata_id='.$linha['modelo_gestao_ata'].'\');">'.imagem('icones/ata_p.png','Ata de Reuni?o','Clique neste ?cone '.imagem('icones/ata_p.png').' para ver a ata de reuni?o que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_mswot']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=swot&a=mswot_ver&mswot_id='.$linha['modelo_gestao_mswot'].'\');">'.imagem('icones/mswot_p.png','Matriz SWOT','Clique neste ?cone '.imagem('icones/mswot_p.png').' para ver a matriz SWOT que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_swot']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=swot&a=swot_ver&swot_id='.$linha['modelo_gestao_swot'].'\');">'.imagem('icones/swot_p.png','Campo SWOT','Clique neste ?cone '.imagem('icones/swot_p.png').' para ver o campo de matriz SWOT que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_operativo']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=operativo&a=operativo_ver&operativo_id='.$linha['modelo_operativo'].'\');">'.imagem('icones/operativo_p.png','Plano Operativo','Clique neste ?cone '.imagem('icones/operativo_p.png').' para ver o plano operativo que este documento est? vinculado.').'</a>';	
				elseif ($linha['modelo_gestao_instrumento']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=instrumento&a=instrumento_ver&instrumento_id='.$linha['modelo_gestao_instrumento'].'\');">'.imagem('icones/instrumento_p.png',ucfirst($config['instrumento']),'Clique neste ?cone '.imagem('icones/instrumento_p.png').' para ver '.$config['genero_instrumento'].' '.$config['instrumento'].' que este documento est? vinculado.').'</a>';	
				elseif ($linha['modelo_gestao_recurso']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=recursos&a=ver&recurso_id='.$linha['modelo_gestao_recurso'].'\');">'.imagem('icones/recurso_p.png',ucfirst($config['recurso']),'Clique neste ?cone '.imagem('icones/recurso_p.png').' para ver '.$config['genero_recurso'].' '.$config['recurso'].' que este documento est? vinculado.').'</a>';	
				elseif ($linha['modelo_gestao_problema']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=problema&a=problema_ver&problema_id='.$linha['modelo_gestao_problema'].'\');">'.imagem('icones/problema_p.png',ucfirst($config['problema']),'Clique neste ?cone '.imagem('icones/problema_p.png').' para ver '.$config['genero_problema'].' '.$config['problema'].' que este documento est? vinculado.').'</a>';	
				elseif ($linha['modelo_gestao_demanda']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=demanda_ver&demanda_id='.$linha['modelo_gestao_demanda'].'\');">'.imagem('icones/demanda_p.gif','Demanda','Clique neste ?cone '.imagem('icones/demanda_p.gif').' para ver a demanda que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_programa']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=programa_pro_ver&programa_id='.$linha['modelo_gestao_programa'].'\');">'.imagem('icones/programa_p.png',ucfirst($config['programa']),'Clique neste ?cone '.imagem('icones/programa_p.png').' para ver '.$config['genero_programa'].' '.$config['programa'].' que este documento est? vinculado.').'</a>';	
				elseif ($linha['modelo_gestao_evento']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=calendario&a=ver&evento_id='.$linha['modelo_gestao_evento'].'\');">'.imagem('icones/calendario_p.png','Evento','Clique neste ?cone '.imagem('icones/calendario_p.png').' para ver o evento que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_link']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=links&a=ver&link_id='.$linha['modelo_gestao_link'].'\');">'.imagem('icones/links_p.gif','Link','Clique neste ?cone '.imagem('icones/links_p.gif').' para ver o link que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_avaliacao']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=avaliacao_ver&avaliacao_id='.$linha['modelo_gestao_avaliacao'].'\');">'.imagem('icones/avaliacao_p.gif','Avalia??o','Clique neste ?cone '.imagem('icones/avaliacao_p.gif').' para ver a avalia??o que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_tgn']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=tgn_pro_ver&tgn_id='.$linha['modelo_gestao_tgn'].'\');">'.imagem('icones/tgn_p.png',ucfirst($config['tgn']),'Clique neste ?cone '.imagem('icones/tgn_p.png').' para ver a '.$config['tgn'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_brainstorm']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=brainstorm_ver&brainstorm_id='.$linha['modelo_gestao_brainstorm'].'\');">'.imagem('icones/brainstorm_p.gif','Brainstorm','Clique neste ?cone '.imagem('icones/brainstorm_p.gif').' para ver o brainstorm que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_gut']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=gut_ver&gut_id='.$linha['modelo_gestao_gut'].'\');">'.imagem('icones/gut_p.gif','Matriz GUT','Clique neste ?cone '.imagem('icones/gut_p.gif').' para ver a matriz GUT que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_causa_efeito']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=causa_efeito_ver&causa_efeito_id='.$linha['modelo_gestao_causa_efeito'].'\');">'.imagem('icones/causa_efeito_p.gif','Diagrama de Causa-Efeito','Clique neste ?cone '.imagem('icones/causa_efeito_p.gif').' para ver o diagrama de causa-efeito que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_arquivo']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=arquivos&a=ver&arquivo_id='.$linha['modelo_gestao_arquivo'].'\');">'.imagem('icones/arquivo_p.png','Arquivo','Clique neste ?cone '.imagem('icones/arquivo_p.png').' para ver a arquivo que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_forum']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=foruns&a=ver&forum_id='.$linha['modelo_gestao_forum'].'\');">'.imagem('icones/forum_p.gif','F?rum','Clique neste ?cone '.imagem('icones/forum_p.gif').' para ver o f?rum que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_checklist']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=checklist_ver&checklist_id='.$linha['modelo_gestao_checklist'].'\');">'.imagem('icones/todo_list_p.png','Checklist','Clique neste ?cone '.imagem('icones/todo_list_p.png').' para ver o checklist que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_agenda']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=email&a=ver_compromisso&agenda_id='.$linha['modelo_gestao_agenda'].'\');">'.imagem('icones/calendario_p.png','Compromisso','Clique neste ?cone '.imagem('icones/calendario_p.png').' para ver o compromisso que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_agrupamento']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=agrupamento&a=agrupamento_ver&agrupamento_id='.$linha['modelo_gestao_agrupamento'].'\');">'.imagem('icones/agrupamento_p.png','Agrupamento','Clique neste ?cone '.imagem('icones/agrupamento_p.png').' para ver o arupamento que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_patrocinador']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=patrocinadores&a=patrocinador_ver&patrocinador_id='.$linha['modelo_gestao_patrocinador'].'\');">'.imagem('icones/patrocinador_p.gif','Patrocinador','Clique neste ?cone '.imagem('icones/patrocinador_p.gif').' para ver o patrocinador que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_template']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=template_pro_ver&template_id='.$linha['modelo_gestao_template'].'\');">'.imagem('icones/instrumento_p.png','Modelo','Clique neste ?cone '.imagem('icones/instrumento_p.png').' para ver o modelo que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_painel']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=painel_pro_ver&painel_id='.$linha['modelo_gestao_painel'].'\');">'.imagem('icones/indicador_p.gif','Painel','Clique neste ?cone '.imagem('icones/indicador_p.gif').' para ver o painel que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_painel_odometro']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=odometro_pro_ver&painel_odometro_id='.$linha['modelo_gestao_painel_odometro'].'\');">'.imagem('icones/odometro_p.png','Od?metro','Clique neste ?cone '.imagem('icones/odometro_p.png').' para ver o od?metro que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_painel_composicao']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=painel_composicao_pro_ver&painel_composicao_id='.$linha['modelo_gestao_painel_composicao'].'\');">'.imagem('icones/composicao_p.gif','Composi??o de Pain?is','Clique neste ?cone '.imagem('icones/composicao_p.gif').' para ver a composi??o de pain?is que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_tr']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=tr&a=tr_ver&tr_id='.$linha['modelo_gestao_tr'].'\');">'.imagem('icones/tr_p.png',ucfirst($config['tr']),'Clique neste ?cone '.imagem('icones/tr_p.png').' para ver '.$config['genero_tr'].' '.$config['tr'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_me']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=me_ver_pro&me_id='.$linha['modelo_gestao_me'].'\');">'.imagem('icones/me_p.png',ucfirst($config['me']),'Clique neste ?cone '.imagem('icones/me_p.png').' para ver '.$config['genero_me'].' '.$config['me'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_acao_item']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=plano_acao_item_ver&plano_acao_item_id='.$linha['modelo_gestao_acao_item'].'\');">'.imagem('icones/acao_item_p.png','Item d'.$config['genero_acao'].' '.ucfirst($config['acao']),'Clique neste ?cone '.imagem('icones/acao_item_p.png').' para ver o item d'.$config['genero_acao'].' '.$config['acao'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_beneficio']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=beneficio_pro_ver&beneficio_id='.$linha['modelo_gestao_beneficio'].'\');">'.imagem('icones/beneficio_p.png',ucfirst($config['beneficio']).' d'.$config['genero_programa'].' '.ucfirst($config['programa']),'Clique neste ?cone '.imagem('icones/beneficio_p.png').' para ver '.$config['genero_beneficio'].' '.$config['beneficio'].' d'.$config['genero_programa'].' '.$config['programa'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_painel_slideshow']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.$linha['modelo_gestao_painel_slideshow'].'\');">'.imagem('icones/slideshow_p.gif','Slideshow de Composi??es','Clique neste ?cone '.imagem('icones/slideshow_p.gif').' para ver o slideshow de composi??es que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_projeto_viabilidade']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$linha['modelo_gestao_projeto_viabilidade'].'\');">'.imagem('icones/viabilidade_p.gif','Estudo de Viabilidade','Clique neste ?cone '.imagem('icones/viabilidade_p.gif').' para ver o estudo de viabilidade que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_projeto_abertura']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$linha['modelo_gestao_projeto_abertura'].'\');">'.imagem('icones/anexo_projeto_p.png','Termo de Abertura','Clique neste ?cone '.imagem('icones/anexo_projeto_p.png').' para ver o termo de abertura que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_plano_gestao']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=praticas&u=gestao&a=menu&pg_id='.$linha['modelo_gestao_plano_gestao'].'\');">'.imagem('icones/planogestao_p.png','Planejamento Estrat?gico','Clique neste ?cone '.imagem('icones/planogestao_p.png').' para ver o planejamento estrat?gico que este documento est? vinculado.').'</a>';		
				elseif ($linha['modelo_gestao_ssti']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=ssti&a=ssti_ver&ssti_id='.$linha['modelo_gestao_ssti'].'\');">'.imagem('icones/ssti_p.png',ucfirst($config['ssti']),'Clique neste ?cone '.imagem('icones/ssti_p.png').' para ver '.$config['genero_ssti'].' '.$config['ssti'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_laudo']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=ssti&a=laudo_ver&laudo_id='.$linha['modelo_gestao_laudo'].'\');">'.imagem('icones/laudo_p.png',ucfirst($config['laudo']),'Clique neste ?cone '.imagem('icones/laudo_p.png').' para ver '.$config['genero_laudo'].' '.$config['laudo'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_trelo']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=trelo&a=trelo_ver&trelo_id='.$linha['modelo_gestao_trelo'].'\');">'.imagem('icones/trelo_p.png',ucfirst($config['trelo']),'Clique neste ?cone '.imagem('icones/trelo_p.png').' para ver '.$config['genero_trelo'].' '.$config['trelo'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_trelo_cartao']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=trelo&a=trelo_cartao_ver&trelo_cartao_id='.$linha['modelo_gestao_trelo_cartao'].'\');">'.imagem('icones/trelo_cartao_p.png',ucfirst($config['trelo_cartao']),'Clique neste ?cone '.imagem('icones/trelo_cartao_p.png').' para ver '.$config['genero_trelo_cartao'].' '.$config['trelo_cartao'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_pdcl']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=pdcl&a=pdcl_ver&pdcl_id='.$linha['modelo_gestao_pdcl'].'\');">'.imagem('icones/pdcl_p.png',ucfirst($config['pdcl']),'Clique neste ?cone '.imagem('icones/pdcl_p.png').' para ver '.$config['genero_pdcl'].' '.$config['pdcl'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_pdcl_item']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=pdcl&a=pdcl_item_ver&pdcl_item_id='.$linha['modelo_gestao_pdcl_item'].'\');">'.imagem('icones/pdcl_item_p.png',ucfirst($config['pdcl_item']),'Clique neste ?cone '.imagem('icones/pdcl_item_p.png').' para ver '.$config['genero_pdcl_item'].' '.$config['pdcl_item'].' que este documento est? vinculado.').'</a>';
				elseif ($linha['modelo_gestao_os']) $icone.='<a href="javascript:void(0);" onclick="url_passar(0,\'m=os&a=os_ver&os_id='.$linha['modelo_gestao_os'].'\');">'.imagem('icones/os_p.png',ucfirst($config['os']),'Clique neste ?cone '.imagem('icones/os_p.png').' para ver '.$config['genero_os'].' '.$config['os'].' que este documento est? vinculado.').'</a>';

				}
				
			echo '<tr '.($item_menu=='entrada' && $rs_linha['status']==0 ? retornar_cores (2) : retornar_cores ($tipo_linha)).'>'; 
			if (!$anexar_documento && !$referenciar_documento) echo '<td width="20"><input type="checkbox" name="vetor_modelo_msg_usuario[]" value="'.$rs_linha['modelo_usuario_id'].'"></td>';
			$imagem=imagem('icones/msg'.($modelo ? '1' : '0').($qnt_arquivo ? '1' : '0').'0'.$rs_linha['tipo'].'0.gif');
			$rs_anexo=null;
			echo '<td align="left">'.($icone_anexar ? $icone_anexar : '').$imagem.$icone.'<a href="javascript:void(0);" onclick="javascript:visualizar_msg('.$rs_linha['modelo_id'].', '.$rs_linha['modelo_usuario_id'].')">'.dica($tipo[$rs_linha['tipo']],$rs_linha['texto_nota']).$rs_linha['modelo_assunto'].dicaF().'</a></td>';
			echo '<td style="white-space: nowrap">'.dica('Detalhes do '.ucfirst($config['usuario']), $dentro).'<a href="javascript:void(0);" onclick="javascript:visualizar_usuario('.$rs_linha['de_id'].');">'.($Aplic->usuario_prefs['nomefuncao'] ? ($rs_linha['nome_de'] ? $rs_linha['nome_de'] : $rs_linha['nome_usuario']) : ($rs_linha['funcao_de'] ? $rs_linha['funcao_de'] : $rs_linha['contato_funcao'])).'</a>'.dicaF().'</td>';	
			echo '<td>'.$rs_linha['modelo_tipo_nome'].'</td>';
			if ($item_menu=='enviado'){
				$sql->adTabela('modelo_usuario');
			  $sql->adCampo('count(DISTINCT modelo_usuario.para_id) AS quantidade');
				$sql->adOnde('modelo_usuario.status = 0 AND modelo_id = '.$rs_linha['modelo_id']);
			  $quantidade = $sql->Resultado();
			  $sql->limpar();
				echo '<td style="white-space: nowrap">'.$quantidade.'</td>'; 
				}
			echo '<td style="white-space: nowrap" width="120">'.retorna_data($rs_linha['datahora']).'</td>';
			if ($item_menu=='entrada'){ 
				echo '<td style="white-space: nowrap">'.$tipos_status[$rs_linha['status']].'</td>';
				$passou=1;
				}
			echo '<td style="white-space: nowrap">'.$rs_linha['modelo_id'].'</td>';
			if ($item_menu=='entrada' || $item_menu=='pendente' || $item_menu=='arquivado') echo '<td width="25" style="background-color:#'.$rs_linha['cor'].'"><a href="javascript:void(0);" onclick="javascript:inserir_nota('.$rs_linha['modelo_usuario_id'].');">'.($rs_linha['nota'] ? dica('Anota??o',retorna_tira_duas_linhas($rs_linha['nota']).'<br><br>Clique para editar a anota??o').imagem('icones/anexar.png'): dica('Inserir Anota??o','Clique para inserir uma anota??o').imagem('icones/nota.gif')).dicaF().'</a></td>';	
			echo '</tr>';
			}
	 	} 
	
	if (!($anexar_documento || $referenciar_documento)){
		echo '<tr><td align=center colspan="20"><table><tr>';
		if (($item_menu=='pendente' || $item_menu=='arquivado')  && $tem_pasta) echo '<td>'.dica('Caixa de Sele??o de Pasta ','Selecione na caixa de op??o em qual pasta deseja entrar para ver os documentos armazenados.').'<a>'.comboPasta().'</a>'.dicaF().'</td>'; 
		if (($item_menu=='entrada' || $item_menu=='pendente' || $item_menu=='arquivado') && $tem_pasta) echo '<td>'.dica('Mover para Pasta','Selecione na caixa de op??o para qual pasta deseja mover os documentos selecionados.').'<a>'.comboMover().'</a>'.dicaF().'</td>'; 
		if ($item_menu=='entrada' || $item_menu=='pendente' || $item_menu=='arquivado')	echo '<td>'.dica('Responder','Clique nesta op??o para enviar uma resposta para o remetente do documento.<BR><BR>Ao contr?rio do DESPACHAR, em que se seleciona quantos destinat?rios desejar, ao responder apenas o remetente do documento ? automaticamente selecionado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:responder();"><span><b>responder</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Despachar','Clique nesta op??o para enviar um texto para os destinat?rios selecionados dos documentos selecionadas.<BR><BR>Ao contr?rio do RESPONDER, em que o remetente do documento ? automaticamente selecionado, no despacho cada destinat?rio dever? ser selecionado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:despachar();"><span><b>despachar</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Anotar','Clique nesta op??o para registrar um texto junto com  os documentos selecionados.<BR><BR>Anotar se diferencia das op??es RESPONDER E DESPACHAR por n?o enviar o documento para nenhum destinat?rio.').'<a class="botao"  href="javascript:void(0);" onclick="javascript:anotar();"><span><b>anotar</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Encaminhar','Clique nesta op??o para enviar os documentos selecionados para os destinat?rios selecionados.<BR><BR>Ao contr?rio de DESPACHAR, RESPONDER e ANOTAR, nenhum texto ser? registrado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:encaminhar();"><span><b>encaminhar</b></span></a>'.dicaF().'</td>';
		if ($item_menu=='entrada' || $item_menu=='arquivado') echo '<td>'.dica('Pender','Clique nesta op??o para mover os documentos selecionados para a caixa des documentos pendentes').'<a class="botao" href="javascript:pender();"><span><b>pender</b></span></a>'.dicaF().'</td>';
		if ($item_menu=='entrada' || $item_menu=='pendente') echo '<td>'.dica('Arquivar','Clique nesta op??o para mover os documentos selecionados para a caixa dos documentos arquivados').'<a class="botao" href="javascript:arquivar();"><span><b>arquivar</b></span></a>'.dicaF().'</td>';
		if ($item_menu=='entrada' || $item_menu=='pendente' || $item_menu=='arquivado') echo '<td>'.dica('Pastas','Clique nesta op??o para editar as pastas particulares.<BR><BR>Apenas ap?s criar, ao menos, uma pasta, ser? poss?vel utilizar as caixas de sele??o da extrema esquerda.').'<a class="botao" href="javascript:void(0);" onclick="javascript:editar_pastas()"><span><b>pastas</b></span></a>'.dicaF().'</td>';
		echo '</tr></table></td></tr>';
		}
	}

///diferente de entrada, pendente, arquivada, enviada e pesquisar
elseif (!(($anexar_documento || $referenciar_documento) && !$pesquisar)){
	$tipo_linha=0;
	$resultados=$sql->Lista();
	$xpg_tamanhoPagina = 16;
	$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 
	$xpg_totalregistros = ($resultados ? count($resultados) : 0);
	$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
	
	if ($tipo_tempo=='data_aprovado' || $item_menu=='a_protocolar') $campo_data='modelo_data_aprovado';
	elseif ($tipo_tempo=='data_protocolo' || $item_menu=='protocolado') $campo_data='modelo_data_protocolo';
	elseif ($tipo_tempo=='data_assinatura' || $item_menu=='assinado') $campo_data='modelo_data_assinado';
	else $campo_data='modelo_dados_data';
	
	echo '<tr><td height=30  colspan="20"><font size=2><center><b>'.$titulo.'</b></center></td></tr>';
	echo '<tr><td colspan="20"><table width="100%" class="std" align="center" rules="ALL" cellpadding=0 cellspacing=0>';
	echo '<tr align="center">';
	if (!$anexar_documento && !$referenciar_documento) echo '<td><input type="checkbox" name="sel_todas" value="1" onclick="marca_sel_todas();"></td>';
	echo '<td>'.dica('Ordenar pelo Assunto','Clique para ordenar pelo assunto do documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'assunto\');">'.($campo_ordenar=='assunto' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Assunto</b>'.dicaF().'</a></td>';
	echo '<td>'.dica('Ordenar pelo Tipo','Clique para ordenar pelo tipo de documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'tipo\');">'.($campo_ordenar=='tipo' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Tipo</b>'.dicaF().'</td>';
	
	if ($tipo_tempo=='data_aprovado' || $item_menu=='a_protocolar') echo '<td>'.dica('Ordenar pela Data de Aprova??o','Clique para ordenar pela data de aprova??o do documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'data_aprovado\');">'.($campo_ordenar=='data_aprovado' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Data</b>'.dicaF().'</td>';
	elseif ($tipo_tempo=='data_protocolo' || $item_menu=='protocolado') echo '<td>'.dica('Ordenar pela Data de Aprova??o','Clique para ordenar pela data de aprova??o do documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'data_protocolo\');">'.($campo_ordenar=='data_protocolo' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Data</b>'.dicaF().'</td>';
	elseif ($tipo_tempo=='data_assinatura' || $item_menu=='assinado') echo '<td>'.dica('Ordenar pela Data de Assinatura','Clique para ordenar pela data de assinatura do documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'data_assinatura\');">'.($campo_ordenar=='data_assinatura' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Data</b>'.dicaF().'</td>';

		
	else echo '<td>'.dica('Ordenar pela Data de Cria??o','Clique para ordenar pela data de cria??o do documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'data_criacao\');">'.($campo_ordenar=='data_criacao' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Data</b>'.dicaF().'</td>';
	if ($item_menu!='a_protocolar' && $item_menu!='protocolado' && $item_menu!='assinado') echo '<td style="white-space: nowrap"><a href="javascript:void(0);" onclick="javascript:ordenar(\'criador\');">'.dica('Criador', 'Neste campo fica o criador do documento. Mesmo que o mesmo tenha sido editado por outr'.$config['genero_usuario'].'s '.$config['usuarios'].' apenas o criador original ? considerado.').($campo_ordenar=='criador' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Criador</b>'.dicaF().'</td>';
	echo '<td>'.dica('Ordenar pelo Numero','Clique para ordenar pelo numero do documento.<br><br>A cada clique ser? alterada a ordem, entre crescente e decrescente.').'<a href="javascript:void(0);" onclick="javascript:ordenar(\'numero\');">'.($campo_ordenar=='numero' ? imagem('icones/'.$seta[$sentido]) : '').'<b>Nr</b>'.dicaF().'</td>';
	echo '</tr>';
	
	if ($tipo_tempo=='data_aprovado' || $item_menu=='a_protocolar') $campo_data='modelo_data_aprovado';
	elseif ($tipo_tempo=='data_protocolo' || $item_menu=='protocolado') $campo_data='modelo_data_protocolo';
	elseif ($tipo_tempo=='data_assinatura' || $item_menu=='assinado') $campo_data='modelo_data_assinado';
	else $campo_data='modelo_dados_data';
	
	$quant=0;
	
	for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
		$rs=$resultados[$i];
		//evitar inconsistencias
		if(isset($rs['modelo_id']) && $rs['modelo_id']){
			$tipo_linha =($tipo_linha == 1 ? 0 : 1);
			if (($anexar_documento || $referenciar_documento)&& !$msg_id) {
				$icone_anexar='<a href="javascript:void(0);" onclick="javascript:anexar('.$rs['modelo_id'].', \''.$rs['modelo_tipo_nome'].($rs['modelo_id']? ' - Nr '.$rs['modelo_id'] : '' ).' - '.$rs['modelo_assunto'].($rs['modelo_dados_data'] && !$campo ? ' - '.retorna_data($rs['modelo_dados_data']) : '' ).($rs['nome_usuario']  && !$campo ? ' ('.$rs['nome_usuario'].')' : '' ).'\', '.$campo.');">'.imagem('icones/adicionar.png','Adicionar Documento', 'Clique neste ?cone '.imagem('icones/adicionar.png').' para adicionar este documento.').'</a>';
				$comando_anexar='anexar('.$rs['modelo_id'].', \''.$rs['modelo_tipo_nome'].($rs['modelo_id']? ' - Nr '.$rs['modelo_id'].' - ' : '' ).' - '.$rs['modelo_assunto'].($rs['modelo_dados_data'] && !$campo ? ' - '.retorna_data($rs[$campo_data]) : '' ).($rs['nome_usuario']  && !$campo ? ' ('.$rs['nome_usuario'].')' : '' ).'\', '.$campo.')';
				}
			elseif (($anexar_documento || $referenciar_documento) && $msg_id) {
				$icone_anexar='<a href="javascript:void(0);" onclick="javascript: env.anexar_msg.value='.$rs['modelo_id'].'; env.submit();">'.imagem('icones/adicionar.png','Adicionar Documento', 'Clique neste ?cone '.imagem('icones/adicionar.png').' para adicionar este documento n'.$config['genero_mensagem'].' '.$config['mensagem'].' '.$msg_id).'</a>';
				$comando_anexar='env.anexar_msg.value='.$rs['modelo_id'].'; env.submit();';
				}
			else {
				$icone_anexar='';
				$comando_anexar='';
				}
			
			//inicio da Linha
			echo '<tr align="center" '.retornar_cores ($tipo_linha).'>';
			if (!$anexar_documento && !$referenciar_documento) echo '<td width="20"><input type="checkbox" name="modeloID[]" value="'.$rs['modelo_id'].'" onclick="'.$comando_anexar.'"></td>';
			echo '<td align="left"><a href="javascript:void(0);" onclick="url_passar('.($anexar_documento || $referenciar_documento ? '1' : '0').', \'m=email&a=modelo_ver&retornar=modelo_pesquisar&modelo_id='.$rs['modelo_id'].($anexar_documento || $referenciar_documento ? '&dialogo=1' : '').'\');">'.$icone_anexar.$rs['modelo_assunto'].'</a></td>';
			echo '<td>'.$rs['modelo_tipo_nome'].'</td>';
			
			echo '<td width="120">'.($rs[$campo_data]? retorna_data($rs[$campo_data]) : '&nbsp;' ).'</td>';

			if ($item_menu!='a_protocolar' && $item_menu!='protocolado' && $item_menu!='assinado') echo '<td>'.($rs['modelo_criador_original'] ? nome_funcao(null, null, null, null, $rs['modelo_criador_original']): '&nbsp;' ).'</td>';
			echo '<td>'.$rs['modelo_id'].'</td>';
			echo '</tr>';
			$quant++;
			}
		}
	if (!$quant) echo '<tr '.retornar_cores ($tipo_linha).'><td colspan=20><br>Nenhum documento encontrado<br>&nbsp;</td></tr>';	
	if (!$anexar_documento && !$referenciar_documento){
		echo '<tr><td align=center colspan="20"><table><tr>';
		echo '<td>'.dica('Despachar','Clique nesta op??o para enviar um texto para os destinat?rios selecionados dos documentos selecionadas.<BR><BR>Ao contr?rio do RESPONDER, em que o remetente do documento ? automaticamente selecionado, no despacho cada destinat?rio dever? ser selecionado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:despachar();"><span><b>despachar</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Anotar','Clique nesta op??o para registrar um texto junto com  os documentos selecionados.<BR><BR>Anotar se diferencia das op??es RESPONDER E DESPACHAR por n?o enviar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' para nenhum destinat?rio.').'<a class="botao"  href="javascript:void(0);" onclick="javascript:anotar();"><span><b>anotar</b></span></a>'.dicaF().'</td>';
		echo '<td>'.dica('Encaminhar','Clique nesta op??o para enviar os documentos selecionados para os destinat?rios selecionados.<BR><BR>Ao contr?rio de DESPACHAR, RESPONDER e ANOTAR, nenhum texto ser? registrado.').'<a class="botao" href="javascript:void(0);" onclick="javascript:encaminhar();"><span><b>encaminhar</b></span></a>'.dicaF().'</td>';
		if ($config['envia_email']) echo '<td>'.dica('Encaminhar por E-Mail','Clique nesta op??o para enviar os documentos selecionados para os e-mails dos destinat?rios selecionados.<BR><BR>Caso os destinat?rios n?o tenham um e-mail cadastrado n?o ser? poss?vel o envio.').'<a class="botao" href="javascript:void(0);" onclick="javascript:encaminhar_email()"><span><b>encaminhar por e-mail</b></span></a>'.dicaF().'</td>';
		echo '</tr></table></td></tr>';
		}
	echo '</table></td></tr>';
	}	
echo '</table>';

echo '</form>';
echo estiloFundoCaixa();


mostrarBarraNav2($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'documento', 'documentos', '', 'env');


function comboPasta() {
	global $status, $pasta, $Aplic, $coletivo;
	$sql = new BDConsulta;
	$s = '<select id="codigo_pasta" name="codigo_pasta" class=text size=1 onchange="resulta_combo('.$status.');">';
	$s .= '<option value="null" '.(!$pasta ? ' selected="selected"' : '').' >fora de pasta</option>';
	$s .= '<option value="-1" '.($pasta==-1 ? ' selected="selected"' : '').' >todos os documentos</option>';
	$sql->adTabela('pasta');
  $sql->adCampo('pasta_id,nome');
	$sql->adOnde('pasta.usuario_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$pastas=$sql->Lista();
	$sql->limpar();
	foreach((array)$pastas as $linha) $s .= '<option value="'.$linha['pasta_id'].'"'.(($linha['pasta_id'] == $pasta ) ? ' selected="selected"' : '').'>'.$linha['nome'].'</option>';
	$s .= '</select>';
	return $s;	
	}

function comboMover() {
	global $status, $coletivo, $Aplic, $item_menu;
	$sql = new BDConsulta;
	$sql->adTabela('pasta');
  $sql->adCampo('pasta_id, nome');
	$sql->adOnde('pasta.usuario_id	'.($coletivo ? 'IN ('.$Aplic->usuario_lista_grupo.')' : '='.(int)$Aplic->usuario_id));
	$pastas=$sql->Lista();
	$sql->limpar();
	$s = '<select id="codigo_mover_pasta" name="codigo_mover_pasta" class=text size=1 onchange="javascript:mover_pasta(\''.$item_menu.'\');" >';
	$s .= '<option value="null" selected>'.($item_menu=='entrada' ? 'arquivar em' : 'mover para').'</option>';
	$s .= '<option value="null" >fora de pasta</option>';
	foreach((array)$pastas as $linha) $s .= '<option value="'.$linha['pasta_id'].'">'.$linha['nome'].'</option>';
	$s .= '</select>';
	return $s;	
	}

 
?>
<script type="text/javascript">

function popCriador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Criador', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCriador&usuario_id='+document.getElementById('criador').value, window.setCriador, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCriador&usuario_id='+document.getElementById('criador').value, 'Criador','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setCriador(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('criador').value=usuario_id;		
	document.getElementById('nome_criador').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}		


function popAprovou() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Aprovador', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAprovou&usuario_id='+document.getElementById('aprovou').value, window.setAprovou, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setAprovou&usuario_id='+document.getElementById('aprovou').value, 'Aprovador','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setAprovou(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('aprovou').value=usuario_id;		
	document.getElementById('nome_aprovou').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}

function resulta_combo(status) {
  document.getElementById('pasta').value=document.getElementById('codigo_pasta').value;
  document.getElementById('a').value="modelo_pesquisar";
	document.getElementById('env').submit();		
  } 
        
function mover_pasta(escolha) {
  var vetor_msg_id=new Array();
  var j=0;
  for(i=0;i<document.getElementById('env').elements.length;i++) {
			thiselm = document.getElementById('env').elements[i];
			if (thiselm.checked && thiselm.name=='vetor_modelo_msg_usuario[]')  vetor_msg_id[j++]=thiselm.value;
	  	}	
	if (j>0){
			if (escolha=='entrada') document.getElementById('arquivar').value=1;
			document.getElementById('mover').value=vetor_msg_id;
			document.getElementById('pasta').value=document.getElementById('codigo_mover_pasta').value;
  		document.getElementById('a').value="modelo_pesquisar";
			document.getElementById('env').submit();
			}
	else alert("Selecione ao menos um documento!");				
  }              

function marca_sel_todas() {
  with(document.getElementById('env')) {
		  for(i=0;i<elements.length;i++) {
					thiselm = elements[i];
					thiselm.checked = !thiselm.checked
	        }
      }
  }
  
function editar_pastas(){
 	env.a.value="editar_pastas";
 	env.retornar.value="modelo_pesquisar";
	env.submit();		
  }

function visualizar_msg(modelo_id, modelo_usuario_id){
	env.modelo_id.value=modelo_id;
	env.modelo_usuario_id.value=modelo_usuario_id;
	env.retornar.value="modelo_pesquisar";
	env.m.value="email";
	env.a.value="modelo_ver";
	env.submit();	
	}		

function inserir_nota(modelo_usuario_id){
	env.a.value="modelo_inserir_nota";
	env.modelo_usuario_id.value=modelo_usuario_id;
	env.retornar.value="modelo_pesquisar";
	env.submit();	
	}


function visualizar_usuario (usuario_id){
	env.m.value='admin';
	env.a.value='ver_usuario';
	env.tab.value=3;
	env.usuario_id.value=usuario_id;
	env.retornar.value="modelo_pesquisar";
	env.submit();	
	} 
  
function verifica_selecao(){
	var j=0;
	for(i=0;i < document.getElementById('env').elements.length;i++) {
		if (document.getElementById('env').elements[i].checked) j++;
		}	
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos um documento!"); 
		return 0;
		}
	}  


function anotar(){
	if (verifica_selecao()){
		env.status.value=null;
		env.tipo.value=4;
		env.a.value="modelo_envia_anot";
		env.retornar.value="modelo_pesquisar";
		env.submit();	
		}
	}  

function encaminhar(){
	if (verifica_selecao()){	
		env.tipo.value=3;
		env.destino.value="modelo_grava_encaminha";
		env.a.value="modelo_seleciona_usuarios";
		env.retornar.value="modelo_pesquisar";
		env.submit();
		}
	} 

function despachar(){
	if (verifica_selecao()){
		env.status.value=null;	
		env.tipo.value=1;
		env.destino.value="modelo_envia_anot";
		env.a.value="modelo_seleciona_usuarios";
		env.retornar.value="modelo_pesquisar";
		env.submit();
		}
	}  

function responder(){
	if (verifica_selecao()){
		env.status.value=null;	
		env.tipo.value=2;
		env.a.value="modelo_envia_anot";
		env.retornar.value="modelo_pesquisar";
		env.submit();
		}
	}  



function pender(){
	if (verifica_selecao()){	
		env.status.value=3;
		env.a.value="modelo_grava_status";
		env.submit();
		}
	} 

function arquivar(){
	if (verifica_selecao()){	
		env.status.value=4;
		env.a.value="modelo_grava_status";
		env.submit();
		}
	} 
 
function encaminhar_email(){
	if (verifica_selecao()){
		env.destino.value="modelo_envia_email";	
		env.tipo.value=1;	 
		env.a.value="modelo_seleciona_usuarios";
		env.retornar.value="modelo_pesquisar";
		env.submit();
		}
	}	
	
function ordenar(pesquisa){
	env.campo_ordenar.value=pesquisa;	 
	env.a.value="modelo_pesquisar";
	if (env.sentido.value==0) env.sentido.value=1;
	else env.sentido.value=0;
	env.submit();
	}  	
	
function anexar(modelo_id, texto, campo){
	<?php 
	if ($Aplic->profissional) echo ($referenciar_documento ? 'parent.gpwebApp._popupCallback(modelo_id, texto, campo);' : 'parent.gpwebApp._popupCallback(modelo_id, texto, campo);'); 
	else echo ($anexar_documento ? 'window.opener.anexar_documento_referencia(modelo_id, texto, campo);' : 'window.opener.anexar_documento(modelo_id, texto, campo);'); 
	?>
	window.close();
	}	
	
function mostrarEsconder() {
  if (document.getElementById('pesquisa_completa').style.display != 'none') document.getElementById('pesquisa_completa').style.display = 'none';
  else document.getElementById('pesquisa_completa').style.display = '';
	}

var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "pesquisa_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pesquisa_inicio").value = Calendario.printDate(date, "%Y%m%d");
      }
  	cal1.hide(); 
  	}
  });
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "pesquisa_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) { 
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pesquisa_fim").value = Calendario.printDate(date, "%Y%m%d");
      }
  	cal2.hide(); 
  	}
  });	
	
function setData( frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.'+frm_nome+ '.'+f_data);
	campo_data_real = eval( 'document.'+frm_nome+'.'+f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n?o corresponde ao formato padr?o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		} 
    else{
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		} 
	else campo_data_real.value = '';
	}

function limpar_pesquisa(){
	env.pagina.value=1; 
	env.tipo_documento.selectedIndex=0;
	env.tipo_tempo.selectedIndex=0;
	env.numero.value='';
	env.modelo_protocolo.value='';
	env.assunto.value='';
	env.data_inicio.value='';
	env.data_fim.value='';
	env.pesquisa_inicio.value='';
	env.pesquisa_fim.value='';
	env.criador.selectedIndex=0;
	env.aprovou.selectedIndex=0;
	env.estado_documento.selectedIndex=0;
	env.acao_documento.selectedIndex=0;
	env.modelo_tipo_id.selectedIndex=0;
	env.pesquisar_tudo.checked=false;
	}
	
	
function popRelacao(relacao){
	if(relacao) eval(relacao+'()'); 
	env.tipo_relacao.value='';
	}
	
function limpar_tudo(){
	document.env.projeto_id.value = null;
	document.env.tarefa_id.value = null;
	document.env.pg_perspectiva_id.value = null;
	document.env.tema_id.value = null;
	document.env.objetivo_id.value = null;
	document.env.fator_id.value = null;
	document.env.pg_estrategia_id.value = null;
	document.env.pg_meta_id.value = null;
	document.env.pratica_id.value = null;
	document.env.plano_acao_id.value = null;
	document.env.canvas_id.value = null;
	document.env.risco_id.value = null;
	document.env.risco_resposta_id.value = null;
	document.env.pratica_indicador_id.value = null;
	document.env.calendario_id.value = null;
	document.env.monitoramento_id.value = null;
	document.env.ata_id.value = null;
	document.env.mswot_id.value = null;
	document.env.swot_id.value = null;
	document.env.operativo_id.value = null;
	document.env.instrumento_id.value = null;
	document.env.recurso_id.value = null;
	document.env.problema_id.value = null;
	document.env.demanda_id.value = null;
	document.env.programa_id.value = null;
	document.env.licao_id.value = null;
	document.env.evento_id.value = null;
	document.env.link_id.value = null;
	document.env.avaliacao_id.value = null;
	document.env.tgn_id.value = null;
	document.env.brainstorm_id.value = null;
	document.env.gut_id.value = null;
	document.env.causa_efeito_id.value = null;
	document.env.arquivo_id.value = null;
	document.env.forum_id.value = null;
	document.env.checklist_id.value = null;
	document.env.agenda_id.value = null;
	document.env.agrupamento_id.value = null;
	document.env.patrocinador_id.value = null;
	document.env.template_id.value = null;
	document.env.painel_id.value = null;
	document.env.painel_odometro_id.value = null;
	document.env.painel_composicao_id.value = null;
	document.env.tr_id.value = null;
	document.env.me_id.value = null;
	document.env.plano_acao_item_id.value = null;
	document.env.beneficio_id.value = null;
	document.env.painel_slideshow_id.value = null;
	document.env.projeto_viabilidade_id.value = null;
	document.env.projeto_abertura_id.value = null;
	document.env.pg_id.value = null;		
	document.env.ssti_id.value = null;
	document.env.laudo_id.value = null;
	document.env.trelo_id.value = null;
	document.env.trelo_cartao_id.value = null;
	document.env.pdcl_id.value = null;
	document.env.pdcl_item_id.value = null;
	document.env.os_id.value = null;
	}


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 1000, 700, 'm=projetos&a=index&dialogo=1&selecao=2&chamarVolta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.projeto_id.value = chave;
	env.submit();
	}

function popTarefa() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 1000, 700, 'm=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('cia_id').value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.tarefa_id.value = chave;
	env.submit();
	}
	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 1000, 700, 'm=praticas&a=perspectiva_lista&dialogo=1&selecao=2&chamarVolta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.pg_perspectiva_id.value = chave;
	env.submit();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 1000, 700, 'm=praticas&a=tema_lista&dialogo=1&selecao=2&chamarVolta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.tema_id.value = chave;
	env.submit();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&selecao=2&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.objetivo_id.value = chave;
	env.submit();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 1000, 700, 'm=praticas&a=fator_lista&dialogo=1&selecao=2&chamarVolta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setFator&tabela=fator&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.fator_id.value = chave;
	env.submit();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 1000, 700, 'm=praticas&a=estrategia_lista&dialogo=1&selecao=2&chamarVolta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.pg_estrategia_id.value = chave;
	env.submit();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&selecao=2&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.pg_meta_id.value = chave;
	env.submit();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 1000, 700, 'm=praticas&a=pratica_lista&dialogo=1&selecao=2&chamarVolta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.pratica_id.value = chave;
	env.submit();
	}
	
function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=praticas&a=indicador_lista&dialogo=1&selecao=2&chamarVolta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('cia_id').value, 'Indicador','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_id.value = chave;
	env.submit();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_lista&dialogo=1&selecao=2&chamarVolta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.plano_acao_id.value = chave;
	env.submit();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 1000, 700, 'm=praticas&a=canvas_pro_lista&dialogo=1&selecao=2&chamarVolta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.canvas_id.value = chave;
	env.submit();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 1000, 700, 'm=praticas&a=risco_pro_lista&dialogo=1&selecao=2&chamarVolta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.risco_id.value = chave;
	env.submit();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 1000, 700, 'm=praticas&a=risco_resposta_pro_lista&dialogo=1&selecao=2&chamarVolta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.risco_resposta_id.value = chave;
	env.submit();
	}
<?php }?>	
	
function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 1000, 700, 'm=sistema&u=calendario&a=calendario_lista&dialogo=1&selecao=2&chamarVolta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, 'Agenda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.calendario_id.value = chave;
	env.submit();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 1000, 700, 'm=praticas&a=monitoramento_lista_pro&dialogo=1&selecao=2&chamarVolta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.monitoramento_id.value = chave;
	env.submit();
	}	

function popAta() {
	parent.gpwebApp.popUp('Ata de Reuni?o', 1000, 700, 'm=atas&a=ata_lista&dialogo=1&selecao=2&chamarVolta=setAta&tabela=ata&cia_id='+document.getElementById('cia_id').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.ata_id.value = chave;
	env.submit();
	}	

function popMSWOT() {
	parent.gpwebApp.popUp('Matriz SWOT', 1000, 700, 'm=swot&a=mswot_lista&dialogo=1&selecao=2&chamarVolta=setMSWOT&tabela=mswot&cia_id='+document.getElementById('cia_id').value, window.setMSWOT, window);
	}

function setMSWOT(chave, valor){
	limpar_tudo();
	document.env.mswot_id.value = chave;
	env.submit();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('Campo SWOT', 1000, 700, 'm=swot&a=swot_lista&dialogo=1&selecao=2&chamarVolta=setSWOT&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.swot_id.value = chave;
	env.submit();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 1000, 700, 'm=operativo&a=operativo_lista&dialogo=1&selecao=2&chamarVolta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Plano Operativo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.operativo_id.value = chave;
	env.submit();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jur?dico', 1000, 700, 'm=instrumento&a=instrumento_lista&dialogo=1&selecao=2&chamarVolta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, 'Instrumento Jur?dico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.instrumento_id.value = chave;
	env.submit();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 1000, 700, 'm=recursos&a=index&dialogo=1&selecao=2&chamarVolta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, 'Recurso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.recurso_id.value = chave;
	env.submit();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 1000, 700, 'm=problema&a=problema_lista&dialogo=1&selecao=2&chamarVolta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.problema_id.value = chave;
	env.submit();
	}
<?php } ?>


<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 1000, 700, 'm=projetos&a=programa_pro_lista&dialogo=1&selecao=2&chamarVolta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.programa_id.value = chave;
	env.submit();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 1000, 700, 'm=projetos&a=licao_lista&dialogo=1&selecao=2&chamarVolta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.licao_id.value = chave;
	env.submit();
	}

function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 1000, 700, 'm=calendario&a=evento_lista_pro&dialogo=1&selecao=2&chamarVolta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, 'Evento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.evento_id.value = chave;
	env.submit();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 1000, 700, 'm=links&a=index&dialogo=1&selecao=2&chamarVolta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, 'Link','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.link_id.value = chave;
	env.submit();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avalia??o', 1000, 700, 'm=praticas&a=avaliacao_lista&dialogo=1&selecao=2&chamarVolta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, 'Avalia??o','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.avaliacao_id.value = chave;
	env.submit();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 1000, 700, 'm=praticas&a=tgn_pro_lista&dialogo=1&selecao=2&chamarVolta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.tgn_id.value = chave;
	env.submit();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 1000, 700, 'm=praticas&a=brainstorm_lista&dialogo=1&selecao=2&chamarVolta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, 'Brainstorm','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.brainstorm_id.value = chave;
	env.submit();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz GUT', 1000, 700, 'm=praticas&a=gut_lista&dialogo=1&selecao=2&chamarVolta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, 'Matriz GUT','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.gut_id.value = chave;
	env.submit();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 1000, 700, 'm=praticas&a=causa_efeito_lista&dialogo=1&selecao=2&chamarVolta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.causa_efeito_id.value = chave;
	env.submit();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 1000, 700, 'm=arquivos&a=index&dialogo=1&selecao=2&chamarVolta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setArquivo&tabela=arquivo&cia_id='+document.getElementById('cia_id').value, 'Arquivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.arquivo_id.value = chave;
	env.submit();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('F?rum', 1000, 700, 'm=foruns&a=index&dialogo=1&selecao=2&chamarVolta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, 'F?rum','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.forum_id.value = chave;
	env.submit();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 1000, 700, 'm=praticas&a=checklist_lista&dialogo=1&selecao=2&chamarVolta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.checklist_id.value = chave;
	env.submit();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 1000, 700, 'm=email&a=compromisso_lista_pro&dialogo=1&selecao=2&chamarVolta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, 'Compromisso','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.agenda_id.value = chave;
	env.submit();
	}
	
<?php  if ($Aplic->profissional) { ?>
	
	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 1000, 700, 'm=agrupamento&a=agrupamento_lista&dialogo=1&selecao=2&chamarVolta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, 'Agrupamento','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.agrupamento_id.value = chave;
		env.submit();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["patrocinador"])?>', 1000, 700, 'm=patrocinadores&a=index&dialogo=1&selecao=2&chamarVolta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["patrocinador"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.patrocinador_id.value = chave;
		env.submit();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 1000, 700, 'm=projetos&a=template_pro_lista&dialogo=1&selecao=2&chamarVolta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, 'Modelo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.template_id.value = chave;
		env.submit();
		}	
		
	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 1000, 700, 'm=praticas&a=painel_pro_lista&dialogo=1&selecao=2&chamarVolta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, 'Painel','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setPainel(chave, valor){
		limpar_tudo();
		document.env.painel_id.value = chave;
		env.submit();
		}		
		
	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Od?metro', 1000, 700, 'm=praticas&a=odometro_pro_lista&dialogo=1&selecao=2&chamarVolta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, 'Od?metro','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.painel_odometro_id.value = chave;
		env.submit();
		}			
		
	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composi??o de Pain?is', 1000, 700, 'm=praticas&a=painel_composicao_pro_lista&dialogo=1&selecao=2&chamarVolta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, 'Composi??o de Pain?is','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}
	
	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.painel_composicao_id.value = chave;
		env.submit();
		}	
		
	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 1000, 700, 'm=tr&a=tr_lista&dialogo=1&selecao=2&chamarVolta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.tr_id.value = chave;
		env.submit();
		}	
			
	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 1000, 700, 'm=praticas&a=me_lista_pro&dialogo=1&selecao=2&chamarVolta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.me_id.value = chave;
		env.submit();
		}		
		
	function popDemanda() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 1000, 700, 'm=projetos&a=demanda_lista&dialogo=1&selecao=2&chamarVolta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, window.setDemanda, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, 'Demanda','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setDemanda(chave, valor){
		limpar_tudo();
		document.env.demanda_id.value = chave;
		env.submit();
		}		

	function popAcaoItem() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Item de <?php echo ucfirst($config["acao"])?>', 1000, 700, 'm=praticas&a=plano_acao_itens_lista&dialogo=1&selecao=2&chamarVolta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('cia_id').value, window.setAcaoItem, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAcaoItem&tabela=plano_acao_item&cia_id='+document.getElementById('cia_id').value, 'Item de <?php echo ucfirst($config["acao"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setAcaoItem(chave, valor){
		limpar_tudo();
		document.env.plano_acao_item_id.value = chave;
		env.submit();
		}		
	
	function popBeneficio() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["beneficio"])?>', 1000, 700, 'm=projetos&a=beneficio_pro_lista&dialogo=1&selecao=2&chamarVolta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('cia_id').value, window.setBeneficio, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setBeneficio&tabela=beneficio&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["beneficio"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setBeneficio(chave, valor){
		limpar_tudo();
		document.env.beneficio_id.value = chave;
		env.submit();
		}	

	function popSlideshow() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Slideshow de Composi??es', 1000, 700, 'm=praticas&a=painel_slideshow_pro_lista&dialogo=1&selecao=2&chamarVolta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('cia_id').value, window.setSlideshow, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setSlideshow&tabela=painel_slideshow&cia_id='+document.getElementById('cia_id').value, 'Slideshow de Composi??es','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setSlideshow(chave, valor){
		limpar_tudo();
		document.env.painel_slideshow_id.value = chave;
		env.submit();
		}	

	function popViabilidade() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Estudo de Viabilidade', 1000, 700, 'm=projetos&a=viabilidade_lista&dialogo=1&selecao=2&chamarVolta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('cia_id').value, window.setViabilidade, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setViabilidade&tabela=projeto_viabilidade&cia_id='+document.getElementById('cia_id').value, 'Estudo de Viabilidade','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setViabilidade(chave, valor){
		limpar_tudo();
		document.env.projeto_viabilidade_id.value = chave;
		env.submit();
		}	
		
	function popAbertura() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Termo de Abertura', 1000, 700, 'm=projetos&a=termo_abertura_lista&dialogo=1&selecao=2&chamarVolta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('cia_id').value, window.setAbertura, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setAbertura&tabela=projeto_abertura&cia_id='+document.getElementById('cia_id').value, 'Termo de Abertura','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setAbertura(chave, valor){
		limpar_tudo();
		document.env.projeto_abertura_id.value = chave;
		env.submit();
		}		
		
	function popPlanejamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Planejamento Estrat?gico', 1000, 700, 'm=praticas&u=gestao&a=gestao_lista&dialogo=1&selecao=2&chamarVolta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('cia_id').value, window.setPlanejamento, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPlanejamento&tabela=plano_gestao&cia_id='+document.getElementById('cia_id').value, 'Planejamento Estrat?gico','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
		}

	function setPlanejamento(chave, valor){
		limpar_tudo();
		document.env.pg_id.value = chave;
		env.submit();
		}		
		
		
		
	function popSSTI() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["ssti"])?>', 1000, 700, 'm=ssti&a=ssti_lista&dialogo=1&selecao=2&chamarVolta=setSSTI&tabela=ssti&cia_id='+document.getElementById('cia_id').value, window.setSSTI, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setSSTI&tabela=ssti&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["ssti"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setSSTI(chave, valor){
		limpar_tudo();
		document.env.ssti_id.value = chave;
		env.submit();
		}	
					
	function popLaudo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["laudo"])?>', 1000, 700, 'm=ssti&a=laudo_lista&dialogo=1&selecao=2&chamarVolta=setLaudo&tabela=laudo&cia_id='+document.getElementById('cia_id').value, window.setLaudo, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setLaudo&tabela=laudo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["laudo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setLaudo(chave, valor){
		limpar_tudo();
		document.env.laudo_id.value = chave;
		env.submit();
		}		
		
	function popTrelo() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo"])?>', 1000, 700, 'm=trelo&a=trelo_lista&dialogo=1&selecao=2&chamarVolta=setTrelo&tabela=trelo&cia_id='+document.getElementById('cia_id').value, window.setTrelo, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTrelo&tabela=trelo&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["trelo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTrelo(chave, valor){
		limpar_tudo();
		document.env.trelo_id.value = chave;
		env.submit();
		}	
		
	function popTreloCartao() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["trelo_cartao"])?>', 1000, 700, 'm=trelo&a=cartao_lista&dialogo=1&selecao=2&chamarVolta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('cia_id').value, window.setTreloCartao, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setTreloCartao&tabela=trelo_cartao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["trelo_cartao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTreloCartao(chave, valor){
		limpar_tudo();
		document.env.trelo_cartao_id.value = chave;
		env.submit();
		}	
		
	function popPDCL() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl"])?>', 1000, 700, 'm=pdcl&a=pdcl_lista&dialogo=1&selecao=2&chamarVolta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('cia_id').value, window.setPDCL, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=setPDCL&tabela=pdcl&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pdcl"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPDCL(chave, valor){
		limpar_tudo();
		document.env.pdcl_id.value = chave;
		env.submit();
		}				
		
	function pop_pdcl_item() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pdcl_item"])?>', 1000, 700, 'm=pdcl&a=pdcl_item_lista&dialogo=1&selecao=2&chamarVolta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('cia_id').value, window.set_pdcl_item, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=set_pdcl_item&tabela=pdcl_item&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pdcl_item"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function set_pdcl_item(chave, valor){
		limpar_tudo();
		document.env.pdcl_item_id.value = chave;
		env.submit();
		}	
	
	function pop_os() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["os"])?>', 1000, 700, 'm=os&a=os_lista&dialogo=1&selecao=2&chamarVolta=set_os&tabela=os&cia_id='+document.getElementById('cia_id').value, window.set_os, window);
		else window.open('./index.php?m=publico&a=selecionar_multiplo&dialogo=1&chamar_volta=set_os&tabela=os&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["os"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function set_os(chave, valor){
		limpar_tudo();
		document.env.os_id.value = chave;
		env.submit();
		}			

<?php } ?>		
</script>	
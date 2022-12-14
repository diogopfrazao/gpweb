<?php 
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');


if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic, $tarefa_id, 
	$projeto_id, 
	$pg_perspectiva_id, 
	$tema_id, 
	$objetivo_id, 
	$fator_id, 
	$pg_estrategia_id,
	$pg_meta_id, 
	$pratica_id, 
	$pratica_indicador_id, 
	$plano_acao_id, 
	$canvas_id, 
	$risco_id,
	$risco_resposta_id,
	$calendario_id, 
	$monitoramento_id, 
	$ata_id, 
	$mswot_id, 
	$swot_id, 
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id;
$q = new BDConsulta;
$q->adTabela('foruns');
$q->adCampo('forum_id, forum_descricao, forum_dono, forum_nome, forum_contagem_msg, forum_ultima_data');
if ($pratica_id) $q->adOnde('forum_pratica = '.(int)$pratica_id);
elseif ($pratica_indicador_id) $q->adOnde('forum_indicador = '.(int)$pratica_indicador_id);
elseif ($fator_id) $q->adOnde('forum_fator = '.(int)$fator_id);
elseif ($pg_meta_id) $q->adOnde('forum_fator = '.(int)$pg_meta_id);
elseif ($pg_perspectiva_id) $q->adOnde('forum_perspectiva = '.(int)$pg_perspectiva_id);
elseif ($canvas_id) $q->adOnde('forum_canvas = '.(int)$canvas_id);
elseif ($tema_id) $q->adOnde('forum_tema = '.(int)$tema_id);
elseif ($objetivo_id) $q->adOnde('forum_objetivo = '.(int)$objetivo_id);
elseif ($pg_estrategia_id) $q->adOnde('forum_estrategia = '.(int)$pg_estrategia_id);
elseif ($plano_acao_id) $q->adOnde('forum_acao = '.(int)$plano_acao_id);
$q->adOrdem('forum_nome');
$rc=$q->Lista();
$q->limpar();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr><th style="white-space: nowrap" width="16">&nbsp;</th>';
echo '<th style="white-space: nowrap">'.dica('Assunto', 'Assunto do f?rum.').'Assunto'.dicaF().'</th><th>'.dica('Descri??o', 'Descri??o do f?rum.').'Descri??o'.dicaF().'</th><th style="white-space: nowrap">'.dica('N?mero de Mensagens', 'N?mero de mensagens contidas neste f?rum.').'Msg'.dicaF().'</th><th style="white-space: nowrap">'.dica('?ltima Postagem', '?ltima postagem de mensagem neste f?rum.').'?ltima Postagem'.dicaF().'</th></tr>';


$qnt=0;
foreach ($rc as $linha)	{  
	$qnt++;
	$data = new CData($linha['forum_ultima_data']); 
	echo '<tr><td style="white-space: nowrap" align="center">'.( $linha["forum_dono"] == $Aplic->usuario_id ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=editar&forum_id='.$linha['forum_id'].'\');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ?cone '.imagem('icones/editar.gif').' para editar o f?rum.').'</a>' : '').'</td>';
	echo '<td style="white-space: nowrap">'.link_forum($linha['forum_id']).'</td><td>'.($linha['forum_descricao'] ? $linha['forum_descricao'] : '&nbsp;').'</td>';
	echo '<td style="white-space: nowrap" align="center" width="40">'.($linha['forum_contagem_msg'] ? $linha['forum_contagem_msg'] : 0).'</td>';
	echo '<td style="white-space: nowrap"  width="120" align="center">'.(intval($linha['forum_ultima_data']) > 0 ? $data->format('%d/%m/%Y %H:%M') : 'n/d').'</td></tr>';
	}
if (!$qnt) echo '<tr><td colspan="7"><p>Nenhum f?rum encontrado.</p></td></tr>';
echo '</table>';
?>

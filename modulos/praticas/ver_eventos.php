<?php 
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $config, $data_inicio, $data_fim,
    $m, $a, $u, $tab,
	$projeto_id,
	$tarefa_id,
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
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id,
	$os_id;

require_once $Aplic->getClasseModulo('calendario');

$usuario_id = $Aplic->usuario_id;

$eventos = CEvento::getEventoParaPeriodo($data_inicio, $data_fim, 'todos', null,null,null,null,
	$projeto_id,
	$tarefa_id,
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
	$template_id,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id,
	$plano_acao_item_id,
	$beneficio_id,
	$painel_slideshow_id,
	$projeto_viabilidade_id,
	$projeto_abertura_id,
	$pg_id,
	$ssti_id,
	$laudo_id,
	$trelo_id,
	$trelo_cartao_id,
	$pdcl_id,
	$pdcl_item_id,
	$os_id);

$tipos = getSisValor('TipoEvento');
echo '<table cellspacing=0 cellpadding="2" border=0 width="100%" class="tbl1">';
echo '<tr><th>'.dica('Inicio', 'A data e hora do in�cio t�rmino do evento.').'In�cio'.dicaF().'</th>';
echo '<th>'.dica('T�rmino', 'A data e hora do t�rmino do evento.').'T�rmino'.dicaF().'</th>';
echo '<th>'.dica('Tipo', 'O tipo de evento.').'Tipo'.dicaF().'</th><th>'.dica('Evento', 'O nome do evento.').'Evento'.dicaF().'</th>';
if ($Aplic->profissional)echo '<th>'.dica('Relacionado', 'A quais partes do sistema o evento est� relacionado.').'Relacionado'.dicaF().'</th>';
echo '</tr>';
$qnt=0;


foreach ($eventos as $linha) {
	$qnt++;
	echo '<tr>';

	echo '<td width=50 style="white-space: nowrap">'.retorna_data($linha['evento_inicio']).'</td>';
	echo '<td width=50 style="white-space: nowrap">'.retorna_data($linha['evento_fim']).'</td>';
	echo '<td width="10%" style="white-space: nowrap">'.imagem('icones/evento'.$linha['evento_tipo'].'.png', 'Tipo de Evento', 'Cada evento tem um gr�fico diferente para facilitar a identifica��o visual.').'&nbsp;<b>'.$tipos[$linha['evento_tipo']].'</b></td>';
	echo '<td>'.link_evento($linha['evento_id']).'</td>';
	
	
	
	
	if ($Aplic->profissional){
		$sql = new BDConsulta;
		$sql->adTabela('evento_gestao');
		$sql->adCampo('evento_gestao.*');
		$sql->adOnde('evento_gestao_evento ='.(int)$linha['evento_id']);
		$sql->adOrdem('evento_gestao_ordem');
	  $lista = $sql->Lista();
	  $sql->limpar();
		echo '<td>';
		$qnt_gestao=0;
		foreach($lista as $gestao_data){
			if ($gestao_data['evento_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['evento_gestao_tarefa']);
			elseif ($gestao_data['evento_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['evento_gestao_projeto']);
			elseif ($gestao_data['evento_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['evento_gestao_pratica']);
			elseif ($gestao_data['evento_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['evento_gestao_acao']);
			elseif ($gestao_data['evento_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['evento_gestao_perspectiva']);
			elseif ($gestao_data['evento_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['evento_gestao_tema']);
			elseif ($gestao_data['evento_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['evento_gestao_objetivo']);
			elseif ($gestao_data['evento_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['evento_gestao_fator']);
			elseif ($gestao_data['evento_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['evento_gestao_estrategia']);
			elseif ($gestao_data['evento_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['evento_gestao_meta']);
			elseif ($gestao_data['evento_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['evento_gestao_canvas']);
			elseif ($gestao_data['evento_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['evento_gestao_risco']);
			elseif ($gestao_data['evento_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['evento_gestao_risco_resposta']);
			elseif ($gestao_data['evento_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['evento_gestao_indicador']);
			elseif ($gestao_data['evento_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agenda_p.png').link_calendario($gestao_data['evento_gestao_calendario']);
			elseif ($gestao_data['evento_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['evento_gestao_monitoramento']);
			elseif ($gestao_data['evento_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata_pro($gestao_data['evento_gestao_ata']);
			elseif ($gestao_data['evento_gestao_mswot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/mswot_p.png').link_mswot($gestao_data['evento_gestao_mswot']);
			elseif ($gestao_data['evento_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/swot_p.png').link_swot($gestao_data['evento_gestao_swot']);
			elseif ($gestao_data['evento_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['evento_gestao_operativo']);
			elseif ($gestao_data['evento_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['evento_gestao_instrumento']);
			elseif ($gestao_data['evento_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['evento_gestao_recurso']);
			elseif ($gestao_data['evento_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema($gestao_data['evento_gestao_problema']);
			elseif ($gestao_data['evento_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['evento_gestao_demanda']);	
			elseif ($gestao_data['evento_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['evento_gestao_programa']);
			elseif ($gestao_data['evento_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['evento_gestao_licao']);
			
			elseif ($gestao_data['evento_gestao_semelhante']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['evento_gestao_semelhante']);
			
			elseif ($gestao_data['evento_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['evento_gestao_link']);
			elseif ($gestao_data['evento_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['evento_gestao_avaliacao']);
			elseif ($gestao_data['evento_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['evento_gestao_tgn']);
			elseif ($gestao_data['evento_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm($gestao_data['evento_gestao_brainstorm']);
			elseif ($gestao_data['evento_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut($gestao_data['evento_gestao_gut']);
			elseif ($gestao_data['evento_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito($gestao_data['evento_gestao_causa_efeito']);
			elseif ($gestao_data['evento_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['evento_gestao_arquivo']);
			elseif ($gestao_data['evento_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['evento_gestao_forum']);
			elseif ($gestao_data['evento_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['evento_gestao_checklist']);
			elseif ($gestao_data['evento_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/compromisso_p.png').link_agenda($gestao_data['evento_gestao_agenda']);
			elseif ($gestao_data['evento_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/agrupamento_p.png').link_agrupamento($gestao_data['evento_gestao_agrupamento']);
			elseif ($gestao_data['evento_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/patrocinador_p.gif').link_patrocinador($gestao_data['evento_gestao_patrocinador']);
			elseif ($gestao_data['evento_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/template_p.gif').link_template($gestao_data['evento_gestao_template']);
			elseif ($gestao_data['evento_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.png').link_painel($gestao_data['evento_gestao_painel']);
			elseif ($gestao_data['evento_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['evento_gestao_painel_odometro']);
			elseif ($gestao_data['evento_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/composicao_p.gif').link_painel_composicao($gestao_data['evento_gestao_painel_composicao']);		
			elseif ($gestao_data['evento_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['evento_gestao_tr']);	
			elseif ($gestao_data['evento_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['evento_gestao_me']);	
			elseif ($gestao_data['evento_gestao_acao_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/acao_item_p.png').link_acao_item($gestao_data['evento_gestao_acao_item']);	
			elseif ($gestao_data['evento_gestao_beneficio']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/beneficio_p.png').link_beneficio($gestao_data['evento_gestao_beneficio']);	
			elseif ($gestao_data['evento_gestao_painel_slideshow']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/slideshow_p.gif').link_painel_slideshow($gestao_data['evento_gestao_painel_slideshow']);	
			elseif ($gestao_data['evento_gestao_projeto_viabilidade']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/viabilidade_p.gif').link_viabilidade($gestao_data['evento_gestao_projeto_viabilidade']);	
			elseif ($gestao_data['evento_gestao_projeto_abertura']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/anexo_projeto_p.png').link_termo_abertura($gestao_data['evento_gestao_projeto_abertura']);	
			elseif ($gestao_data['evento_gestao_plano_gestao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/planogestao_p.png').link_plano_gestao($gestao_data['evento_gestao_plano_gestao']);
			elseif ($gestao_data['evento_gestao_ssti']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ssti_p.png').link_ssti($gestao_data['evento_gestao_ssti']);	
			elseif ($gestao_data['evento_gestao_laudo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/laudo_p.png').link_laudo($gestao_data['evento_gestao_laudo']);	
			elseif ($gestao_data['evento_gestao_trelo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_p.png').link_trelo($gestao_data['evento_gestao_trelo']);	
			elseif ($gestao_data['evento_gestao_trelo_cartao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/trelo_cartao_p.png').link_trelo_cartao($gestao_data['evento_gestao_trelo_cartao']);	
			elseif ($gestao_data['evento_gestao_pdcl']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_p.png').link_pdcl($gestao_data['evento_gestao_pdcl']);	
			elseif ($gestao_data['evento_gestao_pdcl_item']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pdcl_item_p.png').link_pdcl_item($gestao_data['evento_gestao_pdcl_item']);	
			elseif ($gestao_data['evento_gestao_os']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/os_p.png').link_os($gestao_data['evento_gestao_os']);	
			
			
			}
		echo '</td>';
		}
	echo '</tr>';
	}
if (!$qnt) echo '<tr><td colspan="3"><p>Nenhum evento encontrado.</p></td></tr>';	
echo '</table>';

?>
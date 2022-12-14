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
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic, $tarefa_id;
$q = new BDConsulta;
$q->adTabela('foruns');
$q->esqUnir('projetos', 'p', 'projeto_id = forum_projeto');
$q->adCampo('forum_id, forum_projeto, forum_descricao, forum_dono, forum_nome, forum_contagem_msg, forum_ultima_data,	projeto_nome, projeto_cor, projeto_id');
$q->adOnde('forum_tarefa = '.(int)$tarefa_id);
$q->adOrdem('forum_projeto, forum_nome');
$rc=$q->Lista();
$q->limpar();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr><th style="white-space: nowrap">&nbsp;</th>';
echo '<th style="white-space: nowrap">'.dica('Assunto', 'Assunto do f?rum.').'Assunto'.dicaF().'</th><th>'.dica('Descri??o', 'Descri??o do f?rum.').'Descri??o'.dicaF().'</th><th style="white-space: nowrap">'.dica('N?mero de '.ucfirst($config['mensagens']).'', 'N?mero de mensagens cont?das neste f?rum.').'Msg'.dicaF().'</th><th style="white-space: nowrap">'.dica('?ltima Postagem', '?ltima postagem de mensagem neste f?rum.').'?ltima Postagem'.dicaF().'</th></tr>';


$qnt=0;
foreach ($rc as $linha)	{  
	$qnt++;
	$data = new CData($linha['forum_ultima_data']); 
	echo '<tr><td style="white-space: nowrap" align="center" width="10">'.( $linha["forum_dono"] == $Aplic->usuario_id ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=editar&forum_id='.$linha['forum_id'].'\');"><img src="'.acharImagem('icones/editar.gif').'" alt="expandir forum" border=0 width=12 height=12></a>' : '').'</td>';
	echo '<td style="white-space: nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$linha["forum_id"].'\');">'.$linha['forum_nome'].'</a></td><td>'.$linha['forum_descricao'].'</td>';
	echo '<td style="white-space: nowrap">'.$linha['forum_contagem_msg'].'</td>';
	echo '<td style="white-space: nowrap">'.(intval($linha['forum_ultima_data']) > 0 ? $data->format('%d/%m/%Y %H:%M') : 'n/d').'</td></tr>';
	}
if (!$qnt) echo '<tr><td colspan="7"><p>Nenhum f?rum encontrado.</p></td></tr>';
echo '</table>';
?>

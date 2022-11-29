<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de Metas</h1></td></tr>'; 
//metas

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>Meta</b></td><td></td></tr>';

	echo '<tr><td><input type="text" name="pg_meta_nome" id="pg_meta_nome" style="width:400px;" class="texto" value=""></td>';
	echo '<td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste �cone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td>';
	
	echo '</tr></table></td></tr>';
	}

echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="" />';

echo '<tr><td colspan=20><div id="combo_metas">';

$sql->adTabela('plano_gestao_metas');
$sql->esqUnir('metas', 'metas', 'plano_gestao_metas.pg_meta_id=metas.pg_meta_id');
$sql->adCampo('pg_meta_nome, pg_meta_cor, plano_gestao_metas.pg_meta_ordem, metas.pg_meta_id');
$sql->adOnde('plano_gestao_metas.pg_id='.(int)$pg_id);
$sql->adOrdem('pg_meta_ordem ASC');
$metas=$sql->Lista();

if (count($metas)) echo '<table class="tbl1" cellspacing=0 cellpadding=0><tr>'.($editarPG ? '<th></th>' : '').'<th style="white-space: nowrap">'.ucfirst($config['meta']).'</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($metas as $meta) {
	echo'<tr>';
	if ($editarPG){
		echo' <td td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_meta('.$meta['pg_meta_ordem'].', '.$meta['pg_meta_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_meta('.$meta['pg_meta_ordem'].', '.$meta['pg_meta_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_meta('.$meta['pg_meta_ordem'].', '.$meta['pg_meta_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_meta('.$meta['pg_meta_ordem'].', '.$meta['pg_meta_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td>'.$meta['pg_meta_nome'].'</td>';
	if ($editarPG)	echo '<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_meta('.$meta['pg_meta_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
	echo '</tr>';
	}
if (count($metas)) echo '</table>';


echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'metas_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>&nbsp;</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';



?>

<script type="text/javascript">
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 1000, 700, 'm=praticas&a=meta_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}	

function setMeta(chave, valor){
	if (chave > 0) {
		xajax_inserir_meta(chave, document.getElementById('pg_id').value);
		__buildTooltip();	
		}
	}	
	
function mudar_ordem_meta(ordem, pg_meta_id, direcao){
	xajax_mudar_ordem_meta(ordem, pg_meta_id, direcao, document.getElementById('pg_id').value);
	__buildTooltip();
	}

function excluir_meta(pg_meta_id){
	xajax_excluir_meta(pg_meta_id, document.getElementById('pg_id').value);
	__buildTooltip();
	}	
	
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_meta_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_meta_cor.value;
	}
</script>
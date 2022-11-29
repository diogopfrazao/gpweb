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
echo '<tr><td colspan=2 align="left"><h1>Lista de '.ucfirst($config['objetivos']).'</h1></td></tr>'; 
//objetivo

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>'.ucfirst($config['objetivo']).'</b></td><td></td></tr>';

	echo '<tr><td><input type="text" name="objetivo_nome" id="objetivo_nome" style="width:400px;" class="texto" value=""></td>';
	echo '<td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste �cone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td>';
	
	echo '</tr></table></td></tr>';
	}



echo '<input type="hidden" name="objetivo_id" id="objetivo_id" value="" />';

echo '<tr><td colspan=20><div id="combo_objetivos">';

$sql->adTabela('plano_gestao_objetivo');
$sql->esqUnir('objetivo', 'objetivo', 'plano_gestao_objetivo_objetivo=objetivo.objetivo_id');
$sql->adCampo('objetivo_nome, objetivo_cor, plano_gestao_objetivo_ordem, objetivo.objetivo_id');
$sql->adOnde('plano_gestao_objetivo_plano_gestao='.(int)$pg_id);
$sql->adOrdem('plano_gestao_objetivo_ordem ASC');
$objetivos=$sql->Lista();

if (count($objetivos)) echo '<table class="tbl1" cellspacing=0 cellpadding=0><tr>'.($editarPG ? '<th></th>' : '').'<th style="white-space: nowrap">'.ucfirst($config['objetivo']).'</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($objetivos as $objetivo) {
	echo'<tr>';
	if ($editarPG){
		echo' <td td style="white-space: nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_objetivo('.$objetivo['plano_gestao_objetivo_ordem'].', '.$objetivo['objetivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_objetivo('.$objetivo['plano_gestao_objetivo_ordem'].', '.$objetivo['objetivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_objetivo('.$objetivo['plano_gestao_objetivo_ordem'].', '.$objetivo['objetivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_ordem_objetivo('.$objetivo['plano_gestao_objetivo_ordem'].', '.$objetivo['objetivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		}
	echo '<td>'.$objetivo['objetivo_nome'].'</td>';
	if ($editarPG)	echo '<td width="16" align="center"><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_objetivo('.$objetivo['objetivo_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td>';
	echo '</tr>';
	}
if (count($objetivos)) echo '</table>';

echo '</div></td></tr>';






echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'objetivo_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('pr�ximo', 'Pr�ximo', 'Ir para a pr�xima tela.','','carregar(\''.($Aplic->profissional && $config['exibe_me']  && $Aplic->checarModulo('praticas', 'acesso', null, 'me') ? 'mes_pro' : (!$Aplic->profissional || ($Aplic->profissional && $Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'acesso', null, 'fator')) ? 'fator_geral' : 'estrategias_geral')).'\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';



?>

<script type="text/javascript">
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 1000, 700, 'm=praticas&a=obj_estrategico_lista&dialogo=1&edicao=0&selecao=1&chamarVolta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivo&cia_id='+document.getElementById('cia_id').value, 'Objetivo','left=0,top=0,height=700,width=700,scrollbars=yes, resizable=yes');
	}		
	

function setObjetivo(chave, valor){
	if (chave > 0) {
		xajax_inserir_objetivo(chave, document.getElementById('pg_id').value);
		__buildTooltip();	
		}
	}	
	
function mudar_ordem_objetivo(ordem, objetivo_id, direcao){
	xajax_mudar_ordem_objetivo(ordem, objetivo_id, direcao, document.getElementById('pg_id').value);
	__buildTooltip();
	}

function excluir_objetivo(objetivo_id){
	xajax_excluir_objetivo(objetivo_id, document.getElementById('pg_id').value);
	__buildTooltip();
	}		
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.objetivo_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.objetivo_cor.value;
	}
</script>
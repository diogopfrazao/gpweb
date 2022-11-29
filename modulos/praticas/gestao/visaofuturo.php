<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $config;
echo '<script type="text/javascript" src="'.BASE_URL.'/js/jscolor.js"></script>';
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
if ($editarPG) {
	$Aplic->carregarCKEditorJS();
	}

$direcao=getParam($_REQUEST, 'cmd', '');
$plano_gestao_arquivo_plano_gestao=getParam($_REQUEST, 'plano_gestao_arquivo_plano_gestao', '0');
$ordem=getParam($_REQUEST, 'ordem', '0');

echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="plano_gestao_arquivo_id" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';

if($direcao) {
		$novo_ui_ordem = $ordem;
		$q = new BDConsulta;
		$q->adTabela('plano_gestao_arquivo');
		$q->adOnde('plano_gestao_arquivo_id !='.(int)$plano_gestao_arquivo_id);
		$q->adOnde('plano_gestao_arquivo_plano_gestao ='.(int)$pg_id);
		$q->adOnde('plano_gestao_arquivo_campo = \'Visao\'');
		$q->adOrdem('plano_gestao_arquivo_ordem');
		$arquivos = $q->Lista();
		$q->limpar();
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			}
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			}
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			}
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($arquivos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($arquivos) + 1)) {
			$q = new BDConsulta;
			$q->adTabela('plano_gestao_arquivo');
			$q->adAtualizar('plano_gestao_arquivo_ordem', $novo_ui_ordem);
			$q->adOnde('plano_gestao_arquivo_id ='.(int)$plano_gestao_arquivo_id);
			$q->exec();
			$q->limpar();
			$idx = 1;
			foreach ($arquivos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$q->adTabela('plano_gestao_arquivo');
					$q->adAtualizar('plano_gestao_arquivo_ordem', $idx);
					$q->adOnde('plano_gestao_arquivo_id ='.(int)$acao['plano_gestao_arquivo_id']);
					$q->exec();
					$q->limpar();
					$idx++;
					}
				else {
					$q->adTabela('plano_gestao_arquivo');
					$q->adAtualizar('plano_gestao_arquivo_ordem', $idx + 1);
					$q->adOnde('plano_gestao_arquivo_id ='.(int)$acao['plano_gestao_arquivo_id']);
					$q->exec();
					$q->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}


if ($excluiranexo){

	$sql->adTabela('plano_gestao_arquivo');
	$sql->adCampo('plano_gestao_arquivo_endereco, plano_gestao_arquivo_local, plano_gestao_arquivo_nome_real');
	$sql->adOnde('plano_gestao_arquivo_id='.(int)$plano_gestao_arquivo_id);
	$caminho=$sql->linha();
	$sql->limpar();
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	if ($arquivo['plano_gestao_arquivo_local']) @unlink($base_dir.'/arquivos/'.$arquivo['plano_gestao_arquivo_local'].$arquivo['plano_gestao_arquivo_nome_real']);
	else @unlink($base_dir.'/arquivos/gestao/'.$arquivo['plano_gestao_arquivo_endereco']);

	$sql->setExcluir('plano_gestao_arquivo');
	$sql->adOnde('plano_gestao_arquivo_id='.(int)$plano_gestao_arquivo_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela plano_gestao_arquivo!'.$bd->stderr(true));
	$sql->limpar();
	}


if ($salvaranexo){
	grava_arquivo_pg($pg_id, 'arquivo', 'Visao');
	}


if ($salvar){
	//checar se existe
	$sql->adTabela('plano_gestao2');
	$sql->adCampo('count(pg_id)');
	$sql->adOnde('pg_id='.(int)$pg_id);
	$existe=$sql->Resultado();
	$sql->limpar();
	if ($existe) {
		$sql->adTabela('plano_gestao2');
		$sql->adAtualizar('pg_visao_futuro', getParam($_REQUEST, 'pg_visao_futuro', ''));
		$sql->adAtualizar('pg_visao_futuro_detalhada', getParam($_REQUEST, 'pg_visao_futuro_detalhada', ''));
		$sql->adAtualizar('pg_visao_futuro_cor', getParam($_REQUEST, 'pg_visao_futuro_cor', 'c9deae'));
		$sql->adOnde('pg_id ='.(int)$pg_id);
		$retorno=$sql->exec();
		$sql->limpar();
		}
	else {
		$sql->adTabela('plano_gestao2');
		$sql->adInserir('pg_visao_futuro', getParam($_REQUEST, 'pg_visao_futuro', ''));
		$sql->adInserir('pg_visao_futuro_detalhada', getParam($_REQUEST, 'pg_visao_futuro_detalhada', ''));
		$sql->adInserir('pg_visao_futuro_cor', getParam($_REQUEST, 'pg_visao_futuro_cor', 'c9deae'));
		$sql->adInserir('pg_id', (int)$pg_id);
		$sql->exec();
		$sql->limpar();
		}		
	}


$sql->adTabela('plano_gestao2');
$sql->adCampo('pg_visao_futuro, pg_visao_futuro_detalhada, pg_visao_futuro_cor');
$sql->adOnde('pg_id='.(int)$pg_id);
$pg=$sql->Linha();
$sql->limpar();

echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Vis�o de Futuro</h1></td></tr>';

if ($editarPG || $pg['pg_visao_futuro']) echo '<tr><td colspan=2 align="left"><b>Vis�o de futuro d'.$config['genero_organizacao'].' '.$config['organizacao'].'</b></td></tr>';
if ($editarPG) echo '<tr><td colspan=2 align="left"><table width="810"><tr><td style="width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="pg_visao_futuro" id="pg_visao_futuro" >'.$pg['pg_visao_futuro'].'</textarea></td></tr></table></td></tr>';
elseif($pg['pg_visao_futuro'])  echo '<tr><td colspan=2 align="left"><table width="100%"><tr><td class="realce" width="100%">'.$pg['pg_visao_futuro'].'</td></tr></table></td></tr>';


if ($editarPG || $pg['pg_visao_futuro_detalhada']) echo '<tr><td colspan=2 align="left"><b>Detalhamento da vis�o de futuro d'.$config['genero_organizacao'].' '.$config['organizacao'].'</b></td></tr>';
if ($editarPG) echo '<tr><td colspan=2 align="left"><table width="810"><tr><td style="width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="pg_visao_futuro_detalhada" id="pg_visao_futuro_detalhada" >'.$pg['pg_visao_futuro_detalhada'].'</textarea></td></tr></table></td></tr>';
elseif($pg['pg_visao_futuro_detalhada'])  echo '<tr><td colspan=2 align="left"><table width="100%"><tr><td class="realce" width="100%">'.$pg['pg_visao_futuro_detalhada'].'</td></tr></table></td></tr>';

if ($editarPG) echo '<tr><td align="right" style="white-space: nowrap">'.dica('Cor', 'Cor selecionada dentre as 16 milh�es poss�veis. Pode-se escrever diretamente o hexadecinal na cor ou utilizar a interface que se abre ao clicar na caixa de inser��o do valor.').'Cor:'.dicaF().'</td><td align="left" style="white-space: nowrap"><input class="jscolor" name="pg_visao_futuro_cor" value="'.($pg['pg_visao_futuro_cor'] ? $pg['pg_visao_futuro_cor'] : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="6" maxlength="6" style="width:57px;" /></td></tr>';

//arquivo anexo
$sql->adTabela('plano_gestao_arquivo');
$sql->adCampo('plano_gestao_arquivo_id, plano_gestao_arquivo_usuario, plano_gestao_arquivo_data, plano_gestao_arquivo_ordem, plano_gestao_arquivo_plano_gestao,plano_gestao_arquivo_nome, plano_gestao_arquivo_endereco');
$sql->adOnde('plano_gestao_arquivo_plano_gestao='.(int)$pg_id);
$sql->adOnde('plano_gestao_arquivo_campo=\'Visao\'');
$sql->adOrdem('plano_gestao_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();
if ($arquivos && count($arquivos))echo '<tr><td colspan=2><b>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</b></td></tr>';
foreach ($arquivos as $arquivo) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Remetente</b></td><td>'.nome_funcao('', '', '', '',$arquivo['plano_gestao_arquivo_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['plano_gestao_arquivo_data']).'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
	echo '<tr><td colspan=2><table cellpadding=0 cellspacing=0><tr>';
	if ($editarPG) {
			echo '<td style="white-space: nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['plano_gestao_arquivo_ordem'].'; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['plano_gestao_arquivo_ordem'].'; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['plano_gestao_arquivo_ordem'].'; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$arquivo['plano_gestao_arquivo_ordem'].'; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td><a href="javascript:void(0);" onclick="javascript:pg_download('.(int)$arquivo['plano_gestao_arquivo_id'].');">'.dica($arquivo['plano_gestao_arquivo_nome'],$dentro).$arquivo['plano_gestao_arquivo_nome'].'</a></td>';
	if ($editarPG) echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {env.excluiranexo.value=1; env.plano_gestao_arquivo_id.value='.(int)$arquivo['plano_gestao_arquivo_id'].'; env.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste �cone para excluir o arquivo.').'</a></td>';
	echo '</tr></table></td></tr>';
	}
if ($editarPG) echo'<tr><td colspan=2><table><tr><td><b>Arquivo:</b></td><td><input type="file" class="arquivo" name="arquivo" size="30"></td><td width="720">'.($editarPG ? botao('salvar arquivo', 'Salvar Arquivo', 'Clique neste bot�o para enviar arquivo e salvar o mesmo no sistema.','','env.salvaranexo.value=1; env.submit()') : '&nbsp').'</td></tr></table></td></tr>';


echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'missao\');').'</td><td width="40%">&nbsp;</td><td>'.($editarPG ? botao('salvar', 'Salvar', 'Salvar os dados acima.','','env.salvar.value=1; env.submit();') : '&nbsp').'</td><td width="40%">&nbsp;</td><td>'.botao('pr�ximo', 'Pr�ximo', 'Ir para a pr�xima tela.','','carregar(\''.($Aplic->checarModulo('praticas', 'acesso', null, 'planejamento_swot') ? 'ponto_forte_geral' : 'principios').'\');').'</td></tr></table></td></tr>';
echo '</table>';
echo '</td></tr></table>';

?>

<script type="text/javascript">
function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_visao_futuro_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_visao_futuro_cor.value;
	}
</script>
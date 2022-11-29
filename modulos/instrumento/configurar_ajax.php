<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);




$sql = new BDConsulta;

	
	
function incluir_instrumento_campo(
		$instrumento_campo_id=null, 
		$instrumento_campo_nome=null
		){
			
	global $Aplic, $bd;
	
	$instrumento_campo_nome=previnirXSS(utf8_decode($instrumento_campo_nome));
	
	$sql = new BDConsulta;
	if ($instrumento_campo_id){
		$sql->adTabela('instrumento_campo');
		$sql->adAtualizar('instrumento_campo_nome', $instrumento_campo_nome);
		$sql->adOnde('instrumento_campo_id ='.(int)$instrumento_campo_id);
		$sql->exec();
	  $sql->limpar();
		}
	else {	
		
		$sql->adTabela('instrumento_campo');
		$sql->adCampo('instrumento_campo.*');
		$sql->adOnde('instrumento_campo_id=1');
	  $default = $sql->linha();
	  $sql->limpar();
		
		$sql->adTabela('instrumento_campo');
		$sql->adCampo('MAX(instrumento_campo_ordem)');
	  $qnt = (int)$sql->Resultado();
	  $sql->limpar();
		
		$sql->adTabela('instrumento_campo');
		$sql->adInserir('instrumento_campo_nome', $instrumento_campo_nome);
		$sql->adInserir('instrumento_campo_ordem', ++$qnt);
		
		foreach($default as $chave => $valor) {
			if ($chave!='instrumento_campo_nome' && $chave!='instrumento_campo_ordem' && $chave!='instrumento_campo_id') $sql->adInserir($chave, $valor);
			}
		
		
		
		$sql->exec();
		$sql->limpar();
		}
	$saida=atualizar_instrumento_campo();
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_instrumento_campo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_instrumento_campo");

function excluir_instrumento_campo($instrumento_campo_id){
	$sql = new BDConsulta;
	
	$sql->adTabela('instrumento');
	$sql->adAtualizar('instrumento_campo', 1);
	$sql->adOnde('instrumento_campo ='.(int)$instrumento_campo_id);
	$sql->exec();
  $sql->limpar();
	
	$sql->setExcluir('instrumento_campo');
	$sql->adOnde('instrumento_campo_id='.(int)$instrumento_campo_id);
	$sql->exec();

	$saida=atualizar_instrumento_campo();
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_instrumento_campo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_instrumento_campo");	


function atualizar_instrumento_campo(){
	global $config;
	$sql = new BDConsulta;
	
	$sql->adTabela('instrumento_campo');
	$sql->adCampo('instrumento_campo.*');
	$sql->adOrdem('instrumento_campo_ordem');
	$linhas=$sql->Lista();
	$sql->limpar();
	$qnt=0;
	
	
	$saida= '<table border=0 cellpadding=0 cellspacing=0 class="tbl1">';
	$saida.= '<tr><th>'.dica('Nome', 'Nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').'Nome'.dicaF().'</th><th></th></tr>';

	foreach ($linhas as $linha) {
		$saida.= '<tr align="center">';
		$saida.= '<td align="left" width="300"><a href="javascript:void(0);" onclick="javascript:mostrar_formulario('.$linha['instrumento_campo_id'].');">'.$linha['instrumento_campo_nome'].'</a></td>';
		$saida.= '<td width="72" align="left">';
		$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_instrumento_campo('.(int)$linha['instrumento_campo_ordem'].', '.$linha['instrumento_campo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_instrumento_campo('.(int)$linha['instrumento_campo_ordem'].', '.$linha['instrumento_campo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_instrumento_campo('.(int)$linha['instrumento_campo_ordem'].', '.$linha['instrumento_campo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_instrumento_campo('.(int)$linha['instrumento_campo_ordem'].', '.$linha['instrumento_campo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		$saida.= dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar.').'<a href="javascript:void(0);" onclick="javascript:editar_instrumento_campo('.$linha['instrumento_campo_id'].');">'.imagem('icones/editar.gif').'</a>'.dicaF();
		if ($linha['instrumento_campo_id']!=1) $saida.= dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'<a href="javascript:void(0);" onclick="javascript:excluir_instrumento_campo('.$linha['instrumento_campo_id'].');">'.imagem('icones/remover.png').'</a>'.dicaF();
		$saida.= '</td>';	
		$saida.= '</tr>';
		}		
	$saida.= '</table>';
	
	return $saida;
	}

function exibir_instrumento_campo(){
	$saida=atualizar_instrumento_campo();
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_instrumento_campo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("exibir_instrumento_campo");
	
function mudar_posicao_instrumento_campo($ordem, $instrumento_campo_id, $direcao){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $instrumento_campo_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('instrumento_campo');
		$sql->adOnde('instrumento_campo_id != '.(int)$instrumento_campo_id);
		$sql->adOrdem('instrumento_campo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
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
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('instrumento_campo');
			$sql->adAtualizar('instrumento_campo_ordem', $novo_ui_ordem);
			$sql->adOnde('instrumento_campo_id = '.(int)$instrumento_campo_id);
			$sql->exec();
			$sql->limpar();
			
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('instrumento_campo');
					$sql->adAtualizar('instrumento_campo_ordem', $idx);
					$sql->adOnde('instrumento_campo_id = '.(int)$acao['instrumento_campo_id']);
					$sql->exec();
					$sql->limpar();

					$idx++;
					} 
				else {
					$sql->adTabela('instrumento_campo');
					$sql->adAtualizar('instrumento_campo_ordem', $idx + 1);
					$sql->adOnde('instrumento_campo_id = '.(int)$acao['instrumento_campo_id']);
					$sql->exec();
					$sql->limpar();

					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_instrumento_campo();
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_instrumento_campo","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_instrumento_campo");	
	
	
	

function editar_instrumento_campo($instrumento_campo_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_campo');
	$sql->adCampo('instrumento_campo.*');
	$sql->adOnde('instrumento_campo_id = '.(int)$instrumento_campo_id);
	$linha=$sql->Linha();
	$sql->limpar();
	
	$objResposta->assign("instrumento_campo_id","value", $instrumento_campo_id);
	$objResposta->assign("instrumento_campo_nome","value", utf8_encode($linha['instrumento_campo_nome']));
	return $objResposta;
	}	
$xajax->registerFunction("editar_instrumento_campo");		
	








function mostrar_formulario($instrumento_campo_id){
	global $config;
	
	$sql = new BDConsulta;
	
	
	$sql->adTabela('instrumento_campo');
	$estrutura=$sql->estrutura();
	$sql->limpar();
	$vetor=array();
	
	$traducao=array(
	'instrumento_dept' => ucfirst($config['departamento']).' responsável',
	'instrumento_depts' => ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s',
	'instrumento_designados' => 'Designados',
	'instrumento_avulso_custo_moeda' => 'Moeda',
	'instrumento_avulso_custo_custo_atual' => 'Valor unitário atual',
	'instrumento_avulso_custo_usuario' => ucfirst($config['usuario']).' cadastrador',
	'instrumento_cia' => ucfirst($config['organizacao']).' responsável',
	'instrumento_cias' => ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s',
	'instrumento_relacionados' => 'Relacionado',
	'instrumento_acesso' => 'Nível de acesso',
	'instrumento_responsavel' => 'Responsável',
	'instrumento_garantia_contratual_percentual' => 'Percentual(%) da garantia',
	'instrumento_garantia_contratual_vencimento' => 'Vencimento da garantia'
	);
	
	foreach($estrutura as $chave => $linha) {
		if ($linha['Field']!='instrumento_campo_nome' && $linha['Field']!='instrumento_campo_ordem' && $linha['Field']!='instrumento_campo_id'){
			$campo=explode('(', $linha['Type']);
			$vetor[$linha['Field']]=$campo[0];
			}
		}
	
	$sql->adTabela('instrumento_campo');
	$sql->adCampo('instrumento_campo.*');
	$sql->adOnde('instrumento_campo_id = '.(int)$instrumento_campo_id);
	$linha=$sql->Linha();
	$sql->limpar();
	
	$saida= '<table cellspacing=0 cellpadding=0 width=100%>';
	foreach($linha as $chave => $valor) {
		if ($chave!='instrumento_campo_nome' && $chave!='instrumento_campo_ordem' && $chave!='instrumento_campo_id'){
			
			if ($vetor[$chave]=='tinyint'){
				if ($chave=='instrumento_financeiro' || $chave=='instrumento_avulso_custo') $saida.= '<tr><td colspan=2><hr></td></tr>';
				$saida.= '<tr><td width=16><input type="checkbox" name="'.$chave.'" id="'.$chave.'" value="1" '.($valor ? 'checked="checked"' : '').' onclick="javascript:check(\''.$chave.'\');" /></td>';
				
				if (isset($vetor[$chave.'_leg'])) $saida.= '<td><input type="text" class="texto" name="'.$chave.'_leg" id="'.$chave.'_leg" value="'.$linha[$chave.'_leg'].'" maxlength="255" style="width:300px;" onChange="javascript:texto(\''.$chave.'_leg\');" /></td></tr>';
				else {
					if (isset($traducao[$chave])) $saida.= '<td>'.$traducao[$chave].'</td></tr>';
					elseif (isset($config[str_replace('instrumento_', '',$chave)])) $saida.= '<td>'.ucfirst($config[str_replace('instrumento_', '',$chave)]).'</td></tr>';
					else $saida.= '<td>'.$chave.'</td></tr>';
					}
				}
			elseif ($vetor[$chave]=='varchar' && !isset($vetor[str_replace('_leg', '',$chave)])) $saida.= '<tr><td></td><td><input type="text" class="texto" name="'.$chave.'" id="'.$chave.'" value="'.$valor.'" maxlength="255" style="width:300px;" onChange="javascript:texto(\''.$chave.'\');" /></td></tr>';
			}
		}
	$saida.= '</table>';
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("combo_formulario","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("mostrar_formulario");	


function texto($instrumento_campo_id, $chave, $valor){
	$valor=previnirXSS(utf8_decode($valor));
	$sql = new BDConsulta;
	$sql->adTabela('instrumento_campo');
	$sql->adAtualizar($chave, $valor);
	$sql->adOnde('instrumento_campo_id = '.(int)$instrumento_campo_id);
	$sql->exec();
	$sql->limpar();
	}
$xajax->registerFunction("texto");	

$xajax->processRequest();
?>
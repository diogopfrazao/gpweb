<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');

global $Aplic, $config,$projetos, $projStatus, $projetos_status, $projeto_status_filtro, $tabAtualId, $tabNomeAtual, $desenvolvedor;


if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css"></head><body>';
require_once ($Aplic->getClasseModulo('cias'));
$projetoStatuses=getSisValor('StatusProjeto');
$quant=count($projetoStatuses)+2;
$status=array('0' => 'Todos', '1'=>'Ativos') + getSisValor('StatusProjeto', 2)+array($quant => 'Inativos');
$pesquisar_texto = $Aplic->getEstado('projtextobusca') ? $Aplic->getEstado('projtextobusca') : '';
$desenvolvedor = $Aplic->getEstado('ProjIdxDesenvolvedor') !== null ? $Aplic->getEstado('ProjIdxDesenvolvedor') : 0;

$tab=getParam($_REQUEST, 'tab', 0);
$cia_id=getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$ativo = intval(!$tab);


$ordemDir = $Aplic->getEstado('ordemDir') ? $Aplic->getEstado('ordemDir') : 'desc';
if ($ordemDir == 'asc') $ordemDir = 'desc';
else $ordemDir = 'asc';

$ordenarPor = $Aplic->getEstado('ProjIdxOrdemPor') ? $Aplic->getEstado('ProjIdxOrdemPor') : 'projeto_data_fim';


if (isset($_REQUEST['projeto_responsavel'])) $Aplic->setEstado('ProjIdxResponsavel', getParam($_REQUEST, 'projeto_responsavel', null));
$responsavel = $Aplic->getEstado('ProjIdxResponsavel');
$projeto_tipo = $Aplic->getEstado('ProjIdxTipo') ? $Aplic->getEstado('ProjIdxTipo') : -1;
$projeto_tipos = array(-1 => 'todos') + getSisValor('TipoProjeto');
//projetos_inicio_data();

$filtrosBuilder = new FiltrosProjetoBuilder();
$filtrosBuilder->setUsuarioId(0)
    ->setCiaId($cia_id)
    ->setOrdenarPor($ordenarPor)
    ->setOrdemDir($ordemDir)
    ->setProjetoTipo($projeto_tipo)
    ->setPesquisarTexto($pesquisar_texto);

$projetos=projetos_inicio_data($filtrosBuilder);

echo '<table width="1024" border=0 align="center" cellpadding=0>';
echo '<tr><td colspan="4"><h2>Lista de '.ucfirst($config['projetos']).'</h2></td></tr>';
echo '<tr><td>Status: '.$status[$tab].'</td><td>Tipo: '.resultafazer_combo($projeto_tipos, 'projeto_tipo', $projeto_tipo).'</td>';
$q = new BDConsulta();
$q->adTabela('projetos', 'p');
$q->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
$q->esqUnir('usuarios', 'u', 'u.usuario_id = p.projeto_responsavel');
$q->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
$q->adOnde('usuario_id > 0');
$q->adOnde('p.projeto_responsavel IS NOT NULL');
$lista_usuarios = array(0 => 'todos');
$lista_usuarios = $lista_usuarios + $q->ListaChave();
echo '<td>'.ucfirst($config['genero_gerente']).': '.(resultafazer_combo($lista_usuarios, 'projeto_responsavel', $responsavel)? resultafazer_combo($lista_usuarios, 'projeto_responsavel', $responsavel) : 'Todos').'</td>';
$q = new BDConsulta;
$q->adTabela('cias');
$q->adCampo('cia_id, cia_nome');
$q->adOrdem('cia_nome');
$cias = unirVetores(array(0 => ''), $q->ListaChave());
echo '<td>'.ucfirst($config['organizacao']).': ' .resultafazer_combo($cias, 'projeto_cia', $cia_id).'</td></tr></table>';
$q = new BDConsulta();
$q->adTabela('projetos', 'p');
$q->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
$q->esqUnir('usuarios', 'u', 'u.usuario_id = p.projeto_responsavel');
$q->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
$q->adOnde('usuario_id > 0');
$lista_usuarios = array(0 => 'todos');
$lista_usuarios = $lista_usuarios + $q->ListaChave();

$mostrar_todos_projetos = false;
$tabAtualId = ($Aplic->getEstado('ProjIdxTab') !== null ? $Aplic->getEstado('ProjIdxTab') : 0);
if ($tabAtualId == 0 || $tabAtualId == -1) $projeto_status_filtro = -1;
elseif ($tabAtualId == 1) $projeto_status_filtro = -2;
elseif ($tabAtualId == count($projetos_status) - 1) $projeto_status_filtro = -3;
else $projeto_status_filtro = ($projetoStatuses[0] ? $tabAtualId - 2 : $tabAtualId - 1);
if (($projeto_status_filtro == -1 || $projeto_status_filtro == -2 || $projeto_status_filtro == -3)) $mostrar_todos_projetos = true;
if ($projeto_status_filtro == -1) {
	//fazer algo?
	}
elseif ($projeto_status_filtro == -2) {
	$chave = 0;
	foreach ($projetos as $projeto) {
		if (!$projeto['projeto_ativo']) unset($projetos[$chave]);
		$chave++;
		}
	$chave = 0;
	foreach ($projetos as $projeto) {
		$tmp_projetos[$chave] = $projeto;
		$chave++;
		}
	$projetos = (isset($tmp_projetos) ? $tmp_projetos : array());
	}
elseif ($projeto_status_filtro == -3) {
	$chave = 0;
	foreach ($projetos as $projeto) {
		if ($projeto['projeto_ativo']) unset($projetos[$chave]);
		$chave++;
		}
	$chave = 0;
	foreach ($projetos as $projeto) {
		$tmp_projetos[$chave] = $projeto;
		$chave++;
		}
	$projetos = $tmp_projetos;
	}
else {
	//fazer algo?
	}
echo '<table width="98%" border=0 align="center" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr><th style="white-space: nowrap">Cor</th><th style="white-space: nowrap">P</th><th style="white-space: nowrap">Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).'</th><th style="white-space: nowrap">'.$config['organizacao'].'</th><th style="white-space: nowrap">In?cio</th><th style="white-space: nowrap">T?rmino</th><th style="white-space: nowrap">Prov?vel</th><th style="white-space: nowrap">Respons?vel</th><th style="white-space: nowrap">T M</th><th style="white-space: nowrap">Custo</th>';
if ($projeto_status_filtro < 0) echo '<th style="white-space: nowrap">Status</th>';
echo '</tr>';
$nenhum = true;
foreach ($projetos as $linha) {
	if (($mostrar_todos_projetos || ($linha['projeto_ativo'] && $linha['projeto_status'] == $projeto_status_filtro)) || (($linha['projeto_ativo'] && $linha['projeto_status'] == $projeto_status_filtro)) || ((!$linha['projeto_ativo'] && $projeto_status_filtro == -3))) {
		$nenhum = false;
		$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
		$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
		$adjusted_data_final = (isset($linha['projeto_data_fim_ajustada']) && intval($linha['projeto_data_fim_ajustada'])) ? new CData($linha['projeto_data_fim_ajustada']) : null;
		$data_fim_atual = (isset($linha['projeto_fim_atualizado']) && intval($linha['projeto_fim_atualizado'])) ? new CData($linha['projeto_fim_atualizado']) : null;
		$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
		$s = '<tr><td width="45" align="center" style="background-color:#'.$linha['projeto_cor'].'"><font color="'.melhorCor($linha['projeto_cor']).'">'.number_format($linha['projeto_percentagem'], 2, ',', '.').'</font></td>';
		$s .= '<td align="center">'.prioridade($linha['projeto_prioridade'], true).'</td>';
		$s .= '<td>'.htmlspecialchars($linha['projeto_nome'], ENT_QUOTES, $localidade_tipo_caract).'</td>';
		$s .= '<td>'.htmlspecialchars($linha['cia_nome'], ENT_QUOTES, $localidade_tipo_caract).'</td>';
		$s .= '<td align="center" width="80">'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '&nbsp;').'</td><td align="center" width="80" style="white-space: nowrap">'.($data_fim ? $data_fim->format('%d/%m/%Y') : '&nbsp;').'</td><td width="85" align="center">';
		$s .= $data_fim_atual ? '<span '.$estilo.'>'.$data_fim_atual->format('%d/%m/%Y').'</span>' : '&nbsp;';
		$s .= '</td><td style="white-space: nowrap">'.htmlspecialchars($linha['nome_responsavel'], ENT_QUOTES, $localidade_tipo_caract).'</td><td align="center" style="white-space: nowrap">';
		$s .= $linha['total_tarefas'].($linha['minhas_tarefas'] ? ' ('.$linha['minhas_tarefas'].')' : '');
		$s .= '</td>';
		$s .= '<td align="right" style="white-space: nowrap">'.number_format($linha['projeto_custo'], 2, ',', '.').'</td>';
		if ($mostrar_todos_projetos) {
			$s .= '<td align="center" style="white-space: nowrap">';
			$s .= $linha['projeto_status'] == 0 ? 'N?o definido' : $projetoStatuses[$linha['projeto_status']];
			$s .= '</td>';
			}
		$s .= '</tr>';
		echo $s;
		}
	}
if ($nenhum) echo '<tr><td colspan="10"><p>'.($config['genero_projeto']=='o'? 'Nenhum' : 'Nenhuma').' '.$config['projeto'].' encontrad'.$config['genero_projeto'].'.</p></td></tr>';
echo '</td></tr></table>';
echo '</table>';
?>

<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $projeto_id, $ano;
$ordenar=getParam($_REQUEST, 'ordenar', 'pratica_indicador_id');
$ordem=getParam($_REQUEST, 'ordem', '0');

$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : $Aplic->usuario_pauta);

$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_unidade, pratica_indicador_acumulacao, pratica_indicador_acesso, pratica_indicador_nome, pratica_indicador_descricao, pratica_indicador_cor, pratica_indicador_sentido, pratica_indicador_responsavel, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_agrupar');
$sql->adOnde('pratica_indicador_projeto = '.$projeto_id);
if ($ano) $sql->adOnde('pratica_indicador_requisito.ano = '.$ano);
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$indicadores = $sql->lista();
$sql->limpar();

$detalhe_projeto=1;

include_once BASE_DIR.'/modulos/praticas/indicadores_ver_idx.php';

?>
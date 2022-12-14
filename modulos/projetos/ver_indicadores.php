<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
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
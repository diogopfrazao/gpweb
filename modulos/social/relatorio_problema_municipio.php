<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
global $sql, $perms, $Aplic, $tab, $status_id, $dialogo, $estado_sigla, $estado, $relatorio_id, $municipios_superintendencia, $municipio_id , $social_id, $acao_id, $social_comunidade_id, $social_familia_id, $opcao_id;

//problema_tipo==0 nas Familias
//problema_tipo==1 no Comit? Nacional
//problema_tipo==2 no Comit? Estaduais
//problema_tipo==3 no Comit? Municipais
//problema_tipo==4 no Comit? Comunit?rios

if ($opcao_id=='problema_municipio_beneficiario') $tipo=0;
elseif ($opcao_id=='problema_municipio_comunidade') $tipo=4;
elseif ($opcao_id=='problema_municipio_municipio') $tipo=3;

echo '<table cellpadding=0 cellspacing=0 align=center>'
echo '<tr><td align=center><h1>Lista dos Problemas '.($tipo==0 ? 'nos Benrfici?rios' : '').($tipo==4 ? 'nas Comiss?es Comunit?rias' : '').($tipo==3 ? 'nos Comit?s Municipais' : '').'</h1><br></td></tr>';

$sql->adTabela('social_acao_problema');
$sql->adCampo('DISTINCT social_acao_problema_id, social_acao_problema_descricao');
$sql->adOnde('social_acao_problema_tipo='.(int)$tipo);
$sql->adOnde('social_acao_problema_acao_id='.(int)$acao_id);
$sql->adOrdem('social_acao_problema_ordem');
$tipos_problema=$sql->lista();
$sql->limpar();


$qnt=count($tipos_problema);
$resultado=array();

foreach($tipos_problema as $problema){
	if ($tipo==0){
		//familia
		$sql->adTabela('social_familia_problema');
		$sql->esqUnir('social_familia','social_familia', 'social_familia_problema_familia=social_familia_id');
		$sql->esqUnir('social_familia_acao', 'social_familia_acao', 'social_familia_acao_familia=social_familia_id');
		$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_familia_acao_acao');
		$sql->esqUnir('municipios', 'municipios', 'municipio_id=social_familia_municipio');
		$sql->esqUnir('estado', 'estado', 'estado.estado_sigla=social_familia_estado');
		$sql->adCampo('estado_nome, municipio_nome, count(social_familia_problema_tipo) AS total');
		$sql->adOnde('social_acao_social='.(int)$social_id);
		$sql->adOnde('social_familia_acao_acao='.(int)$acao_id);
		if ($status_id) $sql->adOnde('social_familia_problema_status='.(int)$status_id);
		if ($municipios_superintendencia) $sql->adOnde('social_familia_municipio IN ('.$municipios_superintendencia.')');
		if ($estado_sigla) $sql->adOnde('social_familia_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('social_familia_municipio='.$municipio_id);
		$sql->adOnde('social_familia_problema_tipo='.(int)$problema['social_acao_problema_id']);
		$sql->adOrdem('estado_nome, municipio_nome');
		$sql->adGrupo('social_familia_municipio');
		$vetor_comunidade=$sql->lista();
		$sql->limpar();
		foreach($vetor_comunidade as $linha) $resultado[$linha['estado_nome']][$linha['municipio_nome']][$problema['social_acao_problema_id']]=$linha['total'];
		}
		
	if ($tipo==4||$tipo==3){
		//comit?s
		$sql->adTabela('social_comite_problema');
		$sql->esqUnir('social_comite','social_comite', 'social_comite_problema_comite=social_comite_id');
		$sql->esqUnir('social_comite_acao', 'social_comite_acao', 'social_comite_acao_comite=social_comite_id');
		$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=social_comite_acao_acao');
		$sql->esqUnir('municipios', 'municipios', 'municipio_id=social_comite_municipio');
		$sql->esqUnir('estado', 'estado', 'estado.estado_sigla=social_comite_estado');
		$sql->adCampo('estado_nome, municipio_nome, count(social_comite_problema_tipo) AS total');
		$sql->adOnde('social_acao_social='.(int)$social_id);
		$sql->adOnde('social_comite_acao_acao='.(int)$acao_id);
		if ($status_id) $sql->adOnde('social_comite_problema_status='.(int)$status_id);
		if ($estado_sigla) $sql->adOnde('social_comite_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('social_comite_municipio="'.$municipio_id.'"');
		$sql->adOnde('social_comite_problema_tipo='.(int)$problema['social_acao_problema_id']);
		$sql->adOrdem('estado_nome, municipio_nome');
		$sql->adGrupo('social_comite_municipio');
		$vetor_comunidade=$sql->lista();
		$sql->limpar();
		foreach($vetor_comunidade as $linha) $resultado[$linha['estado_nome']][$linha['municipio_nome']][$problema['social_acao_problema_id']]=$linha['total'];
		}		
	}

if (count($resultado)){
	//cabe?alho
	$cabecalho='';
	foreach($tipos_problema as $chave => $problema) $cabecalho.= '<td>'.($chave+1).'</td>';
	echo '<tr><td><table cellpadding=0 cellspacing=0 class="tbl1" align=center>';
	foreach($tipos_problema as $chave => $problema) echo '<tr><td align=right colspan='.($chave+2).' >'.$problema['social_acao_problema_descricao'].'</td></tr>';
	
	foreach ($resultado as $uf => $linha){
		echo '<tr><td align="left" colspan='.($qnt+1).' ><b>'.$uf.'</b></td></tr>';
		
		foreach ($linha as $municipio_nome => $linha2){
			echo '<tr><td>&nbsp;&nbsp;&nbsp;'.$municipio_nome.'</td>';
			foreach($tipos_problema as $problema) echo '<td width="15" align="center">'.(isset($linha2[$problema['social_acao_problema_id']]) ? $linha2[$problema['social_acao_problema_id']] : '0').'</td>';
			echo '</tr>';
			}
		}
	}	
else echo '<tr><td align=center>N?o foi encontrado nenhum problema baseado nos par?metros passados</td></tr>';	

echo '</table></td></tr></table>';


function texto_vertical1($legenda){
	$saida='';
	for ($i=0; $i< strlen($legenda); $i++) $saida.=$legenda[$i].'<br>';
	return $saida;
	}
?>
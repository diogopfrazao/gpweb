<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';

$projeto_abertura_id=getParam($_REQUEST, 'projeto_abertura_id', 0);
$baseline_id=getParam($_REQUEST, 'baseline_id', 0);

$sql = new BDConsulta();
$sql->adTabela('projeto_abertura');
$sql->adCampo('projeto_abertura.*, projeto_abertura_projeto AS projeto_id');
$sql->adOnde('projeto_abertura_id = '.(int)$projeto_abertura_id);
$dados = $sql->Linha();
$sql->limpar();

if ($Aplic->profissional){
	$barra=codigo_barra('projeto', $dados['projeto_id']);
	if ($barra['cabecalho']) echo $barra['imagem'];
	}

$sql->adTabela('artefatos_tipo');
$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
$sql->adOnde('artefato_tipo_arquivo=\'termo_abertura.html\'');
$linha = $sql->linha();
$sql->limpar();
$campos = unserialize($linha['artefato_tipo_campos']);

$modelo= new Modelo;
$modelo->set_modelo_tipo(1);
foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
$modelo->set_modelo($tpl);


	
echo '<table align="left" cellspacing=0 cellpadding=0 width="100%"><tr><td>';
for ($i=1; $i <= $modelo->quantidade(); $i++){
	$campo='campo_'.$i;
	$tpl->$campo = $modelo->get_campo($i);
	} 
echo $tpl->exibir($modelo->edicao); 
echo '</td></tr>';
if ($Aplic->profissional && $barra['rodape']) echo '<tr><td>'.$barra['imagem'].'</td></tr>';
echo '</table>';


if ($dialogo && !$Aplic->pdf_print && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script>self.print();</script>';
?>
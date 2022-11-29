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

$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$baseline_id=getParam($_REQUEST, 'baseline_id', 0);

$rodape_varivel=array();
$rodape_varivel['gerente']=(int)getParam($_REQUEST, 'gerente', 0);
$rodape_varivel['supervisor']=(int)getParam($_REQUEST, 'supervisor', 0);
$rodape_varivel['autoridade']=(int)getParam($_REQUEST, 'autoridade', 0);
$rodape_varivel['cliente']=(int)getParam($_REQUEST, 'cliente', 0);
$rodape_varivel['barra']=(int)getParam($_REQUEST, 'barra', 0);

if ($rodape_varivel['barra'] && $Aplic->profissional) {
	$barra=codigo_barra('projeto', $projeto_id, $baseline_id);
	if ($barra['cabecalho']) echo $barra['imagem'];
	}

$sql = new BDConsulta();
$sql->adTabela('projeto_comunicacao');
$sql->esqUnir(($baseline_id ? 'baseline_' : '').'projetos', 'projetos', 'projeto_id=projeto_comunicacao_projeto'.($baseline_id ? ' AND projetos.baseline_id='.(int)$baseline_id : ''));
$sql->adCampo('projeto_comunicacao.*, projeto_id, projeto_cia, projeto_nome, projeto_codigo');
$sql->adOnde('projeto_comunicacao_projeto = '.(int)$projeto_id);
$dados = $sql->Linha();
$sql->limpar();


$sql->adTabela('artefatos_tipo');
$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
$sql->adOnde('artefato_tipo_arquivo=\'plano_comunicacoes.html\'');
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
echo '</table>';

include_once BASE_DIR.'/modulos/projetos/projeto_impressao_funcao_pro.php';
echo impressao_rodape_projeto($rodape_varivel, $projeto_id, $baseline_id, 'font-family:Times New Roman, Times, serif; font-size:12pt;');


if ($dialogo && !$Aplic->pdf_print && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script>self.print();</script>';
?>
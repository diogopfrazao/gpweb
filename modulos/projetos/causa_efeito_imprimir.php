<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
$causa_efeito_id=getParam($_REQUEST, 'causa_efeito_id', 0);
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

?>
<script type="text/javascript">
function nodeSelect_handle(sender,arg){	
var treenode = treeview.getNode(arg.NodeId);
var observacao = treenode.getData("observacao");	
//sobrescreve com o dado recente
if (vetor_observacao[arg.NodeId])observacao=vetor_observacao[arg.NodeId];
if(observacao) document.getElementById('observacao').innerHTML = "<br><table cellspacing=3 cellpadding=3 border=1><tr><td><b>" + observacao + "</b></td></tr></table>";
else document.getElementById('observacao').innerHTML = "";
}
</script>
<?php

$sql = new BDConsulta();
require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";
$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;
$arvore->EditNodeEnable = true;
$arvore->DragAndDropEnable=true;
$arvore->multipleSelectEnable = true;

$sql->adTabela('causa_efeito');
$sql->adOnde('causa_efeito_id='.(int)$causa_efeito_id);
$linha=$sql->Linha();
$sql->limpar();

				
$vetor=array();
$vetor_utf=mjson_decode($linha['causa_efeito_objeto']);
$i=0;
foreach($vetor_utf as $campo){
	foreach($campo as $chave => $valor) $vetor[$i][$chave]=($chave!='obs' ? utf8_decode($valor) : $valor);
	$i++;
	}


$root = $arvore->getRootNode();
$root->text=$vetor[0]['texto'];
$root->addData("observacao", $vetor[0]['obs']);
$root->expand=true;
$root->image="ball_glass_redS.gif";
$maiorfilho=0;

$imagens=array();
foreach ($vetor as $chave => $campo){
	if($chave>0){
		$imagens[$campo['filho']]=(isset($imagens[$campo['pai']])&& $imagens[$campo['pai']]=='ball_glass_greenS.gif' ? 'ball_glass_redS.gif' : 'ball_glass_greenS.gif');
		if (substr($campo['filho'],0,6)=='nodulo' && (int)substr($campo['filho'],6)> $maiorfilho) $maiorfilho=(int)substr($campo['filho'],6);
		$nodulo=$arvore->Add($campo['pai'],$campo['filho'],$campo['texto'],false,$imagens[$campo['filho']]);
		if ($campo['obs']) $nodulo->addData("observacao", $campo['obs']);
		}
	}

$saida_causa= '<table><tr><td colspan=20>'.$arvore->Render().'</td></tr>';
$saida_causa.= '<tr><td colspan=20><div id="observacao"></div></td></tr></table>';
$saida_causa.='<script>treeview.expandAll();</script>';


include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';



$sql = new BDConsulta();
$sql->adTabela('causa_efeito');
$sql->esqUnir('causa_efeito_gestao','causa_efeito_gestao','causa_efeito_gestao.causa_efeito_gestao_causa_efeito=causa_efeito.causa_efeito_id');
$sql->esqUnir(($baseline_id ? 'baseline_' : '').'projetos', 'projetos', 'projetos.projeto_id=causa_efeito_gestao_projeto'.($baseline_id ? ' AND projetos.baseline_id='.(int)$baseline_id : ''));
$sql->adCampo('projetos.projeto_id, projeto_cia, projeto_nome, projeto_codigo, causa_efeito.causa_efeito_id');
$sql->adOnde('causa_efeito.causa_efeito_id = '.(int)$causa_efeito_id);
$dados = $sql->Linha();
$sql->limpar();

$sql->adTabela('artefatos_tipo');
$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
$sql->adOnde('artefato_tipo_arquivo=\'arvore_problema.html\'');
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
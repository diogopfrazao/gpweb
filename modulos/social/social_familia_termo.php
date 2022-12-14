<?php
/* Copyright [2011] -  S?rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo ? parte do programa gpweb
O gpweb ? um software livre; voc? pode redistribu?-lo e/ou modific?-lo dentro dos termos da Licen?a P?blica Geral GNU como publicada pela Funda??o do Software Livre (FSF); na vers?o 2 da Licen?a.
Este programa ? distribu?do na esperan?a que possa ser  ?til, mas SEM NENHUMA GARANTIA; sem uma garantia impl?cita de ADEQUA??O a qualquer  MERCADO ou APLICA??O EM PARTICULAR. Veja a Licen?a P?blica Geral GNU/GPL em portugu?s para maiores detalhes.
Voc? deve ter recebido uma c?pia da Licen?a P?blica Geral GNU, sob o t?tulo "licen?a GPL 2.odt", junto com este programa, se n?o, acesse o Portal do Software P?blico Brasileiro no endere?o www.softwarepublico.gov.br ou escreva para a Funda??o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc? n?o deveria acessar este arquivo diretamente.');
$social_acao_id = intval(getParam($_REQUEST, 'social_acao_id', 0));
$social_familia_id = intval(getParam($_REQUEST, 'social_familia_id', 0));
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
include_once BASE_DIR.'/modulos/social/acao.class.php';
$obj = new CAcao;
$obj->load($social_acao_id);

$sql = new BDConsulta;
$sql->adTabela('social_familia');
$sql->esqUnir('estado', 'estado', 'social_familia_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'social_familia_municipio=municipio_id');
$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_familia_comunidade=social_comunidade_id');
$sql->adCampo('social_familia_estado, estado_nome, municipio_nome, social_comunidade_nome, social_familia_latitude, social_familia_longitude, social_familia_cpf, social_familia_nome, social_familia_nis');
$sql->adOnde('social_familia_id='.$social_familia_id);
$linha= $sql->Linha();
$sql->limpar();

$sql->adTabela('social_acao_arquivo');
$sql->adCampo('social_acao_arquivo_endereco');
$sql->adOnde('social_acao_arquivo_acao='.(int)$social_acao_id);
$sql->adOnde('social_acao_arquivo_familia='.(int)$social_familia_id);
$sql->adOnde('social_acao_arquivo_depois=1');
$sql->adOrdem('social_acao_arquivo_ordem ASC');
$arquivos=$sql->Resultado();
$sql->limpar();

$sql->adTabela('social_familia_acao');
$sql->adCampo('social_familia_acao_codigo');
$sql->adOnde('social_familia_acao_familia='.(int)$social_familia_id);
$codigo=$sql->Resultado();
$sql->limpar();


echo '<table>';
echo '<tr><td style="width:50px">&nbsp;</td><td align="center">'.($obj->social_acao_logo ? '<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social').'/arquivos/acoes_logo/'.$obj->social_acao_logo.'" alt="" border=0 />' : '').'</td><td></td></tr>';
echo '<tr><td style="width:50px">&nbsp;</td><td><table width="750" cellspacing=0 cellpadding=2 align="center" border=1>';
echo '<tr><td align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>TERMO DE RECEBIMENTO '.strtoupper($obj->social_acao_produto).'</b></td><td align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>'.strtoupper($obj->social_acao_orgao).'</b></td></tr>';
echo '<tr><td style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>1. Financiador</b>: '.$obj->social_acao_financiador.'</td><td style="white-space: nowrap;font-family:Times New Roman, Times, serif; font-size:12pt;"><b>2. '.$obj->social_acao_codigo.'</b>: '.$codigo.'</td></tr>';
echo '<tr><td colspan=2 style="font-family:Times New Roman, Times, serif; font-size:12pt;">';
echo '<b>3. Execu??o:</b><br />';
echo '&nbsp;&nbsp;&nbsp;3.1. Instala??o: '.retorna_data(date('Y-m-d H:i:s'),false).'<br />';
echo '&nbsp;&nbsp;&nbsp;3.2. Munic?pio: '.$linha['municipio_nome'].($linha['social_familia_estado'] && $linha['municipio_nome'] ? '-'.$linha['social_familia_estado'] : '').'<br />';
echo '&nbsp;&nbsp;&nbsp;3.3. Localidade: '.$linha['social_comunidade_nome'].'<br />';
echo '&nbsp;&nbsp;&nbsp;3.4. Coordena??o Geogr?fica: '.($linha['social_familia_latitude'] && $linha['social_familia_longitude'] ? $linha['social_familia_latitude'].'? '.$linha['social_familia_longitude'].'?' : '');
echo '</td></tr>';
echo '<tr><td rowspan="2" style="font-family:Times New Roman, Times, serif; font-size:12pt;"><b>4. Identifica&ccedil;&atilde;o do(a) Benefici&aacute;rio(a)</b><br />';
echo '&nbsp;&nbsp;&nbsp;4.1. Nome do(a) Benefici&aacute;rio(a): '.$linha['social_familia_nome'].'</td><td style="white-space: nowrap;font-family:Times New Roman, Times, serif; font-size:12pt;"><b>5. CPF</b>: '.$linha['social_familia_cpf'].'</td></tr>'; 
echo '<tr><td style="white-space: nowrap;font-family:Times New Roman, Times, serif; font-size:12pt;"><b>6. NIS</b>: '.$linha['social_familia_nis'].'</td></tr>';
if ($arquivos) echo '<tr><td colspan=2 align="center"><img width="500px" src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social').'/arquivos/acoes/'.$arquivos.'" alt="" border=0 /></td></tr>';
echo '<tr><td colspan=2 style="font-family:Times New Roman, Times, serif; font-size:12pt;">'.$obj->social_acao_declaracao.'</td></tr>';
echo '<tr><td align="center" style="font-family:Times New Roman, Times, serif; font-size:12pt;"><br /><br />________________________________________<br />Assinatura do Benefici&aacute;rio</td><td style="white-space: nowrap;font-family:Times New Roman, Times, serif; font-size:12pt;" valign="middle">Data: '.retorna_data(date('Y-m-d H:i:s'),false).'</td></tr>'; 
echo '</table></td><td style="width:25px">&nbsp;</td></tr></table>';
if ($dialogo && !($Aplic->usuario_nomeguerra=='Visitante' && $Aplic->usuario_id=1)) echo '<script>self.print();</script>';


?>
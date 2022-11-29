<?php
/* Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $config, $traducao;

$traducao=array_merge($traducao, array(
'performace_usuario_titulo'=>'Performance dos '.$config['usuarios'],
'performace_usuario_descricao'=>'Relat�rio que mostra a quantidade de horas trabalhadas por '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' n'.$config['genero_tarefa'].'s '.$config['tarefas'].' a eles atribu�d'.$config['genero_tarefa'].'s',
'performace_usuario_dica'=>'Relat�rio que mostra uma lista de '.$config['usuario'].' com a quantidade de horas alocadas, trabalhadas e completadas por eles n'.$config['genero_tarefa'].'s '.$config['tarefas'].' designad'.$config['genero_tarefa'].'s.'
));
?>
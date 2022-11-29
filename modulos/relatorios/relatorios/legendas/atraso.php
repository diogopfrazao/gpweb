<?php
/* Copyright [2011] -  Sérgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
require BASE_DIR.'/incluir/validar_autorizado.php';

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
global $config, $traducao;

$traducao=array_merge($traducao, array(
'atraso_titulo'=>ucfirst($config['tarefas']).' atrasad'.$config['genero_tarefa'].'s',
'atraso_descricao'=>ucfirst($config['tarefas']).' atualmente atrasad'.$config['genero_tarefa'].'s',
'atraso_dica'=>'Lista d'.$config['genero_tarefa'].'s '.$config['tarefas'].' atualmente atrasad'.$config['genero_tarefa'].'s, ao se considerar a data de término previsto para '.$config['genero_tarefa'].'s mesm'.$config['genero_tarefa'].'s e o fato de ainda não estarem 100% completad'.$config['genero_tarefa'].'s.',
));
?>
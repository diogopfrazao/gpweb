<?php
/*
Copyright [2011] -  S�rgio Fernandes Reinert de Lima - INPI 11802-5
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/
if (file_exists('../pdfimg.php')) @unlink('../pdfimg.php');

remover_arquivos_pro('../');
rrmdir('../lib/codigobarra');
rrmdir('../lib/ckeditor/plugins/simpleuploads');
rrmdir('../lib/extgantt');
rrmdir('../lib/extjs');
rrmdir('../lib/SlickGrid');
rrmdir('../lib/PHPExcel');
rrmdir('../lib/mpdf');
rrmdir('../lib/highcharts3');
rrmdir('../modulos/atas');
rrmdir('../modulos/graficos');
rrmdir('../modulos/sistema/menu');
rrmdir('../modulos/problema');
rrmdir('../modulos/operativo');
rrmdir('../modulos/projetos/eb');
rrmdir('../modulos/projetos/gantt_agil');
rrmdir('../modulos/agrupamento');
rrmdir('../modulos/financeiro');
rrmdir('../modulos/swot');
rrmdir('../modulos/tr');
rrmdir('../modulos/ssti');
rrmdir('../modulos/registro');
rrmdir('../modulos/aviso');
rrmdir('../modulos/sistema/pauta');
rrmdir('../modulos/sistema/ator');
rrmdir('../modulos/pdcl');
rrmdir('../modulos/trelo');
rrmdir('../modulos/os');
rrmdir('../modulos/sistema/nd');
echo 'Terminado em '.date('d/m/Y H:i:s');


function remover_arquivos_pro($dir = '../'){
	$files = scandir($dir);
	if(!$files) return;

	foreach($files as $file){
		$fullPath = $dir.$file;
		if(is_dir($fullPath) && $file != '.' && $file != '..'){
			remover_arquivos_pro($fullPath.'/');
			}
		else if((stripos($file, '_pro.') !== false || stripos($file, '_pro_') !== false) && $file !='limpar_pro.php'){
			@unlink($fullPath);
			}
		}
	}

function rrmdir($dir) {
	if (is_dir($dir)){
		$objects = scandir($dir);
		foreach ($objects as $object){
			if ($object != "." && $object != "..") {
				if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object);
				}
			}
		reset($objects);
		rmdir($dir);
		}
	}
?>
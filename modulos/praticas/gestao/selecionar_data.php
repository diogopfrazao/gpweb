<?php
/*
Copyright [2015] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número BR 51 2015 000171 0 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/
require BASE_DIR.'/incluir/validar_autorizado.php';
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$Aplic->carregarCalendarioJS();
$data_inicio = new CData(date('Y-m-d'));

$pg_id=getParam($_REQUEST, 'pg_id', 0);
$endereco=getParam($_REQUEST, 'endereco', null);

echo '<form name="env" id="env" method="POST">';

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="left" style="white-space: nowrap">Data:<input type="hidden" name="reg_data_inicio" id="reg_data_inicio" value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format('%d/%m/%Y') : '').'" class="texto" /><a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a></td>';
echo '<td align="right" width="80px" style="white-space: nowrap">'.botao('exibir', null, null,'','exibir();').'</td><td></td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';
?>
<script type="text/javascript">

function exibir(){
	var data1=document.getElementById("reg_data_inicio").value;
	var endereco='<?php echo $endereco?>';
	var pg_id=<?php echo $pg_id?>;
	parent.gpwebApp._popupCallback(data1, pg_id, endereco);
	}

function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'reg_' + f_data );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
  	else{
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}


  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "reg_data_inicio",
  	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
  	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
    onSelect: function(cal1) {
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide();
  	}
  });
  
</script>  
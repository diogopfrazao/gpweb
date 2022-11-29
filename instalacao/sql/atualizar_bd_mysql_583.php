<?php
global $config, $bd;

$resultado = $bd->Execute("SHOW TABLES LIKE 'financeiro_ob';");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if($existe) {
	//checar se é a base para sema instalado
	$resultado = $bd->Execute("SHOW COLUMNS FROM financeiro_ob LIKE 'CD_EXERCICIO'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if($existe) {
		$bd->Execute("INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES	
			('projetos','ne_qnt','Quantidade de Notas de Empenho',0),
			('projetos','ne_valor','Valor de Notas de Empenho',0),
			('projetos','ne_estorno_qnt','Quantidade de Estornos de Notas de Empenho',0),
			('projetos','ne_estorno_valor','Valor de Estornos de Notas de Empenho',0),
			('projetos','ns_qnt','Quantidade de Notas de Liquidação',0),
			('projetos','ns_valor','Valor de Notas de Liquidação',0),
			('projetos','ns_estorno_qnt','Quantidade de Estornos de Notas de Liquidação',0),
			('projetos','ns_estorno_valor','Valor de Estornos de Notas de Liquidação',0),
			('projetos','ob_qnt','Quantidade de Notas de Ordem Bancária',0),
			('projetos','ob_valor','Valor de Notas de Ordem Bancária',0),
			('projetos','ob_estorn_qnt','Quantidade de Estornos de Notas de Ordem Bancária',0),
			('projetos','ob_estorno_valor','Valor de Estornos de Notas de Ordem Bancária',0),
			('projetos','gcv_qnt','Quantidade de Guias de Crédito de Verba',0),
			('projetos','gcv_valor','Valor de Guias de Crédito de Verba',0),
			('projetos','gcv_estorno_qnt','Quantidade de Estornos de Guias de Crédito de Verba',0),
			('projetos','gcv_estorno_valor','Valor de Estornos de Guias de Crédito de Verba',0),
			('trs','ne_qnt','Quantidade de Notas de Empenho',0),
			('trs','ne_valor','Valor de Notas de Empenho',0),
			('trs','ne_estorno_qnt','Quantidade de Estornos de Notas de Empenho',0),
			('trs','ne_estorno_valor','Valor de Estornos de Notas de Empenho',0),
			('trs','ns_qnt','Quantidade de Notas de Liquidação',0),
			('trs','ns_valor','Valor de Notas de Liquidação',0),
			('trs','ns_estorno_qnt','Quantidade de Estornos de Notas de Liquidação',0),
			('trs','ns_estorno_valor','Valor de Estornos de Notas de Liquidação',0),
			('trs','ob_qnt','Quantidade de Notas de Ordem Bancária',0),
			('trs','ob_valor','Valor de Notas de Ordem Bancária',0),
			('trs','ob_estorn_qnt','Quantidade de Estornos de Notas de Ordem Bancária',0),
			('trs','ob_estorno_valor','Valor de Estornos de Notas de Ordem Bancária',0),
			('trs','gcv_qnt','Quantidade de Guias de Crédito de Verba',0),
			('trs','gcv_valor','Valor de Guias de Crédito de Verba',0),
			('trs','gcv_estorno_qnt','Quantidade de Estornos de Guias de Crédito de Verba',0),
			('trs','gcv_estorno_valor','Valor de Estornos de Guias de Crédito de Verba',0),
			('oss','ne_qnt','Quantidade de Notas de Empenho',0),
			('oss','ne_valor','Valor de Notas de Empenho',0),
			('oss','ne_estorno_qnt','Quantidade de Estornos de Notas de Empenho',0),
			('oss','ne_estorno_valor','Valor de Estornos de Notas de Empenho',0),
			('oss','ns_qnt','Quantidade de Notas de Liquidação',0),
			('oss','ns_valor','Valor de Notas de Liquidação',0),
			('oss','ns_estorno_qnt','Quantidade de Estornos de Notas de Liquidação',0),
			('oss','ns_estorno_valor','Valor de Estornos de Notas de Liquidação',0),
			('oss','ob_qnt','Quantidade de Notas de Ordem Bancária',0),
			('oss','ob_valor','Valor de Notas de Ordem Bancária',0),
			('oss','ob_estorn_qnt','Quantidade de Estornos de Notas de Ordem Bancária',0),
			('oss','ob_estorno_valor','Valor de Estornos de Notas de Ordem Bancária',0),
			('oss','gcv_qnt','Quantidade de Guias de Crédito de Verba',0),
			('oss','gcv_valor','Valor de Guias de Crédito de Verba',0),
			('oss','gcv_estorno_qnt','Quantidade de Estornos de Guias de Crédito de Verba',0),
			('oss','gcv_estorno_valor','Valor de Estornos de Guias de Crédito de Verba',0),
			('instrumentos','ne_qnt','Quantidade de Notas de Empenho',0),
			('instrumentos','ne_valor','Valor de Notas de Empenho',0),
			('instrumentos','ne_estorno_qnt','Quantidade de Estornos de Notas de Empenho',0),
			('instrumentos','ne_estorno_valor','Valor de Estornos de Notas de Empenho',0),
			('instrumentos','ns_qnt','Quantidade de Notas de Liquidação',0),
			('instrumentos','ns_valor','Valor de Notas de Liquidação',0),
			('instrumentos','ns_estorno_qnt','Quantidade de Estornos de Notas de Liquidação',0),
			('instrumentos','ns_estorno_valor','Valor de Estornos de Notas de Liquidação',0),
			('instrumentos','ob_qnt','Quantidade de Notas de Ordem Bancária',0),
			('instrumentos','ob_valor','Valor de Notas de Ordem Bancária',0),
			('instrumentos','ob_estorn_qnt','Quantidade de Estornos de Notas de Ordem Bancária',0),
			('instrumentos','ob_estorno_valor','Valor de Estornos de Notas de Ordem Bancária',0),
			('instrumentos','gcv_qnt','Quantidade de Guias de Crédito de Verba',0),
			('instrumentos','gcv_valor','Valor de Guias de Crédito de Verba',0),
			('instrumentos','gcv_estorno_qnt','Quantidade de Estornos de Guias de Crédito de Verba',0),
			('instrumentos','gcv_estorno_valor','Valor de Estornos de Guias de Crédito de Verba',0);");

		}
	}
	
	 
	
?>
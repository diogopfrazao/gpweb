<?php
global $config, $bd;

$resultado = $bd->Execute("SHOW TABLES LIKE 'financeiro_ob';");
$existe = ($resultado->RecordCount() ? TRUE : FALSE);
if($existe) {
	//checar se é a base para sema instalado
	$resultado = $bd->Execute("SHOW COLUMNS FROM financeiro_ob LIKE 'CD_EXERCICIO'");
	$existe = ($resultado->RecordCount() ? TRUE : FALSE);
	if($existe) {
		$bd->Execute("ALTER TABLE financeiro_ob CHANGE CD_AGENCIA_CREDOR CODG_AGENCIA_CREDOR VARCHAR(4) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob CHANGE CD_BANCO_CREDOR CODG_BANCO_CREDOR VARCHAR(3) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob CHANGE CD_FONTE_RECURSO FONTE_RECURSO VARCHAR(8) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob CHANGE CODG_NUMRCC NUMR_CONTA_BANCARIA VARCHAR(15) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob CHANGE HISTORICO_OBSERVACAO HISTORICO VARCHAR(400) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob CHANGE NUMR_CONTA_CORRENTE_CREDOR CODG_NUMRCC_CREDOR VARCHAR(15) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob CHANGE NUMR_NOB_ESTORNO NUMR_DOCT_ESTORNO_ESTORNADO VARCHAR(25) DEFAULT NULL;");
		
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN CPF VARCHAR(11) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN CNPJ VARCHAR(14) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN CODG_NUMR_DIG_CC_CREDOR VARCHAR(2) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN CODG_CONTA_BANCARIA VARCHAR(15) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN IDEN_CREDOR VARCHAR(10) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN OBSERVACAO VARCHAR(400) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN FLAG_TIPO_PAGAMENTO VARCHAR(100) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN NUMR_ARQUIVO_RETORNO VARCHAR(7) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN SITUACAO VARCHAR(100) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN CD_PAOE VARCHAR(4) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN CDELEMENTODESPESA VARCHAR(3) DEFAULT NULL;");
		$bd->Execute("ALTER TABLE financeiro_ob ADD COLUMN CODG_DOTACAO_ORCAMENTARIA VARCHAR(48) DEFAULT NULL;");
		}
	}
	
	 
	
?>
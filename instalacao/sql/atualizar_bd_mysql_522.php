<?php
global $config, $bd, $Aplic;

if(file_exists(BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php')) {
    if( file_exists( BASE_DIR . '/modulos/projetos/wbs_completo_pro.js' ) ) {
        @unlink( BASE_DIR . '/modulos/projetos/wbs_completo_pro.js' );
    }

    if( file_exists( BASE_DIR . '/lib/extgantt/gnt-all.js' ) ) {
        @unlink( BASE_DIR . '/lib/extgantt/gnt-all.js' );
    }

    if( file_exists( BASE_DIR . '/lib/extgantt/gnt-all-debug.js' ) ) {
        @unlink( BASE_DIR . '/lib/extgantt/gnt-all-debug.js' );
    }

    if( file_exists( BASE_DIR . '/lib/extgantt/gnt-all-orig.js' ) ) {
        @unlink( BASE_DIR . '/lib/extgantt/gnt-all-orig.js' );
    }

    rrmdir_local(BASE_DIR . '/lib/extgantt/resources/css');
    rrmdir_local(BASE_DIR . '/lib/extgantt/resources/images');
}

function rrmdir_local($dir) {
    if (is_dir($dir)){
        $objects = scandir($dir, 0);

        foreach ($objects as $object){
            if ($object !== "." && $object !== "..") {
                if (filetype($dir."/".$object) === "dir"){
                    rrmdir_local($dir."/".$object);
                }
                else{
                    @unlink($dir."/".$object);
                }
            }
        }

        reset($objects);
        @rmdir($dir);
    }
}
?>
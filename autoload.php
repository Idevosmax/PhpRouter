<?php 

spl_autoload_register(function($class) {
    // remove backslashes
    $trimmedName = trim($class, "\/");

    // Explode the name
    $explodedName = explode("\\", $trimmedName);
    if(count($explodedName) > 1){
        if(is_dir( pf.$explodedName[0])) {
            require pf.$explodedName[0].DIR_SP.$explodedName[1].".php";
        }
    } else{
        require pf.$class.".php";
    }

});
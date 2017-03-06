<?php

/**
 * Get variable from $_POST
 * 
 * @param string $variableName
 * @return null|string
 */
function getPost($variableName){
    if(isset($_POST[$variableName])){
        return $_POST[$variableName];
    }
    return NULL;
}


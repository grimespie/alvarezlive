<?php

function DHMR_Image($data) {
    global $_TABLE_IMAGES, $_TABLE_IMAGES_FIELDS;
    
    $Result = table_read($_TABLE_IMAGES, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(prepare_results($Result, $_TABLE_IMAGES_FIELDS));
}

?>
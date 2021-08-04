<?php

function DHMR_Video($data) {
    global $_TABLE_VIDEOS, $_TABLE_VIDEOS_FIELDS;
    
    $Result = table_read($_TABLE_VIDEOS, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(prepare_results($Result, $_TABLE_VIDEOS_FIELDS));
}

?>

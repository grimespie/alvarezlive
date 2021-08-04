<?php

function DHMD_Video($data) {
    global $_TABLE_VIDEOS, $_TABLE_VIDEOS_FIELDS;
    
    $Result = table_delete($_TABLE_VIDEOS, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(true);
}

?>
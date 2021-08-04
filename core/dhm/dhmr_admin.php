<?php

function DHMR_Admin() {
    global $_TABLE_ADMIN, $_TABLE_ADMIN_FIELDS;
    
    $Result = table_read($_TABLE_ADMIN, "1 = 1");
    
    if(!$Result) {
        return(false);
    }
    
    return(prepare_results($Result, $_TABLE_ADMIN_FIELDS));
}

?>
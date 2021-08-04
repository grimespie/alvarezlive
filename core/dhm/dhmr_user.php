<?php

function DHMR_User($data) {
    global $_TABLE_USERS, $_TABLE_USERS_FIELDS;
    
    $Result = table_read($_TABLE_USERS, $data);
    
    if(!$Result) {
        return(false);
    }
    
    return(prepare_results($Result, $_TABLE_USERS_FIELDS));
}

?>